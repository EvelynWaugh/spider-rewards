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
		add_action( 'wp_ajax_spider_delete_submission', array( $this, 'deleteSubmission' ) );
		add_action( 'wp_ajax_spider_save_submission', array( $this, 'saveSubmission' ) );
		add_action( 'wp_ajax_spider_get_submission', array( $this, 'getSubmission' ) );

		add_filter( 'theme_page_templates', array( $this, 'theme_page_templates' ) );
		add_filter( 'page_template', array( $this, 'page_templates' ) );
		add_filter( 'template_include', array( $this, 'page_templates' ), 99 );
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
			// Enqueue SweetAlert2
			wp_enqueue_script(
				'sweetalert2',
				'https://cdn.jsdelivr.net/npm/sweetalert2@11',
				array(),
				'11.0.0',
				true
			);

			wp_enqueue_script(
				'spider-rewards-admin',
				SPIDER_REWARDS_PLUGIN_URL . 'assets/js/admin.js',
				array( 'jquery', 'sweetalert2' ),
				SPIDER_REWARDS_VERSION,
				true
			);

			wp_localize_script(
				'spider-rewards-admin',
				'spiderRewardsAdmin',
				array(
					'ajaxurl'        => admin_url( 'admin-ajax.php' ),
					'nonce'          => wp_create_nonce( 'spider_rewards_admin_nonce' ),
					'edit_url'       => admin_url( 'admin.php' ),
					'platforms'      => SpiderRewards\get_form_platform_options(),
					'delete_confirm' => __( 'Are you sure you want to delete this submission?', 'spider-rewards' ),
					'delete_success' => __( 'Submission deleted successfully!', 'spider-rewards' ),
					'update_success' => __( 'Submission updated successfully!', 'spider-rewards' ),
					'save_success'   => __( 'Submission saved successfully!', 'spider-rewards' ),
					'error_message'  => __( 'An error occurred. Please try again.', 'spider-rewards' ),
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

	/**
	 * Delete submission via AJAX
	 */
	public function deleteSubmission() {
		check_ajax_referer( 'spider_rewards_admin_nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Permission denied', 'spider-rewards' ) ) );
		}

		$submission_id = isset( $_POST['submission_id'] ) ? intval( $_POST['submission_id'] ) : 0;
		$table_type    = isset( $_POST['table_type'] ) ? sanitize_text_field( wp_unslash( $_POST['table_type'] ) ) : '';

		if ( ! $submission_id || ! $table_type ) {
			wp_send_json_error( array( 'message' => __( 'Invalid submission data', 'spider-rewards' ) ) );
		}

		global $wpdb;
		$table_name = $wpdb->prefix . 'spiderawards_' . $table_type . '_submissions';

		$result = $wpdb->delete(
			$table_name,
			array( 'id' => $submission_id ),
			array( '%d' )
		);

		if ( $result !== false ) {
			wp_send_json_success( array( 'message' => __( 'Submission deleted successfully', 'spider-rewards' ) ) );
		} else {
			wp_send_json_error( array( 'message' => __( 'Failed to delete submission', 'spider-rewards' ) ) );
		}
	}

	/**
	 * Get submission data via AJAX
	 */
	public function getSubmission() {
		check_ajax_referer( 'spider_rewards_admin_nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Permission denied', 'spider-rewards' ) ) );
		}

		$submission_id = isset( $_POST['submission_id'] ) ? intval( $_POST['submission_id'] ) : 0;
		$table_type    = isset( $_POST['table_type'] ) ? sanitize_text_field( wp_unslash( $_POST['table_type'] ) ) : '';

		if ( ! $submission_id || ! $table_type ) {
			wp_send_json_error( array( 'message' => __( 'Invalid submission data', 'spider-rewards' ) ) );
		}

		global $wpdb;
		$table_name = $wpdb->prefix . 'spiderawards_' . $table_type . '_submissions';

		$submission = $wpdb->get_row(
			$wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", $submission_id ),
			ARRAY_A
		);

		if ( $submission ) {
			wp_send_json_success( array( 'submission' => $submission ) );
		} else {
			wp_send_json_error( array( 'message' => __( 'Submission not found', 'spider-rewards' ) ) );
		}
	}

	/**
	 * Save submission via AJAX
	 */
	public function saveSubmission() {
		check_ajax_referer( 'spider_rewards_admin_nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Permission denied', 'spider-rewards' ) ) );
		}

		$submission_id = isset( $_POST['submission_id'] ) ? intval( $_POST['submission_id'] ) : 0;
		$table_type    = isset( $_POST['table_type'] ) ? sanitize_text_field( wp_unslash( $_POST['table_type'] ) ) : '';

		if ( ! $table_type ) {
			wp_send_json_error( array( 'message' => __( 'Invalid table type', 'spider-rewards' ) ) );
		}

		// Get form data
		$customer_name  = isset( $_POST['customer_name'] ) ? sanitize_text_field( wp_unslash( $_POST['customer_name'] ) ) : '';
		$customer_email = isset( $_POST['customer_email'] ) ? sanitize_email( wp_unslash( $_POST['customer_email'] ) ) : '';
		$status         = isset( $_POST['status'] ) ? sanitize_text_field( wp_unslash( $_POST['status'] ) ) : 'pending';

		// Validate required fields
		if ( ! $customer_name || ! $customer_email ) {
			wp_send_json_error( array( 'message' => __( 'Name and email are required', 'spider-rewards' ) ) );
		}

		global $wpdb;
		$table_name = $wpdb->prefix . 'spiderawards_' . $table_type . '_submissions';

		// Prepare data based on table type
		$data = array(
			'customer_name'  => $customer_name,
			'customer_email' => $customer_email,
			'status'         => $status,
		);

		// Add type-specific fields
		switch ( $table_type ) {
			case 'video':
				$data['order_number']  = isset( $_POST['order_number'] ) ? sanitize_text_field( wp_unslash( $_POST['order_number'] ) ) : '';
				$data['content_link']  = isset( $_POST['content_link'] ) ? esc_url_raw( wp_unslash( $_POST['content_link'] ) ) : '';
				$data['social_handle'] = isset( $_POST['social_handle'] ) ? sanitize_text_field( wp_unslash( $_POST['social_handle'] ) ) : '';
				break;
			case 'referral':
				$data['friend_info'] = isset( $_POST['friend_info'] ) ? sanitize_text_field( wp_unslash( $_POST['friend_info'] ) ) : '';
				break;
			case 'best':
				$data['social_handle'] = isset( $_POST['social_handle'] ) ? sanitize_text_field( wp_unslash( $_POST['social_handle'] ) ) : '';
				$data['content_link']  = isset( $_POST['content_link'] ) ? esc_url_raw( wp_unslash( $_POST['content_link'] ) ) : '';
				break;
			case 'review':
				$data['content_link']  = isset( $_POST['content_link'] ) ? esc_url_raw( wp_unslash( $_POST['content_link'] ) ) : '';
				$data['social_handle'] = isset( $_POST['social_handle'] ) ? sanitize_text_field( wp_unslash( $_POST['social_handle'] ) ) : '';
				break;
		}

		$format = array_fill( 0, count( $data ), '%s' );

		if ( $submission_id ) {
			// Update existing submission
			$result  = $wpdb->update(
				$table_name,
				$data,
				array( 'id' => $submission_id ),
				$format,
				array( '%d' )
			);
			$message = __( 'Submission updated successfully', 'spider-rewards' );
		} else {
			// Add submission_date for new submissions
			$data['submission_date'] = current_time( 'mysql' );
			$format[]                = '%s';

			// Insert new submission
			$result  = $wpdb->insert( $table_name, $data, $format );
			$message = __( 'Submission created successfully', 'spider-rewards' );
		}

		if ( $result !== false ) {
			wp_send_json_success( array( 'message' => $message ) );
		} else {
			wp_send_json_error( array( 'message' => __( 'Failed to save submission', 'spider-rewards' ) ) );
		}
	}


	public function theme_page_templates( $templates ) {

		if ( file_exists( SPIDER_REWARDS_PLUGIN_DIR . 'templates/pages/rewards.php' ) ) {
			$templates['rewards.php'] = __( 'Spider Rewards Page', 'spider-rewards' );
		}

		return $templates;
	}


	public function page_templates( $page_template ) {

		if ( is_page_template( 'rewards.php' ) ) {
			$page_template = SPIDER_REWARDS_PLUGIN_DIR . 'templates/pages/rewards.php';
		}

		return $page_template;
	}
}
