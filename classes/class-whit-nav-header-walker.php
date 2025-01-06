<?php
/**
 * Walker for handling navigation header menus. Extended from Walker_Nav_Menu via Whit_Nav_Walker.
 */

declare(strict_types=1);

class Whit_Nav_Header_Walker extends Whit_Nav_Walker {

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
        
        // Set basic data
        $el_title = $data_object->title;
        // URL is set to # if top-level element with children
        // NOTE: Bootstrap treats all top-level elements with children as dropdowns, NOT links,
        // so this makes that behavior more transparent.
        $el_url = ($depth === 0 && $args->walker->has_children) ? '#' : $data_object->url;
        // Element also active if top-level ancestor of active element
        $el_active = ($data_object->current || ($depth === 0 && $data_object->current_item_ancestor));

        // Set default conditions
        $li_classes = array();
        $a_classes = array();
        $a_attr = array(
            'href' => $el_url
        );

        // Handle add list item and anchor classes and attributes based on depth
        if ($depth === 0) {
            // Add classes for top-level elements
            $li_classes[] = 'nav-item';
            $a_classes[] = 'nav-link';
            // Add additional dropdown classes for top level elements with children
            if ($args->walker->has_children) {
                $li_classes[] = 'dropdown';
                $a_classes[] = 'dropdown-toggle';
                $a_attr += array(
                    'role' => 'button',
                    'data-bs-toggle' => 'dropdown'
                );
            }
        } else {
            // Add classes for links inside a dropdown menu (depth > 0)
            $a_classes[] = 'dropdown-item';
        }

        // Add a class if the element or its descendent is active
        if ($el_active) {
            $a_classes[] = 'active';
        }

        // Get a formatted anchor tag
        $a_tag = $this->html_helper->create_html_tag(
            tag_type: 'a',
            inner_html: $el_title,
            classes: $a_classes,
            attr: $a_attr
        );

        // Get a formatted list item tag
        // We use the array format since we only need the opening tag
        $li_tag_array = $this->html_helper->create_html_tag(
            tag_type: 'li',
            return_str: false,
            classes: $li_classes
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

        // Set classes for the dropdown menu based on depth
		if ( $depth === 0 ) {
			$ul_classes = array('dropdown-menu');
		} else {
			$ul_classes = array('submenu', 'list-unstyled', 'ms-3');
		}

        // Get a formatted unoreded list tag
        // We use the array format since we only need the opening tag
        $ul_tag_array = $this->html_helper->create_html_tag(
            tag_type: 'ul',
            return_str: false,
            classes: $ul_classes
        );

        // Inserts tags into the output
        $output .= N . str_repeat(T, $this->base_indent + $depth) . $ul_tag_array['start'] . N;
    }

}