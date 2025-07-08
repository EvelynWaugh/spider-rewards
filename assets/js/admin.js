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
});
