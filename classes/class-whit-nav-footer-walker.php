<?php
/**
 * Walker for handling navigation footer menus. Extended from Walker_Nav_Menu via Whit_Nav_Walker.
 */

declare(strict_types=1);

class Whit_Nav_Footer_Walker extends Whit_Nav_Walker {

    public function start_el(&$output, $data_object, $depth = 0, $args = null, $current_object_id = 0) {
        /**
         * Start of each element.  Abstract class in extending class, so much match that format
         * and cannot specify types.
         * 
         * @param string                $output             Output of walker. Passed by reference so all functions in class affect it.
         * @param object                $data_object        WP_Post object for current element (menu items are each a WP_Post object).
         * @param int                   $depth              Current depth of walker. Starts at 0 for level of first items.
         * @param null|array|object     $args               Arguments.  This will be an object if invoked with wp_nav_walker but an array
         *                                                  for other uses.
         * @param int                   $current_objct_id   Current object ID, but not passed in by default.
         */
        
        // Create anchor tag
        $a_tag = $this->html_helper->create_html_tag(
            tag_type: 'a',
            inner_html: $data_object->title,
            classes: array('whit-hover-line-only'),
            attr: array(
                'href' => $data_object->url
            ),
        );

        // Create li tag (as array since can't use closing tag)
        $li_tag_array = $this->html_helper->create_html_tag(
            tag_type: 'li',
            return_str: false
        );

        // Inserts tags into the output
        $output .= str_repeat(T, $this->base_indent + $depth) . $li_tag_array['start'] . $a_tag;

    }

    // Start each level below the top level
	public function start_lvl( &$output, $depth = 0, $args = null ) {
        /**
         * Start of each level beyond the top level.  Abstract class in extending class, so much match that format
         * and cannot specify types.
         * 
         * @param string                $output             Output of walker. Passed by reference so all functions in class affect it.
         * @param int                   $depth              Current depth of walker. Starts at 0 for first children of top-level items.
         * @param null|array|object     $args               Arguments.  This will be an object if invoked with wp_nav_walker but an array
         *                                                  for other uses.
         * @param int                   $current_objct_id   Current object ID, but not passed in by default.
         */

        // Get a formatted unoreded list tag
        // We use the array format since we only need the opening tag
        $ul_tag_array = $this->html_helper->create_html_tag(
            tag_type: 'ul',
            return_str: false,
            classes: array('list-unstyled'),
        );

        // Inserts tags into the output
        $output .= N . str_repeat(T, $this->base_indent + $depth) . $ul_tag_array['start'] . N;
    }

}