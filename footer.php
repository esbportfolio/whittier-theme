		<footer class="mt-auto">
			<div class="py-3 bg-orange">
				<div class="container">
					<div class="row text-black whit-override-link-color">
						<div class="col-12 col-md-6"><h6 class="fw-bold"><a class="whit-hover-line-only" href="<?php echo get_site_url(); ?>"><?php echo get_bloginfo('name'); ?></a></h6></div>
						<div class="col-12 col-md-6 text-start text-md-center">
							<p>Be Social</p>
							<ul class="list-unstyled">
<?php
if (whit_get_menu_id('footer-menu')) {
	wp_nav_menu(array(
		'menu' => whit_get_menu_id('footer-menu'),
		'container' => false,
		'items_wrap' => '%3$s',
		'walker' => new Whit_Nav_Footer_Walker(new Whit_Html_Helper(), 8),
	));
} else {
	echo '<li>Find us on social media!</li>';
}
?>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="bg-dark text-light">
				<div class="container-fluid whit-copyright">
					<p class="m-0">Copyright <?php echo date("Y"); ?> Whittier Solidarity Network // Wordpress theme by <a class="text-light" href="https://esbportfolio.com/">Elizabeth Sullivan-Burton</a></p>
			</div>
			</div>
		</footer>
<!-- Begin Wordpress footer -->
<?php wp_footer(); ?>
<!-- End Wordpress footer -->
	</body>
</html>