<?php
/**
 * Custom snippets to upgrade instapress theme
 */

if (!defined('WPINC')) {
    die;
}

require_once get_stylesheet_directory() . '/modules/video.php';
require_once get_stylesheet_directory() . '/modules/tweaks.php';

/**
 * Add custom styles
 */
add_action ('wp_enqueue_scripts', function() {
    wp_enqueue_style( 'instapress-customs', content_url('/customs/assets/styles.css'), array('instapress-styles'), '1.1' );
} );

/**
 * Add custom scripts
 */
add_action ('wp_enqueue_scripts', function() {
    wp_enqueue_script( 'instapress-customs', content_url('/customs/assets/scripts.js'), array(), '1.1', true );
} );
