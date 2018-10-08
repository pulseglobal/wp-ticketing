<?php
/**
 * Template name: Confirmation Page
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
            <?php if (!empty($_SESSION['cart'])) unset($_SESSION['cart']); ?>
            <?php if (!empty($_SESSION['order'])): $_SESSION['confirmation'] = $_SESSION['order']; unset($_SESSION['order']); endif; ?>
            <?php if (!empty($_SESSION['placed_order'])): $_SESSION['confirmation']['last_order'] = $_SESSION['placed_order']; unset($_SESSION['placed_order']); endif; ?>
        <div class="content-heading">
            <h2>ORDER CONFIRMED</h2>
        </div>
        <!-- /.content-heading -->
        <div class="journal-post-wrapper">
            <div class="journal-single-wrapper">
                <div class="container">
                    <div id="journal-content" class="journal-entry error-entry confirm-entry">
                        <div class="content-wrapper">
                            <div class="content-wrapper text-center">
                                <h3>
                                    Thank you! Your booking is complete
                                    <?php if (!empty($_SESSION['confirmation'])): ?>
                                        - Order Number <?=$_SESSION['confirmation']['last_order']['order_number']; ?>
                                        <br/>
                                        Your Tickets will be sent to <?=$_SESSION['confirmation']['customer_email']; ?>
                                    <?php endif; ?>
                                </h3>

                                <p>Please check your junk mail or check your email address above
                                    if you don't receive your tickets</p>

                                <h3><a href="https://pulseradio.net/users/signin" target="_blank">Click here</a> to access your tickets online</h3>

                                <p>Create an account using your email above and go to 'My Tickets'</p>

                                <div class="status-icon-wrapper">
                                    <img src="<?php echo get_template_directory_uri(); ?>/img/status-success.png"
                                         alt="">
                                </div>
                                <!-- /.icon-wrapper -->
                            </div>
                            <!-- /.content-wrapper -->
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
        <?php endif; ?>
    </div>
    <!-- /.container -->
</div>
<!-- /#journal-listing -->
<div id="loadSingle" class="mfp-hide"></div>
<!-- hidden popup container -->
<?php
get_footer();
