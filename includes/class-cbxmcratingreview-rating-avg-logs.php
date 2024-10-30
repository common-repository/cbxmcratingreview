<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}


class CBXMCRatingReviewRatingAvgLog_List_Table extends WP_List_Table {

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
			'singular' => 'cbxmcratingreviewratingavglog',     //singular name of the listed records
			'plural'   => 'cbxmcratingreviewratingavglogs',    //plural name of the listed records
			'ajax'     => false      //does this table support ajax?
		) );
	}

	/**
	 * Callback for column 'post_id'
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_post_id( $item ) {
		$edit_link = intval( $item['post_id'] );

		$edit_url = esc_url( get_edit_post_link( $item['post_id'] ) );
		if ( ! is_null( $edit_url ) ) {
			$edit_link .= '<a href="' . $edit_url . '" target="_blank" title="' . esc_html__( 'Edit Post', 'cbxmcratingreview' ) . '">' . esc_html__( ' (Edit)', 'cbxmcratingreview' ) . '</a>';
		}

		return $edit_link;
	}//end method column_post_id

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
	}//end method column_form_id

	/**
	 * Callback for column 'post_title'
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_post_title( $item ) {
		$post_id = intval( $item['post_id'] );

		$post_url = esc_url( get_permalink( $post_id ) );

		if ( ! is_null( $post_url ) ) {
			$post_url = '<a target="_blank" href="' . $post_url . '" target="_blank" title="' . esc_html__( 'Visit Post', 'cbxmcratingreview' ) . '">' . get_the_title( $post_id ) . '</a>';
		}

		return $post_url;
	}


	/**
	 * Callback for column 'Avg Rating'
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_avg_rating( $item ) {

		return number_format_i18n( floatval( $item['avg_rating'] ), 2 );
	}

	/**
	 * Callback for column 'Total'
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_total_count( $item ) {

		$post_id = intval( $item['post_id'] );

		return intval( $item['total_count'] ) . ' - <a target="_blank" href="' . esc_url( add_query_arg( 'post_id', $post_id, admin_url( 'admin.php?page=cbxmcratingreviewreviewlist' ) ) ) . '">' . esc_html__( 'View All', 'cbxmcratingreview' ) . '</a>';
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
			case 'post_title':
				return $item[ $column_name ];
			case 'avg_rating':
				return $item[ $column_name ];
			case 'total_count':
				return $item[ $column_name ];
			case 'date_created':
				return $item[ $column_name ];
			case 'date_modified':
				return $item[ $column_name ];
			default:
				return print_r( $item, true ); //Show the whole array for troubleshooting purposes
		}
	}

	function get_columns() {
		$columns = array(
			'cb'            => '<input type="checkbox" />', //Render a checkbox instead of text
			'id'            => esc_html__( 'ID', 'cbxmcratingreview' ),
			'form_id'       => esc_html__( 'Form', 'cbxmcratingreview' ),
			'post_id'       => esc_html__( 'Post', 'cbxmcratingreview' ),
			'post_title'    => esc_html__( 'Article', 'cbxmcratingreview' ),
			'avg_rating'    => esc_html__( 'Average', 'cbxmcratingreview' ),
			'total_count'   => esc_html__( 'Total', 'cbxmcratingreview' ),
			'date_created'  => esc_html__( 'Created', 'cbxmcratingreview' ),
			'date_modified' => esc_html__( 'Modified', 'cbxmcratingreview' ),
		);

		return $columns;
	}


	function get_sortable_columns() {
		$sortable_columns = array(
			'id'            => array( 'logs.id', false ), //true means it's already sorted
			'form_id'       => array( 'logs.form_id', false ),
			'post_id'       => array( 'logs.post_id', false ),
			'avg_rating'    => array( 'logs.avg_rating', false ),
			'total_count'   => array( 'logs.total_count', false ),
			'date_created'  => array( 'logs.date_created', false ),
			'date_modified' => array( 'logs.date_modified', false ),
		);

		return $sortable_columns;
	}

	function get_bulk_actions() {
		$avg_bulk_arr['delete'] = esc_html__( 'Delete', 'cbxmcratingreview' );

		$bulk_actions = apply_filters( 'cbxmcratingreview_review_avg_bulk_action', $avg_bulk_arr );

		return $bulk_actions;
	}


	function process_bulk_action() {

		$new_status = $this->current_action();

		if ( 'delete' === $new_status ) {
			//Detect when a bulk action is being triggered...
			if ( ! empty( $_REQUEST['cbxmcratingreviewratingavglog'] ) ) {
				global $wpdb;
				$table_rating_log = $wpdb->prefix . 'cbxmcratingreview_log';


				$results = $_REQUEST['cbxmcratingreviewratingavglog'];
				foreach ( $results as $id ) {
					$id = intval( $id );

					//get avg rating info
					$rating_avg_info = cbxmcratingreview_singleAvgRatingInfo( $id );
					$post_id         = $rating_avg_info['post_id'];
					$post_reviews    = cbxmcratingreview_postReviews( $post_id, - 1 );

					//here we will delete all single review for this avg and
					if ( is_array( $post_reviews ) && sizeof( $post_reviews ) > 0 ) {
						foreach ( $post_reviews as $index => $post_review ) {
							$review_id = intval( $post_review['id'] );

							do_action( 'cbxmcratingreview_review_delete_before', $post_review );
							$delete_status = $wpdb->query( $wpdb->prepare( "DELETE FROM $table_rating_log WHERE id=%d", $review_id ) );

							if ( $delete_status !== false ) {
								do_action( 'cbxmcratingreview_review_delete_after', $post_review );
							}
						}
					}//end all reviews
				}
			}
		}

		return;
	}

	function prepare_items() {

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

		//$search  = ( isset( $_REQUEST['s'] ) && $_REQUEST['s'] != '' ) ? sanitize_text_field( $_REQUEST['s'] ) : '';
		$order   = ( isset( $_REQUEST['order'] ) && $_REQUEST['order'] != '' ) ? $_REQUEST['order'] : 'DESC';
		$orderby = ( isset( $_REQUEST['orderby'] ) && $_REQUEST['orderby'] != '' ) ? $_REQUEST['orderby'] : 'logs.id';

		$data = $this->getLogData( $orderby, $order, $per_page, $current_page );

		$total_items = intval( $this->getLogDataCount() );

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
	 * Get Data
	 *
	 * @param string $orderby
	 * @param string $order
	 * @param int $perpage
	 * @param int $page
	 *
	 * @return array|null|object
	 */
	public function getLogData( $orderby = 'logs.id', $order = 'DESC', $perpage = 20, $page = 1 ) {

		global $wpdb;
		$table_rating_avg = $wpdb->prefix . 'cbxmcratingreview_log_avg';

		$sql_select = "SELECT * FROM $table_rating_avg as logs";

		$join = $where_sql = '';


		if ( $where_sql == '' ) {
			$where_sql = '1';
		}

		$start_point = ( $page * $perpage ) - $perpage;
		$limit_sql   = "LIMIT";
		$limit_sql   .= ' ' . $start_point . ',';
		$limit_sql   .= ' ' . $perpage;

		$sortingOrder = " ORDER BY $orderby $order ";

		$data = $wpdb->get_results( "$sql_select $join  WHERE  $where_sql $sortingOrder  $limit_sql", 'ARRAY_A' );

		return $data;
	}//end method getLogData

	/**
	 * Get total data count
	 *
	 *
	 * @return array|null|object
	 */
	public function getLogDataCount() {

		global $wpdb;
		$table_rating_avg = $wpdb->prefix . 'cbxmcratingreview_log_avg';

		$sql_select = "SELECT COUNT(*) FROM $table_rating_avg as logs";

		$join = $where_sql = '';


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
		$row_class = 'cbxmcratingreview_avg_row';
		$row_class = apply_filters( 'cbxmcratingreview_avg_row_class', $row_class, $item );
		echo '<tr id="cbxmcratingreview_avg_row_' . $item['id'] . '" class="' . $row_class . '">';
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
		echo '<div class="notice notice-warning inline "><p>' . esc_html__( 'No rating average found. Please change your search criteria for better result.', 'cbxmcratingreview' ) . '</p></div>';
	}
}//end class CBXMCRatingReviewRatingAvgLog_List_Table