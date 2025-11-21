<?php
/**
 * XML Sitemap Generation with Images
 *
 * @package FAQs_Theme
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Generate XML sitemap
 */
function faqs_theme_generate_sitemap() {
    // Check if WordPress core sitemap is enabled (WP 5.5+)
    if (get_option('blog_public') == '0') {
        return;
    }

    // Use WordPress core sitemap features
    add_filter('wp_sitemaps_post_types', 'faqs_theme_sitemap_post_types');
    add_filter('wp_sitemaps_taxonomies', 'faqs_theme_sitemap_taxonomies');
    add_filter('wp_sitemaps_posts_entry', 'faqs_theme_sitemap_add_images', 10, 3);
}
add_action('init', 'faqs_theme_generate_sitemap');

/**
 * Configure post types in sitemap
 */
function faqs_theme_sitemap_post_types($post_types) {
    // Customize post types as needed
    return $post_types;
}

/**
 * Configure taxonomies in sitemap
 */
function faqs_theme_sitemap_taxonomies($taxonomies) {
    // Customize taxonomies as needed
    return $taxonomies;
}

/**
 * Add images to sitemap entries
 */
function faqs_theme_sitemap_add_images($entry, $post, $post_type) {
    // Get all images from the post
    $images = array();

    // Featured image
    if (has_post_thumbnail($post->ID)) {
        $thumbnail_id = get_post_thumbnail_id($post->ID);
        $image_url = wp_get_attachment_image_url($thumbnail_id, 'full');
        if ($image_url) {
            $images[] = array(
                'loc' => $image_url,
                'title' => get_the_title($thumbnail_id),
                'caption' => wp_get_attachment_caption($thumbnail_id),
            );
        }
    }

    // Images in content
    $content = $post->post_content;
    preg_match_all('/<img[^>]+src=[\'"]([^\'"]+)[\'"][^>]*>/i', $content, $matches);

    if (!empty($matches[1])) {
        foreach ($matches[1] as $image_url) {
            // Only include images from the same domain
            if (strpos($image_url, home_url()) === 0) {
                $images[] = array(
                    'loc' => $image_url,
                );
            }
        }
    }

    // Add images to entry
    if (!empty($images)) {
        $entry['images'] = $images;
    }

    return $entry;
}

/**
 * Custom sitemap index
 * This is a fallback for older WordPress versions
 */
function faqs_theme_custom_sitemap() {
    // Only for older WP versions without core sitemap
    if (function_exists('wp_sitemaps_get_server')) {
        return;
    }

    // Check if custom sitemap is requested
    if (isset($_GET['faqs_sitemap']) && $_GET['faqs_sitemap'] === 'index') {
        faqs_theme_output_sitemap_index();
        exit;
    } elseif (isset($_GET['faqs_sitemap']) && $_GET['faqs_sitemap'] === 'posts') {
        faqs_theme_output_sitemap_posts();
        exit;
    }
}
add_action('template_redirect', 'faqs_theme_custom_sitemap');

/**
 * Output sitemap index (fallback)
 */
function faqs_theme_output_sitemap_index() {
    header('Content-Type: application/xml; charset=utf-8');
    echo '<?xml version="1.0" encoding="UTF-8"?>';
    echo '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    echo '<sitemap>';
    echo '<loc>' . esc_url(home_url('/?faqs_sitemap=posts')) . '</loc>';
    echo '<lastmod>' . esc_xml(get_lastpostmodified('GMT')) . '</lastmod>';
    echo '</sitemap>';
    echo '</sitemapindex>';
}

/**
 * Output posts sitemap (fallback)
 */
function faqs_theme_output_sitemap_posts() {
    header('Content-Type: application/xml; charset=utf-8');

    $posts = get_posts(array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'modified',
        'order' => 'DESC',
    ));

    echo '<?xml version="1.0" encoding="UTF-8"?>';
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

    foreach ($posts as $post) {
        setup_postdata($post);

        echo '<url>';
        echo '<loc>' . esc_url(get_permalink($post)) . '</loc>';
        echo '<lastmod>' . esc_xml(get_the_modified_date('c', $post)) . '</lastmod>';
        echo '<changefreq>weekly</changefreq>';
        echo '<priority>0.8</priority>';

        // Add images
        if (has_post_thumbnail($post->ID)) {
            $thumbnail_id = get_post_thumbnail_id($post->ID);
            $image_url = wp_get_attachment_image_url($thumbnail_id, 'full');
            if ($image_url) {
                echo '<image:image>';
                echo '<image:loc>' . esc_url($image_url) . '</image:loc>';
                echo '<image:title>' . esc_xml(get_the_title($thumbnail_id)) . '</image:title>';
                echo '</image:image>';
            }
        }

        echo '</url>';
    }

    wp_reset_postdata();

    echo '</urlset>';
}

/**
 * Add sitemap to robots.txt
 */
function faqs_theme_robots_txt($output) {
    $sitemap_url = function_exists('wp_sitemaps_get_server')
        ? esc_url(home_url('/wp-sitemap.xml'))
        : esc_url(home_url('/?faqs_sitemap=index'));

    $output .= "\nSitemap: " . $sitemap_url . "\n";
    return $output;
}
add_filter('robots_txt', 'faqs_theme_robots_txt');
