<?php
/**
 * Enterprise-Level SEO Features
 * - Open Graph tags
 * - Twitter Cards
 * - Canonical URLs
 * - Meta Robots
 *
 * @package FAQs_Theme
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Output Open Graph meta tags
 */
function faqs_theme_open_graph_tags() {
    // Don't output if Yoast or RankMath is active
    if (defined('WPSEO_VERSION') || class_exists('RankMath')) {
        return;
    }

    $og_tags = array();

    // Common tags
    $og_tags['og:site_name'] = get_bloginfo('name');
    $og_tags['og:locale'] = get_locale();

    if (is_singular()) {
        global $post;

        $og_tags['og:type'] = 'article';
        $og_tags['og:title'] = get_the_title();
        $og_tags['og:description'] = get_the_excerpt() ?: wp_trim_words(strip_tags($post->post_content), 30);
        $og_tags['og:url'] = get_permalink();

        // Featured image
        if (has_post_thumbnail()) {
            $image_id = get_post_thumbnail_id();
            $image = wp_get_attachment_image_src($image_id, 'full');
            if ($image) {
                $og_tags['og:image'] = $image[0];
                $og_tags['og:image:width'] = $image[1];
                $og_tags['og:image:height'] = $image[2];
                $og_tags['og:image:alt'] = get_post_meta($image_id, '_wp_attachment_image_alt', true);
            }
        }

        // Article specific
        $og_tags['article:published_time'] = get_the_date('c');
        $og_tags['article:modified_time'] = get_the_modified_date('c');
        $og_tags['article:author'] = get_author_posts_url(get_the_author_meta('ID'));

        // Categories
        $categories = get_the_category();
        if (!empty($categories)) {
            $og_tags['article:section'] = $categories[0]->name;
        }

        // Tags
        $tags = get_the_tags();
        if ($tags) {
            foreach ($tags as $tag) {
                $og_tags['article:tag'][] = $tag->name;
            }
        }
    } elseif (is_front_page() || is_home()) {
        $og_tags['og:type'] = 'website';
        $og_tags['og:title'] = get_bloginfo('name');
        $og_tags['og:description'] = get_bloginfo('description');
        $og_tags['og:url'] = home_url('/');

        // Site logo
        if (has_custom_logo()) {
            $logo_id = get_theme_mod('custom_logo');
            $logo = wp_get_attachment_image_src($logo_id, 'full');
            if ($logo) {
                $og_tags['og:image'] = $logo[0];
            }
        }
    } elseif (is_category() || is_tag() || is_tax()) {
        $term = get_queried_object();
        $og_tags['og:type'] = 'website';
        $og_tags['og:title'] = $term->name;
        $og_tags['og:description'] = $term->description ?: sprintf(__('Browse %s in %s', 'faqs-theme'), $term->name, get_bloginfo('name'));
        $og_tags['og:url'] = get_term_link($term);
    } elseif (is_author()) {
        $author = get_queried_object();
        $og_tags['og:type'] = 'profile';
        $og_tags['og:title'] = $author->display_name;
        $og_tags['og:description'] = $author->description ?: sprintf(__('Articles by %s', 'faqs-theme'), $author->display_name);
        $og_tags['og:url'] = get_author_posts_url($author->ID);
        $og_tags['profile:username'] = $author->user_login;
    }

    // Output tags
    foreach ($og_tags as $property => $content) {
        if (is_array($content)) {
            foreach ($content as $item) {
                echo '<meta property="' . esc_attr($property) . '" content="' . esc_attr($item) . '">' . "\n";
            }
        } else {
            echo '<meta property="' . esc_attr($property) . '" content="' . esc_attr($content) . '">' . "\n";
        }
    }
}
add_action('wp_head', 'faqs_theme_open_graph_tags', 5);

/**
 * Output Twitter Card meta tags
 */
function faqs_theme_twitter_card_tags() {
    // Don't output if Yoast or RankMath is active
    if (defined('WPSEO_VERSION') || class_exists('RankMath')) {
        return;
    }

    $twitter_tags = array();

    // Card type
    if (is_singular() && has_post_thumbnail()) {
        $twitter_tags['twitter:card'] = 'summary_large_image';
    } else {
        $twitter_tags['twitter:card'] = 'summary';
    }

    // Twitter username (can be customized via Customizer)
    $twitter_username = get_theme_mod('twitter_username');
    if ($twitter_username) {
        $twitter_tags['twitter:site'] = '@' . ltrim($twitter_username, '@');
    }

    if (is_singular()) {
        global $post;

        $twitter_tags['twitter:title'] = get_the_title();
        $twitter_tags['twitter:description'] = get_the_excerpt() ?: wp_trim_words(strip_tags($post->post_content), 30);

        // Featured image
        if (has_post_thumbnail()) {
            $image_id = get_post_thumbnail_id();
            $image = wp_get_attachment_image_src($image_id, 'full');
            if ($image) {
                $twitter_tags['twitter:image'] = $image[0];
                $twitter_tags['twitter:image:alt'] = get_post_meta($image_id, '_wp_attachment_image_alt', true);
            }
        }

        // Author Twitter
        $author_twitter = get_the_author_meta('twitter');
        if ($author_twitter) {
            $twitter_tags['twitter:creator'] = '@' . ltrim($author_twitter, '@');
        }
    } elseif (is_front_page() || is_home()) {
        $twitter_tags['twitter:title'] = get_bloginfo('name');
        $twitter_tags['twitter:description'] = get_bloginfo('description');

        if (has_custom_logo()) {
            $logo_id = get_theme_mod('custom_logo');
            $logo = wp_get_attachment_image_src($logo_id, 'full');
            if ($logo) {
                $twitter_tags['twitter:image'] = $logo[0];
            }
        }
    }

    // Output tags
    foreach ($twitter_tags as $name => $content) {
        echo '<meta name="' . esc_attr($name) . '" content="' . esc_attr($content) . '">' . "\n";
    }
}
add_action('wp_head', 'faqs_theme_twitter_card_tags', 5);

/**
 * Output canonical URL
 */
function faqs_theme_canonical_url() {
    // Don't output if Yoast or RankMath is active
    if (defined('WPSEO_VERSION') || class_exists('RankMath')) {
        return;
    }

    $canonical = '';

    if (is_singular()) {
        $canonical = get_permalink();
    } elseif (is_front_page()) {
        $canonical = home_url('/');
    } elseif (is_category() || is_tag() || is_tax()) {
        $canonical = get_term_link(get_queried_object());
    } elseif (is_author()) {
        $canonical = get_author_posts_url(get_queried_object_id());
    } elseif (is_search()) {
        $canonical = get_search_link();
    } elseif (is_post_type_archive()) {
        $canonical = get_post_type_archive_link(get_query_var('post_type'));
    }

    if ($canonical) {
        // Remove pagination from canonical
        $canonical = preg_replace('/\/page\/[0-9]+\/?/', '/', $canonical);
        echo '<link rel="canonical" href="' . esc_url($canonical) . '">' . "\n";
    }
}
add_action('wp_head', 'faqs_theme_canonical_url', 5);

/**
 * Output meta robots tag
 */
function faqs_theme_meta_robots() {
    // Don't output if Yoast or RankMath is active
    if (defined('WPSEO_VERSION') || class_exists('RankMath')) {
        return;
    }

    $robots = array();

    // Index/NoIndex
    if (is_singular()) {
        if (get_post_meta(get_the_ID(), '_noindex', true)) {
            $robots[] = 'noindex';
        } else {
            $robots[] = 'index';
        }
    } elseif (is_search() || is_404()) {
        $robots[] = 'noindex';
    } else {
        $robots[] = 'index';
    }

    // Follow/NoFollow
    $robots[] = 'follow';

    // Additional directives
    if (is_singular()) {
        $robots[] = 'max-snippet:-1';
        $robots[] = 'max-image-preview:large';
        $robots[] = 'max-video-preview:-1';
    }

    if (!empty($robots)) {
        echo '<meta name="robots" content="' . esc_attr(implode(', ', $robots)) . '">' . "\n";
    }
}
add_action('wp_head', 'faqs_theme_meta_robots', 5);

/**
 * Add meta description
 */
function faqs_theme_meta_description() {
    // Don't output if Yoast or RankMath is active
    if (defined('WPSEO_VERSION') || class_exists('RankMath')) {
        return;
    }

    $description = '';

    if (is_singular()) {
        global $post;
        $description = get_the_excerpt() ?: wp_trim_words(strip_tags($post->post_content), 30);
    } elseif (is_front_page() || is_home()) {
        $description = get_bloginfo('description');
    } elseif (is_category() || is_tag() || is_tax()) {
        $term = get_queried_object();
        $description = $term->description ?: sprintf(__('Browse %s in %s', 'faqs-theme'), $term->name, get_bloginfo('name'));
    } elseif (is_author()) {
        $author = get_queried_object();
        $description = $author->description ?: sprintf(__('Articles by %s', 'faqs-theme'), $author->display_name);
    }

    if ($description) {
        echo '<meta name="description" content="' . esc_attr(wp_strip_all_tags($description)) . '">' . "\n";
    }
}
add_action('wp_head', 'faqs_theme_meta_description', 5);

/**
 * Add prev/next links for pagination
 */
function faqs_theme_pagination_links() {
    global $wp_query;

    if (is_singular()) {
        return;
    }

    $current_page = max(1, get_query_var('paged'));
    $max_pages = $wp_query->max_num_pages;

    if ($current_page > 1) {
        $prev_link = get_pagenum_link($current_page - 1);
        echo '<link rel="prev" href="' . esc_url($prev_link) . '">' . "\n";
    }

    if ($current_page < $max_pages) {
        $next_link = get_pagenum_link($current_page + 1);
        echo '<link rel="next" href="' . esc_url($next_link) . '">' . "\n";
    }
}
add_action('wp_head', 'faqs_theme_pagination_links', 5);
