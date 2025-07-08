<?php
/**
 * Admin settings page template
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wrap">
	<h1><?php _e( 'Spider Rewards Settings', 'spider-rewards' ); ?></h1>
	
	<form method="post" action="options.php">
		<?php
		settings_fields( 'spider_rewards_settings' );
		do_settings_sections( 'spider_rewards_settings' );
		submit_button();
		?>
	</form>
</div>
