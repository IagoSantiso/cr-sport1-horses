<?php
/**
 * Piezas dinámicas que se insertan en las páginas con un bloque «Código corto»:
 *
 * [crsport_caballos limite="3" filtros="si" destacados="si"]  Listado de caballos en venta.
 * [crsport_caballos estado="competicion"]                   Caballos de competición de Roi (no están en venta).
 * [crsport_consulta interes="pupilaje"]                        Bloque «Consultar»: texto + teléfono + WhatsApp + formulario.
 * [crsport_formulario interes="…"]                             Solo el formulario (inc/form.php).
 * [crsport_contacto]                                           Datos de contacto.
 * [crsport_telefono]                                           Teléfono enlazado, para usar dentro de un texto.
 * [crsport_instagram]                                          Feed de Instagram (Smash Balloon) o enlace al perfil.
 * [crsport_mapa]                                               Mapa de Google que solo se carga al pulsar.
 * [crsport_dato campo="nif"]                                   Un dato de Ajustes (textos legales).
 */

defined( 'ABSPATH' ) || exit;

/* ---------------------------------------------------------------------------
 * Caballos
 * ------------------------------------------------------------------------- */

function crs_horse_card( $post, string $heading = 'h3' ): string {
	$h     = crs_horse( $post );
	$cats  = wp_list_pluck( $h['categorias'], 'slug' );
	$meta  = crs_horse_meta_line( $h );
	$image = get_post_thumbnail_id( $h['id'] ) ?: ( $h['galeria'][0] ?? 0 );

	ob_start();
	?>
	<article class="crs-horse-card is-<?php echo esc_attr( $h['estado'] ); ?><?php echo $image ? '' : ' has-no-image'; ?>" data-cats="<?php echo esc_attr( implode( ' ', $cats ) ); ?>">
		<?php if ( $image || 'competicion' !== $h['estado'] ) : ?>
		<a class="crs-horse-card__media" href="<?php echo esc_url( $h['url'] ); ?>" tabindex="-1" aria-hidden="true">
			<?php
			echo $image
				? wp_get_attachment_image( $image, 'crs-card', false, array( 'loading' => 'lazy', 'sizes' => '(min-width: 1024px) 33vw, (min-width: 640px) 50vw, 100vw' ) )
				: '<span class="crs-horse-card__noimg"></span>';
			?>
			<?php if ( ! in_array( $h['estado'], array( 'disponible', 'competicion' ), true ) ) : ?>
				<span class="crs-badge crs-badge--<?php echo esc_attr( $h['estado'] ); ?>"><?php echo esc_html( $h['estado_txt'] ); ?></span>
			<?php endif; ?>
			<?php if ( $h['nivel'] ) : ?>
				<span class="crs-horse-card__level"><?php echo esc_html( $h['nivel'] ); ?></span>
			<?php endif; ?>
		</a>
		<?php endif; ?>
		<div class="crs-horse-card__body">
			<?php if ( 'competicion' === $h['estado'] ) : ?>
				<p class="crs-horse-card__kicker"><?php echo esc_html( $h['estado_txt'] ); ?></p>
			<?php endif; ?>
			<<?php echo tag_escape( $heading ); ?> class="crs-horse-card__name"><a href="<?php echo esc_url( $h['url'] ); ?>"><?php echo esc_html( $h['nombre'] ); ?></a></<?php echo tag_escape( $heading ); ?>>
			<?php if ( $meta ) : ?>
				<p class="crs-meta-line"><?php echo esc_html( implode( ' · ', $meta ) ); ?></p>
			<?php endif; ?>
			<?php if ( $h['resumen'] ) : ?>
				<p class="crs-horse-card__excerpt"><?php echo esc_html( $h['resumen'] ); ?></p>
			<?php endif; ?>
			<span class="crs-link-arrow" aria-hidden="true"><?php crs_e( 'competicion' === $h['estado'] ? 'view_profile' : 'view_horse' ); ?></span>
		</div>
	</article>
	<?php
	return (string) ob_get_clean();
}

add_shortcode(
	'crsport_caballos',
	function ( $atts ) {
		$atts = shortcode_atts(
			array(
				'limite'     => -1,
				'filtros'    => 'no',
				'destacados' => 'no',
				'estado'     => '',
			),
			$atts
		);

		$args = array( 'posts_per_page' => (int) $atts['limite'] );
		if ( 'competicion' === $atts['estado'] ) {
			// Caballos de competición de Roi: no están en venta y no muestran aviso si no hay ninguno.
			$query = crs_query_horses(
				$args + array(
					'meta_query' => array( // phpcs:ignore WordPress.DB.SlowDBQuery
						array(
							'key'   => '_crs_estado',
							'value' => 'competicion',
						),
					),
				)
			);
			if ( ! $query->have_posts() ) {
				return '';
			}
		} elseif ( 'si' === $atts['destacados'] ) {
			$featured = crs_query_horses(
				$args + array(
					'meta_key'   => '_crs_destacado', // phpcs:ignore WordPress.DB.SlowDBQuery
					'meta_value' => '1', // phpcs:ignore WordPress.DB.SlowDBQuery
				)
			);
			$query    = $featured->have_posts() ? $featured : crs_query_horses( $args );
		} else {
			$query = crs_query_horses( $args );
		}

		if ( ! $query->have_posts() ) {
			return '<div class="crs-empty"><p>' . esc_html( crs_t( 'horses_empty' ) ) . '</p><a class="crs-btn crs-btn--primary" href="' . esc_url( crs_page_url( 'contacto' ) . '?interes=caballos' ) . '">' . esc_html( crs_t( 'horses_empty_cta' ) ) . '</a></div>';
		}

		$out = '';
		if ( 'si' === $atts['filtros'] ) {
			$terms = get_terms(
				array(
					'taxonomy'   => 'tipo_caballo',
					'hide_empty' => true,
				)
			);
			if ( $terms && ! is_wp_error( $terms ) && count( $terms ) > 1 ) {
				$out .= '<div class="crs-filters" role="group" aria-label="' . esc_attr( crs_t( 'filter_label' ) ) . '" data-crs-filters>';
				$out .= '<button type="button" class="is-active" data-filter="*" aria-pressed="true">' . esc_html( crs_t( 'filter_all' ) ) . '</button>';
				foreach ( $terms as $term ) {
					$out .= '<button type="button" data-filter="' . esc_attr( $term->slug ) . '" aria-pressed="false">' . esc_html( $term->name ) . '</button>';
				}
				$out .= '</div>';
			}
		}

		$out .= '<div class="crs-horse-grid" data-crs-grid>';
		while ( $query->have_posts() ) {
			$query->the_post();
			$out .= crs_horse_card( get_post() );
		}
		$out .= '</div>';
		wp_reset_postdata();

		return $out;
	}
);

/* ---------------------------------------------------------------------------
 * Contacto
 * ------------------------------------------------------------------------- */

if ( ! function_exists( 'crs_icon' ) ) {
	function crs_icon( string $name ): string {
		$paths = array(
			'phone'     => '<path d="M6.6 10.8a15.1 15.1 0 0 0 6.6 6.6l2.2-2.2a1 1 0 0 1 1-.25 11.4 11.4 0 0 0 3.6.57 1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.25.2 2.45.57 3.57a1 1 0 0 1-.25 1z"/>',
			'whatsapp'  => '<path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2zm0 18.2a8.2 8.2 0 0 1-4.2-1.2l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1 1 12 20.2zm4.5-6.1c-.2-.1-1.5-.7-1.7-.8s-.4-.1-.6.1-.7.8-.8 1-.3.2-.5.1a6.7 6.7 0 0 1-3.3-2.9c-.3-.4.3-.4.7-1.4.1-.2 0-.3 0-.4l-.8-1.8c-.2-.5-.4-.4-.6-.4h-.5a1 1 0 0 0-.7.3 3 3 0 0 0-.9 2.2 5.1 5.1 0 0 0 1.1 2.7 11.6 11.6 0 0 0 4.4 3.9c1.6.7 2.3.8 3.1.6.5-.1 1.5-.6 1.7-1.2s.2-1.1.1-1.2-.2-.2-.5-.3z"/>',
			'mail'      => '<path d="M3 5h18a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1zm9 7.2L4 7.3V17h16V7.3zM4.7 7l7.3 4.5L19.3 7z"/>',
			'pin'       => '<path d="M12 2a7 7 0 0 1 7 7c0 5.2-7 13-7 13S5 14.2 5 9a7 7 0 0 1 7-7zm0 9.5A2.5 2.5 0 1 0 12 6.5a2.5 2.5 0 0 0 0 5z"/>',
			'clock'     => '<path d="M12 2a10 10 0 1 1 0 20 10 10 0 0 1 0-20zm0 2a8 8 0 1 0 0 16 8 8 0 0 0 0-16zm1 3v4.6l3.5 2.1-1 1.7L11 12.7V7z"/>',
			'instagram' => '<path d="M12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10zm0 2a3 3 0 1 0 0 6 3 3 0 0 0 0-6zm5.5-3.2a1.2 1.2 0 1 1 0 2.4 1.2 1.2 0 0 1 0-2.4zM7.5 2h9A5.5 5.5 0 0 1 22 7.5v9a5.5 5.5 0 0 1-5.5 5.5h-9A5.5 5.5 0 0 1 2 16.5v-9A5.5 5.5 0 0 1 7.5 2zm0 2A3.5 3.5 0 0 0 4 7.5v9A3.5 3.5 0 0 0 7.5 20h9a3.5 3.5 0 0 0 3.5-3.5v-9A3.5 3.5 0 0 0 16.5 4z"/>',
			'arrow'     => '<path d="M13.2 5.3 20 12l-6.8 6.7-1.4-1.4 4.3-4.3H4v-2h12.1l-4.3-4.3z"/>',
			'external'  => '<path d="M14 3h7v7h-2V6.4l-9.3 9.3-1.4-1.4L17.6 5H14zM5 5h6v2H5v12h12v-6h2v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2z"/>',
			'play'      => '<path d="M8 5.1v13.8a.6.6 0 0 0 .9.5l10.6-6.9a.6.6 0 0 0 0-1L8.9 4.6a.6.6 0 0 0-.9.5z"/>',
		);
		return '<svg class="crs-icon" viewBox="0 0 24 24" width="20" height="20" aria-hidden="true" focusable="false" fill="currentColor">' . ( $paths[ $name ] ?? '' ) . '</svg>';
	}
}

add_shortcode(
	'crsport_telefono',
	fn() => '<a class="crs-tel" href="' . esc_attr( crs_tel_href() ) . '">' . esc_html( crs_opt( 'telefono' ) ) . '</a>'
);

add_shortcode(
	'crsport_contacto',
	function () {
		$items   = array();
		$items[] = array( 'phone', crs_t( 'contact_phone' ), crs_opt( 'telefono' ), crs_tel_href() );
		$items[] = array( 'whatsapp', 'WhatsApp', crs_t( 'contact_whatsapp_cta' ), crs_whatsapp_url( crs_t( 'whatsapp_text' ) ) );
		if ( crs_opt( 'email_publico' ) ) {
			$items[] = array( 'mail', 'Email', crs_opt( 'email_publico' ), 'mailto:' . crs_opt( 'email_publico' ) );
		}
		$items[] = array( 'pin', crs_t( 'contact_where' ), crs_opt( 'direccion' ), crs_maps_url() );
		if ( crs_opt( 'horario' ) ) {
			$items[] = array( 'clock', crs_t( 'contact_hours' ), crs_opt( 'horario' ), '' );
		}
		$items[] = array( 'instagram', 'Instagram', '@' . crs_opt( 'instagram' ), crs_instagram_url() );

		$out = '<ul class="crs-contact-list">';
		foreach ( $items as $item ) {
			$external = str_starts_with( $item[3], 'http' ) ? ' target="_blank" rel="noopener"' : '';
			$value    = $item[3] ? '<a href="' . esc_url( $item[3], array( 'http', 'https', 'tel', 'mailto' ) ) . '"' . $external . '>' . esc_html( $item[2] ) . '</a>' : esc_html( $item[2] );
			$out     .= '<li>' . crs_icon( $item[0] ) . '<span><small>' . esc_html( $item[1] ) . '</small>' . $value . '</span></li>';
		}
		return $out . '</ul>';
	}
);

add_shortcode(
	'crsport_consulta',
	function ( $atts ) {
		$atts = shortcode_atts(
			array(
				'interes' => '',
				'titulo'  => '',
				'texto'   => '',
			),
			$atts
		);
		ob_start();
		?>
		<div class="crs-consult" id="consulta">
			<div class="crs-consult__intro">
				<p class="crs-eyebrow"><?php crs_e( 'consult_eyebrow' ); ?></p>
				<h2 class="crs-consult__title"><?php echo esc_html( $atts['titulo'] ?: crs_t( 'consult_title' ) ); ?></h2>
				<p><?php echo esc_html( $atts['texto'] ?: crs_t( 'consult_text' ) ); ?></p>
				<div class="crs-consult__direct">
					<a class="crs-direct" href="<?php echo esc_attr( crs_tel_href() ); ?>">
						<?php echo crs_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<span><small><?php crs_e( 'consult_call' ); ?></small><?php echo esc_html( crs_opt( 'telefono' ) ); ?></span>
					</a>
					<a class="crs-direct" href="<?php echo esc_url( crs_whatsapp_url( crs_t( 'whatsapp_text' ) ) ); ?>" target="_blank" rel="noopener">
						<?php echo crs_icon( 'whatsapp' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<span><small>WhatsApp</small><?php crs_e( 'contact_whatsapp_cta' ); ?></span>
					</a>
				</div>
			</div>
			<div class="crs-consult__form">
				<?php echo crs_render_form( array( 'interes' => $atts['interes'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</div>
		</div>
		<?php
		return (string) ob_get_clean();
	}
);

/* ---------------------------------------------------------------------------
 * Instagram y mapa
 * ------------------------------------------------------------------------- */

add_shortcode(
	'crsport_instagram',
	function () {
		$handle = crs_opt( 'instagram' );
		$out    = '<div class="crs-instagram">';
		// Si está instalado «Smash Balloon Instagram Feed» y conectado, se muestra el feed real.
		if ( shortcode_exists( 'instagram-feed' ) ) {
			$out .= do_shortcode( '[instagram-feed]' );
		}
		$out .= '<a class="crs-btn crs-btn--outline" href="' . esc_url( crs_instagram_url() ) . '" target="_blank" rel="noopener">' . crs_icon( 'instagram' ) . '<span>@' . esc_html( $handle ) . '</span></a>';
		return $out . '</div>';
	}
);

add_shortcode(
	'crsport_mapa',
	function () {
		$embed = 'https://www.google.com/maps?q=' . rawurlencode( crs_opt( 'maps_query' ) ) . '&z=13&output=embed';
		return '<div class="crs-map" data-crs-embed="' . esc_url( $embed ) . '" data-title="' . esc_attr( crs_t( 'map_title' ) ) . '">'
			. '<div class="crs-map__facade">' . crs_icon( 'pin' )
			. '<p><strong>' . esc_html( crs_opt( 'direccion' ) ) . '</strong><br>' . esc_html( crs_t( 'map_notice' ) ) . '</p>'
			. '<p class="crs-map__actions"><button type="button" class="crs-btn crs-btn--primary" data-crs-embed-load>' . esc_html( crs_t( 'map_load' ) ) . '</button>'
			. '<a class="crs-btn crs-btn--ghost" href="' . esc_url( crs_maps_url() ) . '" target="_blank" rel="noopener">' . esc_html( crs_t( 'map_open' ) ) . '</a></p>'
			. '</div></div>';
	}
);

/* ---------------------------------------------------------------------------
 * Datos de Ajustes (textos legales)
 * ------------------------------------------------------------------------- */

add_shortcode(
	'crsport_dato',
	function ( $atts ) {
		$key   = sanitize_key( $atts['campo'] ?? '' );
		$value = $key ? crs_opt( $key ) : '';
		if ( '' !== $value ) {
			return esc_html( $value );
		}
		// Aviso visible solo para quien puede editar: falta rellenar el dato en CR Sport › Ajustes.
		return current_user_can( 'edit_pages' ) ? '<mark class="crs-pending">[' . esc_html( crs_t( 'pending' ) . ': ' . $key ) . ']</mark>' : '';
	}
);
