<?php
/**
 * Custom tweaks for default theme functionality
 */

/**
 * Replace useless menu classes with custom ones
 *
 * Applies to menu in primary theme location only
 */
add_filter( 'nav_menu_css_class', function( $atts, $item, $args ) {
    if( $args->theme_location === 'primary' ) {
        // Redefine classes array
        $classes = array( 'menu-item' );

        if( $item->current === true ) {
            $classes[] = 'current-menu-item';
        }
    }

    return $classes;
}, 10, 3 );

/**
 * Remove stupid menu id attribute
 */
add_filter( 'nav_menu_item_id', '__return_empty_string' );

/**
 * Add class to link menu items
 *
 * Applies to menu in primary theme location only
 */
add_filter( 'nav_menu_link_attributes', function( $atts, $item, $args ) {
    if ( $args->theme_location === 'primary' ) {
        $atts['class'] = 'menu-item-link';
    }

    return $atts;
},  10, 3 );

/**
 * Replace annoying post classes
 */
add_filter( 'post_class', function( $classes, $class, $post_id ) {
    if( ! is_admin() ) {
        $classes = array();

        if ( $class ) {
            if ( ! is_array( $class ) ) {
                $class = preg_split( '#\s+#', $class );
            }

            $classes = array_map( 'esc_attr', $class );
        }

        // Display post type
        $classes[] = 'type-' . get_post_type( $post_id );

        // Sticky for Sticky Posts
        if ( is_sticky( $post_id ) && is_home() ) {
            $classes[] = 'sticky';
        }
    }

    return $classes;
}, 10, 3 );

/**
 * Add is- prefix to all body classes
 */
add_filter( 'body_class', function( $wp_classes, $extra_classes ) {
    $body_classes = $wp_classes + $extra_classes;

    foreach ( $body_classes as &$body_class ) {
        // Skip no-customize-support class
        if ( 'no-customize-support' !== $body_class ) {
            $body_class = 'is-' . $body_class;
        }
    }

    // Remove link to avoid unexpected behavior
    unset( $body_class );

    return $body_classes;
}, 10, 2 );


/**
 * Remove useless emojis styles
 */
add_action( 'init', function() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
} );

add_action( 'wp_enqueue_scripts', function() {
    wp_dequeue_style( 'global-styles' );
    wp_dequeue_style( 'wp-webfonts' );
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
    wp_dequeue_style( 'block-style-variation-styles' );
    wp_dequeue_style( 'classic-theme-styles' );
}, 20 );

/**
 * Remove wordpress meta for security reasons
 */
add_action( 'init', function() {
    remove_action( 'wp_head', 'wlwmanifest_link' );
    remove_action( 'wp_head', 'rsd_link' );
    remove_action( 'wp_head', 'adjacent_posts_rel_link' );
    remove_action( 'wp_head', 'rest_output_link_wp_head' );
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
    remove_action( 'wp_head', 'wp_generator' );

    // Remove rest output from xmlrpc
    remove_action( 'xmlrpc_rsd_apis', 'rest_output_rsd' );

    // Remove rest output from header
    remove_action( 'template_redirect', 'rest_output_link_header', 11 );
} );

/**
 * Disable rest api for non-priveleged users
 */
add_filter( 'rest_authentication_errors', function( $access ) {
    if ( ! is_user_logged_in() ) {
        $message = __( 'REST API restricted to authenticated users' );

        return new WP_Error( 'rest_login_required', $message, array( 'status' => rest_authorization_required_code() ) );
    }

    return $access;
} );

/**
 * Disable post attachment pages and redirect to post parent if exists
 */
add_action( 'template_redirect', function() {
    global $post;

    if ( is_attachment() ) {
        if ( isset( $post->post_parent ) && absint( $post->post_parent ) > 0 ) {
            $url = get_permalink( $post->post_parent );
        } else {
            $url = home_url( '/' );
        }

        wp_redirect( esc_url( $url ), 301 );
        exit;
    }
} );

/**
 * Remove embeds script from footer
 */
add_action( 'wp_footer', function() {
    wp_deregister_script( 'wp-embed' );
} );

/**
 * Add meta tags to header
 */
add_action( 'wp_head', function() {
    $meta = array();

    $meta[] = sprintf(
        '<meta name="description" content="%s">',
        esc_attr( get_bloginfo( 'description' ) )
    );

    $meta[] = sprintf(
        '<meta property="og:description" content="%s">',
        esc_attr( get_bloginfo( 'description' ) )
    );

    $meta[] = sprintf(
        '<meta property="og:title" content="%s">',
        esc_attr( get_bloginfo( 'name' ) )
    );

    echo implode( PHP_EOL, $meta );
}, 5 );

/**
 * Update srcset images attribute
 */
add_filter( 'max_srcset_image_width', function( $max_width ) {
    return 1200;
}, 10, 2 );

/**
 * Remove categories for posts
 */
add_action( 'init', function() {
    unregister_taxonomy_for_object_type( 'category', 'post' );
} );
