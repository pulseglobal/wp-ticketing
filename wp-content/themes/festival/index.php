<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package festival
 */

get_header(); ?>


<div id="journal-listing">
	<div class="container">
		<?php
		if ( have_posts() ) : ?>

			<div class="content-heading">
				<h2>Journal</h2>
			</div>
			<!-- /.content-heading -->
			<div class="journal-post-wrapper">
				<?php /* Start the Loop */
				while ( have_posts() ) : the_post();

					/*
					 * Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'template-parts/content', 'journal' );

				endwhile; ?>
			</div>
			<!-- /.journal-post-wrapper -->
		<?php endif; ?>
	</div>
	<!-- /.container -->
</div>
<!-- /#journal-listing -->


<?php
get_footer();
