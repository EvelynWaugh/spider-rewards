<?php
/**
 * Template for displaying reward forms
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="spider-rewards-container">
	<div class="rewards-grid">
		
		<!-- Video Submission Form -->
		<div class="reward-card">
			<div class="reward-header">
				<h3><?php _e( 'Post an Unboxing or Reaction Video', 'spider-rewards' ); ?></h3>
				<p class="reward-amount">$30 PayPal</p>
			</div>
			<div class="reward-description">
				<p><?php _e( 'Once your video is verified, you\'ll receive $30 via PayPal within 24 hours. Make sure your PayPal email matches the one you submitted.', 'spider-rewards' ); ?></p>
			</div>
			<form class="reward-form" id="video-form" data-form-type="video">
				<div class="form-group">
					<label for="video_customer_name"><?php _e( 'Your Name', 'spider-rewards' ); ?></label>
					<input type="text" id="video_customer_name" name="customer_name" required>
				</div>
				<div class="form-group">
					<label for="video_customer_email"><?php _e( 'Email Address (PayPal)', 'spider-rewards' ); ?></label>
					<input type="email" id="video_customer_email" name="customer_email" required>
				</div>
				<div class="form-group">
					<label for="video_order_number"><?php _e( 'Order Number', 'spider-rewards' ); ?></label>
					<input type="text" id="video_order_number" name="order_number" required>
				</div>
				<div class="form-group">
					<label for="video_content_link"><?php _e( 'Video Link', 'spider-rewards' ); ?></label>
					<input type="url" id="video_content_link" name="content_link" required>
				</div>
				<div class="form-group">
					<label for="video_social_handle"><?php _e( 'Social Media Handle', 'spider-rewards' ); ?></label>
					<input type="text" id="video_social_handle" name="social_handle" required>
				</div>
				<button type="submit" class="submit-btn"><?php _e( 'Submit Video', 'spider-rewards' ); ?></button>
			</form>
		</div>

		<!-- Referral Form -->
		<div class="reward-card">
			<div class="reward-header">
				<h3><?php _e( 'Invite a Friend', 'spider-rewards' ); ?></h3>
				<p class="reward-amount">$25 Store Credit</p>
			</div>
			<div class="reward-description">
				<p><?php _e( 'As soon as your friend\'s order is verified, you\'ll both get $25 in store credit delivered to your email as a discount code.', 'spider-rewards' ); ?></p>
			</div>
			<form class="reward-form" id="referral-form" data-form-type="referral">
				<div class="form-group">
					<label for="referral_customer_name"><?php _e( 'Your Name', 'spider-rewards' ); ?></label>
					<input type="text" id="referral_customer_name" name="customer_name" required>
				</div>
				<div class="form-group">
					<label for="referral_customer_email"><?php _e( 'Your Email Address', 'spider-rewards' ); ?></label>
					<input type="email" id="referral_customer_email" name="customer_email" required>
				</div>
				<div class="form-group">
					<label for="referral_friend_info"><?php _e( 'Friend\'s Email or Order Number', 'spider-rewards' ); ?></label>
					<input type="text" id="referral_friend_info" name="friend_info" required>
				</div>
				<button type="submit" class="submit-btn"><?php _e( 'Submit Referral', 'spider-rewards' ); ?></button>
			</form>
		</div>

		<!-- Best Fit Contest Form -->
		<div class="reward-card">
			<div class="reward-header">
				<h3><?php _e( 'Best Fit of the Month', 'spider-rewards' ); ?></h3>
				<p class="reward-amount">Free Outfit</p>
			</div>
			<div class="reward-description">
				<p><?php _e( 'We select one winner every month. The winner receives a free hoodie or outfit from our current drop — shipped at no cost.', 'spider-rewards' ); ?></p>
			</div>
			<form class="reward-form" id="best-form" data-form-type="best">
				<div class="form-group">
					<label for="best_customer_name"><?php _e( 'Your Name', 'spider-rewards' ); ?></label>
					<input type="text" id="best_customer_name" name="customer_name" required>
				</div>
				<div class="form-group">
					<label for="best_customer_email"><?php _e( 'Email Address', 'spider-rewards' ); ?></label>
					<input type="email" id="best_customer_email" name="customer_email" required>
				</div>
				<div class="form-group">
					<label for="best_social_handle"><?php _e( 'Social Media Handle', 'spider-rewards' ); ?></label>
					<input type="text" id="best_social_handle" name="social_handle" required>
				</div>
				<div class="form-group">
					<label for="best_content_link"><?php _e( 'Content Link', 'spider-rewards' ); ?></label>
					<input type="url" id="best_content_link" name="content_link" required>
				</div>
				<button type="submit" class="submit-btn"><?php _e( 'Submit Entry', 'spider-rewards' ); ?></button>
			</form>
		</div>

		<!-- Review Form -->
		<div class="reward-card">
			<div class="reward-header">
				<h3><?php _e( 'Leave a Public Review', 'spider-rewards' ); ?></h3>
				<p class="reward-amount">30% Off Code</p>
			</div>
			<div class="reward-description">
				<p><?php _e( 'Once your review is verified, we\'ll email you a 30% off discount code valid for your next order.', 'spider-rewards' ); ?></p>
			</div>
			<form class="reward-form" id="review-form" data-form-type="review">
				<div class="form-group">
					<label for="review_customer_name"><?php _e( 'Your Name', 'spider-rewards' ); ?></label>
					<input type="text" id="review_customer_name" name="customer_name" required>
				</div>
				<div class="form-group">
					<label for="review_customer_email"><?php _e( 'Email Address', 'spider-rewards' ); ?></label>
					<input type="email" id="review_customer_email" name="customer_email" required>
				</div>
				<div class="form-group">
					<label for="review_content_link"><?php _e( 'Review Link', 'spider-rewards' ); ?></label>
					<input type="url" id="review_content_link" name="content_link" required>
				</div>
				<div class="form-group">
					<label for="review_social_handle"><?php _e( 'Social Media Handle', 'spider-rewards' ); ?></label>
					<input type="text" id="review_social_handle" name="social_handle" required>
				</div>
				<button type="submit" class="submit-btn"><?php _e( 'Submit Review', 'spider-rewards' ); ?></button>
			</form>
		</div>

	</div>
</div>

<!-- Success/Error Modal -->
<div id="spider-rewards-modal" class="modal">
	<div class="modal-content">
		<span class="close">&times;</span>
		<div class="modal-body">
			<div class="modal-icon"></div>
			<div class="modal-message"></div>
		</div>
	</div>
</div>
