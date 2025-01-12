<?php
/**
 * The template for displaying comments
 * 
 * This is the template that displays the area of the page that contains the pagination.
 * 
 * This should always be called inside a conditional statement, since the pagination section
 * shouldn't be created if it isn't necessary.  Variables in this file should be scoped
 * to that conditional and should *not* be global.
 */

declare(strict_types=1);

?>
                </div>
                <div id="pagination">
                    <nav aria-label="Page Navigation">
                        <ul class="pagination justify-content-center">
<?php
$pagination_formatter = new Whit_Pagination_Formatter($wp_query, new Whit_Html_Helper());
echo $pagination_formatter->format_links(8);
?>
                        </ul>
                    </nav>
