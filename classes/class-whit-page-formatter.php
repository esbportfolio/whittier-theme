<?php
/**
 * Class that formats pages for display
 */

declare(strict_types=1);

class Whit_Page_Formatter {

    // Dependency injection
    public function __construct(private Whit_Html_Helper $html_helper) {}

    public function format_page(int $base_indent = 0): string {
        /**
         * Formats the post
         * 
         * @param int       $base_indent        Base tabs to use when indenting HTML. Default 0.
         * @param bool      $excerpt_only       Whether to show only the excerpt. Default false.
         * 
         * @return string   Returns formatted HTML for post.
         */
        
        // Create title line (omit if on site home)
        $title_line = '';
        if ( !is_front_page() ) {
            $title_tag = $this->html_helper->create_html_tag(
                tag_type: 'h1',
                inner_html: get_the_title(),
                classes: array('mb-3'),
            );
            $title_line = str_repeat(T, $base_indent + 1) . $title_tag . N;
        }
        
        $output =
            str_repeat(T, $base_indent) . '<div class="page">' . N . 
            $title_line . 
            apply_filters('the_content', get_the_content()) . N . // Filters are necessary to make plugin work correctly
            str_repeat(T, $base_indent) . '</div>';

        return $output;
    }

}