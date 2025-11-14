<?php
/**
 * TRYGO Theme setup.
 */
function trygo_theme_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );

    register_nav_menus(
        [
            'primary'        => __( 'Primary Navigation', 'trygo' ),
            'footer_main'    => __( 'Footer Main Navigation', 'trygo' ),
            'footer_tools'   => __( 'Footer Tools Links', 'trygo' ),
            'footer_features'=> __( 'Footer Feature Links', 'trygo' ),
            'footer_who'     => __( 'Footer Audience Links', 'trygo' ),
            'footer_legal'   => __( 'Footer Legal Links', 'trygo' ),
        ]
    );
}
add_action( 'after_setup_theme', 'trygo_theme_setup' );

/**
 * Print structured data JSON-LD in the head.
 */
function trygo_print_structured_data() {
    // Early return in admin.
    if ( is_admin() ) {
        return;
    }

    // Gather site metadata.
    $site_name        = get_bloginfo( 'name' );
    $site_description = get_bloginfo( 'description' );
    $site_url         = site_url();

    // Determine fallback image URL.
    $fallback_image = '';
    $og_image_path  = get_template_directory() . '/assets/images/trygo-og.png';
    $logo_path      = get_template_directory() . '/assets/images/trygo-logo.png';
    
    if ( file_exists( $og_image_path ) ) {
        $fallback_image = get_template_directory_uri() . '/assets/images/trygo-og.png';
    } elseif ( file_exists( $logo_path ) ) {
        $fallback_image = get_template_directory_uri() . '/assets/images/trygo-logo.png';
    }

    // Determine schema type.
    $schema_type = 'SoftwareApplication';
    if ( is_singular( 'features' ) || is_singular( 'post' ) ) {
        $schema_type = 'Product';
    }

    // Build JSON-LD object.
    $structured_data = [
        '@context'    => 'https://schema.org',
        '@type'       => $schema_type,
        'name'        => $site_name,
        'url'         => $site_url,
        'description' => $site_description,
        'image'       => $fallback_image,
        'brand'       => [
            '@type' => 'Brand',
            'name'  => $site_name,
        ],
        'audience'    => [
            '@type'        => 'Audience',
            'audienceType' => 'Business',
        ],
    ];

    // Add offers for Product type.
    if ( 'Product' === $schema_type ) {
        $structured_data['offers'] = [
            '@type'         => 'Offer',
            'priceCurrency' => 'USD',
            'price'         => '0.00',
            'availability'  => 'InStock',
            'url'           => $site_url,
        ];
    }

    // Encode and output.
    $json_output = wp_json_encode( $structured_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
    if ( $json_output ) {
        echo '<script type="application/ld+json">' . $json_output . '</script>' . "\n";
    }
}
add_action( 'wp_head', 'trygo_print_structured_data', 1 );

/**
 * Enqueue theme assets.
 */
function trygo_theme_assets() {
    $theme_version = wp_get_theme()->get( 'Version' );

    wp_enqueue_style(
        'trygo-style',
        get_template_directory_uri() . '/style.css',
        [],
        $theme_version
    );

    wp_enqueue_script(
        'trygo-modal',
        get_template_directory_uri() . '/modal.js',
        [],
        $theme_version,
        true
    );

    wp_enqueue_script(
        'trygo-nav',
        get_template_directory_uri() . '/nav.js',
        [],
        $theme_version,
        true
    );

    wp_enqueue_script(
        'trygo-product',
        get_template_directory_uri() . '/product.js',
        [],
        $theme_version,
        true
    );
}
add_action( 'wp_enqueue_scripts', 'trygo_theme_assets' );

/**
 * Estimate reading time for a post.
 *
 * @param int $post_id Post ID.
 * @return string Human readable time label.
 */
function trygo_estimated_reading_time( $post_id ) {
    $post = get_post( $post_id );

    if ( ! $post ) {
        return __( '3 min read', 'trygo' );
    }

    $word_count = str_word_count( wp_strip_all_tags( $post->post_content ) );
    $minutes    = max( 1, ceil( $word_count / 200 ) );

    /* translators: %d: number of minutes. */
    return sprintf( _n( '%d min read', '%d mins read', $minutes, 'trygo' ), $minutes );
}

/**
 * Register custom post types and taxonomies.
 */
function trygo_register_content_types() {
    $labels = [
        'name'               => _x( 'Features', 'post type general name', 'trygo' ),
        'singular_name'      => _x( 'Feature', 'post type singular name', 'trygo' ),
        'add_new'            => _x( 'Add New', 'feature', 'trygo' ),
        'add_new_item'       => __( 'Add New Feature', 'trygo' ),
        'edit_item'          => __( 'Edit Feature', 'trygo' ),
        'new_item'           => __( 'New Feature', 'trygo' ),
        'view_item'          => __( 'View Feature', 'trygo' ),
        'search_items'       => __( 'Search Features', 'trygo' ),
        'not_found'          => __( 'No features found.', 'trygo' ),
        'not_found_in_trash' => __( 'No features found in Trash.', 'trygo' ),
        'all_items'          => __( 'All Features', 'trygo' ),
        'menu_name'          => __( 'Features', 'trygo' ),
    ];

    if ( ! post_type_exists( 'features' ) ) {
        register_post_type(
            'features',
            [
                'labels'             => $labels,
                'public'             => true,
                'show_in_rest'       => true,
                'has_archive'        => true,
                'rewrite'            => [ 'slug' => 'features' ],
                'menu_icon'          => 'dashicons-layers',
                'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
                'taxonomies'         => [ 'feature_type' ],
            ]
        );
    }

    $taxonomy_labels = [
        'name'              => _x( 'Feature Types', 'taxonomy general name', 'trygo' ),
        'singular_name'     => _x( 'Feature Type', 'taxonomy singular name', 'trygo' ),
        'search_items'      => __( 'Search Feature Types', 'trygo' ),
        'all_items'         => __( 'All Feature Types', 'trygo' ),
        'parent_item'       => __( 'Parent Feature Type', 'trygo' ),
        'parent_item_colon' => __( 'Parent Feature Type:', 'trygo' ),
        'edit_item'         => __( 'Edit Feature Type', 'trygo' ),
        'update_item'       => __( 'Update Feature Type', 'trygo' ),
        'add_new_item'      => __( 'Add New Feature Type', 'trygo' ),
        'new_item_name'     => __( 'New Feature Type Name', 'trygo' ),
        'menu_name'         => __( 'Feature Types', 'trygo' ),
    ];

    if ( ! taxonomy_exists( 'feature_type' ) ) {
        register_taxonomy(
            'feature_type',
            'features',
            [
                'labels'            => $taxonomy_labels,
                'hierarchical'      => true,
                'show_in_rest'      => true,
                'rewrite'           => [ 'slug' => 'feature-type' ],
            ]
        );
    }

    if ( taxonomy_exists( 'feature_type' ) && post_type_exists( 'features' ) ) {
        register_taxonomy_for_object_type( 'feature_type', 'features' );
    }
}
add_action( 'init', 'trygo_register_content_types' );
