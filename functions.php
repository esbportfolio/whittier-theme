<?php
/**
 * This file contains only functions related to setting up the theme.
 * Any other functions that need to be loaded should be placed inside
 * the /inc directory.
 */

declare(strict_types=1);

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
These hook fires when scripts and styles are enqueued.
This can be used to enqueue both scripts and styles.  Use
the dependency array to manage styles/scripts that need to
be loaded after styles/scripts.
*/

/* ## HOOK: wp_enqueue_scripts ##
These hook fires when scripts and styles are enqueued.
This can be used to enqueue both scripts and styles.  Use
the dependency array to manage styles/scripts that need to
be loaded after styles/scripts.
*/

// Add CSS and JS files
function whit_enqueue_scripts() {
	wp_enqueue_style(
        'bootstrap_css',
        get_template_directory_uri() . '/css/custom-bootstrap.css'
    );
    wp_enqueue_style(
        'whit_main_css',
        get_stylesheet_uri(),
        array('bootstrap_css')
    );
    wp_enqueue_script(
        'bootstrap_js',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js',
        array(),
        false,
        array(
            'strategy' => 'defer'
        )
    );
}

add_action( 'wp_enqueue_scripts', 'whit_enqueue_scripts' );