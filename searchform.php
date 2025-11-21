<?php
/**
 * Search Form Template
 *
 * @package FAQs_Theme
 * @since 1.0.0
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label for="s" class="sr-only"><?php _e('Search for:', 'faqs-theme'); ?></label>
    <div class="relative">
        <input
            type="search"
            id="s"
            name="s"
            class="w-full px-4 py-3 pl-12 pr-4 text-gray-900 dark:text-white bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            placeholder="<?php echo esc_attr_x('Search FAQs...', 'placeholder', 'faqs-theme'); ?>"
            value="<?php echo get_search_query(); ?>"
            required
        />
        <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
        <button
            type="submit"
            class="absolute right-2 top-1/2 transform -translate-y-1/2 px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
        >
            <?php _e('Search', 'faqs-theme'); ?>
        </button>
    </div>
</form>
