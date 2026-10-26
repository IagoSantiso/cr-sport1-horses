<?php
/**
 * Tema CR Sport Horses.
 *
 * Tema clásico con theme.json: plantillas PHP para cabecera, pie y ficha de caballo, y el resto
 * de páginas construidas con bloques nativos y estilos propios (editables en el editor).
 */

defined( 'ABSPATH' ) || exit;

define( 'CRS_THEME_DIR', get_template_directory() );
define( 'CRS_THEME_URI', get_template_directory_uri() );

/* Si el plugin «CR Sport Horses · Núcleo» no está activo, el tema sigue funcionando con lo básico. */
if ( ! function_exists( 'crs_t' ) ) {
	function crs_t( string $key ): string {
		return $key;
	}
	function crs_e( string $key ): void {
		echo esc_html( $key );
	}
	function crs_opt( string $key ): string {
		return '';
	}
	function crs_page_url( string $key ): string {
		return home_url( '/' );
	}
	function crs_lang(): string {
		return 'es';
	}
	function crs_tel_href(): string {
		return '';
	}
	function crs_whatsapp_url( string $text = '' ): string {
		return '';
	}
	function crs_maps_url(): string {
		return '';
	}
	function crs_icon( string $name ): string {
		return '';
	}
}

add_action(
	'admin_notices',
	function () {
		if ( ! defined( 'CRS_VERSION' ) && current_user_can( 'activate_plugins' ) ) {
			echo '<div class="notice notice-warning"><p><strong>CR Sport Horses:</strong> activa el plugin «CR Sport Horses · Núcleo» para que el tema funcione completo (caballos, formulario, textos ES/EN).</p></div>';
		}
	}
);

add_action(
	'after_setup_theme',
	function () {
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'editor-styles' );
		add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 64,
				'width'       => 320,
				'flex-height' => true,
				'flex-width'  => true,
			)
		);
		remove_theme_support( 'core-block-patterns' );

		add_editor_style( 'assets/css/main.css' );

		register_nav_menus(
			array(
				'primary' => 'Menú principal',
				'footer'  => 'Pie · Servicios',
				'legal'   => 'Pie · Legal',
			)
		);

		add_image_size( 'crs-card', 720, 900, true );
		add_image_size( 'crs-wide', 1600, 1000, true );
	}
);

/* Tamaños de imagen con nombre en el editor. */
add_filter(
	'image_size_names_choose',
	fn( $sizes ) => $sizes + array(
		'crs-card' => 'Vertical (tarjeta)',
		'crs-wide' => 'Panorámica',
	)
);

add_action(
	'wp_enqueue_scripts',
	function () {
		$css = '/assets/css/main.css';
		$js  = '/assets/js/main.js';
		wp_enqueue_style( 'crs-main', CRS_THEME_URI . $css, array(), (string) filemtime( CRS_THEME_DIR . $css ) );
		wp_enqueue_script(
			'crs-main',
			CRS_THEME_URI . $js,
			array(),
			(string) filemtime( CRS_THEME_DIR . $js ),
			array(
				'strategy'  => 'defer',
				'in_footer' => true,
			)
		);
		wp_localize_script(
			'crs-main',
			'CRS',
			array(
				'sending' => crs_t( 'form_sending' ),
				'error'   => crs_t( 'form_err_network' ),
				'prev'    => crs_t( 'prev' ),
				'next'    => crs_t( 'next' ),
				'close'   => crs_t( 'close' ),
			)
		);
	}
);

/* Precarga de las dos fuentes principales (latin) para evitar saltos de texto. */
add_action(
	'wp_head',
	function () {
		foreach ( array( 'playfair-latin', 'jakarta-latin' ) as $font ) {
			printf( '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n", esc_url( CRS_THEME_URI . '/assets/fonts/' . $font . '.woff2' ) );
		}
	},
	1
);

/* Limpieza de <head>. */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );

/* Estilos de bloque seleccionables en el editor. */
add_action(
	'init',
	function () {
		register_block_style(
			'core/group',
			array(
				'name'  => 'crs-soft',
				'label' => 'Sección gris',
			)
		);
		register_block_style(
			'core/group',
			array(
				'name'  => 'crs-dark',
				'label' => 'Sección azul marino',
			)
		);
		register_block_style(
			'core/paragraph',
			array(
				'name'  => 'crs-eyebrow',
				'label' => 'Antetítulo',
			)
		);
		register_block_style(
			'core/list',
			array(
				'name'  => 'crs-check',
				'label' => 'Lista con marcas',
			)
		);
		register_block_style(
			'core/button',
			array(
				'name'  => 'crs-light',
				'label' => 'Claro (sobre foto)',
			)
		);
		register_block_pattern_category( 'crsport', array( 'label' => 'CR Sport Horses' ) );
	}
);

/**
 * Las páginas que empiezan con una portada (bloque .crs-hero) llevan la cabecera transparente encima.
 */
function crs_has_hero(): bool {
	if ( ! is_singular( 'page' ) ) {
		return false;
	}
	$content = (string) get_post_field( 'post_content', get_queried_object_id() );
	return str_contains( substr( ltrim( $content ), 0, 400 ), 'crs-hero' );
}

add_filter(
	'body_class',
	function ( $classes ) {
		$classes[] = crs_has_hero() ? 'has-hero' : 'has-solid-header';
		return $classes;
	}
);

/**
 * Menú de respaldo si todavía no se ha creado ninguno.
 */
function crs_menu_fallback(): void {
	echo '<ul class="crs-menu">';
	foreach ( array( 'caballos', 'escuela', 'entrenamiento', 'pupilaje', 'instalaciones', 'roi', 'contacto' ) as $key ) {
		$id = function_exists( 'crs_page_id' ) ? crs_page_id( $key ) : 0;
		if ( $id ) {
			printf( '<li><a href="%s">%s</a></li>', esc_url( get_permalink( $id ) ), esc_html( get_the_title( $id ) ) );
		}
	}
	echo '</ul>';
}

/**
 * Selector de idioma ES | EN (Polylang).
 */
function crs_language_switcher( string $class = '' ): void {
	if ( ! function_exists( 'pll_the_languages' ) ) {
		return;
	}
	$langs = pll_the_languages(
		array(
			'raw'           => 1,
			'hide_if_empty' => 0,
		)
	);
	if ( count( (array) $langs ) < 2 ) {
		return;
	}
	echo '<ul class="crs-lang ' . esc_attr( $class ) . '" aria-label="' . esc_attr( crs_t( 'lang_switch' ) ) . '">';
	foreach ( $langs as $lang ) {
		printf(
			'<li><a href="%1$s" hreflang="%2$s" lang="%2$s"%3$s>%4$s</a></li>',
			esc_url( $lang['url'] ),
			esc_attr( $lang['locale'] ? str_replace( '_', '-', $lang['locale'] ) : $lang['slug'] ),
			$lang['current_lang'] ? ' aria-current="true"' : '',
			esc_html( strtoupper( $lang['slug'] ) )
		);
	}
	echo '</ul>';
}

/**
 * Logo: el subido en Apariencia › Personalizar, o el monograma CR por defecto.
 */
function crs_logo(): void {
	if ( has_custom_logo() ) {
		the_custom_logo();
		return;
	}
	printf(
		'<a class="crs-logo" href="%1$s" rel="home" aria-label="CR Sport Horses">%2$s<span class="crs-logo__text"><span class="crs-logo__name">CR Sport Horses</span><span class="crs-logo__sub">Roi Calviño · Cecebre</span></span></a>',
		esc_url( function_exists( 'pll_home_url' ) ? pll_home_url() : home_url( '/' ) ),
		'<svg class="crs-logo__mark" viewBox="0 0 64 64" aria-hidden="true"><g fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M28 18c-10 0-16 6-16 14s6 14 16 14c5 0 9-3 11-6"/><path d="M36 46V18h11c6 0 9 3 9 9 0 5-4 8-10 8m-2 0 12 11"/></g></svg>'
	);
}

/**
 * Migas de pan simples.
 */
function crs_breadcrumbs( array $items ): void {
	echo '<nav class="crs-breadcrumbs" aria-label="' . esc_attr( crs_t( 'breadcrumbs' ) ) . '"><ol>';
	$home = function_exists( 'pll_home_url' ) ? pll_home_url() : home_url( '/' );
	echo '<li><a href="' . esc_url( $home ) . '">' . esc_html( crs_t( 'breadcrumb_home' ) ) . '</a></li>';
	foreach ( $items as $item ) {
		echo $item[1]
			? '<li><a href="' . esc_url( $item[1] ) . '">' . esc_html( $item[0] ) . '</a></li>'
			: '<li aria-current="page">' . esc_html( $item[0] ) . '</li>';
	}
	echo '</ol></nav>';
}
