<?php
/**
 * Admin referrals page template
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wrap">
	<h1><?php _e( 'Referral Submissions', 'spider-rewards' ); ?></h1>
	
	<form method="post">
		<?php
		$list_table->display();
		?>
	</form>
</div>
