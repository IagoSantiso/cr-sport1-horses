<?php
/**
 * Plantilla general (entradas, búsquedas y archivos).
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<header class="crs-page-intro crs-page-intro--simple">
	<div class="crs-page-intro__inner">
		<h1>
			<?php
			if ( is_search() ) {
				echo esc_html( crs_t( 'search_results' ) . ': ' . get_search_query() );
			} elseif ( is_singular() ) {
				single_post_title();
			} else {
				echo esc_html( wp_strip_all_tags( get_the_archive_title() ) );
			}
			?>
		</h1>
	</div>
</header>
<div class="entry-content is-layout-constrained has-global-padding crs-loop">
	<?php if ( have_posts() ) : ?>
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<?php if ( is_singular() ) : ?>
				<?php the_content(); ?>
			<?php else : ?>
				<article <?php post_class( 'crs-loop__item' ); ?>>
					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<?php the_excerpt(); ?>
				</article>
			<?php endif; ?>
		<?php endwhile; ?>
		<?php the_posts_pagination(); ?>
	<?php else : ?>
		<p><?php crs_e( 'nothing_found' ); ?></p>
	<?php endif; ?>
</div>
<?php
get_footer();
