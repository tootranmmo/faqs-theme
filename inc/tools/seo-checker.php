<?php
/**
 * SEO Checker Tool
 * Analyze and improve post SEO scores
 *
 * @package FAQs_Theme
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class FAQs_SEO_Checker {

    public function __construct() {
        add_action('admin_menu', array($this, 'add_menu'));
        add_action('add_meta_boxes', array($this, 'add_seo_meta_box'));
        add_action('wp_ajax_faqs_analyze_seo', array($this, 'analyze_seo'));
    }

    public function add_menu() {
        add_submenu_page(
            'faqs-tools',
            __('SEO Checker', 'faqs-theme'),
            __('SEO Checker', 'faqs-theme'),
            'edit_posts',
            'faqs-tool-seo-checker',
            array($this, 'render_page')
        );
    }

    public function add_seo_meta_box() {
        add_meta_box(
            'faqs_seo_checker',
            __('🔍 SEO Analysis', 'faqs-theme'),
            array($this, 'render_meta_box'),
            'post',
            'side',
            'high'
        );
    }

    public function render_meta_box($post) {
        $score = $this->calculate_seo_score($post->ID);
        ?>
        <div class="faqs-seo-meta-box">
            <div style="text-align: center; margin-bottom: 15px;">
                <div style="font-size: 48px; font-weight: bold; color: <?php echo $this->get_score_color($score); ?>;">
                    <?php echo $score; ?>/100
                </div>
                <div style="font-size: 12px; color: #666; margin-top: 5px;">
                    <?php echo $this->get_score_label($score); ?>
                </div>
            </div>
            <a href="<?php echo admin_url('admin.php?page=faqs-tool-seo-checker&post_id=' . $post->ID); ?>" class="button button-primary button-large" style="width: 100%;">
                <?php _e('View Detailed Analysis', 'faqs-theme'); ?>
            </a>
        </div>
        <?php
    }

    public function render_page() {
        $post_id = isset($_GET['post_id']) ? absint($_GET['post_id']) : 0;

        if ($post_id > 0) {
            $this->render_single_analysis($post_id);
        } else {
            $this->render_bulk_analysis();
        }
    }

    private function render_single_analysis($post_id) {
        $post = get_post($post_id);
        if (!$post) {
            echo '<div class="notice notice-error"><p>' . __('Post not found', 'faqs-theme') . '</p></div>';
            return;
        }

        $analysis = $this->analyze_post($post);
        ?>
        <div class="wrap faqs-tool-page">
            <div class="faqs-tool-header">
                <h1>🔍 <?php _e('SEO Analysis', 'faqs-theme'); ?>: <?php echo esc_html($post->post_title); ?></h1>
                <p class="description"><?php _e('Comprehensive SEO analysis and recommendations', 'faqs-theme'); ?></p>
            </div>

            <div class="faqs-tool-content">
                <!-- Overall Score -->
                <div style="text-align: center; padding: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px; margin-bottom: 30px;">
                    <div style="font-size: 72px; font-weight: bold; margin-bottom: 10px;">
                        <?php echo $analysis['score']; ?>/100
                    </div>
                    <div style="font-size: 24px; opacity: 0.9;">
                        <?php echo $this->get_score_label($analysis['score']); ?>
                    </div>
                </div>

                <!-- Checks -->
                <h2><?php _e('SEO Checklist', 'faqs-theme'); ?></h2>
                <table class="faqs-table">
                    <?php foreach ($analysis['checks'] as $check) : ?>
                        <tr>
                            <td style="width: 50px; text-align: center;">
                                <?php if ($check['passed']) : ?>
                                    <span style="color: #46b450; font-size: 24px;">✓</span>
                                <?php else : ?>
                                    <span style="color: #dc3232; font-size: 24px;">✗</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo esc_html($check['label']); ?></strong><br>
                                <span class="description"><?php echo esc_html($check['message']); ?></span>
                            </td>
                            <td style="width: 80px; text-align: center;">
                                <?php if ($check['passed']) : ?>
                                    <span class="faqs-badge success"><?php _e('Passed', 'faqs-theme'); ?></span>
                                <?php else : ?>
                                    <span class="faqs-badge warning"><?php _e('Fix', 'faqs-theme'); ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>

                <!-- Recommendations -->
                <?php if (!empty($analysis['recommendations'])) : ?>
                    <h2><?php _e('Recommendations', 'faqs-theme'); ?></h2>
                    <div class="faqs-alert info">
                        <ul style="margin: 10px 0; padding-left: 20px;">
                            <?php foreach ($analysis['recommendations'] as $recommendation) : ?>
                                <li><?php echo esc_html($recommendation); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="faqs-tool-actions">
                    <a href="<?php echo get_edit_post_link($post_id); ?>" class="button button-primary button-large">
                        <?php _e('Edit Post', 'faqs-theme'); ?>
                    </a>
                    <a href="<?php echo admin_url('admin.php?page=faqs-tool-seo-checker'); ?>" class="button button-large">
                        <?php _e('Back to All Posts', 'faqs-theme'); ?>
                    </a>
                </div>
            </div>
        </div>
        <?php
    }

    private function render_bulk_analysis() {
        $posts = get_posts(array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 50,
            'orderby' => 'date',
            'order' => 'DESC',
        ));
        ?>
        <div class="wrap faqs-tool-page">
            <div class="faqs-tool-header">
                <h1>🔍 <?php _e('SEO Checker', 'faqs-theme'); ?></h1>
                <p class="description"><?php _e('Analyze SEO scores of all posts', 'faqs-theme'); ?></p>
            </div>

            <div class="faqs-tool-content">
                <table class="faqs-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th style="width: 80px;"><?php _e('Score', 'faqs-theme'); ?></th>
                            <th><?php _e('Post Title', 'faqs-theme'); ?></th>
                            <th style="width: 150px;"><?php _e('Status', 'faqs-theme'); ?></th>
                            <th style="width: 200px;"><?php _e('Actions', 'faqs-theme'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($posts as $post) :
                            $score = $this->calculate_seo_score($post->ID);
                            $status_class = $score >= 80 ? 'success' : ($score >= 60 ? 'warning' : 'error');
                        ?>
                            <tr>
                                <td style="text-align: center;">
                                    <strong style="font-size: 18px; color: <?php echo $this->get_score_color($score); ?>;">
                                        <?php echo $score; ?>
                                    </strong>
                                </td>
                                <td><strong><?php echo esc_html($post->post_title); ?></strong></td>
                                <td>
                                    <span class="faqs-badge <?php echo $status_class; ?>">
                                        <?php echo $this->get_score_label($score); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo admin_url('admin.php?page=faqs-tool-seo-checker&post_id=' . $post->ID); ?>" class="button button-small">
                                        <?php _e('Analyze', 'faqs-theme'); ?>
                                    </a>
                                    <a href="<?php echo get_edit_post_link($post->ID); ?>" class="button button-small">
                                        <?php _e('Edit', 'faqs-theme'); ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }

    private function analyze_post($post) {
        $content = $post->post_content;
        $title = $post->post_title;
        $excerpt = $post->post_excerpt;

        $checks = array();
        $recommendations = array();
        $score = 0;

        // Title length check (10 points)
        $title_length = mb_strlen($title);
        if ($title_length >= 30 && $title_length <= 60) {
            $checks[] = array(
                'label' => __('Title Length', 'faqs-theme'),
                'passed' => true,
                'message' => sprintf(__('Perfect! Title is %d characters', 'faqs-theme'), $title_length),
            );
            $score += 10;
        } else {
            $checks[] = array(
                'label' => __('Title Length', 'faqs-theme'),
                'passed' => false,
                'message' => sprintf(__('Title should be 30-60 characters. Current: %d', 'faqs-theme'), $title_length),
            );
            $recommendations[] = __('Optimize title length to 30-60 characters', 'faqs-theme');
        }

        // Content length check (15 points)
        $word_count = str_word_count(strip_tags($content));
        if ($word_count >= 300) {
            $checks[] = array(
                'label' => __('Content Length', 'faqs-theme'),
                'passed' => true,
                'message' => sprintf(__('Great! %d words', 'faqs-theme'), $word_count),
            );
            $score += 15;
        } else {
            $checks[] = array(
                'label' => __('Content Length', 'faqs-theme'),
                'passed' => false,
                'message' => sprintf(__('Content should be at least 300 words. Current: %d', 'faqs-theme'), $word_count),
            );
            $recommendations[] = __('Add more content (minimum 300 words recommended)', 'faqs-theme');
        }

        // Meta description check (10 points)
        if (!empty($excerpt)) {
            $excerpt_length = mb_strlen($excerpt);
            if ($excerpt_length >= 120 && $excerpt_length <= 160) {
                $checks[] = array(
                    'label' => __('Meta Description', 'faqs-theme'),
                    'passed' => true,
                    'message' => __('Perfect meta description length', 'faqs-theme'),
                );
                $score += 10;
            } else {
                $checks[] = array(
                    'label' => __('Meta Description', 'faqs-theme'),
                    'passed' => false,
                    'message' => __('Meta description should be 120-160 characters', 'faqs-theme'),
                );
                $recommendations[] = __('Optimize excerpt/meta description to 120-160 characters', 'faqs-theme');
            }
        } else {
            $checks[] = array(
                'label' => __('Meta Description', 'faqs-theme'),
                'passed' => false,
                'message' => __('No meta description found', 'faqs-theme'),
            );
            $recommendations[] = __('Add a meta description (excerpt)', 'faqs-theme');
        }

        // Headings check (10 points)
        $has_h2 = preg_match('/<h2/i', $content);
        if ($has_h2) {
            $checks[] = array(
                'label' => __('Headings Structure', 'faqs-theme'),
                'passed' => true,
                'message' => __('Good heading structure found', 'faqs-theme'),
            );
            $score += 10;
        } else {
            $checks[] = array(
                'label' => __('Headings Structure', 'faqs-theme'),
                'passed' => false,
                'message' => __('No H2 headings found', 'faqs-theme'),
            );
            $recommendations[] = __('Add H2 headings to structure your content', 'faqs-theme');
        }

        // Images check (10 points)
        $has_images = preg_match('/<img/i', $content);
        if ($has_images) {
            $checks[] = array(
                'label' => __('Images', 'faqs-theme'),
                'passed' => true,
                'message' => __('Images found in content', 'faqs-theme'),
            );
            $score += 10;
        } else {
            $checks[] = array(
                'label' => __('Images', 'faqs-theme'),
                'passed' => false,
                'message' => __('No images found', 'faqs-theme'),
            );
            $recommendations[] = __('Add relevant images to improve engagement', 'faqs-theme');
        }

        // Internal links check (10 points)
        $internal_links = preg_match_all('/<a[^>]*href=["\']' . preg_quote(home_url(), '/') . '[^"\']*["\'][^>]*>/i', $content);
        if ($internal_links >= 2) {
            $checks[] = array(
                'label' => __('Internal Links', 'faqs-theme'),
                'passed' => true,
                'message' => sprintf(__('%d internal links found', 'faqs-theme'), $internal_links),
            );
            $score += 10;
        } else {
            $checks[] = array(
                'label' => __('Internal Links', 'faqs-theme'),
                'passed' => false,
                'message' => __('Add internal links to related content', 'faqs-theme'),
            );
            $recommendations[] = __('Add at least 2 internal links to related posts', 'faqs-theme');
        }

        // External links check (5 points)
        $external_links = preg_match_all('/<a[^>]*href=["\']https?:\/\/[^"\']*["\'][^>]*>/i', $content) - $internal_links;
        if ($external_links >= 1) {
            $checks[] = array(
                'label' => __('External Links', 'faqs-theme'),
                'passed' => true,
                'message' => __('External links to authoritative sources', 'faqs-theme'),
            );
            $score += 5;
        } else {
            $checks[] = array(
                'label' => __('External Links', 'faqs-theme'),
                'passed' => false,
                'message' => __('No external links found', 'faqs-theme'),
            );
            $recommendations[] = __('Link to authoritative external sources', 'faqs-theme');
        }

        // Categories check (10 points)
        $categories = wp_get_post_categories($post->ID);
        if (!empty($categories)) {
            $checks[] = array(
                'label' => __('Categories', 'faqs-theme'),
                'passed' => true,
                'message' => sprintf(__('%d categories assigned', 'faqs-theme'), count($categories)),
            );
            $score += 10;
        } else {
            $checks[] = array(
                'label' => __('Categories', 'faqs-theme'),
                'passed' => false,
                'message' => __('No categories assigned', 'faqs-theme'),
            );
            $recommendations[] = __('Assign at least one category', 'faqs-theme');
        }

        // Tags check (10 points)
        $tags = wp_get_post_tags($post->ID);
        if (count($tags) >= 3 && count($tags) <= 10) {
            $checks[] = array(
                'label' => __('Tags', 'faqs-theme'),
                'passed' => true,
                'message' => sprintf(__('%d tags assigned', 'faqs-theme'), count($tags)),
            );
            $score += 10;
        } else {
            $checks[] = array(
                'label' => __('Tags', 'faqs-theme'),
                'passed' => false,
                'message' => __('Add 3-10 relevant tags', 'faqs-theme'),
            );
            $recommendations[] = __('Add 3-10 relevant tags for better discoverability', 'faqs-theme');
        }

        // Featured image check (10 points)
        if (has_post_thumbnail($post->ID)) {
            $checks[] = array(
                'label' => __('Featured Image', 'faqs-theme'),
                'passed' => true,
                'message' => __('Featured image set', 'faqs-theme'),
            );
            $score += 10;
        } else {
            $checks[] = array(
                'label' => __('Featured Image', 'faqs-theme'),
                'passed' => false,
                'message' => __('No featured image', 'faqs-theme'),
            );
            $recommendations[] = __('Set a featured image for social sharing', 'faqs-theme');
        }

        return array(
            'score' => $score,
            'checks' => $checks,
            'recommendations' => $recommendations,
        );
    }

    private function calculate_seo_score($post_id) {
        $post = get_post($post_id);
        if (!$post) {
            return 0;
        }
        $analysis = $this->analyze_post($post);
        return $analysis['score'];
    }

    private function get_score_color($score) {
        if ($score >= 80) {
            return '#46b450';
        } elseif ($score >= 60) {
            return '#ffb900';
        } else {
            return '#dc3232';
        }
    }

    private function get_score_label($score) {
        if ($score >= 80) {
            return __('Excellent', 'faqs-theme');
        } elseif ($score >= 60) {
            return __('Good', 'faqs-theme');
        } elseif ($score >= 40) {
            return __('Needs Work', 'faqs-theme');
        } else {
            return __('Poor', 'faqs-theme');
        }
    }
}

new FAQs_SEO_Checker();
