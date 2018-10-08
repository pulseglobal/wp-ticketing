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

<div id="about-area">
    <div class="container">
        <div class="content-heading">
            <h2>Tickets</h2>
        </div>
        <!-- /.content-heading -->
        <div class="cta-wrapper">
            <a href="/tickets" class="btn btn-bordered btn-cta-color single-page-link">
                View Tickets
            </a>
            <!-- /.btn btn-bordered btn-cta-color -->
        </div>
        <!-- /.cta-wrapper -->
    </div>
    <!-- /.container -->
</div>
<!-- /#about-area -->

<?php
get_footer();
