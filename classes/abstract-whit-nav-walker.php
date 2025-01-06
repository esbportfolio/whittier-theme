<?php
/**
 * Abstract class that extends the abstract Walker_Nav_Menu class
 */

declare(strict_types=1);

abstract class Whit_Nav_Walker extends Walker_Nav_Menu {

    // Dependency injection
    public function __construct(protected Whit_Html_Helper $html_helper, protected int $base_indent = 0) {}

    // End each level below the top level
    public function end_lvl( &$output, $depth = 0, $args = null ) {
        /**
         * End of each level beyond the top level.  Abstract class in parent class, so much match that format
         * and cannot specify types.
         * 
         * @param string                $output             Output of walker. Passed by reference so all functions in class affect it.
         * @param int                   $depth              Current depth of walker. Starts at 0 for first children of top-level items.
         * @param null|array|object     $args               Arguments.  This will be an object if invoked with wp_nav_walker but an array
         *                                                  for other uses.
         * @param int                   $current_objct_id   Current object ID, but not passed in by default.
         */
        
        $output .= str_repeat(T, $this->base_indent + $depth) . '</ul>';
    }

}