<?php
/**
 * The template for displaying comments
 *
 * @package FAQs_Theme
 * @since 1.0.0
 */

if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area">
    <?php if (have_comments()) : ?>
        <h2 class="comments-title text-2xl font-bold mb-6">
            <?php
            $comment_count = get_comments_number();
            if ('1' === $comment_count) {
                printf(
                    esc_html__('One answer to &ldquo;%s&rdquo;', 'faqs-theme'),
                    '<span>' . get_the_title() . '</span>'
                );
            } else {
                printf(
                    esc_html(_nx('%1$s answer to &ldquo;%2$s&rdquo;', '%1$s answers to &ldquo;%2$s&rdquo;', $comment_count, 'comments title', 'faqs-theme')),
                    number_format_i18n($comment_count),
                    '<span>' . get_the_title() . '</span>'
                );
            }
            ?>
        </h2>

        <ol class="comment-list space-y-6">
            <?php
            wp_list_comments(array(
                'style' => 'ol',
                'short_ping' => true,
                'avatar_size' => 48,
                'callback' => 'faqs_theme_comment',
            ));
            ?>
        </ol>

        <?php
        the_comments_navigation(array(
            'prev_text' => '<span class="inline-flex items-center gap-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>' . __('Older Answers', 'faqs-theme') . '</span>',
            'next_text' => '<span class="inline-flex items-center gap-2">' . __('Newer Answers', 'faqs-theme') . '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></span>',
        ));
        ?>

    <?php endif; ?>

    <?php if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) : ?>
        <p class="no-comments text-gray-600 dark:text-gray-400 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
            <?php _e('Comments are closed.', 'faqs-theme'); ?>
        </p>
    <?php endif; ?>

    <?php
    comment_form(array(
        'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title text-2xl font-bold mb-6">',
        'title_reply_after' => '</h3>',
        'title_reply' => __('Leave an Answer', 'faqs-theme'),
        'comment_field' => '<div class="comment-form-comment mb-4">
            <label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">' . __('Your Answer', 'faqs-theme') . ' <span class="required text-red-500">*</span></label>
            <textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" required class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="' . esc_attr__('Share your knowledge and help others...', 'faqs-theme') . '"></textarea>
        </div>',
        'fields' => array(
            'author' => '<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="comment-form-author">
                    <label for="author" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">' . __('Name', 'faqs-theme') . ' <span class="required text-red-500">*</span></label>
                    <input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30" maxlength="245" required class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                </div>',
            'email' => '<div class="comment-form-email">
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">' . __('Email', 'faqs-theme') . ' <span class="required text-red-500">*</span></label>
                    <input id="email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . '" size="30" maxlength="100" required class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                </div>
            </div>',
            'url' => '<div class="comment-form-url mb-4">
                <label for="url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">' . __('Website', 'faqs-theme') . '</label>
                <input id="url" name="url" type="url" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" maxlength="200" class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
            </div>',
            'cookies' => '<div class="comment-form-cookies-consent mb-4">
                <input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes" class="mr-2" />
                <label for="wp-comment-cookies-consent" class="text-sm text-gray-600 dark:text-gray-400">' . __('Save my name, email, and website in this browser for the next time I comment.', 'faqs-theme') . '</label>
            </div>',
        ),
        'submit_button' => '<button type="submit" name="submit" id="submit" class="inline-flex items-center gap-2 px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
            </svg>
            %4$s
        </button>',
        'submit_field' => '<div class="form-submit">%1$s %2$s</div>',
        'class_submit' => 'submit',
    ));
    ?>
</div>

<?php
/**
 * Custom comment callback
 */
function faqs_theme_comment($comment, $args, $depth) {
    $tag = ('div' === $args['style']) ? 'div' : 'li';
    ?>
    <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class('comment bg-white dark:bg-gray-800 rounded-lg shadow-md p-6', $comment); ?>>
        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
            <footer class="comment-meta mb-4">
                <div class="comment-author vcard flex items-start gap-3">
                    <div class="avatar-wrap flex-shrink-0">
                        <?php
                        if (0 != $args['avatar_size']) {
                            echo get_avatar($comment, $args['avatar_size'], '', '', array('class' => 'rounded-full'));
                        }
                        ?>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <b class="fn text-lg font-semibold text-gray-900 dark:text-white">
                                    <?php echo get_comment_author_link($comment); ?>
                                </b>
                                <?php if ('0' == $comment->comment_approved) : ?>
                                    <span class="inline-flex items-center gap-1 ml-2 px-2 py-1 text-xs bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-300 rounded-full">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <?php _e('Awaiting moderation', 'faqs-theme'); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="comment-metadata text-sm text-gray-600 dark:text-gray-400">
                                <a href="<?php echo esc_url(get_comment_link($comment, $args)); ?>" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                    <time datetime="<?php comment_time('c'); ?>">
                                        <?php
                                        printf(
                                            __('%s ago', 'faqs-theme'),
                                            human_time_diff(get_comment_time('U'), current_time('timestamp'))
                                        );
                                        ?>
                                    </time>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>

            <div class="comment-content prose dark:prose-invert max-w-none">
                <?php comment_text(); ?>
            </div>

            <div class="comment-actions mt-4 flex items-center gap-4">
                <?php
                comment_reply_link(array_merge($args, array(
                    'add_below' => 'div-comment',
                    'depth' => $depth,
                    'max_depth' => $args['max_depth'],
                    'before' => '<div class="reply">',
                    'after' => '</div>',
                )));
                ?>

                <?php edit_comment_link(__('Edit', 'faqs-theme'), '<span class="edit-link text-sm">', '</span>'); ?>

                <!-- Helpful buttons -->
                <div class="comment-helpful flex items-center gap-2 ml-auto">
                    <button class="helpful-btn text-sm text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition-colors flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                        </svg>
                        <span><?php _e('Helpful', 'faqs-theme'); ?></span>
                    </button>
                </div>
            </div>
        </article>
    <?php
}
