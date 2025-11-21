<?php
/**
 * Dark Mode with localStorage
 *
 * @package FAQs_Theme
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add inline script for dark mode initialization
 * This must run before the page renders to prevent flash
 */
function faqs_theme_dark_mode_init() {
    ?>
    <script>
        (function() {
            // Check localStorage or system preference
            const darkMode = localStorage.getItem('darkMode');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            if (darkMode === 'enabled' || (darkMode === null && prefersDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
    <?php
}
add_action('wp_head', 'faqs_theme_dark_mode_init', 1);

/**
 * Add dark mode toggle to customizer
 */
function faqs_theme_dark_mode_customizer($wp_customize) {
    // Dark Mode Section
    $wp_customize->add_section('faqs_dark_mode', array(
        'title' => __('Dark Mode', 'faqs-theme'),
        'priority' => 30,
    ));

    // Enable Dark Mode Toggle
    $wp_customize->add_setting('enable_dark_mode', array(
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));

    $wp_customize->add_control('enable_dark_mode', array(
        'label' => __('Enable Dark Mode Toggle', 'faqs-theme'),
        'section' => 'faqs_dark_mode',
        'type' => 'checkbox',
    ));

    // Default Mode
    $wp_customize->add_setting('default_dark_mode', array(
        'default' => 'auto',
        'sanitize_callback' => 'faqs_theme_sanitize_dark_mode',
    ));

    $wp_customize->add_control('default_dark_mode', array(
        'label' => __('Default Mode', 'faqs-theme'),
        'section' => 'faqs_dark_mode',
        'type' => 'radio',
        'choices' => array(
            'auto' => __('Auto (Follow System Preference)', 'faqs-theme'),
            'light' => __('Light Mode', 'faqs-theme'),
            'dark' => __('Dark Mode', 'faqs-theme'),
        ),
    ));
}
add_action('customize_register', 'faqs_theme_dark_mode_customizer');

/**
 * Sanitize dark mode setting
 */
function faqs_theme_sanitize_dark_mode($input) {
    $valid = array('auto', 'light', 'dark');
    return in_array($input, $valid, true) ? $input : 'auto';
}

/**
 * Add body class for dark mode default
 */
function faqs_theme_dark_mode_body_class($classes) {
    $default_mode = get_theme_mod('default_dark_mode', 'auto');

    if ($default_mode === 'dark') {
        $classes[] = 'dark-default';
    } elseif ($default_mode === 'light') {
        $classes[] = 'light-default';
    } else {
        $classes[] = 'auto-dark-mode';
    }

    return $classes;
}
add_filter('body_class', 'faqs_theme_dark_mode_body_class');
