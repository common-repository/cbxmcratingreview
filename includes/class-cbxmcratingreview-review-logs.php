<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}


class CBXMCRatingReviewLog_List_Table extends WP_List_Table {

	/**
	 * The current list of all branches.
	 *
	 * @since  3.1.0
	 * @access public
	 * @var array
	 */
	function __construct() {

		//Set parent defaults
		parent::__construct( array(
			'singular' => 'cbxmcratingreviewreviewlog',     //singular name of the listed records
			'plural'   => 'cbxmcratingreviewreviewlogs',    //plural name of the listed records
			'ajax'     => false      //does this table support ajax?
		) );
	}

	/**
	 * Callback for column 'id'
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_id( $item ) {
		return '<a href="' . admin_url( 'admin.php?page=cbxmcratingreviewreviewlist&view=view&id=' . $item['id'] ) . '" title="' . esc_html__( 'View Review', 'cbxmcratingreview' ) . '">' . $item['id'] . '</a>' . ' (<a target="_blank" href="' . admin_url( 'admin.php?page=cbxmcratingreviewreviewlist&view=addedit&id=' . $item['id'] ) . '" title="' . esc_html__( 'Edit Review', 'cbxmcratingreview' ) . '">' . esc_html__( 'Edit', 'cbxmcratingreview' ) . '</a>)';
	}


	/**
	 * Callback for column 'form_id'
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_form_id( $item ) {
		$form_id = intval( $item['form_id'] );

		return $form_id;
	}

	/**
	 * Callback for column 'post_id'
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_post_id( $item ) {
		$post_id    = intval( $item['post_id'] );
		$post_title = get_the_title( intval( $post_id ) );
		$post_title = ( $post_title == '' ) ? esc_html__( 'Untitled article', 'cbxmcratingreview' ) : $post_title;
		$edit_link  = '<a target="_blank" href="' . get_permalink( $post_id ) . '">' . esc_html( $post_title ) . '</a>';

		$edit_url = esc_url( get_edit_post_link( $post_id ) );
		if ( ! is_null( $edit_url ) ) {
			$edit_link .= ' - <a target="_blank" href="' . $edit_url . '" target="_blank" title="' . esc_html__( 'Edit Post', 'cbxmcratingreview' ) . '">' . $post_id . '</a>';
		}

		return $edit_link;
	}

	/**
	 * Callback for column 'User'
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_user_id( $item ) {
		$user_id           = absint( $item['user_id'] );
		$user_display_name = $item['display_name'];
		$user_html         = '';


		if ( current_user_can( 'edit_user', $user_id ) ) {
			$user_html = '<a href="' . get_edit_user_link( $user_id ) . '" target="_blank" title="' . esc_html__( 'Edit User', 'cbxmcratingreview' ) . '">' . esc_html( $user_display_name ) . '</a>';
		}


		return $user_html;
	}

	/**
	 * Callback for column 'Rating'
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_score( $item ) {
		return number_format_i18n( floatval( $item['score'] ), 2 );
	}

	/**
	 * Callback for column 'post_type'
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_post_type( $item ) {
		return esc_attr( $item['post_type'] );
	}


	/**
	 * Callback for column 'Headline'
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_headline( $item ) {
		$headline = wp_unslash( $item['headline'] );
		if ( strlen( $headline ) > 25 ) {
			$headline = substr( $headline, 0, 25 ) . '...';
		}

		return $headline;
	}

	/**
	 * Callback for column 'Comment'
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_comment( $item ) {
		$headline = wp_unslash( $item['headline'] );
		$comment  = wpautop( wp_unslash( $item['comment'] ) );


		$comment_short = '';
		if ( strlen( strip_tags( $comment ) ) > 25 ) {
			$comment_short = substr( strip_tags( $comment ), 0, 25 ) . '...';
		} else {
			$comment_short = substr( strip_tags( $comment ), 0, 25 );
		}


		wp_add_inline_script( 'cbxmcratingreview-admin',
			'				
				var reviews_arr= cbxmcratingreview_admin.reviews_arr;
				reviews_arr[' . $item['id'] . '] = ' . json_encode( $comment ) . ';
				cbxmcratingreview_admin.reviews_arr = reviews_arr;							
				'
		);

		return $comment_short . '<a data-headline="' . sprintf( esc_html__( 'Review Heading: %s', 'cbxmcratingreview' ), esc_html( $headline ) ) . '" data-reviewid="' . $item['id'] . '" href="#" class="cbxmcratingreview_review_text_expand">' . esc_html__( '(details)', 'cbxmcratingreview' ) . '</a>';


	}


	/**
	 * Callback for column 'Review Date'
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	/*function column_review_date( $item ) {
		$review_date = '';
		if ( $item['review_date'] != '' ) {
			$review_date = CBXMCRatingReviewHelper::dateReadableFormat( wp_unslash( $item['review_date'] ), 'M j, Y H:i' );
		}

		return $review_date;
	}*/

	/**
	 * Callback for column 'Date Created'
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_date_created( $item ) {
		$date_created = '';
		if ( $item['date_created'] != '' ) {
			$date_created = CBXMCRatingReviewHelper::dateReadableFormat( wp_unslash( $item['date_created'] ) );
		}

		return $date_created;
	}

	/**
	 * Callback for column 'Location'
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	/*	function column_location( $item ) {
			return $location = wp_unslash( $item['location'] );
		}*/


	/**
	 * Callback for column 'Review location lat'
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	/*function column_lat( $item ) {

		return $item['lat'];
	}*/

	/**
	 * Callback for column 'Review location lon'
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	/*function column_lon( $item ) {

		return $item['lon'];
	}*/


	/**
	 * Callback for column 'Status'
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_status( $item ) {
		$status_key = $item['status'];

		$exprev_status_arr = CBXMCRatingReviewHelper::ReviewStatusOptions();

		$review_status = '';
		if ( isset( $exprev_status_arr[ $status_key ] ) ) {
			$review_status = $exprev_status_arr[ $status_key ];
		}

		return wp_unslash( $review_status );
	}


	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="%1$s[]" value="%2$s" />',
			/*$1%s*/
			$this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
			/*$2%s*/
			$item['id']                //The value of the checkbox should be the record's id
		);
	}

	function column_default( $item, $column_name ) {

		switch ( $column_name ) {
			case 'id':
				return $item[ $column_name ];
			case 'form_id':
				return $item[ $column_name ];
			case 'post_id':
				return $item[ $column_name ];
			case 'user_id':
				return $item[ $column_name ];
			case 'score':
				return $item[ $column_name ];
			case 'post_type':
				return $item[ $column_name ];
			case 'headline':
				return $item[ $column_name ];
			case 'comment':
				return $item[ $column_name ];
			/*case 'review_date':
				return $item[ $column_name ];*/
			case 'date_created':
				return $item[ $column_name ];
			/*case 'location':
				return $item[ $column_name ];
			case 'lat':
				return $item[ $column_name ];
			case 'lon':
				return $item[ $column_name ];*/
			case 'status':
				return $item[ $column_name ];
			default:
				//return print_r( $item, true ); //Show the whole array for troubleshooting purposes
				echo apply_filters( 'cbxmcratingreview_admin_log_listing_column_default', $item, $column_name );
		}
	}

	function get_columns() {
		$columns = array(
			'cb'           => '<input type="checkbox" />', //Render a checkbox instead of text
			'id'           => esc_html__( 'ID', 'cbxmcratingreview' ),
			'form_id'      => esc_html__( 'Form', 'cbxmcratingreview' ),
			'post_id'      => esc_html__( 'Post', 'cbxmcratingreview' ),
			'user_id'      => esc_html__( 'User', 'cbxmcratingreview' ),
			'score'        => esc_html__( 'Score', 'cbxmcratingreview' ),
			'post_type'    => esc_html__( 'Post Type', 'cbxmcratingreview' ),
			'headline'     => esc_html__( 'Headline', 'cbxmcratingreview' ),
			'comment'      => esc_html__( 'Comment', 'cbxmcratingreview' ),
			//'review_date'  => esc_html__( 'Exp. Date', 'cbxmcratingreview' ),
			'date_created' => esc_html__( 'Created', 'cbxmcratingreview' ),
			//'location'     => esc_html__( 'Location', 'cbxmcratingreview' ),
			//'lat'          => esc_html__( 'Lat', 'cbxmcratingreview' ),
			//'lon'          => esc_html__( 'Long', 'cbxmcratingreview' ),
			'status'       => esc_html__( 'Status', 'cbxmcratingreview' )
		);

		return apply_filters( 'cbxmcratingreview_admin_log_listing_columns', $columns );
	}//end method get_columns


	function get_sortable_columns() {
		$sortable_columns = array(
			'id'           => array( 'logs.id', false ), //true means it's already sorted
			'form_id'      => array( 'logs.form_id', false ),
			'post_id'      => array( 'logs.post_id', false ),
			'user_id'      => array( 'logs.user_id', false ),
			'score'        => array( 'logs.score', false ),
			'post_type'    => array( 'logs.post_type', false ),
			'headline'     => array( 'logs.headline', false ),
			'date_created' => array( 'logs.date_created', false ),
			'status'       => array( 'logs.status', false )
		);

		return apply_filters( 'cbxmcratingreview_admin_log_listing_sortable_columns', $sortable_columns );
	}//end method get_sortable_columns


	function get_bulk_actions() {
		$status_arr           = CBXMCRatingReviewHelper::ReviewStatusOptions();
		$status_arr['delete'] = esc_html__( 'Delete', 'cbxmcratingreview' );

		$bulk_actions = apply_filters( 'cbxmcratingreview_review_bulk_action', $status_arr );

		return $bulk_actions;
	}//end method get_bulk_actions

	function process_bulk_action() {

		$new_status = $this->current_action();

		if ( $new_status == - 1 ) {
			return;
		}


		//Detect when a bulk action is being triggered...
		if ( ! empty( $_REQUEST['cbxmcratingreviewreviewlog'] ) ) {
			global $wpdb;
			$table_cbxmcratingreview_review = $wpdb->prefix . 'cbxmcratingreview_log';


			$results = $_REQUEST['cbxmcratingreviewreviewlog'];
			foreach ( $results as $id ) {

				$id = intval( $id );

				$review_info = cbxmcratingreview_singleReview( $id );


				if ( 'delete' === $new_status ) {
					do_action( 'cbxmcratingreview_review_delete_before', $review_info );

					$delete_status = $wpdb->query( $wpdb->prepare( "DELETE FROM $table_cbxmcratingreview_review WHERE id=%d", $id ) );

					if ( $delete_status !== false ) {
						do_action( 'cbxmcratingreview_review_delete_after', $review_info );
					}
				}
				else {
					if ( is_numeric( $new_status ) && $new_status >= 0 ) {

						$old_status = $review_info['status'];

						if ( $old_status !== $new_status ) {
							$update_status = $wpdb->update(
								$table_cbxmcratingreview_review,
								array(
									'status'        => intval( $new_status ),
									'mod_by'        => intval( get_current_user_id() ),
									'date_modified' => current_time( 'mysql' )
								),
								array( 'id' => $id ),
								array(
									'%d',
									'%d',
									'%s'
								),
								array( '%d' )
							);

							if ( $update_status !== false ) {
								if ( $new_status == 1 ) {
									do_action( 'cbxmcratingreview_review_publish', $review_info );
								} else {
									do_action( 'cbxmcratingreview_review_unpublish', $review_info );
								}

								do_action( 'cbxmcratingreview_review_status_change', $old_status, $new_status, $review_info );
							}//if db update success

						}//if status changed

					}//if status related action
				}//if not delete action


			}
		}

		return;
	}//end method process_bulk_action


	/**
	 * Prepare the review log items
	 */
	function prepare_items() {
		//global $wpdb; //This is used only if making any database queries

		$user   = get_current_user_id();
		$screen = get_current_screen();

		$current_page = $this->get_pagenum();

		$option_name = $screen->get_option( 'per_page', 'option' ); //the core class name is WP_Screen

		$per_page = intval( get_user_meta( $user, $option_name, true ) );

		if ( $per_page == 0 ) {
			$per_page = intval( $screen->get_option( 'per_page', 'default' ) );
		}


		$columns  = $this->get_columns();
		$hidden   = array();
		$sortable = $this->get_sortable_columns();


		$this->_column_headers = array( $columns, $hidden, $sortable );


		$this->process_bulk_action();

		$search  = ( isset( $_REQUEST['s'] ) && $_REQUEST['s'] != '' ) ? sanitize_text_field( $_REQUEST['s'] ) : '';
		$id      = ( isset( $_REQUEST['id'] ) && $_REQUEST['id'] != 0 ) ? intval( $_REQUEST['id'] ) : 0;
		$post_id = ( isset( $_REQUEST['post_id'] ) && $_REQUEST['post_id'] != 0 ) ? intval( $_REQUEST['post_id'] ) : 0;
		$user_id = ( isset( $_REQUEST['user_id'] ) && $_REQUEST['user_id'] != 0 ) ? intval( $_REQUEST['user_id'] ) : 0;
		$order   = ( isset( $_REQUEST['order'] ) && $_REQUEST['order'] != '' ) ? $_REQUEST['order'] : 'DESC';
		$orderby = ( isset( $_REQUEST['orderby'] ) && $_REQUEST['orderby'] != '' ) ? $_REQUEST['orderby'] : 'logs.id';

		$status = isset( $_REQUEST['status'] ) ? wp_unslash( $_REQUEST['status'] ) : 'all';
		$data   = $this->getLogData( $search, $status, $post_id, $user_id, $id, $orderby, $order, $per_page, $current_page );

		$total_items = intval( $this->getLogDataCount( $search, $status, $post_id, $user_id, $id ) );

		$this->items = $data;

		/**
		 * REQUIRED. We also have to register our pagination options & calculations.
		 */
		$this->set_pagination_args( array(
			'total_items' => $total_items,                  //WE have to calculate the total number of items
			'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
			'total_pages' => ceil( $total_items / $per_page )   //WE have to calculate the total number of pages
		) );

	}//end method prepare_items

	/**
	 * Get review logs data
	 *
	 * @param string $search
	 * @param string $status
	 * @param int $post_id
	 * @param int $user_id
	 * @param int $id
	 * @param string $orderby
	 * @param string $order
	 * @param int $perpage
	 * @param int $page
	 *
	 * @return array|null|object
	 */
	public function getLogData( $search = '', $status = 'all', $post_id = 0, $user_id = 0, $id = 0, $orderby = 'logs.id', $order = 'DESC', $perpage = 20, $page = 1 ) {

		global $wpdb;

		$table_cbxmcratingreview_review = $wpdb->prefix . 'cbxmcratingreview_log';
		$table_users                    = $wpdb->prefix . 'users';

		$sql_select = "logs.*, users.user_email, users.display_name";

		$sql_select = apply_filters( 'cbxmcratingreview_admin_log_listing_select', $sql_select, $search, $status, $orderby, $order, $post_id, $user_id, $id, $perpage, $page );

		$join = $where_sql = '';

		$join = " LEFT JOIN $table_users AS users ON users.ID = logs.user_id ";

		$join = apply_filters( 'cbxmcratingreview_admin_log_listing_join', $join, $search, $status, $orderby, $order, $post_id, $user_id, $id, $perpage, $page );

		if ( $search != '' ) {
			if ( $where_sql != '' ) {
				$where_sql .= ' AND ';
			}
			//$where_sql .= $wpdb->prepare( " logs.headline LIKE '%%%s%%' OR logs.comment LIKE '%%%s%%' OR logs.location LIKE '%%%s%%'", $search, $search, $search );
			$where_sql .= $wpdb->prepare( " logs.headline LIKE '%%%s%%' OR logs.comment LIKE '%%%s%%'", $search, $search );
		}

		if ( $status !== 'all' ) {
			$where_sql .= ( ( $where_sql != '' ) ? ' AND ' : '' ) . $wpdb->prepare( 'logs.status=%s', $status );
		}

		if ( $post_id !== 0 ) {
			$where_sql .= ( ( $where_sql != '' ) ? ' AND ' : '' ) . $wpdb->prepare( 'logs.post_id=%d', $post_id );
		}

		if ( $user_id !== 0 ) {
			$where_sql .= ( ( $where_sql != '' ) ? ' AND ' : '' ) . $wpdb->prepare( 'logs.user_id=%d', $user_id );
		}

		$where_sql = apply_filters( 'cbxmcratingreview_admin_log_listing_where', $where_sql, $search, $status, $orderby, $order, $post_id, $user_id, $id, $perpage, $page );

		if ( $where_sql == '' ) {
			$where_sql = '1';
		}

		$start_point = ( $page * $perpage ) - $perpage;
		$limit_sql   = "LIMIT";
		$limit_sql   .= ' ' . $start_point . ',';
		$limit_sql   .= ' ' . $perpage;

		$sortingOrder = " ORDER BY $orderby $order ";

		$data = $wpdb->get_results( "SELECT $sql_select FROM $table_cbxmcratingreview_review as logs $join  WHERE  $where_sql $sortingOrder  $limit_sql", 'ARRAY_A' );

		return $data;
	}//end method getLogData

	/**
	 * Review logs data count
	 *
	 * @param string $search
	 * @param string $status
	 * @param int $post_id
	 * @param int $user_id
	 * @param int $id
	 *
	 * @return null|string
	 */
	public function getLogDataCount( $search = '', $status = 'all', $post_id = 0, $user_id = 0, $id = 0 ) {

		global $wpdb;
		$table_cbxmcratingreview_review = $wpdb->prefix . 'cbxmcratingreview_log';

		$sql_select = "SELECT COUNT(*) FROM $table_cbxmcratingreview_review as logs";

		$join = $where_sql = '';

		$join = apply_filters( 'cbxmcratingreview_admin_log_listing_join_total', $join, $search, $status, $post_id, $user_id, $id );

		if ( $search != '' ) {
			if ( $where_sql != '' ) {
				$where_sql .= ' AND ';
			}
			//$where_sql .= $wpdb->prepare( " logs.headline LIKE '%%%s%%' OR logs.comment LIKE '%%%s%%' OR logs.location LIKE '%%%s%%'", $search, $search, $search );
			$where_sql .= $wpdb->prepare( " logs.headline LIKE '%%%s%%' OR logs.comment LIKE '%%%s%%'", $search, $search );
		}

		if ( $status != 'all' ) {
			$where_sql .= ( ( $where_sql != '' ) ? ' AND ' : '' ) . $wpdb->prepare( 'logs.status=%s', $status );
		}

		if ( $post_id !== 0 ) {
			$where_sql .= ( ( $where_sql != '' ) ? ' AND ' : '' ) . $wpdb->prepare( 'logs.post_id=%d', $post_id );
		}

		if ( $user_id !== 0 ) {
			$where_sql .= ( ( $where_sql != '' ) ? ' AND ' : '' ) . $wpdb->prepare( 'logs.user_id=%d', $user_id );
		}

		$where_sql = apply_filters( 'cbxmcratingreview_admin_log_listing_where_total', $where_sql, $search, $status, $post_id, $user_id, $id );

		if ( $where_sql == '' ) {
			$where_sql = '1';
		}


		$count = $wpdb->get_var( "$sql_select $join  WHERE  $where_sql" );

		return $count;
	}//end method getLogDataCount


	/**
	 * Generates content for a single row of the table
	 *
	 * @since  3.1.0
	 * @access public
	 *
	 * @param object $item The current item
	 */
	public function single_row( $item ) {
		$row_class = 'cbxmcratingreview_row';
		$row_class = apply_filters( 'cbxmcratingreview_row_class', $row_class, $item );
		echo '<tr id="cbxmcratingreview_row_' . $item['id'] . '" class="' . $row_class . '">';
		$this->single_row_columns( $item );
		echo '</tr>';
	}

	/**
	 * Message to be displayed when there are no items
	 *
	 * @since  3.1.0
	 * @access public
	 */
	public function no_items() {
		echo '<div class="notice notice-warning inline "><p>' . esc_html__( 'No review found. Please change your search criteria for better result.', 'cbxmcratingreview' ) . '</p></div>';
	}
}//end class CBXMCRatingReviewLog_List_Table