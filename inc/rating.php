<?php
/**
 * 5-Star Rating System
 *
 * @package FAQs_Theme
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Display star rating
 *
 * @param float $rating Rating value (0-5)
 * @param bool $interactive Whether to show interactive stars
 * @return string HTML output
 */
function faqs_theme_display_rating($rating = 0, $interactive = false) {
    $rating = floatval($rating);
    $rating = max(0, min(5, $rating)); // Clamp between 0 and 5

    $output = '<div class="star-rating flex items-center gap-1" ' . ($interactive ? 'role="img" aria-label="' . esc_attr(sprintf(__('Rating: %.1f out of 5 stars', 'faqs-theme'), $rating)) . '"' : '') . '>';

    for ($i = 1; $i <= 5; $i++) {
        $fill_percentage = 0;

        if ($rating >= $i) {
            $fill_percentage = 100;
        } elseif ($rating > ($i - 1)) {
            $fill_percentage = ($rating - ($i - 1)) * 100;
        }

        $output .= '<span class="star-wrapper relative inline-block">';

        // Empty star (background)
        $output .= '<svg class="star-empty w-5 h-5 text-gray-300 dark:text-gray-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">';
        $output .= '<path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>';
        $output .= '</svg>';

        // Filled star (overlay)
        if ($fill_percentage > 0) {
            $output .= '<svg class="star-filled w-5 h-5 text-yellow-400 absolute top-0 left-0" style="clip-path: inset(0 ' . (100 - $fill_percentage) . '% 0 0);" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">';
            $output .= '<path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>';
            $output .= '</svg>';
        }

        $output .= '</span>';
    }

    if ($rating > 0) {
        $output .= '<span class="rating-value text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">' . number_format($rating, 1) . '</span>';
    }

    $output .= '</div>';

    return $output;
}

/**
 * Rating input form
 *
 * @return string HTML output
 */
function faqs_theme_rating_input() {
    $output = '<div class="rating-input-stars flex gap-1" role="radiogroup" aria-label="' . esc_attr__('Rate this FAQ', 'faqs-theme') . '">';

    for ($i = 1; $i <= 5; $i++) {
        $output .= '<button type="button" class="star-button group" data-rating="' . $i . '" aria-label="' . esc_attr(sprintf(__('Rate %d stars', 'faqs-theme'), $i)) . '" role="radio" aria-checked="false">';
        $output .= '<svg class="w-6 h-6 text-gray-300 dark:text-gray-600 group-hover:text-yellow-400 transition-colors cursor-pointer" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">';
        $output .= '<path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>';
        $output .= '</svg>';
        $output .= '</button>';
    }

    $output .= '</div>';

    return $output;
}

/**
 * AJAX handler for rating submission
 */
function faqs_theme_submit_rating() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'faqs_theme_nonce')) {
        wp_send_json_error(array('message' => __('Security check failed', 'faqs-theme')));
    }

    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    $rating = isset($_POST['rating']) ? floatval($_POST['rating']) : 0;

    if (!$post_id || $rating < 1 || $rating > 5) {
        wp_send_json_error(array('message' => __('Invalid rating data', 'faqs-theme')));
    }

    // Check if user already rated (using cookies for guest users)
    $cookie_name = 'faqs_rated_' . $post_id;
    if (isset($_COOKIE[$cookie_name])) {
        wp_send_json_error(array('message' => __('You have already rated this FAQ', 'faqs-theme')));
    }

    // Get current rating data
    $current_rating = get_post_meta($post_id, '_faqs_rating', true);
    $current_count = get_post_meta($post_id, '_faqs_rating_count', true);

    $current_rating = $current_rating ? floatval($current_rating) : 0;
    $current_count = $current_count ? intval($current_count) : 0;

    // Calculate new average
    $total_rating = ($current_rating * $current_count) + $rating;
    $new_count = $current_count + 1;
    $new_rating = $total_rating / $new_count;

    // Update post meta
    update_post_meta($post_id, '_faqs_rating', $new_rating);
    update_post_meta($post_id, '_faqs_rating_count', $new_count);

    // Set cookie to prevent multiple ratings (expires in 1 year)
    setcookie($cookie_name, '1', time() + YEAR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true);

    wp_send_json_success(array(
        'rating' => $new_rating,
        'count' => $new_count,
        'html' => faqs_theme_display_rating($new_rating, true),
        'message' => __('Thank you for your rating!', 'faqs-theme'),
    ));
}
add_action('wp_ajax_faqs_submit_rating', 'faqs_theme_submit_rating');
add_action('wp_ajax_nopriv_faqs_submit_rating', 'faqs_theme_submit_rating');

/**
 * Calculate reading time
 *
 * @param int $post_id Post ID
 * @return string Reading time text
 */
function faqs_theme_reading_time($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $content = get_post_field('post_content', $post_id);
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // Average reading speed: 200 words per minute

    return sprintf(_n('%d min read', '%d min read', $reading_time, 'faqs-theme'), $reading_time);
}
