<?php

declare(strict_types=1);

get_header();

?>
        <main>
            <div class="container">
                <div id="page-body" class="d-flex flex-column row-gap-3 mb-3">
<?php
if ( have_posts() ) {
	// If there are posts, create a Post Formatter object
	$page_formatter = new Whit_Page_Formatter(new Whit_Html_Helper);
	while ( have_posts() ) {
		the_post();
        echo $page_formatter->format_page(5);
	}
}
?>
                </div>
            </div>
        </main>
<?php

get_footer();