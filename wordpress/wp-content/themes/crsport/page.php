<?php
/**
 * Páginas. El contenido (portada, secciones, formularios…) se edita con bloques.
 * Si la página no empieza con una portada, se muestra un encabezado sencillo con el título.
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'crs-page' ); ?>>
		<?php if ( ! crs_has_hero() && ! str_contains( (string) get_post_field( 'post_content' ), 'crs-page-intro' ) ) : ?>
			<header class="crs-page-intro crs-page-intro--simple">
				<div class="crs-page-intro__inner">
					<h1><?php the_title(); ?></h1>
				</div>
			</header>
		<?php endif; ?>
		<div class="entry-content is-layout-constrained has-global-padding">
			<?php the_content(); ?>
		</div>
	</article>
	<?php
endwhile;

get_footer();
