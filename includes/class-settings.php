<?php
/**
 * Settings Class
 *
 * Handles plugin settings and options.
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SpiderRewards_Settings {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'initSettings' ) );
	}

	/**
	 * Initialize settings
	 */
	public function initSettings() {
		register_setting( 'spider_rewards_settings', 'spider_rewards_options' );

		add_settings_section(
			'spider_rewards_general',
			__( 'General Settings', 'spider-rewards' ),
			array( $this, 'generalSectionCallback' ),
			'spider_rewards_settings'
		);

		add_settings_field(
			'video_reward_amount',
			__( 'Video Reward Amount ($)', 'spider-rewards' ),
			array( $this, 'videoRewardAmountCallback' ),
			'spider_rewards_settings',
			'spider_rewards_general'
		);

		add_settings_field(
			'referral_reward_amount',
			__( 'Referral Reward Amount ($)', 'spider-rewards' ),
			array( $this, 'referralRewardAmountCallback' ),
			'spider_rewards_settings',
			'spider_rewards_general'
		);

		add_settings_field(
			'review_discount_percentage',
			__( 'Review Discount Percentage (%)', 'spider-rewards' ),
			array( $this, 'reviewDiscountPercentageCallback' ),
			'spider_rewards_settings',
			'spider_rewards_general'
		);

		add_settings_field(
			'paypal_email',
			__( 'PayPal Email for Payments', 'spider-rewards' ),
			array( $this, 'paypalEmailCallback' ),
			'spider_rewards_settings',
			'spider_rewards_general'
		);
	}

	/**
	 * General section callback
	 */
	public function generalSectionCallback() {
		echo '<p>' . __( 'Configure the rewards system settings below.', 'spider-rewards' ) . '</p>';
	}

	/**
	 * Video reward amount callback
	 */
	public function videoRewardAmountCallback() {
		$options = get_option( 'spider_rewards_options' );
		$value   = isset( $options['video_reward_amount'] ) ? $options['video_reward_amount'] : '30';
		echo '<input type="number" name="spider_rewards_options[video_reward_amount]" value="' . esc_attr( $value ) . '" min="0" step="0.01" />';
	}

	/**
	 * Referral reward amount callback
	 */
	public function referralRewardAmountCallback() {
		$options = get_option( 'spider_rewards_options' );
		$value   = isset( $options['referral_reward_amount'] ) ? $options['referral_reward_amount'] : '25';
		echo '<input type="number" name="spider_rewards_options[referral_reward_amount]" value="' . esc_attr( $value ) . '" min="0" step="0.01" />';
	}

	/**
	 * Review discount percentage callback
	 */
	public function reviewDiscountPercentageCallback() {
		$options = get_option( 'spider_rewards_options' );
		$value   = isset( $options['review_discount_percentage'] ) ? $options['review_discount_percentage'] : '30';
		echo '<input type="number" name="spider_rewards_options[review_discount_percentage]" value="' . esc_attr( $value ) . '" min="0" max="100" />';
	}

	/**
	 * PayPal email callback
	 */
	public function paypalEmailCallback() {
		$options = get_option( 'spider_rewards_options' );
		$value   = isset( $options['paypal_email'] ) ? $options['paypal_email'] : '';
		echo '<input type="email" name="spider_rewards_options[paypal_email]" value="' . esc_attr( $value ) . '" class="regular-text" />';
		echo '<p class="description">' . __( 'Email address for sending PayPal payments to video submitters.', 'spider-rewards' ) . '</p>';
	}

	/**
	 * Get option value
	 */
	public static function getOption( $key, $default = '' ) {
		$options = get_option( 'spider_rewards_options' );
		return isset( $options[ $key ] ) ? $options[ $key ] : $default;
	}
}
