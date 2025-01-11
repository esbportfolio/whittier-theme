<?php

declare(strict_types=1);

get_header();

?>
        <main>
            <div class="container">
                <div id="posts" class="d-flex flex-column row-gap-3 mb-3">
<?php
if ( have_posts() ) {
	// If there are posts, create a Post Formatter object
	$post_formatter = new Whit_Post_Formatter(new Whit_Html_Helper);
	while ( have_posts() ) {
		the_post();
        echo $post_formatter->format_post(5);

        // If there are comments or if comments are open, insert the comments template
        if ( comments_open() || intval(get_comments_number()) > 0 ) {
            comments_template('/comments.php');
        }
	}
}
?>
                </div>
            </div>    
        </main>
<?php

get_footer();