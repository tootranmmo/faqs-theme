<?php
/**
 * Tool Placeholder Template
 * Used for tools that are coming soon
 *
 * @package FAQs_Theme
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Generic placeholder function for coming soon tools
 */
function faqs_tools_render_placeholder($tool_name, $tool_description, $features = array()) {
    ?>
    <div class="wrap faqs-tool-page">
        <div class="faqs-tool-header">
            <h1><?php echo esc_html($tool_name); ?></h1>
            <p class="description"><?php echo esc_html($tool_description); ?></p>
        </div>

        <div class="faqs-tool-content">
            <div style="text-align: center; padding: 80px 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px;">
                <div style="font-size: 72px; margin-bottom: 20px;">🚀</div>
                <h2 style="font-size: 36px; margin: 0 0 20px 0; color: white;">
                    <?php _e('Coming Soon!', 'faqs-theme'); ?>
                </h2>
                <p style="font-size: 18px; opacity: 0.9; max-width: 600px; margin: 0 auto;">
                    <?php printf(__('The %s tool is currently under development and will be available in a future update.', 'faqs-theme'), $tool_name); ?>
                </p>
            </div>

            <?php if (!empty($features)) : ?>
                <h2 style="margin-top: 40px;"><?php _e('Planned Features', 'faqs-theme'); ?></h2>
                <div style="background: #f9f9f9; padding: 30px; border-radius: 8px;">
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <?php foreach ($features as $feature) : ?>
                            <li style="padding: 12px 0; border-bottom: 1px solid #ddd; display: flex; align-items: center; gap: 10px;">
                                <span style="color: #46b450; font-size: 20px;">✓</span>
                                <span><?php echo esc_html($feature); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="faqs-alert info" style="margin-top: 30px;">
                <p>
                    <strong><?php _e('Want to prioritize this tool?', 'faqs-theme'); ?></strong><br>
                    <?php _e('Let us know which tools are most important to you! Contact the theme developer to share your feedback.', 'faqs-theme'); ?>
                </p>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Add placeholder submenu for a tool
 */
function faqs_tools_add_placeholder_menu($parent_slug, $page_title, $menu_title, $menu_slug, $tool_description, $features = array()) {
    add_submenu_page(
        $parent_slug,
        $page_title,
        $menu_title,
        'manage_options',
        $menu_slug,
        function() use ($page_title, $tool_description, $features) {
            faqs_tools_render_placeholder($page_title, $tool_description, $features);
        }
    );
}

// Register placeholder tools
add_action('admin_menu', function() {
    // Quick Duplicate
    faqs_tools_add_placeholder_menu(
        'faqs-tools',
        __('Quick Duplicate', 'faqs-theme'),
        __('Quick Duplicate', 'faqs-theme'),
        'faqs-tool-quick-duplicate',
        __('Duplicate posts quickly with one click', 'faqs-theme'),
        array(
            __('One-click post duplication', 'faqs-theme'),
            __('Preserve all post meta and taxonomies', 'faqs-theme'),
            __('Bulk duplicate multiple posts', 'faqs-theme'),
            __('Add "Duplicate" button to post list', 'faqs-theme'),
        )
    );

    // Post Templates
    faqs_tools_add_placeholder_menu(
        'faqs-tools',
        __('Post Templates', 'faqs-theme'),
        __('Post Templates', 'faqs-theme'),
        'faqs-tool-post-templates',
        __('Save and reuse post structures', 'faqs-theme'),
        array(
            __('Save posts as templates', 'faqs-theme'),
            __('Reuse templates for new posts', 'faqs-theme'),
            __('Template library with categories', 'faqs-theme'),
            __('Share templates between sites', 'faqs-theme'),
        )
    );

    // Broken Link Checker
    faqs_tools_add_placeholder_menu(
        'faqs-tools',
        __('Broken Link Checker', 'faqs-theme'),
        __('Broken Links', 'faqs-theme'),
        'faqs-tool-broken-links',
        __('Find and fix broken links automatically', 'faqs-theme'),
        array(
            __('Automatic link scanning', 'faqs-theme'),
            __('Email notifications for broken links', 'faqs-theme'),
            __('Bulk fix or remove links', 'faqs-theme'),
            __('Monitor external links status', 'faqs-theme'),
        )
    );

    // Image Compressor
    faqs_tools_add_placeholder_menu(
        'faqs-tools',
        __('Image Compressor', 'faqs-theme'),
        __('Image Compressor', 'faqs-theme'),
        'faqs-tool-image-compressor',
        __('Compress images automatically on upload', 'faqs-theme'),
        array(
            __('Auto-compress on upload', 'faqs-theme'),
            __('Bulk compress existing images', 'faqs-theme'),
            __('WebP conversion', 'faqs-theme'),
            __('Maintain quality settings', 'faqs-theme'),
        )
    );

    // Content Calendar
    faqs_tools_add_placeholder_menu(
        'faqs-tools',
        __('Content Calendar', 'faqs-theme'),
        __('Calendar', 'faqs-theme'),
        'faqs-tool-content-calendar',
        __('Visual content planning with drag-and-drop', 'faqs-theme'),
        array(
            __('Drag-and-drop calendar interface', 'faqs-theme'),
            __('Schedule posts visually', 'faqs-theme'),
            __('Editorial calendar view', 'faqs-theme'),
            __('Color-coded by category', 'faqs-theme'),
        )
    );

    // Advanced Analytics
    faqs_tools_add_placeholder_menu(
        'faqs-tools',
        __('Advanced Analytics', 'faqs-theme'),
        __('Analytics', 'faqs-theme'),
        'faqs-tool-custom-dashboard',
        __('Custom analytics dashboard with charts', 'faqs-theme'),
        array(
            __('Custom analytics dashboard', 'faqs-theme'),
            __('Beautiful charts and graphs', 'faqs-theme'),
            __('Export reports to Excel/PDF', 'faqs-theme'),
            __('Track custom metrics', 'faqs-theme'),
        )
    );

    // More placeholder tools can be added here...
}, 99);
