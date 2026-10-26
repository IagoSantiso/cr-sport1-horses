<?php
/**
 * Title: Imagen + texto
 * Slug: crsport/split
 * Categories: crsport
 * Description: Sección con foto a un lado y texto con lista y botón al otro.
 */

defined( 'ABSPATH' ) || exit;
require_once get_template_directory() . '/inc/pattern-helpers.php';
if ( ! function_exists( 'crs_section' ) ) {
	return;
}
echo crs_section( // phpcs:ignore WordPress.Security.EscapeOutput
	crs_split(
		crs_pattern_image(),
		crs_p( 'Antetítulo', 'is-style-crs-eyebrow' )
		. crs_h( 'Título de la sección' )
		. crs_p( 'Texto de introducción de la sección.', 'crs-lead' )
		. crs_list( array( 'Primer punto.', 'Segundo punto.', 'Tercer punto.' ), 'is-style-crs-check' )
		. crs_buttons( array( array( 'Consultar', '#consulta' ) ) )
	)
);
