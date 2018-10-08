<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package festival
 */

get_header(); ?>
<div id="journal-listing">
	<?php
	while ( have_posts() ) : the_post();

		get_template_part( 'template-parts/content', 'single_journal' );

	endwhile; // End of the loop.
	?>
</div>
<!-- /#journal-listing -->
<?php
get_footer();
