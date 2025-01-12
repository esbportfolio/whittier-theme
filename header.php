<?php

declare(strict_types=1);

?>
<!doctype html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Begin Wordpress header -->
<?php wp_head(); ?>
<!-- End Wordpress header -->
	</head>
    <body class="d-flex flex-column min-vh-100 bg-light">
		<header>
			<nav class="navbar navbar-expand-lg mb-4">
				<div class="container">
					<div class="d-flex">
						<?php
// Display logo if a custom logo has been selected
if (has_custom_logo()) {

	$html_helper = new Whit_Html_Helper();

	// Get the data for the logo image
	$img_id = intval(get_theme_mod('custom_logo'));
	$img_data = whit_get_image_data($img_id);

	// Create the image tag
	$img_html = $html_helper->create_html_tag(
		tag_type: 'img',
		return_str: false,
		classes: array('d-inline-block', 'align-middle', 'whit-site-logo'),
		attr: array(
			'src' => $img_data['src'],
			'alt' => $img_data['alt']
		)
	);

	// Nest the image in an anchor tag
	$logo_html = $html_helper->create_html_tag(
		tag_type: 'a',
		inner_html: $img_html['start'],
		classes: array('navbar-brand', 'me-2'),
		attr: array('href'=>get_site_url())
	);
	
	// Output the logo
	echo $logo_html . N;
}
?>
						<a class="navbar-brand py-2" href="<?php echo get_site_url(); ?>"><?php echo get_bloginfo('name'); ?></a>
					</div>
					<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>
					<div class="collapse navbar-collapse" id="navbarSupportedContent">
						<ul class="navbar-nav ms-auto">
<!-- Begin main nav walker -->
<?php
// Note: If a menu isn't found at the location below,
// wp_nav_menu falls back to the first menu created
wp_nav_menu(array(
	'menu' => whit_get_menu_id('header-menu'),
	'container' => false,
	'items_wrap' => '%3$s',
	'walker' => new Whit_Nav_Header_Walker(new Whit_Html_Helper(), 7),
));
?>
<!-- End main nav walker -->
						</ul>
						<form class="d-flex ms-md-3" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
							<input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" value="<?php echo get_search_query(); ?>" name="s">
							<button class="btn btn-success" type="submit" value="Search" >Search</button>
						</form>
					</div>
				</div>
			</nav>
		</header>
