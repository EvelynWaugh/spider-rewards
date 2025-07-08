<?php
/**
 * Database Manager Class
 *
 * Handles database table creation and management for the Spider Rewards plugin.
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SpiderRewards_Database_Manager {

	/**
	 * Create all required database tables
	 */
	public function createTables() {
		$this->createVideoSubmissionsTable();
		$this->createReferralSubmissionsTable();
		$this->createBestSubmissionsTable();
		$this->createReviewSubmissionsTable();
	}

	/**
	 * Create video submissions table
	 */
	private function createVideoSubmissionsTable() {
		global $wpdb;

		$table_name = $wpdb->prefix . 'spiderawards_video_submissions';

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            customer_name varchar(255) NOT NULL,
            customer_email varchar(255) NOT NULL,
            order_number varchar(100) NOT NULL,
            content_link text NOT NULL,
            social_handle varchar(255) NOT NULL,
            status enum('pending', 'approved', 'rejected') DEFAULT 'pending',
            submission_date datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY customer_email (customer_email),
            KEY status (status),
            KEY submission_date (submission_date)
        ) $charset_collate;";

		$this->executeSQL( $sql );
	}

	/**
	 * Create referral submissions table
	 */
	private function createReferralSubmissionsTable() {
		global $wpdb;

		$table_name = $wpdb->prefix . 'spiderawards_referral_submissions';

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            customer_name varchar(255) NOT NULL,
            customer_email varchar(255) NOT NULL,
            friend_info text NOT NULL,
            status enum('pending', 'approved', 'rejected') DEFAULT 'pending',
            submission_date datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY customer_email (customer_email),
            KEY status (status),
            KEY submission_date (submission_date)
        ) $charset_collate;";

		$this->executeSQL( $sql );
	}

	/**
	 * Create best submissions table
	 */
	private function createBestSubmissionsTable() {
		global $wpdb;

		$table_name = $wpdb->prefix . 'spiderawards_best_submissions';

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            customer_name varchar(255) NOT NULL,
            customer_email varchar(255) NOT NULL,
            social_handle varchar(255) NOT NULL,
            content_link text NOT NULL,
            status enum('pending', 'approved', 'rejected') DEFAULT 'pending',
            submission_date datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY customer_email (customer_email),
            KEY status (status),
            KEY submission_date (submission_date)
        ) $charset_collate;";

		$this->executeSQL( $sql );
	}

	/**
	 * Create review submissions table
	 */
	private function createReviewSubmissionsTable() {
		global $wpdb;

		$table_name = $wpdb->prefix . 'spiderawards_review_submissions';

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            customer_name varchar(255) NOT NULL,
            customer_email varchar(255) NOT NULL,
            content_link text NOT NULL,
            social_handle varchar(255) NOT NULL,
            status enum('pending', 'approved', 'rejected') DEFAULT 'pending',
            submission_date datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY customer_email (customer_email),
            KEY status (status),
            KEY submission_date (submission_date)
        ) $charset_collate;";

		$this->executeSQL( $sql );
	}

	/**
	 * Execute SQL statement
	 */
	private function executeSQL( $sql ) {
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}

	/**
	 * Get recent reward submissions
	 */
	public static function getRecentRewardSubmissions( $limit = 10 ) {
		global $wpdb;

		$results = array();

		// Get video submissions
		$video_table   = $wpdb->prefix . 'spiderawards_video_submissions';
		$video_results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT customer_name, social_handle, submission_date, 'video' as type 
             FROM $video_table 
             WHERE status = 'approved' 
             ORDER BY submission_date DESC 
             LIMIT %d",
				$limit
			)
		);

		// Get referral submissions
		$referral_table   = $wpdb->prefix . 'spiderawards_referral_submissions';
		$referral_results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT customer_name, '' as social_handle, submission_date, 'referral' as type 
             FROM $referral_table 
             WHERE status = 'approved' 
             ORDER BY submission_date DESC 
             LIMIT %d",
				$limit
			)
		);

		// Get best submissions
		$best_table   = $wpdb->prefix . 'spiderawards_best_submissions';
		$best_results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT customer_name, social_handle, submission_date, 'best_fit' as type 
             FROM $best_table 
             WHERE status = 'approved' 
             ORDER BY submission_date DESC 
             LIMIT %d",
				$limit
			)
		);

		// Get review submissions
		$review_table   = $wpdb->prefix . 'spiderawards_review_submissions';
		$review_results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT customer_name, social_handle, submission_date, 'review' as type 
             FROM $review_table 
             WHERE status = 'approved' 
             ORDER BY submission_date DESC 
             LIMIT %d",
				$limit
			)
		);

		// Combine all results
		$all_results = array_merge( $video_results, $referral_results, $best_results, $review_results );

		// Sort by submission date
		usort(
			$all_results,
			function ( $a, $b ) {
				return strtotime( $b->submission_date ) - strtotime( $a->submission_date );
			}
		);

		// Return limited results
		return array_slice( $all_results, 0, $limit );
	}
}
