<?php
/**
 * Frontend Class
 *
 * Handles frontend functionality and scripts.
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SpiderRewards_Frontend {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueueScripts' ) );
		add_shortcode( 'spider_rewards_forms', array( $this, 'displayForms' ) );
		add_shortcode( 'spider_rewards_recent', array( $this, 'displayRecentSubmissions' ) );
	}

	/**
	 * Enqueue frontend scripts
	 */
	public function enqueueScripts() {
		wp_enqueue_script(
			'spider-rewards-frontend',
			SPIDER_REWARDS_PLUGIN_URL . 'assets/js/rewards.js',
			array( 'jquery' ),
			SPIDER_REWARDS_VERSION,
			true
		);

		wp_localize_script(
			'spider-rewards-frontend',
			'spiderRewards',
			array(
				'apiUrl' => home_url( '/wp-json/spider-rewards/v1/' ),
				'nonce'  => wp_create_nonce( 'wp_rest' ),
			)
		);

		wp_enqueue_style(
			'spider-rewards-frontend',
			SPIDER_REWARDS_PLUGIN_URL . 'assets/css/rewards.css',
			array(),
			SPIDER_REWARDS_VERSION
		);
	}

	/**
	 * Display reward forms shortcode
	 */
	public function displayForms( $atts ) {
		$atts = shortcode_atts(
			array(
				'type' => 'all',
			),
			$atts
		);

		ob_start();
		include SPIDER_REWARDS_PLUGIN_DIR . 'templates/reward-forms.php';
		return ob_get_clean();
	}

	/**
	 * Display recent submissions shortcode
	 */
	public function displayRecentSubmissions( $atts ) {
		$atts = shortcode_atts(
			array(
				'limit' => 10,
			),
			$atts
		);

		$recent_submissions = SpiderRewards_Database_Manager::getRecentRewardSubmissions( $atts['limit'] );

		ob_start();
		include SPIDER_REWARDS_PLUGIN_DIR . 'templates/recent-submissions.php';
		return ob_get_clean();
	}
}
