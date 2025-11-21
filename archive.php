<?php
/**
 * The template for displaying archive pages
 *
 * @package FAQs_Theme
 * @since 1.0.0
 */

get_header();
?>

<main id="main-content" class="container mx-auto px-4 py-12" role="main">
    <header class="page-header mb-8">
        <?php
        the_archive_title('<h1 class="page-title text-3xl md:text-4xl font-bold mb-4">', '</h1>');
        the_archive_description('<div class="archive-description text-lg text-gray-600 dark:text-gray-400">', '</div>');
        ?>

        <?php if (have_posts()) : ?>
            <div class="archive-meta mt-4 text-gray-600 dark:text-gray-400">
                <?php
                global $wp_query;
                printf(
                    esc_html(_n('%s FAQ found', '%s FAQs found', $wp_query->found_posts, 'faqs-theme')),
                    '<strong>' . number_format_i18n($wp_query->found_posts) . '</strong>'
                );
                ?>
            </div>
        <?php endif; ?>
    </header>

    <?php if (have_posts()) : ?>
        <!-- Filters and Sort -->
        <div class="archive-filters bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8">
            <div class="flex flex-col md:flex-row gap-4 items-start md:items-center justify-between">
                <!-- Sort Options -->
                <div class="sort-options flex items-center gap-3">
                    <label for="sort-select" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        <?php _e('Sort by:', 'faqs-theme'); ?>
                    </label>
                    <select
                        id="sort-select"
                        class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                        onchange="window.location.href=this.value"
                    >
                        <?php
                        $current_url = add_query_arg(array());
                        $orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'date';
                        $order = isset($_GET['order']) ? sanitize_text_field($_GET['order']) : 'DESC';

                        $sort_options = array(
                            'date_desc' => array('label' => __('Newest First', 'faqs-theme'), 'orderby' => 'date', 'order' => 'DESC'),
                            'date_asc' => array('label' => __('Oldest First', 'faqs-theme'), 'orderby' => 'date', 'order' => 'ASC'),
                            'title_asc' => array('label' => __('Title A-Z', 'faqs-theme'), 'orderby' => 'title', 'order' => 'ASC'),
                            'title_desc' => array('label' => __('Title Z-A', 'faqs-theme'), 'orderby' => 'title', 'order' => 'DESC'),
                            'rating' => array('label' => __('Highest Rated', 'faqs-theme'), 'orderby' => 'meta_value_num', 'order' => 'DESC'),
                            'popular' => array('label' => __('Most Popular', 'faqs-theme'), 'orderby' => 'comment_count', 'order' => 'DESC'),
                        );

                        foreach ($sort_options as $key => $option) {
                            $url = add_query_arg(array('orderby' => $option['orderby'], 'order' => $option['order']), remove_query_arg(array('orderby', 'order')));
                            $selected = ($orderby === $option['orderby'] && $order === $option['order']) ? 'selected' : '';
                            echo '<option value="' . esc_url($url) . '" ' . $selected . '>' . esc_html($option['label']) . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <!-- View Toggle -->
                <div class="view-toggle flex items-center gap-2">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300"><?php _e('View:', 'faqs-theme'); ?></span>
                    <button
                        id="grid-view"
                        class="view-btn p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors active"
                        aria-label="<?php esc_attr_e('Grid view', 'faqs-theme'); ?>"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                    </button>
                    <button
                        id="list-view"
                        class="view-btn p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                        aria-label="<?php esc_attr_e('List view', 'faqs-theme'); ?>"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Filter by Rating -->
            <?php
            $min_rating = isset($_GET['min_rating']) ? intval($_GET['min_rating']) : 0;
            if ($min_rating > 0) :
            ?>
                <div class="active-filters mt-4 flex flex-wrap gap-2">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300"><?php _e('Active filters:', 'faqs-theme'); ?></span>
                    <span class="inline-flex items-center gap-2 px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded-full text-sm">
                        <?php printf(__('Rating: %d+ stars', 'faqs-theme'), $min_rating); ?>
                        <a href="<?php echo esc_url(remove_query_arg('min_rating')); ?>" class="hover:text-blue-900 dark:hover:text-blue-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </a>
                    </span>
                </div>
            <?php endif; ?>
        </div>

        <!-- Posts Grid/List -->
        <div id="posts-container" class="posts-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            <?php while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('post-card bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1'); ?>>
                    <div class="p-6">
                        <header class="entry-header mb-4">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <?php
                                    $categories = get_the_category();
                                    if (!empty($categories)) :
                                    ?>
                                        <a href="<?php echo esc_url(get_category_link($categories[0]->term_id)); ?>" class="inline-block px-3 py-1 text-xs font-semibold text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-900 rounded-full mb-2 hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors">
                                            <?php echo esc_html($categories[0]->name); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <?php
                                $rating = get_post_meta(get_the_ID(), '_faqs_rating', true);
                                if ($rating) :
                                ?>
                                    <div class="rating-display flex items-center gap-1 text-yellow-400">
                                        <?php echo faqs_theme_display_rating($rating); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php the_title('<h2 class="entry-title text-xl font-bold mb-2 line-clamp-2"><a href="' . esc_url(get_permalink()) . '" class="text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors" rel="bookmark">', '</a></h2>'); ?>

                            <div class="entry-meta text-sm text-gray-600 dark:text-gray-400 flex items-center gap-3">
                                <time class="published flex items-center gap-1" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <?php echo get_the_date(); ?>
                                </time>
                                <span class="comments-count flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                    </svg>
                                    <?php comments_number('0', '1', '%'); ?>
                                </span>
                                <?php
                                $views = get_post_meta(get_the_ID(), '_faqs_views', true);
                                if ($views) :
                                ?>
                                    <span class="views-count flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <?php echo number_format_i18n($views); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </header>

                        <div class="entry-excerpt text-gray-700 dark:text-gray-300 mb-4 line-clamp-3">
                            <?php the_excerpt(); ?>
                        </div>

                        <footer class="entry-footer">
                            <a href="<?php the_permalink(); ?>" class="read-more inline-flex items-center text-blue-600 dark:text-blue-400 font-medium hover:underline group">
                                <?php _e('Read more', 'faqs-theme'); ?>
                                <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </footer>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>

        <?php
        the_posts_pagination(array(
            'mid_size' => 2,
            'prev_text' => __('&larr; Previous', 'faqs-theme'),
            'next_text' => __('Next &rarr;', 'faqs-theme'),
            'class' => 'pagination',
        ));
        ?>
    <?php else : ?>
        <div class="no-results bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 text-center">
            <h2 class="text-2xl font-bold mb-4"><?php _e('No FAQs Found', 'faqs-theme'); ?></h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                <?php _e('Sorry, there are no FAQs in this category yet. Please check back later.', 'faqs-theme'); ?>
            </p>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                <?php _e('Back to Home', 'faqs-theme'); ?>
            </a>
        </div>
    <?php endif; ?>
</main>

<script>
// View Toggle
document.addEventListener('DOMContentLoaded', function() {
    const gridViewBtn = document.getElementById('grid-view');
    const listViewBtn = document.getElementById('list-view');
    const postsContainer = document.getElementById('posts-container');

    if (gridViewBtn && listViewBtn && postsContainer) {
        // Load saved preference
        const savedView = localStorage.getItem('archive-view') || 'grid';
        setView(savedView);

        gridViewBtn.addEventListener('click', () => setView('grid'));
        listViewBtn.addEventListener('click', () => setView('list'));

        function setView(view) {
            if (view === 'list') {
                postsContainer.classList.remove('md:grid-cols-2', 'lg:grid-cols-3');
                postsContainer.classList.add('grid-cols-1');
                listViewBtn.classList.add('active', 'bg-blue-100', 'dark:bg-blue-900');
                gridViewBtn.classList.remove('active', 'bg-blue-100', 'dark:bg-blue-900');
            } else {
                postsContainer.classList.add('md:grid-cols-2', 'lg:grid-cols-3');
                postsContainer.classList.remove('grid-cols-1');
                gridViewBtn.classList.add('active', 'bg-blue-100', 'dark:bg-blue-900');
                listViewBtn.classList.remove('active', 'bg-blue-100', 'dark:bg-blue-900');
            }
            localStorage.setItem('archive-view', view);
        }
    }
});
</script>

<?php
get_footer();
