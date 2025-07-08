<?php
/**
 * Admin reviews page template
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wrap">
	<h1><?php _e( 'Review Submissions', 'spider-rewards' ); ?></h1>
	
	<form method="post">
		<?php
		$list_table->display();
		?>
	</form>
</div>
