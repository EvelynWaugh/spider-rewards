<?php
/**
 * Main admin page template
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wrap">
	<h1><?php _e( 'Spider Rewards Dashboard', 'spider-rewards' ); ?></h1>
	
	<div class="rewards-dashboard">
		<div class="dashboard-stats">
			<div class="stat-card">
				<h3><?php _e( 'Total Submissions', 'spider-rewards' ); ?></h3>
				<p class="stat-number"><?php echo count( $recent_submissions ); ?></p>
			</div>
			<div class="stat-card">
				<h3><?php _e( 'Pending Reviews', 'spider-rewards' ); ?></h3>
				<p class="stat-number">
					<?php
					global $wpdb;
					$pending_count = $wpdb->get_var(
						"
                        SELECT 
                            (SELECT COUNT(*) FROM {$wpdb->prefix}spiderawards_video_submissions WHERE status = 'pending') +
                            (SELECT COUNT(*) FROM {$wpdb->prefix}spiderawards_referral_submissions WHERE status = 'pending') +
                            (SELECT COUNT(*) FROM {$wpdb->prefix}spiderawards_best_submissions WHERE status = 'pending') +
                            (SELECT COUNT(*) FROM {$wpdb->prefix}spiderawards_review_submissions WHERE status = 'pending')
                    "
					);
					echo intval( $pending_count );
					?>
				</p>
			</div>
			<div class="stat-card">
				<h3><?php _e( 'Approved Today', 'spider-rewards' ); ?></h3>
				<p class="stat-number">
					<?php
					$today_approved = $wpdb->get_var(
						"
                        SELECT 
                            (SELECT COUNT(*) FROM {$wpdb->prefix}spiderawards_video_submissions WHERE status = 'approved' AND DATE(submission_date) = CURDATE()) +
                            (SELECT COUNT(*) FROM {$wpdb->prefix}spiderawards_referral_submissions WHERE status = 'approved' AND DATE(submission_date) = CURDATE()) +
                            (SELECT COUNT(*) FROM {$wpdb->prefix}spiderawards_best_submissions WHERE status = 'approved' AND DATE(submission_date) = CURDATE()) +
                            (SELECT COUNT(*) FROM {$wpdb->prefix}spiderawards_review_submissions WHERE status = 'approved' AND DATE(submission_date) = CURDATE())
                    "
					);
					echo intval( $today_approved );
					?>
				</p>
			</div>
		</div>
		
		<div class="dashboard-content">
			<div class="recent-submissions-section">
				<h2><?php _e( 'Recent Submissions', 'spider-rewards' ); ?></h2>
				<?php if ( ! empty( $recent_submissions ) ) : ?>
					<div class="submissions-table">
						<table class="wp-list-table widefat fixed striped">
							<thead>
								<tr>
									<th><?php _e( 'Customer', 'spider-rewards' ); ?></th>
									<th><?php _e( 'Type', 'spider-rewards' ); ?></th>
									<th><?php _e( 'Social Handle', 'spider-rewards' ); ?></th>
									<th><?php _e( 'Date', 'spider-rewards' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ( $recent_submissions as $submission ) : ?>
									<tr>
										<td><?php echo esc_html( $submission->customer_name ); ?></td>
										<td>
											<?php
											switch ( $submission->type ) {
												case 'video':
													echo '<span class="submission-type video">' . __( 'Video', 'spider-rewards' ) . '</span>';
													break;
												case 'referral':
													echo '<span class="submission-type referral">' . __( 'Referral', 'spider-rewards' ) . '</span>';
													break;
												case 'best_fit':
													echo '<span class="submission-type best-fit">' . __( 'Best Fit', 'spider-rewards' ) . '</span>';
													break;
												case 'review':
													echo '<span class="submission-type review">' . __( 'Review', 'spider-rewards' ) . '</span>';
													break;
											}
											?>
										</td>
										<td><?php echo esc_html( $submission->social_handle ); ?></td>
										<td><?php echo esc_html( date( 'Y-m-d H:i', strtotime( $submission->submission_date ) ) ); ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				<?php else : ?>
					<p><?php _e( 'No recent submissions found.', 'spider-rewards' ); ?></p>
				<?php endif; ?>
			</div>
			
			<div class="quick-actions">
				<h3><?php _e( 'Quick Actions', 'spider-rewards' ); ?></h3>
				<div class="action-buttons">
					<a href="<?php echo admin_url( 'admin.php?page=spider-rewards-videos' ); ?>" class="button button-primary">
						<?php _e( 'Review Videos', 'spider-rewards' ); ?>
					</a>
					<a href="<?php echo admin_url( 'admin.php?page=spider-rewards-referrals' ); ?>" class="button button-primary">
						<?php _e( 'Review Referrals', 'spider-rewards' ); ?>
					</a>
					<a href="<?php echo admin_url( 'admin.php?page=spider-rewards-best' ); ?>" class="button button-primary">
						<?php _e( 'Review Best Fit', 'spider-rewards' ); ?>
					</a>
					<a href="<?php echo admin_url( 'admin.php?page=spider-rewards-reviews' ); ?>" class="button button-primary">
						<?php _e( 'Review Reviews', 'spider-rewards' ); ?>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
