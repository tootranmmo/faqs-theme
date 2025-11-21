<?php
/**
 * Keyboard Shortcuts
 *
 * @package FAQs_Theme
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add keyboard shortcuts help modal
 */
function faqs_theme_keyboard_shortcuts_modal() {
    ?>
    <!-- Keyboard Shortcuts Modal -->
    <div id="keyboard-shortcuts-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true" aria-labelledby="shortcuts-modal-title">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75" aria-hidden="true"></div>

            <!-- Modal panel -->
            <div class="relative inline-block w-full max-w-2xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-xl rounded-2xl">
                <div class="flex items-center justify-between mb-6">
                    <h3 id="shortcuts-modal-title" class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                        <?php _e('Keyboard Shortcuts', 'faqs-theme'); ?>
                    </h3>
                    <button
                        id="close-shortcuts-modal"
                        class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        aria-label="<?php esc_attr_e('Close shortcuts help', 'faqs-theme'); ?>"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="shortcuts-grid grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Navigation -->
                    <div class="shortcut-section">
                        <h4 class="text-lg font-semibold mb-3 text-gray-900 dark:text-white"><?php _e('Navigation', 'faqs-theme'); ?></h4>
                        <div class="space-y-2">
                            <div class="shortcut-item flex items-center justify-between">
                                <span class="text-gray-600 dark:text-gray-400"><?php _e('Search', 'faqs-theme'); ?></span>
                                <kbd class="kbd">/</kbd>
                            </div>
                            <div class="shortcut-item flex items-center justify-between">
                                <span class="text-gray-600 dark:text-gray-400"><?php _e('Go to home', 'faqs-theme'); ?></span>
                                <kbd class="kbd">g + h</kbd>
                            </div>
                            <div class="shortcut-item flex items-center justify-between">
                                <span class="text-gray-600 dark:text-gray-400"><?php _e('Scroll to top', 'faqs-theme'); ?></span>
                                <kbd class="kbd">↑↑</kbd>
                            </div>
                            <div class="shortcut-item flex items-center justify-between">
                                <span class="text-gray-600 dark:text-gray-400"><?php _e('Scroll to bottom', 'faqs-theme'); ?></span>
                                <kbd class="kbd">↓↓</kbd>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="shortcut-section">
                        <h4 class="text-lg font-semibold mb-3 text-gray-900 dark:text-white"><?php _e('Actions', 'faqs-theme'); ?></h4>
                        <div class="space-y-2">
                            <div class="shortcut-item flex items-center justify-between">
                                <span class="text-gray-600 dark:text-gray-400"><?php _e('Copy link', 'faqs-theme'); ?></span>
                                <kbd class="kbd">c</kbd>
                            </div>
                            <div class="shortcut-item flex items-center justify-between">
                                <span class="text-gray-600 dark:text-gray-400"><?php _e('Print', 'faqs-theme'); ?></span>
                                <kbd class="kbd">p</kbd>
                            </div>
                            <div class="shortcut-item flex items-center justify-between">
                                <span class="text-gray-600 dark:text-gray-400"><?php _e('Bookmark', 'faqs-theme'); ?></span>
                                <kbd class="kbd">b</kbd>
                            </div>
                            <div class="shortcut-item flex items-center justify-between">
                                <span class="text-gray-600 dark:text-gray-400"><?php _e('Share', 'faqs-theme'); ?></span>
                                <kbd class="kbd">s</kbd>
                            </div>
                        </div>
                    </div>

                    <!-- Display -->
                    <div class="shortcut-section">
                        <h4 class="text-lg font-semibold mb-3 text-gray-900 dark:text-white"><?php _e('Display', 'faqs-theme'); ?></h4>
                        <div class="space-y-2">
                            <div class="shortcut-item flex items-center justify-between">
                                <span class="text-gray-600 dark:text-gray-400"><?php _e('Toggle dark mode', 'faqs-theme'); ?></span>
                                <kbd class="kbd">d</kbd>
                            </div>
                            <div class="shortcut-item flex items-center justify-between">
                                <span class="text-gray-600 dark:text-gray-400"><?php _e('Increase font', 'faqs-theme'); ?></span>
                                <kbd class="kbd">+</kbd>
                            </div>
                            <div class="shortcut-item flex items-center justify-between">
                                <span class="text-gray-600 dark:text-gray-400"><?php _e('Decrease font', 'faqs-theme'); ?></span>
                                <kbd class="kbd">-</kbd>
                            </div>
                        </div>
                    </div>

                    <!-- Help -->
                    <div class="shortcut-section">
                        <h4 class="text-lg font-semibold mb-3 text-gray-900 dark:text-white"><?php _e('Help', 'faqs-theme'); ?></h4>
                        <div class="space-y-2">
                            <div class="shortcut-item flex items-center justify-between">
                                <span class="text-gray-600 dark:text-gray-400"><?php _e('Show shortcuts', 'faqs-theme'); ?></span>
                                <kbd class="kbd">?</kbd>
                            </div>
                            <div class="shortcut-item flex items-center justify-between">
                                <span class="text-gray-600 dark:text-gray-400"><?php _e('Close modal', 'faqs-theme'); ?></span>
                                <kbd class="kbd">Esc</kbd>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
                    <p class="text-sm text-blue-800 dark:text-blue-200">
                        <strong><?php _e('Tip:', 'faqs-theme'); ?></strong>
                        <?php _e('Most shortcuts work when you\'re not typing in an input field.', 'faqs-theme'); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <style>
    .kbd {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        font-family: monospace;
        font-weight: 600;
        line-height: 1;
        color: #1f2937;
        background-color: #f3f4f6;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }

    .dark .kbd {
        color: #f3f4f6;
        background-color: #374151;
        border-color: #4b5563;
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const shortcutsModal = document.getElementById('keyboard-shortcuts-modal');
        const closeShortcutsBtn = document.getElementById('close-shortcuts-modal');

        let lastKey = '';
        let lastKeyTime = 0;

        function openShortcutsModal() {
            if (shortcutsModal) {
                shortcutsModal.classList.remove('hidden');
                shortcutsModal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeShortcutsModal() {
            if (shortcutsModal) {
                shortcutsModal.classList.add('hidden');
                shortcutsModal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
            }
        }

        if (closeShortcutsBtn) {
            closeShortcutsBtn.addEventListener('click', closeShortcutsModal);
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Don't trigger if user is typing in input
            if (['INPUT', 'TEXTAREA', 'SELECT'].includes(e.target.tagName)) {
                return;
            }

            const key = e.key.toLowerCase();
            const now = Date.now();

            // Check for double key press (within 500ms)
            const isDoublePress = (lastKey === key && now - lastKeyTime < 500);

            // Help - ?
            if (e.shiftKey && key === '?') {
                e.preventDefault();
                openShortcutsModal();
                return;
            }

            // Close modal - Escape
            if (key === 'escape') {
                closeShortcutsModal();
                return;
            }

            // Search - /
            if (key === '/') {
                e.preventDefault();
                const searchToggle = document.getElementById('search-toggle');
                if (searchToggle) searchToggle.click();
                return;
            }

            // Dark mode - d
            if (key === 'd') {
                e.preventDefault();
                const darkModeToggle = document.getElementById('dark-mode-toggle');
                if (darkModeToggle) darkModeToggle.click();
                return;
            }

            // Copy link - c
            if (key === 'c') {
                e.preventDefault();
                const copyLinkBtn = document.getElementById('copy-link-btn');
                if (copyLinkBtn) copyLinkBtn.click();
                return;
            }

            // Print - p
            if (key === 'p') {
                e.preventDefault();
                window.print();
                return;
            }

            // Bookmark - b
            if (key === 'b') {
                e.preventDefault();
                const bookmarkBtn = document.getElementById('bookmark-btn');
                if (bookmarkBtn) bookmarkBtn.click();
                return;
            }

            // Increase font - +
            if (key === '+' || key === '=') {
                e.preventDefault();
                const increaseBtn = document.getElementById('increase-font');
                if (increaseBtn) increaseBtn.click();
                return;
            }

            // Decrease font - -
            if (key === '-') {
                e.preventDefault();
                const decreaseBtn = document.getElementById('decrease-font');
                if (decreaseBtn) decreaseBtn.click();
                return;
            }

            // Scroll to top - ↑↑
            if (key === 'arrowup' && isDoublePress) {
                e.preventDefault();
                window.scrollTo({ top: 0, behavior: 'smooth' });
                return;
            }

            // Scroll to bottom - ↓↓
            if (key === 'arrowdown' && isDoublePress) {
                e.preventDefault();
                window.scrollTo({ top: document.documentElement.scrollHeight, behavior: 'smooth' });
                return;
            }

            // Go to home - g + h
            if (lastKey === 'g' && key === 'h' && now - lastKeyTime < 1000) {
                e.preventDefault();
                window.location.href = '<?php echo esc_js(home_url('/')); ?>';
                return;
            }

            lastKey = key;
            lastKeyTime = now;
        });

        // Click backdrop to close
        if (shortcutsModal) {
            shortcutsModal.addEventListener('click', function(e) {
                if (e.target === shortcutsModal) {
                    closeShortcutsModal();
                }
            });
        }
    });
    </script>
    <?php
}
add_action('wp_footer', 'faqs_theme_keyboard_shortcuts_modal');

/**
 * Add keyboard shortcuts indicator
 */
function faqs_theme_shortcuts_indicator() {
    ?>
    <button
        id="shortcuts-help-btn"
        class="fixed bottom-24 right-8 p-3 bg-gray-800 dark:bg-gray-700 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-300 z-30 group"
        aria-label="<?php esc_attr_e('Keyboard shortcuts help', 'faqs-theme'); ?>"
        onclick="document.getElementById('keyboard-shortcuts-modal').classList.remove('hidden'); document.body.style.overflow = 'hidden';"
    >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
        </svg>
        <span class="absolute right-full mr-2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
            <?php _e('Press ? for shortcuts', 'faqs-theme'); ?>
        </span>
    </button>
    <?php
}
add_action('wp_footer', 'faqs_theme_shortcuts_indicator');
