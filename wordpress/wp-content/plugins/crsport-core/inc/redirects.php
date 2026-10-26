<?php
/**
 * Redirecciones 301 desde las URL de la web anterior (tema Enfold) a las nuevas páginas,
 * para conservar el posicionamiento y los enlaces que ya circulan.
 * Solo actúa cuando la URL pedida no existe (404).
 */

defined( 'ABSPATH' ) || exit;

function crs_legacy_redirects(): array {
	return array(
		'inicio/pupilaje-caballos'      => 'pupilaje',
		'inicio/clases-equitacion'      => 'escuela',
		'inicio/campamentos-ecuestres'  => 'escuela',
		'inicio/competiciones'          => 'entrenamiento',
		'mail-telefono'                 => 'contacto',
		'politica-privacidad'           => 'privacidad',
		'accesibilidad'                 => 'inicio',
		'mapa-web'                      => 'inicio',
	);
}

add_action(
	'template_redirect',
	function () {
		if ( ! is_404() ) {
			return;
		}
		$path = trim( (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ), '/' ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		$lang = 'es';
		if ( str_starts_with( $path, 'en/' ) ) {
			$path = substr( $path, 3 );
			$lang = 'en';
		}
		$map = crs_legacy_redirects();
		if ( isset( $map[ $path ] ) ) {
			$id = crs_page_id( $map[ $path ], $lang );
			if ( $id ) {
				wp_safe_redirect( get_permalink( $id ), 301 );
				exit;
			}
		}
	}
);
