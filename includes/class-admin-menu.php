<?php
/**
 * Admin Menu Class
 *
 * Handles the admin interface for the Spider Rewards plugin.
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SpiderRewards_Admin_Menu {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'addAdminMenu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueueAdminScripts' ) );
		add_action( 'wp_ajax_update_submission_status', array( $this, 'updateSubmissionStatus' ) );
	}

	/**
	 * Add admin menu
	 */
	public function addAdminMenu() {
		add_menu_page(
			__( 'Rewards', 'spider-rewards' ),
			__( 'Rewards', 'spider-rewards' ),
			'manage_options',
			'spider-rewards',
			array( $this, 'displayMainPage' ),
			'dashicons-star-filled',
			30
		);

		add_submenu_page(
			'spider-rewards',
			__( 'Unboxing Videos', 'spider-rewards' ),
			__( 'Unboxing Videos', 'spider-rewards' ),
			'manage_options',
			'spider-rewards-videos',
			array( $this, 'displayVideosPage' )
		);

		add_submenu_page(
			'spider-rewards',
			__( 'Friend Referrals', 'spider-rewards' ),
			__( 'Friend Referrals', 'spider-rewards' ),
			'manage_options',
			'spider-rewards-referrals',
			array( $this, 'displayReferralsPage' )
		);

		add_submenu_page(
			'spider-rewards',
			__( 'Best Fit Contests', 'spider-rewards' ),
			__( 'Best Fit Contests', 'spider-rewards' ),
			'manage_options',
			'spider-rewards-best',
			array( $this, 'displayBestPage' )
		);

		add_submenu_page(
			'spider-rewards',
			__( 'Reviews', 'spider-rewards' ),
			__( 'Reviews', 'spider-rewards' ),
			'manage_options',
			'spider-rewards-reviews',
			array( $this, 'displayReviewsPage' )
		);

		add_submenu_page(
			'spider-rewards',
			__( 'Settings', 'spider-rewards' ),
			__( 'Settings', 'spider-rewards' ),
			'manage_options',
			'spider-rewards-settings',
			array( $this, 'displaySettingsPage' )
		);
	}

	/**
	 * Enqueue admin scripts
	 */
	public function enqueueAdminScripts( $hook ) {
		if ( strpos( $hook, 'spider-rewards' ) !== false ) {
			wp_enqueue_script(
				'spider-rewards-admin',
				SPIDER_REWARDS_PLUGIN_URL . 'assets/js/admin.js',
				array( 'jquery' ),
				SPIDER_REWARDS_VERSION,
				true
			);

			wp_localize_script(
				'spider-rewards-admin',
				'spiderRewardsAdmin',
				array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'nonce'   => wp_create_nonce( 'spider_rewards_admin_nonce' ),
				)
			);

			wp_enqueue_style(
				'spider-rewards-admin',
				SPIDER_REWARDS_PLUGIN_URL . 'assets/css/admin.css',
				array(),
				SPIDER_REWARDS_VERSION
			);
		}
	}

	/**
	 * Display main page
	 */
	public function displayMainPage() {
		$recent_submissions = SpiderRewards_Database_Manager::getRecentRewardSubmissions( 20 );
		include SPIDER_REWARDS_PLUGIN_DIR . 'templates/admin-main.php';
	}

	/**
	 * Display videos page
	 */
	public function displayVideosPage() {
		$list_table = new SpiderRewards_Video_Submissions_Table();
		$list_table->prepare_items();
		include SPIDER_REWARDS_PLUGIN_DIR . 'templates/admin-videos.php';
	}

	/**
	 * Display referrals page
	 */
	public function displayReferralsPage() {
		$list_table = new SpiderRewards_Referral_Submissions_Table();
		$list_table->prepare_items();
		include SPIDER_REWARDS_PLUGIN_DIR . 'templates/admin-referrals.php';
	}

	/**
	 * Display best fit page
	 */
	public function displayBestPage() {
		$list_table = new SpiderRewards_Best_Submissions_Table();
		$list_table->prepare_items();
		include SPIDER_REWARDS_PLUGIN_DIR . 'templates/admin-best.php';
	}

	/**
	 * Display reviews page
	 */
	public function displayReviewsPage() {
		$list_table = new SpiderRewards_Review_Submissions_Table();
		$list_table->prepare_items();
		include SPIDER_REWARDS_PLUGIN_DIR . 'templates/admin-reviews.php';
	}

	/**
	 * Display settings page
	 */
	public function displaySettingsPage() {
		include SPIDER_REWARDS_PLUGIN_DIR . 'templates/admin-settings.php';
	}

	/**
	 * Update submission status via AJAX
	 */
	public function updateSubmissionStatus() {
		check_ajax_referer( 'spider_rewards_admin_nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Permission denied', 'spider-rewards' ) );
		}

		$submission_id = intval( $_POST['submission_id'] );
		$status        = sanitize_text_field( $_POST['status'] );
		$table_type    = sanitize_text_field( $_POST['table_type'] );

		global $wpdb;

		$table_name = $wpdb->prefix . 'spiderawards_' . $table_type . '_submissions';

		$result = $wpdb->update(
			$table_name,
			array( 'status' => $status ),
			array( 'id' => $submission_id ),
			array( '%s' ),
			array( '%d' )
		);

		if ( $result !== false ) {
			wp_send_json_success( array( 'message' => __( 'Status updated successfully', 'spider-rewards' ) ) );
		} else {
			wp_send_json_error( array( 'message' => __( 'Failed to update status', 'spider-rewards' ) ) );
		}
	}
}
