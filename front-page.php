<?php
/**
 * The front page template file
 *
 * @package FAQs_Theme
 * @since 1.0.0
 */

get_header();
?>

<main id="main-content" class="site-main" role="main">
    <!-- Hero Section with Search -->
    <section class="hero-section bg-gradient-to-br from-blue-600 to-blue-800 dark:from-blue-800 dark:to-blue-900 text-white py-20" role="region" aria-label="<?php esc_attr_e('Hero section', 'faqs-theme'); ?>">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 animate-fade-in">
                    <?php
                    $hero_title = get_theme_mod('hero_title', __('Find Answers to Your Questions', 'faqs-theme'));
                    echo esc_html($hero_title);
                    ?>
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-blue-100 animate-fade-in-delay">
                    <?php
                    $hero_subtitle = get_theme_mod('hero_subtitle', __('Search our comprehensive FAQ database for instant answers', 'faqs-theme'));
                    echo esc_html($hero_subtitle);
                    ?>
                </p>

                <!-- Hero Search Form -->
                <div class="hero-search max-w-2xl mx-auto animate-fade-in-delay-2">
                    <form role="search" method="get" class="search-form relative" action="<?php echo esc_url(home_url('/')); ?>">
                        <label for="hero-search-input" class="sr-only"><?php _e('Search for:', 'faqs-theme'); ?></label>
                        <div class="relative">
                            <input
                                type="search"
                                id="hero-search-input"
                                class="w-full px-6 py-4 pl-14 pr-32 text-lg text-gray-900 dark:text-white bg-white dark:bg-gray-800 border-0 rounded-full focus:outline-none focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-600 shadow-xl"
                                placeholder="<?php echo esc_attr_x('Type your question here...', 'placeholder', 'faqs-theme'); ?>"
                                value="<?php echo get_search_query(); ?>"
                                name="s"
                                autocomplete="off"
                            />
                            <svg class="absolute left-5 top-1/2 transform -translate-y-1/2 w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <button
                                type="submit"
                                class="absolute right-2 top-1/2 transform -translate-y-1/2 px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                            >
                                <?php _e('Search', 'faqs-theme'); ?>
                            </button>
                        </div>
                        <div id="hero-search-suggestions" class="mt-4 bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden hidden"></div>
                    </form>

                    <!-- Popular Search Tags -->
                    <div class="popular-searches mt-6 flex flex-wrap justify-center gap-2">
                        <span class="text-sm text-blue-100"><?php _e('Popular:', 'faqs-theme'); ?></span>
                        <?php
                        $popular_tags = get_tags(array(
                            'orderby' => 'count',
                            'order' => 'DESC',
                            'number' => 5,
                        ));
                        foreach ($popular_tags as $tag) :
                        ?>
                            <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="px-3 py-1 bg-blue-700 hover:bg-blue-600 rounded-full text-sm transition-colors">
                                <?php echo esc_html($tag->name); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Dashboard -->
    <section class="stats-section py-12 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700" role="region" aria-label="<?php esc_attr_e('Statistics', 'faqs-theme'); ?>">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <?php
                // Get statistics
                $total_posts = wp_count_posts('post')->publish;
                $total_categories = wp_count_terms('category');
                $total_tags = wp_count_terms('post_tag');
                $total_comments = wp_count_comments()->approved;

                $stats = array(
                    array(
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>',
                        'number' => $total_posts,
                        'label' => __('FAQs', 'faqs-theme'),
                        'color' => 'blue',
                    ),
                    array(
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>',
                        'number' => $total_categories,
                        'label' => __('Categories', 'faqs-theme'),
                        'color' => 'green',
                    ),
                    array(
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>',
                        'number' => $total_tags,
                        'label' => __('Tags', 'faqs-theme'),
                        'color' => 'purple',
                    ),
                    array(
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>',
                        'number' => $total_comments,
                        'label' => __('Answers', 'faqs-theme'),
                        'color' => 'orange',
                    ),
                );

                foreach ($stats as $stat) :
                ?>
                    <div class="stat-card bg-gradient-to-br from-<?php echo $stat['color']; ?>-50 to-<?php echo $stat['color']; ?>-100 dark:from-<?php echo $stat['color']; ?>-900 dark:to-<?php echo $stat['color']; ?>-800 p-6 rounded-lg text-center transform hover:scale-105 transition-transform duration-300">
                        <div class="flex justify-center mb-3">
                            <svg class="w-10 h-10 text-<?php echo $stat['color']; ?>-600 dark:text-<?php echo $stat['color']; ?>-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <?php echo $stat['icon']; ?>
                            </svg>
                        </div>
                        <div class="stat-number text-3xl font-bold text-<?php echo $stat['color']; ?>-700 dark:text-<?php echo $stat['color']; ?>-300 mb-1">
                            <?php echo number_format_i18n($stat['number']); ?>
                        </div>
                        <div class="stat-label text-sm font-medium text-<?php echo $stat['color']; ?>-600 dark:text-<?php echo $stat['color']; ?>-400">
                            <?php echo $stat['label']; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Latest FAQs -->
    <section class="latest-posts-section py-16 bg-gray-50 dark:bg-gray-900" role="region" aria-label="<?php esc_attr_e('Latest FAQs', 'faqs-theme'); ?>">
        <div class="container mx-auto px-4">
            <div class="section-header text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">
                    <?php _e('Latest FAQs', 'faqs-theme'); ?>
                </h2>
                <p class="text-lg text-gray-600 dark:text-gray-400">
                    <?php _e('Browse our most recent frequently asked questions', 'faqs-theme'); ?>
                </p>
            </div>

            <?php
            $latest_posts = new WP_Query(array(
                'posts_per_page' => 9,
                'post_status' => 'publish',
                'orderby' => 'date',
                'order' => 'DESC',
            ));

            if ($latest_posts->have_posts()) :
            ?>
                <div class="posts-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                    <?php while ($latest_posts->have_posts()) : $latest_posts->the_post(); ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class('post-card bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1'); ?> itemscope itemtype="http://schema.org/Article">
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

                                    <?php
                                    the_title('<h3 class="entry-title text-xl font-bold mb-2 line-clamp-2" itemprop="headline"><a href="' . esc_url(get_permalink()) . '" class="text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors" rel="bookmark">', '</a></h3>');
                                    ?>

                                    <div class="entry-meta text-sm text-gray-600 dark:text-gray-400 flex items-center gap-3">
                                        <time class="published flex items-center gap-1" datetime="<?php echo esc_attr(get_the_date('c')); ?>" itemprop="datePublished">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <?php echo get_the_date(); ?>
                                        </time>
                                        <span class="comments-count flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                            </svg>
                                            <?php comments_number('0', '1', '%'); ?>
                                        </span>
                                    </div>
                                </header>

                                <div class="entry-excerpt text-gray-700 dark:text-gray-300 mb-4 line-clamp-3">
                                    <?php the_excerpt(); ?>
                                </div>

                                <footer class="entry-footer">
                                    <a href="<?php the_permalink(); ?>" class="read-more inline-flex items-center text-blue-600 dark:text-blue-400 font-medium hover:underline group" aria-label="<?php echo esc_attr(sprintf(__('Read more about %s', 'faqs-theme'), get_the_title())); ?>">
                                        <?php _e('Read more', 'faqs-theme'); ?>
                                        <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </footer>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>

                <div class="text-center">
                    <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="inline-flex items-center px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <?php _e('View All FAQs', 'faqs-theme'); ?>
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                </div>
            <?php
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="cta-section py-16 bg-gradient-to-r from-blue-600 to-purple-600 dark:from-blue-800 dark:to-purple-800 text-white" role="region" aria-label="<?php esc_attr_e('Call to action', 'faqs-theme'); ?>">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">
                    <?php
                    $cta_title = get_theme_mod('cta_title', __("Didn't Find Your Answer?", 'faqs-theme'));
                    echo esc_html($cta_title);
                    ?>
                </h2>
                <p class="text-xl mb-8 text-blue-100">
                    <?php
                    $cta_subtitle = get_theme_mod('cta_subtitle', __('Our support team is here to help you. Contact us and get your questions answered.', 'faqs-theme'));
                    echo esc_html($cta_subtitle);
                    ?>
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="<?php echo esc_url(get_theme_mod('cta_button_url', '#contact')); ?>" class="inline-flex items-center justify-center px-8 py-3 bg-white text-blue-600 font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-600">
                        <?php echo esc_html(get_theme_mod('cta_button_text', __('Contact Support', 'faqs-theme'))); ?>
                    </a>
                    <a href="<?php echo esc_url(get_theme_mod('cta_secondary_button_url', '/submit-question')); ?>" class="inline-flex items-center justify-center px-8 py-3 bg-transparent border-2 border-white text-white font-semibold rounded-lg hover:bg-white hover:text-blue-600 transition-all duration-300 transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-600">
                        <?php echo esc_html(get_theme_mod('cta_secondary_button_text', __('Submit a Question', 'faqs-theme'))); ?>
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();
