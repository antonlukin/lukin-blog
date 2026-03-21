<?php
/**
 * Custom snippets to upgrade instapress theme
 */

if (!defined('WPINC')) {
    die;
}

require_once get_stylesheet_directory() . '/modules/video.php';
require_once get_stylesheet_directory() . '/modules/tweaks.php';
require_once get_stylesheet_directory() . '/modules/live.php';
/**
 * Add custom styles
 */
add_action ('wp_enqueue_scripts', function() {
    wp_enqueue_style(
        'lukin-blog',
        get_stylesheet_directory_uri() . '/assets/common.css',
        array(),
        filemtime( get_stylesheet_directory() . '/assets/common.css' )
    );
}, 20 );

/**
 * Add custom scripts
 */
add_action ('wp_enqueue_scripts', function() {
    wp_enqueue_script(
        'lukin-blog',
        get_stylesheet_directory_uri() . '/assets/common.js',
        array(),
        filemtime( get_stylesheet_directory() . '/assets/common.js' ),
        true
    );
}, 20 );
