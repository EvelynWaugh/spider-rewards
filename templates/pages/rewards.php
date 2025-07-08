<?php
/**
 * Template Name: Spider Rewards Page
 */

get_header();
?>
<div class="spider-rewards-container">

<?php echo do_shortcode( '[spider_rewards_forms]' ); ?>
<?php echo do_shortcode( '[spider_rewards_recent]' ); ?>


<section class="rewards-faq-section">
	<h3 class="rewards-faq-title">Frequently Asked Questions</h3>
	
	<div class="rewards-faq-item">
		<div class="rewards-faq-question">How do I know if I'll get the reward?</div>
		<div class="rewards-faq-answer">Every submission is reviewed manually within 24 hours. If your entry meets the requirements, your reward is issued.</div>
	</div>

	<div class="rewards-faq-item">
		<div class="rewards-faq-question">Can I do more than one action?</div>
		<div class="rewards-faq-answer">Yes! You can complete all the listed actions — and receive a separate reward for each one.</div>
	</div>

	<div class="rewards-faq-item">
		<div class="rewards-faq-question">What if I posted something but forgot to tag?</div>
		<div class="rewards-faq-answer">No worries — just submit the link in the form and we'll still check it manually.</div>
	</div>

	<div class="rewards-faq-item">
		<div class="rewards-faq-question">Do I need a public account?</div>
		<div class="rewards-faq-answer">Yes — make sure your content is public so our team can view it and verify.</div>
	</div>
</section>

<section class="rewards-footer">
	<div class="rewards-footer-disclaimer">
		*Rewards are available for verified purchases only. Max 1 reward per action per order. You agree your content may be used in our social media.
	</div>
	<div class="rewards-footer-contact">
		Support: <a href="mailto:support@hellstr.com">support@hellstr.com</a><br>
		Instagram: <a href="https://instagram.com/hells_treet">@hells_treet</a>
	</div>
</section>
</div>
<?php
get_footer();
