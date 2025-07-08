# Rewards system
Build a complete WordPress plugin (modern PHP style) for a rewards system with WooCommerce integration.

## Core Features

1. Custom Database Tables
Create separate custom database tables for each of the four reward types to store their unique submission data. The table schemas should be optimized for the fields present in each form:

wp_spiderawards_video_submissions: To store data from the "Post an Unboxing or Reaction Video" form. It should include columns for id, customer_name, customer_email, order_number, content_link, social_handle, status (e.g., 'pending', 'approved', 'rejected'), and submission_date. The goal: 'Once your video is verified, you’ll receive $30 via PayPal within 24 hours. Make sure your PayPal email matches the one you submitted.'

wp_spiderawards_referral_submissions: To store data from the "Invite a Friend" form. It should include columns for id, customer_name, customer_email, friend_info(email or order_number), status, and submission_date. The goal: 'As soon as your friend’s order is verified, you’ll both get $25 in store credit delivered to your email as a discount code.'

wp_spiderawards_best_submissions: To store data from the "Best Fit of the Month" form. It should include columns for id, customer_name, customer_email, social_handle, content_link, status, and submission_date. The goal: 'We select one winner every month. The winner receives a free hoodie or outfit from our current drop — shipped at no cost.'

wp_spiderawards_review_submissions: To store data from the "Leave a Public Review" form. It should include columns for id, customer_name, customer_email, content_link, social_handle, status, and submission_date. The goal: 'Once your review is verified, we’ll email you a 30% off discount code valid for your next order.'

Include the necessary PHP code to create these tables upon plugin activation.

2. AJAX Form Handling
The user experience should be seamless, without any page reloads.
JavaScript: Write the necessary JavaScript/jQuery to intercept the form submission, perform basic client-side validation (e.g., checking for empty fields, valid email/URL formats), send it to the server.

Rest Controller for each form will be responsible for:
- Verifying the nonce for security.
- Performing comprehensive server-side validation for all submitted data.
- Sanitizing all inputs before processing.
- Inserting the validated data into the appropriate custom database table.
- Returning a JSON response indicating success or failure with a clear message.
- Show success/fail popup or errors for each invalid field

3. Admin Management Interface
Develop a dedicated admin area within the WordPress dashboard for managing the rewards system. This should include:

A top-level menu item titled "Rewards".

Sub-menu pages for each submission type: "Unboxing Videos," "Friend Referrals," "Best Fit Contests," and "Reviews."

Each sub-menu page should display a WP_List_Table of the submissions from its corresponding custom table. The tables should be sortable and include all relevant data from the submission.

Implement the ability for an administrator to update the status of each submission (e.g., from 'pending' to 'approved' or 'rejected') directly from the list table.

4. Dynamic "Recent Submissions"
Replace the hardcoded "Recent Submissions" section with a dynamic implementation that queries the custom database tables.

Create a function get_recent_reward_submissions($limit) that fetches the latest $limit approved submissions from all four custom tables.

The function should return a unified array of recent activities that can be displayed on the frontend, showing the submitter's name/handle and the action they took.

5. Settings and Options
Create a settings page under the "Rewards" admin menu. This ensures that non-developers can easily update values without touching the code.


