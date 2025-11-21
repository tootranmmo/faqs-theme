<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package FAQs_Theme
 * @since 1.0.0
 */

get_header();
?>

<main id="main-content" class="container mx-auto px-4 py-16" role="main">
    <div class="max-w-3xl mx-auto text-center">
        <div class="error-404-content bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 md:p-12">
            <!-- 404 Image/Icon -->
            <div class="mb-8">
                <svg class="w-32 h-32 mx-auto text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>

            <h1 class="text-6xl md:text-8xl font-bold text-gray-900 dark:text-white mb-4">404</h1>

            <h2 class="text-2xl md:text-3xl font-semibold text-gray-800 dark:text-gray-200 mb-4">
                <?php _e('Oops! Page Not Found', 'faqs-theme'); ?>
            </h2>

            <p class="text-lg text-gray-600 dark:text-gray-400 mb-8">
                <?php _e("The page you're looking for doesn't exist or has been moved.", 'faqs-theme'); ?>
            </p>

            <!-- Search Form -->
            <div class="max-w-xl mx-auto mb-8">
                <h3 class="text-lg font-semibold mb-4"><?php _e('Try searching for what you need:', 'faqs-theme'); ?></h3>
                <?php get_search_form(); ?>
            </div>

            <!-- Quick Links -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="p-4 bg-blue-50 dark:bg-blue-900 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-800 transition-colors group">
                    <svg class="w-8 h-8 mx-auto mb-2 text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="font-semibold text-blue-600 dark:text-blue-400"><?php _e('Go Home', 'faqs-theme'); ?></span>
                </a>

                <?php
                $categories = get_categories(array('number' => 1, 'orderby' => 'count', 'order' => 'DESC'));
                if (!empty($categories)) :
                ?>
                    <a href="<?php echo esc_url(get_category_link($categories[0]->term_id)); ?>" class="p-4 bg-green-50 dark:bg-green-900 rounded-lg hover:bg-green-100 dark:hover:bg-green-800 transition-colors group">
                        <svg class="w-8 h-8 mx-auto mb-2 text-green-600 dark:text-green-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        <span class="font-semibold text-green-600 dark:text-green-400"><?php _e('Browse FAQs', 'faqs-theme'); ?></span>
                    </a>
                <?php endif; ?>

                <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="p-4 bg-purple-50 dark:bg-purple-900 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-800 transition-colors group">
                    <svg class="w-8 h-8 mx-auto mb-2 text-purple-600 dark:text-purple-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <span class="font-semibold text-purple-600 dark:text-purple-400"><?php _e('All Posts', 'faqs-theme'); ?></span>
                </a>
            </div>

            <!-- Popular Tags -->
            <?php
            $popular_tags = get_tags(array(
                'orderby' => 'count',
                'order' => 'DESC',
                'number' => 8,
            ));

            if (!empty($popular_tags)) :
            ?>
                <div class="mt-8">
                    <h3 class="text-lg font-semibold mb-4"><?php _e('Popular Topics:', 'faqs-theme'); ?></h3>
                    <div class="flex flex-wrap justify-center gap-2">
                        <?php foreach ($popular_tags as $tag) : ?>
                            <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full hover:bg-blue-100 dark:hover:bg-blue-900 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                <?php echo esc_html($tag->name); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php
get_footer();
