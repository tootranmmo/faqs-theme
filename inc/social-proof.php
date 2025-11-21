<?php
/**
 * Social Proof Features
 * - View counter
 * - Helpful counter
 * - Popular posts
 * - Trending badges
 *
 * @package FAQs_Theme
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Track post views
 */
function faqs_theme_track_post_views($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    if (!$post_id || is_preview() || is_admin() || current_user_can('edit_posts')) {
        return;
    }

    $views = (int) get_post_meta($post_id, '_faqs_views', true);
    $views++;
    update_post_meta($post_id, '_faqs_views', $views);

    // Update last viewed time
    update_post_meta($post_id, '_faqs_last_viewed', current_time('timestamp'));
}

/**
 * Get post views
 *
 * @param int $post_id Post ID
 * @return int Number of views
 */
function faqs_theme_get_post_views($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    return (int) get_post_meta($post_id, '_faqs_views', true);
}

/**
 * Display post views
 *
 * @param int $post_id Post ID
 * @return string HTML output
 */
function faqs_theme_display_post_views($post_id = null) {
    $views = faqs_theme_get_post_views($post_id);

    if ($views < 1) {
        return '';
    }

    return sprintf(
        '<span class="post-views flex items-center gap-1 text-gray-600 dark:text-gray-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
            <span>%s</span>
        </span>',
        number_format_i18n($views) . ' ' . _n('view', 'views', $views, 'faqs-theme')
    );
}

/**
 * Track helpful votes via AJAX
 */
function faqs_theme_track_helpful_vote() {
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'faqs_theme_nonce')) {
        wp_send_json_error(array('message' => __('Security check failed', 'faqs-theme')));
    }

    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    $vote_type = isset($_POST['vote_type']) ? sanitize_text_field($_POST['vote_type']) : '';

    if (!$post_id || !in_array($vote_type, array('helpful', 'not_helpful'), true)) {
        wp_send_json_error(array('message' => __('Invalid data', 'faqs-theme')));
    }

    // Check if user already voted
    $cookie_name = 'faqs_voted_' . $post_id;
    if (isset($_COOKIE[$cookie_name])) {
        wp_send_json_error(array('message' => __('You have already voted', 'faqs-theme')));
    }

    // Get current counts
    $helpful = (int) get_post_meta($post_id, '_faqs_helpful', true);
    $not_helpful = (int) get_post_meta($post_id, '_faqs_not_helpful', true);

    // Update count
    if ($vote_type === 'helpful') {
        $helpful++;
        update_post_meta($post_id, '_faqs_helpful', $helpful);
    } else {
        $not_helpful++;
        update_post_meta($post_id, '_faqs_not_helpful', $not_helpful);
    }

    // Set cookie
    setcookie($cookie_name, $vote_type, time() + YEAR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true);

    wp_send_json_success(array(
        'helpful' => $helpful,
        'not_helpful' => $not_helpful,
        'message' => __('Thank you for your feedback!', 'faqs-theme'),
    ));
}
add_action('wp_ajax_faqs_helpful_vote', 'faqs_theme_track_helpful_vote');
add_action('wp_ajax_nopriv_faqs_helpful_vote', 'faqs_theme_track_helpful_vote');

/**
 * Display helpful counter
 *
 * @param int $post_id Post ID
 * @return string HTML output
 */
function faqs_theme_display_helpful_counter($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $helpful = (int) get_post_meta($post_id, '_faqs_helpful', true);
    $not_helpful = (int) get_post_meta($post_id, '_faqs_not_helpful', true);
    $total = $helpful + $not_helpful;

    // Check if user already voted
    $cookie_name = 'faqs_voted_' . $post_id;
    $already_voted = isset($_COOKIE[$cookie_name]);

    ob_start();
    ?>
    <div class="helpful-counter mt-8 p-6 bg-gray-50 dark:bg-gray-700 rounded-lg" data-post-id="<?php echo esc_attr($post_id); ?>">
        <h3 class="text-lg font-semibold mb-4"><?php _e('Was this FAQ helpful?', 'faqs-theme'); ?></h3>

        <?php if ($total > 0) : ?>
            <div class="helpful-stats mb-4">
                <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <span><?php printf(_n('%s person found this helpful', '%s people found this helpful', $helpful, 'faqs-theme'), number_format_i18n($helpful)); ?></span>
                    <span><?php echo $total > 0 ? round(($helpful / $total) * 100) : 0; ?>%</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                    <div class="bg-green-500 h-2 rounded-full transition-all duration-300" style="width: <?php echo $total > 0 ? ($helpful / $total) * 100 : 0; ?>%"></div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!$already_voted) : ?>
            <div class="helpful-buttons flex gap-3">
                <button class="helpful-btn flex-1 px-6 py-3 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg transition-colors flex items-center justify-center gap-2" data-vote="helpful">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                    </svg>
                    <?php _e('Yes, helpful', 'faqs-theme'); ?>
                </button>
                <button class="not-helpful-btn flex-1 px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition-colors flex items-center justify-center gap-2" data-vote="not_helpful">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018c.163 0 .326.02.485.06L17 4m-7 10v2a2 2 0 002 2h.095c.5 0 .905-.405.905-.905 0-.714.211-1.412.608-2.006L17 13V4m-7 10h2m5-10h2a2 2 0 012 2v6a2 2 0 01-2 2h-2.5"></path>
                    </svg>
                    <?php _e('Not helpful', 'faqs-theme'); ?>
                </button>
            </div>
        <?php else : ?>
            <p class="text-center text-gray-600 dark:text-gray-400">
                <?php _e('Thank you for your feedback!', 'faqs-theme'); ?>
            </p>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Get popular/trending posts
 *
 * @param int $limit Number of posts
 * @param int $days Look back days (default 7)
 * @return WP_Query
 */
function faqs_theme_get_popular_posts($limit = 5, $days = 7) {
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $limit,
        'post_status' => 'publish',
        'meta_key' => '_faqs_views',
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
        'date_query' => array(
            array(
                'after' => $days . ' days ago',
            ),
        ),
    );

    return new WP_Query($args);
}

/**
 * Display trending badge
 *
 * @param int $post_id Post ID
 * @return string HTML output
 */
function faqs_theme_trending_badge($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $views = faqs_theme_get_post_views($post_id);
    $last_viewed = get_post_meta($post_id, '_faqs_last_viewed', true);

    // Consider trending if:
    // 1. Has more than 100 views
    // 2. Was viewed in last 24 hours
    $is_trending = $views > 100 && $last_viewed && (current_time('timestamp') - $last_viewed < DAY_IN_SECONDS);

    if (!$is_trending) {
        return '';
    }

    return '<span class="trending-badge inline-flex items-center gap-1 px-2 py-1 bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-400 text-xs font-semibold rounded-full">
        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path>
        </svg>
        ' . __('Trending', 'faqs-theme') . '
    </span>';
}
