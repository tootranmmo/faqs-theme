<footer id="colophon" class="site-footer bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-12" role="contentinfo">
    <div class="container mx-auto px-4 py-12">
        <?php if (is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3')) : ?>
            <div class="footer-widgets grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <?php if (is_active_sidebar('footer-1')) : ?>
                    <div class="footer-widget-area">
                        <?php dynamic_sidebar('footer-1'); ?>
                    </div>
                <?php endif; ?>

                <?php if (is_active_sidebar('footer-2')) : ?>
                    <div class="footer-widget-area">
                        <?php dynamic_sidebar('footer-2'); ?>
                    </div>
                <?php endif; ?>

                <?php if (is_active_sidebar('footer-3')) : ?>
                    <div class="footer-widget-area">
                        <?php dynamic_sidebar('footer-3'); ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (has_nav_menu('footer')) : ?>
            <nav class="footer-navigation mb-8" role="navigation" aria-label="<?php esc_attr_e('Footer Navigation', 'faqs-theme'); ?>">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer',
                    'menu_class' => 'footer-menu flex flex-wrap justify-center gap-6 text-sm',
                    'container' => false,
                    'depth' => 1,
                ));
                ?>
            </nav>
        <?php endif; ?>

        <div class="site-info text-center text-sm text-gray-600 dark:text-gray-400">
            <p class="copyright mb-2">
                &copy; <?php echo date('Y'); ?>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                    <?php bloginfo('name'); ?>
                </a>
                <?php _e('All rights reserved.', 'faqs-theme'); ?>
            </p>
            <p class="theme-credit">
                <?php
                printf(
                    esc_html__('Powered by %1$s | Theme: %2$s', 'faqs-theme'),
                    '<a href="https://wordpress.org/" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors" rel="nofollow">WordPress</a>',
                    '<a href="https://github.com/tootranmmo/faqs-theme" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">FAQs Theme</a>'
                );
                ?>
            </p>
        </div>
    </div>

    <!-- Back to top button -->
    <button
        id="back-to-top"
        class="back-to-top fixed bottom-8 right-8 p-3 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg opacity-0 invisible transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 z-30"
        aria-label="<?php esc_attr_e('Back to top', 'faqs-theme'); ?>"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
        </svg>
    </button>
</footer>

<?php wp_footer(); ?>

<!-- Remove no-js class and add js-loaded class -->
<script>
    document.documentElement.classList.remove('no-js');
    document.documentElement.classList.add('js-loaded');
</script>

</body>
</html>
