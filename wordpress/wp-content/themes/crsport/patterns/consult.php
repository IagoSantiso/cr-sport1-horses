<?php
/**
 * Title: Bloque «Consultar» con formulario
 * Slug: crsport/consult
 * Categories: crsport
 * Description: Texto, teléfono, WhatsApp y formulario. Cambia interes="…" por: caballos, escuela, rutas, academy, entrenamiento, pack, pupilaje, concursos u otro.
 */

defined( 'ABSPATH' ) || exit;
if ( ! function_exists( 'crs_section' ) ) {
	return;
}
echo crs_section( crs_shortcode( '[crsport_consulta interes="otro"]' ), 'soft', '', 'crs-section--consult' ); // phpcs:ignore WordPress.Security.EscapeOutput
