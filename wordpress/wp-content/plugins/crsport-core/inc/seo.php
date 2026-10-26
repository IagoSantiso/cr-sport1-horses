<?php
/**
 * SEO básico: datos estructurados del negocio y meta descripción.
 * Si hay un plugin de SEO activo (Rank Math, Yoast, SEOPress) la meta descripción la gestiona él.
 * Las etiquetas hreflang entre ES y EN las añade Polylang.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', fn() => add_post_type_support( 'page', 'excerpt' ) );

function crs_has_seo_plugin(): bool {
	return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'SEOPRESS_VERSION' );
}

add_action(
	'wp_head',
	function () {
		if ( crs_has_seo_plugin() || ! is_singular() ) {
			return;
		}
		$post = get_queried_object();
		$desc = has_excerpt( $post ) ? get_the_excerpt( $post ) : wp_trim_words( wp_strip_all_tags( strip_shortcodes( $post->post_content ) ), 28, '…' );
		if ( $desc ) {
			printf( '<meta name="description" content="%s">' . "\n", esc_attr( $desc ) );
		}
	},
	2
);

add_action(
	'wp_head',
	function () {
		$same_as = array_filter( array( crs_instagram_url(), crs_opt( 'facebook' ) ) );
		$logo    = get_theme_mod( 'custom_logo' ) ? wp_get_attachment_image_url( get_theme_mod( 'custom_logo' ), 'full' ) : '';

		$business = array(
			'@context'    => 'https://schema.org',
			'@type'       => array( 'SportsActivityLocation', 'LocalBusiness' ),
			'@id'         => home_url( '/#organization' ),
			'name'        => 'CR Sport Horses',
			'url'         => home_url( '/' ),
			'telephone'   => '+34 ' . crs_opt( 'telefono' ),
			'description' => crs_t( 'schema_description' ),
			'address'     => array(
				'@type'           => 'PostalAddress',
				'addressLocality' => 'Cecebre',
				'addressRegion'   => 'A Coruña',
				'addressCountry'  => 'ES',
			),
			'founder'     => array(
				'@type'    => 'Person',
				'name'     => 'Roi Calviño Rabón',
				'jobTitle' => crs_t( 'schema_founder_role' ),
			),
			'foundingDate' => '2022-03',
			'sameAs'      => array_values( $same_as ),
		);
		if ( $logo ) {
			$business['logo'] = $logo;
		}
		if ( crs_opt( 'email_publico' ) ) {
			$business['email'] = crs_opt( 'email_publico' );
		}

		echo '<script type="application/ld+json">' . wp_json_encode( $business, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . "</script>\n";
	},
	20
);
