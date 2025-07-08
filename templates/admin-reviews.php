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
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Review Submissions', 'spider-rewards' ); ?></h1>
	<a href="#" class="page-title-action add-new-submission" data-table="review"><?php esc_html_e( 'Add New', 'spider-rewards' ); ?></a>
	<hr class="wp-header-end">
	
	<form method="post">
		<?php
		$list_table->display();
		?>
	</form>
</div>

<!-- Edit/Add Submission Modal -->
<div id="submission-modal" class="spider-modal" style="display: none;">
	<div class="spider-modal-content">
		<div class="spider-modal-header">
			<h2 id="modal-title"><?php esc_html_e( 'Edit Submission', 'spider-rewards' ); ?></h2>
			<span class="spider-modal-close">&times;</span>
		</div>
		<div class="spider-modal-body">
			<form id="submission-form">
				<input type="hidden" id="submission-id" name="submission_id" value="">
				<input type="hidden" id="table-type" name="table_type" value="review">
				
				<div class="form-field">
					<label for="customer_name"><?php esc_html_e( 'Customer Name', 'spider-rewards' ); ?> *</label>
					<input type="text" id="customer_name" name="customer_name" required>
				</div>
				
				<div class="form-field">
					<label for="customer_email"><?php esc_html_e( 'Customer Email', 'spider-rewards' ); ?> *</label>
					<input type="email" id="customer_email" name="customer_email" required>
				</div>
				
				<div class="form-field">
					<label for="social_handle"><?php esc_html_e( 'Social Handle', 'spider-rewards' ); ?></label>
					<input type="text" id="social_handle" name="social_handle">
				</div>
				
				<div class="form-field">
					<label for="content_link"><?php esc_html_e( 'Review Link', 'spider-rewards' ); ?></label>
					<input type="url" id="content_link" name="content_link">
				</div>
				
				<div class="form-field">
					<label for="status"><?php esc_html_e( 'Status', 'spider-rewards' ); ?></label>
					<select id="status" name="status">
						<option value="pending"><?php esc_html_e( 'Pending', 'spider-rewards' ); ?></option>
						<option value="approved"><?php esc_html_e( 'Approved', 'spider-rewards' ); ?></option>
						<option value="rejected"><?php esc_html_e( 'Rejected', 'spider-rewards' ); ?></option>
					</select>
				</div>
			</form>
		</div>
		<div class="spider-modal-footer">
			<button type="button" class="button" id="cancel-submission"><?php esc_html_e( 'Cancel', 'spider-rewards' ); ?></button>
			<button type="button" class="button button-primary" id="save-submission"><?php esc_html_e( 'Save', 'spider-rewards' ); ?></button>
		</div>
	</div>
</div>
