<?php
/**
 * Textos fijos de la web en español e inglés.
 *
 * El contenido de las páginas y caballos se traduce con Polylang (una entrada por idioma).
 * Los textos fijos del tema y del plugin (menú, etiquetas de la ficha, formulario…) viven en
 * inc/strings.php, para poder corregirlos en un solo sitio sin ficheros .po/.mo.
 */

defined( 'ABSPATH' ) || exit;

// Las funciones envueltas en function_exists() también existen como respaldo en el tema;
// el guardado evita el choque en la petición en la que se activa el plugin.

/**
 * Idioma activo: 'es' o 'en'.
 */
if ( ! function_exists( 'crs_lang' ) ) {
	function crs_lang(): string {
		if ( function_exists( 'pll_current_language' ) ) {
			$lang = pll_current_language( 'slug' );
			if ( $lang ) {
				return 'en' === $lang ? 'en' : 'es';
			}
		}
		return str_starts_with( determine_locale(), 'en' ) ? 'en' : 'es';
	}
}

/**
 * Idioma de una entrada concreta (útil en el admin o en correos).
 */
function crs_post_lang( $post_id ): string {
	if ( function_exists( 'pll_get_post_language' ) ) {
		$lang = pll_get_post_language( $post_id, 'slug' );
		if ( $lang ) {
			return 'en' === $lang ? 'en' : 'es';
		}
	}
	return crs_lang();
}

/**
 * Devuelve un texto fijo traducido.
 */
if ( ! function_exists( 'crs_t' ) ) {
	function crs_t( string $key, ?string $lang = null ): string {
		static $dict = null;
		if ( null === $dict ) {
			$dict = require CRS_DIR . 'inc/strings.php';
		}
		$lang = $lang ?? crs_lang();
		return $dict[ $key ][ $lang ] ?? $dict[ $key ]['es'] ?? $key;
	}
}

if ( ! function_exists( 'crs_e' ) ) {
	function crs_e( string $key ): void {
		echo esc_html( crs_t( $key ) );
	}
}

/**
 * URL de una página clave del sitio («contacto», «caballos»…) en el idioma activo.
 * Las páginas se registran al crear el contenido inicial (inc/setup.php).
 */
function crs_page_id( string $key, ?string $lang = null ): int {
	$pages = get_option( 'crs_pages', array() );
	$id    = (int) ( $pages[ $key ] ?? 0 );
	if ( $id && function_exists( 'pll_get_post' ) ) {
		$translated = pll_get_post( $id, $lang ?? crs_lang() );
		if ( $translated ) {
			$id = (int) $translated;
		}
	}
	return $id && 'publish' === get_post_status( $id ) ? $id : 0;
}

if ( ! function_exists( 'crs_page_url' ) ) {
	function crs_page_url( string $key, ?string $lang = null ): string {
		$id = crs_page_id( $key, $lang );
		return $id ? get_permalink( $id ) : home_url( '/' );
	}
}
