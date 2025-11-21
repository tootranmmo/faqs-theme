<?php
/**
 * Category Structure & Auto Import for General Knowledge / Wiki / Blog
 *
 * @package FAQs_Theme
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * General Knowledge / Wiki Category Structure
 */
function faqs_theme_get_category_structure() {
    return array(
        'technology' => array(
            'name' => __('Technology & Computers', 'faqs-theme'),
            'slug' => 'technology',
            'description' => __('Everything about technology, computers, software, and digital trends', 'faqs-theme'),
            'icon' => '💻',
            'color' => '#3b82f6', // Blue
            'order' => 1,
            'children' => array(
                'software' => array(
                    'name' => __('Software & Apps', 'faqs-theme'),
                    'slug' => 'software',
                    'description' => __('Software applications, mobile apps, and desktop programs', 'faqs-theme'),
                ),
                'hardware' => array(
                    'name' => __('Hardware & Devices', 'faqs-theme'),
                    'slug' => 'hardware',
                    'description' => __('Computer hardware, smartphones, tablets, and gadgets', 'faqs-theme'),
                ),
                'internet' => array(
                    'name' => __('Internet & Web', 'faqs-theme'),
                    'slug' => 'internet',
                    'description' => __('Internet basics, browsers, websites, and online services', 'faqs-theme'),
                ),
                'programming' => array(
                    'name' => __('Programming & Development', 'faqs-theme'),
                    'slug' => 'programming',
                    'description' => __('Coding, programming languages, and software development', 'faqs-theme'),
                ),
            ),
        ),
        'science' => array(
            'name' => __('Science & Nature', 'faqs-theme'),
            'slug' => 'science',
            'description' => __('Scientific discoveries, natural phenomena, and environmental topics', 'faqs-theme'),
            'icon' => '🔬',
            'color' => '#10b981', // Green
            'order' => 2,
            'children' => array(
                'physics' => array(
                    'name' => __('Physics & Chemistry', 'faqs-theme'),
                    'slug' => 'physics',
                    'description' => __('Laws of physics, chemical reactions, and matter', 'faqs-theme'),
                ),
                'biology' => array(
                    'name' => __('Biology & Life Sciences', 'faqs-theme'),
                    'slug' => 'biology',
                    'description' => __('Living organisms, human body, and ecosystems', 'faqs-theme'),
                ),
                'astronomy' => array(
                    'name' => __('Astronomy & Space', 'faqs-theme'),
                    'slug' => 'astronomy',
                    'description' => __('Stars, planets, galaxies, and space exploration', 'faqs-theme'),
                ),
                'environment' => array(
                    'name' => __('Environment & Climate', 'faqs-theme'),
                    'slug' => 'environment',
                    'description' => __('Environmental issues, climate change, and sustainability', 'faqs-theme'),
                ),
            ),
        ),
        'health' => array(
            'name' => __('Health & Wellness', 'faqs-theme'),
            'slug' => 'health',
            'description' => __('Health tips, medical information, fitness, and wellness advice', 'faqs-theme'),
            'icon' => '🏥',
            'color' => '#ef4444', // Red
            'order' => 3,
            'children' => array(
                'medical' => array(
                    'name' => __('Medical & Conditions', 'faqs-theme'),
                    'slug' => 'medical',
                    'description' => __('Medical conditions, symptoms, and treatments', 'faqs-theme'),
                ),
                'fitness' => array(
                    'name' => __('Fitness & Exercise', 'faqs-theme'),
                    'slug' => 'fitness',
                    'description' => __('Workout routines, exercise tips, and physical health', 'faqs-theme'),
                ),
                'nutrition' => array(
                    'name' => __('Nutrition & Diet', 'faqs-theme'),
                    'slug' => 'nutrition',
                    'description' => __('Healthy eating, diets, vitamins, and supplements', 'faqs-theme'),
                ),
                'mental-health' => array(
                    'name' => __('Mental Health', 'faqs-theme'),
                    'slug' => 'mental-health',
                    'description' => __('Mental wellness, stress management, and emotional health', 'faqs-theme'),
                ),
            ),
        ),
        'education' => array(
            'name' => __('Education & Learning', 'faqs-theme'),
            'slug' => 'education',
            'description' => __('Educational resources, study tips, and learning guides', 'faqs-theme'),
            'icon' => '📚',
            'color' => '#8b5cf6', // Purple
            'order' => 4,
            'children' => array(
                'academic' => array(
                    'name' => __('Academic Subjects', 'faqs-theme'),
                    'slug' => 'academic',
                    'description' => __('Math, science, languages, and school subjects', 'faqs-theme'),
                ),
                'study-tips' => array(
                    'name' => __('Study Tips & Methods', 'faqs-theme'),
                    'slug' => 'study-tips',
                    'description' => __('Effective study techniques and learning strategies', 'faqs-theme'),
                ),
                'career' => array(
                    'name' => __('Career & Skills', 'faqs-theme'),
                    'slug' => 'career',
                    'description' => __('Career development, job skills, and professional growth', 'faqs-theme'),
                ),
                'languages' => array(
                    'name' => __('Languages & Communication', 'faqs-theme'),
                    'slug' => 'languages',
                    'description' => __('Language learning, grammar, and communication skills', 'faqs-theme'),
                ),
            ),
        ),
        'lifestyle' => array(
            'name' => __('Lifestyle & Entertainment', 'faqs-theme'),
            'slug' => 'lifestyle',
            'description' => __('Daily life, hobbies, entertainment, and leisure activities', 'faqs-theme'),
            'icon' => '🎨',
            'color' => '#f59e0b', // Orange
            'order' => 5,
            'children' => array(
                'home' => array(
                    'name' => __('Home & Garden', 'faqs-theme'),
                    'slug' => 'home',
                    'description' => __('Home improvement, gardening, and interior design', 'faqs-theme'),
                ),
                'cooking' => array(
                    'name' => __('Cooking & Recipes', 'faqs-theme'),
                    'slug' => 'cooking',
                    'description' => __('Recipes, cooking techniques, and food preparation', 'faqs-theme'),
                ),
                'travel' => array(
                    'name' => __('Travel & Places', 'faqs-theme'),
                    'slug' => 'travel',
                    'description' => __('Travel guides, destinations, and tourism tips', 'faqs-theme'),
                ),
                'entertainment' => array(
                    'name' => __('Movies, Music & Games', 'faqs-theme'),
                    'slug' => 'entertainment',
                    'description' => __('Entertainment, media, gaming, and pop culture', 'faqs-theme'),
                ),
            ),
        ),
        'business' => array(
            'name' => __('Business & Finance', 'faqs-theme'),
            'slug' => 'business',
            'description' => __('Business advice, financial planning, and money management', 'faqs-theme'),
            'icon' => '💼',
            'color' => '#06b6d4', // Cyan
            'order' => 6,
            'children' => array(
                'entrepreneurship' => array(
                    'name' => __('Entrepreneurship & Startups', 'faqs-theme'),
                    'slug' => 'entrepreneurship',
                    'description' => __('Starting a business, entrepreneurship, and startup advice', 'faqs-theme'),
                ),
                'personal-finance' => array(
                    'name' => __('Personal Finance', 'faqs-theme'),
                    'slug' => 'personal-finance',
                    'description' => __('Money management, budgeting, and saving tips', 'faqs-theme'),
                ),
                'investing' => array(
                    'name' => __('Investing & Trading', 'faqs-theme'),
                    'slug' => 'investing',
                    'description' => __('Investment strategies, stocks, and financial markets', 'faqs-theme'),
                ),
                'marketing' => array(
                    'name' => __('Marketing & Sales', 'faqs-theme'),
                    'slug' => 'marketing',
                    'description' => __('Marketing strategies, digital marketing, and sales techniques', 'faqs-theme'),
                ),
            ),
        ),
        'social' => array(
            'name' => __('Society & Culture', 'faqs-theme'),
            'slug' => 'social',
            'description' => __('Social issues, cultural topics, and human relationships', 'faqs-theme'),
            'icon' => '🌍',
            'color' => '#ec4899', // Pink
            'order' => 7,
            'children' => array(
                'relationships' => array(
                    'name' => __('Relationships & Family', 'faqs-theme'),
                    'slug' => 'relationships',
                    'description' => __('Relationships, family life, and social connections', 'faqs-theme'),
                ),
                'history' => array(
                    'name' => __('History & Geography', 'faqs-theme'),
                    'slug' => 'history',
                    'description' => __('Historical events, geography, and world cultures', 'faqs-theme'),
                ),
                'politics' => array(
                    'name' => __('Politics & Law', 'faqs-theme'),
                    'slug' => 'politics',
                    'description' => __('Political systems, laws, and governance', 'faqs-theme'),
                ),
                'religion' => array(
                    'name' => __('Religion & Philosophy', 'faqs-theme'),
                    'slug' => 'religion',
                    'description' => __('Religious beliefs, philosophy, and ethics', 'faqs-theme'),
                ),
            ),
        ),
        'how-to' => array(
            'name' => __('How-To & Guides', 'faqs-theme'),
            'slug' => 'how-to',
            'description' => __('Step-by-step guides, tutorials, and practical how-to articles', 'faqs-theme'),
            'icon' => '📖',
            'color' => '#14b8a6', // Teal
            'order' => 8,
            'children' => array(
                'diy' => array(
                    'name' => __('DIY & Crafts', 'faqs-theme'),
                    'slug' => 'diy',
                    'description' => __('Do-it-yourself projects, crafts, and handmade items', 'faqs-theme'),
                ),
                'automotive' => array(
                    'name' => __('Automotive & Vehicles', 'faqs-theme'),
                    'slug' => 'automotive',
                    'description' => __('Car maintenance, repairs, and vehicle care', 'faqs-theme'),
                ),
                'pets' => array(
                    'name' => __('Pets & Animals', 'faqs-theme'),
                    'slug' => 'pets',
                    'description' => __('Pet care, animal behavior, and veterinary advice', 'faqs-theme'),
                ),
                'fashion' => array(
                    'name' => __('Fashion & Style', 'faqs-theme'),
                    'slug' => 'fashion',
                    'description' => __('Fashion trends, style tips, and wardrobe advice', 'faqs-theme'),
                ),
            ),
        ),
    );
}

/**
 * Import categories automatically
 */
function faqs_theme_import_categories() {
    $categories = faqs_theme_get_category_structure();
    $imported_count = 0;
    $skipped_count = 0;

    foreach ($categories as $category_data) {
        // Check if parent category exists
        $parent_exists = term_exists($category_data['slug'], 'category');

        if (!$parent_exists) {
            // Insert parent category
            $parent_id = wp_insert_term(
                $category_data['name'],
                'category',
                array(
                    'slug' => $category_data['slug'],
                    'description' => $category_data['description'],
                )
            );

            if (!is_wp_error($parent_id)) {
                $parent_term_id = $parent_id['term_id'];

                // Save custom metadata (icon, color, order)
                update_term_meta($parent_term_id, 'category_icon', $category_data['icon']);
                update_term_meta($parent_term_id, 'category_color', $category_data['color']);
                update_term_meta($parent_term_id, 'category_order', $category_data['order']);

                $imported_count++;

                // Insert child categories
                if (isset($category_data['children']) && !empty($category_data['children'])) {
                    foreach ($category_data['children'] as $child_data) {
                        $child_exists = term_exists($child_data['slug'], 'category');

                        if (!$child_exists) {
                            $child_id = wp_insert_term(
                                $child_data['name'],
                                'category',
                                array(
                                    'slug' => $child_data['slug'],
                                    'description' => $child_data['description'],
                                    'parent' => $parent_term_id,
                                )
                            );

                            if (!is_wp_error($child_id)) {
                                $imported_count++;
                            }
                        } else {
                            $skipped_count++;
                        }
                    }
                }
            }
        } else {
            $skipped_count++;
        }
    }

    return array(
        'imported' => $imported_count,
        'skipped' => $skipped_count,
    );
}

/**
 * Add admin menu for category import
 */
function faqs_theme_category_import_menu() {
    add_management_page(
        __('Import Categories', 'faqs-theme'),
        __('Import Categories', 'faqs-theme'),
        'manage_categories',
        'faqs-import-categories',
        'faqs_theme_category_import_page'
    );
}
add_action('admin_menu', 'faqs_theme_category_import_menu');

/**
 * Category import admin page
 */
function faqs_theme_category_import_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('Import Categories for General Knowledge / Wiki', 'faqs-theme'); ?></h1>

        <?php
        if (isset($_POST['faqs_import_categories']) && check_admin_referer('faqs_import_categories_action', 'faqs_import_categories_nonce')) {
            $result = faqs_theme_import_categories();
            ?>
            <div class="notice notice-success is-dismissible">
                <p>
                    <strong><?php _e('Import Complete!', 'faqs-theme'); ?></strong><br>
                    <?php printf(__('Imported: %d categories', 'faqs-theme'), $result['imported']); ?><br>
                    <?php printf(__('Skipped (already exists): %d categories', 'faqs-theme'), $result['skipped']); ?>
                </p>
            </div>
            <?php
        }
        ?>

        <div class="card" style="max-width: 800px;">
            <h2><?php _e('Category Structure Preview', 'faqs-theme'); ?></h2>
            <p><?php _e('This will import the following category structure optimized for General Knowledge / Wiki / Blog:', 'faqs-theme'); ?></p>

            <div style="background: #f0f0f1; padding: 20px; border-radius: 4px; margin: 20px 0;">
                <?php
                $categories = faqs_theme_get_category_structure();
                foreach ($categories as $cat_data) {
                    echo '<div style="margin-bottom: 20px;">';
                    echo '<div style="font-size: 18px; font-weight: 600; margin-bottom: 8px;">';
                    echo '<span style="margin-right: 8px;">' . esc_html($cat_data['icon']) . '</span>';
                    echo esc_html($cat_data['name']);
                    echo ' <span style="display: inline-block; width: 12px; height: 12px; background: ' . esc_attr($cat_data['color']) . '; border-radius: 50%; margin-left: 8px;"></span>';
                    echo '</div>';
                    echo '<div style="color: #666; font-size: 14px; margin-bottom: 8px;">' . esc_html($cat_data['description']) . '</div>';

                    if (isset($cat_data['children'])) {
                        echo '<div style="margin-left: 30px; margin-top: 8px;">';
                        foreach ($cat_data['children'] as $child) {
                            echo '<div style="font-size: 14px; color: #555; margin-bottom: 4px;">↳ ' . esc_html($child['name']) . '</div>';
                        }
                        echo '</div>';
                    }
                    echo '</div>';
                }
                ?>
            </div>

            <form method="post" action="">
                <?php wp_nonce_field('faqs_import_categories_action', 'faqs_import_categories_nonce'); ?>
                <p>
                    <button type="submit" name="faqs_import_categories" class="button button-primary button-hero">
                        <?php _e('Import Categories Now', 'faqs-theme'); ?>
                    </button>
                </p>
                <p class="description">
                    <?php _e('Note: Existing categories will not be duplicated. Only new categories will be imported.', 'faqs-theme'); ?>
                </p>
            </form>
        </div>

        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2><?php _e('Features Included', 'faqs-theme'); ?></h2>
            <ul style="list-style: disc; margin-left: 20px;">
                <li><?php _e('8 main categories with 4 subcategories each (32 total categories)', 'faqs-theme'); ?></li>
                <li><?php _e('SEO-optimized category descriptions', 'faqs-theme'); ?></li>
                <li><?php _e('Custom icons for visual identification', 'faqs-theme'); ?></li>
                <li><?php _e('Color coding for better organization', 'faqs-theme'); ?></li>
                <li><?php _e('Proper URL-friendly slugs', 'faqs-theme'); ?></li>
                <li><?php _e('Hierarchical structure (parent → child)', 'faqs-theme'); ?></li>
            </ul>
        </div>
    </div>
    <?php
}

/**
 * Get category with metadata
 */
function faqs_theme_get_category_meta($term_id) {
    return array(
        'icon' => get_term_meta($term_id, 'category_icon', true),
        'color' => get_term_meta($term_id, 'category_color', true),
        'order' => get_term_meta($term_id, 'category_order', true),
    );
}
