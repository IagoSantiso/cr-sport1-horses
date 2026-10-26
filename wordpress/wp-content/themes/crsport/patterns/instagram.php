<?php
/**
 * Title: Instagram
 * Slug: crsport/instagram
 * Categories: crsport
 * Description: Sección con el feed de Instagram. Úsala solo cuando el plugin «Smash Balloon Instagram Feed» esté conectado a @crsport_horses; sin feed se queda en un simple botón.
 */

defined( 'ABSPATH' ) || exit;
if ( ! function_exists( 'crs_section' ) ) {
	return;
}
echo crs_section( // phpcs:ignore WordPress.Security.EscapeOutput
	crs_section_head( 'Instagram', 'El día a día del centro', 'Entrenamientos, concursos y nuevos caballos, contados en nuestro Instagram.', 'crs-center' )
	. crs_shortcode( '[crsport_instagram]' )
);
