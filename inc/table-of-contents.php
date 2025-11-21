<?php
/**
 * Table of Contents Auto-Generation
 *
 * @package FAQs_Theme
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Extract headings from content
 *
 * @param string $content Post content
 * @return array Array of headings
 */
function faqs_theme_extract_headings($content) {
    if (empty($content)) {
        return array();
    }

    $headings = array();
    $index = 0;

    // Match H2 and H3 headings
    preg_match_all('/<h([23])([^>]*)>(.*?)<\/h\1>/i', $content, $matches, PREG_SET_ORDER);

    foreach ($matches as $match) {
        $level = (int) $match[1];
        $attributes = $match[2];
        $text = wp_strip_all_tags($match[3]);

        // Generate ID if not exists
        $id = '';
        if (preg_match('/id=["\']([^"\']+)["\']/i', $attributes, $id_match)) {
            $id = $id_match[1];
        } else {
            $id = 'toc-' . sanitize_title($text) . '-' . $index;
        }

        $headings[] = array(
            'level' => $level,
            'text' => $text,
            'id' => $id,
            'index' => $index,
        );

        $index++;
    }

    return $headings;
}

/**
 * Add IDs to headings in content
 *
 * @param string $content Post content
 * @return string Modified content
 */
function faqs_theme_add_heading_ids($content) {
    if (empty($content) || !is_singular('post')) {
        return $content;
    }

    $index = 0;

    $content = preg_replace_callback(
        '/<h([23])([^>]*)>(.*?)<\/h\1>/i',
        function ($matches) use (&$index) {
            $level = $matches[1];
            $attributes = $matches[2];
            $text = $matches[3];

            // Check if ID already exists
            if (preg_match('/id=["\']([^"\']+)["\']/i', $attributes)) {
                return $matches[0];
            }

            // Generate ID
            $id = 'toc-' . sanitize_title(wp_strip_all_tags($text)) . '-' . $index;
            $index++;

            return '<h' . $level . $attributes . ' id="' . esc_attr($id) . '">' . $text . '</h' . $level . '>';
        },
        $content
    );

    return $content;
}
add_filter('the_content', 'faqs_theme_add_heading_ids', 10);

/**
 * Generate Table of Contents HTML
 *
 * @param array $headings Array of headings
 * @return string TOC HTML
 */
function faqs_theme_generate_toc($headings) {
    if (empty($headings) || count($headings) < 3) {
        return '';
    }

    $toc = '<nav class="table-of-contents bg-blue-50 dark:bg-blue-900 rounded-lg p-6 mb-8" aria-label="' . esc_attr__('Table of Contents', 'faqs-theme') . '">';
    $toc .= '<h2 class="text-xl font-bold mb-4 flex items-center gap-2">';
    $toc .= '<svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>';
    $toc .= esc_html__('Table of Contents', 'faqs-theme');
    $toc .= '</h2>';
    $toc .= '<ol class="toc-list space-y-2">';

    $prev_level = 2;

    foreach ($headings as $heading) {
        $level = $heading['level'];
        $text = $heading['text'];
        $id = $heading['id'];

        // Handle nesting
        if ($level > $prev_level) {
            $toc .= '<ol class="ml-4 mt-2 space-y-2">';
        } elseif ($level < $prev_level) {
            $toc .= '</ol>';
        }

        $toc .= '<li class="toc-item">';
        $toc .= '<a href="#' . esc_attr($id) . '" class="toc-link text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors flex items-center gap-2">';
        $toc .= '<svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>';
        $toc .= '<span>' . esc_html($text) . '</span>';
        $toc .= '</a>';
        $toc .= '</li>';

        $prev_level = $level;
    }

    // Close any open nested lists
    while ($prev_level > 2) {
        $toc .= '</ol>';
        $prev_level--;
    }

    $toc .= '</ol>';
    $toc .= '</nav>';

    return $toc;
}

/**
 * Display Table of Contents
 *
 * @param string $content Post content
 * @return string Modified content with TOC
 */
function faqs_theme_display_toc($content) {
    if (!is_singular('post') || !in_the_loop() || !is_main_query()) {
        return $content;
    }

    // Check if TOC is disabled for this post
    if (get_post_meta(get_the_ID(), '_faqs_disable_toc', true)) {
        return $content;
    }

    $headings = faqs_theme_extract_headings($content);

    if (empty($headings) || count($headings) < 3) {
        return $content;
    }

    $toc = faqs_theme_generate_toc($headings);

    // Insert TOC after first paragraph
    $paragraphs = explode('</p>', $content);
    if (count($paragraphs) > 1) {
        $paragraphs[0] .= '</p>' . $toc;
        $content = implode('</p>', $paragraphs);
    } else {
        $content = $toc . $content;
    }

    return $content;
}
add_filter('the_content', 'faqs_theme_display_toc', 20);

/**
 * Add smooth scroll behavior for TOC links
 */
function faqs_theme_toc_scripts() {
    if (!is_singular('post')) {
        return;
    }
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Smooth scroll for TOC links
        const tocLinks = document.querySelectorAll('.toc-link');

        tocLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();

                const targetId = this.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);

                if (targetElement) {
                    const offset = 100; // Offset for fixed header
                    const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - offset;

                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });

                    // Update URL without scrolling
                    history.pushState(null, null, '#' + targetId);

                    // Highlight the target heading
                    targetElement.classList.add('highlight-heading');
                    setTimeout(() => {
                        targetElement.classList.remove('highlight-heading');
                    }, 2000);
                }
            });
        });

        // Highlight current section in TOC on scroll
        let currentSection = '';

        function highlightTocLink() {
            const scrollPosition = window.pageYOffset + 150;

            const headings = document.querySelectorAll('h2[id^="toc-"], h3[id^="toc-"]');
            headings.forEach(heading => {
                const headingTop = heading.offsetTop;
                const headingBottom = headingTop + heading.offsetHeight;

                if (scrollPosition >= headingTop && scrollPosition < headingBottom) {
                    const id = heading.getAttribute('id');
                    if (currentSection !== id) {
                        currentSection = id;

                        // Remove active class from all TOC links
                        tocLinks.forEach(link => {
                            link.classList.remove('font-bold', 'text-blue-600', 'dark:text-blue-400');
                        });

                        // Add active class to current TOC link
                        const activeTocLink = document.querySelector('.toc-link[href="#' + id + '"]');
                        if (activeTocLink) {
                            activeTocLink.classList.add('font-bold', 'text-blue-600', 'dark:text-blue-400');
                        }
                    }
                }
            });
        }

        window.addEventListener('scroll', highlightTocLink);
        highlightTocLink(); // Initial check
    });
    </script>

    <style>
    .highlight-heading {
        background-color: rgba(59, 130, 246, 0.1);
        padding: 0.5rem;
        margin: -0.5rem;
        border-radius: 0.5rem;
        transition: background-color 0.3s ease;
    }
    </style>
    <?php
}
add_action('wp_footer', 'faqs_theme_toc_scripts');
