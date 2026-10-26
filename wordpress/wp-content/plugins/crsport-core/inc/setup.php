<?php
/**
 * Contenido inicial: idiomas, páginas ES/EN, menús, caballos de ejemplo e imágenes provisionales.
 *
 * Se lanza desde «CR Sport › Contenido inicial» o con WP-CLI:  wp crsport setup [--textos]
 * Es seguro repetirlo: solo crea lo que falta. Con «--textos» reescribe el contenido de las
 * páginas creadas por el instalador (útil en staging, nunca en producción con cambios hechos a mano).
 */

defined( 'ABSPATH' ) || exit;

require_once CRS_DIR . 'inc/content/blocks.php';
require_once CRS_DIR . 'inc/content/pages.php';
require_once CRS_DIR . 'inc/content/legal.php';

/**
 * Imágenes provisionales (assets/seed). Clave => [archivo, alt ES, alt EN].
 * Las fotos «roi-*», «ruta-*» y «escuela-*» son de la web actual; las «demo-*» son del diseño
 * de Stitch y hay que sustituirlas por fotos reales.
 */
function crs_seed_images(): array {
	return array(
		'hero-home'          => array( 'roi-salto-pista.jpg', 'Roi Calviño saltando en concurso', 'Roi Calviño jumping at a show' ),
		'roi-saludo'         => array( 'roi-saludo.jpg', 'Roi Calviño saludando en pista cubierta', 'Roi Calviño saluting in an indoor arena' ),
		'cecebre'            => array( 'cecebre-embalse-paco-suarez.jpg', 'Atardecer en el Encoro de Cecebre', 'Sunset over the Cecebre reservoir' ),
		'roi-salto'          => array( 'roi-salto-vertical.jpg', 'Roi Calviño en pleno salto', 'Roi Calviño mid-jump' ),
		'hero-caballos'      => array( 'roi-salto-negro.jpg', 'Caballo de salto en concurso', 'Show jumper at a competition' ),
		'hero-escuela'       => array( 'escuela-ruta-bosque.jpg', 'Alumnos de la escuela en una ruta a caballo', 'Riding school pupils on a trail ride' ),
		'escuela-clases'     => array( 'demo-clase.jpg', 'Clase de equitación en pista', 'Riding lesson in the arena' ),
		'escuela-rutas'      => array( 'ruta-encoro.jpg', 'Ruta a caballo a orillas del Encoro de Cecebre', 'Trail ride on the shore of the Cecebre reservoir' ),
		'academy'            => array( 'demo-academy.jpg', 'Joven jinete saltando con su entrenador', 'Young rider jumping with the coach' ),
		'hero-entrenamiento' => array( 'roi-salto-concurso.jpg', 'Salto en concurso', 'Jumping at a show' ),
		'concursos'          => array( 'roi-concurso.jpg', 'Recorrido de concurso de salto', 'Show jumping round' ),
		'roi-salto-2'        => array( 'roi-salto-8.jpg', 'Roi Calviño en concurso', 'Roi Calviño competing' ),
		'hero-pupilaje'      => array( 'demo-cuadras-pasillo.jpg', 'Pasillo de cuadras', 'Stable aisle' ),
		'pupilaje'           => array( 'demo-caballo-box.jpg', 'Caballo en su box', 'Horse in its stable' ),
		'hero-instalaciones' => array( 'demo-pista.jpg', 'Pista de arena de sílice', 'Silica sand arena' ),
		'pista'              => array( 'demo-pista.jpg', 'Pista de arena de sílice', 'Silica sand arena' ),
		'cuadras'            => array( 'demo-cuadras.jpg', 'Cuadras de 3 × 3 m', '3 × 3 m stables' ),
		'preparadores'       => array( 'demo-preparador.jpg', 'Preparador con zona de herraje y veterinario', 'Grooming bay with farrier and vet area' ),
		'hero-roi'           => array( 'roi-salto-tordo.jpg', 'Roi Calviño saltando con un caballo tordo', 'Roi Calviño jumping a grey horse' ),
		'roi-retrato'        => array( 'roi-retrato.jpg', 'Retrato de Roi Calviño', 'Portrait of Roi Calviño' ),
		'caballo-1'          => array( 'demo-caballo-box.jpg', 'Caballo castaño', 'Bay horse' ),
		'caballo-1b'         => array( 'demo-caballo-1b.jpg', 'Caballo castaño de perfil', 'Bay horse in profile' ),
		'caballo-1c'         => array( 'demo-caballo-1c.jpg', 'Detalle de la cabeza', 'Head detail' ),
		'caballo-2'          => array( 'demo-caballo-2.jpg', 'Yegua en el prado', 'Mare in the field' ),
		'caballo-2b'         => array( 'demo-caballo-2b.jpg', 'Retrato de la yegua', 'Portrait of the mare' ),
		'caballo-3'          => array( 'demo-caballo-3.jpg', 'Caballo saltando en concurso', 'Horse jumping at a show' ),
		'caballo-3b'         => array( 'demo-caballo-3b.jpg', 'Caballo en recorrido', 'Horse on course' ),
	);
}

/**
 * Fotos con licencia libre que exigen atribución: clave => [autor, URL de la ficha, licencia, URL de la licencia].
 */
function crs_seed_image_credits(): array {
	return array(
		'cecebre' => array( 'Paco Suarez', 'https://commons.wikimedia.org/wiki/File:Embalse_Cecebre_Puesta_de_sol.jpg', 'CC BY-SA 4.0', 'https://creativecommons.org/licenses/by-sa/4.0/' ),
	);
}

/**
 * Pie de foto con la atribución, en el idioma indicado ('' si la foto no la necesita).
 */
function crs_seed_image_credit( string $key, string $lang = 'es' ): string {
	$c = crs_seed_image_credits()[ $key ] ?? null;
	if ( ! $c ) {
		return '';
	}
	return sprintf(
		'%s <a href="%s" target="_blank" rel="noopener">%s</a> · <a href="%s" target="_blank" rel="noopener">%s</a>',
		'en' === $lang ? 'Photo:' : 'Foto:',
		esc_url( $c[1] ),
		esc_html( $c[0] ),
		esc_url( $c[3] ),
		esc_html( $c[2] )
	);
}

/**
 * Importa (una sola vez por archivo) una imagen provisional y devuelve su ID.
 */
function crs_seed_image_id( string $key ): int {
	static $cache = array();
	$def = crs_seed_images()[ $key ] ?? null;
	if ( ! $def ) {
		return 0;
	}
	$file = $def[0];
	if ( isset( $cache[ $file ] ) ) {
		return $cache[ $file ];
	}

	$existing = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'meta_key'       => '_crs_seed', // phpcs:ignore WordPress.DB.SlowDBQuery
			'meta_value'     => $file, // phpcs:ignore WordPress.DB.SlowDBQuery
			'fields'         => 'ids',
			'posts_per_page' => 1,
			'lang'           => '',
		)
	);
	if ( $existing ) {
		return $cache[ $file ] = (int) $existing[0];
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	// Las fotos con licencia libre no son provisionales: llevan su autor en el título y el pie.
	$credit     = '';
	foreach ( crs_seed_images() as $k => $d ) {
		if ( $d[0] === $file && isset( crs_seed_image_credits()[ $k ] ) ) {
			$credit = crs_seed_image_credits()[ $k ];
		}
	}
	$title = $credit ? $def[1] . ' · ' . $credit[0] . ' (' . $credit[2] . ')' : 'PROVISIONAL · ' . $def[1];

	$tmp = wp_tempnam( $file );
	copy( CRS_DIR . 'assets/seed/' . $file, $tmp );
	$id = media_handle_sideload(
		array(
			'name'     => 'provisional-' . $file,
			'tmp_name' => $tmp,
		),
		0,
		$title
	);
	if ( is_wp_error( $id ) ) {
		wp_delete_file( $tmp );
		return $cache[ $file ] = 0;
	}
	update_post_meta( $id, '_crs_seed', $file );
	update_post_meta( $id, '_wp_attachment_image_alt', $def[1] );
	if ( $credit ) {
		wp_update_post(
			array(
				'ID'           => $id,
				'post_excerpt' => crs_seed_image_credit( (string) $key ),
			)
		);
	}
	return $cache[ $file ] = (int) $id;
}

function crs_has_polylang(): bool {
	return function_exists( 'PLL' ) && function_exists( 'pll_set_post_language' ) && isset( PLL()->model );
}

/**
 * Crea los idiomas ES (por defecto) y EN en Polylang si no existen.
 */
function crs_setup_languages( array &$log ): bool {
	if ( ! crs_has_polylang() ) {
		$log[] = '⚠ Polylang no está activo: se crea solo la versión en español. Activa Polylang y vuelve a ejecutar para crear la versión en inglés.';
		return false;
	}
	$model    = PLL()->model;
	$existing = $model->get_languages_list( array( 'fields' => 'slug' ) );
	$wanted   = array(
		array(
			'name'       => 'Español',
			'slug'       => 'es',
			'locale'     => 'es_ES',
			'rtl'        => false,
			'flag'       => 'es',
			'term_group' => 0,
		),
		array(
			'name'       => 'English',
			'slug'       => 'en',
			'locale'     => 'en_GB',
			'rtl'        => false,
			'flag'       => 'gb',
			'term_group' => 1,
		),
	);
	foreach ( $wanted as $lang ) {
		if ( in_array( $lang['slug'], $existing, true ) ) {
			continue;
		}
		$result = isset( $model->languages ) && method_exists( $model->languages, 'add' )
			? $model->languages->add( $lang )
			: $model->add_language( $lang );
		if ( is_wp_error( $result ) ) {
			$log[] = '✖ No se pudo crear el idioma ' . $lang['name'] . ': ' . $result->get_error_message();
			return false;
		}
		$log[] = '✔ Idioma creado: ' . $lang['name'];
	}
	if ( isset( PLL()->options ) ) {
		PLL()->options['default_lang']  = 'es';
		PLL()->options['redirect_lang'] = true; // Portada en inglés en /en/ (no /en/home/).
		PLL()->options['browser']       = true; // Visitantes con el navegador en inglés llegan a /en/.
		if ( method_exists( PLL()->options, 'save' ) ) {
			PLL()->options->save();
		}
	}
	if ( method_exists( $model, 'clean_languages_cache' ) ) {
		$model->clean_languages_cache();
	}
	return true;
}

/**
 * Instalador completo.
 */
function crs_run_setup( bool $rewrite_texts = false ): array {
	$log = array();
	if ( ! get_option( 'permalink_structure' ) ) {
		update_option( 'permalink_structure', '/%postname%/' );
		$log[] = '✔ Enlaces permanentes: /nombre-de-la-entrada/';
	}

	$bilingual = crs_setup_languages( $log );
	$langs     = $bilingual ? array( 'es', 'en' ) : array( 'es' );

	// 1) Páginas (primero vacías, para tener todas las URL antes de escribir los enlaces internos).
	$pages = get_option( 'crs_pages', array() );
	$ids   = array(); // [clave][idioma] => ID
	foreach ( crs_seed_page_defs() as $key => $def ) {
		foreach ( $langs as $lang ) {
			$id = 0;
			if ( ! empty( $pages[ $key ] ) && get_post( $pages[ $key ] ) ) {
				$id = (int) $pages[ $key ];
				if ( 'en' === $lang ) {
					$id = $bilingual ? (int) pll_get_post( $id, 'en' ) : 0;
				}
			}
			if ( ! $id ) {
				$id = wp_insert_post(
					array(
						'post_type'   => 'page',
						'post_status' => 'publish',
						'post_title'  => 'en' === $lang ? $def[2] : $def[0],
						'post_name'   => 'en' === $lang ? $def[3] : $def[1],
						'post_content' => '',
						'meta_input'  => array( '_crs_seed_page' => $key ),
					)
				);
				if ( $bilingual ) {
					pll_set_post_language( $id, $lang );
				}
				$ids[ $key ]['new'][ $lang ] = true;
				$log[]                       = '✔ Página creada: ' . ( 'en' === $lang ? $def[2] : $def[0] ) . ' (' . strtoupper( $lang ) . ')';
			}
			$ids[ $key ][ $lang ] = (int) $id;
		}
		if ( $bilingual && ! empty( $ids[ $key ]['en'] ) ) {
			pll_save_post_translations(
				array(
					'es' => $ids[ $key ]['es'],
					'en' => $ids[ $key ]['en'],
				)
			);
		}
		$pages[ $key ] = $ids[ $key ]['es'];
	}
	update_option( 'crs_pages', $pages );

	// 2) Contenido de las páginas.
	foreach ( crs_seed_page_defs() as $key => $def ) {
		foreach ( $langs as $lang ) {
			$id    = $ids[ $key ][ $lang ];
			$fresh = ! empty( $ids[ $key ]['new'][ $lang ] );
			if ( ! $fresh && ! $rewrite_texts ) {
				continue;
			}
			$img  = function ( string $k ) use ( $lang ) {
				$def = crs_seed_images()[ $k ] ?? array( '', '', '' );
				return array( crs_seed_image_id( $k ), 'en' === $lang ? $def[2] : $def[1] );
			};
			$url  = function ( string $page ) use ( $ids, $lang ) {
				$target = $ids[ $page ][ $lang ] ?? 0;
				return $target ? get_permalink( $target ) : home_url( '/' );
			};
			$data = crs_seed_page_content( $key, $lang, $img, $url );
			wp_update_post(
				array(
					'ID'           => $id,
					'post_content' => $data['content'],
					'post_excerpt' => $data['excerpt'],
				)
			);
			if ( ! $fresh ) {
				$log[] = '↻ Textos reescritos: ' . get_the_title( $id ) . ' (' . strtoupper( $lang ) . ')';
			}
		}
	}

	// 3) Portada y página de privacidad de WordPress.
	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $ids['inicio']['es'] );
	update_option( 'wp_page_for_privacy_policy', $ids['privacidad']['es'] );

	// 4) Menús.
	crs_setup_menus( $ids, $langs, $bilingual, $log );

	// 5) Caballos de ejemplo.
	crs_setup_horses( $langs, $bilingual, $log );
	crs_setup_competition_horses( $langs, $bilingual, $log );

	// 6) Polylang: sincronizar foto destacada, orden y campos personalizados entre traducciones.
	if ( $bilingual && isset( PLL()->options ) ) {
		$sync = (array) PLL()->options['sync'];
		foreach ( array( 'taxonomies', 'post_meta', '_thumbnail_id', 'menu_order' ) as $s ) {
			if ( ! in_array( $s, $sync, true ) ) {
				$sync[] = $s;
			}
		}
		PLL()->options['sync'] = $sync;
		if ( method_exists( PLL()->options, 'save' ) ) {
			PLL()->options->save();
		}
	}

	// Polylang guarda en caché la portada de cada idioma: se limpia tras cambiar page_on_front.
	if ( $bilingual ) {
		delete_transient( 'pll_languages_list' );
		if ( method_exists( PLL()->model, 'clean_languages_cache' ) ) {
			PLL()->model->clean_languages_cache();
		}
	}

	flush_rewrite_rules();
	update_option( 'crs_setup_version', CRS_VERSION );
	if ( 'crsport' !== get_stylesheet() ) {
		$log[] = '⚠ El tema «CR Sport Horses» no está activo: actívalo en Apariencia › Temas para ver el diseño.';
	}
	$log[] = '✔ Listo. Revisa «CR Sport › Ajustes» para completar email y datos legales.';
	return $log;
}

function crs_setup_menus( array $ids, array $langs, bool $bilingual, array &$log ): void {
	$menus = array(
		'primary' => array( 'caballos', 'escuela', 'entrenamiento', 'pupilaje', 'instalaciones', 'roi', 'contacto' ),
		'footer'  => array( 'escuela', 'entrenamiento', 'pupilaje', 'instalaciones', 'caballos', 'roi' ),
		'legal'   => array( 'aviso_legal', 'privacidad', 'cookies' ),
	);
	$names = array(
		'primary' => 'Menú principal',
		'footer'  => 'Pie · Servicios',
		'legal'   => 'Pie · Legal',
	);

	// Se asignan al tema «crsport» aunque todavía no esté activo (si no, los menús quedarían sin ubicación).
	$theme       = 'crsport';
	$mods        = get_option( 'theme_mods_' . $theme, array() );
	$locations   = (array) ( $mods['nav_menu_locations'] ?? array() );
	$pll_menus   = $bilingual ? (array) PLL()->options['nav_menus'] : array();

	foreach ( $menus as $location => $keys ) {
		foreach ( $langs as $lang ) {
			$name = $names[ $location ] . ' (' . strtoupper( $lang ) . ')';
			$menu = wp_get_nav_menu_object( $name );
			if ( ! $menu ) {
				$menu_id = wp_create_nav_menu( $name );
				foreach ( $keys as $i => $key ) {
					wp_update_nav_menu_item(
						$menu_id,
						0,
						array(
							'menu-item-object-id' => $ids[ $key ][ $lang ],
							'menu-item-object'    => 'page',
							'menu-item-type'      => 'post_type',
							'menu-item-status'    => 'publish',
							'menu-item-position'  => $i + 1,
						)
					);
				}
				$log[] = '✔ Menú creado: ' . $name;
			} else {
				$menu_id = $menu->term_id;
			}
			if ( $bilingual ) {
				$pll_menus[ $theme ][ $location ][ $lang ] = (int) $menu_id;
			}
			if ( 'es' === $lang ) {
				$locations[ $location ] = (int) $menu_id;
			}
		}
	}
	$mods['nav_menu_locations'] = $locations;
	update_option( 'theme_mods_' . $theme, $mods );
	if ( $bilingual ) {
		PLL()->options['nav_menus'] = $pll_menus;
		if ( method_exists( PLL()->options, 'save' ) ) {
			PLL()->options->save();
		}
	}
}

function crs_setup_horses( array $langs, bool $bilingual, array &$log ): void {
	if ( get_option( 'crs_seed_horses' ) ) {
		return;
	}

	// Categorías.
	$cats = array(
		'jovenes'     => array( 'Jóvenes promesas', 'Young prospects', 'young-prospects' ),
		'amateur'     => array( 'Amateur', 'Amateur rides', 'amateur-rides' ),
		'competicion' => array( 'Competición', 'Competition', 'competition' ),
	);
	$term_ids = array();
	foreach ( $cats as $slug => $c ) {
		foreach ( $langs as $lang ) {
			$name   = 'en' === $lang ? $c[1] : $c[0];
			$tslug  = 'en' === $lang ? $c[2] : $slug;
			$exists = get_term_by( 'slug', $tslug, 'tipo_caballo' );
			$tid    = $exists ? $exists->term_id : wp_insert_term( $name, 'tipo_caballo', array( 'slug' => $tslug ) )['term_id'];
			if ( $bilingual ) {
				pll_set_term_language( $tid, $lang );
			}
			$term_ids[ $slug ][ $lang ] = (int) $tid;
		}
		if ( $bilingual ) {
			pll_save_term_translations( $term_ids[ $slug ] );
		}
	}

	$horses = array(
		array(
			'name'   => "Cornet d'Or (ejemplo)",
			'name_en' => "Cornet d'Or (example)",
			'cat'    => 'competicion',
			'images' => array( 'caballo-1', 'caballo-1b', 'caballo-1c' ),
			'meta'   => array( 'nacimiento' => 2017, 'sexo' => 'castrado', 'alzada' => 1.68, 'origen' => 'Cornet Obolensky × Diamant de Semilly', 'estado' => 'disponible', 'destacado' => 1, 'rx' => 1, 'prueba' => 1, 'resultados' => "1º · 1,35 m · CSN3* (ejemplo)\nDoble cero · 1,40 m · CSI2* (ejemplo)", 'p_salto' => 9, 'p_capacidad' => 8, 'p_galope' => 8, 'p_caracter' => 9, 'p_manejo' => 8 ),
			'es'     => array( 'raza' => 'Warmblood Belga (BWP)', 'capa' => 'Castaña', 'nivel' => 'Salta 1,35 m con regularidad', 'potencial' => 'Gran Premio 1,45–1,50 m', 'resumen' => 'Técnica limpia y un galope fácil de regular. Listo para competir a 1,35 m.', 'texto' => 'Caballo de ejemplo para ver cómo queda una ficha. Castrado de gran regularidad, con mucha técnica de manos y un galope amplio y fácil de regular. Muy noble en la cuadra y en el trabajo diario. Ideal para un jinete que quiera consolidarse en pruebas de 1,35 m y seguir subiendo.' ),
			'en'     => array( 'raza' => 'Belgian Warmblood (BWP)', 'capa' => 'Bay', 'nivel' => 'Jumping 1.35 m consistently', 'potencial' => '1.45–1.50 m Grand Prix', 'resumen' => 'Clean technique and an easily adjustable canter. Ready to compete at 1.35 m.', 'texto' => 'Example horse showing how a profile looks. A very consistent gelding with great front-leg technique and a big, adjustable canter. Kind in the stable and in daily work. Ideal for a rider looking to establish themselves at 1.35 m and move up.' ),
		),
		array(
			'name'   => 'Diamant Blue (ejemplo)',
			'name_en' => 'Diamant Blue (example)',
			'cat'    => 'amateur',
			'images' => array( 'caballo-2', 'caballo-2b' ),
			'meta'   => array( 'nacimiento' => 2016, 'sexo' => 'yegua', 'alzada' => 1.70, 'origen' => 'Diamant de Semilly × Heartbreaker', 'estado' => 'reservado', 'destacado' => 1, 'p_salto' => 8, 'p_capacidad' => 7, 'p_galope' => 8, 'p_caracter' => 10, 'p_manejo' => 9 ),
			'es'     => array( 'raza' => 'KWPN', 'capa' => 'Torda', 'nivel' => 'Pruebas de 1,30 m', 'potencial' => '1,40 m · ideal amateur', 'resumen' => 'Yegua generosa y muy segura, pensada para un jinete amateur con ambición.', 'texto' => 'Caballo de ejemplo para ver cómo queda una ficha. Yegua de carácter excepcional, muy segura en pista y fácil de montar. Perfecta para jinetes amateur o jóvenes que buscan confianza para dar el salto de altura.' ),
			'en'     => array( 'raza' => 'KWPN', 'capa' => 'Grey', 'nivel' => '1.30 m classes', 'potencial' => '1.40 m · ideal amateur ride', 'resumen' => 'A generous, very safe mare for an ambitious amateur rider.', 'texto' => 'Example horse showing how a profile looks. A mare with an exceptional temperament, very safe in the ring and easy to ride. Perfect for amateur or young riders looking for the confidence to move up a level.' ),
		),
		array(
			'name'   => 'Kallisto CR (ejemplo)',
			'name_en' => 'Kallisto CR (example)',
			'cat'    => 'jovenes',
			'images' => array( 'caballo-3', 'caballo-3b' ),
			'meta'   => array( 'nacimiento' => 2020, 'sexo' => 'macho', 'alzada' => 1.66, 'origen' => 'Kannan × Quidam de Revel', 'estado' => 'disponible', 'destacado' => 1, 'p_salto' => 8, 'p_capacidad' => 9, 'p_galope' => 9, 'p_caracter' => 8, 'p_manejo' => 7 ),
			'es'     => array( 'raza' => 'Caballo de Deporte Español (CDE)', 'capa' => 'Alazana', 'nivel' => 'Ciclos clásicos de 6 años', 'potencial' => 'Gran Premio', 'resumen' => 'Joven con mucha capacidad y un galope de calidad. Un proyecto con futuro.', 'texto' => 'Caballo de ejemplo para ver cómo queda una ficha. Joven caballo con mucha capacidad y un galope de gran calidad, compitiendo en los ciclos clásicos. Un proyecto con proyección para Gran Premio.' ),
			'en'     => array( 'raza' => 'Spanish Sport Horse (CDE)', 'capa' => 'Chestnut', 'nivel' => 'Young horse classes (6-year-olds)', 'potencial' => 'Grand Prix', 'resumen' => 'A young horse with plenty of scope and a quality canter. A real prospect.', 'texto' => 'Example horse showing how a profile looks. A young horse with plenty of scope and a top-quality canter, competing in the national young horse series. A prospect with Grand Prix potential.' ),
		),
	);

	foreach ( $horses as $order => $horse ) {
		$ids     = array();
		$gallery = array_map( 'crs_seed_image_id', $horse['images'] );
		foreach ( $langs as $lang ) {
			$txt = $horse[ $lang ];
			$id  = wp_insert_post(
				array(
					'post_type'    => 'caballo',
					'post_status'  => 'publish',
					'post_title'   => 'en' === $lang ? $horse['name_en'] : $horse['name'],
					'post_content' => crs_p( $txt['texto'] ),
					'post_excerpt' => $txt['resumen'],
					'menu_order'   => $order,
				)
			);
			if ( $bilingual ) {
				pll_set_post_language( $id, $lang );
			}
			foreach ( $horse['meta'] + array_intersect_key( $txt, array_flip( array( 'raza', 'capa', 'nivel', 'potencial' ) ) ) as $k => $v ) {
				update_post_meta( $id, '_crs_' . $k, $v );
			}
			update_post_meta( $id, '_crs_galeria', implode( ',', array_slice( $gallery, 1 ) ) );
			update_post_meta( $id, '_crs_horsetelex', 'https://www.horsetelex.es/' );
			set_post_thumbnail( $id, $gallery[0] );
			wp_set_object_terms( $id, array( $term_ids[ $horse['cat'] ][ $lang ] ), 'tipo_caballo' );
			$ids[ $lang ] = $id;
		}
		if ( $bilingual ) {
			pll_save_post_translations( $ids );
		}
		$log[] = '✔ Caballo de ejemplo: ' . $horse['name'];
	}
	update_option( 'crs_seed_horses', 1 );
}

/**
 * Caballos con los que Roi compite en 2026 según la FEI (no están en venta). Sin fotos todavía:
 * se muestran como tarjetas de texto hasta que se les añada una foto principal.
 */
function crs_setup_competition_horses( array $langs, bool $bilingual, array &$log ): void {
	if ( get_option( 'crs_seed_competition' ) ) {
		return;
	}
	foreach ( array( 'Morris', 'Intouch Four Flags', 'Allablanka Z' ) as $order => $name ) {
		$ids = array();
		foreach ( $langs as $lang ) {
			$id = wp_insert_post(
				array(
					'post_type'    => 'caballo',
					'post_status'  => 'publish',
					'post_title'   => $name,
					'post_name'    => sanitize_title( $name ) . ( 'en' === $lang ? '-en' : '' ),
					'post_content' => crs_p( 'en' === $lang ? 'One of the horses Roi Calviño is competing with at national and international shows in the 2026 season.' : 'Uno de los caballos con los que Roi Calviño compite en concursos nacionales e internacionales en la temporada 2026.' ),
					'menu_order'   => $order,
				)
			);
			if ( $bilingual ) {
				pll_set_post_language( $id, $lang );
			}
			update_post_meta( $id, '_crs_estado', 'competicion' );
			$ids[ $lang ] = $id;
		}
		if ( $bilingual ) {
			pll_save_post_translations( $ids );
		}
		$log[] = '✔ Caballo de competición: ' . $name;
	}
	update_option( 'crs_seed_competition', 1 );
}

/* ---------------------------------------------------------------------------
 * Página de administración y WP-CLI
 * ------------------------------------------------------------------------- */

add_action(
	'admin_menu',
	function () {
		add_submenu_page( 'crs', 'Contenido inicial · CR Sport', 'Contenido inicial', 'manage_options', 'crs-setup', 'crs_render_setup_page' );
	},
	20
);

function crs_render_setup_page(): void {
	$log = array();
	if ( isset( $_POST['crs_setup'] ) && check_admin_referer( 'crs_setup' ) ) {
		$log = crs_run_setup( ! empty( $_POST['crs_textos'] ) );
	}
	$done = get_option( 'crs_setup_version' );
	?>
	<div class="wrap">
		<h1>Contenido inicial</h1>
		<p>Crea en un clic la estructura completa de la web: idiomas español e inglés (Polylang), las páginas con sus textos, los menús, tres caballos de ejemplo y las imágenes provisionales (marcadas como <strong>PROVISIONAL</strong> en la biblioteca de medios).</p>
		<p>Es seguro repetirlo: solo crea lo que falta y nunca borra nada.</p>
		<?php if ( $done ) : ?>
			<div class="notice notice-info inline"><p>El contenido inicial ya se creó (versión <?php echo esc_html( $done ); ?>).</p></div>
		<?php endif; ?>
		<?php if ( $log ) : ?>
			<div class="notice notice-success inline"><p><?php echo implode( '<br>', array_map( 'esc_html', $log ) ); ?></p></div>
		<?php endif; ?>
		<form method="post">
			<?php wp_nonce_field( 'crs_setup' ); ?>
			<p><label><input type="checkbox" name="crs_textos" value="1"> Reescribir también los textos de las páginas ya creadas <strong>(se pierden los cambios hechos a mano en ellas)</strong></label></p>
			<?php submit_button( $done ? 'Completar / actualizar contenido' : 'Crear contenido inicial', 'primary', 'crs_setup' ); ?>
		</form>
	</div>
	<?php
}

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	/**
	 * Crea el contenido inicial de CR Sport Horses.
	 *
	 * ## OPTIONS
	 *
	 * [--textos]
	 * : Reescribe también los textos de las páginas ya creadas.
	 */
	WP_CLI::add_command(
		'crsport setup',
		function ( $args, $assoc ) {
			foreach ( crs_run_setup( ! empty( $assoc['textos'] ) ) as $line ) {
				WP_CLI::log( $line );
			}
		}
	);
}
