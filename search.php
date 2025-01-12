<?php

declare(strict_types=1);

get_header();

?>
        <main>
            <div class="container">
                <div id="posts" class="d-flex flex-column row-gap-3<?php echo ( $wp_query->max_num_pages <= 1 ) ? ' mb-3' : ''; ?>">
                    <h1 class="mb-3"><?php printf('Results For: “%s”', get_search_query()); ?></h1>
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
                    <p>Your search returned no results.  Please try again.</p>
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