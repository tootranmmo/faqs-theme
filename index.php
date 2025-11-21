<?php
/**
 * The main template file
 *
 * @package FAQs_Theme
 * @since 1.0.0
 */

get_header();
?>

<main id="main-content" class="container mx-auto px-4 py-8" role="main">
    <?php if (have_posts()) : ?>
        <div class="posts-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('post-card bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300'); ?> itemscope itemtype="http://schema.org/Article">
                    <div class="p-6">
                        <header class="entry-header mb-4">
                            <?php
                            if (is_singular()) :
                                the_title('<h1 class="entry-title text-3xl font-bold mb-4" itemprop="headline">', '</h1>');
                            else :
                                the_title('<h2 class="entry-title text-xl font-semibold mb-2 hover:text-blue-600 dark:hover:text-blue-400 transition-colors" itemprop="headline"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
                            endif;
                            ?>

                            <div class="entry-meta text-sm text-gray-600 dark:text-gray-400 flex items-center gap-4">
                                <time class="published" datetime="<?php echo esc_attr(get_the_date('c')); ?>" itemprop="datePublished">
                                    <?php echo get_the_date(); ?>
                                </time>
                                <span class="author" itemprop="author" itemscope itemtype="http://schema.org/Person">
                                    <span itemprop="name"><?php the_author(); ?></span>
                                </span>
                                <?php
                                $rating = get_post_meta(get_the_ID(), '_faqs_rating', true);
                                if ($rating) {
                                    echo '<span class="rating">' . faqs_theme_display_rating($rating) . '</span>';
                                }
                                ?>
                            </div>
                        </header>

                        <div class="entry-content text-gray-700 dark:text-gray-300" itemprop="articleBody">
                            <?php
                            if (is_singular()) :
                                the_content();
                            else :
                                the_excerpt();
                            endif;
                            ?>
                        </div>

                        <?php if (!is_singular()) : ?>
                            <footer class="entry-footer mt-4">
                                <a href="<?php the_permalink(); ?>" class="read-more inline-flex items-center text-blue-600 dark:text-blue-400 hover:underline" aria-label="<?php echo esc_attr(sprintf(__('Read more about %s', 'faqs-theme'), get_the_title())); ?>">
                                    <?php _e('Read more', 'faqs-theme'); ?>
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </footer>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>

        <?php
        // Pagination
        the_posts_pagination(array(
            'mid_size' => 2,
            'prev_text' => __('&larr; Previous', 'faqs-theme'),
            'next_text' => __('Next &rarr;', 'faqs-theme'),
            'class' => 'pagination mt-12',
        ));
        ?>
    <?php else : ?>
        <div class="no-results bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 text-center">
            <h2 class="text-2xl font-bold mb-4"><?php _e('Nothing Found', 'faqs-theme'); ?></h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6"><?php _e('Sorry, but nothing matched your search terms. Please try again with different keywords.', 'faqs-theme'); ?></p>
            <?php get_search_form(); ?>
        </div>
    <?php endif; ?>
</main>

<?php
get_footer();
