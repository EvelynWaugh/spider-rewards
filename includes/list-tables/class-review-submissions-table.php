<?php
/**
 * Review Submissions List Table
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class SpiderRewards_Review_Submissions_Table extends WP_List_Table {

	public function __construct() {
		parent::__construct(
			array(
				'singular' => 'review_submission',
				'plural'   => 'review_submissions',
				'ajax'     => false,
			)
		);
	}

	public function get_columns() {
		return array(
			'cb'              => '<input type="checkbox" />',
			'id'              => __( 'ID', 'spider-rewards' ),
			'customer_name'   => __( 'Customer Name', 'spider-rewards' ),
			'customer_email'  => __( 'Email', 'spider-rewards' ),
			'content_link'    => __( 'Review Link', 'spider-rewards' ),
			'social_handle'   => __( 'Social Handle', 'spider-rewards' ),
			'status'          => __( 'Status', 'spider-rewards' ),
			'submission_date' => __( 'Submission Date', 'spider-rewards' ),
			'actions'         => __( 'Actions', 'spider-rewards' ),
		);
	}

	public function get_sortable_columns() {
		return array(
			'id'              => array( 'id', false ),
			'customer_name'   => array( 'customer_name', false ),
			'status'          => array( 'status', false ),
			'submission_date' => array( 'submission_date', false ),
		);
	}

	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'id':
			case 'customer_name':
			case 'customer_email':
			case 'social_handle':
			case 'submission_date':
				return $item[ $column_name ];
			case 'content_link':
				return '<a href="' . esc_url( $item[ $column_name ] ) . '" target="_blank">' . __( 'View Review', 'spider-rewards' ) . '</a>';
			case 'status':
				return $this->getStatusBadge( $item[ $column_name ] );
			case 'actions':
				return $this->column_actions( $item );
			default:
				return print_r( $item, true );
		}
	}

	public function column_cb( $item ) {
		return sprintf( '<input type="checkbox" name="review_submission[]" value="%s" />', $item['id'] );
	}

	public function column_status( $item ) {
		$status_badge = $this->getStatusBadge( $item['status'] );
		$actions      = array();

		if ( $item['status'] !== 'approved' ) {
			$actions['approve'] = sprintf( '<a href="#" class="status-action" data-id="%d" data-status="approved" data-table="review">%s</a>', $item['id'], __( 'Approve', 'spider-rewards' ) );
		}
		if ( $item['status'] !== 'rejected' ) {
			$actions['reject'] = sprintf( '<a href="#" class="status-action" data-id="%d" data-status="rejected" data-table="review">%s</a>', $item['id'], __( 'Reject', 'spider-rewards' ) );
		}
		if ( $item['status'] !== 'pending' ) {
			$actions['pending'] = sprintf( '<a href="#" class="status-action" data-id="%d" data-status="pending" data-table="review">%s</a>', $item['id'], __( 'Set Pending', 'spider-rewards' ) );
		}

		return $status_badge . $this->row_actions( $actions );
	}

	/**
	 * Column actions
	 */
	public function column_actions( $item ) {
		$edit_link = sprintf(
			'<a href="#" class="edit-submission" data-id="%d" data-table="review" title="%s">%s</a>',
			$item['id'],
			__( 'Edit this submission', 'spider-rewards' ),
			__( 'Edit', 'spider-rewards' )
		);

		$delete_link = sprintf(
			'<a href="#" class="delete-submission" data-id="%d" data-table="review" title="%s">%s</a>',
			$item['id'],
			__( 'Delete this submission', 'spider-rewards' ),
			__( 'Delete', 'spider-rewards' )
		);

		return $edit_link . ' | ' . $delete_link;
	}

	private function getStatusBadge( $status ) {
		$badges = array(
			'pending'  => '<span class="status-badge status-pending">' . __( 'Pending', 'spider-rewards' ) . '</span>',
			'approved' => '<span class="status-badge status-approved">' . __( 'Approved', 'spider-rewards' ) . '</span>',
			'rejected' => '<span class="status-badge status-rejected">' . __( 'Rejected', 'spider-rewards' ) . '</span>',
		);
		return isset( $badges[ $status ] ) ? $badges[ $status ] : $status;
	}

	public function prepare_items() {
		global $wpdb;

		$table_name   = $wpdb->prefix . 'spiderawards_review_submissions';
		$per_page     = 20;
		$current_page = $this->get_pagenum();
		$offset       = ( $current_page - 1 ) * $per_page;

		$orderby = isset( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : 'submission_date';
		$order   = isset( $_GET['order'] ) ? sanitize_text_field( $_GET['order'] ) : 'DESC';

		$total_items = $wpdb->get_var( "SELECT COUNT(id) FROM $table_name" );
		$items       = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $offset ), ARRAY_A );

		$this->items = $items;
		$this->set_pagination_args(
			array(
				'total_items' => $total_items,
				'per_page'    => $per_page,
				'total_pages' => ceil( $total_items / $per_page ),
			)
		);

		$this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns() );
	}
}
