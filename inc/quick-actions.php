<?php
/**
 * FAQ Quick Actions
 * - Copy link
 * - Print
 * - Bookmark/Save for later
 * - Share
 *
 * @package FAQs_Theme
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Display quick actions bar
 *
 * @param int $post_id Post ID
 * @return string HTML output
 */
function faqs_theme_display_quick_actions($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $permalink = get_permalink($post_id);
    $title = get_the_title($post_id);

    ob_start();
    ?>
    <div class="quick-actions sticky top-20 z-30 bg-white dark:bg-gray-800 border-y border-gray-200 dark:border-gray-700 py-3 mb-8">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div class="quick-actions-left flex items-center gap-2">
                <!-- Copy Link -->
                <button
                    id="copy-link-btn"
                    class="quick-action-btn px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors flex items-center gap-2 text-sm font-medium"
                    data-url="<?php echo esc_attr($permalink); ?>"
                    aria-label="<?php esc_attr_e('Copy link', 'faqs-theme'); ?>"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    <span class="copy-text"><?php _e('Copy Link', 'faqs-theme'); ?></span>
                </button>

                <!-- Print -->
                <button
                    id="print-btn"
                    class="quick-action-btn px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors flex items-center gap-2 text-sm font-medium"
                    aria-label="<?php esc_attr_e('Print', 'faqs-theme'); ?>"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    <span><?php _e('Print', 'faqs-theme'); ?></span>
                </button>

                <!-- Bookmark -->
                <button
                    id="bookmark-btn"
                    class="quick-action-btn px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors flex items-center gap-2 text-sm font-medium"
                    data-post-id="<?php echo esc_attr($post_id); ?>"
                    aria-label="<?php esc_attr_e('Bookmark', 'faqs-theme'); ?>"
                >
                    <svg class="w-4 h-4 bookmark-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                    </svg>
                    <span class="bookmark-text"><?php _e('Bookmark', 'faqs-theme'); ?></span>
                </button>

                <!-- Font Size -->
                <div class="font-size-controls flex items-center gap-1 ml-2">
                    <button
                        id="decrease-font"
                        class="quick-action-btn p-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors"
                        aria-label="<?php esc_attr_e('Decrease font size', 'faqs-theme'); ?>"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                        </svg>
                    </button>
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">A</span>
                    <button
                        id="increase-font"
                        class="quick-action-btn p-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors"
                        aria-label="<?php esc_attr_e('Increase font size', 'faqs-theme'); ?>"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="quick-actions-right flex items-center gap-2">
                <!-- Reading Progress -->
                <div class="reading-progress text-sm text-gray-600 dark:text-gray-400 hidden md:block">
                    <span id="reading-progress-text"><?php _e('0% read', 'faqs-theme'); ?></span>
                </div>

                <!-- Estimated Reading Time -->
                <div class="reading-time flex items-center gap-1 text-sm text-gray-600 dark:text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span><?php echo faqs_theme_reading_time($post_id); ?></span>
                </div>
            </div>
        </div>

        <!-- Reading Progress Bar -->
        <div class="reading-progress-bar mt-2">
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1">
                <div id="reading-progress-fill" class="bg-blue-500 h-1 rounded-full transition-all duration-150" style="width: 0%"></div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Copy Link
        const copyLinkBtn = document.getElementById('copy-link-btn');
        if (copyLinkBtn) {
            copyLinkBtn.addEventListener('click', function() {
                const url = this.dataset.url;
                navigator.clipboard.writeText(url).then(() => {
                    const copyText = this.querySelector('.copy-text');
                    const originalText = copyText.textContent;
                    copyText.textContent = '<?php esc_js(_e('Copied!', 'faqs-theme')); ?>';

                    setTimeout(() => {
                        copyText.textContent = originalText;
                    }, 2000);
                });
            });
        }

        // Print
        const printBtn = document.getElementById('print-btn');
        if (printBtn) {
            printBtn.addEventListener('click', function() {
                window.print();
            });
        }

        // Bookmark
        const bookmarkBtn = document.getElementById('bookmark-btn');
        if (bookmarkBtn) {
            const postId = bookmarkBtn.dataset.postId;
            const bookmarkedKey = 'faqs_bookmarked_' + postId;

            // Check if already bookmarked
            if (localStorage.getItem(bookmarkedKey)) {
                bookmarkBtn.classList.add('bookmarked');
                const icon = bookmarkBtn.querySelector('.bookmark-icon');
                icon.setAttribute('fill', 'currentColor');
                bookmarkBtn.querySelector('.bookmark-text').textContent = '<?php esc_js(_e('Bookmarked', 'faqs-theme')); ?>';
            }

            bookmarkBtn.addEventListener('click', function() {
                const isBookmarked = localStorage.getItem(bookmarkedKey);
                const icon = this.querySelector('.bookmark-icon');
                const text = this.querySelector('.bookmark-text');

                if (isBookmarked) {
                    localStorage.removeItem(bookmarkedKey);
                    this.classList.remove('bookmarked');
                    icon.setAttribute('fill', 'none');
                    text.textContent = '<?php esc_js(_e('Bookmark', 'faqs-theme')); ?>';
                } else {
                    localStorage.setItem(bookmarkedKey, '1');
                    this.classList.add('bookmarked');
                    icon.setAttribute('fill', 'currentColor');
                    text.textContent = '<?php esc_js(_e('Bookmarked', 'faqs-theme')); ?>';
                }
            });
        }

        // Font Size Controls
        const decreaseBtn = document.getElementById('decrease-font');
        const increaseBtn = document.getElementById('increase-font');
        const content = document.querySelector('.entry-content');

        if (decreaseBtn && increaseBtn && content) {
            let fontSize = parseInt(localStorage.getItem('faqs_font_size')) || 100;
            content.style.fontSize = fontSize + '%';

            decreaseBtn.addEventListener('click', () => {
                if (fontSize > 80) {
                    fontSize -= 10;
                    content.style.fontSize = fontSize + '%';
                    localStorage.setItem('faqs_font_size', fontSize);
                }
            });

            increaseBtn.addEventListener('click', () => {
                if (fontSize < 140) {
                    fontSize += 10;
                    content.style.fontSize = fontSize + '%';
                    localStorage.setItem('faqs_font_size', fontSize);
                }
            });
        }

        // Reading Progress
        const progressFill = document.getElementById('reading-progress-fill');
        const progressText = document.getElementById('reading-progress-text');

        function updateReadingProgress() {
            const windowHeight = window.innerHeight;
            const documentHeight = document.documentElement.scrollHeight - windowHeight;
            const scrolled = window.scrollY;
            const progress = (scrolled / documentHeight) * 100;

            if (progressFill) {
                progressFill.style.width = Math.min(progress, 100) + '%';
            }

            if (progressText) {
                progressText.textContent = Math.round(Math.min(progress, 100)) + '% <?php esc_js(_e('read', 'faqs-theme')); ?>';
            }
        }

        window.addEventListener('scroll', updateReadingProgress);
        updateReadingProgress();
    });
    </script>
    <?php
    return ob_get_clean();
}
