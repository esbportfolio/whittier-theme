<?php
/**
 * Pagination template part. Should always be called inside a conditional statement, since
 * the pagination sections shouldn't be created if pagination isn't necessary.
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
