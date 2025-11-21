<?php
/**
 * FAQs Theme Functions
 *
 * @package FAQs_Theme
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Theme constants
define('FAQS_THEME_VERSION', '1.0.0');
define('FAQS_THEME_DIR', get_template_directory());
define('FAQS_THEME_URI', get_template_directory_uri());

/**
 * Theme Setup
 */
function faqs_theme_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails
    add_theme_support('post-thumbnails');

    // Enable support for HTML5 markup
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script'
    ));

    // Add theme support for selective refresh for widgets
    add_theme_support('customize-selective-refresh-widgets');

    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'faqs-theme'),
        'footer' => __('Footer Menu', 'faqs-theme'),
    ));

    // Add support for responsive embeds
    add_theme_support('responsive-embeds');

    // Add support for editor styles
    add_theme_support('editor-styles');

    // Add support for wide alignment
    add_theme_support('align-wide');
}
add_action('after_setup_theme', 'faqs_theme_setup');

/**
 * Enqueue scripts and styles
 */
function faqs_theme_enqueue_scripts() {
    // Main stylesheet (Tailwind CSS compiled)
    wp_enqueue_style('faqs-theme-style', FAQS_THEME_URI . '/assets/css/style.css', array(), FAQS_THEME_VERSION);

    // Main JavaScript
    wp_enqueue_script('faqs-theme-script', FAQS_THEME_URI . '/assets/js/main.js', array(), FAQS_THEME_VERSION, true);

    // Localize script for AJAX
    wp_localize_script('faqs-theme-script', 'faqsTheme', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('faqs_theme_nonce'),
        'homeUrl' => home_url('/'),
        'searchPlaceholder' => __('Search FAQs...', 'faqs-theme'),
    ));

    // Comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'faqs_theme_enqueue_scripts');

/**
 * Add resource hints for performance
 */
function faqs_theme_resource_hints($urls, $relation_type) {
    if ('preconnect' === $relation_type) {
        $urls[] = array(
            'href' => 'https://fonts.googleapis.com',
            'crossorigin',
        );
        $urls[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin',
        );
    }
    return $urls;
}
add_filter('wp_resource_hints', 'faqs_theme_resource_hints', 10, 2);

/**
 * Register widget areas
 */
function faqs_theme_widgets_init() {
    register_sidebar(array(
        'name' => __('Sidebar', 'faqs-theme'),
        'id' => 'sidebar-1',
        'description' => __('Add widgets here to appear in your sidebar.', 'faqs-theme'),
        'before_widget' => '<section id="%1$s" class="widget %2$s mb-8">',
        'after_widget' => '</section>',
        'before_title' => '<h3 class="widget-title text-xl font-bold mb-4">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Footer 1', 'faqs-theme'),
        'id' => 'footer-1',
        'description' => __('Add widgets here to appear in your footer.', 'faqs-theme'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget-title text-lg font-semibold mb-4">',
        'after_title' => '</h4>',
    ));

    register_sidebar(array(
        'name' => __('Footer 2', 'faqs-theme'),
        'id' => 'footer-2',
        'description' => __('Add widgets here to appear in your footer.', 'faqs-theme'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget-title text-lg font-semibold mb-4">',
        'after_title' => '</h4>',
    ));

    register_sidebar(array(
        'name' => __('Footer 3', 'faqs-theme'),
        'id' => 'footer-3',
        'description' => __('Add widgets here to appear in your footer.', 'faqs-theme'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget-title text-lg font-semibold mb-4">',
        'after_title' => '</h4>',
    ));
}
add_action('widgets_init', 'faqs_theme_widgets_init');

/**
 * Custom excerpt length
 */
function faqs_theme_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'faqs_theme_excerpt_length');

/**
 * Custom excerpt more
 */
function faqs_theme_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'faqs_theme_excerpt_more');

// Include additional functionality
require_once FAQS_THEME_DIR . '/inc/seo.php';
require_once FAQS_THEME_DIR . '/inc/schema.php';
require_once FAQS_THEME_DIR . '/inc/rating.php';
require_once FAQS_THEME_DIR . '/inc/sitemap.php';
require_once FAQS_THEME_DIR . '/inc/performance.php';
require_once FAQS_THEME_DIR . '/inc/ajax-search.php';
require_once FAQS_THEME_DIR . '/inc/dark-mode.php';
