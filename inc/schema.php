<?php
/**
 * Schema.org Markup (7 types)
 *
 * @package FAQs_Theme
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Output Organization Schema
 */
function faqs_theme_organization_schema() {
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => get_bloginfo('name'),
        'url' => home_url('/'),
        'description' => get_bloginfo('description'),
        'logo' => array(
            '@type' => 'ImageObject',
            'url' => get_theme_mod('custom_logo') ? wp_get_attachment_url(get_theme_mod('custom_logo')) : '',
        ),
    );

    return $schema;
}

/**
 * Output WebSite Schema
 */
function faqs_theme_website_schema() {
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => get_bloginfo('name'),
        'url' => home_url('/'),
        'description' => get_bloginfo('description'),
        'potentialAction' => array(
            '@type' => 'SearchAction',
            'target' => array(
                '@type' => 'EntryPoint',
                'urlTemplate' => home_url('/?s={search_term_string}'),
            ),
            'query-input' => 'required name=search_term_string',
        ),
    );

    return $schema;
}

/**
 * Output Article Schema for single posts
 */
function faqs_theme_article_schema($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    if (!is_singular('post')) {
        return array();
    }

    $post = get_post($post_id);
    $author = get_userdata($post->post_author);

    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => get_the_title($post_id),
        'description' => get_the_excerpt($post_id),
        'articleBody' => wp_strip_all_tags($post->post_content),
        'datePublished' => get_the_date('c', $post_id),
        'dateModified' => get_the_modified_date('c', $post_id),
        'author' => array(
            '@type' => 'Person',
            'name' => $author->display_name,
            'url' => get_author_posts_url($author->ID),
        ),
        'publisher' => faqs_theme_organization_schema(),
        'mainEntityOfPage' => array(
            '@type' => 'WebPage',
            '@id' => get_permalink($post_id),
        ),
    );

    // Add rating aggregate if available
    $rating = get_post_meta($post_id, '_faqs_rating', true);
    $rating_count = get_post_meta($post_id, '_faqs_rating_count', true);

    if ($rating && $rating_count) {
        $schema['aggregateRating'] = array(
            '@type' => 'AggregateRating',
            'ratingValue' => floatval($rating),
            'ratingCount' => intval($rating_count),
            'bestRating' => 5,
            'worstRating' => 1,
        );
    }

    return $schema;
}

/**
 * Output FAQPage Schema
 */
function faqs_theme_faq_schema($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    if (!is_singular('post')) {
        return array();
    }

    $post = get_post($post_id);
    $content = $post->post_content;

    // Extract Q&A from content (if formatted with headings)
    $questions = array();

    // Try to find questions in content
    // This is a simple parser - you might want to customize based on your content structure
    preg_match_all('/<h([23])>(.*?)<\/h\1>(.*?)(?=<h[23]>|$)/is', $content, $matches, PREG_SET_ORDER);

    if (!empty($matches)) {
        foreach ($matches as $match) {
            $questions[] = array(
                '@type' => 'Question',
                'name' => wp_strip_all_tags($match[2]),
                'acceptedAnswer' => array(
                    '@type' => 'Answer',
                    'text' => wp_trim_words(wp_strip_all_tags($match[3]), 100),
                ),
            );
        }
    }

    // If no structured Q&A found, create a single entry
    if (empty($questions)) {
        $questions[] = array(
            '@type' => 'Question',
            'name' => get_the_title($post_id),
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text' => wp_trim_words(wp_strip_all_tags($content), 200),
            ),
        );
    }

    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => $questions,
    );

    return $schema;
}

/**
 * Output BreadcrumbList Schema
 */
function faqs_theme_breadcrumb_schema($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $items = array(
        array(
            '@type' => 'ListItem',
            'position' => 1,
            'name' => __('Home', 'faqs-theme'),
            'item' => home_url('/'),
        ),
    );

    $position = 2;

    // Add category
    if (is_singular('post')) {
        $categories = get_the_category($post_id);
        if (!empty($categories)) {
            $items[] = array(
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => $categories[0]->name,
                'item' => get_category_link($categories[0]->term_id),
            );
        }

        // Add current post
        $items[] = array(
            '@type' => 'ListItem',
            'position' => $position,
            'name' => get_the_title($post_id),
            'item' => get_permalink($post_id),
        );
    } elseif (is_category()) {
        $category = get_queried_object();
        $items[] = array(
            '@type' => 'ListItem',
            'position' => $position,
            'name' => $category->name,
            'item' => get_category_link($category->term_id),
        );
    } elseif (is_tag()) {
        $tag = get_queried_object();
        $items[] = array(
            '@type' => 'ListItem',
            'position' => $position,
            'name' => $tag->name,
            'item' => get_tag_link($tag->term_id),
        );
    } elseif (is_search()) {
        $items[] = array(
            '@type' => 'ListItem',
            'position' => $position,
            'name' => sprintf(__('Search Results for: %s', 'faqs-theme'), get_search_query()),
        );
    }

    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => $items,
    );

    return $schema;
}

/**
 * Output Person Schema (for author pages)
 */
function faqs_theme_person_schema($author_id = null) {
    if (!$author_id && is_author()) {
        $author_id = get_queried_object_id();
    }

    if (!$author_id) {
        return array();
    }

    $author = get_userdata($author_id);

    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Person',
        'name' => $author->display_name,
        'url' => get_author_posts_url($author->ID),
        'description' => $author->description,
    );

    return $schema;
}

/**
 * Output CollectionPage Schema (for archive pages)
 */
function faqs_theme_collection_schema() {
    if (!is_archive() && !is_home()) {
        return array();
    }

    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'CollectionPage',
        'name' => wp_get_document_title(),
        'url' => get_permalink(),
        'description' => get_the_archive_description(),
    );

    return $schema;
}

/**
 * Output all schemas in JSON-LD format
 */
function faqs_theme_output_schemas() {
    $schemas = array();

    // Always include Organization and WebSite
    $schemas[] = faqs_theme_organization_schema();
    $schemas[] = faqs_theme_website_schema();

    // Add page-specific schemas
    if (is_singular('post')) {
        $schemas[] = faqs_theme_article_schema();
        $schemas[] = faqs_theme_faq_schema();
        $schemas[] = faqs_theme_breadcrumb_schema();
    } elseif (is_author()) {
        $schemas[] = faqs_theme_person_schema();
        $schemas[] = faqs_theme_breadcrumb_schema();
    } elseif (is_archive() || is_home()) {
        $schemas[] = faqs_theme_collection_schema();
        $schemas[] = faqs_theme_breadcrumb_schema();
    } elseif (is_search()) {
        $schemas[] = faqs_theme_breadcrumb_schema();
    }

    // Remove empty schemas
    $schemas = array_filter($schemas);

    if (empty($schemas)) {
        return;
    }

    // Output as JSON-LD
    echo '<script type="application/ld+json">' . "\n";
    echo wp_json_encode(array('@graph' => $schemas), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    echo "\n" . '</script>' . "\n";
}
add_action('wp_head', 'faqs_theme_output_schemas', 5);
