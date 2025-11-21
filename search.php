<?php
/**
 * The template for displaying search results
 *
 * @package FAQs_Theme
 * @since 1.0.0
 */

get_header();
?>

<main id="main-content" class="container mx-auto px-4 py-12" role="main">
    <header class="page-header mb-8">
        <h1 class="page-title text-3xl md:text-4xl font-bold mb-4">
            <?php
            printf(
                esc_html__('Search Results for: %s', 'faqs-theme'),
                '<span class="text-blue-600 dark:text-blue-400">' . get_search_query() . '</span>'
            );
            ?>
        </h1>

        <?php if (have_posts()) : ?>
            <p class="text-gray-600 dark:text-gray-400">
                <?php
                global $wp_query;
                printf(
                    esc_html(_n('Found %s result', 'Found %s results', $wp_query->found_posts, 'faqs-theme')),
                    '<strong>' . number_format_i18n($wp_query->found_posts) . '</strong>'
                );
                ?>
            </p>
        <?php endif; ?>
    </header>

    <?php if (have_posts()) : ?>
        <div class="posts-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            <?php while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('post-card bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300'); ?>>
                    <div class="p-6">
                        <header class="entry-header mb-4">
                            <?php
                            $categories = get_the_category();
                            if (!empty($categories)) :
                            ?>
                                <a href="<?php echo esc_url(get_category_link($categories[0]->term_id)); ?>" class="inline-block px-3 py-1 text-xs font-semibold text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-900 rounded-full mb-2 hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors">
                                    <?php echo esc_html($categories[0]->name); ?>
                                </a>
                            <?php endif; ?>

                            <?php the_title('<h2 class="entry-title text-xl font-semibold mb-2 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>'); ?>

                            <div class="entry-meta text-sm text-gray-600 dark:text-gray-400 flex items-center gap-4">
                                <time class="published" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                    <?php echo get_the_date(); ?>
                                </time>
                                <?php
                                $rating = get_post_meta(get_the_ID(), '_faqs_rating', true);
                                if ($rating) {
                                    echo '<span class="rating flex items-center gap-1 text-yellow-400">⭐ ' . number_format($rating, 1) . '</span>';
                                }
                                ?>
                            </div>
                        </header>

                        <div class="entry-excerpt text-gray-700 dark:text-gray-300 mb-4">
                            <?php the_excerpt(); ?>
                        </div>

                        <footer class="entry-footer">
                            <a href="<?php the_permalink(); ?>" class="read-more inline-flex items-center text-blue-600 dark:text-blue-400 hover:underline group">
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

        <?php
        the_posts_pagination(array(
            'mid_size' => 2,
            'prev_text' => __('&larr; Previous', 'faqs-theme'),
            'next_text' => __('Next &rarr;', 'faqs-theme'),
            'class' => 'pagination',
        ));
        ?>
    <?php else : ?>
        <div class="no-results bg-white dark:bg-gray-800 rounded-lg shadow-md p-8">
            <h2 class="text-2xl font-bold mb-4"><?php _e('Nothing Found', 'faqs-theme'); ?></h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                <?php _e('Sorry, but nothing matched your search terms. Please try again with different keywords.', 'faqs-theme'); ?>
            </p>
            <?php get_search_form(); ?>

            <div class="mt-8">
                <h3 class="text-lg font-semibold mb-4"><?php _e('Popular Searches:', 'faqs-theme'); ?></h3>
                <div class="flex flex-wrap gap-2">
                    <?php
                    $popular_tags = get_tags(array(
                        'orderby' => 'count',
                        'order' => 'DESC',
                        'number' => 10,
                    ));
                    foreach ($popular_tags as $tag) :
                    ?>
                        <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="px-4 py-2 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 rounded-full hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors">
                            <?php echo esc_html($tag->name); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</main>

<?php
get_footer();
