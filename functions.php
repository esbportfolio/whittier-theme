<?php
/**
 * Whittier Solidarity Theme functions and definitions
 * 
 * This file contains only functions related to setting up the theme.
 * Any other functions that need to be loaded should be placed inside
 * the /inc directory.
 */

declare(strict_types=1);

/* ----- ACTIONS ----- */

/* ## HOOK: after_setup_theme ##
This hook fires when the theme is initialized.
This can be used for init actions that need to happen when a theme
is launched.
*/

// Import dependencies
if (!function_exists('whit_dependency_setup')) {
    function whit_dependency_setup() {

        // List of required files
        $required_files = array(
            get_stylesheet_directory() . '/inc/constants.php', // Constants
            get_stylesheet_directory() . '/inc/helpers.php', // Helper functions

            get_stylesheet_directory() . '/classes/class-whit-html-helper.php', // HTML helper class, goes before rest of classes

            get_stylesheet_directory() . '/classes/abstract-whit-nav-walker.php', // Walker - Navigation walker abstract class
            get_stylesheet_directory() . '/classes/class-whit-nav-header-walker.php', // Walker - Header navigation walker
            get_stylesheet_directory() . '/classes/class-whit-nav-footer-walker.php', // Walker - Footer navigation walker
            get_stylesheet_directory() . '/classes/class-whit-comment-walker.php', // Walker - Comment walker

            get_stylesheet_directory() . '/classes/class-whit-form-formatter.php', // Class for handling form formatting
            get_stylesheet_directory() . '/classes/class-whit-page-formatter.php', // Class for handling page formatting
            get_stylesheet_directory() . '/classes/class-whit-pagination-formatter.php', // Class for handling pagination
            get_stylesheet_directory() . '/classes/class-whit-post-formatter.php', // Class for handling post formatting
        );
        
        // Require files
        foreach ($required_files as $dependency) {
            require_once($dependency);
        }
    }
}

add_action( 'after_setup_theme', 'whit_dependency_setup' );

// Add support for theme features
if (!function_exists('whit_theme_setup')) {
    
    function whit_theme_setup() {

		// Let Wordpress manage site title
		add_theme_support( 'title-tag' );
		// Add support for custom logo
		add_theme_support( 'custom-logo' );

        register_nav_menus( array(
            'header-menu' => 'Header Menu',
            'footer-menu' => 'Footer Menu (no nesting)'
        ) );
        
    }
}

add_action( 'after_setup_theme', 'whit_theme_setup' );

/* ## HOOK: wp_enqueue_scripts ##
This hook fires when scripts and styles are enqueued.
This can be used to enqueue both scripts and styles.  Use
the dependency array to manage styles/scripts that need to
be loaded after styles/scripts.
*/

// Add CSS and JS files
function whit_enqueue_scripts() {
	
    // Bootstrap CSS (local due to color customization)
    wp_enqueue_style(
        'bootstrap_css',
        get_template_directory_uri() . '/css/custom-bootstrap.css'
    );

    // Local CSS (depends on Bootstrap)
    wp_enqueue_style(
        'whit_main_css',
        get_stylesheet_uri(),
        array('bootstrap_css')
    );

    // Bootstrap JS (loaded from CDN)
    wp_enqueue_script(
        'bootstrap_js',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js',
        array(),
        false,
        array(
            'strategy' => 'defer'
        )
    );

    // WP comment reply JavaScript (loaded only if necesary)
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

add_action( 'wp_enqueue_scripts', 'whit_enqueue_scripts' );

/* ----- FILTERS ----- */

/* ## FILTER: comment_form_default_fields ##
This hook fires when the default fields for a comment form
are loaded.  This can be used to remove default fields.
*/

function whit_remove_website_field( $fields ) {
	unset( $fields['url'] );
	return $fields;
}

add_filter( 'comment_form_default_fields', 'whit_remove_website_field' );