<?php
/**
 * The template for displaying all pages
 *
 * @package FAQs_Theme
 * @since 1.0.0
 */

get_header();
?>

<main id="main-content" class="site-main py-12" role="main">
    <div class="container mx-auto px-4">
        <?php
        while (have_posts()) :
            the_post();

            // Check if full-width layout
            $full_width = get_post_meta(get_the_ID(), '_faqs_full_width', true);
            $container_class = $full_width ? 'max-w-full' : 'max-w-4xl mx-auto';
        ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('page-content ' . $container_class); ?>>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                    <?php if (has_post_thumbnail() && !$full_width) : ?>
                        <div class="featured-image mb-0">
                            <?php the_post_thumbnail('large', array('class' => 'w-full h-auto')); ?>
                        </div>
                    <?php endif; ?>

                    <div class="page-inner p-8">
                        <header class="entry-header mb-8">
                            <?php if (!is_front_page()) : ?>
                                <!-- Breadcrumb -->
                                <nav class="breadcrumb text-sm mb-4" aria-label="<?php esc_attr_e('Breadcrumb', 'faqs-theme'); ?>">
                                    <ol class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                                        <li>
                                            <a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                                <?php _e('Home', 'faqs-theme'); ?>
                                            </a>
                                        </li>
                                        <li aria-hidden="true">/</li>
                                        <li class="text-gray-900 dark:text-white"><?php the_title(); ?></li>
                                    </ol>
                                </nav>
                            <?php endif; ?>

                            <h1 class="entry-title text-3xl md:text-4xl font-bold mb-4">
                                <?php the_title(); ?>
                            </h1>

                            <?php if (get_the_excerpt()) : ?>
                                <div class="page-excerpt text-lg text-gray-600 dark:text-gray-400 mb-4">
                                    <?php the_excerpt(); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (!$full_width) : ?>
                                <div class="entry-meta text-sm text-gray-600 dark:text-gray-400 flex items-center gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <?php if (get_the_modified_time('U') !== get_the_time('U')) : ?>
                                        <time class="updated flex items-center gap-1" datetime="<?php echo esc_attr(get_the_modified_date('c')); ?>">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                            </svg>
                                            <?php printf(__('Last updated: %s', 'faqs-theme'), get_the_modified_date()); ?>
                                        </time>
                                    <?php endif; ?>

                                    <?php if (get_edit_post_link()) : ?>
                                        <a href="<?php echo esc_url(get_edit_post_link()); ?>" class="flex items-center gap-1 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            <?php _e('Edit', 'faqs-theme'); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </header>

                        <div class="entry-content prose prose-lg dark:prose-invert max-w-none">
                            <?php
                            the_content();

                            wp_link_pages(array(
                                'before' => '<div class="page-links mt-8 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg"><span class="font-semibold">' . __('Pages:', 'faqs-theme') . '</span>',
                                'after' => '</div>',
                            ));
                            ?>
                        </div>

                        <?php if (comments_open() || get_comments_number()) : ?>
                            <div class="comments-area mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
                                <?php comments_template(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </article>

            <?php
            // Child pages for hierarchical pages
            $child_pages = get_pages(array(
                'child_of' => get_the_ID(),
                'sort_column' => 'menu_order',
            ));

            if ($child_pages) :
            ?>
                <div class="child-pages <?php echo $container_class; ?> mt-8">
                    <h2 class="text-2xl font-bold mb-6"><?php _e('Related Pages', 'faqs-theme'); ?></h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($child_pages as $child) : ?>
                            <a href="<?php echo esc_url(get_permalink($child->ID)); ?>" class="child-page-card bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                                <h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                    <?php echo esc_html($child->post_title); ?>
                                </h3>
                                <?php if ($child->post_excerpt) : ?>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm">
                                        <?php echo esc_html($child->post_excerpt); ?>
                                    </p>
                                <?php endif; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

        <?php endwhile; ?>
    </div>
</main>

<?php
get_footer();
