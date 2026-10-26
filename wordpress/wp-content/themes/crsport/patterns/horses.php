<?php
/**
 * Title: Caballos en venta (listado)
 * Slug: crsport/horses
 * Categories: crsport
 * Description: Listado automático de caballos. limite="3" para mostrar solo algunos, destacados="si" para los marcados como destacados, filtros="si" para mostrar los filtros por categoría.
 */

defined( 'ABSPATH' ) || exit;
if ( ! function_exists( 'crs_section' ) ) {
	return;
}
echo crs_section( // phpcs:ignore WordPress.Security.EscapeOutput
	crs_section_head( 'Caballos en venta', 'Encuentra tu próximo caballo' )
	. crs_shortcode( '[crsport_caballos limite="3" destacados="si"]' )
);
