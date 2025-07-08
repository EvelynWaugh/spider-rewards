/**
 * Spider Rewards Frontend JavaScript
 */

jQuery(document).ready(function($) {
    'use strict';
    
    // Form submission handler
    $('.reward-form').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const formType = form.data('form-type');
        const submitBtn = form.find('.submit-btn');
        const originalText = submitBtn.text();
        
        // Clear previous errors
        form.find('.field-error').remove();
        form.find('input').removeClass('error');
        
        // Get form data
        const formData = {};
        form.find('input').each(function() {
            formData[$(this).attr('name')] = $(this).val();
        });
        
        // Client-side validation
        const validationErrors = validateForm(formData, formType);
        if (Object.keys(validationErrors).length > 0) {
            displayValidationErrors(form, validationErrors);
            return;
        }
        
        // Show loading state
        submitBtn.addClass('loading').prop('disabled', true);
        
        // Submit form via AJAX
        $.ajax({
            url: spiderRewards.apiUrl + 'submit-' + formType,
            method: 'POST',
            data: formData,
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-WP-Nonce', spiderRewards.nonce);
            },
            success: function(response) {
                showModal('success', response.message);
                form[0].reset();
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                if (response && response.data && response.data.errors) {
                    displayValidationErrors(form, response.data.errors);
                } else {
                    showModal('error', response.message || 'An error occurred. Please try again.');
                }
            },
            complete: function() {
                submitBtn.removeClass('loading').prop('disabled', false);
            }
        });
    });
    
    // Client-side validation
    function validateForm(data, formType) {
        const errors = {};
        
        // Common validations
        if (!data.customer_name || data.customer_name.trim() === '') {
            errors.customer_name = 'Name is required';
        }
        
        if (!data.customer_email || !isValidEmail(data.customer_email)) {
            errors.customer_email = 'Valid email address is required';
        }
        
        // Form-specific validations
        switch (formType) {
            case 'video':
                if (!data.order_number || data.order_number.trim() === '') {
                    errors.order_number = 'Order number is required';
                }
                if (!data.content_link || !isValidUrl(data.content_link)) {
                    errors.content_link = 'Valid video link is required';
                }
                if (!data.social_handle || data.social_handle.trim() === '') {
                    errors.social_handle = 'Social handle is required';
                }
                break;
                
            case 'referral':
                if (!data.friend_info || data.friend_info.trim() === '') {
                    errors.friend_info = 'Friend information is required';
                }
                break;
                
            case 'best':
                if (!data.social_handle || data.social_handle.trim() === '') {
                    errors.social_handle = 'Social handle is required';
                }
                if (!data.content_link || !isValidUrl(data.content_link)) {
                    errors.content_link = 'Valid content link is required';
                }
                break;
                
            case 'review':
                if (!data.content_link || !isValidUrl(data.content_link)) {
                    errors.content_link = 'Valid review link is required';
                }
                if (!data.social_handle || data.social_handle.trim() === '') {
                    errors.social_handle = 'Social handle is required';
                }
                break;
        }
        
        return errors;
    }
    
    // Display validation errors
    function displayValidationErrors(form, errors) {
        for (const field in errors) {
            const input = form.find(`input[name="${field}"]`);
            input.addClass('error');
            input.after(`<div class="field-error">${errors[field]}</div>`);
        }
        
        // Scroll to first error
        const firstError = form.find('input.error').first();
        if (firstError.length) {
            $('html, body').animate({
                scrollTop: firstError.offset().top - 100
            }, 300);
        }
    }
    
    // Show modal
    function showModal(type, message) {
        const modal = $('#spider-rewards-modal');
        const modalIcon = modal.find('.modal-icon');
        const modalMessage = modal.find('.modal-message');
        
        modalIcon.removeClass('success error').addClass(type);
        modalMessage.text(message);
        modal.fadeIn(300);
        
        // Auto-hide success modals after 3 seconds
        if (type === 'success') {
            setTimeout(function() {
                modal.fadeOut(300);
            }, 3000);
        }
    }
    
    // Close modal
    $(document).on('click', '.close, .modal', function(e) {
        if (e.target === this) {
            $('#spider-rewards-modal').fadeOut(300);
        }
    });
    
    // ESC key to close modal
    $(document).on('keydown', function(e) {
        if (e.keyCode === 27) {
            $('#spider-rewards-modal').fadeOut(300);
        }
    });
    
    // Utility functions
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    function isValidUrl(url) {
        try {
            new URL(url);
            return true;
        } catch {
            return false;
        }
    }
    
    // Real-time validation
    $('.reward-form input').on('blur', function() {
        const input = $(this);
        const form = input.closest('.reward-form');
        const formType = form.data('form-type');
        const fieldName = input.attr('name');
        const value = input.val();
        
        // Remove previous error
        input.removeClass('error');
        input.siblings('.field-error').remove();
        
        // Validate field
        let error = '';
        
        switch (fieldName) {
            case 'customer_name':
                if (!value.trim()) {
                    error = 'Name is required';
                }
                break;
                
            case 'customer_email':
                if (!value.trim()) {
                    error = 'Email is required';
                } else if (!isValidEmail(value)) {
                    error = 'Valid email address is required';
                }
                break;
                
            case 'order_number':
                if (!value.trim()) {
                    error = 'Order number is required';
                }
                break;
                
            case 'content_link':
                if (!value.trim()) {
                    error = 'Link is required';
                } else if (!isValidUrl(value)) {
                    error = 'Valid URL is required';
                }
                break;
                
            case 'social_handle':
                if (!value.trim()) {
                    error = 'Social handle is required';
                }
                break;
                
            case 'friend_info':
                if (!value.trim()) {
                    error = 'Friend information is required';
                }
                break;
        }
        
        if (error) {
            input.addClass('error');
            input.after(`<div class="field-error">${error}</div>`);
        }
    });
    
    // Auto-format social handles
    $('.reward-form input[name="social_handle"]').on('input', function() {
        let value = $(this).val();
        if (value && !value.startsWith('@')) {
            $(this).val('@' + value);
        }
    });
    
    // Character limits and formatting
    $('.reward-form input[name="customer_name"]').on('input', function() {
        let value = $(this).val();
        // Capitalize first letter of each word
        value = value.replace(/\b\w/g, function(char) {
            return char.toUpperCase();
        });
        $(this).val(value);
    });
});
