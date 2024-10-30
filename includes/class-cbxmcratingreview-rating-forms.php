<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}


class CBXMCRatingReviewForm_List_Table extends WP_List_Table {
	/**
	 * The setting of this plugin.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string $settings plugin setting class ref
	 */
	public $settings;

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
			'singular' => 'cbxmcratingreviewreviewform',     //singular name of the listed records
			'plural'   => 'cbxmcratingreviewreviewforms',    //plural name of the listed records
			'ajax'     => false      //does this table support ajax?
		) );

		$this->settings = new CBXMCRatingReviewSettings();
	}

	/**
	 * Callback for column 'status'
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_name( $item ) {
		$form_id = $item['id'];
		$cbxmcratingreview_setting = $this->getSetting();
		$default_form = intval( $cbxmcratingreview_setting->get_option( 'default_form', 'cbxmcratingreview_common_config', 0 ) );

		$form_title = wp_unslash($item['name']);

		if($default_form > 0 && $default_form == $form_id){
			$form_title .= ' '.esc_html__('(Default)', 'cbxmcratingreview');
		}

		return '<a href="' . admin_url( 'admin.php?page=cbxmcratingreviewformlist&view=addedit&id=' . $item['id'] ) . '" title="' . esc_html__( 'Edit Form', 'cbxmcratingreview' ) . '">' .esc_attr( $form_title ). '</a>';
	}//end method column_status

	/**
	 * Callback for column 'id'
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_id( $item ) {
		return intval($item['id']) ;
	}

	/**
	 * Callback for column 'status'
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_status( $item ) {
		$status     = intval( $item['status'] );
		$status_arr = CBXMCRatingReviewHelper::FormStatusOptions();

		return isset( $status_arr[ $status ] ) ? $status_arr[ $status ] : $status;
	}//end method column_status

	/**
	 * Callback for column 'post_types'
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_post_types( $item ) {
		$all_post_types = CBXMCRatingReviewHelper::post_types( true );

		$item_extrafields = isset( $item['extrafields'] ) ? maybe_unserialize( $item['extrafields'] ) : array();
		$item_post_types  = isset( $item_extrafields['post_types'] ) ? $item_extrafields['post_types'] : array();

		$selected_posts = array();
		foreach ( $item_post_types as $item_post_type ) {
			$selected_posts[] = isset( $all_post_types[ $item_post_type ] ) ? $all_post_types[ $item_post_type ] : $item_post_type;
		}

		return implode( ",", $selected_posts );
	}//column_post_types

	/**
	 * Callback for column 'auto_integration'
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_auto_integration( $item ) {
		$item_extrafields = isset( $item['extrafields'] ) ? maybe_unserialize( $item['extrafields'] ) : array();
		$auto_integration = isset( $item_extrafields['enable_auto_integration'] ) ? intval( $item_extrafields['enable_auto_integration'] ) : 0;

		return ( $auto_integration ) ? esc_html__( 'On', 'cbxmcratingreview' ) : esc_html__( 'Off', 'cbxmcratingreview' );
	}//column_auto_integration




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
			case 'name':
				return $item[ $column_name ];
			case 'status':
				return $item[ $column_name ];
			case 'post_types':
				return $item[ $column_name ];
			case 'auto_integration':
				return $item[ $column_name ];
			default:
				//return print_r( $item, true ); //Show the whole array for troubleshooting purposes
				echo apply_filters( 'cbxmcratingreview_admin_form_listing_column_default', $item, $column_name );
		}
	}//end method column_default

	function get_columns() {
		$columns = array(
			'cb'               => '<input type="checkbox" />', //Render a checkbox instead of text
			'name'             => esc_html__( 'Title', 'cbxmcratingreview' ),
			'id'               => esc_html__( 'Form ID', 'cbxmcratingreview' ),
			'status'           => esc_html__( 'Status', 'cbxmcratingreview' ),
			'post_types'       => esc_html__( 'Post Types', 'cbxmcratingreview' ),
			'auto_integration' => esc_html__( 'Auto Integration', 'cbxmcratingreview' )
		);

		return apply_filters( 'cbxmcratingreview_admin_form_listing_columns', $columns );
	}//end method get_columns


	function get_sortable_columns() {
		$sortable_columns = array(
			'id'     => array( 'forms.id', false ), //true means it's already sorted
			'name'   => array( 'forms.name', false ),
			'status' => array( 'forms.status', false ),
		);

		return apply_filters( 'cbxmcratingreview_admin_form_listing_sortable_columns', $sortable_columns );
	}//end method get_sortable_columns


	function get_bulk_actions() {
		//$status_arr           = CBXMCRatingReviewHelper::FormStatusOptions();
		$status_arr           = array();
		$status_arr['delete'] = esc_html__( 'Delete', 'cbxmcratingreview' );

		$bulk_actions = apply_filters( 'cbxmcratingreview_form_bulk_action', $status_arr );

		return $bulk_actions;
	}//end method get_bulk_actions

	function process_bulk_action() {

		$new_status = $this->current_action();

		if ( $new_status == - 1 ) {
			return;
		}


		//Detect when a bulk action is being triggered...
		if ( ! empty( $_REQUEST['cbxmcratingreviewreviewform'] ) ) {
			global $wpdb;
			//$table_cbxmcratingreview_review = $wpdb->prefix . 'cbxmcratingreview_log';
			$table_cbxmcratingreview_form = $wpdb->prefix . 'cbxmcratingreview_form';


			$results = $_REQUEST['cbxmcratingreviewreviewform'];
			foreach ( $results as $id ) {

				$id = intval( $id );

				$form_info = CBXMCRatingReviewHelper::getRatingForm( $id );


				if ( 'delete' === $new_status ) {
					do_action( 'cbxmcratingreview_form_delete_before', $form_info );

					$delete_status = $wpdb->query( $wpdb->prepare( "DELETE FROM $table_cbxmcratingreview_form WHERE id=%d", $id ) );

					if ( $delete_status !== false ) {
						do_action( 'cbxmcratingreview_form_delete_after', $form_info );
					}
				}
				/*else {
					if ( is_numeric( $new_status ) && $new_status >= 0 ) {

						$old_status = $review_info['status'];

						if ( $old_status !== $new_status ) {
							$update_status = $wpdb->update(
								$table_cbxmcratingreview_form,
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
				}//if not delete action*/
			}
		}

		return;
	}//end method process_bulk_action


	/**
	 * Prepare the review log items
	 */
	function prepare_items() {
		global $wpdb; //This is used only if making any database queries

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
		$order   = ( isset( $_REQUEST['order'] ) && $_REQUEST['order'] != '' ) ? $_REQUEST['order'] : 'DESC';
		$orderby = ( isset( $_REQUEST['orderby'] ) && $_REQUEST['orderby'] != '' ) ? $_REQUEST['orderby'] : 'forms.id';

		$status = isset( $_REQUEST['status'] ) ? intval( $_REQUEST['status'] ) : '';
		$data   = $this->getLogData( $search, $status, $id, $orderby, $order, $per_page, $current_page );

		$total_items = intval( $this->getLogDataCount( $search, $status, $id ) );

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
	public function getLogData( $search = '', $status = '', $id = 0, $orderby = 'forms.id', $order = 'DESC', $perpage = 20, $page = 1 ) {

		global $wpdb;

		$table_cbxmcratingreview_form = $wpdb->prefix . 'cbxmcratingreview_form';

		$sql_select = "forms.* ";

		$sql_select = apply_filters( 'cbxmcratingreview_admin_forms_listing_select', $sql_select, $search, $status, $orderby, $order, $id, $perpage, $page );

		$where_sql = '';

		if ( $search != '' ) {
			if ( $where_sql != '' ) {
				$where_sql .= ' AND ';
			}
			$where_sql .= $wpdb->prepare( " forms.name LIKE '%%%s%%' ", $search );
		}

		if ( is_numeric( $status ) ) {
			$where_sql .= ( ( $where_sql != '' ) ? ' AND ' : '' ) . $wpdb->prepare( 'forms.status=%d', $status );
		}


		$where_sql = apply_filters( 'cbxmcratingreview_admin_forms_listing_where', $where_sql, $search, $status, $orderby, $order, $id, $perpage, $page );

		if ( $where_sql == '' ) {
			$where_sql = '1';
		}

		$start_point = ( $page * $perpage ) - $perpage;
		$limit_sql   = "LIMIT";
		$limit_sql   .= ' ' . $start_point . ',';
		$limit_sql   .= ' ' . $perpage;

		$sortingOrder = " ORDER BY $orderby $order ";

		$data = $wpdb->get_results( "SELECT $sql_select FROM $table_cbxmcratingreview_form as forms WHERE  $where_sql $sortingOrder  $limit_sql", 'ARRAY_A' );

		return $data;
	}//end method getLogData

	/**
	 * Review logs data count
	 *
	 * @param string $search
	 * @param string $status
	 * @param int $id
	 *
	 * @return null|string
	 */
	public function getLogDataCount( $search = '', $status = '', $id = 0 ) {

		global $wpdb;


		$table_cbxmcratingreview_form = $wpdb->prefix . 'cbxmcratingreview_form';

		$sql_select = "SELECT COUNT(*) FROM $table_cbxmcratingreview_form as forms";

		$where_sql = '';

		if ( $search != '' ) {
			if ( $where_sql != '' ) {
				$where_sql .= ' AND ';
			}

			$where_sql .= $wpdb->prepare( " forms.name LIKE '%%%s%%' ", $search );
		}

		if ( is_numeric( $status ) ) {
			$where_sql .= ( ( $where_sql != '' ) ? ' AND ' : '' ) . $wpdb->prepare( 'forms.status=%d', $status );
		}


		$where_sql = apply_filters( 'cbxmcratingreview_admin_forms_listing_where_total', $where_sql, $search, $status, $id );

		if ( $where_sql == '' ) {
			$where_sql = '1';
		}


		$count = $wpdb->get_var( "$sql_select WHERE  $where_sql" );

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
	}//end method single_row

	/**
	 * Message to be displayed when there are no items
	 *
	 * @since  3.1.0
	 * @access public
	 */
	public function no_items() {
		echo '<div class="notice notice-warning inline "><p>' . esc_html__( 'No form found. Please change your search criteria for better result.', 'cbxmcratingreview' ) . '</p></div>';
	}//end method no_items

	/**
	 * Set the $setting if null and return
	 *
	 * @return CBXMCRatingReviewSettings
	 */
	public function getSetting() {
		if ( $this->settings === null ) {
			$this->settings = new CBXMCRatingReviewSettings();
		}

		return $this->settings;
	}//end method getSetting
}//end class CBXMCRatingReviewForm_List_Table