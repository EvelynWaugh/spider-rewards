<?php
/**
 * Template for displaying reward forms
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>


	<div class="reward-cards">
		
		<!-- Video Submission Form -->
		<div class="reward-card">
			<div class="reward-header">
				<h3><?php _e( 'Post an Unboxing or Reaction Video', 'spider-rewards' ); ?><span class="reward-amount"> - Get $30</span></h3>
				
			</div>
			<div class="reward-steps">
				<ol>
					<li>Record a short video while unboxing or reacting to your new gear</li>
					<li>Post it on TikTok or Instagram</li>
					<li>Tag @hells_treet</li>
					<li>Submit your link using the form below</li>
				</ol>
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
					
					<select id="video_social_handle" name="social_handle">
						<?php foreach ( SpiderRewards\get_form_platform_options( 'video' ) as $platform => $label ) : ?>
							<option value="<?php echo esc_attr( $platform ); ?>"><?php echo esc_html( $label ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<button type="submit" class="submit-btn"><?php _e( 'Submit for 30$ reward', 'spider-rewards' ); ?></button>
			</form>
			<div class="reward-info">
				💰 Once your video is verified, you'll receive $30 via PayPal within 24 hours. Make sure your PayPal email matches the one you submitted.
			</div>
		</div>

		<!-- Referral Form -->
		<div class="reward-card">
			<div class="reward-header">
				<h3><?php _e( 'Invite a Friend', 'spider-rewards' ); ?><span class="reward-amount"> - You Both Get $25</span></h3>
			</div>
			<div class="reward-steps">
				<ol>
					<li>Share your referral code or email</li>
					<li>Your friend places an order</li>
					<li>Submit your and your friend's info below</li>
				</ol>
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
				<button type="submit" class="submit-btn"><?php _e( 'Submit for 25$ each', 'spider-rewards' ); ?></button>
			</form>
			<div class="reward-info">
				💰 As soon as your friend's order is verified, you'll both get $25 in store credit delivered to your email as a discount code.
			</div>
		</div>

		<!-- Best Fit Contest Form -->
		<div class="reward-card">
			<div class="reward-header">
				<h3><?php _e( 'Best Fit of the Month', 'spider-rewards' ); ?><span class="reward-amount"> - Win a Free Drop</span></h3>
			
			</div>
			<div class="reward-steps">
				<ol>
					<li>Post a photo or video in your full fit</li>
					<li>Tag @hells_treet and use hashtag #hells_treet</li>
					<li>Submit your link below</li>
				</ol>
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
					
					<select id="best_social_handle" name="social_handle">
						<?php foreach ( SpiderRewards\get_form_platform_options( 'best' ) as $platform => $label ) : ?>
							<option value="<?php echo esc_attr( $platform ); ?>"><?php echo esc_html( $label ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="form-group">
					<label for="best_content_link"><?php _e( 'Content Link', 'spider-rewards' ); ?></label>
					<input type="url" id="best_content_link" name="content_link" required>
				</div>
				<button type="submit" class="submit-btn"><?php _e( 'Submit for contest', 'spider-rewards' ); ?></button>
			</form>
			<div class="reward-info">
				🎁 We select one winner every month. The winner receives a free hoodie or outfit from our current drop — shipped at no cost.
			</div>
		</div>

		<!-- Review Form -->
		<div class="reward-card">
			<div class="reward-header">
				<h3><?php _e( 'Leave a Public Review', 'spider-rewards' ); ?><span class="reward-amount"> - Get 30% Off</span></h3>
			</div>

			<div class="reward-steps">
				<ol>
					<li>Post a review on one of the following platforms: Reddit, Trustpilot, Sitejabber</li>
					<li>Make sure it's public and honest</li>
					<li>Submit your review link below</li>
				</ol>
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
					<select id="review_social_handle" name="social_handle">
						<?php foreach ( SpiderRewards\get_form_platform_options( 'review' ) as $platform => $label ) : ?>
							<option value="<?php echo esc_attr( $platform ); ?>"><?php echo esc_html( $label ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<button type="submit" class="submit-btn"><?php _e( 'Submit for 30% Off', 'spider-rewards' ); ?></button>
			</form>
			<div class="reward-info">
				💸 Once your review is verified, we'll email you a 30% off discount code valid for your next order.
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
