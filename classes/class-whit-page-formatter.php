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
        get_the_content() . N . 
        str_repeat(T, $base_indent) . '</div>';

    return $output;
}

}


//     $post_title = $html_helper->create_html_tag(
//         tag_type: 'h2',
//         inner_html: ($excerpt_only) ? $post_link : get_the_title(),
//         classes: array('border-3', 'border-bottom', 'border-secondary', 'mb-0'),
//     );

// private function format_post_data(bool $excerpt_only): array {
//     /**
//      * Formats the inner HTML components of a post (such as title, content, etc)
//      * 
//      * @param bool      $excerpt_only   Whether to show the excerpt instead of the full text.
//      * 
//      * @return array    Returns associative array of formatted post content
//      */

//     $html_helper = $this->html_helper;
    
//     // Get the tags
//     $post_tags = $this->format_taxonomy_inline(get_the_tags());

//     // Get the categories
//     $post_cats = $this->format_taxonomy_inline(get_the_category());

//     // Get the post date
//     // Bottom margin changes if there are no tags, so this MUST go after the tags are formatted
//     $date_classes = ($post_tags) ? array('text-muted', 'mb-0') : array('text-muted');
//     $post_date = $html_helper->create_html_tag(
//         tag_type: 'p',
//         inner_html: get_the_date('F j, Y'),
//         classes: $date_classes
//     );
    
//     // Format post title as a link if in preview mode
//     if ($excerpt_only) {
//         $post_link = $html_helper->create_html_tag(
//             tag_type: 'a',
//             inner_html: get_the_title(),
//             classes: array('text-dark', 'whit-hover-bold-only'),
//             attr: array('href' => get_permalink()),
//         );
//     }
//     // Get the post title
//     $post_title = $html_helper->create_html_tag(
//         tag_type: 'h2',
//         inner_html: ($excerpt_only) ? $post_link : get_the_title(),
//         classes: array('border-3', 'border-bottom', 'border-secondary', 'mb-0'),
//     );

//     // Get the post body or excerpt
//     // Using apply filters fixes lets line breaks appear correctly
//     // in excerpts, but still ensures that this function always
//     // returns a string
//     $post_body = ($excerpt_only) ? apply_filters('the_excerpt', get_the_excerpt()) : get_the_content();

//     // Set the read more link as null, or format if not using the excerpt
//     $post_read_more = null;
//     if ($excerpt_only) {
//         $post_read_more = $html_helper->create_html_tag(
//             tag_type: 'a',
//             inner_html: 'Read More...',
//             attr: array('href' => get_permalink())
//         );
//     };

//     // Return an array of post html
//     return array(
//         'title' => $post_title,
//         'content' => $post_body,
//         'read_more' => $post_read_more,
//         'date' => $post_date,
//         'cats' => $post_cats,
//         'tags' => $post_tags
//     );
// }

// public function format_post(int $base_indent = 0, bool $excerpt_only = false): string {
//     /**
//      * Formats the post
//      * 
//      * @param int       $base_indent        Base tabs to use when indenting HTML. Default 0.
//      * @param bool      $excerpt_only       Whether to show only the excerpt. Default false.
//      * 
//      * @return string   Returns formatted HTML for post.
//      */

//     // $html_helper = $this->html_helper;
    
//     $post_data = $this->format_post_data($excerpt_only);

//     // Handle sections that can be omitted to avoid unnecessary white space in code
//     $read_more_line = ($post_data['read_more']) ? str_repeat(T, $base_indent + 1) . '<p>' . $post_data['read_more'] . '</p>' . N : '';
//     $tags_line = ($post_data['tags']) ? str_repeat(T, $base_indent + 1) . $post_data['tags'] . N : '';
//     $cats_line = ($post_data['cats']) ? str_repeat(T, $base_indent + 1) . $post_data['cats'] . N : '';

//     // Build output
//     $output = 
//         str_repeat(T, $base_indent) . '<div class="post">' . N . 
//         str_repeat(T, $base_indent + 1) . $post_data['title'] . N . 
//         str_repeat(T, $base_indent + 1) .  $post_data['date'] . N . 
//         $tags_line . 
//         str_repeat(T, $base_indent + 1) .  $post_data['content'] . N . 
//         $read_more_line . 
//         $cats_line . N . 
//         str_repeat(T, $base_indent) . '</div>' . N;

//     return $output;
// }