<?php

// Prevent direct file access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Most rated posts widgets
 *
 * Class CBXMCRatingReviewMRPostsWidget
 */
class CBXMCRatingReviewMRPostsWidget extends WP_Widget {

	/**
	 *
	 * Unique identifier for your widget.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * widget file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $widget_slug = 'cbxmcratingreviewmrposts-widget'; //main parent plugin's language file
	/*--------------------------------------------------*/
	/* Constructor
	/*--------------------------------------------------*/

	/**
	 * Specifies the classname and description, instantiates the widget,
	 * loads localization files, and includes necessary stylesheets and JavaScript.
	 */
	public function __construct() {

		parent::__construct(
			$this->get_widget_slug(),
			esc_html__( 'CBXMCRR: Most Rated Posts', 'cbxmcratingreview' ),
			array(
				'classname'   => 'widget-cbxmcratingreviewmrposts',
				'description' => esc_html__( 'Most rated posts as per different filter widget', 'cbxmcratingreview' )
			)
		);

	} // end constructor

	/**
	 * Return the widget slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_widget_slug() {
		return $this->widget_slug;
	}

	/*--------------------------------------------------*/
	/* Widget API Functions
	/*--------------------------------------------------*/

	/**
	 * Outputs the content of the widget.
	 *
	 * @param array args  The array of form elements
	 * @param array instance The current instance of the widget
	 */
	public function widget( $args, $instance ) {

		// Check if there is a cached output
		/*$cache = wp_cache_get( $this->get_widget_slug(), 'widget' );

		if ( ! is_array( $cache ) ) {
			$cache = array();
		}*/

		if ( ! isset ( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		/*if ( isset ( $cache[ $args['widget_id'] ] ) ) {
			return print $cache[ $args['widget_id'] ];
		}*/

		// go on with your widget logic, put everything into a string and …

		extract( $args, EXTR_SKIP );

		$widget_string = $before_widget;

		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? esc_html__( 'Most Rated Posts', 'cbxmcratingreview' ) : $instance['title'], $instance, $this->id_base );
		// Defining the Widget Title
		if ( $title ) {
			$widget_string .= $args['before_title'] . $title . $args['after_title'];
		} else {
			$widget_string .= $args['before_title'] . $args['after_title'];
		}

		//ob_start();

		$instance = apply_filters( 'cbxmcratingreviewmrposts_widget_instance_display', $instance );

		$form_id = isset($instance['form_id'])?  intval($instance['form_id']) : 0;
		$limit   = ( isset( $instance['limit'] ) && ! empty( $instance['limit'] ) ) ? intval( $instance['limit'] ) : 10;
		$orderby = isset( $instance['orderby'] ) ? esc_attr( $instance['orderby'] ) : 'avg_rating'; //avg_rating, total_count, post_id
		$order   = isset( $instance['order'] ) ? esc_attr( $instance['order'] ) : 'DESC';
		$type    = isset( $instance['type'] ) ? $instance['type'] : 'post';


		//extract( $instance );

		$scope = 'widget';


		$data_posts = cbxmcratingreview_most_rated_posts( $form_id, $limit, $orderby, $order, $type );     //variable name $data_posts  is important for template files

		cbxmcratingreview_AddJsCss();

		// Display the most rated post link
		$content_html = '';
		ob_start();

		//include( apply_filters( 'cbxmcratingreview_tpl_most_rated_posts', CBXMCRATINGREVIEW_ROOT_PATH . 'templates/widgets/most_rated_posts.php' ) );
		include (cbxmcratingreview_locate_template('widgets/most_rated_posts.php'));

		$content_html = ob_get_contents();
		ob_end_clean();

		$widget_string .= $content_html;

		$widget_string .= $after_widget;

		/*$cache[ $args['widget_id'] ] = $widget_string;

		wp_cache_set( $this->get_widget_slug(), $cache, 'widget' );

		print $widget_string;*/

		echo $widget_string;

	}//end widget

	/*public function flush_widget_cache() {
		wp_cache_delete( $this->get_widget_slug(), 'widget' );
	}*/

	/**
	 * Processes the widget's options to be saved.
	 *
	 * @param array new_instance The new instance of values to be generated via the update.
	 * @param array old_instance The previous instance of values before the update.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title']   = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['form_id']   = intval( $new_instance['form_id'] );
		$instance['limit']   = intval( $new_instance['limit'] );
		$instance['orderby'] = esc_attr( $new_instance['orderby'] );
		$instance['order']   = esc_attr( $new_instance['order'] );
		$instance['type']    = $new_instance['type'];

		$instance = apply_filters( 'cbxmcratingreviewmrposts_widget_instance_update', $instance, $new_instance );

		return $instance;

	}//end method update

	/**
	 * Generates the administration form for the widget.
	 *
	 * @param array instance The array of keys and values for the widget.
	 */
	public function form( $instance ) {

		$fields = array(
			'title'   => esc_html__( 'Most Rated Posts', 'cbxmcratingreview' ),
			'form_id'   => 0,
			'limit'   => 10,
			'orderby' => 'avg_rating', //avg_rating, total_count, post_id
			'order'   => 'DESC', //DESC, ASC
			'type'    => 'post'
		);

		//$fields = apply_filters( 'cbxmcratingreviewmrposts_widget_form_fields', $fields );

		$instance = wp_parse_args(
			(array) $instance,
			$fields
		);


		$instance = apply_filters( 'cbxmcratingreviewmrposts_widget_instance_form', $instance );

		extract( $instance, EXTR_SKIP );

		$forms = CBXMCRatingReviewHelper::getRatingForms();

		// Display the admin form
		include( plugin_dir_path( __FILE__ ) . 'views/admin.php' );

	}//end method form

} // end class CBXMCRatingReviewMRPostsWidget