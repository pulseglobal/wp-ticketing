<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package festival
 */

?>
	</div>
	<!-- /#main -->
	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="container">
			<div class="footer-wrapper">
				<div class="logo-list">
					<ul>
						<li class="small-logo">
							<img src="<?php echo get_template_directory_uri(); ?>/img/lpp.png" alt="">
						</li>
					</ul>
				</div>
				<!-- /.logo-list -->
			</div>
			<!-- /.footer-wrapper -->
		</div>
		<!-- /.container -->
	</footer><!-- #colophon -->
</div><!-- #page -->
<div class="process-spinner">
	<div class="content text-center">
		<img src="<?php echo get_template_directory_uri(); ?>/img/order-proc.png" alt="processing order">
	</div>
	<!-- /.content -->
</div>
<!-- /.process-spinner -->

<?php wp_footer(); ?>

</body>
</html>
