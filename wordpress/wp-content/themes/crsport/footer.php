<?php
/**
 * Pie del sitio.
 */

defined( 'ABSPATH' ) || exit;
?>
</main>

<footer class="crs-footer">
	<div class="crs-footer__inner">
		<div class="crs-footer__brand">
			<?php crs_logo(); ?>
			<p class="crs-footer__tagline"><?php crs_e( 'footer_tagline' ); ?></p>
			<?php if ( function_exists( 'crs_instagram_url' ) ) : ?>
				<a class="crs-footer__social" href="<?php echo esc_url( crs_instagram_url() ); ?>" target="_blank" rel="noopener">
					<?php echo crs_icon( 'instagram' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<span>@<?php echo esc_html( crs_opt( 'instagram' ) ); ?></span>
				</a>
			<?php endif; ?>
		</div>

		<nav class="crs-footer__col" aria-label="<?php echo esc_attr( crs_t( 'footer_services' ) ); ?>">
			<p class="crs-footer__title"><?php crs_e( 'footer_services' ); ?></p>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footer',
					'container'      => false,
					'menu_class'     => 'crs-footer__menu',
					'depth'          => 1,
					'fallback_cb'    => false,
				)
			);
			?>
		</nav>

		<div class="crs-footer__col">
			<p class="crs-footer__title"><?php crs_e( 'footer_contact' ); ?></p>
			<ul class="crs-footer__menu crs-footer__contact">
				<?php if ( crs_opt( 'telefono' ) ) : ?>
					<li><a href="<?php echo esc_attr( crs_tel_href() ); ?>"><?php echo esc_html( crs_opt( 'telefono' ) ); ?></a></li>
					<li><a href="<?php echo esc_url( crs_whatsapp_url( crs_t( 'whatsapp_text' ) ) ); ?>" target="_blank" rel="noopener">WhatsApp</a></li>
				<?php endif; ?>
				<?php if ( crs_opt( 'email_publico' ) ) : ?>
					<li><a href="mailto:<?php echo esc_attr( crs_opt( 'email_publico' ) ); ?>"><?php echo esc_html( crs_opt( 'email_publico' ) ); ?></a></li>
				<?php endif; ?>
				<?php if ( crs_opt( 'direccion' ) ) : ?>
					<li><a href="<?php echo esc_url( crs_maps_url() ); ?>" target="_blank" rel="noopener"><?php echo esc_html( crs_opt( 'direccion' ) ); ?></a></li>
				<?php endif; ?>
			</ul>
		</div>

		<div class="crs-footer__col crs-footer__cta">
			<p class="crs-footer__title"><?php crs_e( 'footer_visit' ); ?></p>
			<p><?php crs_e( 'footer_visit_text' ); ?></p>
			<a class="crs-btn crs-btn--light" href="<?php echo esc_url( crs_page_url( 'contacto' ) ); ?>"><?php crs_e( 'consult_btn' ); ?></a>
		</div>
	</div>

	<div class="crs-footer__bottom">
		<p>© <?php echo esc_html( gmdate( 'Y' ) ); ?> CR Sport Horses · <?php crs_e( 'rights' ); ?></p>
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'legal',
				'container'      => false,
				'menu_class'     => 'crs-footer__legal',
				'depth'          => 1,
				'fallback_cb'    => false,
			)
		);
		?>
	</div>
</footer>

<?php if ( function_exists( 'crs_whatsapp_url' ) && crs_opt( 'whatsapp' ) ) : ?>
	<a class="crs-wa-float" href="<?php echo esc_url( crs_whatsapp_url( crs_t( 'whatsapp_text' ) ) ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( crs_t( 'whatsapp_float' ) ); ?>">
		<?php echo crs_icon( 'whatsapp' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</a>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
