<?php
/**
 * Template for displaying recent submissions
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

	<div class="spider-rewards-recent">

	<h3><?php _e( 'Recent Submissions', 'spider-rewards' ); ?></h3>
	
	<?php if ( ! empty( $recent_submissions ) ) : ?>
		<div class="recent-submissions-list">
			<?php foreach ( $recent_submissions as $submission ) : ?>
				<div class="submission-item">
	
					<div class="submission-info">
						<span class="customer-name"><?php echo esc_html( $submission->customer_name ); ?></span>

						<span class="submission-action">
							<?php
							$submission_reward_text = '';
							switch ( $submission->type ) {
								case 'video':
									_e( 'posted an unboxing video', 'spider-rewards' );
									$submission_reward_text = ' • $30 PayPal reward sent';
									break;
								case 'referral':
									_e( 'referred a friend', 'spider-rewards' );
									$submission_reward_text = ' • $25 PayPal reward sent';
									break;
								case 'best_fit':
									_e( 'entered in monthly contest', 'spider-rewards' );
									break;
								case 'review':
									_e( 'left a review', 'spider-rewards' );
									$submission_reward_text = ' • 30% discount code sent';
									break;
							}
							?>
						</span>
						<?php if ( $submission->status === 'approved' ) : ?>
							<span class="submission-status"><?php echo $submission_reward_text; ?></span>
						<?php endif; ?>
						<span class="submission-date"><?php echo esc_html( human_time_diff( strtotime( $submission->submission_date ), current_time( 'timestamp' ) ) . ' ago' ); ?></span>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php else : ?>
		<p class="no-submissions"><?php _e( 'No recent submissions found.', 'spider-rewards' ); ?></p>
	<?php endif; ?>
</div>
