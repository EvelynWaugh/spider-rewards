/**
 * Spider Rewards Admin JavaScript
 */

jQuery(document).ready(function($) {
    'use strict';
    
    // Status update functionality
    $('.status-action').on('click', function(e) {
        e.preventDefault();
        
        const button = $(this);
        const submissionId = button.data('id');
        const newStatus = button.data('status');
        const tableType = button.data('table');
        
        if (!submissionId || !newStatus || !tableType) {
            return;
        }
        
        // Confirm action
        const confirmMessage = `Are you sure you want to ${newStatus} this submission?`;
        if (!confirm(confirmMessage)) {
            return;
        }
        
        // Show loading state
        button.addClass('loading').text('Processing...');
        
        // Send AJAX request
        $.ajax({
            url: spiderRewardsAdmin.ajaxurl,
            method: 'POST',
            data: {
                action: 'update_submission_status',
                submission_id: submissionId,
                status: newStatus,
                table_type: tableType,
                nonce: spiderRewardsAdmin.nonce
            },
            success: function(response) {
                if (response.success) {
                    // Reload the page to show updated status
                    location.reload();
                } else {
                    alert('Error: ' + response.data.message);
                    button.removeClass('loading').text(getButtonText(newStatus));
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
                button.removeClass('loading').text(getButtonText(newStatus));
            }
        });
    });
    
    // Get button text based on status
    function getButtonText(status) {
        switch (status) {
            case 'approved':
                return 'Approve';
            case 'rejected':
                return 'Reject';
            case 'pending':
                return 'Set Pending';
            default:
                return 'Update';
        }
    }
    
    // Bulk actions (if needed in the future)
    $('#doaction, #doaction2').on('click', function(e) {
        const action = $(this).siblings('select').val();
        const checked = $('input[name^="video_submission[]"]:checked, input[name^="referral_submission[]"]:checked, input[name^="best_submission[]"]:checked, input[name^="review_submission[]"]:checked');
        
        if (action === '-1') {
            return;
        }
        
        if (checked.length === 0) {
            e.preventDefault();
            alert('Please select at least one item.');
            return;
        }
        
        const confirmMessage = `Are you sure you want to ${action} ${checked.length} submission(s)?`;
        if (!confirm(confirmMessage)) {
            e.preventDefault();
        }
    });
    
    // Auto-refresh functionality for real-time updates
    let autoRefreshInterval;
    const autoRefreshCheckbox = $('#auto-refresh');
    
    if (autoRefreshCheckbox.length) {
        autoRefreshCheckbox.on('change', function() {
            if ($(this).is(':checked')) {
                startAutoRefresh();
            } else {
                stopAutoRefresh();
            }
        });
    }
    
    function startAutoRefresh() {
        autoRefreshInterval = setInterval(function() {
            // Only refresh if no AJAX requests are pending
            if ($.active === 0) {
                location.reload();
            }
        }, 30000); // Refresh every 30 seconds
    }
    
    function stopAutoRefresh() {
        if (autoRefreshInterval) {
            clearInterval(autoRefreshInterval);
        }
    }
    
    // Statistics refresh
    $('.stat-card').on('click', function() {
        refreshStatistics();
    });
    
    function refreshStatistics() {
        $.ajax({
            url: spiderRewardsAdmin.ajaxurl,
            method: 'POST',
            data: {
                action: 'get_dashboard_stats',
                nonce: spiderRewardsAdmin.nonce
            },
            success: function(response) {
                if (response.success) {
                    updateStatistics(response.data);
                }
            }
        });
    }
    
    function updateStatistics(stats) {
        $('.stat-card').each(function() {
            const card = $(this);
            const statType = card.data('stat-type');
            if (stats[statType]) {
                card.find('.stat-number').text(stats[statType]);
            }
        });
    }
    
    // Enhanced table interactions
    $('.wp-list-table tbody tr').hover(
        function() {
            $(this).addClass('hover');
        },
        function() {
            $(this).removeClass('hover');
        }
    );
    
    // Quick view functionality
    $('.view-submission').on('click', function(e) {
        e.preventDefault();
        const submissionId = $(this).data('id');
        const tableType = $(this).data('table');
        
        // Open quick view modal (implementation would depend on specific needs)
        openQuickView(submissionId, tableType);
    });
    
    function openQuickView(submissionId, tableType) {
        // This would typically open a modal with submission details
        // For now, we'll just show an alert
        alert(`Quick view for ${tableType} submission #${submissionId} would open here.`);
    }
    
    // Export functionality
    $('.export-submissions').on('click', function(e) {
        e.preventDefault();
        const tableType = $(this).data('table');
        const exportUrl = `${window.location.href}&export=${tableType}&nonce=${spiderRewardsAdmin.nonce}`;
        window.open(exportUrl, '_blank');
    });
    
    // Search and filter functionality
    $('#submission-search').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        $('.wp-list-table tbody tr').each(function() {
            const row = $(this);
            const text = row.text().toLowerCase();
            if (text.includes(searchTerm)) {
                row.show();
            } else {
                row.hide();
            }
        });
    });
    
    // Filter by status
    $('#status-filter').on('change', function() {
        const selectedStatus = $(this).val();
        $('.wp-list-table tbody tr').each(function() {
            const row = $(this);
            const statusCell = row.find('.status-badge');
            
            if (selectedStatus === '' || statusCell.hasClass(`status-${selectedStatus}`)) {
                row.show();
            } else {
                row.hide();
            }
        });
    });
    
    // Keyboard shortcuts
    $(document).on('keydown', function(e) {
        // Ctrl/Cmd + R for refresh
        if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
            e.preventDefault();
            location.reload();
        }
        
        // Escape to clear search
        if (e.key === 'Escape') {
            $('#submission-search').val('').trigger('input');
            $('#status-filter').val('').trigger('change');
        }
    });
    
    // Modal functionality for add/edit/delete operations
    let currentModal = null;
    
    // Modal HTML template
    const modalTemplate = `
        <div id="spider-rewards-modal" class="modal-overlay">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title"></h3>
                    <button class="modal-close">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="modal-form">
                        <div class="form-fields"></div>
                        <div class="form-actions">
                            <button type="submit" class="button button-primary">Save</button>
                            <button type="button" class="button modal-cancel">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    `;
    
    // Initialize modal container
    if ($('#spider-rewards-modal').length === 0) {
        $('body').append(modalTemplate);
    }
    
    // Modal event handlers
    $(document).on('click', '.modal-close, .modal-cancel', closeModal);
    $(document).on('click', '.modal-overlay', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
    
    // Add new submission handlers
    $('.add-new-submission').on('click', function(e) {
        e.preventDefault();
        const tableType = $(this).data('table');
        openAddModal(tableType);
    });
    
    // Edit submission handlers
    $(document).on('click', '.edit-submission', function(e) {
        e.preventDefault();
        const submissionId = $(this).data('id');
        const tableType = $(this).data('table');
        openEditModal(submissionId, tableType);
    });
    
    // Delete submission handlers
    $(document).on('click', '.delete-submission', function(e) {
        e.preventDefault();
        const submissionId = $(this).data('id');
        const tableType = $(this).data('table');
        confirmDelete(submissionId, tableType);
    });
    
    // Form submission handler
    $(document).on('submit', '#modal-form', function(e) {
        e.preventDefault();
        submitModalForm();
    });
    
    // Open add modal
    function openAddModal(tableType) {
        const modalConfig = getModalConfig(tableType, 'add');
        if (!modalConfig) return;
        
        setupModal(modalConfig);
        showModal();
    }
    
    // Open edit modal
    function openEditModal(submissionId, tableType) {
        const modalConfig = getModalConfig(tableType, 'edit');
        if (!modalConfig) return;
        
        // Load existing data
        $.ajax({
            url: spiderRewardsAdmin.ajaxurl,
            method: 'POST',
            data: {
                action: 'spider_get_submission',
                submission_id: submissionId,
                table_type: tableType,
                nonce: spiderRewardsAdmin.nonce
            },
            success: function(response) {
                if (response.success) {
                    setupModal(modalConfig, response.data.submission);
                    showModal();
                } else {
                    Swal.fire('Error', response.data.message || 'Failed to load submission data', 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'An error occurred while loading submission data', 'error');
            }
        });
    }
    
    // Confirm delete
    function confirmDelete(submissionId, tableType) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This action cannot be undone!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteSubmission(submissionId, tableType);
            }
        });
    }
    
    // Delete submission
    function deleteSubmission(submissionId, tableType) {
        $.ajax({
            url: spiderRewardsAdmin.ajaxurl,
            method: 'POST',
            data: {
                action: 'spider_delete_submission',
                submission_id: submissionId,
                table_type: tableType,
                nonce: spiderRewardsAdmin.nonce
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire('Deleted!', 'Submission has been deleted.', 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', response.data.message || 'Failed to delete submission', 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'An error occurred while deleting the submission', 'error');
            }
        });
    }
    
    // Get modal configuration based on table type
    function getModalConfig(tableType, action) {
        const configs = {
            video: {
                title: action === 'add' ? 'Add Video Submission' : 'Edit Video Submission',
                fields: [
                    { name: 'customer_name', label: 'Name', type: 'text', required: true },
                    { name: 'customer_email', label: 'Email', type: 'email', required: true },
                    { name: 'order_number', label: 'Order', type: 'number', required: true },
                    { name: 'content_link', label: 'Video URL', type: 'url', required: true },

					{ name: 'social_handle', label: 'Platform', type: 'select', required: true, options:Object.entries(spiderRewardsAdmin.platforms.video).map(([value, label]) => ({ value, label }))},
                    { name: 'status', label: 'Status', type: 'select', required: true, options: [
                        { value: 'pending', label: 'Pending' },
                        { value: 'approved', label: 'Approved' },
                        { value: 'rejected', label: 'Rejected' }
                    ]}
                ]
            },
            referral: {
                title: action === 'add' ? 'Add Referral Submission' : 'Edit Referral Submission',
                fields: [
                    { name: 'customer_name', label: 'Name', type: 'text', required: true },
                    { name: 'customer_email', label: 'Email', type: 'email', required: true },
                    { name: 'friend_info', label: 'Friend Info', type: 'text', required: true },
                    { name: 'status', label: 'Status', type: 'select', required: true, options: [
                        { value: 'pending', label: 'Pending' },
                        { value: 'approved', label: 'Approved' },
                        { value: 'rejected', label: 'Rejected' }
                    ]}
                ]
            },
            best: {
                title: action === 'add' ? 'Add Best Submission' : 'Edit Best Submission',
                fields: [
                    { name: 'customer_name', label: 'Name', type: 'text', required: true },
                    { name: 'customer_email', label: 'Email', type: 'email', required: true },
                    { name: 'order_number', label: 'Order', type: 'number', required: true },
                    { name: 'content_link', label: 'Video URL', type: 'url', required: true },

					{ name: 'social_handle', label: 'Platform', type: 'select', required: true,options:Object.entries(spiderRewardsAdmin.platforms.best).map(([value, label]) => ({ value, label }))},
                    { name: 'status', label: 'Status', type: 'select', required: true, options: [
                        { value: 'pending', label: 'Pending' },
                        { value: 'approved', label: 'Approved' },
                        { value: 'rejected', label: 'Rejected' }
                    ]}
                ]
            },
            review: {
                title: action === 'add' ? 'Add Review Submission' : 'Edit Review Submission',
                fields: [
                    { name: 'customer_name', label: 'Name', type: 'text', required: true },
                    { name: 'customer_email', label: 'Email', type: 'email', required: true },
 
                    { name: 'content_link', label: 'Video URL', type: 'url', required: true },

					{ name: 'social_handle', label: 'Platform', type: 'select', required: true, options:Object.entries(spiderRewardsAdmin.platforms.review).map(([value, label]) => ({ value, label }))},
                    { name: 'status', label: 'Status', type: 'select', required: true, options: [
                        { value: 'pending', label: 'Pending' },
                        { value: 'approved', label: 'Approved' },
                        { value: 'rejected', label: 'Rejected' }
                    ]}
                ]
            }
        };
        
        return configs[tableType] || null;
    }
    
    // Setup modal with configuration
    function setupModal(config, data = null) {
        const modal = $('#spider-rewards-modal');
        modal.find('.modal-title').text(config.title);
        
        // Build form fields
        let fieldsHtml = '';
		console.log(config,data);
        config.fields.forEach(field => {
            fieldsHtml += buildFieldHtml(field, data);
        });
        
        modal.find('.form-fields').html(fieldsHtml);
        
        // Store config for form submission
        modal.data('config', config);
        modal.data('submission-data', data);
    }
    
    // Build HTML for form field
    function buildFieldHtml(field, data) {
        const value = data ? (data[field.name] || '') : '';
        const required = field.required ? 'required' : '';
        
        let fieldHtml = `
            <div class="form-field">
                <label for="${field.name}">${field.label}${field.required ? ' *' : ''}</label>
        `;
        
        switch (field.type) {
            case 'textarea':
                fieldHtml += `<textarea id="${field.name}" name="${field.name}" ${required}>${value}</textarea>`;
                break;
            case 'select':
                fieldHtml += `<select id="${field.name}" name="${field.name}" ${required}>`;
                field.options.forEach(option => {
                    const selected = value === option.value ? 'selected' : '';
                    fieldHtml += `<option value="${option.value}" ${selected}>${option.label}</option>`;
                });
                fieldHtml += '</select>';
                break;
            default:
                const minMax = field.min !== undefined ? `min="${field.min}"` : '';
                const maxAttr = field.max !== undefined ? `max="${field.max}"` : '';
                fieldHtml += `<input type="${field.type}" id="${field.name}" name="${field.name}" value="${value}" ${required} ${minMax} ${maxAttr}>`;
        }
        
        fieldHtml += '</div>';
        return fieldHtml;
    }
    
    // Show modal
    function showModal() {
        const modal = $('#spider-rewards-modal');
        modal.addClass('active');
        currentModal = modal;
        
        // Focus first input
        setTimeout(() => {
            modal.find('input, textarea, select').first().focus();
        }, 100);
        
        // Prevent body scroll
        $('body').addClass('modal-open');
    }
    
    // Close modal
    function closeModal() {
        if (currentModal) {
            currentModal.removeClass('active');
            currentModal = null;
        }
        $('body').removeClass('modal-open');
    }
    
    // Submit modal form
    function submitModalForm() {
        const modal = $('#spider-rewards-modal');
        const form = modal.find('#modal-form');
        const config = modal.data('config');
        const existingData = modal.data('submission-data');
        
        // Validate form
        if (!form[0].checkValidity()) {
            form[0].reportValidity();
            return;
        }
        
        // Collect form data
        const formData = {};
        form.find('input, textarea, select').each(function() {
            const field = $(this);
            formData[field.attr('name')] = field.val();
        });
        
        // Determine action
        const action = existingData ? 'spider_save_submission' : 'spider_save_submission';
        const submitData = {
            action: action,
            table_type: getCurrentTableType(),
            nonce: spiderRewardsAdmin.nonce,
            ...formData
        };
        
        if (existingData) {
            submitData.submission_id = existingData.id;
        }
        
        // Show loading
        const submitButton = form.find('button[type="submit"]');
        const originalText = submitButton.text();
        submitButton.text('Saving...').prop('disabled', true);
        
        // Submit via AJAX
        $.ajax({
            url: spiderRewardsAdmin.ajaxurl,
            method: 'POST',
            data: submitData,
            success: function(response) {
				console.log(response);
                if (response.success) {
					closeModal();
                    Swal.fire('Success!', response.data.message || 'Submission saved successfully', 'success').then(() => {
                        
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', response.data.message || 'Failed to save submission', 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'An error occurred while saving the submission', 'error');
            },
            complete: function() {
                submitButton.text(originalText).prop('disabled', false);
            }
        });
    }
    
    // Get current table type from URL or page context
    function getCurrentTableType() {
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab');
        
        if (tab) {
            return tab;
        }

		const tableType = $('#table-type').val();
		return tableType || 'video';
        
    }
    
    // Keyboard shortcuts for modal
    $(document).on('keydown', function(e) {
        if (currentModal) {
            // Escape to close modal
            if (e.key === 'Escape') {
                closeModal();
            }
            // Enter to submit form (if not in textarea)
            if (e.key === 'Enter' && !$(e.target).is('textarea')) {
                e.preventDefault();
                $('#modal-form').submit();
            }
        }
        
        // Ctrl/Cmd + R for refresh
        if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
            e.preventDefault();
            location.reload();
        }
        
        // Escape to clear search
        if (e.key === 'Escape') {
            $('#submission-search').val('').trigger('input');
            $('#status-filter').val('').trigger('change');
        }
    });
    
    // Enhanced table row actions
    $('.wp-list-table').on('mouseenter', 'tr', function() {
        $(this).find('.row-actions').addClass('visible');
    }).on('mouseleave', 'tr', function() {
        $(this).find('.row-actions').removeClass('visible');
    });
    
    // Table row selection for bulk actions
    $('.wp-list-table').on('change', 'input[type="checkbox"]', function() {
        updateBulkActionButtons();
    });
    
    function updateBulkActionButtons() {
        const checkedItems = $('.wp-list-table input[type="checkbox"]:checked').length;
        const bulkActions = $('.bulkactions select');
        
        if (checkedItems > 0) {
            bulkActions.prop('disabled', false);
            $('.bulk-action-count').text(`${checkedItems} selected`);
        } else {
            bulkActions.prop('disabled', true);
            $('.bulk-action-count').text('');
        }
    }
});
