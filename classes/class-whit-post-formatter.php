<?php
/**
 * Class that formats pagination for display
 */

declare(strict_types=1);

class Whit_Post_Formatter {

    // Dependency injection
    public function __construct(private Whit_Html_Helper $html_helper) {}

    private function format_taxonomy_inline(bool|array $items): string {
        /**
         * Format taxonomoy inline for use in post cards.
         * 
         * @param bool|array     $items     Array of taxonomy items. Will be false if no items are present.
         * 
         * @return string        Returns formatted HTML with the tags or categories list
         */
        
        // Set default condition
        $output = '';

        // Aliases reference to html_helper
        $html_helper = $this->html_helper;

        // Exits if the taxonomy isn't a post tag or category
        // TODO Handle exceptions properly
        if ($items && ! ($items[0]->taxonomy === 'post_tag' || $items[0]->taxonomy === 'category')) {
            return '<p>Unable to use ' . __METHOD__ . ' for ' . $items[0]->taxonomy . ' ' . __METHOD__ . ' only supports tags and categories.</p>';
        }

        if ($items) {
            $el_array = array();

            foreach($items as $item) {

                // Set the classes for this taxonomy class
                $link_classes = ($item->taxonomy === 'post_tag') ? 
                    array('text-decoration-none', 'link-light') : 
                    array('whit-hover-line-only', 'text-muted');

                // Create anchor tags for each taxonomy entry
                $a_tag = $html_helper->create_html_tag(
                    tag_type: 'a',
                    inner_html: $item->name,
                    classes: $link_classes,
                    attr: array('href' => get_tag_link($item->term_id))
                );

                // Nest post tags within a span tag
                if ($item->taxonomy === 'post_tag') {
                    $span_tag = $html_helper->create_html_tag(
                        tag_type: 'span',
                        inner_html: $a_tag,
                        classes: array('badge', 'bg-orange')
                    );
                }

                $el_array[] = $span_tag ?? $a_tag;

            }

            // When all tags have been formatted, insert them into a paragraph tag
            if ($item->taxonomy === 'post_tag') {
                $output = '<p>' . implode(' ', $el_array) . '</p>';
            } else {
                $output = '<p class="text-muted"><em>Posted In: ' . implode(', ', $el_array) . '</em></p>';
            }
            
        }

        return $output;
    }

    private function format_post_data(bool $excerpt_only): array {
        /**
         * Formats the inner HTML components of a post (such as title, content, etc)
         * 
         * @param bool      $excerpt_only   Whether to show the excerpt instead of the full text.
         * 
         * @return array    Returns associative array of formatted post content
         */

        $html_helper = $this->html_helper;
        
        // Get the tags
        $post_tags = $this->format_taxonomy_inline(get_the_tags());
    
        // Get the categories
        $post_cats = $this->format_taxonomy_inline(get_the_category());

        // Get the post date
        // Bottom margin changes if there are no tags, so this MUST go after the tags are formatted
        $date_classes = ($post_tags) ? array('text-muted', 'mb-0') : array('text-muted');
        $post_date = $html_helper->create_html_tag(
            tag_type: 'p',
            inner_html: get_the_date('F j, Y'),
            classes: $date_classes
        );
        
        // Format post title as a link if in preview mode
        if ($excerpt_only) {
            $post_link = $html_helper->create_html_tag(
                tag_type: 'a',
                inner_html: get_the_title(),
                classes: array('text-dark', 'whit-hover-bold-only'),
                attr: array('href' => get_permalink()),
            );
        }
        // Get the post title
        $post_title = $html_helper->create_html_tag(
            tag_type: 'h2',
            inner_html: ($excerpt_only) ? $post_link : get_the_title(),
            classes: array('border-3', 'border-bottom', 'border-secondary', 'mb-0'),
        );
    
        // Get the post body or excerpt
        // Using apply filters fixes lets line breaks appear correctly
        // in excerpts, but still ensures that this function always
        // returns a string
        $post_body = ($excerpt_only) ? apply_filters('the_excerpt', get_the_excerpt()) : apply_filters('the_content', get_the_content());

        // Set the read more link as null, or format if not using the excerpt
        $post_read_more = null;
        if ($excerpt_only) {
            $post_read_more = $html_helper->create_html_tag(
                tag_type: 'a',
                inner_html: 'Read More...',
                attr: array('href' => get_permalink())
            );
        };
    
        // Return an array of post html
        return array(
            'title' => $post_title,
            'content' => $post_body,
            'read_more' => $post_read_more,
            'date' => $post_date,
            'cats' => $post_cats,
            'tags' => $post_tags
        );
    }

    public function format_post(int $base_indent = 0, bool $excerpt_only = false): string {
        /**
         * Formats the post
         * 
         * @param int       $base_indent        Base tabs to use when indenting HTML. Default 0.
         * @param bool      $excerpt_only       Whether to show only the excerpt. Default false.
         * 
         * @return string   Returns formatted HTML for post.
         */
        
        $post_data = $this->format_post_data($excerpt_only);

        // Handle sections that can be omitted to avoid unnecessary white space in code
        $read_more_line = ($post_data['read_more']) ? str_repeat(T, $base_indent + 1) . '<p>' . $post_data['read_more'] . '</p>' . N : '';
        $tags_line = ($post_data['tags']) ? str_repeat(T, $base_indent + 1) . $post_data['tags'] . N : '';
        $cats_line = ($post_data['cats']) ? str_repeat(T, $base_indent + 1) . $post_data['cats'] . N : '';

        // Build output
        $output = 
            str_repeat(T, $base_indent) . '<div class="post">' . N . 
            str_repeat(T, $base_indent + 1) . $post_data['title'] . N . 
            str_repeat(T, $base_indent + 1) .  $post_data['date'] . N . 
            $tags_line . 
            str_repeat(T, $base_indent + 1) .  $post_data['content'] . N . 
            $read_more_line . 
            $cats_line . N . 
            str_repeat(T, $base_indent) . '</div>' . N;

        return $output;
    }

}