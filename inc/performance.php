<?php
/**
 * Performance Optimizations
 * - WebP image support
 * - Lazy loading
 * - Critical CSS
 * - Resource hints
 *
 * @package FAQs_Theme
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add WebP support to allowed upload types
 */
function faqs_theme_webp_upload_support($mimes) {
    $mimes['webp'] = 'image/webp';
    return $mimes;
}
add_filter('mime_types', 'faqs_theme_webp_upload_support');

/**
 * Enable WebP support in media library
 */
function faqs_theme_webp_media_support($result, $path) {
    if (strpos($path, '.webp') !== false) {
        $result = true;
    }
    return $result;
}
add_filter('file_is_displayable_image', 'faqs_theme_webp_media_support', 10, 2);

/**
 * Add lazy loading to images
 */
function faqs_theme_add_lazy_loading($content) {
    if (is_admin() || is_feed() || wp_is_json_request()) {
        return $content;
    }

    // Add loading="lazy" to images
    $content = preg_replace('/<img((?![^>]*loading=)[^>]*)>/i', '<img$1 loading="lazy">', $content);

    return $content;
}
add_filter('the_content', 'faqs_theme_add_lazy_loading', 20);
add_filter('post_thumbnail_html', 'faqs_theme_add_lazy_loading', 20);
add_filter('get_avatar', 'faqs_theme_add_lazy_loading', 20);

/**
 * Add decoding="async" to images
 */
function faqs_theme_add_async_decoding($content) {
    if (is_admin() || is_feed() || wp_is_json_request()) {
        return $content;
    }

    // Add decoding="async" to images
    $content = preg_replace('/<img((?![^>]*decoding=)[^>]*)>/i', '<img$1 decoding="async">', $content);

    return $content;
}
add_filter('the_content', 'faqs_theme_add_async_decoding', 20);
add_filter('post_thumbnail_html', 'faqs_theme_add_async_decoding', 20);

/**
 * Defer non-critical JavaScript
 */
function faqs_theme_defer_scripts($tag, $handle, $src) {
    // Don't defer scripts in admin or if they're excluded
    if (is_admin()) {
        return $tag;
    }

    // Scripts that should not be deferred
    $exclude = array('jquery', 'jquery-core', 'jquery-migrate');

    if (in_array($handle, $exclude, true)) {
        return $tag;
    }

    // Add defer attribute
    return str_replace(' src', ' defer src', $tag);
}
add_filter('script_loader_tag', 'faqs_theme_defer_scripts', 10, 3);

/**
 * Optimize WordPress emoji script
 */
function faqs_theme_disable_emojis() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

    // Remove TinyMCE emojis
    add_filter('tiny_mce_plugins', 'faqs_theme_disable_emojis_tinymce');
}
add_action('init', 'faqs_theme_disable_emojis');

/**
 * Remove emoji CDN hostname from DNS prefetching hints
 */
function faqs_theme_disable_emojis_tinymce($plugins) {
    if (is_array($plugins)) {
        return array_diff($plugins, array('wpemoji'));
    }
    return array();
}

/**
 * Remove emoji DNS prefetch
 */
function faqs_theme_disable_emojis_remove_dns_prefetch($urls, $relation_type) {
    if ('dns-prefetch' === $relation_type) {
        $emoji_svg_url = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/');
        $urls = array_diff($urls, array($emoji_svg_url));
    }
    return $urls;
}
add_filter('wp_resource_hints', 'faqs_theme_disable_emojis_remove_dns_prefetch', 10, 2);

/**
 * Remove query strings from static resources
 */
function faqs_theme_remove_query_strings($src) {
    if (strpos($src, '?ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
add_filter('style_loader_src', 'faqs_theme_remove_query_strings', 10, 1);
add_filter('script_loader_src', 'faqs_theme_remove_query_strings', 10, 1);

/**
 * Add preconnect and dns-prefetch for external resources
 */
function faqs_theme_add_resource_hints($hints, $relation_type) {
    if ('preconnect' === $relation_type) {
        // Add Google Fonts preconnect if used
        $hints[] = array(
            'href' => 'https://fonts.googleapis.com',
            'crossorigin',
        );
        $hints[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin',
        );
    }

    if ('dns-prefetch' === $relation_type) {
        $hints[] = 'https://fonts.googleapis.com';
        $hints[] = 'https://fonts.gstatic.com';
    }

    return $hints;
}
add_filter('wp_resource_hints', 'faqs_theme_add_resource_hints', 10, 2);

/**
 * Optimize RSS feeds
 */
function faqs_theme_optimize_rss() {
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
}
add_action('init', 'faqs_theme_optimize_rss');

/**
 * Remove WordPress version from head
 */
remove_action('wp_head', 'wp_generator');

/**
 * Limit post revisions
 */
if (!defined('WP_POST_REVISIONS')) {
    define('WP_POST_REVISIONS', 3);
}

/**
 * Enable Gzip compression
 */
function faqs_theme_enable_gzip() {
    if (!headers_sent() && !ob_get_length() && extension_loaded('zlib')) {
        ob_start('ob_gzhandler');
    }
}
add_action('init', 'faqs_theme_enable_gzip');
