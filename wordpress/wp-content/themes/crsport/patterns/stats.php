<?php
/**
 * Title: Cifras destacadas
 * Slug: crsport/stats
 * Categories: crsport
 * Description: Fila de cifras grandes con su descripción.
 */

defined( 'ABSPATH' ) || exit;
if ( ! function_exists( 'crs_section' ) ) {
	return;
}
echo crs_section( // phpcs:ignore WordPress.Security.EscapeOutput
	crs_grid( crs_stat( '70 × 35 m', 'Descripción' ) . crs_stat( '26', 'Descripción' ) . crs_stat( '6', 'Descripción' ) . crs_stat( '1.000 m²', 'Descripción' ), 'crs-stats', '12rem' )
);
