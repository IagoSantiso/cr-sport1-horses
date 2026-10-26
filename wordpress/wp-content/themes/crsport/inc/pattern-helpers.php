<?php
/**
 * Ayudantes para los patrones: reutilizan los del plugin si está activo.
 */

defined( 'ABSPATH' ) || exit;

function crs_pattern_image( string $class = '' ): string {
	$src   = esc_url( get_template_directory_uri() . '/assets/img/placeholder.jpg' );
	$attrs = $class ? ' {"sizeSlug":"large","className":"' . esc_attr( $class ) . '"}' : ' {"sizeSlug":"large"}';
	return "<!-- wp:image{$attrs} -->\n<figure class=\"wp-block-image size-large {$class}\"><img src=\"{$src}\" alt=\"\"/></figure>\n<!-- /wp:image -->\n";
}
