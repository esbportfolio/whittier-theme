<?php
/**
 * The template for displaying archive pages (including tag and category archives)
 */

declare(strict_types=1);

get_header();

?>
        <main>
            <div class="container">
                <div id="posts" class="d-flex flex-column row-gap-3<?php echo ( $wp_query->max_num_pages <= 1 ) ? ' mb-3' : ''; ?>">
                    <h1 class="mb-3"><?php echo the_archive_title(); ?></h1>
<?php
if ( have_posts() ) {
	// If there are posts, create a Post Formatter object
	$post_formatter = new Whit_Post_Formatter(new Whit_Html_Helper);
	while ( have_posts() ) {
		the_post();
        echo $post_formatter->format_post(5, true);
	}
} else {
    ?>
                    <p>No posts yet, but check back to see what's coming soon!</p>
                    <p><a href="<?php echo get_site_url(); ?>">Return Home</a></p>
    <?php
}

if ( $wp_query->max_num_pages > 1 ) {
    get_template_part('pagination');
}
?>
                </div>
            </div>    
        </main>
<?php

get_footer();