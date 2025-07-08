<?php
/**
 * Plugin Name: Spider Rewards
 * Plugin URI: https://example.com/spider-rewards
 * Description: A complete WordPress plugin for a rewards system with WooCommerce integration.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com
 * Text Domain: spider-rewards
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 8.1
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants
define( 'SPIDER_REWARDS_VERSION', '1.0.0' );
define( 'SPIDER_REWARDS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SPIDER_REWARDS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SPIDER_REWARDS_PLUGIN_FILE', __FILE__ );

// Main plugin class
class SpiderRewards {

	/**
	 * Single instance of the plugin
	 */
	private static $instance = null;

	/**
	 * Get single instance
	 */
	public static function getInstance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	private function __construct() {
		add_action( 'init', array( $this, 'init' ) );
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
	}

	/**
	 * Initialize plugin
	 */
	public function init() {
		// Load required files
		$this->loadIncludes();

		// Initialize components
		$this->initializeComponents();

		// Load text domain
		load_plugin_textdomain( 'spider-rewards', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Load required files
	 */
	private function loadIncludes() {
		require_once SPIDER_REWARDS_PLUGIN_DIR . 'includes/class-database-manager.php';
		require_once SPIDER_REWARDS_PLUGIN_DIR . 'includes/class-admin-menu.php';
		require_once SPIDER_REWARDS_PLUGIN_DIR . 'includes/class-rest-controller.php';
		require_once SPIDER_REWARDS_PLUGIN_DIR . 'includes/class-frontend.php';
		require_once SPIDER_REWARDS_PLUGIN_DIR . 'includes/class-settings.php';
		require_once SPIDER_REWARDS_PLUGIN_DIR . 'includes/list-tables/class-video-submissions-table.php';
		require_once SPIDER_REWARDS_PLUGIN_DIR . 'includes/list-tables/class-referral-submissions-table.php';
		require_once SPIDER_REWARDS_PLUGIN_DIR . 'includes/list-tables/class-best-submissions-table.php';
		require_once SPIDER_REWARDS_PLUGIN_DIR . 'includes/list-tables/class-review-submissions-table.php';
	}

	/**
	 * Initialize components
	 */
	private function initializeComponents() {
		new SpiderRewards_Admin_Menu();
		new SpiderRewards_REST_Controller();
		new SpiderRewards_Frontend();
		new SpiderRewards_Settings();
	}

	/**
	 * Plugin activation
	 */
	public function activate() {
		$database_manager = new SpiderRewards_Database_Manager();
		$database_manager->createTables();

		// Flush rewrite rules
		flush_rewrite_rules();
	}

	/**
	 * Plugin deactivation
	 */
	public function deactivate() {
		// Flush rewrite rules
		flush_rewrite_rules();
	}
}

// Initialize the plugin
SpiderRewards::getInstance();
