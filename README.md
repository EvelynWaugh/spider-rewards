# Spider Rewards Plugin

A complete WordPress plugin for a rewards system with WooCommerce integration.

## Features

### 🎯 Core Functionality
- **Video Submissions**: Users can submit unboxing/reaction videos for $30 PayPal rewards
- **Friend Referrals**: Invite friends for $25 store credit rewards for both parties
- **Best Fit Contest**: Monthly contest with free outfit prizes
- **Review Submissions**: Public reviews rewarded with 30% discount codes

### 🛠 Technical Features
- **Modern PHP Architecture**: MVC pattern with proper separation of concerns
- **REST API Integration**: AJAX form handling with comprehensive validation
- **Custom Database Tables**: Optimized schemas for each submission type
- **Admin Management**: Full WordPress admin interface with WP_List_Table
- **Real-time Updates**: Dynamic recent submissions display

## Installation

1. Upload the `spider-rewards` folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Navigate to 'Rewards' in the admin menu to configure settings

## Database Tables

The plugin creates four custom tables:

- `wp_spiderawards_video_submissions`
- `wp_spiderawards_referral_submissions` 
- `wp_spiderawards_best_submissions`
- `wp_spiderawards_review_submissions`

## Shortcodes

### Display Reward Forms
```
[spider_rewards_forms]
```

### Display Recent Submissions
```
[spider_rewards_recent limit="10"]
```

## REST API Endpoints

- `POST /wp-json/spider-rewards/v1/submit-video`
- `POST /wp-json/spider-rewards/v1/submit-referral`
- `POST /wp-json/spider-rewards/v1/submit-best`
- `POST /wp-json/spider-rewards/v1/submit-review`

## Admin Features

### Dashboard
- Statistics overview (total submissions, pending reviews, approved today)
- Recent submissions table with type indicators
- Quick action buttons for each submission type

### Management Pages
- **Video Submissions**: Manage unboxing video submissions
- **Referrals**: Handle friend referral submissions
- **Best Fit**: Contest entry management with monthly selection
- **Reviews**: Public review submission oversight

### Settings
- Configurable reward amounts
- PayPal integration settings
- Discount percentage configuration

## File Structure

```
spider-rewards/
├── includes/
│   ├── class-database-manager.php
│   ├── class-admin-menu.php
│   ├── class-rest-controller.php
│   ├── class-frontend.php
│   ├── class-settings.php
│   └── list-tables/
│       ├── class-video-submissions-table.php
│       ├── class-referral-submissions-table.php
│       ├── class-best-submissions-table.php
│       └── class-review-submissions-table.php
├── assets/
│   ├── css/
│   │   ├── rewards.css
│   │   └── admin.css
│   └── js/
│       ├── rewards.js
│       └── admin.js
├── templates/
│   ├── reward-forms.php
│   ├── recent-submissions.php
│   ├── admin-main.php
│   ├── admin-videos.php
│   ├── admin-referrals.php
│   ├── admin-best.php
│   ├── admin-reviews.php
│   └── admin-settings.php
└── spider-rewards.php
```

## Usage Examples

### Frontend Implementation
Add the reward forms to any page or post:
```
[spider_rewards_forms]
```

Show recent activity:
```
[spider_rewards_recent limit="5"]
```

### Admin Management
1. Navigate to **Rewards** in WordPress admin
2. Review submissions in respective sections
3. Approve/reject submissions with one click
4. Configure settings for reward amounts and PayPal details

## Technical Details

### Security
- Nonce verification for all AJAX requests
- Input sanitization and validation
- SQL injection prevention with prepared statements
- XSS protection with proper escaping

### Performance
- Optimized database queries with proper indexing
- Minimal asset loading (only when needed)
- Efficient AJAX handling without page reloads

### Compatibility
- WordPress 5.0+
- PHP 8.1+
- WooCommerce integration ready
- Modern browser support

## Development

### Adding New Submission Types
1. Create database table in `class-database-manager.php`
2. Add REST endpoint in `class-rest-controller.php`
3. Create list table class in `list-tables/`
4. Add admin menu item in `class-admin-menu.php`
5. Create admin template in `templates/`

### Customization
- Modify CSS files for styling changes
- Extend REST controller for additional validation
- Add custom fields to database tables as needed

## Support

For technical support or customization requests, please refer to the plugin documentation or contact the development team.

## Changelog

### 1.0.0
- Initial release
- Complete rewards system implementation
- Admin management interface
- REST API endpoints
- Frontend form handling
- Database table creation
- Settings configuration
