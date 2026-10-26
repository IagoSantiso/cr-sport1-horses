<?php
/**
 * Página no encontrada.
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<section class="crs-404">
	<p class="is-style-crs-eyebrow">404</p>
	<h1><?php crs_e( '404_title' ); ?></h1>
	<p class="crs-lead"><?php crs_e( '404_text' ); ?></p>
	<p class="crs-404__actions">
		<a class="crs-btn crs-btn--primary" href="<?php echo esc_url( function_exists( 'pll_home_url' ) ? pll_home_url() : home_url( '/' ) ); ?>"><?php crs_e( '404_cta' ); ?></a>
		<a class="crs-btn crs-btn--ghost" href="<?php echo esc_url( crs_page_url( 'caballos' ) ); ?>"><?php crs_e( 'horses_for_sale' ); ?></a>
	</p>
</section>
<?php
get_footer();
