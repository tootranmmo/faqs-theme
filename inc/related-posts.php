<?php
/**
 * Related FAQs Functionality
 *
 * @package FAQs_Theme
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get related posts based on categories and tags
 *
 * @param int $post_id Post ID
 * @param int $number Number of related posts to retrieve
 * @return WP_Query|false
 */
function faqs_theme_get_related_posts($post_id = null, $number = 3) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    // Get categories and tags
    $categories = wp_get_post_categories($post_id);
    $tags = wp_get_post_tags($post_id, array('fields' => 'ids'));

    if (empty($categories) && empty($tags)) {
        return false;
    }

    // Build tax query
    $tax_query = array('relation' => 'OR');

    if (!empty($categories)) {
        $tax_query[] = array(
            'taxonomy' => 'category',
            'field' => 'term_id',
            'terms' => $categories,
        );
    }

    if (!empty($tags)) {
        $tax_query[] = array(
            'taxonomy' => 'post_tag',
            'field' => 'term_id',
            'terms' => $tags,
        );
    }

    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $number,
        'post__not_in' => array($post_id),
        'post_status' => 'publish',
        'orderby' => 'rand',
        'tax_query' => $tax_query,
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => '_faqs_rating',
                'compare' => 'EXISTS',
            ),
            array(
                'key' => '_faqs_rating',
                'compare' => 'NOT EXISTS',
            ),
        ),
        'orderby' => array(
            'meta_value_num' => 'DESC',
            'date' => 'DESC',
        ),
    );

    return new WP_Query($args);
}

/**
 * Display related posts
 *
 * @param int $post_id Post ID
 * @param int $number Number of related posts to display
 */
function faqs_theme_display_related_posts($post_id = null, $number = 3) {
    $related_posts = faqs_theme_get_related_posts($post_id, $number);

    if (!$related_posts || !$related_posts->have_posts()) {
        return;
    }
    ?>
    <section class="related-posts mt-12 p-8 bg-gray-50 dark:bg-gray-700 rounded-lg">
        <h3 class="text-2xl font-bold mb-6 flex items-center gap-2">
            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            <?php _e('Related FAQs', 'faqs-theme'); ?>
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php while ($related_posts->have_posts()) : $related_posts->the_post(); ?>
                <article class="related-post bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <?php
                    $categories = get_the_category();
                    if (!empty($categories)) :
                    ?>
                        <a href="<?php echo esc_url(get_category_link($categories[0]->term_id)); ?>" class="inline-block px-3 py-1 text-xs font-semibold text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-900 rounded-full mb-3 hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors">
                            <?php echo esc_html($categories[0]->name); ?>
                        </a>
                    <?php endif; ?>

                    <h4 class="text-lg font-semibold mb-2 line-clamp-2">
                        <a href="<?php the_permalink(); ?>" class="text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                            <?php the_title(); ?>
                        </a>
                    </h4>

                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">
                        <?php echo wp_trim_words(get_the_excerpt(), 15); ?>
                    </p>

                    <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-400">
                        <?php
                        $rating = get_post_meta(get_the_ID(), '_faqs_rating', true);
                        if ($rating) :
                        ?>
                            <div class="flex items-center gap-1 text-yellow-400">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <span><?php echo number_format($rating, 1); ?></span>
                            </div>
                        <?php endif; ?>

                        <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:underline">
                            <?php _e('Read more', 'faqs-theme'); ?>
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    </section>
    <?php

    wp_reset_postdata();
}
