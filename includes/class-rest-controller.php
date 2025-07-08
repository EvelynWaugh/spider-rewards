<?php
/**
 * REST Controller Class
 *
 * Handles REST API endpoints for form submissions.
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SpiderRewards_REST_Controller {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'registerRoutes' ) );
	}

	/**
	 * Register REST routes
	 */
	public function registerRoutes() {
		register_rest_route(
			'spider-rewards/v1',
			'/submit-video',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'submitVideo' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			'spider-rewards/v1',
			'/submit-referral',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'submitReferral' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			'spider-rewards/v1',
			'/submit-best',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'submitBest' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			'spider-rewards/v1',
			'/submit-review',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'submitReview' ),
				'permission_callback' => '__return_true',
			)
		);
	}

	/**
	 * Submit video form
	 */
	public function submitVideo( $request ) {
		// Verify nonce
		$nonce = $request->get_header( 'X-WP-Nonce' );
		if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
			return new WP_Error( 'invalid_nonce', 'Invalid nonce', array( 'status' => 403 ) );
		}

		// Get form data
		$customer_name  = sanitize_text_field( $request->get_param( 'customer_name' ) );
		$customer_email = sanitize_email( $request->get_param( 'customer_email' ) );
		$order_number   = sanitize_text_field( $request->get_param( 'order_number' ) );
		$content_link   = esc_url_raw( $request->get_param( 'content_link' ) );
		$social_handle  = sanitize_text_field( $request->get_param( 'social_handle' ) );

		// Validate data
		$validation = $this->validateVideoData( $customer_name, $customer_email, $order_number, $content_link, $social_handle );
		if ( is_wp_error( $validation ) ) {
			return $validation;
		}

		// Insert into database
		global $wpdb;
		$table_name = $wpdb->prefix . 'spiderawards_video_submissions';

		$result = $wpdb->insert(
			$table_name,
			array(
				'customer_name'  => $customer_name,
				'customer_email' => $customer_email,
				'order_number'   => $order_number,
				'content_link'   => $content_link,
				'social_handle'  => $social_handle,
				'status'         => 'pending',
			),
			array( '%s', '%s', '%s', '%s', '%s', '%s' )
		);

		if ( $result ) {
			return new WP_REST_Response(
				array(
					'success' => true,
					'message' => __( 'Video submission received! We will review it within 24 hours.', 'spider-rewards' ),
				),
				200
			);
		} else {
			return new WP_Error( 'submission_failed', 'Failed to submit video', array( 'status' => 500 ) );
		}
	}

	/**
	 * Submit referral form
	 */
	public function submitReferral( $request ) {
		// Verify nonce
		$nonce = $request->get_header( 'X-WP-Nonce' );
		if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
			return new WP_Error( 'invalid_nonce', 'Invalid nonce', array( 'status' => 403 ) );
		}

		// Get form data
		$customer_name  = sanitize_text_field( $request->get_param( 'customer_name' ) );
		$customer_email = sanitize_email( $request->get_param( 'customer_email' ) );
		$friend_info    = sanitize_text_field( $request->get_param( 'friend_info' ) );

		// Validate data
		$validation = $this->validateReferralData( $customer_name, $customer_email, $friend_info );
		if ( is_wp_error( $validation ) ) {
			return $validation;
		}

		// Insert into database
		global $wpdb;
		$table_name = $wpdb->prefix . 'spiderawards_referral_submissions';

		$result = $wpdb->insert(
			$table_name,
			array(
				'customer_name'  => $customer_name,
				'customer_email' => $customer_email,
				'friend_info'    => $friend_info,
				'status'         => 'pending',
			),
			array( '%s', '%s', '%s', '%s' )
		);

		if ( $result ) {
			return new WP_REST_Response(
				array(
					'success' => true,
					'message' => __( 'Referral submission received! You will get your reward once your friend\'s order is verified.', 'spider-rewards' ),
				),
				200
			);
		} else {
			return new WP_Error( 'submission_failed', 'Failed to submit referral', array( 'status' => 500 ) );
		}
	}

	/**
	 * Submit best fit form
	 */
	public function submitBest( $request ) {
		// Verify nonce
		$nonce = $request->get_header( 'X-WP-Nonce' );
		if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
			return new WP_Error( 'invalid_nonce', 'Invalid nonce', array( 'status' => 403 ) );
		}

		// Get form data
		$customer_name  = sanitize_text_field( $request->get_param( 'customer_name' ) );
		$customer_email = sanitize_email( $request->get_param( 'customer_email' ) );
		$social_handle  = sanitize_text_field( $request->get_param( 'social_handle' ) );
		$content_link   = esc_url_raw( $request->get_param( 'content_link' ) );

		// Validate data
		$validation = $this->validateBestData( $customer_name, $customer_email, $social_handle, $content_link );
		if ( is_wp_error( $validation ) ) {
			return $validation;
		}

		// Insert into database
		global $wpdb;
		$table_name = $wpdb->prefix . 'spiderawards_best_submissions';

		$result = $wpdb->insert(
			$table_name,
			array(
				'customer_name'  => $customer_name,
				'customer_email' => $customer_email,
				'social_handle'  => $social_handle,
				'content_link'   => $content_link,
				'status'         => 'pending',
			),
			array( '%s', '%s', '%s', '%s', '%s' )
		);

		if ( $result ) {
			return new WP_REST_Response(
				array(
					'success' => true,
					'message' => __( 'Best fit submission received! Winners are selected monthly.', 'spider-rewards' ),
				),
				200
			);
		} else {
			return new WP_Error( 'submission_failed', 'Failed to submit best fit entry', array( 'status' => 500 ) );
		}
	}

	/**
	 * Submit review form
	 */
	public function submitReview( $request ) {
		// Verify nonce
		$nonce = $request->get_header( 'X-WP-Nonce' );
		if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
			return new WP_Error( 'invalid_nonce', 'Invalid nonce', array( 'status' => 403 ) );
		}

		// Get form data
		$customer_name  = sanitize_text_field( $request->get_param( 'customer_name' ) );
		$customer_email = sanitize_email( $request->get_param( 'customer_email' ) );
		$content_link   = esc_url_raw( $request->get_param( 'content_link' ) );
		$social_handle  = sanitize_text_field( $request->get_param( 'social_handle' ) );

		// Validate data
		$validation = $this->validateReviewData( $customer_name, $customer_email, $content_link, $social_handle );
		if ( is_wp_error( $validation ) ) {
			return $validation;
		}

		// Insert into database
		global $wpdb;
		$table_name = $wpdb->prefix . 'spiderawards_review_submissions';

		$result = $wpdb->insert(
			$table_name,
			array(
				'customer_name'  => $customer_name,
				'customer_email' => $customer_email,
				'content_link'   => $content_link,
				'social_handle'  => $social_handle,
				'status'         => 'pending',
			),
			array( '%s', '%s', '%s', '%s', '%s' )
		);

		if ( $result ) {
			return new WP_REST_Response(
				array(
					'success' => true,
					'message' => __( 'Review submission received! You will receive your discount code once verified.', 'spider-rewards' ),
				),
				200
			);
		} else {
			return new WP_Error( 'submission_failed', 'Failed to submit review', array( 'status' => 500 ) );
		}
	}

	/**
	 * Validate video submission data
	 */
	private function validateVideoData( $customer_name, $customer_email, $order_number, $content_link, $social_handle ) {
		$errors = array();

		if ( empty( $customer_name ) ) {
			$errors['customer_name'] = __( 'Customer name is required', 'spider-rewards' );
		}

		if ( empty( $customer_email ) || ! is_email( $customer_email ) ) {
			$errors['customer_email'] = __( 'Valid email address is required', 'spider-rewards' );
		}

		if ( empty( $order_number ) ) {
			$errors['order_number'] = __( 'Order number is required', 'spider-rewards' );
		}

		if ( empty( $content_link ) || ! filter_var( $content_link, FILTER_VALIDATE_URL ) ) {
			$errors['content_link'] = __( 'Valid content link is required', 'spider-rewards' );
		}

		if ( empty( $social_handle ) ) {
			$errors['social_handle'] = __( 'Social handle is required', 'spider-rewards' );
		}

		if ( ! empty( $errors ) ) {
			return new WP_Error(
				'validation_failed',
				'Validation failed',
				array(
					'status' => 400,
					'errors' => $errors,
				)
			);
		}

		return true;
	}

	/**
	 * Validate referral submission data
	 */
	private function validateReferralData( $customer_name, $customer_email, $friend_info ) {
		$errors = array();

		if ( empty( $customer_name ) ) {
			$errors['customer_name'] = __( 'Customer name is required', 'spider-rewards' );
		}

		if ( empty( $customer_email ) || ! is_email( $customer_email ) ) {
			$errors['customer_email'] = __( 'Valid email address is required', 'spider-rewards' );
		}

		if ( empty( $friend_info ) ) {
			$errors['friend_info'] = __( 'Friend information is required', 'spider-rewards' );
		}

		if ( ! empty( $errors ) ) {
			return new WP_Error(
				'validation_failed',
				'Validation failed',
				array(
					'status' => 400,
					'errors' => $errors,
				)
			);
		}

		return true;
	}

	/**
	 * Validate best fit submission data
	 */
	private function validateBestData( $customer_name, $customer_email, $social_handle, $content_link ) {
		$errors = array();

		if ( empty( $customer_name ) ) {
			$errors['customer_name'] = __( 'Customer name is required', 'spider-rewards' );
		}

		if ( empty( $customer_email ) || ! is_email( $customer_email ) ) {
			$errors['customer_email'] = __( 'Valid email address is required', 'spider-rewards' );
		}

		if ( empty( $social_handle ) ) {
			$errors['social_handle'] = __( 'Social handle is required', 'spider-rewards' );
		}

		if ( empty( $content_link ) || ! filter_var( $content_link, FILTER_VALIDATE_URL ) ) {
			$errors['content_link'] = __( 'Valid content link is required', 'spider-rewards' );
		}

		if ( ! empty( $errors ) ) {
			return new WP_Error(
				'validation_failed',
				'Validation failed',
				array(
					'status' => 400,
					'errors' => $errors,
				)
			);
		}

		return true;
	}

	/**
	 * Validate review submission data
	 */
	private function validateReviewData( $customer_name, $customer_email, $content_link, $social_handle ) {
		$errors = array();

		if ( empty( $customer_name ) ) {
			$errors['customer_name'] = __( 'Customer name is required', 'spider-rewards' );
		}

		if ( empty( $customer_email ) || ! is_email( $customer_email ) ) {
			$errors['customer_email'] = __( 'Valid email address is required', 'spider-rewards' );
		}

		if ( empty( $content_link ) || ! filter_var( $content_link, FILTER_VALIDATE_URL ) ) {
			$errors['content_link'] = __( 'Valid content link is required', 'spider-rewards' );
		}

		if ( empty( $social_handle ) ) {
			$errors['social_handle'] = __( 'Social handle is required', 'spider-rewards' );
		}

		if ( ! empty( $errors ) ) {
			return new WP_Error(
				'validation_failed',
				'Validation failed',
				array(
					'status' => 400,
					'errors' => $errors,
				)
			);
		}

		return true;
	}
}
