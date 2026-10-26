<?php
/**
 * Title: Portada con foto
 * Slug: crsport/hero
 * Categories: crsport
 * Description: Foto a todo el ancho con antetítulo, titular, entradilla y botones. Úsala como primer bloque de la página.
 */

defined( 'ABSPATH' ) || exit;
require_once get_template_directory() . '/inc/pattern-helpers.php';
if ( ! function_exists( 'crs_hero' ) ) {
	return;
}
$hero = crs_hero( 0, '', 'Antetítulo', 'Titular de la página', 'Una o dos frases que resuman la página.', array( array( 'Consultar', '#consulta' ) ) );
echo str_replace( '<!-- wp:group {"className":"crs-hero__content"', crs_pattern_image( 'crs-hero__media' ) . '<!-- wp:group {"className":"crs-hero__content"', $hero ); // phpcs:ignore WordPress.Security.EscapeOutput
