<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package festival
 */

get_header(); ?>

<div id="journal-listing">
    <div class="container">

        <div class="content-heading">
            <h2>404 Not Found</h2>
        </div>
        <!-- /.content-heading -->
        <div class="journal-post-wrapper">
            <div class="journal-single-wrapper">
                <div class="container">
                    <div id="journal-content" class="journal-entry error-entry">
                        <div class="content-wrapper text-center">
                            <h3>Sorry, an error has occured, Requested page not found!</h3>
                            <div class="status-icon-wrapper">
                                <img src="<?php echo get_template_directory_uri(); ?>/img/error-pic.png" alt="">
                            </div>
                            <!-- /.icon-wrapper -->
                        </div>
                        <!-- /.content-wrapper -->
                    </div>
                    <!-- /.journal-entry -->
                </div>
                <!-- /.container -->
            </div>
            <!-- /.journal-single-wrapper -->
        </div>
        <!-- /.journal-post-wrapper -->
    </div>
    <!-- /.container -->
</div>
<!-- /#journal-listing -->

<?php
get_footer();
