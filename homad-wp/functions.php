<?php
/**
 * Homad functions and definitions
 *
 * @package Homad
 */

if ( ! function_exists( 'homad_setup' ) ) :
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     */
    function homad_setup() {
        // Make theme available for translation.
        load_theme_textdomain( 'homad', get_template_directory() . '/languages' );

        // Add default posts and comments RSS feed links to head.
        add_theme_support( 'automatic-feed-links' );

        // Let WordPress manage the document title.
        add_theme_support( 'title-tag' );

        // Enable support for Post Thumbnails on posts and pages.
        add_theme_support( 'post-thumbnails' );

        // This theme uses wp_nav_menu() in two locations.
        register_nav_menus(
            array(
                'primary' => esc_html__( 'Primary Menu', 'homad' ),
                'mobile'  => esc_html__( 'Mobile Bottom Nav', 'homad' ),
            )
        );

        // Switch default core markup to output valid HTML5.
        add_theme_support(
            'html5',
            array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' )
        );

        // Declare support for WooCommerce.
        add_theme_support( 'woocommerce' );
    }
endif;
add_action( 'after_setup_theme', 'homad_setup' );

/**
 * Enqueue scripts and styles.
 */
function homad_scripts() {
    wp_enqueue_style( 'homad-style', get_stylesheet_uri(), array(), '1.0.0' );
}
add_action( 'wp_enqueue_scripts', 'homad_scripts' );

/**
 * Register Custom Post Types.
 */
function homad_register_cpts() {
    // CPT definitions... (same as before)
    // CPT: Services
    $labels_services = array('name' => _x( 'Services', 'Post Type General Name', 'homad' ), 'singular_name' => _x( 'Service', 'Post Type Singular Name', 'homad' ), 'menu_name' => __( 'Services', 'homad' ));
    $args_services = array('label' => __( 'Service', 'homad' ), 'description' => __( 'Homad Services', 'homad' ), 'labels' => $labels_services, 'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt' ), 'public' => true, 'show_in_menu' => true, 'menu_position' => 5, 'menu_icon' => 'dashicons-admin-tools', 'has_archive' => true, 'show_in_rest' => true);
    register_post_type( 'service', $args_services );

    // CPT: Packages
    $labels_packages = array('name' => _x( 'Packages', 'Post Type General Name', 'homad' ), 'singular_name' => _x( 'Package', 'Post Type Singular Name', 'homad' ), 'menu_name' => __( 'Packages', 'homad' ));
    $args_packages = array('label' => __( 'Package', 'homad' ), 'description' => __( 'Homad Packages', 'homad' ), 'labels' => $labels_packages, 'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt' ), 'public' => true, 'show_in_menu' => true, 'menu_position' => 6, 'menu_icon' => 'dashicons-archive', 'has_archive' => true, 'show_in_rest' => true);
    register_post_type( 'package', $args_packages );

    // CPT: Portfolio
    $labels_portfolio = array('name' => _x( 'Portfolio', 'Post Type General Name', 'homad' ), 'singular_name' => _x( 'Case Study', 'Post Type Singular Name', 'homad' ), 'menu_name' => __( 'Portfolio', 'homad' ));
    $args_portfolio = array('label' => __( 'Case Study', 'homad' ), 'description' => __( 'Portfolio Case Studies', 'homad' ), 'labels' => $labels_portfolio, 'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt' ), 'public' => true, 'show_in_menu' => true, 'menu_position' => 7, 'menu_icon' => 'dashicons-format-gallery', 'has_archive' => true, 'show_in_rest' => true);
    register_post_type( 'portfolio', $args_portfolio );

    // CPT: Leads
    $labels_leads = array('name' => _x( 'Leads', 'Post Type General Name', 'homad' ), 'singular_name' => _x( 'Lead', 'Post Type Singular Name', 'homad' ), 'menu_name' => __( 'Leads', 'homad' ));
    $args_leads = array('label' => __( 'Lead', 'homad' ), 'description' => __( 'Incoming leads/quotes', 'homad' ), 'labels' => $labels_leads, 'supports' => array( 'title', 'editor', 'custom-fields' ), 'public' => false, 'show_ui' => true, 'show_in_menu' => true, 'menu_position' => 8, 'menu_icon' => 'dashicons-email-alt');
    register_post_type( 'lead', $args_leads );
}
add_action( 'init', 'homad_register_cpts', 0 );

/**
 * Register Custom Taxonomies.
 */
function homad_register_taxonomies() {
    // Taxonomy definitions... (same as before)
    // Taxonomy: Service Type
    $labels_service_type = array('name' => _x( 'Service Types', 'taxonomy general name', 'homad' ), 'singular_name' => _x( 'Service Type', 'taxonomy singular name', 'homad' ));
    $args_service_type = array('hierarchical' => true, 'labels' => $labels_service_type, 'show_ui' => true, 'show_admin_column' => true, 'rewrite' => array( 'slug' => 'service-type' ), 'show_in_rest' => true);
    register_taxonomy( 'service_type', array( 'service' ), $args_service_type );

    // Taxonomy: Package Tier
    $labels_package_tier = array('name' => _x( 'Package Tiers', 'taxonomy general name', 'homad' ), 'singular_name' => _x( 'Package Tier', 'taxonomy singular name', 'homad' ));
    $args_package_tier = array('hierarchical' => true, 'labels' => $labels_package_tier, 'show_ui' => true, 'show_admin_column' => true, 'rewrite' => array( 'slug' => 'package-tier' ), 'show_in_rest' => true);
    register_taxonomy( 'package_tier', array( 'package' ), $args_package_tier );

    // Taxonomy: Package Segment
    $labels_package_segment = array('name' => _x( 'Package Segments', 'taxonomy general name', 'homad' ), 'singular_name' => _x( 'Package Segment', 'taxonomy singular name', 'homad' ));
    $args_package_segment = array('hierarchical' => true, 'labels' => $labels_package_segment, 'show_ui' => true, 'show_admin_column' => true, 'rewrite' => array( 'slug' => 'package-segment' ), 'show_in_rest' => true);
    register_taxonomy( 'package_segment', array( 'package' ), $args_package_segment );
}
add_action( 'init', 'homad_register_taxonomies', 0 );
