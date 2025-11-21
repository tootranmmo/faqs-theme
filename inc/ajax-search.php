<?php
/**
 * AJAX Live Search
 *
 * @package FAQs_Theme
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * AJAX search handler
 */
function faqs_theme_ajax_search() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'faqs_theme_nonce')) {
        wp_send_json_error(array('message' => __('Security check failed', 'faqs-theme')));
    }

    $search_query = isset($_POST['query']) ? sanitize_text_field($_POST['query']) : '';

    if (empty($search_query) || strlen($search_query) < 2) {
        wp_send_json_success(array('results' => array()));
    }

    // Search query
    $args = array(
        's' => $search_query,
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => 5,
        'orderby' => 'relevance',
        'no_found_rows' => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
    );

    $search = new WP_Query($args);

    $results = array();

    if ($search->have_posts()) {
        while ($search->have_posts()) {
            $search->the_post();

            $categories = get_the_category();
            $category_name = !empty($categories) ? $categories[0]->name : '';

            $results[] = array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'excerpt' => wp_trim_words(get_the_excerpt(), 15),
                'url' => get_permalink(),
                'date' => get_the_date(),
                'category' => $category_name,
                'rating' => get_post_meta(get_the_ID(), '_faqs_rating', true),
            );
        }
        wp_reset_postdata();
    }

    wp_send_json_success(array('results' => $results, 'count' => count($results)));
}
add_action('wp_ajax_faqs_search', 'faqs_theme_ajax_search');
add_action('wp_ajax_nopriv_faqs_search', 'faqs_theme_ajax_search');

/**
 * Get popular searches
 */
function faqs_theme_get_popular_searches() {
    $popular_tags = get_tags(array(
        'orderby' => 'count',
        'order' => 'DESC',
        'number' => 10,
    ));

    $searches = array();
    foreach ($popular_tags as $tag) {
        $searches[] = array(
            'term' => $tag->name,
            'count' => $tag->count,
            'url' => get_tag_link($tag->term_id),
        );
    }

    return $searches;
}

/**
 * AJAX get popular searches
 */
function faqs_theme_ajax_popular_searches() {
    wp_send_json_success(array('searches' => faqs_theme_get_popular_searches()));
}
add_action('wp_ajax_faqs_popular_searches', 'faqs_theme_ajax_popular_searches');
add_action('wp_ajax_nopriv_faqs_popular_searches', 'faqs_theme_ajax_popular_searches');
