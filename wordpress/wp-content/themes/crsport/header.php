<?php
/**
 * Cabecera del sitio.
 */

defined( 'ABSPATH' ) || exit;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#00183b">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="crs-skip" href="#main"><?php crs_e( 'skip' ); ?></a>

<header class="crs-header" data-crs-header>
	<div class="crs-header__inner">
		<?php crs_logo(); ?>

		<nav class="crs-nav" id="crs-nav" aria-label="<?php echo esc_attr( crs_t( 'menu' ) ); ?>" data-crs-nav>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'crs-menu',
					'depth'          => 2,
					'fallback_cb'    => 'crs_menu_fallback',
				)
			);
			?>
			<div class="crs-nav__mobile-extra">
				<?php crs_language_switcher( 'crs-lang--mobile' ); ?>
				<?php if ( crs_opt( 'telefono' ) ) : ?>
					<a class="crs-nav__phone" href="<?php echo esc_attr( crs_tel_href() ); ?>"><?php echo esc_html( crs_opt( 'telefono' ) ); ?></a>
				<?php endif; ?>
			</div>
		</nav>

		<div class="crs-header__actions">
			<?php crs_language_switcher(); ?>
			<a class="crs-btn crs-btn--primary crs-header__cta" href="<?php echo esc_url( crs_page_url( 'contacto' ) ); ?>"><?php crs_e( 'consult_btn' ); ?></a>
			<button class="crs-burger" type="button" aria-controls="crs-nav" aria-expanded="false" data-crs-burger>
				<span class="screen-reader-text"><?php crs_e( 'menu' ); ?></span>
				<span class="crs-burger__lines" aria-hidden="true"></span>
			</button>
		</div>
	</div>
</header>

<main id="main" class="crs-main">
