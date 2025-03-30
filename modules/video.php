<?php
/**
 * Custom filters for video module
 */

/**
 * Register video post type
 */
add_action( 'init', function() {
    register_post_type( 'video', array(
        'labels'                => array(
            'name'              => __( 'Video', 'instapress' ),
            'singular_name'     => __( 'Add video', 'instapress' ),
            'add_new'           => __( 'Add video', 'instapress' ),
            'menu_name'         => __( 'Video', 'instapress' )
        ),
        'label'                 => __( 'Video', 'instapress' ),
        'supports'              => array( 'title', 'editor', 'author', 'comments' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 10,
        'menu_icon'             => 'dashicons-editor-video',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'taxonomies'            => array( 'post_tag' ),
        'map_meta_cap'          => true,
        'show_in_rest'          => true,
    ) );
} );

/**
 * Update thumbnails for video posts
 */
add_filter( 'post_thumbnail_html', function( $html, $post_id ) {
    if ( 'video' !== get_post_type( $post_id ) ) {
        return $html;
    }

    $content = get_post_field( 'post_content', $post_id );

    // Parse YouTube video id from url
    preg_match('#(?:youtube\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})#i', $content, $match);

    if ( empty( $match[1] ) ) {
        return $html;
    }

    $html = sprintf(
        '<div class="card-thumbnail-video" data-src="%s" style="background-image: url(%s)"></div>',

        sprintf(
            'https://www.youtube.com/embed/%s?autoplay=1',
            esc_attr( $match[1] )
        ),
        sprintf(
            'https://img.youtube.com/vi/%s/maxresdefault.jpg',
            esc_attr( $match[1] )
        )
    );

    return $html;
}, 10, 2 );

/**
 * Remove title from video archive
 */
add_filter( 'get_the_archive_title', function( $title ) {
    if( is_post_type_archive( 'video' ) ) {
        $title = null;
    }

    return $title;
} );