<?php
/**
 * Ficha de caballo en venta.
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'crs_horse' ) ) {
	require get_template_directory() . '/index.php';
	return;
}

get_header();

while ( have_posts() ) :
	the_post();
	$h      = crs_horse();
	$meta   = crs_horse_meta_line( $h );
	$poster = $h['galeria'] ? wp_get_attachment_image_url( $h['galeria'][0], 'crs-wide' ) : '';
	$wa_txt = sprintf( crs_t( 'horse_whatsapp_text' ), $h['nombre'] );

	// Diapositivas: fotos y vídeos subidos + vídeos externos.
	$slides = array();
	foreach ( $h['galeria'] as $att_id ) {
		$is_video = str_starts_with( (string) get_post_mime_type( $att_id ), 'video' );
		$slides[] = array(
			'type' => $is_video ? 'video' : 'image',
			'id'   => $att_id,
		);
	}
	foreach ( $h['videos'] as $video_url ) {
		$embed = crs_video_embed( $video_url );
		if ( $embed ) {
			$slides[] = array(
				'type'     => 'embed',
				'provider' => $embed[0],
				'embed'    => $embed[1],
				'url'      => $video_url,
			);
		}
	}
	$total = count( $slides );
	?>
	<article <?php post_class( 'crs-horse' ); ?>>

		<header class="crs-horse__head">
			<?php crs_breadcrumbs( array( array( crs_t( 'horses_for_sale' ), crs_page_url( 'caballos' ) ), array( $h['nombre'], '' ) ) ); ?>
			<div class="crs-horse__title">
				<div>
					<p class="crs-status crs-status--<?php echo esc_attr( $h['estado'] ); ?>"><span aria-hidden="true"></span><?php echo esc_html( $h['estado_txt'] ); ?></p>
					<h1><?php the_title(); ?></h1>
					<?php if ( $meta ) : ?>
						<p class="crs-meta-line crs-meta-line--lg">
							<?php echo implode( '<span class="crs-dot" aria-hidden="true">·</span>', array_map( 'esc_html', $meta ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</p>
					<?php endif; ?>
				</div>
				<?php if ( $h['media'] ) : ?>
					<div class="crs-horse__score-badge" title="<?php echo esc_attr( crs_t( 'horse_score' ) ); ?>">
						<strong><?php echo esc_html( number_format_i18n( $h['media'], 1 ) ); ?></strong>
						<span><?php crs_e( 'horse_score_short' ); ?></span>
					</div>
				<?php endif; ?>
			</div>
		</header>

		<div class="crs-horse__top">
			<?php if ( $slides ) : ?>
				<div class="crs-gallery" data-crs-gallery aria-roledescription="carousel" aria-label="<?php echo esc_attr( crs_t( 'horse_gallery' ) ); ?>">
					<div class="crs-gallery__stage">
						<?php foreach ( $slides as $i => $slide ) : ?>
							<div class="crs-gallery__slide is-<?php echo esc_attr( $slide['type'] ); ?><?php echo isset( $slide['provider'] ) ? ' is-' . esc_attr( $slide['provider'] ) : ''; ?><?php echo 0 === $i ? ' is-active' : ''; ?>"
								role="group" aria-roledescription="slide" aria-label="<?php echo esc_attr( sprintf( crs_t( 'slide_of' ), $i + 1, $total ) ); ?>"<?php echo 0 === $i ? '' : ' hidden'; ?>>
								<?php if ( 'image' === $slide['type'] ) : ?>
									<a href="<?php echo esc_url( wp_get_attachment_image_url( $slide['id'], 'full' ) ); ?>" data-crs-lightbox>
										<?php
										echo wp_get_attachment_image(
											$slide['id'],
											'crs-wide',
											false,
											array(
												'loading' => 0 === $i ? 'eager' : 'lazy',
												'fetchpriority' => 0 === $i ? 'high' : 'auto',
												'sizes'   => '(min-width: 1024px) 66vw, 100vw',
											)
										);
										?>
									</a>
								<?php elseif ( 'video' === $slide['type'] ) : ?>
									<video controls playsinline preload="none" <?php echo $poster ? 'poster="' . esc_url( $poster ) . '"' : ''; ?>>
										<source src="<?php echo esc_url( wp_get_attachment_url( $slide['id'] ) ); ?>" type="<?php echo esc_attr( get_post_mime_type( $slide['id'] ) ); ?>">
									</video>
								<?php else : ?>
									<div class="crs-embed" data-crs-embed="<?php echo esc_url( $slide['embed'] ); ?>" data-title="<?php echo esc_attr( $h['nombre'] ); ?>">
										<?php if ( $poster ) : ?>
											<img src="<?php echo esc_url( $poster ); ?>" alt="" loading="lazy">
										<?php endif; ?>
										<div class="crs-embed__facade">
											<button type="button" class="crs-embed__play" data-crs-embed-load>
												<?php echo crs_icon( 'play' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
												<span><?php echo esc_html( sprintf( crs_t( 'video_play' ), ucfirst( $slide['provider'] ) ) ); ?></span>
											</button>
											<p><?php echo esc_html( sprintf( crs_t( 'video_consent' ), ucfirst( $slide['provider'] ) ) ); ?> · <a href="<?php echo esc_url( $slide['url'] ); ?>" target="_blank" rel="noopener"><?php crs_e( 'video_open' ); ?></a></p>
										</div>
									</div>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>

						<?php if ( $total > 1 ) : ?>
							<button type="button" class="crs-gallery__nav crs-gallery__nav--prev" data-crs-prev aria-label="<?php echo esc_attr( crs_t( 'prev' ) ); ?>"><span aria-hidden="true">‹</span></button>
							<button type="button" class="crs-gallery__nav crs-gallery__nav--next" data-crs-next aria-label="<?php echo esc_attr( crs_t( 'next' ) ); ?>"><span aria-hidden="true">›</span></button>
							<p class="crs-gallery__count" aria-hidden="true"><span data-crs-current>1</span> / <?php echo esc_html( $total ); ?></p>
						<?php endif; ?>
					</div>

					<?php if ( $total > 1 ) : ?>
						<div class="crs-gallery__thumbs">
							<?php foreach ( $slides as $i => $slide ) : ?>
								<button type="button" class="crs-gallery__thumb<?php echo 0 === $i ? ' is-active' : ''; ?>" data-crs-goto="<?php echo esc_attr( $i ); ?>" aria-label="<?php echo esc_attr( sprintf( crs_t( 'slide_of' ), $i + 1, $total ) ); ?>">
									<?php if ( 'image' === $slide['type'] ) : ?>
										<?php echo wp_get_attachment_image( $slide['id'], 'thumbnail', false, array( 'loading' => 'lazy' ) ); ?>
									<?php else : ?>
										<span class="crs-gallery__thumb-video"><?php echo crs_icon( 'play' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
									<?php endif; ?>
								</button>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<aside class="crs-horse__aside">
				<p class="crs-horse__aside-title"><?php crs_e( 'horse_key_data' ); ?></p>
				<dl class="crs-specs-list">
					<?php
					$rows = array(
						'horse_age'    => $h['edad'] ? $h['edad_txt'] . ( $h['nacimiento'] ? ' (' . $h['nacimiento'] . ')' : '' ) : '',
						'horse_sex'    => $h['sexo_txt'],
						'horse_breed'  => $h['raza'],
						'horse_coat'   => $h['capa'],
						'horse_height' => $h['alzada_txt'],
					);
					foreach ( array_filter( $rows ) as $label => $value ) :
						?>
						<div><dt><?php crs_e( $label ); ?></dt><dd><?php echo esc_html( $value ); ?></dd></div>
					<?php endforeach; ?>
				</dl>
				<div class="crs-horse__actions">
					<?php if ( 'vendido' !== $h['estado'] ) : ?>
						<a class="crs-btn crs-btn--primary" href="#consulta"><?php crs_e( 'horse_ask' ); ?></a>
						<a class="crs-btn crs-btn--ghost" href="<?php echo esc_url( crs_whatsapp_url( $wa_txt ) ); ?>" target="_blank" rel="noopener">
							<?php echo crs_icon( 'whatsapp' ); // phpcs:ignore WordPress.Security.EscapeOutput ?> WhatsApp
						</a>
					<?php endif; ?>
					<?php if ( $h['horsetelex'] ) : ?>
						<a class="crs-btn crs-btn--link" href="<?php echo esc_url( $h['horsetelex'] ); ?>" target="_blank" rel="noopener">
							<?php crs_e( 'horse_horsetelex' ); ?> <?php echo crs_icon( 'external' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</a>
					<?php endif; ?>
				</div>
				<p class="crs-horse__phone"><?php crs_e( 'form_or_call' ); ?> <a href="<?php echo esc_attr( crs_tel_href() ); ?>"><?php echo esc_html( crs_opt( 'telefono' ) ); ?></a></p>
			</aside>
		</div>

		<section class="crs-horse__body">
			<div class="crs-horse__description">
				<h2><?php crs_e( 'horse_description' ); ?></h2>
				<div class="crs-prose"><?php the_content(); ?></div>
			</div>
			<div class="crs-horse__facts">
				<?php
				$facts = array(
					'horse_origin'    => $h['origen'],
					'horse_level'     => $h['nivel'],
					'horse_potential' => $h['potencial'],
				);
				foreach ( array_filter( $facts ) as $label => $value ) :
					?>
					<div class="crs-fact">
						<p class="crs-fact__label"><?php crs_e( $label ); ?></p>
						<p class="crs-fact__value"><?php echo esc_html( $value ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</section>

		<?php if ( $h['puntuacion'] ) : ?>
			<section class="crs-horse__scores">
				<div class="crs-horse__scores-head">
					<p class="is-style-crs-eyebrow"><?php crs_e( 'horse_score' ); ?></p>
					<h2><?php crs_e( 'horse_score_title' ); ?></h2>
					<p><?php crs_e( 'horse_score_note' ); ?></p>
				</div>
				<ul class="crs-scores">
					<?php foreach ( $h['puntuacion'] as $criterion => $score ) : ?>
						<li>
							<span class="crs-scores__label"><?php crs_e( 'score_' . $criterion ); ?></span>
							<span class="crs-scores__bar" role="img" aria-label="<?php echo esc_attr( $score . '/10' ); ?>"><span style="--v:<?php echo esc_attr( $score * 10 ); ?>%"></span></span>
							<span class="crs-scores__value"><?php echo esc_html( $score ); ?><small>/10</small></span>
						</li>
					<?php endforeach; ?>
				</ul>
			</section>
		<?php endif; ?>

		<?php if ( 'vendido' !== $h['estado'] ) : ?>
			<section class="crs-horse__consult" id="consulta">
				<div class="crs-consult">
					<div class="crs-consult__intro">
						<p class="crs-eyebrow"><?php crs_e( 'consult_eyebrow' ); ?></p>
						<h2 class="crs-consult__title"><?php echo esc_html( sprintf( crs_t( 'horse_consult_title' ), $h['nombre'] ) ); ?></h2>
						<p><?php crs_e( 'horse_consult_text' ); ?></p>
						<div class="crs-consult__direct">
							<a class="crs-direct" href="<?php echo esc_attr( crs_tel_href() ); ?>">
								<?php echo crs_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
								<span><small><?php crs_e( 'consult_call' ); ?></small><?php echo esc_html( crs_opt( 'telefono' ) ); ?></span>
							</a>
							<a class="crs-direct" href="<?php echo esc_url( crs_whatsapp_url( $wa_txt ) ); ?>" target="_blank" rel="noopener">
								<?php echo crs_icon( 'whatsapp' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
								<span><small>WhatsApp</small><?php crs_e( 'contact_whatsapp_cta' ); ?></span>
							</a>
						</div>
					</div>
					<div class="crs-consult__form">
						<?php echo crs_render_form( array( 'caballo' => $h['nombre'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<?php
		$others = crs_query_horses(
			array(
				'posts_per_page' => 3,
				'post__not_in'   => array( get_the_ID() ),
			)
		);
		if ( $others->have_posts() ) :
			?>
			<section class="crs-horse__more">
				<div class="crs-section-head crs-section-head--row">
					<h2><?php crs_e( 'horse_more' ); ?></h2>
					<a class="crs-link-arrow" href="<?php echo esc_url( crs_page_url( 'caballos' ) ); ?>"><?php crs_e( 'horse_back' ); ?></a>
				</div>
				<div class="crs-horse-grid">
					<?php
					while ( $others->have_posts() ) {
						$others->the_post();
						echo crs_horse_card( get_post() ); // phpcs:ignore WordPress.Security.EscapeOutput
					}
					wp_reset_postdata();
					?>
				</div>
			</section>
		<?php endif; ?>
	</article>
	<?php
endwhile;

get_footer();
