<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package festival
 */

get_header(); ?>

<div id="tickets-listing" class="ticket-listing">

		<?php
		if ( have_posts() ) : ?>
			<div class="container">
				<div class="content-heading hidden-xs">
					<h2>Tickets</h2>
				</div>
				<!-- /.content-heading -->

				<div class="tickets-menu">
                    <?php wp_nav_menu( array( 'theme_location' => 'menu-3', 'menu_id' => 'tickets-menu' ) ); ?>
				</div>
				<!-- /.tickets-menu -->
			</div>
			<!-- /.container -->


        <div class="tickets-wrapper">

            <div class="container clearfix">
                <?php get_template_part( 'inc/cart', 'sidebar' ); ?>
                <?
                    get_listing_template();
                ?>
            </div>
            <!-- /.container -->
        </div>
        <!-- /.tickets-wrapper -->
        <?php endif; ?>
        <div id="loadSingle" class="mfp-hide"></div>
        <!-- hidden popup container -->
</div>
<!-- /#tickets-listing.ticket-listing -->
<?php
get_footer();
