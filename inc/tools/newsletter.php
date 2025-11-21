<?php
/**
 * Newsletter Manager Tool
 * Manage email subscriptions
 *
 * @package FAQs_Theme
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class FAQs_Newsletter_Manager {

    public function __construct() {
        add_action('admin_menu', array($this, 'add_menu'));
        add_action('wp_ajax_faqs_export_subscribers', array($this, 'export_subscribers'));
        add_action('wp_ajax_nopriv_faqs_newsletter_subscribe', array($this, 'handle_subscription'));
        add_action('wp_ajax_faqs_newsletter_subscribe', array($this, 'handle_subscription'));
        add_shortcode('faqs_newsletter', array($this, 'newsletter_form_shortcode'));

        // Create custom table on init
        add_action('after_switch_theme', array($this, 'create_table'));
        $this->maybe_create_table();
    }

    /**
     * Check and create table if not exists
     */
    public function maybe_create_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'faqs_newsletter';

        // Check if table exists
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $this->create_table();
        }
    }

    public function create_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'faqs_newsletter';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            email varchar(100) NOT NULL,
            name varchar(100) DEFAULT '',
            status varchar(20) DEFAULT 'active',
            subscribed_date datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY email (email)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public function add_menu() {
        add_submenu_page(
            'faqs-tools',
            __('Newsletter Manager', 'faqs-theme'),
            __('Newsletter', 'faqs-theme'),
            'manage_options',
            'faqs-tool-newsletter',
            array($this, 'render_page')
        );
    }

    public function render_page() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'faqs_newsletter';

        // Get stats
        $total = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'active'");
        $today = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE DATE(subscribed_date) = CURDATE()");
        $this_week = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE YEARWEEK(subscribed_date) = YEARWEEK(NOW())");
        $this_month = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE YEAR(subscribed_date) = YEAR(NOW()) AND MONTH(subscribed_date) = MONTH(NOW())");

        // Get recent subscribers
        $recent_subscribers = $wpdb->get_results("SELECT * FROM $table_name ORDER BY subscribed_date DESC LIMIT 50");

        ?>
        <div class="wrap faqs-tool-page">
            <div class="faqs-tool-header">
                <h1>💌 <?php _e('Newsletter Manager', 'faqs-theme'); ?></h1>
                <p class="description"><?php _e('Manage your email subscribers and newsletter campaigns', 'faqs-theme'); ?></p>
            </div>

            <div class="faqs-tool-content">
                <!-- Stats -->
                <div class="faqs-stats-grid">
                    <div class="faqs-stat-card blue">
                        <div class="faqs-stat-number"><?php echo number_format_i18n($total); ?></div>
                        <div class="faqs-stat-label"><?php _e('Total Subscribers', 'faqs-theme'); ?></div>
                    </div>
                    <div class="faqs-stat-card green">
                        <div class="faqs-stat-number"><?php echo number_format_i18n($today); ?></div>
                        <div class="faqs-stat-label"><?php _e('Today', 'faqs-theme'); ?></div>
                    </div>
                    <div class="faqs-stat-card orange">
                        <div class="faqs-stat-number"><?php echo number_format_i18n($this_week); ?></div>
                        <div class="faqs-stat-label"><?php _e('This Week', 'faqs-theme'); ?></div>
                    </div>
                    <div class="faqs-stat-card">
                        <div class="faqs-stat-number"><?php echo number_format_i18n($this_month); ?></div>
                        <div class="faqs-stat-label"><?php _e('This Month', 'faqs-theme'); ?></div>
                    </div>
                </div>

                <!-- Add Subscriber Form -->
                <h2><?php _e('Add New Subscriber', 'faqs-theme'); ?></h2>
                <form method="post" action="" id="add-subscriber-form">
                    <table class="form-table">
                        <tr>
                            <th><?php _e('Email', 'faqs-theme'); ?></th>
                            <td>
                                <input type="email" name="email" required class="regular-text">
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e('Name', 'faqs-theme'); ?></th>
                            <td>
                                <input type="text" name="name" class="regular-text">
                            </td>
                        </tr>
                    </table>
                    <?php wp_nonce_field('faqs_add_subscriber', 'faqs_nonce'); ?>
                    <p class="submit">
                        <button type="submit" class="button button-primary"><?php _e('Add Subscriber', 'faqs-theme'); ?></button>
                    </p>
                </form>

                <?php
                // Handle add subscriber
                if (isset($_POST['faqs_nonce']) && wp_verify_nonce($_POST['faqs_nonce'], 'faqs_add_subscriber')) {
                    $email = sanitize_email($_POST['email']);
                    $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';

                    $inserted = $wpdb->insert(
                        $table_name,
                        array(
                            'email' => $email,
                            'name' => $name,
                            'status' => 'active',
                        ),
                        array('%s', '%s', '%s')
                    );

                    if ($inserted) {
                        echo '<div class="notice notice-success"><p>' . __('Subscriber added successfully!', 'faqs-theme') . '</p></div>';
                    } else {
                        echo '<div class="notice notice-error"><p>' . __('Error adding subscriber. Email may already exist.', 'faqs-theme') . '</p></div>';
                    }
                }
                ?>

                <hr>

                <!-- Subscribers List -->
                <h2><?php _e('Subscribers', 'faqs-theme'); ?></h2>

                <div style="margin-bottom: 20px;">
                    <button id="export-csv" class="button button-primary"><?php _e('Export to CSV', 'faqs-theme'); ?></button>
                    <button id="copy-emails" class="button"><?php _e('Copy All Emails', 'faqs-theme'); ?></button>
                </div>

                <table class="faqs-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th><?php _e('Email', 'faqs-theme'); ?></th>
                            <th><?php _e('Name', 'faqs-theme'); ?></th>
                            <th><?php _e('Subscribed Date', 'faqs-theme'); ?></th>
                            <th><?php _e('Status', 'faqs-theme'); ?></th>
                            <th><?php _e('Actions', 'faqs-theme'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recent_subscribers)) : ?>
                            <?php foreach ($recent_subscribers as $subscriber) : ?>
                                <tr>
                                    <td><strong><?php echo esc_html($subscriber->email); ?></strong></td>
                                    <td><?php echo esc_html($subscriber->name); ?></td>
                                    <td><?php echo date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($subscriber->subscribed_date)); ?></td>
                                    <td>
                                        <span class="faqs-badge success"><?php echo ucfirst($subscriber->status); ?></span>
                                    </td>
                                    <td>
                                        <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=faqs-tool-newsletter&action=delete&id=' . $subscriber->id), 'delete_subscriber_' . $subscriber->id); ?>" class="button button-small" onclick="return confirm('<?php _e('Are you sure?', 'faqs-theme'); ?>');">
                                            <?php _e('Delete', 'faqs-theme'); ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 40px;">
                                    <?php _e('No subscribers yet', 'faqs-theme'); ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Shortcode Info -->
                <div class="faqs-alert info" style="margin-top: 30px;">
                    <h3><?php _e('Add Newsletter Form to Your Site', 'faqs-theme'); ?></h3>
                    <p><?php _e('Use this shortcode to add a newsletter subscription form:', 'faqs-theme'); ?></p>
                    <code style="font-size: 14px; padding: 10px; background: #f5f5f5; display: inline-block; margin: 10px 0;">[faqs_newsletter]</code>
                </div>
            </div>
        </div>

        <script>
        jQuery(document).ready(function($) {
            $('#export-csv').on('click', function() {
                window.location.href = ajaxurl + '?action=faqs_export_subscribers&nonce=<?php echo wp_create_nonce('faqs_export'); ?>';
            });

            $('#copy-emails').on('click', function() {
                var emails = [];
                $('table tbody tr td:first-child').each(function() {
                    var email = $(this).text().trim();
                    if (email && email.indexOf('@') > -1) {
                        emails.push(email);
                    }
                });

                var emailString = emails.join(', ');
                navigator.clipboard.writeText(emailString).then(function() {
                    alert('<?php _e('Emails copied to clipboard!', 'faqs-theme'); ?>');
                });
            });
        });
        </script>
        <?php

        // Handle delete
        if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
            $id = absint($_GET['id']);
            if (wp_verify_nonce($_GET['_wpnonce'], 'delete_subscriber_' . $id)) {
                $wpdb->delete($table_name, array('id' => $id), array('%d'));
                echo '<script>window.location.href = "' . admin_url('admin.php?page=faqs-tool-newsletter') . '";</script>';
            }
        }
    }

    public function export_subscribers() {
        check_ajax_referer('faqs_export', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_die('Permission denied');
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'faqs_newsletter';
        $subscribers = $wpdb->get_results("SELECT * FROM $table_name ORDER BY subscribed_date DESC", ARRAY_A);

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="newsletter-subscribers-' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, array('Email', 'Name', 'Status', 'Subscribed Date'));

        foreach ($subscribers as $subscriber) {
            fputcsv($output, array(
                $subscriber['email'],
                $subscriber['name'],
                $subscriber['status'],
                $subscriber['subscribed_date'],
            ));
        }

        fclose($output);
        exit;
    }

    public function handle_subscription() {
        check_ajax_referer('faqs_newsletter_nonce', 'nonce');

        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';

        if (empty($email) || !is_email($email)) {
            wp_send_json_error(array('message' => __('Invalid email address', 'faqs-theme')));
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'faqs_newsletter';

        $existing = $wpdb->get_var($wpdb->prepare("SELECT id FROM $table_name WHERE email = %s", $email));

        if ($existing) {
            wp_send_json_error(array('message' => __('You are already subscribed!', 'faqs-theme')));
        }

        $inserted = $wpdb->insert(
            $table_name,
            array(
                'email' => $email,
                'name' => $name,
                'status' => 'active',
            ),
            array('%s', '%s', '%s')
        );

        if ($inserted) {
            wp_send_json_success(array('message' => __('Thank you for subscribing!', 'faqs-theme')));
        } else {
            wp_send_json_error(array('message' => __('An error occurred. Please try again.', 'faqs-theme')));
        }
    }

    public function newsletter_form_shortcode($atts) {
        $atts = shortcode_atts(array(
            'title' => __('Subscribe to our Newsletter', 'faqs-theme'),
            'description' => __('Get the latest updates delivered to your inbox', 'faqs-theme'),
        ), $atts);

        ob_start();
        ?>
        <div class="faqs-newsletter-form" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 40px; border-radius: 12px; margin: 30px 0;">
            <h3 style="margin: 0 0 10px 0; font-size: 24px;"><?php echo esc_html($atts['title']); ?></h3>
            <p style="margin: 0 0 20px 0; opacity: 0.9;"><?php echo esc_html($atts['description']); ?></p>

            <form class="faqs-newsletter-subscribe-form" style="display: flex; gap: 10px; flex-wrap: wrap;">
                <input type="email" name="email" placeholder="<?php esc_attr_e('Your email address', 'faqs-theme'); ?>" required style="flex: 1; min-width: 200px; padding: 12px 20px; border: none; border-radius: 6px; font-size: 16px;">
                <button type="submit" style="padding: 12px 30px; background: white; color: #667eea; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 16px;">
                    <?php _e('Subscribe', 'faqs-theme'); ?>
                </button>
                <?php wp_nonce_field('faqs_newsletter_nonce', 'nonce', false); ?>
            </form>

            <div class="faqs-newsletter-message" style="margin-top: 15px; display: none;"></div>
        </div>

        <script>
        jQuery(document).ready(function($) {
            $('.faqs-newsletter-subscribe-form').on('submit', function(e) {
                e.preventDefault();

                var $form = $(this);
                var $button = $form.find('button');
                var $message = $form.siblings('.faqs-newsletter-message');
                var buttonText = $button.text();

                $button.prop('disabled', true).text('<?php _e('Subscribing...', 'faqs-theme'); ?>');

                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'POST',
                    data: $form.serialize() + '&action=faqs_newsletter_subscribe',
                    success: function(response) {
                        $message.show().html(response.data.message);

                        if (response.success) {
                            $form[0].reset();
                            $message.css('color', '#d4edda');
                        } else {
                            $message.css('color', '#f8d7da');
                        }
                    },
                    error: function() {
                        $message.show().html('<?php _e('An error occurred', 'faqs-theme'); ?>').css('color', '#f8d7da');
                    },
                    complete: function() {
                        $button.prop('disabled', false).text(buttonText);
                    }
                });
            });
        });
        </script>
        <?php
        return ob_get_clean();
    }
}

new FAQs_Newsletter_Manager();
