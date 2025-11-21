<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>

    <!-- Critical CSS for above-the-fold content -->
    <style>
        /* Prevent flash of unstyled content */
        html.no-js { opacity: 0; }
        html.js-loaded { opacity: 1; transition: opacity 0.3s; }

        /* Dark mode transition */
        * {
            transition: background-color 0.3s ease, color 0.3s ease;
        }
    </style>

    <!-- Preload critical resources -->
    <link rel="preload" href="<?php echo FAQS_THEME_URI; ?>/assets/css/style.css" as="style">
    <link rel="preload" href="<?php echo FAQS_THEME_URI; ?>/assets/js/main.js" as="script">
</head>

<body <?php body_class('antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100'); ?>>
<?php wp_body_open(); ?>

<a class="skip-link sr-only focus:not-sr-only focus:absolute focus:top-0 focus:left-0 focus:z-50 focus:p-4 focus:bg-blue-600 focus:text-white" href="#main-content">
    <?php _e('Skip to content', 'faqs-theme'); ?>
</a>

<header id="masthead" class="site-header sticky top-0 z-40 bg-white dark:bg-gray-800 shadow-md" role="banner">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            <!-- Logo and Site Title -->
            <div class="site-branding flex items-center">
                <?php if (has_custom_logo()) : ?>
                    <div class="site-logo">
                        <?php the_custom_logo(); ?>
                    </div>
                <?php endif; ?>

                <div class="site-title-wrap <?php echo has_custom_logo() ? 'ml-3' : ''; ?>">
                    <?php if (is_front_page() && is_home()) : ?>
                        <h1 class="site-title text-2xl font-bold">
                            <a href="<?php echo esc_url(home_url('/')); ?>" rel="home" class="text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                <?php bloginfo('name'); ?>
                            </a>
                        </h1>
                    <?php else : ?>
                        <p class="site-title text-2xl font-bold">
                            <a href="<?php echo esc_url(home_url('/')); ?>" rel="home" class="text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                <?php bloginfo('name'); ?>
                            </a>
                        </p>
                    <?php endif; ?>

                    <?php
                    $description = get_bloginfo('description', 'display');
                    if ($description || is_customize_preview()) :
                    ?>
                        <p class="site-description text-sm text-gray-600 dark:text-gray-400"><?php echo $description; ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Desktop Navigation -->
            <nav id="site-navigation" class="main-navigation hidden md:flex items-center gap-6" role="navigation" aria-label="<?php esc_attr_e('Primary Navigation', 'faqs-theme'); ?>">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_class' => 'primary-menu flex gap-6',
                    'container' => false,
                    'fallback_cb' => false,
                    'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                    'depth' => 2,
                ));
                ?>

                <!-- Dark Mode Toggle -->
                <button
                    id="dark-mode-toggle"
                    class="dark-mode-toggle p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500"
                    aria-label="<?php esc_attr_e('Toggle dark mode', 'faqs-theme'); ?>"
                    aria-pressed="false"
                >
                    <svg class="sun-icon w-6 h-6 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <svg class="moon-icon w-6 h-6 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                    </svg>
                </button>

                <!-- Search Toggle -->
                <button
                    id="search-toggle"
                    class="search-toggle p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500"
                    aria-label="<?php esc_attr_e('Toggle search', 'faqs-theme'); ?>"
                    aria-expanded="false"
                    aria-controls="search-modal"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
            </nav>

            <!-- Mobile Menu Toggle -->
            <button
                id="mobile-menu-toggle"
                class="mobile-menu-toggle md:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500"
                aria-label="<?php esc_attr_e('Toggle mobile menu', 'faqs-theme'); ?>"
                aria-expanded="false"
                aria-controls="mobile-menu"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="mobile-menu md:hidden hidden overflow-hidden transition-all duration-300" role="navigation" aria-label="<?php esc_attr_e('Mobile Navigation', 'faqs-theme'); ?>">
            <div class="py-4 border-t border-gray-200 dark:border-gray-700">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_class' => 'mobile-primary-menu flex flex-col gap-2',
                    'container' => false,
                    'fallback_cb' => false,
                    'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                    'depth' => 2,
                ));
                ?>

                <div class="flex items-center gap-4 mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button
                        id="mobile-dark-mode-toggle"
                        class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                        aria-label="<?php esc_attr_e('Toggle dark mode', 'faqs-theme'); ?>"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                        </svg>
                        <span><?php _e('Dark Mode', 'faqs-theme'); ?></span>
                    </button>

                    <button
                        id="mobile-search-toggle"
                        class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                        aria-label="<?php esc_attr_e('Toggle search', 'faqs-theme'); ?>"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span><?php _e('Search', 'faqs-theme'); ?></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Search Modal -->
<div id="search-modal" class="search-modal fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true" aria-labelledby="search-modal-title">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75" aria-hidden="true"></div>

        <!-- Modal panel -->
        <div class="relative inline-block w-full max-w-2xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-xl rounded-2xl">
            <div class="flex items-center justify-between mb-4">
                <h3 id="search-modal-title" class="text-lg font-semibold text-gray-900 dark:text-white">
                    <?php _e('Search FAQs', 'faqs-theme'); ?>
                </h3>
                <button
                    id="close-search-modal"
                    class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    aria-label="<?php esc_attr_e('Close search', 'faqs-theme'); ?>"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form role="search" method="get" class="search-form relative" action="<?php echo esc_url(home_url('/')); ?>">
                <label for="search-input" class="sr-only"><?php _e('Search for:', 'faqs-theme'); ?></label>
                <div class="relative">
                    <input
                        type="search"
                        id="search-input"
                        class="w-full px-4 py-3 pl-12 pr-4 text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-700 border-0 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="<?php echo esc_attr_x('Search FAQs...', 'placeholder', 'faqs-theme'); ?>"
                        value="<?php echo get_search_query(); ?>"
                        name="s"
                        autocomplete="off"
                    />
                    <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <div id="search-results" class="mt-4 max-h-96 overflow-y-auto" role="region" aria-live="polite" aria-atomic="true"></div>
            </form>
        </div>
    </div>
</div>
