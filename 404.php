<?php
/**
 * The template for displaying 404 pages (not found)
 */

declare(strict_types=1);

get_header();

?>
        <main>
            <div class="container">
                <div id="page-body" class="mb-3">
                    <h1 class="mb-3">Page Not Found</h1>
                    <p>The page you requested could not be found.  Please try again.</p>
                    <p><a href="<?php echo get_site_url(); ?>">Return Home</a></p>
                </div>
            </div>
        </main>
<?php

get_footer();