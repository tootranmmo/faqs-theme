/**
 * FAQs Tools Admin JavaScript
 */

(function($) {
    'use strict';

    const FAQsTools = {
        init: function() {
            this.bindEvents();
            this.initComponents();
        },

        bindEvents: function() {
            // Confirmation dialogs
            $(document).on('click', '.faqs-confirm-action', this.confirmAction);

            // AJAX forms
            $(document).on('submit', '.faqs-ajax-form', this.handleAjaxForm);

            // Bulk actions
            $(document).on('click', '.faqs-bulk-action', this.handleBulkAction);
        },

        initComponents: function() {
            // Tooltips
            if ($.fn.tooltip) {
                $('.faqs-tooltip').tooltip();
            }

            // Select2 for better dropdowns
            if ($.fn.select2) {
                $('.faqs-select').select2({
                    width: '100%'
                });
            }

            // Date pickers
            if ($.fn.datepicker) {
                $('.faqs-datepicker').datepicker({
                    dateFormat: 'yy-mm-dd'
                });
            }
        },

        confirmAction: function(e) {
            const message = $(this).data('confirm') || 'Are you sure?';
            if (!confirm(message)) {
                e.preventDefault();
                return false;
            }
        },

        handleAjaxForm: function(e) {
            e.preventDefault();
            const $form = $(this);
            const $button = $form.find('button[type="submit"]');
            const buttonText = $button.text();

            // Disable button and show loading
            $button.prop('disabled', true)
                   .html('<span class="faqs-loading"></span> Processing...');

            // Get form data
            const formData = new FormData(this);

            // Make AJAX request
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        FAQsTools.showNotice('success', response.data.message);
                        if (response.data.reload) {
                            setTimeout(() => location.reload(), 1500);
                        }
                    } else {
                        FAQsTools.showNotice('error', response.data.message || 'An error occurred');
                    }
                },
                error: function() {
                    FAQsTools.showNotice('error', 'Network error. Please try again.');
                },
                complete: function() {
                    // Re-enable button
                    $button.prop('disabled', false).text(buttonText);
                }
            });
        },

        handleBulkAction: function(e) {
            e.preventDefault();
            const $button = $(this);
            const action = $button.data('action');
            const checked = $('.faqs-checkbox:checked');

            if (checked.length === 0) {
                alert('Please select at least one item.');
                return;
            }

            if (!confirm('Apply this action to ' + checked.length + ' items?')) {
                return;
            }

            // Collect IDs
            const ids = [];
            checked.each(function() {
                ids.push($(this).val());
            });

            // Show loading
            $button.prop('disabled', true).html('<span class="faqs-loading"></span> Processing...');

            // Make AJAX request
            $.post(ajaxurl, {
                action: 'faqs_bulk_action',
                bulk_action: action,
                ids: ids,
                nonce: faqsTools.nonce
            }, function(response) {
                if (response.success) {
                    FAQsTools.showNotice('success', response.data.message);
                    setTimeout(() => location.reload(), 1500);
                } else {
                    FAQsTools.showNotice('error', response.data.message || 'An error occurred');
                    $button.prop('disabled', false).text('Apply');
                }
            }).fail(function() {
                FAQsTools.showNotice('error', 'Network error. Please try again.');
                $button.prop('disabled', false).text('Apply');
            });
        },

        showNotice: function(type, message) {
            const $notice = $('<div class="notice notice-' + type + ' is-dismissible"><p>' + message + '</p></div>');
            $('.wrap > h1').after($notice);

            // Auto dismiss after 5 seconds
            setTimeout(() => {
                $notice.fadeOut(() => $notice.remove());
            }, 5000);

            // Scroll to notice
            $('html, body').animate({
                scrollTop: $notice.offset().top - 100
            }, 500);
        },

        updateProgress: function(percent, text) {
            const $progress = $('.faqs-progress-bar');
            $progress.css('width', percent + '%').text(text || percent + '%');
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        FAQsTools.init();
    });

    // Export for global access
    window.FAQsTools = FAQsTools;

})(jQuery);
