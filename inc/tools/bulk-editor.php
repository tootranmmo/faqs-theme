<?php
/**
 * Bulk Post Editor Tool
 * Edit multiple posts at once
 *
 * @package FAQs_Theme
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class FAQs_Bulk_Editor {

    public function __construct() {
        add_action('admin_menu', array($this, 'add_menu'));
        add_action('wp_ajax_faqs_bulk_edit_posts', array($this, 'handle_bulk_edit'));
        add_action('admin_post_list', array($this, 'add_bulk_edit_button'));
    }

    public function add_menu() {
        add_submenu_page(
            'faqs-tools',
            __('Bulk Post Editor', 'faqs-theme'),
            __('Bulk Editor', 'faqs-theme'),
            'edit_posts',
            'faqs-tool-bulk-editor',
            array($this, 'render_page')
        );
    }

    public function render_page() {
        // Get all categories and tags for the filters
        $categories = get_categories(array('hide_empty' => false));
        $tags = get_tags(array('hide_empty' => false));

        ?>
        <div class="wrap faqs-tool-page">
            <div class="faqs-tool-header">
                <h1><?php _e('Bulk Post Editor', 'faqs-theme'); ?></h1>
                <p class="description"><?php _e('Edit multiple posts at once - change categories, tags, status, or delete posts in bulk.', 'faqs-theme'); ?></p>
            </div>

            <div class="faqs-tool-content">
                <!-- Filters -->
                <div class="faqs-filters" style="margin-bottom: 20px;">
                    <h3><?php _e('Filter Posts', 'faqs-theme'); ?></h3>
                    <form method="get" action="">
                        <input type="hidden" name="page" value="faqs-tool-bulk-editor">
                        <table class="form-table">
                            <tr>
                                <th><?php _e('Category', 'faqs-theme'); ?></th>
                                <td>
                                    <select name="filter_category" class="faqs-select">
                                        <option value=""><?php _e('All Categories', 'faqs-theme'); ?></option>
                                        <?php foreach ($categories as $cat) : ?>
                                            <option value="<?php echo esc_attr($cat->term_id); ?>" <?php selected(isset($_GET['filter_category']) ? $_GET['filter_category'] : '', $cat->term_id); ?>>
                                                <?php echo esc_html($cat->name); ?> (<?php echo $cat->count; ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><?php _e('Status', 'faqs-theme'); ?></th>
                                <td>
                                    <select name="filter_status">
                                        <option value="publish" <?php selected(isset($_GET['filter_status']) ? $_GET['filter_status'] : 'publish', 'publish'); ?>><?php _e('Published', 'faqs-theme'); ?></option>
                                        <option value="draft" <?php selected(isset($_GET['filter_status']) ? $_GET['filter_status'] : '', 'draft'); ?>><?php _e('Draft', 'faqs-theme'); ?></option>
                                        <option value="pending" <?php selected(isset($_GET['filter_status']) ? $_GET['filter_status'] : '', 'pending'); ?>><?php _e('Pending', 'faqs-theme'); ?></option>
                                        <option value="any" <?php selected(isset($_GET['filter_status']) ? $_GET['filter_status'] : '', 'any'); ?>><?php _e('All Status', 'faqs-theme'); ?></option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <p class="submit">
                            <button type="submit" class="button button-primary"><?php _e('Apply Filters', 'faqs-theme'); ?></button>
                        </p>
                    </form>
                </div>

                <hr>

                <!-- Posts List -->
                <?php
                $paged = isset($_GET['paged']) ? absint($_GET['paged']) : 1;
                $filter_category = isset($_GET['filter_category']) ? absint($_GET['filter_category']) : 0;
                $filter_status = isset($_GET['filter_status']) ? sanitize_text_field($_GET['filter_status']) : 'publish';

                $query_args = array(
                    'post_type' => 'post',
                    'post_status' => $filter_status,
                    'posts_per_page' => 50,
                    'paged' => $paged,
                );

                if ($filter_category > 0) {
                    $query_args['cat'] = $filter_category;
                }

                $posts_query = new WP_Query($query_args);

                if ($posts_query->have_posts()) :
                ?>
                    <div class="faqs-bulk-actions" style="margin-bottom: 20px;">
                        <h3><?php _e('Bulk Actions', 'faqs-theme'); ?></h3>
                        <form id="bulk-edit-form" method="post">
                            <table class="form-table">
                                <tr>
                                    <th><?php _e('Action', 'faqs-theme'); ?></th>
                                    <td>
                                        <select name="bulk_action" id="bulk-action" required>
                                            <option value=""><?php _e('Select Action', 'faqs-theme'); ?></option>
                                            <option value="change_status"><?php _e('Change Status', 'faqs-theme'); ?></option>
                                            <option value="add_category"><?php _e('Add Category', 'faqs-theme'); ?></option>
                                            <option value="remove_category"><?php _e('Remove Category', 'faqs-theme'); ?></option>
                                            <option value="add_tag"><?php _e('Add Tag', 'faqs-theme'); ?></option>
                                            <option value="delete"><?php _e('Delete Posts', 'faqs-theme'); ?></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr id="status-field" style="display:none;">
                                    <th><?php _e('New Status', 'faqs-theme'); ?></th>
                                    <td>
                                        <select name="new_status">
                                            <option value="publish"><?php _e('Published', 'faqs-theme'); ?></option>
                                            <option value="draft"><?php _e('Draft', 'faqs-theme'); ?></option>
                                            <option value="pending"><?php _e('Pending Review', 'faqs-theme'); ?></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr id="category-field" style="display:none;">
                                    <th><?php _e('Category', 'faqs-theme'); ?></th>
                                    <td>
                                        <select name="category_id" class="faqs-select">
                                            <?php foreach ($categories as $cat) : ?>
                                                <option value="<?php echo esc_attr($cat->term_id); ?>">
                                                    <?php echo esc_html($cat->name); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr id="tag-field" style="display:none;">
                                    <th><?php _e('Tag', 'faqs-theme'); ?></th>
                                    <td>
                                        <input type="text" name="tag_name" placeholder="<?php esc_attr_e('Enter tag name', 'faqs-theme'); ?>">
                                    </td>
                                </tr>
                            </table>

                            <table class="faqs-table widefat fixed striped">
                                <thead>
                                    <tr>
                                        <td class="check-column">
                                            <input type="checkbox" id="select-all">
                                        </td>
                                        <th><?php _e('Title', 'faqs-theme'); ?></th>
                                        <th><?php _e('Author', 'faqs-theme'); ?></th>
                                        <th><?php _e('Categories', 'faqs-theme'); ?></th>
                                        <th><?php _e('Tags', 'faqs-theme'); ?></th>
                                        <th><?php _e('Date', 'faqs-theme'); ?></th>
                                        <th><?php _e('Status', 'faqs-theme'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($posts_query->have_posts()) : $posts_query->the_post(); ?>
                                        <tr>
                                            <td class="check-column">
                                                <input type="checkbox" name="post_ids[]" value="<?php echo get_the_ID(); ?>" class="faqs-checkbox">
                                            </td>
                                            <td><strong><?php the_title(); ?></strong></td>
                                            <td><?php the_author(); ?></td>
                                            <td><?php the_category(', '); ?></td>
                                            <td><?php the_tags('', ', '); ?></td>
                                            <td><?php echo get_the_date(); ?></td>
                                            <td>
                                                <?php
                                                $status = get_post_status();
                                                $status_class = $status === 'publish' ? 'success' : ($status === 'draft' ? 'warning' : 'info');
                                                ?>
                                                <span class="faqs-badge <?php echo $status_class; ?>"><?php echo ucfirst($status); ?></span>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>

                            <?php wp_nonce_field('faqs_bulk_edit_nonce', 'faqs_nonce'); ?>

                            <div class="faqs-tool-actions">
                                <button type="submit" class="button button-primary button-large">
                                    <?php _e('Apply Bulk Action', 'faqs-theme'); ?>
                                </button>
                                <span class="spinner"></span>
                            </div>
                        </form>
                    </div>

                    <!-- Pagination -->
                    <?php if ($posts_query->max_num_pages > 1) : ?>
                        <div class="tablenav">
                            <?php
                            echo paginate_links(array(
                                'base' => add_query_arg('paged', '%#%'),
                                'format' => '',
                                'prev_text' => '&laquo;',
                                'next_text' => '&raquo;',
                                'total' => $posts_query->max_num_pages,
                                'current' => $paged,
                            ));
                            ?>
                        </div>
                    <?php endif; ?>

                <?php else : ?>
                    <div class="faqs-alert info">
                        <p><?php _e('No posts found matching your filters.', 'faqs-theme'); ?></p>
                    </div>
                <?php endif; wp_reset_postdata(); ?>
            </div>
        </div>

        <script>
        jQuery(document).ready(function($) {
            // Select all checkbox
            $('#select-all').on('change', function() {
                $('.faqs-checkbox').prop('checked', this.checked);
            });

            // Show/hide fields based on action
            $('#bulk-action').on('change', function() {
                var action = $(this).val();
                $('#status-field, #category-field, #tag-field').hide();

                if (action === 'change_status') {
                    $('#status-field').show();
                } else if (action === 'add_category' || action === 'remove_category') {
                    $('#category-field').show();
                } else if (action === 'add_tag') {
                    $('#tag-field').show();
                }
            });

            // Handle form submission
            $('#bulk-edit-form').on('submit', function(e) {
                e.preventDefault();

                var checkedCount = $('.faqs-checkbox:checked').length;
                if (checkedCount === 0) {
                    alert('<?php _e('Please select at least one post.', 'faqs-theme'); ?>');
                    return;
                }

                var action = $('#bulk-action').val();
                if (action === 'delete') {
                    if (!confirm('<?php _e('Are you sure you want to delete the selected posts? This cannot be undone!', 'faqs-theme'); ?>')) {
                        return;
                    }
                } else if (!confirm('<?php _e('Apply this action to ', 'faqs-theme'); ?>' + checkedCount + '<?php _e(' posts?', 'faqs-theme'); ?>')) {
                    return;
                }

                var $form = $(this);
                var $button = $form.find('button[type="submit"]');
                var $spinner = $form.find('.spinner');

                $button.prop('disabled', true);
                $spinner.addClass('is-active');

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: $form.serialize() + '&action=faqs_bulk_edit_posts',
                    success: function(response) {
                        if (response.success) {
                            alert(response.data.message);
                            location.reload();
                        } else {
                            alert(response.data.message || '<?php _e('An error occurred', 'faqs-theme'); ?>');
                        }
                    },
                    error: function() {
                        alert('<?php _e('Network error. Please try again.', 'faqs-theme'); ?>');
                    },
                    complete: function() {
                        $button.prop('disabled', false);
                        $spinner.removeClass('is-active');
                    }
                });
            });
        });
        </script>
        <?php
    }

    public function handle_bulk_edit() {
        check_ajax_referer('faqs_bulk_edit_nonce', 'faqs_nonce');

        if (!current_user_can('edit_posts')) {
            wp_send_json_error(array('message' => __('Permission denied', 'faqs-theme')));
        }

        $post_ids = isset($_POST['post_ids']) ? array_map('absint', $_POST['post_ids']) : array();
        $bulk_action = isset($_POST['bulk_action']) ? sanitize_text_field($_POST['bulk_action']) : '';

        if (empty($post_ids)) {
            wp_send_json_error(array('message' => __('No posts selected', 'faqs-theme')));
        }

        $updated_count = 0;

        foreach ($post_ids as $post_id) {
            switch ($bulk_action) {
                case 'change_status':
                    $new_status = isset($_POST['new_status']) ? sanitize_text_field($_POST['new_status']) : 'draft';
                    wp_update_post(array(
                        'ID' => $post_id,
                        'post_status' => $new_status,
                    ));
                    $updated_count++;
                    break;

                case 'add_category':
                    $category_id = isset($_POST['category_id']) ? absint($_POST['category_id']) : 0;
                    if ($category_id > 0) {
                        wp_set_post_categories($post_id, array($category_id), true);
                        $updated_count++;
                    }
                    break;

                case 'remove_category':
                    $category_id = isset($_POST['category_id']) ? absint($_POST['category_id']) : 0;
                    if ($category_id > 0) {
                        $current_cats = wp_get_post_categories($post_id);
                        $new_cats = array_diff($current_cats, array($category_id));
                        wp_set_post_categories($post_id, $new_cats);
                        $updated_count++;
                    }
                    break;

                case 'add_tag':
                    $tag_name = isset($_POST['tag_name']) ? sanitize_text_field($_POST['tag_name']) : '';
                    if (!empty($tag_name)) {
                        wp_add_post_tags($post_id, $tag_name);
                        $updated_count++;
                    }
                    break;

                case 'delete':
                    if (current_user_can('delete_post', $post_id)) {
                        wp_delete_post($post_id, true);
                        $updated_count++;
                    }
                    break;
            }
        }

        wp_send_json_success(array(
            'message' => sprintf(
                __('%d posts updated successfully!', 'faqs-theme'),
                $updated_count
            ),
        ));
    }
}

new FAQs_Bulk_Editor();
