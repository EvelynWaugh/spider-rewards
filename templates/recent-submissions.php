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
					<div class="submission-avatar">
						<?php echo esc_html( substr( $submission->customer_name, 0, 1 ) ); ?>
					</div>
					<div class="submission-info">
						<span class="customer-name"><?php echo esc_html( $submission->customer_name ); ?></span>
						<?php if ( ! empty( $submission->social_handle ) ) : ?>
							<span class="social-handle">@<?php echo esc_html( $submission->social_handle ); ?></span>
						<?php endif; ?>
						<span class="submission-action">
							<?php
							switch ( $submission->type ) {
								case 'video':
									_e( 'posted an unboxing video', 'spider-rewards' );
									break;
								case 'referral':
									_e( 'referred a friend', 'spider-rewards' );
									break;
								case 'best_fit':
									_e( 'entered best fit contest', 'spider-rewards' );
									break;
								case 'review':
									_e( 'left a review', 'spider-rewards' );
									break;
							}
							?>
						</span>
						<span class="submission-date"><?php echo esc_html( human_time_diff( strtotime( $submission->submission_date ), current_time( 'timestamp' ) ) . ' ago' ); ?></span>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php else : ?>
		<p class="no-submissions"><?php _e( 'No recent submissions found.', 'spider-rewards' ); ?></p>
	<?php endif; ?>
</div>
