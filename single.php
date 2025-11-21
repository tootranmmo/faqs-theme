<?php
/**
 * The template for displaying single posts
 *
 * @package FAQs_Theme
 * @since 1.0.0
 */

get_header();
?>

<main id="main-content" class="site-main py-12" role="main">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <?php
            while (have_posts()) :
                the_post();
            ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('single-post bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden'); ?> itemscope itemtype="http://schema.org/Article">
                    <!-- Article Header -->
                    <header class="entry-header p-8 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <!-- Breadcrumb -->
                        <nav class="breadcrumb text-sm mb-4" aria-label="<?php esc_attr_e('Breadcrumb', 'faqs-theme'); ?>">
                            <ol class="flex items-center gap-2 text-gray-600 dark:text-gray-400" vocab="http://schema.org/" typeof="BreadcrumbList">
                                <li property="itemListElement" typeof="ListItem">
                                    <a property="item" typeof="WebPage" href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                        <span property="name"><?php _e('Home', 'faqs-theme'); ?></span>
                                    </a>
                                    <meta property="position" content="1">
                                </li>
                                <li aria-hidden="true">/</li>
                                <?php
                                $categories = get_the_category();
                                if (!empty($categories)) :
                                ?>
                                    <li property="itemListElement" typeof="ListItem">
                                        <a property="item" typeof="WebPage" href="<?php echo esc_url(get_category_link($categories[0]->term_id)); ?>" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                            <span property="name"><?php echo esc_html($categories[0]->name); ?></span>
                                        </a>
                                        <meta property="position" content="2">
                                    </li>
                                    <li aria-hidden="true">/</li>
                                <?php endif; ?>
                                <li property="itemListElement" typeof="ListItem">
                                    <span property="name" class="text-gray-900 dark:text-white"><?php the_title(); ?></span>
                                    <meta property="position" content="3">
                                </li>
                            </ol>
                        </nav>

                        <!-- Categories -->
                        <?php if (!empty($categories)) : ?>
                            <div class="categories mb-4">
                                <?php foreach ($categories as $category) : ?>
                                    <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="inline-block px-3 py-1 text-sm font-semibold text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-900 rounded-full mr-2 mb-2 hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors">
                                        <?php echo esc_html($category->name); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Title -->
                        <h1 class="entry-title text-3xl md:text-4xl font-bold mb-4" itemprop="headline">
                            <?php the_title(); ?>
                        </h1>

                        <!-- Meta Information -->
                        <div class="entry-meta flex flex-wrap items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                            <div class="author flex items-center gap-2" itemprop="author" itemscope itemtype="http://schema.org/Person">
                                <?php echo get_avatar(get_the_author_meta('ID'), 32, '', '', array('class' => 'rounded-full')); ?>
                                <span itemprop="name"><?php the_author(); ?></span>
                            </div>

                            <time class="published flex items-center gap-1" datetime="<?php echo esc_attr(get_the_date('c')); ?>" itemprop="datePublished">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <?php echo get_the_date(); ?>
                            </time>

                            <?php if (get_the_modified_time('U') !== get_the_time('U')) : ?>
                                <time class="updated flex items-center gap-1" datetime="<?php echo esc_attr(get_the_modified_date('c')); ?>" itemprop="dateModified">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    <?php printf(__('Updated: %s', 'faqs-theme'), get_the_modified_date()); ?>
                                </time>
                            <?php endif; ?>

                            <span class="reading-time flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <?php echo faqs_theme_reading_time(); ?>
                            </span>

                            <span class="comments-count flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                </svg>
                                <?php comments_number(__('No answers', 'faqs-theme'), __('1 answer', 'faqs-theme'), __('% answers', 'faqs-theme')); ?>
                            </span>
                        </div>

                        <!-- Rating Display & Vote -->
                        <div class="rating-section mt-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div class="rating-display">
                                    <div class="text-sm font-medium mb-2"><?php _e('Was this helpful?', 'faqs-theme'); ?></div>
                                    <div class="flex items-center gap-2">
                                        <?php
                                        $rating = get_post_meta(get_the_ID(), '_faqs_rating', true);
                                        $rating_count = get_post_meta(get_the_ID(), '_faqs_rating_count', true);
                                        echo faqs_theme_display_rating($rating ? $rating : 0, true);
                                        ?>
                                        <?php if ($rating_count) : ?>
                                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                                (<?php echo number_format_i18n($rating_count); ?> <?php echo _n('vote', 'votes', $rating_count, 'faqs-theme'); ?>)
                                            </span>
                                        <?php endif; ?>
                                        <?php echo faqs_theme_trending_badge(); ?>
                                        <?php echo faqs_theme_display_post_views(); ?>
                                    </div>
                                </div>
                                <div class="rating-input" id="rating-input-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>">
                                    <?php echo faqs_theme_rating_input(); ?>
                                </div>
                            </div>
                        </div>
                    </header>

                    <!-- Quick Actions Bar -->
                    <?php echo faqs_theme_display_quick_actions(); ?>

                    <!-- Article Content -->
                    <div class="entry-content prose prose-lg dark:prose-invert max-w-none p-8" itemprop="articleBody">
                        <?php
                        the_content();

                        wp_link_pages(array(
                            'before' => '<div class="page-links mt-8 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg"><span class="font-semibold">' . __('Pages:', 'faqs-theme') . '</span>',
                            'after' => '</div>',
                        ));
                        ?>
                    </div>

                    <!-- Article Footer -->
                    <footer class="entry-footer p-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <!-- Tags -->
                        <?php
                        $tags = get_the_tags();
                        if ($tags) :
                        ?>
                            <div class="tags-links mb-6">
                                <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    <?php _e('Tags:', 'faqs-theme'); ?>
                                </h3>
                                <div class="flex flex-wrap gap-2">
                                    <?php foreach ($tags as $tag) : ?>
                                        <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full hover:bg-blue-100 dark:hover:bg-blue-900 hover:text-blue-600 dark:hover:text-blue-400 transition-colors" rel="tag">
                                            <?php echo esc_html($tag->name); ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Share Buttons -->
                        <div class="share-buttons">
                            <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-400 mb-3"><?php _e('Share this FAQ:', 'faqs-theme'); ?></h3>
                            <div class="flex gap-2">
                                <?php
                                $share_url = urlencode(get_permalink());
                                $share_title = urlencode(get_the_title());
                                ?>
                                <a href="https://twitter.com/intent/tweet?url=<?php echo $share_url; ?>&text=<?php echo $share_title; ?>" target="_blank" rel="noopener noreferrer" class="share-btn p-3 bg-blue-400 hover:bg-blue-500 text-white rounded-lg transition-colors" aria-label="<?php esc_attr_e('Share on Twitter', 'faqs-theme'); ?>">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"></path>
                                    </svg>
                                </a>
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>" target="_blank" rel="noopener noreferrer" class="share-btn p-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors" aria-label="<?php esc_attr_e('Share on Facebook', 'faqs-theme'); ?>">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path>
                                    </svg>
                                </a>
                                <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $share_url; ?>&title=<?php echo $share_title; ?>" target="_blank" rel="noopener noreferrer" class="share-btn p-3 bg-blue-700 hover:bg-blue-800 text-white rounded-lg transition-colors" aria-label="<?php esc_attr_e('Share on LinkedIn', 'faqs-theme'); ?>">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"></path>
                                        <circle cx="4" cy="4" r="2"></circle>
                                    </svg>
                                </a>
                                <button class="share-btn p-3 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors" onclick="navigator.clipboard.writeText('<?php echo esc_js(get_permalink()); ?>'); this.innerHTML='<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M5 13l4 4L19 7\'></path></svg>'" aria-label="<?php esc_attr_e('Copy link', 'faqs-theme'); ?>">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </footer>
                </article>

                <!-- Post Navigation -->
                <nav class="post-navigation mt-8 mb-12" role="navigation" aria-label="<?php esc_attr_e('Post Navigation', 'faqs-theme'); ?>">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php
                        $prev_post = get_previous_post();
                        $next_post = get_next_post();

                        if ($prev_post) :
                        ?>
                            <a href="<?php echo esc_url(get_permalink($prev_post)); ?>" class="nav-previous flex items-center gap-4 p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                                <div class="flex-shrink-0">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1"><?php _e('Previous FAQ', 'faqs-theme'); ?></div>
                                    <div class="font-semibold text-gray-900 dark:text-white line-clamp-2"><?php echo esc_html($prev_post->post_title); ?></div>
                                </div>
                            </a>
                        <?php endif; ?>

                        <?php if ($next_post) : ?>
                            <a href="<?php echo esc_url(get_permalink($next_post)); ?>" class="nav-next flex items-center gap-4 p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-shadow <?php echo !$prev_post ? 'md:col-start-2' : ''; ?>">
                                <div class="flex-1 text-right">
                                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1"><?php _e('Next FAQ', 'faqs-theme'); ?></div>
                                    <div class="font-semibold text-gray-900 dark:text-white line-clamp-2"><?php echo esc_html($next_post->post_title); ?></div>
                                </div>
                                <div class="flex-shrink-0">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </a>
                        <?php endif; ?>
                    </div>
                </nav>

                <!-- Helpful Counter -->
                <div class="max-w-4xl mx-auto">
                    <?php echo faqs_theme_display_helpful_counter(); ?>
                </div>

                <!-- Related FAQs -->
                <?php faqs_theme_display_related_posts(get_the_ID(), 3); ?>

                <!-- Comments -->
                <?php
                if (comments_open() || get_comments_number()) :
                ?>
                    <div class="comments-area bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 mt-8">
                        <?php comments_template(); ?>
                    </div>
                <?php endif; ?>

            <?php endwhile; ?>
        </div>
    </div>
</main>

<?php
get_footer();
