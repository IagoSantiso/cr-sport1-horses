<?php
/**
 * Caballos en venta: tipo de contenido, categorías, ficha técnica y valoración CR.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Criterios de la valoración CR (1–10). Las etiquetas están en inc/strings.php (score_<clave>).
 */
function crs_score_criteria(): array {
	return array( 'salto', 'capacidad', 'galope', 'caracter', 'manejo' );
}

/**
 * Estados posibles de un caballo. «competicion» = caballo de Roi que no está en venta.
 */
function crs_horse_states(): array {
	return array( 'disponible', 'reservado', 'vendido', 'competicion' );
}

/**
 * Campos de la ficha: clave => [tipo, se sincroniza entre idiomas].
 * Los campos sincronizados se copian automáticamente a la traducción (Polylang).
 */
function crs_horse_fields(): array {
	$fields = array(
		'nacimiento' => array( 'integer', true ),
		'sexo'       => array( 'string', true ),
		'raza'       => array( 'string', false ),
		'capa'       => array( 'string', false ),
		'alzada'     => array( 'number', true ),
		'origen'     => array( 'string', true ),
		'nivel'      => array( 'string', false ),
		'potencial'  => array( 'string', false ),
		'estado'     => array( 'string', true ),
		'horsetelex' => array( 'string', true ),
		'galeria'    => array( 'string', true ),
		'videos'     => array( 'string', true ),
		'destacado'  => array( 'boolean', true ),
		'rx'         => array( 'boolean', true ),
		'prueba'     => array( 'boolean', true ),
		'resultados' => array( 'string', true ),
	);
	foreach ( crs_score_criteria() as $criterion ) {
		$fields[ 'p_' . $criterion ] = array( 'integer', true );
	}
	return $fields;
}

add_action( 'init', 'crs_register_horse_types' );

function crs_register_horse_types(): void {
	register_post_type(
		'caballo',
		array(
			'labels'        => array(
				'name'               => 'Caballos',
				'singular_name'      => 'Caballo',
				'menu_name'          => 'Caballos en venta',
				'add_new'            => 'Añadir caballo',
				'add_new_item'       => 'Añadir caballo',
				'edit_item'          => 'Editar caballo',
				'new_item'           => 'Nuevo caballo',
				'view_item'          => 'Ver ficha',
				'search_items'       => 'Buscar caballos',
				'not_found'          => 'No hay caballos',
				'not_found_in_trash' => 'No hay caballos en la papelera',
				'all_items'          => 'Todos los caballos',
				'featured_image'     => 'Foto principal',
				'set_featured_image' => 'Elegir foto principal',
			),
			'public'        => true,
			'has_archive'   => false,
			'rewrite'       => array(
				'slug'       => 'caballos',
				'with_front' => false,
			),
			'menu_icon'     => 'dashicons-star-filled',
			'menu_position' => 25,
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'page-attributes' ),
			'show_in_rest'  => true,
			'template'      => array(
				array( 'core/paragraph', array( 'placeholder' => 'Descripción del caballo: carácter, trabajo actual, resultados, para qué jinete es ideal…' ) ),
			),
		)
	);

	register_taxonomy(
		'tipo_caballo',
		'caballo',
		array(
			'labels'            => array(
				'name'          => 'Categorías de caballo',
				'singular_name' => 'Categoría',
				'menu_name'     => 'Categorías',
				'add_new_item'  => 'Añadir categoría',
			),
			'public'            => false,
			'show_ui'           => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'hierarchical'      => true,
			'rewrite'           => false,
		)
	);

	foreach ( crs_horse_fields() as $key => $field ) {
		register_post_meta(
			'caballo',
			'_crs_' . $key,
			array(
				'type'          => $field[0],
				'single'        => true,
				'show_in_rest'  => true,
				'auth_callback' => fn() => current_user_can( 'edit_posts' ),
			)
		);
	}
}

/* La ficha es sobre todo un formulario: se edita en la pantalla clásica, con todos los campos a la vista. */
add_filter( 'use_block_editor_for_post_type', fn( $use, $type ) => 'caballo' === $type ? false : $use, 10, 2 );

/* Polylang: caballos y categorías traducibles; los datos técnicos se sincronizan. */
add_filter(
	'pll_get_post_types',
	function ( $types ) {
		$types['caballo'] = 'caballo';
		return $types;
	}
);
add_filter(
	'pll_get_taxonomies',
	function ( $taxonomies ) {
		$taxonomies['tipo_caballo'] = 'tipo_caballo';
		return $taxonomies;
	}
);
add_filter(
	'pll_copy_post_metas',
	function ( $keys ) {
		foreach ( crs_horse_fields() as $key => $field ) {
			if ( $field[1] ) {
				$keys[] = '_crs_' . $key;
			}
		}
		return $keys;
	}
);

/**
 * Todos los datos de un caballo, listos para pintar.
 */
function crs_horse( $post = null ): array {
	$post = get_post( $post );
	$m    = fn( $key ) => get_post_meta( $post->ID, '_crs_' . $key, true );

	$year = (int) $m( 'nacimiento' );
	$age  = $year ? max( 0, (int) current_time( 'Y' ) - $year ) : 0;

	$alzada = (float) $m( 'alzada' );
	$scores = array();
	foreach ( crs_score_criteria() as $criterion ) {
		$value = (int) $m( 'p_' . $criterion );
		if ( $value > 0 ) {
			$scores[ $criterion ] = min( 10, $value );
		}
	}

	$gallery = array_filter( array_map( 'absint', explode( ',', (string) $m( 'galeria' ) ) ) );
	$thumb   = get_post_thumbnail_id( $post );
	if ( $thumb && ! in_array( $thumb, $gallery, true ) ) {
		array_unshift( $gallery, $thumb );
	}

	$estado = $m( 'estado' ) ?: 'disponible';
	$sexo   = (string) $m( 'sexo' );

	return array(
		'id'         => $post->ID,
		'nombre'     => get_the_title( $post ),
		'url'        => get_permalink( $post ),
		'nacimiento' => $year,
		'edad'       => $age,
		'edad_txt'   => $age ? sprintf( crs_t( 1 === $age ? 'age_year' : 'age_years' ), $age ) : '',
		'sexo'       => $sexo,
		'sexo_txt'   => $sexo ? crs_t( 'sex_' . $sexo ) : '',
		'raza'       => (string) $m( 'raza' ),
		'capa'       => (string) $m( 'capa' ),
		'alzada'     => $alzada,
		'alzada_txt' => $alzada ? number_format_i18n( $alzada, 2 ) . ' m' : '',
		'origen'     => (string) $m( 'origen' ),
		'nivel'      => (string) $m( 'nivel' ),
		'potencial'  => (string) $m( 'potencial' ),
		'estado'     => $estado,
		'estado_txt' => crs_t( 'status_' . $estado ),
		'horsetelex' => (string) $m( 'horsetelex' ),
		'galeria'    => array_values( $gallery ),
		'videos'     => array_values( array_filter( array_map( 'trim', preg_split( '/\R/', (string) $m( 'videos' ) ) ) ) ),
		'puntuacion' => $scores,
		'media'      => $scores ? round( array_sum( $scores ) / count( $scores ), 1 ) : 0,
		'categorias' => wp_get_post_terms( $post->ID, 'tipo_caballo' ),
		'resumen'    => has_excerpt( $post ) ? get_the_excerpt( $post ) : '',
		'rx'         => (bool) $m( 'rx' ),
		'prueba'     => (bool) $m( 'prueba' ),
		'resultados' => array_values( array_filter( array_map( 'trim', preg_split( '/\R/', (string) $m( 'resultados' ) ) ) ) ),
	);
}

/**
 * Convierte un enlace de YouTube, Vimeo o Instagram en su URL de reproductor integrado.
 * Devuelve [proveedor, url] o [] si no se reconoce.
 */
function crs_video_embed( string $url ): array {
	if ( preg_match( '~(?:youtube\.com/(?:watch\?v=|shorts/|embed/)|youtu\.be/)([\w-]{11})~', $url, $m ) ) {
		return array( 'youtube', 'https://www.youtube-nocookie.com/embed/' . $m[1] . '?autoplay=1&rel=0&playsinline=1' );
	}
	if ( preg_match( '~vimeo\.com/(?:video/)?(\d+)~', $url, $m ) ) {
		return array( 'vimeo', 'https://player.vimeo.com/video/' . $m[1] . '?autoplay=1&dnt=1' );
	}
	if ( preg_match( '~instagram\.com/(?:[\w.]+/)?(p|reel|reels|tv)/([\w-]+)~', $url, $m ) ) {
		$type = 'reels' === $m[1] ? 'reel' : $m[1];
		return array( 'instagram', 'https://www.instagram.com/' . $type . '/' . $m[2] . '/embed/' );
	}
	return array();
}

/**
 * Línea «edad · raza · capa · alzada» de la ficha.
 */
function crs_horse_meta_line( array $h ): array {
	return array_values( array_filter( array( $h['edad_txt'], $h['raza'], $h['capa'], $h['alzada_txt'] ) ) );
}

/* ---------------------------------------------------------------------------
 * Ficha del caballo en el editor
 * ------------------------------------------------------------------------- */

add_action(
	'add_meta_boxes_caballo',
	function () {
		add_meta_box( 'crs-horse', 'Ficha del caballo', 'crs_render_horse_metabox', 'caballo', 'normal', 'high' );
	}
);

add_action(
	'admin_enqueue_scripts',
	function ( $hook ) {
		$screen = get_current_screen();
		if ( ! $screen || 'caballo' !== $screen->post_type || ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
			return;
		}
		wp_enqueue_media();
		wp_enqueue_style( 'crs-admin', CRS_URL . 'assets/admin/admin.css', array(), CRS_VERSION );
		wp_enqueue_script( 'crs-admin', CRS_URL . 'assets/admin/admin.js', array( 'jquery', 'jquery-ui-sortable' ), CRS_VERSION, true );
	}
);

function crs_render_horse_metabox( WP_Post $post ): void {
	wp_nonce_field( 'crs_horse_save', 'crs_horse_nonce' );
	$v       = fn( $key ) => get_post_meta( $post->ID, '_crs_' . $key, true );
	$gallery = array_filter( array_map( 'absint', explode( ',', (string) $v( 'galeria' ) ) ) );
	$lang    = crs_post_lang( $post->ID );
	?>
	<div class="crs-mb">
		<p class="crs-mb__intro">La <strong>foto principal</strong> (columna derecha) es la que aparece en el listado. El nombre va en el título y la descripción en el editor de arriba. Los datos marcados con <span class="crs-sync">⇄</span> se copian solos a la versión en el otro idioma.</p>

		<h3>Datos clave</h3>
		<div class="crs-mb__grid">
			<label>Año de nacimiento <span class="crs-sync">⇄</span>
				<input type="number" name="crs[nacimiento]" min="1990" max="<?php echo esc_attr( current_time( 'Y' ) ); ?>" value="<?php echo esc_attr( $v( 'nacimiento' ) ); ?>" placeholder="2017">
				<small>La edad se calcula sola cada año.</small>
			</label>
			<label>Sexo <span class="crs-sync">⇄</span>
				<select name="crs[sexo]">
					<option value="">—</option>
					<?php foreach ( array( 'yegua', 'macho', 'castrado' ) as $sexo ) : ?>
						<option value="<?php echo esc_attr( $sexo ); ?>" <?php selected( $v( 'sexo' ), $sexo ); ?>><?php echo esc_html( crs_t( 'sex_' . $sexo, 'es' ) ); ?></option>
					<?php endforeach; ?>
				</select>
			</label>
			<label>Raza / libro genealógico
				<input type="text" name="crs[raza]" value="<?php echo esc_attr( $v( 'raza' ) ); ?>" placeholder="<?php echo 'en' === $lang ? 'Belgian Warmblood (BWP)' : 'Warmblood Belga (BWP)'; ?>">
			</label>
			<label>Capa
				<input type="text" name="crs[capa]" value="<?php echo esc_attr( $v( 'capa' ) ); ?>" placeholder="<?php echo 'en' === $lang ? 'Bay' : 'Castaña'; ?>">
			</label>
			<label>Alzada (metros) <span class="crs-sync">⇄</span>
				<input type="number" step="0.01" min="1" max="2" name="crs[alzada]" value="<?php echo esc_attr( $v( 'alzada' ) ); ?>" placeholder="1,68">
			</label>
			<label>Estado <span class="crs-sync">⇄</span>
				<select name="crs[estado]">
					<?php foreach ( crs_horse_states() as $estado ) : ?>
						<option value="<?php echo esc_attr( $estado ); ?>" <?php selected( $v( 'estado' ) ?: 'disponible', $estado ); ?>><?php echo esc_html( crs_t( 'status_' . $estado, 'es' ) ); ?></option>
					<?php endforeach; ?>
				</select>
				<small>Vendidos: salen del listado y su ficha sigue accesible. Competición: caballos de Roi que no están en venta (salen en su página).</small>
			</label>
		</div>

		<h3>Descripción técnica</h3>
		<div class="crs-mb__grid crs-mb__grid--wide">
			<label>Origen (padre × madre) <span class="crs-sync">⇄</span>
				<input type="text" name="crs[origen]" value="<?php echo esc_attr( $v( 'origen' ) ); ?>" placeholder="Cornet Obolensky × Diamant de Semilly">
			</label>
			<label>Nivel actual
				<input type="text" name="crs[nivel]" value="<?php echo esc_attr( $v( 'nivel' ) ); ?>" placeholder="<?php echo 'en' === $lang ? 'Jumping 1.35 m with clear rounds' : 'Salta 1,35 m con recorridos limpios'; ?>">
			</label>
			<label>Potencial
				<input type="text" name="crs[potencial]" value="<?php echo esc_attr( $v( 'potencial' ) ); ?>" placeholder="<?php echo 'en' === $lang ? '1.45–1.50 m Grand Prix' : 'Gran Premio 1,45–1,50 m'; ?>">
			</label>
			<label>Ficha en HorseTelex (URL) <span class="crs-sync">⇄</span>
				<input type="url" name="crs[horsetelex]" value="<?php echo esc_attr( $v( 'horsetelex' ) ); ?>" placeholder="https://www.horsetelex.es/horses/pedigree/…">
			</label>
		</div>

		<h3>Valoración CR <small>(1 = bajo · 10 = excepcional · vacío = no se muestra)</small> <span class="crs-sync">⇄</span></h3>
		<div class="crs-mb__scores">
			<?php foreach ( crs_score_criteria() as $criterion ) : ?>
				<?php $score = (int) $v( 'p_' . $criterion ); ?>
				<label class="crs-score">
					<span><?php echo esc_html( crs_t( 'score_' . $criterion, 'es' ) ); ?></span>
					<input type="range" min="0" max="10" step="1" name="crs[p_<?php echo esc_attr( $criterion ); ?>]" value="<?php echo esc_attr( $score ); ?>">
					<output><?php echo $score ? esc_html( $score ) : '—'; ?></output>
				</label>
			<?php endforeach; ?>
		</div>

		<h3>Galería de fotos y vídeos <span class="crs-sync">⇄</span></h3>
		<p class="description">Sube fotos y vídeos MP4 (máx. ~60 s, comprimidos). Arrastra para ordenar; el primero de la galería se muestra tras la foto principal.</p>
		<ul class="crs-gallery" data-crs-gallery>
			<?php foreach ( $gallery as $id ) : ?>
				<?php echo crs_admin_gallery_item( $id ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<?php endforeach; ?>
		</ul>
		<input type="hidden" name="crs[galeria]" value="<?php echo esc_attr( implode( ',', $gallery ) ); ?>" data-crs-gallery-input>
		<p><button type="button" class="button" data-crs-gallery-add>Añadir fotos o vídeos</button></p>

		<h3>Vídeos externos <span class="crs-sync">⇄</span></h3>
		<label class="crs-mb__full">
			<textarea name="crs[videos]" rows="3" placeholder="https://www.youtube.com/watch?v=…&#10;https://vimeo.com/…&#10;https://www.instagram.com/reel/…"><?php echo esc_textarea( $v( 'videos' ) ); ?></textarea>
			<small>Un enlace por línea: YouTube, Vimeo o una publicación/reel de Instagram. Se cargan solo cuando el visitante pulsa «reproducir» (sin cookies previas).</small>
		</label>

		<h3>Para el comprador <span class="crs-sync">⇄</span></h3>
		<label class="crs-mb__check">
			<input type="checkbox" name="crs[rx]" value="1" <?php checked( (bool) $v( 'rx' ) ); ?>>
			Radiografías disponibles
		</label>
		<label class="crs-mb__check">
			<input type="checkbox" name="crs[prueba]" value="1" <?php checked( (bool) $v( 'prueba' ) ); ?>>
			Disponible para prueba en nuestras instalaciones
		</label>
		<label class="crs-mb__full" style="margin-top:1em">Resultados destacados
			<textarea name="crs[resultados]" rows="3" placeholder="1º · 1,35 m · CSN3* Casas Novas 2026&#10;Doble cero · 1,40 m · CSI2* Esposende 2026"><?php echo esc_textarea( $v( 'resultados' ) ); ?></textarea>
			<small>Uno por línea. Se muestran en la ficha.</small>
		</label>

		<label class="crs-mb__check">
			<input type="checkbox" name="crs[destacado]" value="1" <?php checked( (bool) $v( 'destacado' ) ); ?>>
			Destacar en la página de inicio <span class="crs-sync">⇄</span>
		</label>
	</div>
	<?php
}

function crs_admin_gallery_item( int $id ): string {
	$is_video = str_starts_with( (string) get_post_mime_type( $id ), 'video' );
	$thumb    = $is_video ? '<span class="dashicons dashicons-video-alt3"></span>' : wp_get_attachment_image( $id, 'thumbnail' );
	return sprintf(
		'<li data-id="%1$d">%2$s<button type="button" class="crs-gallery__remove" aria-label="Quitar">×</button></li>',
		$id,
		$thumb
	);
}

add_action(
	'save_post_caballo',
	function ( $post_id ) {
		if ( ! isset( $_POST['crs_horse_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['crs_horse_nonce'] ), 'crs_horse_save' ) ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		$data = isset( $_POST['crs'] ) ? wp_unslash( (array) $_POST['crs'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput

		foreach ( crs_horse_fields() as $key => $field ) {
			$raw = $data[ $key ] ?? '';
			switch ( 'integer' === $field[0] ? 'integer' : $key ) {
				case 'integer':
					$value = absint( $raw );
					break;
				case 'alzada':
					$value = $raw === '' ? '' : round( (float) str_replace( ',', '.', $raw ), 2 );
					break;
				case 'horsetelex':
					$value = esc_url_raw( $raw );
					break;
				case 'galeria':
					$value = implode( ',', array_filter( array_map( 'absint', explode( ',', $raw ) ) ) );
					break;
				case 'videos':
					$value = implode( "\n", array_filter( array_map( 'esc_url_raw', array_map( 'trim', preg_split( '/\R/', $raw ) ) ) ) );
					break;
				case 'estado':
					$value = in_array( $raw, crs_horse_states(), true ) ? $raw : 'disponible';
					break;
				case 'sexo':
					$value = in_array( $raw, array( 'yegua', 'macho', 'castrado' ), true ) ? $raw : '';
					break;
				case 'destacado':
				case 'rx':
				case 'prueba':
					$value = $raw ? 1 : 0;
					break;
				case 'resultados':
					$value = sanitize_textarea_field( $raw );
					break;
				default:
					$value = sanitize_text_field( $raw );
			}
			if ( '' === $value || 0 === $value ) {
				delete_post_meta( $post_id, '_crs_' . $key );
			} else {
				update_post_meta( $post_id, '_crs_' . $key, $value );
			}
		}
	}
);

/* Columnas del listado de caballos en el admin. */
add_filter(
	'manage_caballo_posts_columns',
	function ( $columns ) {
		$new = array();
		foreach ( $columns as $key => $label ) {
			if ( 'title' === $key ) {
				$new['crs_foto'] = '';
			}
			$new[ $key ] = $label;
			if ( 'title' === $key ) {
				$new['crs_estado'] = 'Estado';
				$new['crs_datos']  = 'Datos';
			}
		}
		return $new;
	}
);

add_action(
	'manage_caballo_posts_custom_column',
	function ( $column, $post_id ) {
		$h = crs_horse( $post_id );
		if ( 'crs_foto' === $column ) {
			echo get_the_post_thumbnail( $post_id, array( 56, 56 ) );
		} elseif ( 'crs_estado' === $column ) {
			printf( '<span class="crs-status crs-status--%s">%s</span>', esc_attr( $h['estado'] ), esc_html( crs_t( 'status_' . $h['estado'], 'es' ) ) );
		} elseif ( 'crs_datos' === $column ) {
			echo esc_html( implode( ' · ', crs_horse_meta_line( $h ) ) );
		}
	},
	10,
	2
);

add_action(
	'admin_head',
	function () {
		echo '<style>.column-crs_foto{width:64px}.column-crs_foto img{border-radius:4px;object-fit:cover}.crs-status{padding:2px 8px;border-radius:10px;font-size:12px;background:#e7f5ea;color:#1e6b32}.crs-status--reservado{background:#fff4dc;color:#8a5a00}.crs-status--vendido{background:#eee;color:#555}.crs-status--competicion{background:#e6edf8;color:#0f2d59}</style>';
	}
);

/**
 * Consulta de caballos para listados.
 */
function crs_query_horses( array $args = array() ): WP_Query {
	$defaults = array(
		'post_type'      => 'caballo',
		'posts_per_page' => -1,
		'orderby'        => array(
			'menu_order' => 'ASC',
			'date'       => 'DESC',
		),
		'meta_query'     => array(
			'relation' => 'OR',
			array(
				'key'     => '_crs_estado',
				'compare' => 'NOT EXISTS',
			),
			array(
				'key'     => '_crs_estado',
				'value'   => array( 'disponible', 'reservado' ),
				'compare' => 'IN',
			),
		),
	);
	return new WP_Query( array_merge( $defaults, $args ) );
}
