<?php

	/**
	 * The public-facing functionality of the plugin.
	 *
	 * @link       https://codeboxr.com
	 * @since      1.0.0
	 *
	 * @package    CBXMCRatingReview
	 * @subpackage CBXMCRatingReview/public
	 */

	/**
	 * The public-facing functionality of the plugin.
	 *
	 * Defines the plugin name, version, and two examples hooks for how to
	 * enqueue the public-facing stylesheet and JavaScript.
	 *
	 * @package    CBXMCRatingReview
	 * @subpackage CBXMCRatingReview/public
	 * @author     Sabuj Kundu <sabuj@codeboxr.com>
	 */
	class CBXMCRatingReview_Public {

		/**
		 * The ID of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string $plugin_name The ID of this plugin.
		 */
		private $plugin_name;

		/**
		 * The version of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string $version The current version of this plugin.
		 */
		private $version;


		public $mail_format;

		public $mail_from_address;

		public $mail_from_name;

		public $setting;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 *
		 * @param      string $plugin_name The name of the plugin.
		 * @param      string $version     The version of this plugin.
		 */
		public function __construct( $plugin_name, $version ) {

			$this->plugin_name = $plugin_name;
			$this->version     = $version;
			$this->setting     = new CBXMCRatingReviewSettings();
		}//end of constructor

		/**
		 * Init all shortcodes
		 */
		public function init_shortcodes() {
			//show rating form
			add_shortcode( 'cbxmcratingreview_reviewform', array( $this, 'reviewform_shortcode' ) );
			//show rating form avg by post id
			add_shortcode( 'cbxmcratingreview_postavgrating', array($this,	'postavgrating_shortcode') );
			//show ratings by post id
			add_shortcode( 'cbxmcratingreview_postreviews', array( $this, 'postreviews_shortcode' ) );

			//dashboard, edit and single review sharing page
			add_shortcode( 'cbxmcratingreview_userdashboard', array($this, 'userdashboard_shortcode') );
			add_shortcode( 'cbxmcratingreview_singlereview', array($this,'singlereview_shortcode') );
			add_shortcode( 'cbxmcratingreview_editreview', array($this, 	'editreview_shortcode') );


			add_shortcode( 'cbxmcratingreviewmrposts', array( $this, 'cbxmcratingreviewmrposts_shortcode' ) );
			add_shortcode( 'cbxmcratingreviewlratings', array( $this, 'cbxmcratingreviewlratings_shortcode' ) );

			//add_shortcode( 'cbxmcratingreviewlratingsbyu', array($this, 'cbxmcratingreviewlratings_by_user_shortcode') );

			/*
			//add shortcode for frontend cbxmcratingreview latest ratings lists, same cbxmcratingreview latest ratings is available as widget


			//add shortcode for frontend cbxmcratingreview user latest ratings lists, same cbxmcratingreview user latest ratings is available as widget
			add_shortcode( 'cbxmcratingreview_userlatestratings', array(
				$this,
				'cbxmcratingreview_userlatestratings_shortcode'
			) );

			//add shortcode for frontend cbxmcratingreview author most rated post lists, same cbxmcratingreview author most rated post is available as widget
			add_shortcode( 'cbxmcratingreview_mostratedpost', array(
				$this,
				'cbxmcratingreview_mostratedpost_shortcode'
			) );

			//add shortcode for frontend cbxmcratingreview author post latest ratings lists, same cbxmcratingreview author post latest ratings is available as widget
			add_shortcode( 'cbxmcratingreview_authorpostlatestratings', array(
				$this,
				'cbxmcratingreview_authorpostlatestratings_shortcode'
			) );*/
		}//end method init_shortcodes

		/**
		 * Init all widgets
		 */
		public function init_register_widget() {
			register_widget( 'CBXMCRatingReviewMRPostsWidget' ); //most rated post widget
			register_widget( 'CBXMCRatingReviewLRatingsWidget' ); //latest ratings widget
		}//end method init_register_widget

		/**
		 * Rating Form shortcode callback
		 *
		 * @param $atts
		 *
		 * @return string
		 */
		public function reviewform_shortcode( $atts ) {
			global $post;

			$post_id = $post->ID;
			$cbxmcratingreview_setting = new CBXMCRatingReviewSettings();
			$default_form = intval( $cbxmcratingreview_setting->get_option( 'default_form', 'cbxmcratingreview_common_config', 0 ) );

			$atts                      = shortcode_atts(
				array(
					'form_id' => intval($default_form),
					'post_id' => $post_id,
				), $atts, 'cbxmcratingreview_reviewform' );


			$form_id = isset( $atts['form_id'] ) ? intval( $atts['form_id'] ) : 0;
			$post_id = isset( $atts['post_id'] ) ? intval( $atts['post_id'] ) : 0;

			if ( function_exists( 'cbxmcratingreview_reviewformRender' ) ) {
				return cbxmcratingreview_reviewformRender( $form_id, $post_id );
			}

			return '';
		}//end method reviewform_shortcode

		/**
		 * Post avg info shortcode call back
		 *
		 * @param $atts
		 *
		 * @return false|string
		 */
		public function postavgrating_shortcode( $atts ) {
			global $post;

			$post_id = $post->ID;
			$cbxmcratingreview_setting = new CBXMCRatingReviewSettings();
			$default_form = intval( $cbxmcratingreview_setting->get_option( 'default_form', 'cbxmcratingreview_common_config', 0 ) );

			$atts                      = shortcode_atts(
				array(
					'form_id' => $default_form,
					'post_id' => $post_id,
					'details' => 0
				), $atts, 'cbxmcratingreview_postavgrating' );

			$form_id = isset( $atts['form_id'] ) ? intval( $atts['form_id'] ) : 0;
			$post_id = isset( $atts['post_id'] ) ? intval( $atts['post_id'] ) : 0;
			$details = isset( $atts['details'] ) ? intval( $atts['details'] ) : 0;

			if($details){
				if ( function_exists( 'cbxmcratingreview_postAvgDetailsRatingRender' ) ) {
					return cbxmcratingreview_postAvgDetailsRatingRender( $form_id, $post_id );
				}
			}
			else{
				if ( function_exists( 'cbxmcratingreview_postAvgRatingRender' ) ) {
					return cbxmcratingreview_postAvgRatingRender( $form_id, $post_id );
				}
			}


			return '';
		}//end method postavgrating_shortcode

		/**
		 * Post reviews shortcode callback
		 *
		 * @param $atts
		 *
		 * @return false|string
		 */
		public function postreviews_shortcode( $atts ) {
			global $post;

			$post_id = $post->ID;

			$cbxmcratingreview_setting = new CBXMCRatingReviewSettings();
			$perpage_default           = intval( $cbxmcratingreview_setting->get_option( 'default_per_page', 'cbxmcratingreview_common_config', 10 ) );
			$show_filter_default       = intval( $cbxmcratingreview_setting->get_option( 'show_review_filter', 'cbxmcratingreview_common_config', 1 ) );
			$default_form = intval( $cbxmcratingreview_setting->get_option( 'default_form', 'cbxmcratingreview_common_config', 0 ) );

			$atts = shortcode_atts(
				array(
					'form_id'     => $default_form,
					'post_id'     => $post_id,
					'orderby'     => 'id', //id, total_count, post_id
					'order'       => 'DESC', //DESC, ASC,
					'score'       => '',
					'perpage'     => $perpage_default,
					'show_filter' => $show_filter_default,
					'show_more'   => 1
				), $atts, 'cbxmcratingreview_postreviews' );


			$form_id     = isset( $atts['form_id'] ) ? intval( $atts['form_id'] ) : 0;
			$post_id     = isset( $atts['post_id'] ) ? intval( $atts['post_id'] ) : 0;
			$orderby     = isset( $atts['orderby'] ) ? esc_attr( $atts['orderby'] ) : 'id';
			$order       = isset( $atts['order'] ) ? esc_attr( $atts['order'] ) : 'DESC';
			$score       = isset( $atts['score'] ) ? esc_attr( $atts['score'] ) : '';
			$perpage     = isset( $atts['perpage'] ) ? intval( $atts['perpage'] ) : $perpage_default;
			$show_filter = isset( $atts['show_filter'] ) ? intval( $atts['show_filter'] ) : $show_filter_default;
			$show_more   = isset( $atts['show_more'] ) ? intval( $atts['show_more'] ) : 1;

			$output = '';

			if ( $show_filter ) {
				if ( function_exists( 'cbxmcratingreview_postReviewsFilterRender' ) ) {
					$output .= cbxmcratingreview_postReviewsFilterRender( $form_id, $post_id, $perpage, 1, $score, $orderby, $order );
				}
			}

			if ( function_exists( 'cbxmcratingreview_postReviewsRender' ) ) {
				$output .= cbxmcratingreview_postReviewsRender( $form_id, $post_id, $perpage, 1, $score, $orderby, $order, $show_more );
			}

			return $output;
		}//end method postreviews_shortcode


		/**
		 * User rating frontend dashboard
		 *
		 * @param $atts
		 *
		 * @return false|string
		 */
		public function userdashboard_shortcode( $atts ) {
			$cbxmcratingreview_setting = new CBXMCRatingReviewSettings();
			$atts                      = shortcode_atts(
				array(
					'form_id' => '', //if form id is given then only reviews for that form wil show
					'orderby' => 'id', //id, post_id, post_type, score, status
					'order'   => 'DESC', //DESC, ASC,
					'perpage' => 20,
				), $atts, 'cbxmcratingreview_userdashboard' );

			$orderby = isset( $atts['orderby'] ) ? esc_attr( $atts['orderby'] ) : 'id'; // //id, post_id, score, status
			$order   = isset( $atts['order'] ) ? esc_attr( $atts['order'] ) : 'DESC';
			$perpage = isset( $atts['perpage'] ) ? intval( $atts['perpage'] ) : 20;
			$form_id = isset( $atts['form_id'] ) ? $atts['form_id'] : '';

			$dashboard_html = '';
			cbxmcratingreview_AddJsCss();

			ob_start();

			//include( apply_filters( 'cbxmcratingreview_tpl_rating-review-user-dashboard', CBXMCRATINGREVIEW_ROOT_PATH . 'templates/rating-review-user-dashboard.php' ) );
			include (cbxmcratingreview_locate_template('rating-review-user-dashboard.php'));

			$dashboard_html = ob_get_contents();
			ob_end_clean();

			return $dashboard_html;

		}//end method userdashboard_shortcode

		/**
		 * Single review render using shortcode
		 *
		 * @param $atts
		 *
		 * @return string
		 */
		public function singlereview_shortcode( $atts ) {
			$cbxmcratingreview_setting = new CBXMCRatingReviewSettings();
			$atts                      = shortcode_atts(
				array(
					'review_id' => 0,
				), $atts, 'cbxmcratingreview_singlereview' );

			$single_review_html = '';

			if ( function_exists( 'cbxmcratingreview_singleReviewRender' ) ) {
				//at first take from shortcode
				$review_id = isset( $atts['review_id'] ) ? intval( $atts['review_id'] ) : 0;
				if ( $review_id == 0 ) {
					//now take from url
					$review_id = isset( $_GET['review_id'] ) ? intval( $_GET['review_id'] ) : 0;
				}

				if ( $review_id > 0 ) {
					$post_review        = cbxmcratingreview_singleReview( $review_id );
					$post_id = intval($post_review['post_id']);
					$post_title = get_the_title($post_id);
					$post_link = get_permalink($post_id);

					$single_review_html .= '<p>'.esc_html__('Reviewed', 'cbxmcratingreview').' : <a target="_blank" href="'.esc_url($post_link).'">'.esc_attr($post_title).'</a></p>';

					$single_review_html .= '<ul class="cbxmcratingreview_review_list_items">';
					$single_review_html .= '<li id="cbxmcratingreview_review_list_item_' . intval( $review_id ) . '" class="' . apply_filters( 'cbxmcratingreview_review_list_item_class', 'cbxmcratingreview_review_list_item' ) . '">';
					$single_review_html .= cbxmcratingreview_singleReviewRender( $post_review );

					$single_review_html .= '</li>';
					$single_review_html .= '</ul>';
				} else {
					$single_review_html .= '<div class="alert alert-danger" role="alert">' . esc_html__( 'Sorry, review not found or unpublished', 'cbxmcratingreview' ) . '</div>';
				}
			}

			return $single_review_html;

		}//end method singlereview_shortcode

		/**
		 * Single review edit render using shortcode
		 *
		 * @param $atts
		 *
		 * @return string
		 */
		public function editreview_shortcode( $atts ) {
			$cbxmcratingreview_setting = new CBXMCRatingReviewSettings();
			$atts                      = shortcode_atts(
				array(
					'review_id' => 0,
				), $atts, 'cbxmcratingreview_editreview' );

			$single_review_edit_html = '';

			if ( function_exists( 'cbxmcratingreview_singleReviewEditRender' ) ) {

				$review_id = isset( $atts['review_id'] ) ? intval( $atts['review_id'] ) : 0;
				if ( $review_id == 0 ) {
					//now take from url
					$review_id = isset( $_GET['review_id'] ) ? intval( $_GET['review_id'] ) : 0;
				}

				if ( $review_id > 0 ) {
					$single_review_edit_html = cbxmcratingreview_singleReviewEditRender( $review_id );
				} else {
					$single_review_edit_html .= '<div class="alert alert-danger" role="alert">' . esc_html__( 'Sorry, review not found', 'cbxmcratingreview' ) . '</div>';
				}
			}

			return $single_review_edit_html;
		}//end method editreview_shortcode


		/**
		 * Shortcode callback for most rated post
		 */
		public function cbxmcratingreviewmrposts_shortcode( $atts ) {
			$cbxmcratingreview_setting = new CBXMCRatingReviewSettings();
			$atts                      = shortcode_atts(
				array(
					'form_id' => '', //if form id is given then only reviews for that form wil show
					'scope'   => 'shortcode',
					'limit'   => 10,
					'orderby' => 'avg_rating', //avg_rating, total_count, post_id
					'order'   => 'DESC', //DESC, ASC,
					'type'    => 'post'
				), $atts, 'cbxmcratingreviewmrposts' );

			$scope   = ( isset( $atts['scope'] ) && $atts['scope'] != '' ) ? esc_attr( $atts['scope'] ) : 'shortcode';
			$form_id = intval( $atts['form_id'] );
			$limit   = intval( $atts['limit'] );
			$orderby = isset( $atts['orderby'] ) ? $atts['orderby'] : 'avg_rating';
			$order   = isset( $atts['order'] ) ? esc_attr( $atts['order'] ) : 'DESC';
			$type    = isset( $atts['type'] ) ? esc_attr( $atts['type'] ) : 'post';

			cbxmcratingreview_AddJsCss();

			$data_posts = cbxmcratingreview_most_rated_posts( $form_id, $limit, $orderby, $order, $type ); //variable name $data_posts  is important for template files

			// Display the most rated post link
			ob_start();

			//include( apply_filters( 'cbxmcratingreview_tpl_most_rated_posts', CBXMCRATINGREVIEW_ROOT_PATH . 'templates/widgets/most_rated_posts.php' ) );
			include (cbxmcratingreview_locate_template('widgets/most_rated_posts.php'));

			$content = ob_get_contents();
			ob_end_clean();

			return $content;
		}//end method cbxmcratingreviewmrposts_shortcode

		/**
		 * Shortcode callback for latest ratings
		 */
		public function cbxmcratingreviewlratings_shortcode( $atts ) {
			$cbxmcratingreview_setting = new CBXMCRatingReviewSettings();

			$atts = shortcode_atts(
				array(
					'form_id' => '', //if form id is given then only reviews for that form wil show
					'scope'   => 'shortcode',
					'limit'   => 10,
					'orderby' => 'id', //id, score, post_id
					'order'   => 'DESC',
				), $atts, 'cbxmcratingreviewlratings' );

			$scope = ( isset( $atts['scope'] ) && $atts['scope'] != '' ) ? esc_attr( $atts['scope'] ) : 'shortcode';

			$form_id   = intval( $atts['form_id'] );
			$limit   = intval( $atts['limit'] );
			$orderby = isset( $atts['orderby'] ) ? $atts['orderby'] : 'id'; //id, score, post_id
			$order   = isset( $atts['order'] ) ? esc_attr( $atts['order'] ) : 'DESC';
			$type    = isset( $atts['type'] ) ? esc_attr( $atts['type'] ) : 'post';


			cbxmcratingreview_AddJsCss();

			$data_posts = cbxmcratingreview_lastest_ratings( $form_id, $limit, $orderby, $order, $type ); //variable name $data_posts  is important for template files

			// Display the most rated post link
			ob_start();

			//include( apply_filters( 'cbxmcratingreview_tpl_lastest_ratings', CBXMCRATINGREVIEW_ROOT_PATH . 'templates/widgets/lastest_ratings.php' ) );
			include (cbxmcratingreview_locate_template('widgets/lastest_ratings.php'));

			$content = ob_get_contents();
			ob_end_clean();

			return $content;
		}//end method cbxmcratingreviewlratings_shortcode

		/**
		 * Shortcode callback for user latest ratings
		 */
		/*public function cbxmcratingreviewlratings_by_user_shortcode( $atts ) {
			//global $post;

			$atts = shortcode_atts(
				array(
					'scope'     => 'shortcode',
					'per_page'  => 10,
					'page'      => 1,
					'load_more' => 0,
					'orderby'   => 'avg_rating',
					'order'     => 'DESC',
				), $atts, 'cbxmcratingreview' );

			$scope     = ( isset( $atts['scope'] ) && $atts['scope'] != '' ) ? esc_attr( $atts['scope'] ) : 'shortcode';
			$per_page  = intval( $atts['per_page'] );
			$page      = intval( $atts['page'] );
			$load_more = isset( $atts['load_more'] ) ? intval( $atts['load_more'] ) : 0;
			$user_id   = isset( $atts['user_id'] ) ? intval( $atts['user_id'] ) : 0;

			if ( $user_id <= 0 ) {
				$user_id = intval( get_current_user_id() );
			}

			if ( $user_id > 0 ) {
				$user_latest_ratings = cbxmcratingreview_cbxmcratingreviewlratings( $per_page, $user_id );
				if ( sizeof( $user_latest_ratings ) > 0 ) {
					// Display the latest ratings link
					ob_start();
					include( plugin_dir_path( __FILE__ ) . '../widgets/userlatestratings-widget/views/public.php' );
					$content = ob_get_contents();
					ob_end_clean();

					return $content;
				}
			}

			return '';
		}*/


		/**
		 * Shortcode callback for author most rated post
		 */
		/*public function cbxmcratingreview_authormostratedpost_shortcode( $atts ) {
			//global $post;

			$atts = shortcode_atts(
				array(
					'scope'   => 'shortcode',
					'limit'   => 10,
					'orderby' => 'avg_rating',
					'order'   => 'DESC',
				), $atts, 'cbxmcratingreview' );

			$scope   = ( isset( $atts['scope'] ) && $atts['scope'] != '' ) ? esc_attr( $atts['scope'] ) : 'shortcode';
			$limit   = intval( $atts['limit'] );
			$orderby = isset( $atts['orderby'] ) ? esc_attr( $atts['orderby'] ) : 'avg_rating';
			$order   = isset( $atts['order'] ) ? esc_attr( $atts['order'] ) : 'DESC';
			$user_id = isset( $atts['user_id'] ) ? intval( $atts['user_id'] ) : 0;

			if ( $user_id <= 0 ) {
				$user_id = intval( get_current_user_id() );
			}

			if ( $user_id > 0 ) {
				$author_most_rated_post = cbxmcratingreview_mostRatedPosts( $limit, $orderby, $order, $user_id );
				if ( sizeof( $author_most_rated_post ) > 0 ) {
					// Display the most rated post link
					ob_start();
					include( plugin_dir_path( __FILE__ ) . '../widgets/authormostratedpost-widget/views/public.php' );
					$content = ob_get_contents();
					ob_end_clean();

					return $content;
				}
			}

			return '';
		}*/


		/**
		 * Shortcode callback for author post latest ratings
		 */
		/*public function cbxmcratingreview_authorpostlatestratings_shortcode( $atts ) {
			//global $post;

			$atts = shortcode_atts(
				array(
					'scope'   => 'shortcode',
					'limit'   => 10,
					'orderby' => 'avg_rating',
					'order'   => 'DESC',
				), $atts, 'cbxmcratingreview' );

			$scope   = ( isset( $atts['scope'] ) && $atts['scope'] != '' ) ? esc_attr( $atts['scope'] ) : 'shortcode';
			$limit   = intval( $atts['limit'] );
			$user_id = isset( $atts['user_id'] ) ? intval( $atts['user_id'] ) : 0;

			if ( $user_id <= 0 ) {
				$user_id = intval( get_current_user_id() );
			}

			if ( $user_id > 0 ) {
				$latest_ratings = cbxmcratingreview_authorpostlatestRatings( $limit, $user_id );
				if ( sizeof( $latest_ratings ) > 0 ) {
					// Display the latest ratings link
					ob_start();
					include( plugin_dir_path( __FILE__ ) . '../widgets/latestratings-widget/views/public.php' );
					$content = ob_get_contents();
					ob_end_clean();

					return $content;
				}
			}

			return '';
		}*/

		/**
		 * Register the stylesheets for the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles() {
			$cbxmcratingreview_setting = $this->setting;


			do_action( 'cbxmcratingreview_reg_styles_before' );

			$ratingform_css_dep = array();
			$common_css_dep     = array();


			wp_register_style( 'jquery-ui', plugin_dir_url( __FILE__ ) . '../assets/css/ui-lightness/jquery-ui.min.css', array(), $this->version );
			wp_register_style( 'jquery-cbxmcratingreview-raty', plugin_dir_url( __FILE__ ) . '../assets/css/jquery.cbxmcratingreview_raty.css', array(), $this->version, 'all' );


			$ratingform_css_dep[] = 'jquery-ui';
			$ratingform_css_dep[] = 'jquery-cbxmcratingreview-raty';

			$common_css_dep[] = 'jquery-cbxmcratingreview-raty';

			$common_css_dep = apply_filters( 'cbxmcratingreview_common_css_dep', $common_css_dep );

			do_action( 'cbxmcratingreview_reg_styles' );

			wp_register_style( 'cbxmcratingreview-public', plugin_dir_url( __FILE__ ) . '../assets/css/cbxmcratingreview-public.css', $common_css_dep, $this->version, 'all' );
			$ratingform_css_dep[] = 'cbxmcratingreview-public';

			$ratingform_css_dep = apply_filters( 'cbxmcratingreview_ratingform_css_dep', $ratingform_css_dep );

			wp_register_style( 'cbxmcratingreview-ratingform', plugin_dir_url( __FILE__ ) . '../assets/css/cbxmcratingreview-ratingform.css', $ratingform_css_dep, $this->version, 'all' );

			do_action( 'cbxmcratingreview_reg_styles_after' );
		}//end method enqueue_styles

		/**
		 * Register the JavaScript for the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts() {
			$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

			$cbxmcratingreview_setting = $this->setting;

			$require_headline = intval( $cbxmcratingreview_setting->get_option( 'require_headline', 'cbxmcratingreview_common_config', 1 ) );
			$require_comment  = intval( $cbxmcratingreview_setting->get_option( 'require_comment', 'cbxmcratingreview_common_config', 1 ) );
			$half_rating      = intval( $cbxmcratingreview_setting->get_option( 'half_rating', 'cbxmcratingreview_common_config', 0 ) );


			$ratingform_js_dep     = array();
			$ratingeditform_js_dep = array();
			$common_js_dep         = array();


			do_action( 'cbxmcratingreview_reg_scripts_before' );

			//common for everywhere
			wp_register_script( 'cbxmcratingreview-events', plugin_dir_url( __FILE__ ) . '../assets/js/cbxmcratingreview-events.js', array(), $this->version, true );
			wp_register_script( 'jquery-cbxmcratingreview-raty', plugin_dir_url( __FILE__ ) . '../assets/js/jquery.cbxmcratingreview_raty.js', array( 'jquery' ), $this->version, true );


			wp_register_script( 'jquery-validate', plugin_dir_url( __FILE__ ) . '../assets/js/jquery.validate' . $suffix . '.js', array( 'jquery' ), $this->version, true );

			$ratingform_js_dep[] = 'cbxmcratingreview-events';
			$ratingform_js_dep[] = 'jquery';
			$ratingform_js_dep[] = 'jquery-ui-datepicker';
			$ratingform_js_dep[] = 'jquery-cbxmcratingreview-raty';
			$ratingform_js_dep[] = 'jquery-validate';

			$ratingeditform_js_dep[] = 'cbxmcratingreview-events';
			$ratingeditform_js_dep[] = 'jquery';
			$ratingeditform_js_dep[] = 'jquery-ui-datepicker';
			$ratingeditform_js_dep[] = 'jquery-cbxmcratingreview-raty';
			$ratingeditform_js_dep[] = 'jquery-validate';


			$common_js_dep[] = 'cbxmcratingreview-events';
			$common_js_dep[] = 'jquery';
			$common_js_dep[] = 'jquery-cbxmcratingreview-raty';


			do_action( 'cbxmcratingreview_reg_scripts' );

			$common_js_dep = apply_filters( 'cbxmcratingreview_common_js_dep', $common_js_dep );

			wp_register_script( 'cbxmcratingreview-public', plugin_dir_url( __FILE__ ) . '../assets/js/cbxmcratingreview-public.js', $common_js_dep, $this->version, true );

			$ratingform_js_dep[]     = 'cbxmcratingreview-public';
			$ratingeditform_js_dep[] = 'cbxmcratingreview-public';

			$ratingform_js_dep     = apply_filters( 'cbxmcratingreview_ratingform_js_dep', $ratingform_js_dep );
			$ratingeditform_js_dep = apply_filters( 'cbxmcratingreview_editform_js_dep', $ratingeditform_js_dep );

			wp_register_script( 'cbxmcratingreview-ratingform', plugin_dir_url( __FILE__ ) . '../assets/js/cbxmcratingreview-ratingform.js', $ratingform_js_dep, $this->version, true );
			wp_register_script( 'cbxmcratingreview-ratingeditform', plugin_dir_url( __FILE__ ) . '../assets/js/cbxmcratingreview-ratingform-frontedit.js', $ratingeditform_js_dep, $this->version, true );


			// Localize the script with new data
			$cbxmcratingreview_public_ratingform_js_vars = apply_filters( 'cbxmcratingreview_public_ratingform_js_vars', array(
				'ajaxurl'              => admin_url( 'admin-ajax.php' ),
				'nonce'                => wp_create_nonce( 'cbxmcratingreview' ),
				'rating'               => array(
					'half_rating' => $half_rating,
					'cancelHint'  => esc_html__( 'Cancel this rating!', 'cbxmcratingreview' ),
					'hints'       => CBXMCRatingReviewHelper::ratingHints(),
					'noRatedMsg'  => esc_html__( 'Not rated yet!', 'cbxmcratingreview' ),
					'img_path'    => apply_filters( 'cbxmcratingreview_star_image_url', CBXMCRATINGREVIEW_ROOT_URL . 'assets/images/stars/' )
				),
				'validation'           => array(
					'required'                        => esc_html__( 'This field is required.', 'cbxmcratingreview' ),
					'remote'                          => esc_html__( 'Please fix this field.', 'cbxmcratingreview' ),
					'email'                           => esc_html__( 'Please enter a valid email address.', 'cbxmcratingreview' ),
					'url'                             => esc_html__( 'Please enter a valid URL.', 'cbxmcratingreview' ),
					'date'                            => esc_html__( 'Please enter a valid date.', 'cbxmcratingreview' ),
					'dateISO'                         => esc_html__( 'Please enter a valid date ( ISO ).', 'cbxmcratingreview' ),
					'number'                          => esc_html__( 'Please enter a valid number.', 'cbxmcratingreview' ),
					'digits'                          => esc_html__( 'Please enter only digits.', 'cbxmcratingreview' ),
					'equalTo'                         => esc_html__( 'Please enter the same value again.', 'cbxmcratingreview' ),
					'maxlength'                       => esc_html__( 'Please enter no more than {0} characters.', 'cbxmcratingreview' ),
					'minlength'                       => esc_html__( 'Please enter at least {0} characters.', 'cbxmcratingreview' ),
					'rangelength'                     => esc_html__( 'Please enter a value between {0} and {1} characters long.', 'cbxmcratingreview' ),
					'range'                           => esc_html__( 'Please enter a value between {0} and {1}.', 'cbxmcratingreview' ),
					'max'                             => esc_html__( 'Please enter a value less than or equal to {0}.', 'cbxmcratingreview' ),
					'min'                             => esc_html__( 'Please enter a value greater than or equal to {0}.', 'cbxmcratingreview' ),
					'recaptcha'                       => esc_html__( 'Please check the captcha.', 'cbxmcratingreview' ),
					'cbxmcratingreview_multicheckbox' => esc_html__( 'Please select at least one option', 'cbxmcratingreview' ),
				),
				'review_common_config' => array(
					'require_headline' => $require_headline,
					'require_comment'  => $require_comment,
				),
				'sort_text'            => esc_html__( 'Drag and Sort', 'cbxmcratingreview' ),
				'forms'                => array()
			) );

			$cbxmcratingreview_public_common_js_vars = apply_filters( 'cbxmcratingreview_public_common_js_vars', array(
				'ajaxurl'               => admin_url( 'admin-ajax.php' ),
				'nonce'                 => wp_create_nonce( 'cbxmcratingreview' ),
				'rating'                => array(
					'half_rating' => $half_rating,
					'cancelHint'  => esc_html__( 'Cancel this rating!', 'cbxmcratingreview' ),
					'hints'       => CBXMCRatingReviewHelper::ratingHints(),
					'noRatedMsg'  => esc_html__( 'Not rated yet!', 'cbxmcratingreview' ),
					'img_path'    => apply_filters( 'cbxmcratingreview_star_image_url', CBXMCRATINGREVIEW_ROOT_URL . 'assets/images/stars/' )
				),
				'no_reviews_found_html' => '<li class="' . apply_filters( 'cbxmcratingreview_review_list_item_class_notfound_class', 'cbxmcratingreview_review_list_item cbxmcratingreview_review_list_item_notfound' ) . '"><p class="no_reviews_found">' . esc_html__( 'No reviews yet!', 'cbxmcratingreview' ) . '</p>
				</li>',
				'load_more_text'        => esc_html__( 'Load More', 'cbxmcratingreview' ),
				'load_more_busy_text'   => esc_html__( 'Loading next page ...', 'cbxmcratingreview' ),
				'delete_confirm'        => esc_html__( 'Are you sure to delete your review, this processs can not be undone ?', 'cbxmcratingreview' ),
				'delete_text'           => esc_html__( 'Delete', 'cbxmcratingreview' ),
				'delete_error'          => esc_html__( 'Sorry! delete failed!', 'cbxmcratingreview' ),
			) );

			wp_localize_script( 'cbxmcratingreview-public', 'cbxmcratingreview_public', $cbxmcratingreview_public_common_js_vars );
			wp_localize_script( 'cbxmcratingreview-ratingform', 'cbxmcratingreview_ratingform', $cbxmcratingreview_public_ratingform_js_vars );
			wp_localize_script( 'cbxmcratingreview-ratingeditform', 'cbxmcratingreview_ratingeditform', $cbxmcratingreview_public_ratingform_js_vars );

			do_action( 'cbxmcratingreview_reg_scripts_after' );

		}//end method enqueue_scripts


		/**
		 * Add all common js and css needed for review and rating
		 */
		public function enqueue_common_js_css_rating() {


			do_action( 'cbxmcratingreview_enq_common_js_css_before' );

			// enqueue styles
			wp_enqueue_style( 'jquery-cbxmcratingreview-raty' );


			// enqueue scripts
			wp_enqueue_script( 'cbxmcratingreview-events' );
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-cbxmcratingreview-raty' );

			do_action( 'cbxmcratingreview_enq_common_js_css' );

			wp_enqueue_style( 'cbxmcratingreview-public' );
			wp_enqueue_script( 'cbxmcratingreview-public' );

			do_action( 'cbxmcratingreview_enq_common_js_css_after' );

		}//end method enqueue_common_js_css_rating

		/**
		 * Add all js and css needed for review submit form
		 */
		public function enqueue_ratingform_js_css_rating() {
			$cbxmcratingreview_setting = $this->setting;

			do_action( 'cbxmcratingreview_enq_ratingform_js_css_before' );

			//enqueue styles
			wp_enqueue_style( 'jquery-ui' );
			wp_enqueue_style( 'jquery-cbxmcratingreview-raty' );


			//enqueue script
			wp_enqueue_script( 'cbxmcratingreview-events' );
			wp_enqueue_script( 'jquery' );

			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_script( 'jquery-cbxmcratingreview-raty' );
			wp_enqueue_script( 'jquery-validate' );


			do_action( 'cbxmcratingreview_enq_ratingform_js_css' );

			wp_enqueue_style( 'cbxmcratingreview-public' );
			wp_enqueue_style( 'cbxmcratingreview-ratingform' );

			wp_enqueue_script( 'cbxmcratingreview-public' );
			wp_enqueue_script( 'cbxmcratingreview-ratingform' );

			do_action( 'cbxmcratingreview_enq_ratingform_js_css_after' );

		}

		/**
		 * Add all js and css needed for review edit form
		 */
		public function enqueue_ratingeditform_js_css_rating() {
			$cbxmcratingreview_setting = $this->setting;

			do_action( 'cbxmcratingreview_enq_ratingeditform_js_css_before' );


			//enqueue styles
			wp_enqueue_style( 'jquery-ui' );
			wp_enqueue_style( 'jquery-cbxmcratingreview-raty' );


			//enqueue script
			wp_enqueue_script( 'cbxmcratingreview-events' );
			wp_enqueue_script( 'jquery' );

			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_script( 'jquery-cbxmcratingreview-raty' );
			wp_enqueue_script( 'jquery-validate' );


			do_action( 'cbxmcratingreview_enq_ratingeditform_js_css' );

			//note: form always load the common public js and js

			wp_enqueue_style( 'cbxmcratingreview-public' );
			wp_enqueue_style( 'cbxmcratingreview-ratingform' );

			wp_enqueue_script( 'cbxmcratingreview-public' );
			wp_enqueue_script( 'cbxmcratingreview-ratingeditform' );

			do_action( 'cbxmcratingreview_enq_ratingeditform_js_css_after' );
		}


		/**
		 * Ajax handler for post reviews load more
		 */
		public function post_more_reviews_ajax_load() {
			check_ajax_referer( 'cbxmcratingreview', 'security' );

			$form_id = isset( $_POST['form_id'] ) ? intval( $_POST['form_id'] ) : 0;
			$post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
			$perpage = isset( $_POST['perpage'] ) ? intval( $_POST['perpage'] ) : 0;
			$page    = isset( $_POST['page'] ) ? intval( $_POST['page'] ) : 1;
			$orderby = isset( $_POST['orderby'] ) ? esc_attr( $_POST['orderby'] ) : 'id';
			$order   = isset( $_POST['order'] ) ? esc_attr( $_POST['order'] ) : 'DESC';
			//$status  = isset( $_POST['status'] ) ? esc_attr( $_POST['status'] ) : ''; //this should be 1 for current regular implementation
			$score = isset( $_POST['score'] ) ? esc_attr( $_POST['score'] ) : '';

			$load_more = isset( $_POST['load_more'] ) ? intval( $_POST['load_more'] ) : 0;
			//$show_filter   = isset( $_POST['show_filter'] ) ? intval( $_POST['show_filter'] ) : 0;

			//filter must be set false
			$output = CBXMCRatingReviewHelper::postReviewsRender( $form_id, $post_id, $perpage, $page, 1, $score, $orderby, $order, $load_more );

			echo wp_json_encode( $output );

			wp_die();
		}//end method post_more_reviews_ajax_load

		/**
		 * Ajax handler for post reviews load more
		 */
		public function post_filter_reviews_ajax_load() {
			check_ajax_referer( 'cbxmcratingreview', 'security' );

			$form_id = isset( $_POST['form_id'] ) ? intval( $_POST['form_id'] ) : 0;
			$post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
			$perpage = isset( $_POST['perpage'] ) ? intval( $_POST['perpage'] ) : 0;
			$page    = isset( $_POST['page'] ) ? intval( $_POST['page'] ) : 1;
			$orderby = isset( $_POST['orderby'] ) ? esc_attr( $_POST['orderby'] ) : 'id';
			$order   = isset( $_POST['order'] ) ? esc_attr( $_POST['order'] ) : 'DESC';
			//$status  = isset( $_POST['status'] ) ? esc_attr( $_POST['status'] ) : ''; //this should be 1 for current regular implementation
			$score = isset( $_POST['score'] ) ? esc_attr( $_POST['score'] ) : '';

			//filter must be set false

			$output_list = CBXMCRatingReviewHelper::postReviewsRender( $form_id, $post_id, $perpage, $page, 1, $score, $orderby, $order, 0 );

			$total_count   = cbxmcratingreview_totalPostReviewsCount( $form_id, $post_id, 1, $score );
			$maximum_pages = ceil( $total_count / $perpage );

			$show_readmore = ( $maximum_pages > 1 ) ? 1 : 0;

			$output = array(
				'list_html' => $output_list,
				'orderby'   => $orderby,
				'order'     => $order,
				'score'     => $score,
				'load_more' => $show_readmore,
				'maxpage'   => $maximum_pages,
				'total'     => $total_count,
			);

			echo wp_json_encode( $output );

			wp_die();
		}//end method post_more_reviews_ajax_load

		/**
		 * Review rating entry via ajax
		 */
		public function review_rating_frontend_submit() {
			check_ajax_referer( 'cbxmcratingreview', 'security' );

			$cbxmcratingreview_setting = $this->setting;

			$show_headline    = intval( $cbxmcratingreview_setting->get_option( 'show_headline', 'cbxmcratingreview_common_config', 1 ) );
			$show_comment     = intval( $cbxmcratingreview_setting->get_option( 'show_comment', 'cbxmcratingreview_common_config', 1 ) );
			$require_headline = intval( $cbxmcratingreview_setting->get_option( 'require_headline', 'cbxmcratingreview_common_config', 1 ) );
			$require_comment  = intval( $cbxmcratingreview_setting->get_option( 'require_comment', 'cbxmcratingreview_common_config', 1 ) );


			$default_status = intval( $cbxmcratingreview_setting->get_option( 'default_status', 'cbxmcratingreview_common_config', 1 ) );

			$submit_data = $_REQUEST['cbxmcratingreview_ratingForm'];

			$validation_errors = $success_data = $return_response = $response_data_arr = array();
			$ok_to_process     = 0;
			$success_msg_class = $success_msg_info = '';


			$user_id = intval( get_current_user_id() );
			if ( is_user_logged_in() ) {
				$post_id = isset( $submit_data['post_id'] ) ? intval( $submit_data['post_id'] ) : 0;
				$form_id = isset( $submit_data['form_id'] ) ? intval( $submit_data['form_id'] ) : 0;

				//get the form setting
				$form = CBXMCRatingReviewHelper::getRatingForm( $form_id );


				$enable_question  = isset( $form['enable_question'] ) ? intval( $form['enable_question'] ) : 0;
				$custom_criterias = isset( $form['custom_criteria'] ) ? $form['custom_criteria'] : array();
				$custom_questions = isset( $form['custom_question'] ) ? $form['custom_question'] : array();

				$rating_scores      = isset( $submit_data['ratings'] ) ? $submit_data['ratings'] : array();
				$rating_score_total = 0;
				$rating_score_count = 0;


				$review_headline = isset( $submit_data['headline'] ) ? sanitize_text_field( $submit_data['headline'] ) : '';
				$review_comment  = isset( $submit_data['comment'] ) ? wp_kses( $submit_data['comment'], CBXMCRatingReviewHelper::allowedHtmlTags() ) : '';

				$questions_store = array();
				$ratings_stars   = array();


				if ( $post_id <= 0 ) {
					$validation_errors['top_errors']['post']['post_id_wrong'] = esc_html__( 'Sorry! Invalid post. Please check and try again.', 'cbxmcratingreview' );
				}

				//rating validation
				if ( is_array( $rating_scores ) && sizeof( $rating_scores ) > 0 ) {
					$rating_score_count = sizeof( $rating_scores );

					foreach ( $custom_criterias as $criteria_index => $custom_criteria ) {
						//$enabled     = isset( $custom_criteria['enabled'] ) ? intval( $custom_criteria['enabled'] ) : 0;
						$criteria_id = isset( $custom_criteria['criteria_id'] ) ? intval( $custom_criteria['criteria_id'] ) : intval( $criteria_index );
						$label       = isset( $custom_criteria['label'] ) ? esc_attr( $custom_criteria['label'] ) : sprintf( esc_html__( 'Untitled criteria - %d' ), $criteria_id );

						$stars_formatted = is_array( $custom_criteria['stars_formatted'] ) ? $custom_criteria['stars_formatted'] : array();
						$stars_length    = isset( $stars_formatted['length'] ) ? intval( $stars_formatted['length'] ) : 0;
						$stars_hints     = isset( $stars_formatted['stars'] ) ? $stars_formatted['stars'] : array();


						if ( isset( $rating_scores[ $criteria_id ] ) ) {

							$rating_score     = $rating_scores[ $criteria_id ];
							$score_percentage = ( $stars_length != 0 ) ? ( $rating_score * 100 ) / $stars_length : 0;
							$score_standard   = ( $score_percentage != 0 ) ? ( ( $score_percentage * 5 ) / 100 ) : 0;
							$score_round      = ceil( $rating_score );
							$round_percentage = ( $stars_length != 0 ) ? ( $score_round * 100 ) / $stars_length : 0;

							//let's find the star from the score !
							//$star_id = array_keys( CBXMCRatingReviewHelper::getNthItemFromArr( $stars_hints, $score_round, 1, true ) )[0]; // we are so confident


							$ratings_stars[ $criteria_id ] = array(
								//'star_id'          => $star_id,
								'stars_length'     => $stars_length,
								'score'            => $rating_score,
								'score_percentage' => $score_percentage,
								'score_standard'   => number_format( $score_standard, 2 ), //score in 5
								'score_round'      => $score_round,
								'round_percentage' => $round_percentage
							);


							$rating_score_total += floatval( $score_percentage );

							if ( $rating_score <= 0 || $rating_score > $stars_length ) {
								$validation_errors['cbxmcratingreview_rating_score'][ 'rating_score_wrong_' . $criteria_id ] = sprintf( __( 'Sorry! Invalid rating score for criteria <strong>%s</strong>. Please check and try again.', 'cbxmcratingreview' ), $label );
							}
						} else if ( ! isset( $rating_scores[ $criteria_id ] ) ) {
							//todo: allow without rating ! , future thought
							$validation_errors['cbxmcratingreview_rating_score'][ 'rating_score_wrong_' . $criteria_id ] = sprintf( __( 'Sorry! Invalid rating score for criteria <strong>%s</strong>. Please check and try again.', 'cbxmcratingreview' ), $label );
						}


					}//end for each criteria
				} else {
					//error checking if review only submit approved
					$validation_errors['cbxmcratingreview_rating_score']['rating_score_wrong'] = esc_html__( 'Sorry! Invalid rating score or no rating selected. Please check and try again.', 'cbxmcratingreview' );
				}//end rating validation


				//questions validations
				$questions = isset( $submit_data['questions'] ) ? $submit_data['questions'] : array();

				//if question enabled for this form and question submitted
				if ( $enable_question && is_array( $questions ) && sizeof( $questions ) ) {
					//for each form questions
					foreach ( $custom_questions as $question_index => $question ) {
						$field_type = isset( $question['type'] ) ? $question['type'] : '';
						$enabled    = isset( $question['enabled'] ) ? intval( $question['enabled'] ) : 0;

						$title = isset( $question['title'] ) ? esc_attr( $question['title'] ) : sprintf( esc_html__( 'Untitled Question %d', 'cbxmcratingreview' ), intval( $question_index ) );

						if ( $field_type != '' && $enabled ) {
							$required = isset( $question['required'] ) ? intval( $question['required'] ) : 0;
							$multiple = isset( $question['multiple'] ) ? intval( $question['multiple'] ) : 0;
							//if question answered
							if ( isset( $questions[ $question_index ] ) ) {
								$answer = $questions[ $question_index ];

								if ( $field_type == 'text' || $field_type == 'textarea' || $field_type == 'number' || ( $field_type == 'select' && $multiple == 0 ) ) {
									if ( $required && $answer == '' ) {
										$validation_errors['cbxmcratingreview_questions_error'][  $question_index ] = sprintf( __( 'Sorry! Question <strong>%s</strong> is blank but required. Please check and try again.', 'cbxmcratingreview' ), $title );
									}
								} else if ( $field_type == 'select' && $multiple ) {
									if ( $required && sizeof( array_filter( $answer, array('CBXMCRatingReviewQuestionHelper', 'arrayFilterRemoveEmpty') ) ) == 0 ) {
										$validation_errors['cbxmcratingreview_questions_error'][  $question_index ] = sprintf( __( 'Sorry! Question <strong>%s</strong> is not answered but required. Please check and try again.', 'cbxmcratingreview' ), $title );
									}
								} else if ( $field_type == 'checkbox' ) {

								} else if ( $field_type == 'multicheckbox' ) {
									if ( $required && sizeof( array_filter( $answer, array('CBXMCRatingReviewQuestionHelper', 'arrayFilterRemoveEmpty') ) ) == 0 ) {
										$validation_errors['cbxmcratingreview_questions_error'][ $question_index ] = sprintf( __( 'Sorry! Question <strong>%s</strong> is not answered but required. Please check and try again.', 'cbxmcratingreview' ), $title );
									}
								}


								//now store the answer
								if ( is_array( $answer ) ) {
									$answer = maybe_serialize( array_filter( $answer, array('CBXMCRatingReviewQuestionHelper', 'arrayFilterRemoveEmpty') ) );
								}
								$questions_store[ $question_index ] = $answer;
							} else if ( $required ) {
								//required but not submitted
								$validation_errors['cbxmcratingreview_questions_error'][ $question_index ] = sprintf( __( 'Sorry! Question <strong>%s</strong> is not answered but required. Please check and try again.', 'cbxmcratingreview' ), $title );
							}
						}


					}
				}//end if question answer submitted
				//end question validation


				if ( $show_headline && $require_headline && $review_headline == '' ) {
					$validation_errors['cbxmcratingreview_review_headline']['review_headline_empty'] = esc_html__( 'Please provide title', 'cbxmcratingreview' );
				}

				if ( $show_comment && $require_comment && $review_comment == '' ) {
					$validation_errors['cbxmcratingreview_review_comment']['review_comment_empty'] = esc_html__( 'Please provide review', 'cbxmcratingreview' );
				}

			} else {
				$validation_errors['top_errors']['user']['user_guest'] = esc_html__( 'You aren\'t currently logged in. Please login to rate.', 'cbxmcratingreview' );
			}

			$validation_errors = apply_filters( 'cbxmcratingreview_review_entry_validation_errors', $validation_errors, $form_id, $post_id, $submit_data );

			if ( sizeof( $validation_errors ) > 0 ) {

			} else {

				$default_status = apply_filters( 'cbxmcratingreview_review_review_default_status', $default_status, $form_id, $post_id );

				$ok_to_process = 1;

				global $wpdb;

				$table_rating_log = $wpdb->prefix . 'cbxmcratingreview_log';

				$user_rated_before = cbxmcratingreview_isPostRatedByUser( $form_id, $post_id, $user_id );

				$multiple_review = false;
				$multiple_review = apply_filters( 'cbxmcratingreview_review_review_repeat', $multiple_review, $form_id, $post_id, $user_id );


				$log_insert_status = false;

				if ( ( $user_rated_before == false ) || $multiple_review ) {
					$attachment = array();

					//$attachment['photos'] = $review_photos;
					//$attachment['video']  = $review_video;


					$attachment = apply_filters( 'cbxmcratingreview_review_entry_attachment', $attachment, $form_id, $post_id, $submit_data );

					$extraparams = array();
					$extraparams = apply_filters( 'cbxmcratingreview_review_entry_extraparams', $extraparams, $form_id, $post_id, $submit_data );


					$rating_avg_percentage = $rating_score_total / $rating_score_count; //in 100%

					$rating_avg_score = ( $rating_avg_percentage != 0 ) ? ( $rating_avg_percentage * 5 ) / 100 : 0; //scale within 5

					$ratings = array(
						'ratings_stars'  => $ratings_stars,
						'avg_percentage' => $rating_avg_percentage,
						'avg_score'      => $rating_avg_score
					);

					// insert rating log
					$data = array(
						'post_id'      => $post_id,
						'form_id'      => $form_id,
						'post_type'    => get_post_type( $post_id ),
						'user_id'      => $user_id,
						'score'        => number_format( $rating_avg_score, 2 ),
						'headline'     => $review_headline,
						'comment'      => $review_comment,
						'extraparams'  => maybe_serialize( $extraparams ),
						'attachment'   => maybe_serialize( $attachment ),
						'status'       => $default_status,
						'date_created' => current_time( 'mysql' ),
						'ratings'      => maybe_serialize( $ratings ),
						'questions'    => maybe_serialize( $questions_store )
					);

					$data = apply_filters( 'cbxmcratingreview_review_entry_data', $data, $form_id, $post_id, $submit_data );

					$data_format = array(
						'%d', // post_id
						'%d', // form_id
						'%s', // post_type
						'%d', // user_id
						'%f', // score
						'%s', // headline
						'%s', // comment
						'%s', // extraparams
						'%s', // attachment
						'%s', // status
						'%s', // date_created
						'%s', // ratings
						'%s', // questions
					);

					$data_format = apply_filters( 'cbxmcratingreview_review_entry_data_format', $data_format, $form_id, $post_id, $submit_data );

					$log_insert_status = $wpdb->insert(
						$table_rating_log,
						$data,
						$data_format
					);

					if ( $log_insert_status != false ) {
						$new_review_id = $wpdb->insert_id;

						do_action( 'cbxmcratingreview_review_entry_just_success', $form_id, $post_id, $submit_data, $new_review_id );

						$success_msg_class = 'success';
						$success_msg_info  = sprintf( esc_html__( 'Thank you for your rating and review. You rated avg %s', 'cbxmcratingreview' ), number_format_i18n( $rating_avg_score, 2 ) . '/' . number_format_i18n( 5 ) );
						if ( $default_status != 1 ) {
							$success_msg_info .= '<br/>';
							$success_msg_info .= esc_html__( 'It will be published after admin approval and you will be notified.', 'cbxmcratingreview' );

							$success_msg_info = apply_filters( 'cbxmcratingreview_review_entry_success_info', $success_msg_info, 'success' );
						}

						$review_info = cbxmcratingreview_singleReview( $new_review_id );

						$response_data_arr['post_id']         = $review_info['post_id'];
						$response_data_arr['form_id']         = $review_info['form_id'];
						$response_data_arr['user_id']         = $review_info['user_id'];
						$response_data_arr['rating_id']       = $review_info['id'];
						$response_data_arr['rating_score']    = $review_info['score'] . '/5';
						$response_data_arr['headline']        = $review_info['headline'];
						$response_data_arr['comment']         = $review_info['comment'];
						$response_data_arr['date_created']    = CBXMCRatingReviewHelper::dateReadableFormat( $review_info['date_created'] );
						$response_data_arr['new_review_info'] = $review_info;

						//return last review with the response data
						if ( $default_status == 1 ) {
							$response_data_arr['review_html'] = '<li id="cbxmcratingreview_review_list_item_' . intval( $new_review_id ) . '" class="' . apply_filters( 'cbxmcratingreview_review_list_item_class', 'cbxmcratingreview_review_list_item' ) . '">' . cbxmcratingreview_singleReviewRender( $review_info ) . '</li>';
						}


						//if published then calculate avg
						if ( intval( $default_status ) == 1 ) {
							do_action( 'cbxmcratingreview_review_publish', $review_info );
						}

						$response_data_arr = apply_filters( 'cbxmcratingreview_review_entry_response_data', $response_data_arr, $form_id, $post_id, $submit_data, $review_info );


						//send email to admin
						$nr_admin_status = $cbxmcratingreview_setting->get_option( 'nr_admin_status', 'cbxmcratingreview_email_alert', 'on' );
						if ( $nr_admin_status == 'on' ) {
							$nr_admin_email_status = CBXMCRatingReviewMailAlert::sendNewReviewAdminEmailAlert( $review_info );
						}

						//send email to user
						$nr_user_status = $cbxmcratingreview_setting->get_option( 'nr_user_status', 'cbxmcratingreview_email_alert', 'on' );
						if ( $nr_user_status == 'on' ) {
							$nr_user_email_status = CBXMCRatingReviewMailAlert::sendNewReviewUserEmailAlert( $review_info );
						}

						do_action( 'cbxmcratingreview_review_entry_success', $form_id, $post_id, $submit_data, $review_info );

					}
				} else {
					$success_msg_class = 'warning';
					$success_msg_info  = esc_html__( 'Sorry! You already rated this or multiple reviews is not possible.', 'cbxmcratingreview' );
					$success_msg_info  = apply_filters( 'cbxmcratingreview_review_entry_success_info', $success_msg_info, 'failed' );
				}


				$success_data['responsedata'] = $response_data_arr;
				$success_data['class']        = $success_msg_class;
				$success_data['msg']          = $success_msg_info;
			}//end review submit validation

			$return_response['ok_to_process'] = $ok_to_process;
			$return_response['success']       = $success_data;
			$return_response['error']         = $validation_errors;

			echo wp_json_encode( $return_response );
			wp_die();
		}//end method review_rating_submit

		/**
		 * Ajax review edit from frontend
		 */
		public function review_rating_front_edit() {
			check_ajax_referer( 'cbxmcratingreview', 'security' );

			$cbxmcratingreview_setting = $this->setting;

			$show_headline    = intval( $cbxmcratingreview_setting->get_option( 'show_headline', 'cbxmcratingreview_common_config', 1 ) );
			$show_comment     = intval( $cbxmcratingreview_setting->get_option( 'show_comment', 'cbxmcratingreview_common_config', 1 ) );
			$require_headline = intval( $cbxmcratingreview_setting->get_option( 'require_headline', 'cbxmcratingreview_common_config', 1 ) );
			$require_comment  = intval( $cbxmcratingreview_setting->get_option( 'require_comment', 'cbxmcratingreview_common_config', 1 ) );

			$default_status = intval( $cbxmcratingreview_setting->get_option( 'default_status', 'cbxmcratingreview_common_config', 1 ) );


			$submit_data = $_REQUEST['cbxmcratingreview_ratingForm'];

			$validation_errors = $success_data = $return_response = $response_data_arr = array();
			$ok_to_process     = 0;
			$success_msg_class = $success_msg_info = '';


			if ( is_user_logged_in() ) {

				$form_id   = isset( $submit_data['form_id'] ) ? intval( $submit_data['form_id'] ) : 0;
				$post_id   = isset( $submit_data['post_id'] ) ? intval( $submit_data['post_id'] ) : 0;
				$review_id = isset( $submit_data['log_id'] ) ? intval( $submit_data['log_id'] ) : 0;
				$user_id   = intval( get_current_user_id() );


				//get the form setting
				$form = CBXMCRatingReviewHelper::getRatingForm( $form_id );


				$enable_question  = isset( $form['enable_question'] ) ? intval( $form['enable_question'] ) : 0;
				$custom_criterias = isset( $form['custom_criteria'] ) ? $form['custom_criteria'] : array();
				$custom_questions = isset( $form['custom_question'] ) ? $form['custom_question'] : array();

				$rating_scores      = isset( $submit_data['ratings'] ) ? $submit_data['ratings'] : array();
				$rating_score_total = 0;
				$rating_score_count = 0;


				$review_headline = isset( $submit_data['headline'] ) ? sanitize_text_field( $submit_data['headline'] ) : '';
				$review_comment  = isset( $submit_data['comment'] ) ? wp_kses( $submit_data['comment'], CBXMCRatingReviewHelper::allowedHtmlTags() ) : '';

				$questions_store = array();
				$ratings_stars   = array();


				if ( $review_id <= 0 ) {
					$validation_errors['top_errors']['log_id']['log_id_wrong'] = esc_html__( 'Sorry! Invalid review id. Please check and try again.', 'cbxmcratingreview' );
				} else {
					$review_info_old = cbxmcratingreview_singleReview( $review_id );
					if ( $review_info_old == null ) {
						$validation_errors['top_errors']['log']['log_wrong'] = esc_html__( 'Sorry! Invalid review. Please check and try again.', 'cbxmcratingreview' );
					} else {
						$review_info_old_user_id = intval( $review_info_old['user_id'] );
						if ( $review_info_old_user_id != $user_id ) {
							$validation_errors['top_errors']['log']['user_wrong'] = esc_html__( 'Are you cheating, huh ?', 'cbxmcratingreview' );
						}
					}
				}

				if ( $post_id <= 0 ) {
					$validation_errors['top_errors']['post']['post_id_wrong'] = esc_html__( 'Sorry! Invalid post. Please check and try again.', 'cbxmcratingreview' );
				}


				//rating validation
				if ( is_array( $rating_scores ) && sizeof( $rating_scores ) > 0 ) {
					$rating_score_count = sizeof( $rating_scores );

					foreach ( $custom_criterias as $criteria_index => $custom_criteria ) {
						//$enabled     = isset( $custom_criteria['enabled'] ) ? intval( $custom_criteria['enabled'] ) : 0;
						$criteria_id = isset( $custom_criteria['criteria_id'] ) ? intval( $custom_criteria['criteria_id'] ) : intval( $criteria_index );
						$label       = isset( $custom_criteria['label'] ) ? esc_attr( $custom_criteria['label'] ) : sprintf( esc_html__( 'Untitled criteria - %d' ), $criteria_id );

						$stars_formatted = is_array( $custom_criteria['stars_formatted'] ) ? $custom_criteria['stars_formatted'] : array();
						$stars_length    = isset( $stars_formatted['length'] ) ? intval( $stars_formatted['length'] ) : 0;
						$stars_hints     = isset( $stars_formatted['stars'] ) ? $stars_formatted['stars'] : array();


						if ( isset( $rating_scores[ $criteria_id ] ) ) {

							$rating_score     = $rating_scores[ $criteria_id ];
							$score_percentage = ( $stars_length != 0 ) ? ( $rating_score * 100 ) / $stars_length : 0; //scale in 100
							$score_standard   = ( $score_percentage != 0 ) ? ( ( $score_percentage * 5 ) / 100 ) : 0; //scale in 5
							$score_round      = ceil( $rating_score ); //rounding the raw score
							$round_percentage = ( $stars_length != 0 ) ? ( $score_round * 100 ) / $stars_length : 0; //scale in 100 for rounded value

							//let's find the star from the score !
							//$star_id = array_keys( CBXMCRatingReviewHelper::getNthItemFromArr( $stars_hints, $score_round, 1, true ) )[0]; // we are so confident


							$ratings_stars[ $criteria_id ] = array(
								//'star_id'          => $star_id,
								'stars_length'     => $stars_length,
								'score'            => $rating_score,
								'score_percentage' => $score_percentage,
								'score_standard'   => number_format( $score_standard, 2 ), //score in 5
								'score_round'      => $score_round, //ceil
								'round_percentage' => $round_percentage,
							);


							$rating_score_total += floatval( $score_percentage );

							if ( $rating_score <= 0 || $rating_score > $stars_length ) {
								$validation_errors['cbxmcratingreview_rating_score'][ 'rating_score_wrong_' . $criteria_id ] = sprintf( __( 'Sorry! Invalid rating score for criteria <strong>%s</strong>. Please check and try again.', 'cbxmcratingreview' ), $label );
							}
						} else if ( ! isset( $rating_scores[ $criteria_id ] ) ) {
							//todo: allow without rating ! , future thought
							$validation_errors['cbxmcratingreview_rating_score'][ 'rating_score_wrong_' . $criteria_id ] = sprintf( __( 'Sorry! Invalid rating score for criteria <strong>%s</strong>. Please check and try again.', 'cbxmcratingreview' ), $label );
						}


					}//end for each criteria
				} else {
					//error checking if review only submit approved
					$validation_errors['cbxmcratingreview_rating_score']['rating_score_wrong'] = esc_html__( 'Sorry! Invalid rating score or no rating selected. Please check and try again.', 'cbxmcratingreview' );
				}//end rating validation

				//questions validations
				$questions = isset( $submit_data['questions'] ) ? $submit_data['questions'] : array();

				//if question enabled for this form and question submitted
				if ( $enable_question && is_array( $questions ) && sizeof( $questions ) ) {
					//for each form questions
					foreach ( $custom_questions as $question_index => $question ) {
						$field_type = isset( $question['type'] ) ? $question['type'] : '';
						$enabled    = isset( $question['enabled'] ) ? intval( $question['enabled'] ) : 0;

						$title = isset( $question['title'] ) ? esc_attr( $question['title'] ) : sprintf( esc_html__( 'Untitled Question %d', 'cbxmcratingreview' ), intval( $question_index ) );

						if ( $field_type != '' && $enabled ) {
							$required = isset( $question['required'] ) ? intval( $question['required'] ) : 0;
							$multiple = isset( $question['multiple'] ) ? intval( $question['multiple'] ) : 0;
							//if question answered
							if ( isset( $questions[ $question_index ] ) ) {
								$answer = $questions[ $question_index ];

								if ( $field_type == 'text' || $field_type == 'textarea' || $field_type == 'number' || ( $field_type == 'select' && $multiple == 0 ) ) {
									if ( $required && $answer == '' ) {
										$validation_errors['cbxmcratingreview_questions_error'][  $question_index ] = sprintf( __( 'Sorry! Question <strong>%s</strong> is blank but required. Please check and try again.', 'cbxmcratingreview' ), $title );
									}
								} else if ( $field_type == 'select' && $multiple ) {
									if ( $required && sizeof( array_filter( $answer, array('CBXMCRatingReviewQuestionHelper', 'arrayFilterRemoveEmpty') ) ) == 0 ) {
										$validation_errors['cbxmcratingreview_questions_error'][  $question_index ] = sprintf( __( 'Sorry! Question <strong>%s</strong> is not answered but required. Please check and try again.', 'cbxmcratingreview' ), $title );
									}
								} else if ( $field_type == 'checkbox' ) {

								} else if ( $field_type == 'multicheckbox' ) {
									if ( $required && sizeof( array_filter( $answer, array('CBXMCRatingReviewQuestionHelper', 'arrayFilterRemoveEmpty') ) ) == 0 ) {
										$validation_errors['cbxmcratingreview_questions_error'][ $question_index ] = sprintf( __( 'Sorry! Question <strong>%s</strong> is not answered but required. Please check and try again.', 'cbxmcratingreview' ), $title );
									}
								}


								//now store the answer
								if ( is_array( $answer ) ) {
									$answer = maybe_serialize( array_filter( $answer, array('CBXMCRatingReviewQuestionHelper', 'arrayFilterRemoveEmpty') ) );
								}
								$questions_store[ $question_index ] = $answer;
							} else if ( $required ) {
								//required but not submitted
								$validation_errors['cbxmcratingreview_questions_error'][  $question_index ] = sprintf( __( 'Sorry! Question <strong>%s</strong> is not answered but required. Please check and try again.', 'cbxmcratingreview' ), $title );
							}
						}


					}
				}//end if question answer submitted
				//end question validation


				if ( $show_headline == 1 && $require_headline == 1 && $review_headline == '' ) {
					$validation_errors['cbxmcratingreview_review_headline']['review_headline_empty'] = esc_html__( 'Please provide title', 'cbxmcratingreview' );
				}
				if ( $show_comment == 1 && $require_comment == 1 && $review_comment == '' ) {
					$validation_errors['cbxmcratingreview_review_comment']['review_comment_empty'] = esc_html__( 'Please provide review', 'cbxmcratingreview' );
				}


			} else {
				$validation_errors['top_errors']['user']['user_guest'] = esc_html__( 'You aren\'t currently logged in. Please login to rate.', 'cbxmcratingreview' );
			}

			$validation_errors = apply_filters( 'cbxmcratingreview_review_frontedit_validation_errors', $validation_errors, $form_id, $post_id, $submit_data );

			if ( sizeof( $validation_errors ) > 0 ) {

			} else {


				$old_status = $review_info_old['status'];


				$ok_to_process = 1;

				global $wpdb;

				$table_rating_log  = $wpdb->prefix . 'cbxmcratingreview_log';
				$log_update_status = false;

				$attachment = maybe_unserialize( $review_info_old['attachment'] );

				$attachment = apply_filters( 'cbxmcratingreview_review_frontedit_attachment', $attachment, $form_id, $post_id, $submit_data, $review_id );

				$extraparams = maybe_unserialize( $review_info_old['extraparams'] );
				$extraparams = apply_filters( 'cbxmcratingreview_review_frontedit_extraparams', $extraparams, $form_id, $post_id, $submit_data, $review_id );

				$rating_avg_percentage = $rating_score_total / $rating_score_count; //in 100%

				$rating_avg_score = ( $rating_avg_percentage != 0 ) ? ( $rating_avg_percentage * 5 ) / 100 : 0; //scale within 5

				$ratings = array(
					'ratings_stars'  => $ratings_stars,
					'avg_percentage' => $rating_avg_percentage,
					'avg_score'      => $rating_avg_score
				);

				// edit rating log
				$data = array(
					'score'         => number_format( $rating_avg_score, 2 ),
					'headline'      => $review_headline,
					'comment'       => $review_comment,
					'extraparams'   => maybe_serialize( $extraparams ),
					'attachment'    => maybe_serialize( $attachment ),
					'mod_by'        => $user_id,
					'date_modified' => current_time( 'mysql' ),
					'ratings'       => maybe_serialize( $ratings ),
					'questions'     => maybe_serialize( $questions_store )
				);

				$data = apply_filters( 'cbxmcratingreview_review_frontedit_data', $data, $form_id, $post_id, $submit_data, $review_id );

				$data_format = array(
					'%f', // score
					'%s', // headline
					'%s', // comment
					'%s', // extraparams
					'%s', // attachment
					'%d', // mod_by
					'%s', // date_modified
					'%s', // ratings
					'%s', // questions
				);

				$data_format = apply_filters( 'cbxmcratingreview_review_frontedit_data_format', $data_format, $post_id, $submit_data, $review_id );

				$data_where = array(
					'id'      => $review_id,
					'post_id' => $post_id,
					'user_id' => $user_id
				);

				$data_where_format = array(
					'%d', // id
					'%d', // post_id
					'%d' // user_id
				);

				$data_where        = apply_filters( 'cbxmcratingreview_review_frontedit_where', $data_where, $form_id, $post_id, $submit_data, $review_id );
				$data_where_format = apply_filters( 'cbxmcratingreview_review_frontedit_where_format', $data_where_format, $form_id, $post_id, $submit_data, $review_id );

				$log_update_status = $wpdb->update(
					$table_rating_log,
					$data,
					$data_where,
					$data_format,
					$data_where_format
				);

				if ( $log_update_status != false ) {
					$review_id = $review_id;

					do_action( 'cbxmcratingreview_review_frontedit_just_success', $form_id, $post_id, $submit_data, $review_id );


					$success_msg_class = 'success';

					$success_msg_info = esc_html__( 'Review updated successfully', 'cbxmcratingreview' );

					$success_msg_info = apply_filters( 'cbxmcratingreview_review_frontedit_success_info', $success_msg_info, 'success' );

					$review_info = cbxmcratingreview_singleReview( $review_id );


					//simple update without status change
					do_action( 'cbxmcratingreview_review_update_without_status', $old_status, $review_info, $review_info_old );


					$response_data_arr = apply_filters( 'cbxmcratingreview_review_frontedit_response_data', $response_data_arr, $form_id, $post_id, $submit_data, $review_info );


					do_action( 'cbxmcratingreview_review_frontedit_success', $form_id, $post_id, $submit_data, $review_info, $review_info_old );

				}


				$success_data['responsedata'] = $response_data_arr;
				$success_data['class']        = $success_msg_class;
				$success_data['msg']          = $success_msg_info;
			}//end review submit validation

			$return_response['ok_to_process'] = $ok_to_process;
			$return_response['success']       = $success_data;
			$return_response['error']         = $validation_errors;

			echo wp_json_encode( $return_response );
			wp_die();
		}//end method review_rating_front_edit

		/**
		 * if review edited in publish mode
		 *
		 * @param $new_status
		 * @param $review_info
		 * @param $review_info_old
		 */
		public function cbxmcratingreview_review_update_without_status_adjust_postavg( $new_status, $review_info, $review_info_old ) {
			//if status is edited in puhlished mode
			if ( $new_status == 1 ) {
				CBXMCRatingReviewHelper::editPostwAvg( $new_status, $review_info, $review_info_old );
			}
		}

		/**
		 * Review Toolbar render
		 */
		public function cbxmcratingreview_single_review_toolbar( $post_review ) {
			echo cbxmcratingreview_reviewToolbarRender( $post_review );
		}//end method cbxmcratingreview_single_review_toolbar

		public function cbxmcratingreview_single_review_delete_button( $post_review ) {
			$cbxmcratingreview_setting = $this->setting;

			$allow_review_delete = $cbxmcratingreview_setting->get_option( 'allow_review_delete', 'cbxmcratingreview_common_config', 1 );
			if ( is_user_logged_in() && intval( $allow_review_delete ) == 1 ) {
				$current_user_id = get_current_user_id();
				$review_user_id  = intval( $post_review['user_id'] );

				if ( $current_user_id == $review_user_id ) {
					echo cbxmcratingreview_reviewDeleteButtonRender( $post_review );
				}

			}
		}//end method cbxmcratingreview_single_review_delete_button

		/**
		 * Ajax review delete
		 */
		public function review_delete_ajax() {
			check_ajax_referer( 'cbxmcratingreview', 'security' );
			$cbxmcratingreview_setting = $this->setting;

			$allow_review_delete = $cbxmcratingreview_setting->get_option( 'allow_review_delete', 'cbxmcratingreview_common_config', 1 );

			$output            = array();
			$output['success'] = 0;


			$review_id = isset( $_POST['review_id'] ) ? intval( $_POST['review_id'] ) : 0;

			if ( intval( $allow_review_delete ) == 0 ) {
				$output['message'] = esc_html__( 'Review delete is not possible. Please contact site authority.', 'cbxmcratingreview' );
			} else if ( $review_id == 0 ) {
				$output['message'] = esc_html__( 'Review id is invalid', 'cbxmcratingreview' );
			} else if ( ! is_user_logged_in() ) {
				$output['message'] = esc_html__( 'You are not logged in and you don\'t own the review. Area you cheating?', 'cbxmcratingreview' );
			} else {
				//now let's try to delete the message
				$current_user = wp_get_current_user();
				$user_id      = $current_user->ID;


				$review_info = cbxmcratingreview_singleReview( $review_id );

				global $wpdb;
				$table_cbxmcratingreview_review = $wpdb->prefix . 'cbxmcratingreview_log';
				do_action( 'cbxmcratingreview_review_delete_before', $review_info );

				$delete_status = $wpdb->query( $wpdb->prepare( "DELETE FROM $table_cbxmcratingreview_review WHERE id=%d AND user_id=%d", $review_id, $user_id ) );

				if ( $delete_status !== false ) {
					do_action( 'cbxmcratingreview_review_delete_after', $review_info );

					$output['success'] = 1;
					$output['message'] = esc_html__( 'Review deleted successfully!', 'cbxmcratingreview' );
				} else {
					$output['message'] = esc_html__( 'Review deleted failed!', 'cbxmcratingreview' );
				}
			}

			echo wp_json_encode( $output );
			wp_die();
		}//end method review_delete_ajax

		/**
		 * Auto integration
		 *
		 * @param $content
		 *
		 * @return string
		 */
		public function the_content_auto_integration( $content ) {
			global $post;
			$cbxmcratingreview_setting = $this->setting;


			$forms = CBXMCRatingReviewHelper::getRatingForms(); //return all fields include extra fields merging to regular fields
			//for each form
			foreach ( $forms as $form ) {
				$form_id = intval( $form['id'] ) ? intval( $form['id'] ) : 0;
				if ( $form_id === 0 ) {
					return $content;
				}

				//if form disable return
				$status = isset( $form['status'] ) ? intval( $form['status'] ) : 0;
				if ( $status === 0 ) {
					return $content;
				}


				$post_id   = intval( $post->ID );
				$post_type = $post->post_type;

				//check if post type supported
				$post_types = isset( $form['post_types'] ) ? $form['post_types'] : array();
				if ( ! in_array( $post_type, $post_types ) ) {
					return $content;
				}

				$auto_integration = isset( $form['enable_auto_integration'] ) ? intval( $form['enable_auto_integration'] ) : 0;
				$show_on_single = isset( $form['show_on_single'] ) ? intval( $form['show_on_single'] ) : 0;
				$show_on_home = isset( $form['show_on_home'] ) ? intval( $form['show_on_home'] ) : 0;
				$show_on_arcv = isset( $form['show_on_arcv'] ) ? intval( $form['show_on_arcv'] ) : 0;

				//if auto integration enabled
				if ( $auto_integration ) {

					//check if post type supported for auto integration
					$post_types_auto = isset( $form['post_types_auto'] ) ? $form['post_types_auto'] : array();


					if ( ! in_array( $post_type, $post_types_auto ) ) {
						return $content;
					}

					if(is_home() && $show_on_home){
						$extra_html_avg = '';
						if ( function_exists( 'cbxmcratingreview_postAvgRatingRender' ) ) {
							$extra_html_avg = cbxmcratingreview_postAvgRatingRender( $form_id, $post_id );
						}

						return $extra_html_avg . $content;
					}
					else if ( is_archive() && $show_on_arcv ) {
						$extra_html_avg = '';
						if ( function_exists( 'cbxmcratingreview_postAvgRatingRender' ) ) {
							$extra_html_avg = cbxmcratingreview_postAvgRatingRender( $form_id, $post_id );
						}

						return $extra_html_avg . $content;


					} else if ( is_singular() && $show_on_single) {
						$extra_html_avg  = '';
						$extra_html_form = '';
						$extra_html_list = '';

						$post_reviews_count = CBXMCRatingReviewHelper::totalPostReviewsCount( $form_id, $post_id, 1, '' );

						if ( $post_reviews_count == 0 ) {
							if ( function_exists( 'cbxmcratingreview_postAvgRatingRender' ) ) {
								$extra_html_avg .= cbxmcratingreview_postAvgRatingRender( $form_id, $post_id, true, true, false);
							}
						}
						else{
							if ( function_exists( 'cbxmcratingreview_postAvgDetailsRatingRender' ) ) {
								$extra_html_avg .= cbxmcratingreview_postAvgDetailsRatingRender( $form_id, $post_id, true, true, true, true );
							}
						}


						if ( function_exists( 'cbxmcratingreview_reviewformRender' ) ) {
							$extra_html_form = cbxmcratingreview_reviewformRender( $form_id, $post_id );
						}

						$extra_html_list = do_shortcode( '[cbxmcratingreview_postreviews form_id="' . $form_id . '" post_id="' . $post_id . '"]' );

						return $extra_html_avg . $content . $extra_html_form . $extra_html_list;
					}

				}//if end auto integration
			}//end for each form

			return $content;
		}//end method the_content_auto_integration

	}//end public facing class