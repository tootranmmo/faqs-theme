<?php
/**
 * FAQs Theme Tools Manager
 * Centralized management for all theme tools
 *
 * @package FAQs_Theme
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class FAQs_Tools_Manager {

    private static $instance = null;
    private $tools = array();

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Register all tools
        $this->register_tools();

        // Load tool files
        $this->load_tool_files();

        // Add admin menu
        add_action('admin_menu', array($this, 'add_tools_menu'));

        // Enqueue admin scripts
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }

    /**
     * Load all available tool files
     */
    private function load_tool_files() {
        $tools_dir = FAQS_THEME_DIR . '/inc/tools/';

        // Load placeholder template first
        require_once $tools_dir . '_placeholder-template.php';

        // Load implemented tool files
        $tool_files = array(
            'bulk-editor.php',
            'database-optimizer.php',
            'seo-checker.php',
            'newsletter.php',
        );

        foreach ($tool_files as $file) {
            $file_path = $tools_dir . $file;
            if (file_exists($file_path)) {
                require_once $file_path;
            }
        }
    }

    /**
     * Register all available tools
     */
    private function register_tools() {
        $this->tools = array(
            'basic' => array(
                'title' => __('Basic Tools', 'faqs-theme'),
                'icon' => '🔧',
                'tools' => array(
                    'bulk-editor' => array(
                        'name' => __('Bulk Post Editor', 'faqs-theme'),
                        'description' => __('Edit multiple posts at once', 'faqs-theme'),
                        'file' => 'bulk-editor.php',
                    ),
                    'quick-duplicate' => array(
                        'name' => __('Quick Duplicate', 'faqs-theme'),
                        'description' => __('Duplicate posts quickly', 'faqs-theme'),
                        'file' => 'quick-duplicate.php',
                    ),
                    'post-templates' => array(
                        'name' => __('Post Templates', 'faqs-theme'),
                        'description' => __('Save and reuse post structures', 'faqs-theme'),
                        'file' => 'post-templates.php',
                    ),
                    'seo-checker' => array(
                        'name' => __('SEO Checker', 'faqs-theme'),
                        'description' => __('Check SEO score of posts', 'faqs-theme'),
                        'file' => 'seo-checker.php',
                    ),
                    'broken-links' => array(
                        'name' => __('Broken Link Checker', 'faqs-theme'),
                        'description' => __('Find and fix broken links', 'faqs-theme'),
                        'file' => 'broken-links.php',
                    ),
                ),
            ),
            'engagement' => array(
                'title' => __('User Engagement', 'faqs-theme'),
                'icon' => '💬',
                'tools' => array(
                    'newsletter' => array(
                        'name' => __('Newsletter Manager', 'faqs-theme'),
                        'description' => __('Manage email subscriptions', 'faqs-theme'),
                        'file' => 'newsletter.php',
                    ),
                    'user-contribution' => array(
                        'name' => __('User Contributions', 'faqs-theme'),
                        'description' => __('Manage user-submitted questions', 'faqs-theme'),
                        'file' => 'user-contribution.php',
                    ),
                    'social-counter' => array(
                        'name' => __('Social Share Counter', 'faqs-theme'),
                        'description' => __('Track social media shares', 'faqs-theme'),
                        'file' => 'social-counter.php',
                    ),
                ),
            ),
            'discovery' => array(
                'title' => __('Content Discovery', 'faqs-theme'),
                'icon' => '🔍',
                'tools' => array(
                    'advanced-search' => array(
                        'name' => __('Advanced Search', 'faqs-theme'),
                        'description' => __('Enhanced search with filters', 'faqs-theme'),
                        'file' => 'advanced-search.php',
                    ),
                    'trending-topics' => array(
                        'name' => __('Trending Topics', 'faqs-theme'),
                        'description' => __('Display hot topics', 'faqs-theme'),
                        'file' => 'trending-topics.php',
                    ),
                ),
            ),
            'productivity' => array(
                'title' => __('Productivity', 'faqs-theme'),
                'icon' => '📅',
                'tools' => array(
                    'content-calendar' => array(
                        'name' => __('Content Calendar', 'faqs-theme'),
                        'description' => __('Visual content planning', 'faqs-theme'),
                        'file' => 'content-calendar.php',
                    ),
                    'editorial-workflow' => array(
                        'name' => __('Editorial Workflow', 'faqs-theme'),
                        'description' => __('Manage editorial process', 'faqs-theme'),
                        'file' => 'editorial-workflow.php',
                    ),
                    'version-history' => array(
                        'name' => __('Version History', 'faqs-theme'),
                        'description' => __('Track post revisions', 'faqs-theme'),
                        'file' => 'version-history.php',
                    ),
                ),
            ),
            'ai-automation' => array(
                'title' => __('AI & Automation', 'faqs-theme'),
                'icon' => '🤖',
                'tools' => array(
                    'ai-suggestions' => array(
                        'name' => __('AI Content Suggestions', 'faqs-theme'),
                        'description' => __('AI-powered content ideas', 'faqs-theme'),
                        'file' => 'ai-suggestions.php',
                    ),
                    'auto-tagging' => array(
                        'name' => __('Auto-Tagging', 'faqs-theme'),
                        'description' => __('Automatic tag generation', 'faqs-theme'),
                        'file' => 'auto-tagging.php',
                    ),
                    'faq-generator' => array(
                        'name' => __('FAQ Generator', 'faqs-theme'),
                        'description' => __('Generate FAQs from comments', 'faqs-theme'),
                        'file' => 'faq-generator.php',
                    ),
                    'content-gap' => array(
                        'name' => __('Content Gap Analysis', 'faqs-theme'),
                        'description' => __('Find missing topics', 'faqs-theme'),
                        'file' => 'content-gap.php',
                    ),
                ),
            ),
            'performance' => array(
                'title' => __('Performance & Optimization', 'faqs-theme'),
                'icon' => '⚡',
                'tools' => array(
                    'database-optimizer' => array(
                        'name' => __('Database Optimizer', 'faqs-theme'),
                        'description' => __('Optimize database tables', 'faqs-theme'),
                        'file' => 'database-optimizer.php',
                    ),
                    'image-compressor' => array(
                        'name' => __('Image Compressor', 'faqs-theme'),
                        'description' => __('Compress images automatically', 'faqs-theme'),
                        'file' => 'image-compressor.php',
                    ),
                    'cache-manager' => array(
                        'name' => __('Cache Manager', 'faqs-theme'),
                        'description' => __('Manage site cache', 'faqs-theme'),
                        'file' => 'cache-manager.php',
                    ),
                ),
            ),
            'moderation' => array(
                'title' => __('Moderation & Quality', 'faqs-theme'),
                'icon' => '🛡️',
                'tools' => array(
                    'comment-moderation' => array(
                        'name' => __('Comment Moderation', 'faqs-theme'),
                        'description' => __('Centralized comment management', 'faqs-theme'),
                        'file' => 'comment-moderation.php',
                    ),
                    'spam-filter' => array(
                        'name' => __('Spam Filter', 'faqs-theme'),
                        'description' => __('AI-powered spam detection', 'faqs-theme'),
                        'file' => 'spam-filter.php',
                    ),
                    'content-quality' => array(
                        'name' => __('Content Quality Checker', 'faqs-theme'),
                        'description' => __('Check grammar and readability', 'faqs-theme'),
                        'file' => 'content-quality.php',
                    ),
                ),
            ),
            'multilang' => array(
                'title' => __('Multi-language', 'faqs-theme'),
                'icon' => '🌍',
                'tools' => array(
                    'translation-manager' => array(
                        'name' => __('Translation Manager', 'faqs-theme'),
                        'description' => __('Manage translations', 'faqs-theme'),
                        'file' => 'translation-manager.php',
                    ),
                    'language-switcher' => array(
                        'name' => __('Language Switcher', 'faqs-theme'),
                        'description' => __('Add language switcher', 'faqs-theme'),
                        'file' => 'language-switcher.php',
                    ),
                ),
            ),
            'analytics' => array(
                'title' => __('Analytics & Reporting', 'faqs-theme'),
                'icon' => '📊',
                'tools' => array(
                    'custom-dashboard' => array(
                        'name' => __('Custom Analytics', 'faqs-theme'),
                        'description' => __('Custom analytics dashboard', 'faqs-theme'),
                        'file' => 'custom-dashboard.php',
                    ),
                    'ab-testing' => array(
                        'name' => __('A/B Testing', 'faqs-theme'),
                        'description' => __('Test different versions', 'faqs-theme'),
                        'file' => 'ab-testing.php',
                    ),
                    'export-reports' => array(
                        'name' => __('Export Reports', 'faqs-theme'),
                        'description' => __('Export data to Excel/PDF', 'faqs-theme'),
                        'file' => 'export-reports.php',
                    ),
                ),
            ),
            'monetization' => array(
                'title' => __('Monetization', 'faqs-theme'),
                'icon' => '💰',
                'tools' => array(
                    'ads-manager' => array(
                        'name' => __('Ads Manager', 'faqs-theme'),
                        'description' => __('Manage ad placements', 'faqs-theme'),
                        'file' => 'ads-manager.php',
                    ),
                    'affiliate-links' => array(
                        'name' => __('Affiliate Link Manager', 'faqs-theme'),
                        'description' => __('Manage affiliate links', 'faqs-theme'),
                        'file' => 'affiliate-links.php',
                    ),
                    'premium-content' => array(
                        'name' => __('Premium Content', 'faqs-theme'),
                        'description' => __('Lock content for members', 'faqs-theme'),
                        'file' => 'premium-content.php',
                    ),
                    'donation-system' => array(
                        'name' => __('Donation System', 'faqs-theme'),
                        'description' => __('Accept donations', 'faqs-theme'),
                        'file' => 'donation-system.php',
                    ),
                ),
            ),
            'api' => array(
                'title' => __('API & Integrations', 'faqs-theme'),
                'icon' => '🔌',
                'tools' => array(
                    'rest-api' => array(
                        'name' => __('REST API Extensions', 'faqs-theme'),
                        'description' => __('Custom API endpoints', 'faqs-theme'),
                        'file' => 'rest-api.php',
                    ),
                    'webhook-manager' => array(
                        'name' => __('Webhook Manager', 'faqs-theme'),
                        'description' => __('Configure webhooks', 'faqs-theme'),
                        'file' => 'webhook-manager.php',
                    ),
                    'import-export' => array(
                        'name' => __('Import/Export', 'faqs-theme'),
                        'description' => __('Import from Wikipedia, Medium', 'faqs-theme'),
                        'file' => 'import-export.php',
                    ),
                ),
            ),
        );
    }

    /**
     * Add tools menu to admin
     */
    public function add_tools_menu() {
        add_menu_page(
            __('FAQs Tools', 'faqs-theme'),
            __('FAQs Tools', 'faqs-theme'),
            'manage_options',
            'faqs-tools',
            array($this, 'render_tools_dashboard'),
            'dashicons-admin-tools',
            30
        );
    }

    /**
     * Render tools dashboard
     */
    public function render_tools_dashboard() {
        ?>
        <div class="wrap faqs-tools-dashboard">
            <h1><?php _e('FAQs Theme Tools', 'faqs-theme'); ?></h1>
            <p class="description"><?php _e('Comprehensive toolkit for managing your FAQs website', 'faqs-theme'); ?></p>

            <div class="faqs-tools-grid">
                <?php foreach ($this->tools as $category_id => $category) : ?>
                    <div class="faqs-tools-category">
                        <h2>
                            <span class="category-icon"><?php echo $category['icon']; ?></span>
                            <?php echo esc_html($category['title']); ?>
                        </h2>
                        <div class="tools-list">
                            <?php foreach ($category['tools'] as $tool_id => $tool) : ?>
                                <div class="tool-card">
                                    <h3><?php echo esc_html($tool['name']); ?></h3>
                                    <p><?php echo esc_html($tool['description']); ?></p>
                                    <a href="<?php echo admin_url('admin.php?page=faqs-tool-' . $tool_id); ?>" class="button button-primary">
                                        <?php _e('Open Tool', 'faqs-theme'); ?>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }

    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        if (strpos($hook, 'faqs-tools') === false && strpos($hook, 'faqs-tool-') === false) {
            return;
        }

        wp_enqueue_style(
            'faqs-tools-admin',
            FAQS_THEME_URI . '/assets/css/tools-admin.css',
            array(),
            FAQS_THEME_VERSION
        );

        wp_enqueue_script(
            'faqs-tools-admin',
            FAQS_THEME_URI . '/assets/js/tools-admin.js',
            array('jquery'),
            FAQS_THEME_VERSION,
            true
        );
    }

    /**
     * Get all registered tools
     */
    public function get_tools() {
        return $this->tools;
    }
}

// Initialize the tools manager
FAQs_Tools_Manager::get_instance();
