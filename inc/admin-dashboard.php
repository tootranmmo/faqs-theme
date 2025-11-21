<?php
/**
 * Admin Dashboard Widget
 *
 * @package FAQs_Theme
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add dashboard widget
 */
function faqs_theme_add_dashboard_widget() {
    wp_add_dashboard_widget(
        'faqs_theme_dashboard_widget',
        __('FAQs Statistics', 'faqs-theme'),
        'faqs_theme_dashboard_widget_display'
    );
}
add_action('wp_dashboard_setup', 'faqs_theme_add_dashboard_widget');

/**
 * Dashboard widget display
 */
function faqs_theme_dashboard_widget_display() {
    // Get statistics
    $total_posts = wp_count_posts('post')->publish;
    $total_views = faqs_theme_get_total_views();
    $total_ratings = faqs_theme_get_total_ratings();
    $avg_rating = faqs_theme_get_average_rating();

    // Get popular posts this week
    $popular_posts = faqs_theme_get_popular_posts(5, 7);

    // Get recent ratings
    $recent_rated_posts = faqs_theme_get_recent_rated_posts(5);
    ?>
    <div class="faqs-dashboard-widget">
        <!-- Stats Overview -->
        <div class="faqs-stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
            <div class="stat-card" style="padding: 1rem; background: #f0f9ff; border-radius: 0.5rem; text-align: center;">
                <div style="font-size: 2rem; font-weight: bold; color: #0369a1; margin-bottom: 0.5rem;">
                    <?php echo number_format_i18n($total_posts); ?>
                </div>
                <div style="color: #64748b; font-size: 0.875rem;">
                    <?php _e('Total FAQs', 'faqs-theme'); ?>
                </div>
            </div>

            <div class="stat-card" style="padding: 1rem; background: #f0fdf4; border-radius: 0.5rem; text-align: center;">
                <div style="font-size: 2rem; font-weight: bold; color: #15803d; margin-bottom: 0.5rem;">
                    <?php echo number_format_i18n($total_views); ?>
                </div>
                <div style="color: #64748b; font-size: 0.875rem;">
                    <?php _e('Total Views', 'faqs-theme'); ?>
                </div>
            </div>

            <div class="stat-card" style="padding: 1rem; background: #fefce8; border-radius: 0.5rem; text-align: center;">
                <div style="font-size: 2rem; font-weight: bold; color: #ca8a04; margin-bottom: 0.5rem;">
                    <?php echo number_format($avg_rating, 1); ?>⭐
                </div>
                <div style="color: #64748b; font-size: 0.875rem;">
                    <?php _e('Average Rating', 'faqs-theme'); ?>
                </div>
            </div>

            <div class="stat-card" style="padding: 1rem; background: #fef2f2; border-radius: 0.5rem; text-align: center;">
                <div style="font-size: 2rem; font-weight: bold; color: #dc2626; margin-bottom: 0.5rem;">
                    <?php echo number_format_i18n($total_ratings); ?>
                </div>
                <div style="color: #64748b; font-size: 0.875rem;">
                    <?php _e('Total Ratings', 'faqs-theme'); ?>
                </div>
            </div>
        </div>

        <!-- Popular Posts This Week -->
        <?php if ($popular_posts && $popular_posts->have_posts()) : ?>
            <div class="popular-posts" style="margin-bottom: 1.5rem;">
                <h3 style="margin-bottom: 1rem; font-size: 1.1rem; font-weight: 600;">
                    🔥 <?php _e('Popular This Week', 'faqs-theme'); ?>
                </h3>
                <ul style="margin: 0; padding: 0; list-style: none;">
                    <?php while ($popular_posts->have_posts()) : $popular_posts->the_post(); ?>
                        <li style="padding: 0.75rem; margin-bottom: 0.5rem; background: #f8fafc; border-radius: 0.375rem; border-left: 3px solid #3b82f6;">
                            <a href="<?php echo esc_url(get_edit_post_link()); ?>" style="text-decoration: none; color: #1e293b; font-weight: 500;">
                                <?php the_title(); ?>
                            </a>
                            <div style="margin-top: 0.25rem; font-size: 0.75rem; color: #64748b;">
                                👁️ <?php echo number_format_i18n(faqs_theme_get_post_views(get_the_ID())); ?> views
                                <?php
                                $rating = get_post_meta(get_the_ID(), '_faqs_rating', true);
                                if ($rating) {
                                    echo ' | ⭐ ' . number_format($rating, 1);
                                }
                                ?>
                            </div>
                        </li>
                    <?php endwhile; wp_reset_postdata(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Recent Ratings -->
        <?php if (!empty($recent_rated_posts)) : ?>
            <div class="recent-ratings">
                <h3 style="margin-bottom: 1rem; font-size: 1.1rem; font-weight: 600;">
                    ⭐ <?php _e('Recently Rated', 'faqs-theme'); ?>
                </h3>
                <ul style="margin: 0; padding: 0; list-style: none;">
                    <?php foreach ($recent_rated_posts as $post_id) : ?>
                        <li style="padding: 0.75rem; margin-bottom: 0.5rem; background: #fffbeb; border-radius: 0.375rem; border-left: 3px solid #f59e0b;">
                            <a href="<?php echo esc_url(get_edit_post_link($post_id)); ?>" style="text-decoration: none; color: #1e293b; font-weight: 500;">
                                <?php echo get_the_title($post_id); ?>
                            </a>
                            <div style="margin-top: 0.25rem; font-size: 0.75rem; color: #64748b;">
                                <?php
                                $rating = get_post_meta($post_id, '_faqs_rating', true);
                                $rating_count = get_post_meta($post_id, '_faqs_rating_count', true);
                                echo '⭐ ' . number_format($rating, 1) . ' (' . $rating_count . ' votes)';
                                ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Quick Actions -->
        <div class="quick-actions" style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e2e8f0;">
            <a href="<?php echo admin_url('post-new.php'); ?>" class="button button-primary" style="margin-right: 0.5rem;">
                <?php _e('Add New FAQ', 'faqs-theme'); ?>
            </a>
            <a href="<?php echo admin_url('edit.php'); ?>" class="button">
                <?php _e('View All FAQs', 'faqs-theme'); ?>
            </a>
        </div>
    </div>

    <style>
    .faqs-dashboard-widget .stat-card {
        transition: transform 0.2s ease;
    }

    .faqs-dashboard-widget .stat-card:hover {
        transform: translateY(-2px);
    }

    .faqs-dashboard-widget a {
        transition: color 0.2s ease;
    }

    .faqs-dashboard-widget a:hover {
        color: #2563eb;
    }
    </style>
    <?php
}

/**
 * Get total views across all posts
 */
function faqs_theme_get_total_views() {
    global $wpdb;
    $total = $wpdb->get_var("SELECT SUM(meta_value) FROM $wpdb->postmeta WHERE meta_key = '_faqs_views'");
    return $total ? intval($total) : 0;
}

/**
 * Get total ratings
 */
function faqs_theme_get_total_ratings() {
    global $wpdb;
    $total = $wpdb->get_var("SELECT SUM(meta_value) FROM $wpdb->postmeta WHERE meta_key = '_faqs_rating_count'");
    return $total ? intval($total) : 0;
}

/**
 * Get average rating
 */
function faqs_theme_get_average_rating() {
    global $wpdb;
    $avg = $wpdb->get_var("SELECT AVG(meta_value) FROM $wpdb->postmeta WHERE meta_key = '_faqs_rating' AND meta_value > 0");
    return $avg ? floatval($avg) : 0;
}

/**
 * Get recent rated posts
 */
function faqs_theme_get_recent_rated_posts($limit = 5) {
    global $wpdb;
    $post_ids = $wpdb->get_col($wpdb->prepare(
        "SELECT post_id FROM $wpdb->postmeta
        WHERE meta_key = '_faqs_rating' AND meta_value > 0
        ORDER BY meta_id DESC
        LIMIT %d",
        $limit
    ));
    return $post_ids;
}
