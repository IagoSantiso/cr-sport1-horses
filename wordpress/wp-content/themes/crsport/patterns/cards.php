<?php
/**
 * Title: Tarjetas
 * Slug: crsport/cards
 * Categories: crsport
 * Description: Cabecera de sección y tres tarjetas numeradas sobre fondo gris.
 */

defined( 'ABSPATH' ) || exit;
if ( ! function_exists( 'crs_section' ) ) {
	return;
}
echo crs_section( // phpcs:ignore WordPress.Security.EscapeOutput
	crs_section_head( 'Antetítulo', 'Título de la sección', 'Entradilla opcional.' )
	. crs_grid(
		crs_card( 'Primera tarjeta', 'Descripción breve.', '01' )
		. crs_card( 'Segunda tarjeta', 'Descripción breve.', '02' )
		. crs_card( 'Tercera tarjeta', 'Descripción breve.', '03' ),
		'crs-cards',
		'16rem'
	),
	'soft'
);
