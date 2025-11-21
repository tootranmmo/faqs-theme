<?php
/**
 * Database Optimizer Tool
 * Optimize and clean database tables
 *
 * @package FAQs_Theme
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class FAQs_Database_Optimizer {

    public function __construct() {
        add_action('admin_menu', array($this, 'add_menu'));
        add_action('wp_ajax_faqs_optimize_database', array($this, 'handle_optimization'));
        add_action('wp_ajax_faqs_analyze_database', array($this, 'analyze_database'));
    }

    public function add_menu() {
        add_submenu_page(
            'faqs-tools',
            __('Database Optimizer', 'faqs-theme'),
            __('DB Optimizer', 'faqs-theme'),
            'manage_options',
            'faqs-tool-database-optimizer',
            array($this, 'render_page')
        );
    }

    public function render_page() {
        global $wpdb;

        // Get database stats
        $stats = $this->get_database_stats();

        ?>
        <div class="wrap faqs-tool-page">
            <div class="faqs-tool-header">
                <h1>⚡ <?php _e('Database Optimizer', 'faqs-theme'); ?></h1>
                <p class="description"><?php _e('Optimize database tables, clean up unnecessary data, and improve performance.', 'faqs-theme'); ?></p>
            </div>

            <div class="faqs-tool-content">
                <!-- Database Stats -->
                <div class="faqs-stats-grid">
                    <div class="faqs-stat-card blue">
                        <div class="faqs-stat-number"><?php echo number_format_i18n($stats['total_size']); ?> MB</div>
                        <div class="faqs-stat-label"><?php _e('Total Database Size', 'faqs-theme'); ?></div>
                    </div>
                    <div class="faqs-stat-card green">
                        <div class="faqs-stat-number"><?php echo number_format_i18n($stats['total_tables']); ?></div>
                        <div class="faqs-stat-label"><?php _e('Total Tables', 'faqs-theme'); ?></div>
                    </div>
                    <div class="faqs-stat-card orange">
                        <div class="faqs-stat-number"><?php echo number_format_i18n($stats['overhead']); ?> MB</div>
                        <div class="faqs-stat-label"><?php _e('Overhead (Waste)', 'faqs-theme'); ?></div>
                    </div>
                    <div class="faqs-stat-card red">
                        <div class="faqs-stat-number"><?php echo number_format_i18n($stats['revisions']); ?></div>
                        <div class="faqs-stat-label"><?php _e('Post Revisions', 'faqs-theme'); ?></div>
                    </div>
                </div>

                <!-- Optimization Options -->
                <h2><?php _e('Optimization Options', 'faqs-theme'); ?></h2>

                <form id="optimize-form" method="post">
                    <table class="form-table">
                        <tr>
                            <th>
                                <input type="checkbox" name="optimize[]" value="revisions" id="opt-revisions" checked>
                            </th>
                            <td>
                                <label for="opt-revisions">
                                    <strong><?php _e('Clean Post Revisions', 'faqs-theme'); ?></strong><br>
                                    <span class="description"><?php printf(__('Delete %d old post revisions', 'faqs-theme'), $stats['revisions']); ?></span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <input type="checkbox" name="optimize[]" value="auto_drafts" id="opt-drafts" checked>
                            </th>
                            <td>
                                <label for="opt-drafts">
                                    <strong><?php _e('Clean Auto-Drafts', 'faqs-theme'); ?></strong><br>
                                    <span class="description"><?php printf(__('Delete %d auto-draft posts', 'faqs-theme'), $stats['auto_drafts']); ?></span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <input type="checkbox" name="optimize[]" value="trash" id="opt-trash" checked>
                            </th>
                            <td>
                                <label for="opt-trash">
                                    <strong><?php _e('Empty Trash', 'faqs-theme'); ?></strong><br>
                                    <span class="description"><?php printf(__('Permanently delete %d trashed items', 'faqs-theme'), $stats['trash']); ?></span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <input type="checkbox" name="optimize[]" value="spam_comments" id="opt-spam" checked>
                            </th>
                            <td>
                                <label for="opt-spam">
                                    <strong><?php _e('Clean Spam Comments', 'faqs-theme'); ?></strong><br>
                                    <span class="description"><?php printf(__('Delete %d spam comments', 'faqs-theme'), $stats['spam_comments']); ?></span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <input type="checkbox" name="optimize[]" value="trash_comments" id="opt-trash-comments" checked>
                            </th>
                            <td>
                                <label for="opt-trash-comments">
                                    <strong><?php _e('Clean Trashed Comments', 'faqs-theme'); ?></strong><br>
                                    <span class="description"><?php printf(__('Delete %d trashed comments', 'faqs-theme'), $stats['trash_comments']); ?></span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <input type="checkbox" name="optimize[]" value="transients" id="opt-transients" checked>
                            </th>
                            <td>
                                <label for="opt-transients">
                                    <strong><?php _e('Clean Expired Transients', 'faqs-theme'); ?></strong><br>
                                    <span class="description"><?php printf(__('Delete %d expired transient options', 'faqs-theme'), $stats['transients']); ?></span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <input type="checkbox" name="optimize[]" value="optimize_tables" id="opt-tables">
                            </th>
                            <td>
                                <label for="opt-tables">
                                    <strong><?php _e('Optimize Database Tables', 'faqs-theme'); ?></strong><br>
                                    <span class="description"><?php _e('Run OPTIMIZE TABLE on all database tables', 'faqs-theme'); ?></span>
                                </label>
                            </td>
                        </tr>
                    </table>

                    <?php wp_nonce_field('faqs_optimize_db', 'faqs_nonce'); ?>

                    <div class="faqs-tool-actions">
                        <button type="submit" class="button button-primary button-hero">
                            ⚡ <?php _e('Run Optimization', 'faqs-theme'); ?>
                        </button>
                        <button type="button" id="select-all-btn" class="button">
                            <?php _e('Select All', 'faqs-theme'); ?>
                        </button>
                        <button type="button" id="backup-warning" class="button" style="float:right;">
                            ⚠️ <?php _e('Backup Reminder', 'faqs-theme'); ?>
                        </button>
                    </div>
                </form>

                <!-- Progress Bar -->
                <div id="optimization-progress" style="display:none; margin-top: 30px;">
                    <h3><?php _e('Optimization Progress', 'faqs-theme'); ?></h3>
                    <div class="faqs-progress">
                        <div class="faqs-progress-bar" style="width: 0%;">0%</div>
                    </div>
                    <div id="optimization-log" style="margin-top: 20px; padding: 15px; background: #f5f5f5; border-radius: 4px; max-height: 300px; overflow-y: auto; font-family: monospace; font-size: 12px;">
                    </div>
                </div>

                <!-- Database Tables Info -->
                <h2 style="margin-top: 40px;"><?php _e('Database Tables', 'faqs-theme'); ?></h2>
                <table class="faqs-table widefat">
                    <thead>
                        <tr>
                            <th><?php _e('Table Name', 'faqs-theme'); ?></th>
                            <th><?php _e('Rows', 'faqs-theme'); ?></th>
                            <th><?php _e('Data Size', 'faqs-theme'); ?></th>
                            <th><?php _e('Index Size', 'faqs-theme'); ?></th>
                            <th><?php _e('Overhead', 'faqs-theme'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $tables = $wpdb->get_results("
                            SELECT
                                table_name as 'name',
                                table_rows as 'rows',
                                ROUND(data_length / 1024 / 1024, 2) as 'data_size',
                                ROUND(index_length / 1024 / 1024, 2) as 'index_size',
                                ROUND(data_free / 1024 / 1024, 2) as 'overhead'
                            FROM information_schema.tables
                            WHERE table_schema = '" . DB_NAME . "'
                            AND table_name LIKE '" . $wpdb->prefix . "%'
                            ORDER BY (data_length + index_length) DESC
                        ");

                        foreach ($tables as $table) :
                        ?>
                            <tr>
                                <td><strong><?php echo esc_html($table->name); ?></strong></td>
                                <td><?php echo number_format_i18n($table->rows); ?></td>
                                <td><?php echo $table->data_size; ?> MB</td>
                                <td><?php echo $table->index_size; ?> MB</td>
                                <td>
                                    <?php if ($table->overhead > 0) : ?>
                                        <span class="faqs-badge warning"><?php echo $table->overhead; ?> MB</span>
                                    <?php else : ?>
                                        <span class="faqs-badge success">0 MB</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <script>
        jQuery(document).ready(function($) {
            $('#select-all-btn').on('click', function() {
                $('input[name="optimize[]"]').prop('checked', true);
            });

            $('#backup-warning').on('click', function() {
                alert('<?php _e('⚠️ IMPORTANT: Always backup your database before optimization!\n\nYou can use plugins like:\n- UpdraftPlus\n- BackWPup\n- Duplicator\n\nOr backup via your hosting control panel.', 'faqs-theme'); ?>');
            });

            $('#optimize-form').on('submit', function(e) {
                e.preventDefault();

                if (!confirm('<?php _e('Are you sure you want to optimize the database?\n\nMake sure you have a backup first!', 'faqs-theme'); ?>')) {
                    return;
                }

                var $form = $(this);
                var $button = $form.find('button[type="submit"]');
                var $progress = $('#optimization-progress');
                var $progressBar = $('.faqs-progress-bar');
                var $log = $('#optimization-log');

                $button.prop('disabled', true);
                $progress.show();
                $log.html('');

                function addLog(message) {
                    $log.append('<div>' + new Date().toLocaleTimeString() + ' - ' + message + '</div>');
                    $log.scrollTop($log[0].scrollHeight);
                }

                addLog('<?php _e('Starting optimization...', 'faqs-theme'); ?>');

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: $form.serialize() + '&action=faqs_optimize_database',
                    success: function(response) {
                        if (response.success) {
                            $progressBar.css('width', '100%').text('100%');

                            $.each(response.data.results, function(key, result) {
                                addLog('✓ ' + result);
                            });

                            addLog('<?php _e('Optimization completed!', 'faqs-theme'); ?>');

                            setTimeout(function() {
                                alert('<?php _e('Database optimization completed successfully!', 'faqs-theme'); ?>\n\n' + response.data.summary);
                                location.reload();
                            }, 2000);
                        } else {
                            addLog('✗ ' + (response.data.message || '<?php _e('An error occurred', 'faqs-theme'); ?>'));
                            alert(response.data.message);
                        }
                    },
                    error: function() {
                        addLog('✗ <?php _e('Network error', 'faqs-theme'); ?>');
                        alert('<?php _e('Network error. Please try again.', 'faqs-theme'); ?>');
                    },
                    complete: function() {
                        $button.prop('disabled', false);
                    }
                });
            });
        });
        </script>
        <?php
    }

    public function get_database_stats() {
        global $wpdb;

        // Get database size and overhead
        $size_query = $wpdb->get_row("
            SELECT
                SUM(ROUND(data_length / 1024 / 1024, 2)) as total_size,
                SUM(ROUND(data_free / 1024 / 1024, 2)) as overhead,
                COUNT(*) as total_tables
            FROM information_schema.tables
            WHERE table_schema = '" . DB_NAME . "'
            AND table_name LIKE '" . $wpdb->prefix . "%'
        ");

        return array(
            'total_size' => $size_query->total_size ?: 0,
            'overhead' => $size_query->overhead ?: 0,
            'total_tables' => $size_query->total_tables ?: 0,
            'revisions' => $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type = 'revision'"),
            'auto_drafts' => $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_status = 'auto-draft'"),
            'trash' => $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_status = 'trash'"),
            'spam_comments' => $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->comments} WHERE comment_approved = 'spam'"),
            'trash_comments' => $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->comments} WHERE comment_approved = 'trash'"),
            'transients' => $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->options} WHERE option_name LIKE '_transient_%'"),
        );
    }

    public function handle_optimization() {
        check_ajax_referer('faqs_optimize_db', 'faqs_nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission denied', 'faqs-theme')));
        }

        global $wpdb;

        $optimize_options = isset($_POST['optimize']) ? $_POST['optimize'] : array();
        $results = array();
        $total_cleaned = 0;

        foreach ($optimize_options as $option) {
            switch ($option) {
                case 'revisions':
                    $count = $wpdb->query("DELETE FROM {$wpdb->posts} WHERE post_type = 'revision'");
                    $results[] = sprintf(__('Deleted %d post revisions', 'faqs-theme'), $count);
                    $total_cleaned += $count;
                    break;

                case 'auto_drafts':
                    $count = $wpdb->query("DELETE FROM {$wpdb->posts} WHERE post_status = 'auto-draft'");
                    $results[] = sprintf(__('Deleted %d auto-drafts', 'faqs-theme'), $count);
                    $total_cleaned += $count;
                    break;

                case 'trash':
                    $count = $wpdb->query("DELETE FROM {$wpdb->posts} WHERE post_status = 'trash'");
                    $results[] = sprintf(__('Deleted %d trashed posts', 'faqs-theme'), $count);
                    $total_cleaned += $count;
                    break;

                case 'spam_comments':
                    $count = $wpdb->query("DELETE FROM {$wpdb->comments} WHERE comment_approved = 'spam'");
                    $results[] = sprintf(__('Deleted %d spam comments', 'faqs-theme'), $count);
                    $total_cleaned += $count;
                    break;

                case 'trash_comments':
                    $count = $wpdb->query("DELETE FROM {$wpdb->comments} WHERE comment_approved = 'trash'");
                    $results[] = sprintf(__('Deleted %d trashed comments', 'faqs-theme'), $count);
                    $total_cleaned += $count;
                    break;

                case 'transients':
                    $count = $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_%' OR option_name LIKE '_site_transient_%'");
                    $results[] = sprintf(__('Deleted %d expired transients', 'faqs-theme'), $count);
                    $total_cleaned += $count;
                    break;

                case 'optimize_tables':
                    $tables = $wpdb->get_col("SHOW TABLES LIKE '" . $wpdb->prefix . "%'");
                    foreach ($tables as $table) {
                        $wpdb->query("OPTIMIZE TABLE `{$table}`");
                    }
                    $results[] = sprintf(__('Optimized %d database tables', 'faqs-theme'), count($tables));
                    break;
            }
        }

        // Clean orphaned meta
        $orphaned_postmeta = $wpdb->query("
            DELETE pm FROM {$wpdb->postmeta} pm
            LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
            WHERE p.ID IS NULL
        ");
        if ($orphaned_postmeta > 0) {
            $results[] = sprintf(__('Deleted %d orphaned post meta', 'faqs-theme'), $orphaned_postmeta);
            $total_cleaned += $orphaned_postmeta;
        }

        wp_send_json_success(array(
            'results' => $results,
            'summary' => sprintf(__('Total items cleaned: %d', 'faqs-theme'), $total_cleaned),
        ));
    }
}

new FAQs_Database_Optimizer();
