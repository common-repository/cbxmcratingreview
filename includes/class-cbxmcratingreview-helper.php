<?php
	// If this file is called directly, abort.
	if ( ! defined( 'WPINC' ) ) {
		die;
	}
?>
<?php

	/**
	 * Helper class
	 *
	 * Class CBXMCRatingReviewHelper
	 */
	class CBXMCRatingReviewHelper {



		/**
		 * Php and js based redirect method based on situation
		 *
		 * @param $url
		 */
		public static function redirect( $url ) {
			if ( headers_sent() ) {
				$string = '<script type="text/javascript">';
				$string .= 'window.location = "' . $url . '"';
				$string .= '</script>';

				echo $string;
			} else {
				wp_safe_redirect( $url );
			}
			exit;
		}//end method redirect

		/**
		 * acceptable image ext
		 * @return array
		 */
		public static function imageExtArr() {
			return array( 'jpg', 'jpeg', 'gif', 'png' );
		}//end method imageExtArr

		/**
		 * Returns post types as array
		 *
		 * @return array
		 */
		public static function post_types( $plain = true ) {
			$post_type_args = array(
				'builtin' => array(
					'options' => array(
						'public'   => true,
						'_builtin' => true,
						'show_ui'  => true,
					),
					'label'   => esc_html__( 'Built in post types', 'cbxmcratingreview' ),
				)

			);

			$post_type_args = apply_filters( 'cbxmcratingreview_post_types_args', $post_type_args );

			$output   = 'objects'; // names or objects, note names is the default
			$operator = 'and'; // 'and' or 'or'

			$postTypes = array();

			if ( $plain ) {
				foreach ( $post_type_args as $postArgType => $postArgTypeArr ) {
					$types = get_post_types( $postArgTypeArr['options'], $output, $operator );

					if ( ! empty( $types ) ) {
						foreach ( $types as $type ) {
							//$postTypes[ $postArgType ]['label']                = $postArgTypeArr['label'];
							$postTypes[ $type->name ] = $type->labels->name;
						}
					}
				}
			} else {
				foreach ( $post_type_args as $postArgType => $postArgTypeArr ) {
					$types = get_post_types( $postArgTypeArr['options'], $output, $operator );

					if ( ! empty( $types ) ) {
						foreach ( $types as $type ) {
							//$postTypes[ $postArgType ]['label']                = $postArgTypeArr['label'];
							$postTypes[ esc_attr( $postArgTypeArr['label'] ) ][ $type->name ] = $type->labels->name;
						}
					}
				}
			}


			return apply_filters( 'cbxmcratingreview_post_types', $postTypes, $plain );
		}//end method post_types


		/**
		 * Returns filtered array of post types in plain list
		 *
		 * @param array $filter
		 *
		 * @return array
		 */
		public static function post_types_filtered( $filter = array() ) {
			$post_types          = CBXMCRatingReviewHelper::post_types( true );
			$post_types_filtered = array();
			if ( is_array( $filter ) && sizeof( $filter ) > 0 ) {
				foreach ( $post_types as $key => $value ) {
					if ( in_array( $key, $filter ) ) {
						$post_types_filtered[ $key ] = $value;
					}
				}
			}//if filter has item

			return $post_types_filtered;
		}//end method post_types_filtered

		/**
		 * Get the user roles
		 *
		 * @param string $useCase
		 *
		 * @return array
		 */
		public static function user_roles( $plain = true, $include_guest = false ) {
			global $wp_roles;

			if ( ! function_exists( 'get_editable_roles' ) ) {
				require_once( ABSPATH . '/wp-admin/includes/user.php' );

			}

			$userRoles = array();
			if ( $plain ) {
				foreach ( get_editable_roles() as $role => $roleInfo ) {
					$userRoles[ $role ] = $roleInfo['name'];
				}
				if ( $include_guest ) {
					$userRoles['guest'] = esc_html__( "Guest", 'cbxmcratingreview' );
				}
			} else {
				//optgroup
				$userRoles_r = array();
				foreach ( get_editable_roles() as $role => $roleInfo ) {
					$userRoles_r[ $role ] = $roleInfo['name'];
				}

				$userRoles = array(
					'Registered' => $userRoles_r,
				);

				if ( $include_guest ) {
					$userRoles['Anonymous'] = array(
						'guest' => esc_html__( "Guest", 'cbxmcratingreview' )
					);
				}
			}

			return apply_filters( 'cbxmcratingreview_userroles', $userRoles, $plain, $include_guest );
		}//end method user_roles

		/**
		 * Time to human readable time
		 *
		 * @param        $ts
		 * @param string $fallback_format
		 *
		 * @return false|string
		 */
		public static function time2str( $ts, $fallback_format = 'M j, Y H:i' ) {
			if ( ! ctype_digit( $ts ) ) {
				$ts = strtotime( $ts );
			}
			$diff = time() - $ts;
			if ( $diff == 0 ) {
				return esc_html__( 'now', 'cbxmcratingreview' );
			} elseif ( $diff > 0 ) {
				$day_diff = floor( $diff / 86400 );
				if ( $day_diff == 0 ) {
					if ( $diff < 60 ) {
						return esc_html__( 'just now', 'cbxmcratingreview' );
					}
					if ( $diff < 120 ) {
						return esc_html__( '1 minute ago', 'cbxmcratingreview' );
					}
					if ( $diff < 3600 ) {
						return sprintf( esc_html__( '%s minutes ago', 'cbxmcratingreview' ), floor( $diff / 60 ) );
					}
					if ( $diff < 7200 ) {
						return esc_html__( '1 hour ago', 'cbxmcratingreview' );
					}
					if ( $diff < 86400 ) {
						return floor( $diff / 3600 ) . ' hours ago';
					}
				}
				if ( $day_diff == 1 ) {
					return esc_html__( 'Yesterday', 'cbxmcratingreview' );
				}
				if ( $day_diff < 7 ) {
					return sprintf( esc_html__( '%s days ago', 'cbxmcratingreview' ), $day_diff );
				}
				if ( $day_diff < 31 ) {
					return sprintf( esc_html__( '%s weeks ago', 'cbxmcratingreview' ), ceil( $day_diff / 7 ) );
				}
				if ( $day_diff < 60 ) {
					return esc_html__( 'last month', 'cbxmcratingreview' );
				}

				return date( $fallback_format, $ts );
			} else {
				$diff     = abs( $diff );
				$day_diff = floor( $diff / 86400 );
				if ( $day_diff == 0 ) {
					if ( $diff < 120 ) {
						return esc_html__( 'in a minute', 'cbxmcratingreview' );
					}
					if ( $diff < 3600 ) {
						return sprintf( esc_html__( 'in %s minutes', 'cbxmcratingreview' ), floor( $diff / 60 ) );
					}
					if ( $diff < 7200 ) {
						return esc_html__( 'in an hour', 'cbxmcratingreview' );
					}
					if ( $diff < 86400 ) {
						return sprintf( esc_html__( 'in %s hours', 'cbxmcratingreview' ), floor( $diff / 3600 ) );
					}
				}
				if ( $day_diff == 1 ) {
					return esc_html__( 'Tomorrow', 'cbxmcratingreview' );
				}
				if ( $day_diff < 4 ) {
					return date( 'l', $ts );
				}
				if ( $day_diff < 7 + ( 7 - date( 'w' ) ) ) {
					return esc_html__( 'next week', 'cbxmcratingreview' );
				}
				if ( ceil( $day_diff / 7 ) < 4 ) {
					return sprintf( esc_html__( 'in %s weeks', 'cbxmcratingreview' ), ceil( $day_diff / 7 ) );
				}
				if ( date( 'n', $ts ) == date( 'n' ) + 1 ) {
					return esc_html__( 'next month', 'cbxmcratingreview' );
				}

				return date( $fallback_format, $ts );
			}
		}//end method time2str

		/**
		 * Add all common js and css needed for review and rating
		 */
		public static function AddJsCss() {
			$plugin_public = new CBXMCRatingReview_Public( CBXMCRATINGREVIEW_PLUGIN_NAME, CBXMCRATINGREVIEW_PLUGIN_VERSION );
			$plugin_public->enqueue_common_js_css_rating();
		}//end method AddJsCss

		/**
		 * Add all js and css needed for review submit form
		 */
		public static function AddRatingFormJsCss() {
			$plugin_public = new CBXMCRatingReview_Public( CBXMCRATINGREVIEW_PLUGIN_NAME, CBXMCRATINGREVIEW_PLUGIN_VERSION );
			$plugin_public->enqueue_ratingform_js_css_rating();
		}//end method AddRatingFormJsCss

		/**
		 * Add all js and css needed for review edit form
		 */
		public static function AddRatingEditFormJsCss() {
			$plugin_public = new CBXMCRatingReview_Public( CBXMCRATINGREVIEW_PLUGIN_NAME, CBXMCRATINGREVIEW_PLUGIN_VERSION );
			$plugin_public->enqueue_ratingeditform_js_css_rating();
		}//end method AddRatingEditFormJsCss

		/**
		 * Returns rating hints keys
		 *
		 * @return array
		 */
		public static function ratingHints() {
			$rating_hints = array(
				esc_html__( 'Bad', 'cbxmcratingreview' ),
				esc_html__( 'Poor', 'cbxmcratingreview' ),
				esc_html__( 'Regular', 'cbxmcratingreview' ),
				esc_html__( 'Good', 'cbxmcratingreview' ),
				esc_html__( 'Gorgeous', 'cbxmcratingreview' ),
			);

			return apply_filters( 'cbxmcratingreview_rating_hints', $rating_hints );
		}//end method ratingHints

		/**
		 * Default star titles
		 *
		 * @return array
		 */
		public static function star_default_titles() {
			return apply_filters( 'cbxmcratingreview_default_star_titles', array(
				esc_html__( 'Worst', 'cbxmcratingreview' ),
				esc_html__( 'Bad', 'cbxmcratingreview' ),
				esc_html__( 'Not Bad', 'cbxmcratingreview' ),
				esc_html__( 'Good', 'cbxmcratingreview' ),
				esc_html__( 'Best', 'cbxmcratingreview' )
			) );
		}//end method star_default_titles

		/**
		 * Rating hints colors
		 *
		 * @return array
		 */
		public static function ratingHintsColors() {
			$rating_hints_colors = array( '#57bb8a', '#9ace6a', '#ffcf02', '#ff9f02', '#ff6f31' );

			return apply_filters( 'cbxmcratingreview_rating_hints_colors', $rating_hints_colors );
		}//end method ratingHintsColors


		/**
		 * all posible status for a review
		 * @return array
		 */
		public static function FormStatusOptions() {
			$exprev_status_arr = array(
				'0' => esc_html__( 'Disabled', 'cbxmcratingreview' ),
				'1' => esc_html__( 'Enabled', 'cbxmcratingreview' ),
				//'2' => esc_html__( 'Unpublished', 'cbxmcratingreview' ),
				//'3' => esc_html__( 'Spam', 'cbxmcratingreview' ),
			);

			return apply_filters( 'cbxmcratingreview_review_form_status_options', $exprev_status_arr );
		}//end method ReviewStatusOptions

		/**
		 * all posible status for a review
		 * @return array
		 */
		public static function ReviewStatusOptions() {
			$exprev_status_arr = array(
				'-2' => esc_html__( 'Unverified', 'cbxmcratingreview' ),
				'-1' => esc_html__( 'Verified', 'cbxmcratingreview' ),
				'0' => esc_html__( 'Pending', 'cbxmcratingreview' ),
				'1' => esc_html__( 'Published', 'cbxmcratingreview' ),
				'2' => esc_html__( 'Unpublished', 'cbxmcratingreview' ),
				'3' => esc_html__( 'Spam', 'cbxmcratingreview' ),
			);

			return apply_filters( 'cbxmcratingreview_review_review_status_options', $exprev_status_arr );
		}//end method ReviewStatusOptions

		/**
		 * all posible status for a review
		 * @return array
		 */
		public static function ReviewPositiveScores() {
			$exprev_status_arr = array(
				'1' => esc_html__( '1 or above', 'cbxmcratingreview' ),
				'2' => esc_html__( '2 or above', 'cbxmcratingreview' ),
				'3' => esc_html__( '3 or above', 'cbxmcratingreview' ),
				'4' => esc_html__( '4 or above', 'cbxmcratingreview' ),
				'5' => esc_html__( '5', 'cbxmcratingreview' ),
			);

			return apply_filters( 'cbxmcratingreview_review_review_status_options', $exprev_status_arr );
		}//end method ReviewPositiveScores


		/**
		 * Return all meta keys created by this plugin
		 */
		public static function getMetaKeys() {
			$meta_keys = array();

			$meta_keys['_cbxmcratingreview_avg']   = esc_html__( 'Post rating avg', 'cbxmcratingreview' ); //todo: for per form these meta keys may change
			$meta_keys['_cbxmcratingreview_total'] = esc_html__( 'Post total Rating/reviews count', 'cbxmcratingreview' ); //todo: for per form these meta keys may change

			return apply_filters( 'cbxmcratingreview_meta_keys', $meta_keys );
		}//end method getMetaKeys

		/**
		 * Get all  core tables list(key and db table name)
		 */
		public static function getAllDBTablesList() {
			global $wpdb;

			//tables
			$table_rating_form = $wpdb->prefix . 'cbxmcratingreview_form';
			$table_rating_log  = $wpdb->prefix . 'cbxmcratingreview_log';
			$table_rating_avg  = $wpdb->prefix . 'cbxmcratingreview_log_avg';


			$table_names = array();

			$table_names['form'] = $table_rating_form;
			$table_names['log']  = $table_rating_log;
			$table_names['avg']  = $table_rating_avg;

			return apply_filters( 'cbxmcratingreview_table_list', $table_names );
		}//end function getAllDBTablesList

		/**
		 * Get all core table keys (key and names)
		 *
		 * @return mixed|void
		 */
		public static function getAllDBTablesKeyList() {
			$table_key_names         = array();
			$table_key_names['form'] = esc_html__( 'Rating Form Table', 'cbxmcratingreview' );
			$table_key_names['log']  = esc_html__( 'Review Log Table', 'cbxmcratingreview' );
			$table_key_names['avg']  = esc_html__( 'Review Avg Table', 'cbxmcratingreview' );


			return apply_filters( 'cbxmcratingreview_table_key_names', $table_key_names );
		}//end method getAllDBTablesKeyList

		/**
		 * List all global option name with prefix cbxpoll_
		 */
		public static function getAllOptionNames() {
			global $wpdb;

			$prefix       = 'cbxmcratingreview_';
			$option_names = $wpdb->get_results( "SELECT * FROM {$wpdb->options} WHERE option_name LIKE '{$prefix}%'", ARRAY_A );

			return apply_filters( 'cbxmcratingreview_option_names', $option_names );
		}//end method getAllOptionNames

		/**
		 * Get Single review by review id
		 *
		 * @param int $post_id
		 *
		 * @return null|string
		 */
		public static function singleReview( $review_id = 0 ) {
			global $wpdb;
			$table_rating_log = $wpdb->prefix . 'cbxmcratingreview_log';
			$table_users      = $wpdb->prefix . 'users';

			$review_id = intval( $review_id );

			$single_review = null;
			if ( $review_id > 0 ) {
				$join = $where_sql = $sql_select = '';
				$join = " LEFT JOIN $table_users AS users ON users.ID = log.user_id ";

				$where_sql = $wpdb->prepare( "log.id = %d", $review_id );

				$sql_select = "SELECT log.*, users.user_email, users.display_name FROM $table_rating_log AS log";

				$single_review = $wpdb->get_row( "$sql_select $join WHERE $where_sql ", 'ARRAY_A' );
				if ( $single_review !== null ) {
					$single_review['attachment']  = maybe_unserialize( $single_review['attachment'] );
					$single_review['extraparams'] = maybe_unserialize( $single_review['extraparams'] );
				}
			}

			return $single_review;
		}//end method singleReview

		/**
		 * Render single review by review id
		 *
		 * @param int $review_id
		 *
		 * @return string
		 */
		public static function singleReviewRender( $review_id = 0 ) {
			$single_review_html = '';

			if ( is_numeric( $review_id ) ) {
				$post_review = self::singleReview( intval( $review_id ) );
			} else {
				$post_review = $review_id;
			}

			if ( ! is_null( $post_review ) ) {

				$form_id = intval( $post_review['form_id'] );
				$form    = CBXMCRatingReviewHelper::getRatingForm( $form_id );

				ob_start();
				//include( apply_filters( 'cbxmcratingreview_tpl_rating-review-reviews-list-item', CBXMCRATINGREVIEW_ROOT_PATH . 'templates/rating-review-reviews-list-item.php' ) );
				include (cbxmcratingreview_locate_template('rating-review-reviews-list-item.php'));

				$single_review_html = ob_get_contents();
				ob_end_clean();
			}

			return $single_review_html;
		}//end method singleReviewRender

		/**
		 * Render single review edit form by review id
		 *
		 * @param int $review_id
		 *
		 * @return string
		 */
		public static function singleReviewEditRender( $review_id = 0 ) {
			cbxmcratingreview_AddJsCss();
			cbxmcratingreview_AddRatingEditFormJsCss();

			$review_edit_form_html = '';

			if ( ! is_user_logged_in() ) {
				$review_edit_url = add_query_arg( 'review_id', $review_id, get_permalink() );

				$url = wp_login_url( $review_edit_url );
				if ( headers_sent() ) {
					return '<a href="' . esc_url( $url ) . '">' . esc_html__( 'Please login to edit review', 'cbxmcratingreview' ) . '</a>';
				} else {
					//redirect any request to {site_url()}/wp-login.php?redirect_to={$requested_url}
					wp_redirect( $url );
					exit;
				}
			}

			if ( is_numeric( $review_id ) ) {
				$post_review = self::singleReview( intval( $review_id ) );

			} else {
				$post_review = $review_id;
			}


			if ( ! is_null( $post_review ) ) {
				$user_id             = intval( get_current_user_id() );
				$post_review_user_id = intval( $post_review['user_id'] );
				$post_review_status  = intval( $post_review['status'] );


				if ( $user_id > 0 && $user_id != $post_review_user_id ) {
					return esc_html__( 'Are you cheating, huh ?', 'cbxmcratingreview' );
				}

				if ( $post_review_status == 0 || $post_review_status == 1 ) {
					ob_start();
					//include( apply_filters( 'cbxmcratingreview_tpl_rating-review-editform', CBXMCRATINGREVIEW_ROOT_PATH . 'templates/rating-review-editform.php' ) );
					include (cbxmcratingreview_locate_template('rating-review-editform.php'));

					$review_edit_form_html = ob_get_contents();
					ob_end_clean();
				} else {
					return esc_html__( 'Sorry, you can edit only pending or published review', 'cbxmcratingreview' );
				}


			}

			return $review_edit_form_html;
		}//end method singleReviewEditRender


		/**
		 * Review lists data of a Post
		 *
		 * @param int    $form_id
		 * @param int    $post_id
		 * @param int    $perpage
		 * @param int    $page
		 * @param string $status
		 * @param string $score
		 * @param string $orderby
		 * @param string $order
		 *
		 * @return array|null|object
		 */
		public static function postReviews( $form_id = 0, $post_id = 0, $perpage = 10, $page = 1, $status = '', $score = '', $orderby = 'id', $order = 'DESC' ) {

			global $wpdb;
			$table_rating_log = $wpdb->prefix . 'cbxmcratingreview_log';
			$table_users      = $wpdb->prefix . 'users';

			$cbxmcratingreview_setting = new CBXMCRatingReviewSettings();

			$post_id = intval( $post_id );
			$post_id = ( $post_id == 0 ) ? intval( get_the_ID() ) : $post_id;

			if ( $form_id == 0 ) {
				$default_form = intval( $cbxmcratingreview_setting->get_option( 'default_form', 'cbxmcratingreview_common_config', 0 ) );
				if ( $default_form == 0 ) {
					return null;
				} else {
					$form_id = $default_form;
				}
			}

			$orderby = ( $orderby == '' ) ? 'id' : $orderby;
			$order   = ( $order == '' ) ? 'DESC' : $order;

			$post_reviews = null;

			if ( $post_id > 0 ) {
				$join = $where_sql = $sql_select = '';
				$join = " LEFT JOIN $table_users AS users ON users.ID = log.user_id ";

				$where_sql = $wpdb->prepare( "log.post_id=%d", $post_id );
				if ( $status != '' ) {
					$status = intval( $status );
					if ( $where_sql != '' ) {
						$where_sql .= ' AND ';
					}
					$where_sql .= $wpdb->prepare( ' log.status = %s', $status );
				}


				if ( $where_sql != '' ) {
					$where_sql .= ' AND ';
				}
				$where_sql .= $wpdb->prepare( ' log.form_id = %s', $form_id );


				if ( $score != '' ) {

					if ( $score == - 1 || $score == - 2 ) {

						$positive_score = intval( $cbxmcratingreview_setting->get_option( 'positive_score', 'cbxmcratingreview_common_config', 4 ) );

						//positive or critial score
						if ( $score == - 1 ) {
							//all positives
							if ( $where_sql != '' ) {
								$where_sql .= ' AND ';
							}
							$where_sql .= $wpdb->prepare( ' CEIL(log.score) >= %d', $positive_score );
						} else if ( $score == - 2 ) {
							//all criticals
							if ( $where_sql != '' ) {
								$where_sql .= ' AND ';
							}
							$where_sql .= $wpdb->prepare( ' CEIL(log.score) < %d', $positive_score );
						}
					} else {
						//regular score
						$score = ceil( $score );
						if ( $where_sql != '' ) {
							$where_sql .= ' AND ';
						}
						$where_sql .= $wpdb->prepare( ' CEIL(log.score) = %f', $score );
					}
				}


				$sql_select = "SELECT log.*, users.user_email, users.display_name FROM $table_rating_log AS log";


				$sorting_order = " ORDER BY $orderby $order ";

				$limit_sql = '';
				if ( $perpage != '-1' ) {
					$perpage     = intval( $perpage );
					$start_point = ( $page * $perpage ) - $perpage;
					$limit_sql   = "LIMIT";
					$limit_sql   .= ' ' . $start_point . ',';
					$limit_sql   .= ' ' . $perpage;
				}


				$post_reviews = $wpdb->get_results( "$sql_select $join WHERE $where_sql $sorting_order $limit_sql", 'ARRAY_A' );



				if ( $post_reviews !== null ) {
					foreach ( $post_reviews as &$post_review ) {
						$post_review['attachment']  = maybe_unserialize( $post_review['attachment'] );
						$post_review['extraparams'] = maybe_unserialize( $post_review['extraparams'] );
					}
				}
			}

			return $post_reviews;
		}//end method postReviews

		/**
		 * Review lists data of a Post by a User
		 *
		 * @param int    $post_id
		 * @param int    $user_id
		 * @param int    $perpage
		 * @param int    $page
		 * @param string $status
		 * @param string $score
		 * @param string $orderby
		 * @param string $order
		 *
		 * @return array|null|object|void
		 */
		/*public static function postReviewsByUser( $post_id = 0, $user_id = 0, $perpage = 10, $page = 1, $status = '', $score = '', $orderby = 'id', $order = 'DESC' ) {
			global $wpdb;
			$table_rating_log = $wpdb->prefix . 'cbxmcratingreview_log';

			$post_id = intval( $post_id );
			$post_id = ( $post_id == 0 ) ? intval( get_the_ID() ) : $post_id;
			$user_id = intval( $user_id );

			$post_review_by_user = null;
			if ( $post_id > 0 && $user_id > 0 ) {
				$post_review_by_user = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_rating_log WHERE post_id = %d AND user_id = %d", $post_id, $user_id ), ARRAY_A );
			}

			return $post_review_by_user;
		}*/


		/**
		 * Render the review filter template
		 *
		 * @param int    $form_id
		 * @param int    $post_id
		 * @param int    $perpage
		 * @param int    $page
		 * @param string $score
		 * @param string $orderby
		 * @param string $order
		 */
		public static function postReviewsFilterRender( $form_id = 0, $post_id = 0, $perpage = 10, $page = 1, $score = '', $orderby = 'id', $order = 'DESC' ) {

			global $current_user;
			$ok_to_render = false;

			$cbxmcratingreview_setting = new CBXMCRatingReviewSettings();


			$post_reviews_filter_html = '';

			$post_id   = intval( $post_id );
			$post_id   = ( $post_id == 0 ) ? intval( get_the_ID() ) : $post_id;
			$post_type = get_post_type( $post_id );
			if ( is_user_logged_in() ) {
				$user_id = get_current_user_id();;
			} else {
				$user_id = 0;
			}

			if ( $user_id == 0 ) {
				$userRoles = array( 'guest' );
			} else {
				$userRoles = $current_user->roles;
			}


			if ( $form_id == 0 ) {
				$default_form = intval( $cbxmcratingreview_setting->get_option( 'default_form', 'cbxmcratingreview_common_config', 0 ) );
				if ( $default_form == 0 ) {
					return $post_reviews_filter_html;
				} else {
					$form_id = $default_form;
				}
			}

			$form = CBXMCRatingReviewHelper::getRatingForm( $form_id );

			//check if post type supported
			//$post_types_supported = $cbxmcratingreview_setting->get_option( 'post_types', 'cbxmcratingreview_common_config', array() );
			$post_types_supported = isset( $form['post_types'] ) ? $form['post_types'] : array();


			if ( $post_id > 0 && is_array( $post_types_supported ) && sizeof( $post_types_supported ) > 0 && in_array( $post_type, $post_types_supported ) ) {
				$ok_to_render = true;
			}
			//end post type check

			//check if user role supported
			if ( $ok_to_render ) {
				//$user_roles_rate = $cbxmcratingreview_setting->get_option( 'user_roles_view', 'cbxmcratingreview_common_config', array() );
				$user_roles_rate = isset( $form['user_roles_view'] ) ? $form['user_roles_view'] : array();

				if ( ! is_array( $user_roles_rate ) ) {
					$user_roles_rate = array();
				}

				$intersectedRoles = array_intersect( $user_roles_rate, $userRoles );
				if ( sizeof( $intersectedRoles ) == 0 ) {
					$ok_to_render = false;
				}
			}//end check user role support


			$total_reviews = cbxmcratingreview_totalPostReviewsCount( $form_id, $post_id, 1 );

			if ( $ok_to_render ) {
				ob_start();
				//include( apply_filters( 'cbxmcratingreview_tpl_rating-review-reviews-list-filter', CBXMCRATINGREVIEW_ROOT_PATH . 'templates/rating-review-reviews-list-filter.php' ) );
				include (cbxmcratingreview_locate_template('rating-review-reviews-list-filter.php'));
				$post_reviews_filter_html = ob_get_contents();
				ob_end_clean();
			}

			return $post_reviews_filter_html;
		}//end method postReviewsFilterRender

		/**
		 * render Review lists data of a Post
		 *
		 * @param int    $form_id
		 * @param int    $post_id
		 * @param int    $perpage
		 * @param int    $page
		 * @param string $status
		 * @param string $score
		 * @param string $orderby
		 * @param string $order
		 * @param bool   $load_more
		 * @param bool   $show_filter
		 *
		 * @return string
		 */
		public static function postReviewsRender( $form_id = 0, $post_id = 0, $perpage = 10, $page = 1, $status = '', $score = '', $orderby = 'id', $order = 'DESC', $load_more = false ) {
			global $current_user;
			$ok_to_render = false;

			$cbxmcratingreview_setting = new CBXMCRatingReviewSettings();

			$post_reviews_html = '';

			if ( $form_id == 0 ) {
				$default_form = intval( $cbxmcratingreview_setting->get_option( 'default_form', 'cbxmcratingreview_common_config', 0 ) );
				if ( $default_form == 0 ) {
					return $post_reviews_html;
				} else {
					$form_id = $default_form;
				}
			}

			$form = CBXMCRatingReviewHelper::getRatingForm( $form_id );

			$post_id   = intval( $post_id );
			$post_id   = ( $post_id == 0 ) ? intval( get_the_ID() ) : $post_id;
			$post_type = get_post_type( $post_id );
			if ( is_user_logged_in() ) {
				$user_id = get_current_user_id();;
			} else {
				$user_id = 0;
			}

			if ( $user_id == 0 ) {
				$userRoles = array( 'guest' );
			} else {
				$userRoles = $current_user->roles;
			}


			//check if post type supported
			//$post_types_supported = $cbxmcratingreview_setting->get_option( 'post_types', 'cbxmcratingreview_common_config', array() );
			$post_types_supported = isset( $form['post_types'] ) ? $form['post_types'] : array();


			if ( $post_id > 0 && is_array( $post_types_supported ) && sizeof( $post_types_supported ) > 0 && in_array( $post_type, $post_types_supported ) ) {
				$ok_to_render = true;
			}
			//end post type check


			//check if user role supported
			if ( $ok_to_render ) {
				//$user_roles_rate = $cbxmcratingreview_setting->get_option( 'user_roles_view', 'cbxmcratingreview_common_config', array() );
				$user_roles_rate = isset( $form['user_roles_view'] ) ? $form['user_roles_view'] : array();

				if ( ! is_array( $user_roles_rate ) ) {
					$user_roles_rate = array();
				}

				$intersectedRoles = array_intersect( $user_roles_rate, $userRoles );
				if ( sizeof( $intersectedRoles ) == 0 ) {
					$ok_to_render = false;
				}
			}//end check user role support

			if ( $ok_to_render ) {
				/*if ( $load_more == false ) {
					cbxmcratingreview_AddJsCss();
				}*/


				ob_start();

				$post_reviews = self::postReviews( $form_id, $post_id, $perpage, $page, $status, $score, $orderby, $order );
				if ( $load_more ) {
					//include( apply_filters( 'cbxmcratingreview_tpl_rating-review-reviews-list', CBXMCRATINGREVIEW_ROOT_PATH . 'templates/rating-review-reviews-list.php' ) );
					include (cbxmcratingreview_locate_template('rating-review-reviews-list.php'));
				} else {
					if ( sizeof( $post_reviews ) > 0 ) {
						foreach ( $post_reviews as $index => $post_review ) { ?>
							<li id="cbxmcratingreview_review_list_item_<?php echo intval( $post_review['id'] ); ?>"
								class="<?php echo apply_filters( 'cbxmcratingreview_review_list_item_class', 'cbxmcratingreview_review_list_item' ); ?>">
								<?php
									//include( apply_filters( 'cbxmcratingreview_tpl_rating-review-reviews-list-item', CBXMCRATINGREVIEW_ROOT_PATH . 'templates/rating-review-reviews-list-item.php' ) );
									include (cbxmcratingreview_locate_template('rating-review-reviews-list-item.php'));
								?>
							</li>
						<?php }
					} else {
						?>
						<li class="<?php echo apply_filters( 'cbxmcratingreview_review_list_item_class_notfound_class', 'cbxmcratingreview_review_list_item cbxmcratingreview_review_list_item_notfound' ); ?>">
							<p class="no_reviews_found"><?php esc_html_e( 'No reviews yet!', '' ); ?></p>
						</li>
						<?php
					}
				}

				if ( $load_more ) {
					$total_count   = cbxmcratingreview_totalPostReviewsCount( $form_id, $post_id, $status, $score );
					$maximum_pages = ceil( $total_count / $perpage );


					//include( apply_filters( 'cbxmcratingreview_tpl_rating-review-reviews-list-more', CBXMCRATINGREVIEW_ROOT_PATH . 'templates/rating-review-reviews-list-more.php' ) );


					include (cbxmcratingreview_locate_template('rating-review-reviews-list-more.php'));

				}


				$post_reviews_html = ob_get_contents();
				ob_end_clean();

				return $post_reviews_html;
			}

			return $post_reviews_html;
		}//end method postReviewsRender


		/**
		 * All Review lists data
		 *
		 * @param int/string    $form_id
		 * @param int    $perpage
		 * @param int    $page
		 * @param string $status
		 * @param string $orderby
		 * @param string $order
		 * @param string $score
		 *
		 * @return array|null|object
		 */
		public static function Reviews( $form_id = '', $perpage = 10, $page = 1, $status = '', $orderby = 'id', $order = 'DESC', $score = '' ) {

			global $wpdb;
			$table_rating_log = $wpdb->prefix . 'cbxmcratingreview_log';
			//$table_rating_avg_log = $wpdb->prefix . 'cbxmcratingreview_log_avg';
			$table_users = $wpdb->prefix . 'users';


			$post_reviews = null;

			$join = $where_sql = $sql_select = '';

			//$join = " LEFT JOIN $table_users AS users ON users.ID = log.user_id ";

			if ( $form_id != '' ) {
				$form_id = intval( $form_id );
				if ( $where_sql != '' ) {
					$where_sql .= ' AND ';
				}
				$where_sql .= $wpdb->prepare( ' log.form_id = %d ', $form_id );
			}

			if ( $status != '' ) {
				$status = intval( $status );
				if ( $where_sql != '' ) {
					$where_sql .= ' AND ';
				}
				$where_sql .= $wpdb->prepare( ' log.status = %d', $status );
			}

			if ( $score != '' ) {
				$score = ceil( $score );
				if ( $where_sql != '' ) {
					$where_sql .= ' AND ';
				}
				$where_sql .= $wpdb->prepare( ' CEIL(log.score) = %d', $score );
			}

			//$sql_select = "SELECT log.*, users.user_email, users.display_name FROM $table_rating_log AS log";
			$sql_select = "SELECT log.* FROM $table_rating_log AS log";

			$sorting_order = " ORDER BY $orderby $order ";

			$limit_sql = '';
			if ( $perpage != '-1' ) {
				$start_point = ( $page * $perpage ) - $perpage;
				$limit_sql   = "LIMIT";
				$limit_sql   .= ' ' . $start_point . ',';
				$limit_sql   .= ' ' . $perpage;
			}


			if ( $where_sql == '' ) {
				$where_sql = ' 1 ';
			}

			$post_reviews = $wpdb->get_results( "$sql_select $join WHERE $where_sql $sorting_order $limit_sql", 'ARRAY_A' );


			return $post_reviews;
		}//end method Reviews


		/**
		 * All Review lists data by a User
		 *
		 * @param int/string    $form_id
		 * @param int    $user_id
		 * @param int    $page
		 * @param int    $perpage
		 * @param string $status
		 *
		 * @return array|null|object
		 */
		public static function ReviewsByUser( $form_id = '', $user_id = 0, $perpage = 10, $page = 1, $status = '', $orderby = 'id', $order = 'DESC', $filter_score = '' ) {
			global $wpdb;
			$table_rating_log = $wpdb->prefix . 'cbxmcratingreview_log';
			//$table_rating_avg_log = $wpdb->prefix . 'cbxmcratingreview_log_avg';
			$table_users = $wpdb->prefix . 'users';

			$user_id = intval( $user_id );
			$user_id = ( $user_id == 0 ) ? intval( get_current_user_id() ) : $user_id;

			$post_reviews_by_user = null;
			if ( $user_id > 0 ) {
				$join = $where_sql = $sql_select = '';

				$join = " LEFT JOIN $table_users AS users ON users.ID = log.user_id ";
				$join = apply_filters( 'cbxmcratingreview_ReviewsByUser_join', $join, $user_id, $perpage, $page, $status, $orderby, $order );

				$where_sql = $wpdb->prepare( "log.user_id=%d", $user_id );

				if ( $status != '' ) {
					$status = intval( $status );
					if ( $where_sql != '' ) {
						$where_sql .= ' AND ';
					}
					$where_sql .= $wpdb->prepare( ' log.status = %s', $status );
				}

				if ( is_numeric( $form_id ) && intval( $form_id ) > 0 ) {
					$form_id = intval( $form_id );
					if ( $where_sql != '' ) {
						$where_sql .= ' AND ';
					}
					$where_sql .= $wpdb->prepare( ' log.form_id = %d ', $form_id );
				}

				if ( $filter_score != '' ) {
					$filter_score = ceil( $filter_score );
					if ( $where_sql != '' ) {
						$where_sql .= ' AND ';
					}
					$where_sql .= $wpdb->prepare( ' CEIL(log.score) = %s', $filter_score );
				}

				$sql_select = "log.*, users.user_email, users.display_name";
				$sql_select = apply_filters( 'cbxmcratingreview_ReviewsByUser_select', $sql_select, $user_id, $perpage, $page, $status, $orderby, $order );

				$sorting_order = " ORDER BY $orderby $order ";

				$limit_sql = '';
				if ( $perpage != '-1' ) {
					$start_point = ( $page * $perpage ) - $perpage;
					$limit_sql   = "LIMIT";
					$limit_sql   .= ' ' . $start_point . ',';
					$limit_sql   .= ' ' . $perpage;
				}

				$where_sql = apply_filters( 'cbxmcratingreview_ReviewsByUser_where', $where_sql, $user_id, $perpage, $page, $status, $orderby, $order );

				if ( $where_sql == '' ) {
					$where_sql = ' 1 ';
				}


				$post_reviews_by_user = $wpdb->get_results( "SELECT $sql_select FROM $table_rating_log AS log $join WHERE $where_sql $sorting_order $limit_sql", 'ARRAY_A' );
			}

			return $post_reviews_by_user;
		}//end method ReviewsByUser

		/**
		 * Total reviews count
		 *
		 * @param int/string $form_id
		 * @param string $status
		 * @param string $filter_score
		 *
		 * @return int
		 */
		public static function totalReviewsCount( $form_id = '', $status = '', $filter_score = '' ) {
			global $wpdb;
			$table_rating_log = $wpdb->prefix . 'cbxmcratingreview_log';
			//$table_rating_avg_log = $wpdb->prefix . 'cbxmcratingreview_log_avg';
			//$table_users          = $wpdb->prefix . 'users';


			$totalcount = 0;

			$join = $where_sql = $sql_select = '';


			if ( $form_id != '' ) {
				$form_id = intval( $form_id );
				if ( $where_sql != '' ) {
					$where_sql .= ' AND ';
				}
				$where_sql .= $wpdb->prepare( ' log.form_id = %d ', $form_id );
			}

			if ( $status != '' ) {
				$status = intval( $status );
				if ( $where_sql != '' ) {
					$where_sql .= ' AND ';
				}
				$where_sql .= $wpdb->prepare( ' log.status = %s', $status );
			}

			if ( $filter_score != '' ) {
				$filter_score = ceil( $filter_score );
				if ( $where_sql != '' ) {
					$where_sql .= ' AND ';
				}
				$where_sql .= $wpdb->prepare( ' CEIL(log.score) = %s', $filter_score );
			}

			$sql_select = "SELECT COUNT(*) as totalcount FROM $table_rating_log AS log";

			if ( $where_sql == '' ) {
				$where_sql = ' 1 ';
			}

			$totalcount = $wpdb->get_var( "$sql_select WHERE $where_sql" );

			return intval( $totalcount );
		}//end method totalReviewsCount

		//

		/**
		 * Total reviews count
		 *
		 * @param int/string $form_id
		 * @param string $post_type
		 * @param string $status
		 * @param string $filter_score
		 *
		 * @return int
		 */
		public static function totalReviewsCountPostType( $form_id = '', $post_type = 'post', $status = '', $filter_score = '' ) {
			global $wpdb;
			$table_rating_log = $wpdb->prefix . 'cbxmcratingreview_log';
			//$table_rating_avg_log = $wpdb->prefix . 'cbxmcratingreview_log_avg';
			//$table_users          = $wpdb->prefix . 'users';


			$totalcount = 0;

			$join = $where_sql = $sql_select = '';

			$where_sql .= $wpdb->prepare( ' log.post_type = %s', $post_type );

			if ( $form_id != '' ) {
				$form_id = intval( $form_id );
				if ( $where_sql != '' ) {
					$where_sql .= ' AND ';
				}
				$where_sql .= $wpdb->prepare( ' log.form_id = %d ', $form_id );
			}

			if ( $status != '' ) {
				$status = intval( $status );
				if ( $where_sql != '' ) {
					$where_sql .= ' AND ';
				}
				$where_sql .= $wpdb->prepare( ' log.status = %s', $status );
			}

			if ( $filter_score != '' ) {
				$filter_score = ceil( $filter_score );
				if ( $where_sql != '' ) {
					$where_sql .= ' AND ';
				}
				$where_sql .= $wpdb->prepare( ' CEIL(log.score) = %s', $filter_score );
			}

			$sql_select = "SELECT COUNT(*) as totalcount FROM $table_rating_log AS log";

			if ( $where_sql == '' ) {
				$where_sql = ' 1 ';
			}

			$totalcount = $wpdb->get_var( "$sql_select WHERE $where_sql" );

			return intval( $totalcount );
		}//end method totalReviewsCountPostType

		/**
		 * Total reviews count by User
		 *
		 * @param int/string $form_id
		 * @param int    $user_id
		 * @param string $status
		 * @param string $filter_score
		 *
		 * @return int
		 */
		public static function totalReviewsCountByUser( $form_id = '', $user_id = 0, $status = '', $filter_score = '' ) {
			global $wpdb;
			$table_rating_log = $wpdb->prefix . 'cbxmcratingreview_log';
			//$table_rating_avg_log = $wpdb->prefix . 'cbxmcratingreview_log_avg';
			//$table_users          = $wpdb->prefix . 'users';

			$user_id = intval( $user_id );

			$user_id = ( $user_id == 0 ) ? intval( get_current_user_id() ) : $user_id;
			if ( $user_id == 0 ) {
				return 0;
			}


			$totalcount = 0;

			$join = $where_sql = $sql_select = '';


			if ( $form_id != '' ) {
				$form_id = intval( $form_id );
				if ( $where_sql != '' ) {
					$where_sql .= ' AND ';
				}
				$where_sql .= $wpdb->prepare( ' log.form_id = %d ', $form_id );
			}

			if ( $status != '' ) {
				$status = intval( $status );
				if ( $where_sql != '' ) {
					$where_sql .= ' AND ';
				}
				$where_sql .= $wpdb->prepare( ' log.status = %s', $status );
			}

			if ( $filter_score != '' ) {
				$filter_score = ceil( $filter_score );
				if ( $where_sql != '' ) {
					$where_sql .= ' AND ';
				}
				$where_sql .= $wpdb->prepare( ' CEIL(log.score) = %s', $filter_score );
			}

			if ( $where_sql != '' ) {
				$where_sql .= ' AND ';
			}
			$where_sql .= $wpdb->prepare( ' log.user_id = %d', $user_id );

			$sql_select = "SELECT COUNT(*) as totalcount FROM $table_rating_log AS log";

			if ( $where_sql == '' ) {
				$where_sql = ' 1 ';
			}

			$totalcount = $wpdb->get_var( "$sql_select WHERE $where_sql" );

			return intval( $totalcount );
		}//end method totalReviewsCountByUser


		/**
		 * Total reviews count of a Post
		 *
		 * @param int    $form_id
		 * @param int    $post_id
		 * @param string $status
		 * @param string $score
		 *
		 * @return int
		 */
		public static function totalPostReviewsCount( $form_id = 0, $post_id = 0, $status = '', $score = '' ) {
			$reviews_count = 0;

			global $wpdb;
			$table_rating_log          = $wpdb->prefix . 'cbxmcratingreview_log';
			$cbxmcratingreview_setting = new CBXMCRatingReviewSettings();


			if ( $form_id == 0 ) {
				$default_form = intval( $cbxmcratingreview_setting->get_option( 'default_form', 'cbxmcratingreview_common_config', 0 ) );
				if ( $default_form == 0 ) {
					return 0;
				} else {
					$form_id = $default_form;
				}
			}

			$post_id = intval( $post_id );
			$post_id = ( $post_id == 0 ) ? intval( get_the_ID() ) : $post_id;

			if ( $post_id == 0 ) {
				return 0;
			}

			$join = $where_sql = $sql_select = '';
			if ( $status != '' ) {
				$status = intval( $status );
				if ( $where_sql != '' ) {
					$where_sql .= ' AND ';
				}
				$where_sql .= $wpdb->prepare( ' log.status = %s', $status );
			}

			if ( $score != '' ) {
				if ( $score == - 1 || $score == - 2 ) {
					$positive_score = intval( $cbxmcratingreview_setting->get_option( 'positive_score', 'cbxmcratingreview_common_config', 4 ) );
					//positive or critial score
					if ( $score == - 1 ) {
						//all positives
						if ( $where_sql != '' ) {
							$where_sql .= ' AND ';
						}
						$where_sql .= $wpdb->prepare( ' CEIL(log.score) >= %d', $positive_score );
					} else if ( $score == - 2 ) {
						//all criticals
						if ( $where_sql != '' ) {
							$where_sql .= ' AND ';
						}
						$where_sql .= $wpdb->prepare( ' CEIL(log.score) < %d', $positive_score );
					}
				} else {
					$score = ceil( $score );


					if ( $where_sql != '' ) {
						$where_sql .= ' AND ';
					}
					$where_sql .= $wpdb->prepare( ' CEIL(log.score) = %f', $score );
				}

			}

			if ( $where_sql != '' ) {
				$where_sql .= ' AND ';
			}
			$where_sql .= $wpdb->prepare( ' log.form_id = %d', $form_id );

			if ( $where_sql != '' ) {
				$where_sql .= ' AND ';
			}
			$where_sql .= $wpdb->prepare( ' log.post_id = %d', $post_id );

			if ( $where_sql == '' ) {
				$where_sql = ' 1 ';
			}

			$count = $wpdb->get_var( "SELECT COUNT(*) as count FROM $table_rating_log as log WHERE $where_sql" );
			if ( $count !== null ) {
				return intval( $count );
			} else {
				return 0;
			}
		}//end method totalPostReviewsCount

		/**
		 * Total reviews count of a Post by a User
		 *
		 * @param int    $form_id
		 * @param int    $post_id
		 * @param int    $user_id
		 * @param string $status
		 *
		 * @return int
		 */
		public static function totalPostReviewsCountByUser( $form_id = 0, $post_id = 0, $user_id = 0, $status = '' ) {

			global $wpdb;
			$table_rating_log = $wpdb->prefix . 'cbxmcratingreview_log';

			$cbxmcratingreview_setting = new CBXMCRatingReviewSettings();

			if ( $form_id == 0 ) {
				$default_form = intval( $cbxmcratingreview_setting->get_option( 'default_form', 'cbxmcratingreview_common_config', 0 ) );
				if ( $default_form == 0 ) {
					return 0;
				} else {
					$form_id = $default_form;
				}
			}

			$post_id = intval( $post_id );
			$post_id = ( $post_id == 0 ) ? intval( get_the_ID() ) : $post_id;

			if ( $post_id == 0 ) {
				return 0;
			}

			$user_id = intval( $user_id );

			$user_id = ( $user_id == 0 ) ? intval( get_current_user_id() ) : $user_id;
			if ( $user_id == 0 ) {
				return 0;
			}

			$join = $where_sql = $sql_select = '';

			if ( $status != '' ) {
				$status = intval( $status );
				if ( $where_sql != '' ) {
					$where_sql .= ' AND ';
				}
				$where_sql .= $wpdb->prepare( ' log.status = %s', $status );
			}

			if ( $where_sql != '' ) {
				$where_sql .= ' AND ';
			}
			$where_sql .= $wpdb->prepare( ' log.form_id = %d', $form_id );

			if ( $where_sql != '' ) {
				$where_sql .= ' AND ';
			}
			$where_sql .= $wpdb->prepare( ' log.post_id = %d', $post_id );

			if ( $where_sql != '' ) {
				$where_sql .= ' AND ';
			}
			$where_sql .= $wpdb->prepare( ' log.user_id = %d', $user_id );

			if ( $where_sql == '' ) {
				$where_sql = ' 1 ';
			}


			$count = $wpdb->get_var( "SELECT COUNT(*) as count FROM $table_rating_log as log WHERE $where_sql" );
			if ( $count !== null ) {
				return intval( $count );
			} else {
				return 0;
			}
		}//end method totalPostReviewsCountByUser

		/**
		 * is this post rated previously
		 *
		 * @param int $form_id
		 * @param int $post_id
		 *
		 * @return bool
		 */
		public static function isPostRated( $form_id = 0, $post_id = 0 ) {

			$cbxmcratingreview_setting = new CBXMCRatingReviewSettings();

			if ( $form_id == 0 ) {
				$default_form = intval( $cbxmcratingreview_setting->get_option( 'default_form', 'cbxmcratingreview_common_config', 0 ) );
				$form_id      = $default_form;
			}

			if ( $form_id == 0 ) {
				return false;
			}

			$post_id = intval( $post_id );
			$post_id = ( $post_id == 0 ) ? intval( get_the_ID() ) : $post_id;

			$is_post_rated = false;

			if ( $post_id == 0 ) {
				return false;
			}

			if ( $post_id > 0 ) {
				$is_post_rated = ( CBXMCRatingReviewHelper::totalPostReviewsCount( $form_id, $post_id, '' ) > 0 ) ? true : false;
			}

			return $is_post_rated;
		}//end method isPostRated


		/**
		 * is this post rated previously by user
		 *
		 * @param int $form_id
		 * @param int $post_id
		 * @param int $user_id
		 *
		 * @return true/false
		 */
		public static function isPostRatedByUser( $form_id = 0, $post_id = 0, $user_id = 0 ) {

			$cbxmcratingreview_setting = new CBXMCRatingReviewSettings();


			$is_post_rated_by_user = false;

			if ( $form_id == 0 ) {
				$default_form = intval( $cbxmcratingreview_setting->get_option( 'default_form', 'cbxmcratingreview_common_config', 0 ) );
				$form_id      = $default_form;
			}

			if ( $form_id == 0 ) {
				return $is_post_rated_by_user;
			}

			$post_id = intval( $post_id );
			$post_id = ( $post_id == 0 ) ? intval( get_the_ID() ) : $post_id;

			if ( $post_id == 0 ) {
				return $is_post_rated_by_user;
			}

			$user_id = intval( $user_id );

			$user_id = ( $user_id == 0 ) ? intval( get_current_user_id() ) : $user_id;
			if ( $user_id == 0 ) {
				return $is_post_rated_by_user;
			}


			if ( $post_id > 0 && $user_id > 0 ) {
				$is_post_rated_by_user = ( CBXMCRatingReviewHelper::totalPostReviewsCountByUser( $form_id, $post_id, $user_id, '' ) > 0 ) ? true : false;
			}

			return $is_post_rated_by_user;
		}//end method isPostRatedByUser

		/**
		 * Last Post rate date by a User
		 *
		 * @param int $form_id
		 * @param int $post_id
		 * @param int $user_id
		 *
		 * @return  datetime
		 */
		public static function lastPostReviewDateByUser( $form_id = 0, $post_id = 0, $user_id = 0 ) {
			global $wpdb;
			$table_rating_log = $wpdb->prefix . 'cbxmcratingreview_log';


			$cbxmcratingreview_setting = new CBXMCRatingReviewSettings();

			if ( $form_id == 0 ) {
				$default_form = intval( $cbxmcratingreview_setting->get_option( 'default_form', 'cbxmcratingreview_common_config', 0 ) );
				$form_id      = $default_form;
			}

			if ( $form_id == 0 ) {
				return null;
			}

			$post_id = intval( $post_id );
			$post_id = ( $post_id == 0 ) ? intval( get_the_ID() ) : $post_id;

			if ( $post_id == 0 ) {
				return null;
			}

			$user_id = intval( $user_id );

			$user_id = ( $user_id == 0 ) ? intval( get_current_user_id() ) : $user_id;
			if ( $user_id == 0 ) {
				return null;
			}

			$date = $wpdb->get_var( $wpdb->prepare( "SELECT date_created FROM $table_rating_log WHERE post_id = %d AND user_id=%d ORDER BY date_created DESC", $post_id, $user_id ) );

			return $date;
		}//end method lastPostReviewDateByUser

		/**
		 * Average rating information of a post
		 *
		 * @param int $form_id
		 * @param int $post_id
		 *
		 * @return null|string
		 */
		public static function postAvgRatingInfo( $form_id = 0, $post_id = 0 ) {
			global $wpdb;
			$table_rating_avg_log = $wpdb->prefix . 'cbxmcratingreview_log_avg';

			$post_avg_rating = null;

			$cbxmcratingreview_setting = new CBXMCRatingReviewSettings();

			if ( $form_id == 0 ) {
				$default_form = intval( $cbxmcratingreview_setting->get_option( 'default_form', 'cbxmcratingreview_common_config', 0 ) );
				$form_id      = $default_form;
			}

			if ( $form_id == 0 ) {
				return $post_avg_rating;
			}

			$post_id = intval( $post_id );
			$post_id = ( $post_id == 0 ) ? intval( get_the_ID() ) : $post_id;


			if ( $post_id > 0 ) {
				$post_avg_rating = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_rating_avg_log WHERE form_id = %d AND post_id = %d", intval( $form_id ), intval( $post_id ) ), ARRAY_A );
			}


			if ( is_null( $post_avg_rating ) ) {
				$post_avg_rating['id']            = 0;
				$post_avg_rating['form_id']       = $form_id;
				$post_avg_rating['post_id']       = $post_id;
				$post_avg_rating['avg_rating']    = 0;
				$post_avg_rating['total_count']   = 0;
				$post_avg_rating['rating_stat']   = array();
				$post_avg_rating['date_created']  = null;
				$post_avg_rating['date_modified'] = null;

				//extra
				$post_avg_rating['rating_stat_scores'] = array();

			} else {
				$post_avg_rating['rating_stat'] = maybe_unserialize( $post_avg_rating['rating_stat'] );

				if ( isset( $post_avg_rating['rating_stat']['rating_stat_scores'] ) ) {
					$post_avg_rating['rating_stat_scores'] = maybe_unserialize( $post_avg_rating['rating_stat']['rating_stat_scores'] );
				} else {
					$post_avg_rating['rating_stat_scores'] = array();
				}
			}


			return apply_filters( 'cbxmcratingreview_post_avg', $post_avg_rating, $post_id );
		}//end postAvgRatingInfo

		/**
		 * Single avg rating info by avg id
		 *
		 * @param int $avg_id
		 *
		 * @return array|null|object|void
		 */
		public static function singleAvgRatingInfo( $avg_id = 0 ) {
			global $wpdb;
			$table_rating_avg = $wpdb->prefix . 'cbxmcratingreview_log_avg';

			$avg_id = intval( $avg_id );

			$single_avg_rating = null;
			if ( $avg_id > 0 ) {
				$single_avg_rating = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_rating_avg WHERE id = %d", intval( $avg_id ) ), ARRAY_A );
				if ( $single_avg_rating !== null ) {
					$single_avg_rating['rating_stat'] = maybe_unserialize( $single_avg_rating['rating_stat'] );
					if ( isset( $single_avg_rating['rating_stat']['rating_stat_scores'] ) ) {
						$single_avg_rating['rating_stat_scores'] = maybe_unserialize( $single_avg_rating['rating_stat']['rating_stat_scores'] );
					} else {
						$single_avg_rating['rating_stat_scores'] = array();
					}
				}

			}

			return $single_avg_rating;
		}//end singleAvgRatingInfo


		/**
		 * Add or update Avg calculation
		 *
		 * @param $review_info
		 *
		 */
		public static function calculatePostAvg( $review_info ) {

			//we need to calculate avg of avg and same time avg for each single criteria id/criteria

			global $wpdb;
			$table_rating_avg_log      = $wpdb->prefix . 'cbxmcratingreview_log_avg';
			$cbxmcratingreview_setting = new CBXMCRatingReviewSettings();

			$post_id           = intval( $review_info['post_id'] );
			$form_id           = intval( $review_info['form_id'] );
			$score             = $review_info['score'];
			$ceil_rating_score = ceil( $score );
			$ratings           = isset( $review_info['ratings'] ) ? maybe_unserialize( $review_info['ratings'] ) : array();


			$post_avg_rating = cbxmcratingreview_postAvgRatingInfo( $form_id, $post_id );
			$ratings_stars   = isset( $ratings['ratings_stars'] ) ? maybe_unserialize( $ratings['ratings_stars'] ) : array();

			//if fresh avg calculation
			if ( is_null( $post_avg_rating ) || intval( $post_avg_rating['id'] ) == 0 ) {

				$rating_stat = array();

				//rating score percentage calculation
				$rating_stat_scores                       = array();
				$rating_stat_scores[ $ceil_rating_score ] = array(
					'count'   => 1,
					'percent' => 100,
				);
				$rating_stat['rating_stat_scores']        = $rating_stat_scores;

				//calculate criteria based rating stat
				if ( is_array( $ratings_stars ) && sizeof( $ratings_stars ) > 0 ) {
					$criteria_rating_stat_scores = array();
					$criteria_infos               = array();

					foreach ( $ratings_stars as $criteria_id => $ratings_star ) {
						$criteria_score                                                  = isset( $ratings_star['score_standard'] ) ? $ratings_star['score_standard'] : 0; //score in 5
						$criteria_infos[ $criteria_id ] = array(
							'avg_rating'  => $criteria_score,
							'total_count' => 1,
						);

						$criteria_score_ceil                                                 = ceil( $criteria_score );
						$criteria_rating_stat_scores[ $criteria_id ][ $criteria_score_ceil ] = array(
							'count'   => 1,
							'percent' => 100,
						);
					}

					$rating_stat['criteria_stat_scores'] = $criteria_rating_stat_scores;
					$rating_stat['criteria_info']        = $criteria_infos;
				}
				//end calculate criteria based rating stat


				$avg_insert_status = $wpdb->insert(
					$table_rating_avg_log,
					array(
						'post_id'      => $post_id,
						'form_id'      => $form_id,
						'post_type'    => get_post_type( $post_id ),
						'avg_rating'   => $score,
						'total_count'  => 1,
						'date_created' => current_time( 'mysql' ),
						'rating_stat'  => maybe_serialize( $rating_stat ),
					),
					array(
						'%d', // post_id,
						'%d', // form_id,
						'%s', // post_type
						'%f', // avg_rating
						'%d', // total_count
						'%s', // date_created
						'%s', // rating_stat
					)
				);

				if ( $avg_insert_status !== false ) {
					//add post avg in post meta key
					update_post_meta( $post_id, '_cbxmcratingreview_avg', $score ); //todo: need ceil ?
					update_post_meta( $post_id, '_cbxmcratingreview_total', 1 );
				}
				//send the currently added review as html
			} else {
				// update avg rating
				$total_score     = ( $post_avg_rating['avg_rating'] * $post_avg_rating['total_count'] ) + $score;
				$total_count_new = intval( $post_avg_rating['total_count'] ) + 1;
				$score_new       = number_format( ( $total_score / $total_count_new ), 2 );


				$rating_stat = maybe_unserialize( $post_avg_rating['rating_stat'] );

				//rating score percentage calculation
				$rating_stat_scores = $rating_stat['rating_stat_scores'];


				if ( isset( $rating_stat_scores[ $ceil_rating_score ] ) ) {
					$new_count                                         = intval( $rating_stat_scores[ $ceil_rating_score ]['count'] ) + 1;
					$rating_stat_scores[ $ceil_rating_score ]['count'] = $new_count;
				} else {
					$rating_stat_scores[ $ceil_rating_score ]['count'] = 1;
				}

				//calculate percentage once again
				foreach ( $rating_stat_scores as $score_loop => $count_percent ) {
					$rating_stat_scores[ $score_loop ]['percent'] = number_format( ( intval( $rating_stat_scores[ $score_loop ]['count'] ) / $total_count_new ) * 100, 2 );
				}

				$rating_stat['rating_stat_scores'] = $rating_stat_scores;

				//calculate criteria based rating stat
				$criteria_infos       = isset( $rating_stat['criteria_info'] ) ? $rating_stat['criteria_info'] : array();
				$criteria_infosT = array();
				$criteria_stat_scores = isset( $rating_stat['criteria_stat_scores'] ) ? $rating_stat['criteria_stat_scores'] : array();
				$criteria_stat_scoresT = array();

				if ( is_array( $ratings_stars ) && sizeof( $ratings_stars ) > 0 ) {
					foreach ( $ratings_stars as $criteria_id => $ratings_star ) {
						$criteria_info = isset( $criteria_infos[ $criteria_id ] ) ? $criteria_infos[ $criteria_id ] : array();

						$criteria_avg_rating  = isset( $criteria_info['avg_rating'] ) ? $criteria_info['avg_rating'] : 0;
						$criteria_total_count = isset( $criteria_info['total_count'] ) ? $criteria_info['total_count'] : 0;

						$rating_score  		= isset( $ratings_star['score_standard'] ) ? $ratings_star['score_standard'] : 0; //score in 5
						$rating_score_ceil 	= ceil( $rating_score );

						$criteria_total_score 		= $criteria_avg_rating * $criteria_total_count ;
						$criteria_total_score_new 	= $criteria_total_score + $rating_score;
						$criteria_total_count_new   = intval( $criteria_total_score ) + 1;

						$criteria_avg_rating_new    = number_format( ( $criteria_total_score_new / $criteria_total_count_new ), 2 );

						$criteria_infosT[ $criteria_id ] = array(
							'avg_rating'  => $criteria_avg_rating_new,
							'total_count' => $criteria_total_count_new,
						);

						$criteria_stat_score = isset( $criteria_stat_scores[ $criteria_id ] ) ? $criteria_stat_scores[ $criteria_id ] : array();


						if ( isset( $criteria_stat_score[ $rating_score_ceil ] ) ) {
							$new_count                                          = intval( $criteria_stat_score[ $rating_score_ceil ]['count'] ) + 1;
							$criteria_stat_score[ $rating_score_ceil ]['count'] = $new_count;
						} else {
							$criteria_stat_score[ $rating_score_ceil ]['count'] = 1;
						}

						//calculate percentage once again
						foreach ( $criteria_stat_score as $score_loop => $count_percent ) {
							$criteria_stat_score[ $score_loop ]['percent'] = number_format( ( intval( $criteria_stat_score[ $score_loop ]['count'] ) / $criteria_total_count_new ) * 100, 2 );
						}

						$criteria_stat_scoresT[ $criteria_id ] = $criteria_stat_score;

					}

					$rating_stat['criteria_info']        = $criteria_infosT;
					$rating_stat['criteria_stat_scores'] = $criteria_stat_scoresT;
				}//end calculate criteria based rating stat


				$avg_update_status = $wpdb->update(
					$table_rating_avg_log,
					array(
						'avg_rating'    => $score_new,
						'total_count'   => $total_count_new,
						'rating_stat'   => maybe_serialize( $rating_stat ),
						'date_modified' => current_time( 'mysql' ),
					),
					array( 'id' => $post_avg_rating['id'] ),
					array(
						'%f', // avg_rating
						'%d', // total_count
						'%s', // rating_stat
						'%s', // date_modifed
					),
					array(
						'%d',
					)
				);

				if ( $avg_update_status !== false ) {
					//update post avg in post meta key
					update_post_meta( $post_id, '_cbxmcratingreview_avg', $score_new ); //todo: need ceil ?
					update_post_meta( $post_id, '_cbxmcratingreview_total', $total_count_new );
				}
			}//end avg calculation



		}//end method calculatePostAvg

		/**
		 * Readjust average after delete of any review or status change to any other state than published(1)
		 *
		 * @param array $review_info
		 *
		 * @return false|int|null
		 */
		public static function adjustPostwAvg( $review_info = array() ) {
			//we need to calculate avg of avg and same time avg for each single criteria id/criteria

			global $wpdb;
			$table_rating_avg          = $wpdb->prefix . 'cbxmcratingreview_log_avg';
			$cbxmcratingreview_setting = new CBXMCRatingReviewSettings();


			$post_id = intval( $review_info['post_id'] );
			$form_id = intval( $review_info['form_id'] );

			$post_avg_rating = cbxmcratingreview_postAvgRatingInfo( $form_id, $post_id );

			$avg_rating        = $post_avg_rating['avg_rating'];
			$total_count       = $post_avg_rating['total_count'];
			$total_count_new   = $total_count - 1;
			$total_score       = $avg_rating * $total_count;
			$score             = $review_info['score'];
			$ceil_rating_score = ceil( $score );

			// avg adjust
			$process_status = null;

			if ( $total_count_new != 0 ) {
				$total_score_new = $total_score - $score;
				$score_new       = number_format( ( $total_score_new / $total_count_new ), 2 );

				$rating_stat = maybe_unserialize( $post_avg_rating['rating_stat'] );

				//rating score percentage calculation
				$rating_stat_scores = isset( $rating_stat['rating_stat_scores'] ) ? $rating_stat['rating_stat_scores'] : array();

				if ( isset( $rating_stat_scores[ $ceil_rating_score ] ) ) {
					$new_count                                         = intval( $rating_stat_scores[ $ceil_rating_score ]['count'] ) - 1;
					$rating_stat_scores[ $ceil_rating_score ]['count'] = $new_count;
				}

				//calculate percentage once again
				foreach ( $rating_stat_scores as $score_loop => $count_percent ) {
					$rating_stat_scores[ $score_loop ]['percent'] = number_format( ( intval( $rating_stat_scores[ $score_loop ]['count'] ) / $total_count_new ) * 100, 2 );
				}

				$rating_stat['rating_stat_scores'] = $rating_stat_scores;
				//end rating score percentage calculation

				//calculate criteria based rating stat
				$ratings       = isset( $review_info['ratings'] ) ? maybe_unserialize( $review_info['ratings'] ) : array();
				$ratings_stars = isset( $ratings['ratings_stars'] ) ? maybe_unserialize( $ratings['ratings_stars'] ) : array();

				$criteria_infos        = isset( $rating_stat['criteria_info'] ) ? $rating_stat['criteria_info'] : array();
				$criteria_stat_scores = isset( $rating_stat['criteria_stat_scores'] ) ? $rating_stat['criteria_stat_scores'] : array();

				$criteria_infosT 		= array();
				$criteria_stat_scoresT 	= array();

				if ( is_array( $ratings_stars ) && sizeof( $ratings_stars ) > 0 ) {
					foreach ( $ratings_stars as $criteria_id => $ratings_star ) {

						$rating_score  = isset( $ratings_star['score_standard'] ) ? $ratings_star['score_standard'] : 0; //score in 5
						$rating_score_ceil = ceil( $rating_score );

						//if(isset( $criteria_infos[ $criteria_id ] )){
							//criteria previous existed
							$criteria_info = isset($criteria_infos[ $criteria_id ])? $criteria_infos[ $criteria_id ]: array();

							$criteria_avg_rating  = isset( $criteria_info['avg_rating'] ) ? $criteria_info['avg_rating'] : 0;
							$criteria_total_count = isset( $criteria_info['total_count'] ) ? $criteria_info['total_count'] : 0;

							$criteria_total_score    = $criteria_avg_rating * $criteria_total_count;
							$criteria_total_count_new = $criteria_total_count-1;

							if($criteria_total_count_new != 0){
								$criteria_total_score_new = $criteria_total_score - $rating_score;
								$criteria_avg_rating_new = number_format( ( $criteria_total_score_new / $criteria_total_count_new ), 2 );

								$criteria_infosT[ $criteria_id ] = array(
									'avg_rating'  => $criteria_avg_rating_new,
									'total_count' => $criteria_total_count_new
								);

								$criteria_stat_score      = isset( $criteria_stat_scores[ $criteria_id ] ) ? $criteria_stat_scores[ $criteria_id ] : array();
								if ( isset( $criteria_stat_score[ $rating_score_ceil ] ) ) {
									$new_count                                          = intval( $criteria_stat_score[ $rating_score_ceil ]['count'] ) - 1;
									$criteria_stat_score[ $rating_score_ceil ]['count'] = $new_count;
								}

								//calculate percentage once again
								foreach ( $criteria_stat_score as $score_loop => $count_percent ) {
									$criteria_stat_score[ $score_loop ]['percent'] = number_format( ( intval( $criteria_stat_score[ $score_loop ]['count'] ) / $criteria_total_count_new ) * 100, 2 );
								}

								$criteria_stat_scoresT[ $criteria_id ] = $criteria_stat_score;
							}
						//}
						/*else{
							//criteria first time rated while in edit mode
							$criteria_infosT[ $criteria_id ] = array(
								'avg_rating'  => $rating_score,
								'total_count' => 1
							);

							$criteria_stat_scoresT[ $criteria_id ][$rating_score_ceil] = array(
								'count'   => 1,
								'percent' => 100,
							);

						}	*/

					}

					$rating_stat['criteria_info']        = $criteria_infosT;
					$rating_stat['criteria_stat_scores'] = $criteria_stat_scoresT;
				}//end calculate criteria based rating stat


				$process_status = $wpdb->update(
					$table_rating_avg,
					array(
						'avg_rating'    => $score_new,
						'total_count'   => $total_count_new,
						'date_modified' => current_time( 'mysql' ),
						'rating_stat'   => maybe_serialize( $rating_stat )
					),
					array( 'id' => $post_avg_rating['id'] ),
					array(
						'%f', //avg_rating
						'%d', //total_count
						'%s', //date_modified
						'%s', //rating_stat
					),
					array( '%d' )
				);

				update_post_meta( $post_id, '_cbxmcratingreview_avg', $score_new ); //todo: should we ceil ?
				update_post_meta( $post_id, '_cbxmcratingreview_total', $total_count_new );
			} else {
				// as no entry for this post so delete the entry from avg table
				$process_status = $wpdb->query( $wpdb->prepare( "DELETE FROM $table_rating_avg WHERE id=%d", intval( $post_avg_rating['id'] ) ) );

				//update_post_meta( $post_id, '_cbxmcratingreview_avg', 0 );
				//update_post_meta( $post_id, '_cbxmcratingreview_total', 0 );

				delete_post_meta( $post_id, '_cbxmcratingreview_avg' );
				delete_post_meta( $post_id, '_cbxmcratingreview_total' );
			}


			return $process_status;
		}//end method adjustPostwAvg

		/**
		 * If review rating changed in published status
		 *
		 * @param array $review_info
		 *
		 * @return false|int|null
		 */
		public static function editPostwAvg( $new_status, $review_info, $review_info_old ) {
			//if not publish status we can ignore
			if ( intval( $new_status ) != 1 ) {
				return;
			}

			$score     = $review_info['score'];
			$score_old = $review_info_old['score'];

			//if new score is same we can ignore
			//if ( $score == $score_old ) {return;} //todo: need to rethink


			global $wpdb;
			$table_rating_avg          = $wpdb->prefix . 'cbxmcratingreview_log_avg';
			$cbxmcratingreview_setting = new CBXMCRatingReviewSettings();


			$post_id = intval( $review_info['post_id'] );
			$form_id = intval( $review_info['form_id'] );

			$post_avg_rating = cbxmcratingreview_postAvgRatingInfo( $form_id, $post_id );
			$rating_stat     = maybe_unserialize( $post_avg_rating['rating_stat'] );

			$avg_rating  = $post_avg_rating['avg_rating'];
			$total_count = $post_avg_rating['total_count'];
			$total_score = $avg_rating * $total_count;


			$ceil_rating_score     = ceil( $score );
			$ceil_rating_score_old = ceil( $score );

			// avg adjust
			$process_status = null;


			if ( $score != $score_old ) {
				$total_score_new = $total_score + $score - $score_old;
				$score_new       = number_format( ( $total_score_new / $total_count ), 2 );

				//rating score percentage calculation
				$rating_stat_scores = $rating_stat['rating_stat_scores'];

				//at first reduce old score count
				if ( isset( $rating_stat_scores[ $ceil_rating_score_old ] ) ) {
					$new_count                                             = intval( $rating_stat_scores[ $ceil_rating_score_old ]['count'] ) - 1;
					$rating_stat_scores[ $ceil_rating_score_old ]['count'] = $new_count;
				}

				//now add new score value
				if ( isset( $rating_stat_scores[ $ceil_rating_score ] ) ) {
					$new_count                                         = intval( $rating_stat_scores[ $ceil_rating_score ]['count'] ) + 1;
					$rating_stat_scores[ $ceil_rating_score ]['count'] = $new_count;
				}

				//calculate percentage once again
				foreach ( $rating_stat_scores as $score_loop => $count_percent ) {
					$rating_stat_scores[ $score_loop ]['percent'] = number_format( ( intval( $rating_stat_scores[ $score_loop ]['count'] ) / $total_count ) * 100, 2 );
				}

				$rating_stat['rating_stat_scores'] = $rating_stat_scores;
				//end rating score percentage calculation
			} else {
				$score_new = $score;
			}

			//calculate criteria based rating stat
			//here  we will take the ratings from the new rating value
			$ratings       = isset( $review_info['ratings'] ) ? maybe_unserialize( $review_info['ratings'] ) : array();
			$ratings_stars = isset( $ratings['ratings_stars'] ) ? maybe_unserialize( $ratings['ratings_stars'] ) : array();


			$ratings_old       = isset( $review_info_old['ratings'] ) ? maybe_unserialize( $review_info_old['ratings'] ) : array();
			$ratings_stars_old = isset( $ratings_old['ratings_stars'] ) ? maybe_unserialize( $ratings_old['ratings_stars'] ) : array();

			$criteria_infos       = isset( $rating_stat['criteria_info'] ) ? $rating_stat['criteria_info'] : array();
			$criteria_stat_scores = isset( $rating_stat['criteria_stat_scores'] ) ? $rating_stat['criteria_stat_scores'] : array();

			$criteria_infosT = array();
			$criteria_stat_scoresT = array();


			if ( is_array( $ratings_stars ) && sizeof( $ratings_stars ) > 0 ) {
				foreach ( $ratings_stars as $criteria_id => $ratings_star ) {

					$ratings_star_old = isset( $ratings_stars_old[ $criteria_id ] ) ? $ratings_stars_old[ $criteria_id ] : array();

					$rating_score_avg     = isset( $ratings_star['score_standard'] ) ? $ratings_star['score_standard'] : 0; //score in 5
					$rating_score_avg_old = isset( $ratings_star_old['score_standard'] ) ? $ratings_star_old['score_standard'] : 0; //score in 5

					$rating_score_avg_ceil 		= ceil( $rating_score_avg );
					$rating_score_avg_old_ceil 	= ceil( $rating_score_avg_old );


					//for this specific criteria  user's rating is changed, so we need to take care
					if ( $rating_score_avg != $rating_score_avg_old ) {

						if ( isset( $criteria_infos[ $criteria_id ] ) ) {
							//criteria previously existed

							$criteria_info = $criteria_infos[ $criteria_id ];

							$criteria_avg_rating  = $criteria_info['avg_rating'];
							$criteria_total_count = $criteria_info['total_count'];

							$criteria_total_score         = $criteria_avg_rating * $criteria_total_count;
							$criteria_total_score_new     = $criteria_total_score + $rating_score_avg - $rating_score_avg_old;
							$criteria_avg_rating_new      = number_format( ( $criteria_total_score_new / $criteria_total_count ), 2 );
							//$criteria_avg_rating_new_ceil = ceil( $criteria_avg_rating_new );


							$criteria_infosT[ $criteria_id ] = array(
								'avg_rating'  => $criteria_avg_rating_new,
								'total_count' => $criteria_total_count
							);

							//re-calculate the percentage for star
							$criteria_stat_score      = isset( $criteria_stat_scores[ $criteria_id ] ) ? $criteria_stat_scores[ $criteria_id ] : array();

							//first minus 1 from previous ceil count and add 1 for new ceil count, then converted to percentage
							if ( isset( $criteria_stat_score[ $rating_score_avg_old_ceil ] ) ) {
								$new_count                                          = intval( $criteria_stat_score[ $rating_score_avg_old_ceil ]['count'] ) - 1;
								$criteria_stat_score[ $rating_score_avg_old_ceil ]['count'] = $new_count;
							}

							if ( isset( $criteria_stat_score[ $rating_score_avg_ceil ] ) ) {
								$new_count                                          = intval( $criteria_stat_score[ $rating_score_avg_ceil ]['count'] ) + 1;
								$criteria_stat_score[ $rating_score_avg_ceil ]['count'] = $new_count;
							}
							else{
								$criteria_stat_score[ $rating_score_avg_ceil ]['count'] = 1;
							}



							//calculate percentage once again
							foreach ( $criteria_stat_score as $score_loop => $count_percent ) {
								$criteria_stat_score[ $score_loop ]['percent'] = number_format( ( intval( $criteria_stat_score[ $score_loop ]['count'] ) / $criteria_total_count ) * 100, 2 );
							}

							$criteria_stat_scoresT[ $criteria_id ] = $criteria_stat_score;

						} else {
							//this criteria is first time rated



							$criteria_infosT[ $criteria_id ] = array(
								'avg_rating'  => $rating_score_avg,
								'total_count' => 1
							);


							$criteria_stat_scoresT[ $criteria_id ][$rating_score_avg_ceil] = array(
								'count'   => 1,
								'percent' => 100,
							);

						}

					}

				}

				$rating_stat['criteria_info']        = $criteria_infosT;
				$rating_stat['criteria_stat_scores'] = $criteria_stat_scoresT;
			}

			//end calculate criteria based rating stat


			$score = $score_new;

			$process_status = $wpdb->update(
				$table_rating_avg,
				array(
					'avg_rating'    => $score,
					'date_modified' => current_time( 'mysql' ),
					'rating_stat'   => maybe_serialize( $rating_stat )
				),
				array( 'id' => $post_avg_rating['id'] ),
				array(
					'%f', //avg_rating
					'%s', //date_modified
					'%s', //rating_stat
				),
				array( '%d' )
			);

			update_post_meta( $post_id, '_cbxmcratingreview_avg', $score_new ); //todo: should we use ceil ?

			return $process_status;
		}//end method editPostwAvg


		/**
		 * @param $timestamp
		 *
		 * @return false|string
		 */
		public static function dateReadableFormat( $timestamp, $format = 'M j, Y' ) {
			$format = ( $format == '' ) ? 'M j, Y' : $format;

			return date( $format, strtotime( $timestamp ) );
		}//end method dateReadableFormat


		/**
		 * HTML elements, attributes, and attribute values will occur in your output
		 * @return array
		 */
		public static function allowedHtmlTags() {
			$allowed_html_tags = array(
				'a'      => array(
					'href'  => array(),
					'title' => array(),
					//'class' => array(),
					//'data'  => array(),
					//'rel'   => array(),
				),
				'br'     => array(),
				'em'     => array(),
				'ul'     => array(//'class' => array(),
				),
				'ol'     => array(//'class' => array(),
				),
				'li'     => array(//'class' => array(),
				),
				'strong' => array(),
				'p'      => array(
					//'class' => array(),
					//'data'  => array(),
					//'style' => array(),
				),
				'span'   => array(
					//					'class' => array(),
					//'style' => array(),
				),
			);

			return apply_filters( 'cbxmcratingreview_allowed_html_tags', $allowed_html_tags );
		}//end method allowedHtmlTags


		/**
		 * Get most rated posts
		 *
		 * @param int    $form_id
		 * @param int    $limit
		 * @param string $orderby
		 * @param string $order
		 * @param string $type
		 *
		 * @return array|null|object
		 */
		public static function most_rated_posts( $form_id = 0, $perpage = 10, $orderby = 'avg_rating', $order = 'DESC', $type = 'post' ) {
			global $wpdb;
			$table_posts          = $wpdb->prefix . 'posts';
			$table_rating_avg_log = $wpdb->prefix . 'cbxmcratingreview_log_avg';

			$cbxmcratingreview_setting = new CBXMCRatingReviewSettings();

			if ( $form_id == 0 ) {
				$default_form = intval( $cbxmcratingreview_setting->get_option( 'default_form', 'cbxmcratingreview_common_config', 0 ) );
				if ( $default_form == 0 ) {
					return null;
				} else {
					$form_id = $default_form;
				}
			}

			$join = $where_sql = $sql_select = '';


			$where_sql .= $wpdb->prepare( ' avg_log.post_type=%s ', $type ); //" avg_log.post_type IN('".$post_types."')";


			if ( $where_sql != '' ) {
				$where_sql .= ' AND ';
			}
			$where_sql .= $wpdb->prepare( ' avg_log.form_id = %d', $form_id );

			if ( $where_sql == '' ) {
				$where_sql = '1';
			}


			$sql_select = "SELECT avg_log.* FROM $table_rating_avg_log AS avg_log";

			$sorting_order = " ORDER BY $orderby $order ";

			$page        = 1;
			$start_point = ( $page * $perpage ) - $perpage;
			$limit_sql   = "LIMIT";
			$limit_sql   .= ' ' . $start_point . ',';
			$limit_sql   .= ' ' . $perpage;

			$posts = $wpdb->get_results( "$sql_select $join WHERE $where_sql $sorting_order $limit_sql", 'ARRAY_A' );

			return $posts;
		}//end method most_rated_posts


		/**
		 * Latest ratings
		 *
		 * @param int    $form_id
		 * @param int    $perpage
		 * @param string $orderby
		 * @param string $order
		 * @param string $type
		 * @param int    $user_id
		 *
		 * @return array|null|object
		 */
		public static function lastest_ratings( $form_id = 0, $perpage = 10, $orderby = 'id', $order = 'DESC', $type = 'post', $user_id = 0 ) {
			global $wpdb;
			$table_rating_log = $wpdb->prefix . 'cbxmcratingreview_log';

			$cbxmcratingreview_setting = new CBXMCRatingReviewSettings();

			if ( $form_id == 0 ) {
				$default_form = intval( $cbxmcratingreview_setting->get_option( 'default_form', 'cbxmcratingreview_common_config', 0 ) );
				if ( $default_form == 0 ) {
					return null;
				} else {
					$form_id = $default_form;
				}
			}

			$join = $where_sql = $sql_select = '';

			//$join = " LEFT JOIN $table_posts AS posts ON posts.ID = log.post_id ";
			//$join .= " LEFT JOIN $table_users AS users ON users.ID = log.user_id ";

			if ( $user_id !== 0 ) {
				$where_sql .= ( ( $where_sql != '' ) ? ' AND ' : '' ) . $wpdb->prepare( 'user_id=%d', $user_id );
			}

			if ( $where_sql != '' ) {
				$where_sql .= ' AND ';
			}
			$where_sql .= $wpdb->prepare( ' log.form_id = %d', $form_id );

			if ( $where_sql == '' ) {
				$where_sql = '1';
			}

			$sql_select = "SELECT log.* FROM $table_rating_log AS log";

			$orderby       = 'id';
			$order         = 'DESC';
			$sorting_order = " ORDER BY $orderby $order ";

			$page = 1;

			$start_point = ( $page * $perpage ) - $perpage;
			$limit_sql   = "LIMIT";
			$limit_sql   .= ' ' . $start_point . ',';
			$limit_sql   .= ' ' . $perpage;

			$latest_ratings = $wpdb->get_results( "$sql_select $join WHERE $where_sql $sorting_order $limit_sql", 'ARRAY_A' );

			return $latest_ratings;
		}//end method lastest_ratings

		/**
		 * latest ratings of author posts
		 * @return array|null|object
		 */
		/*public static function authorpostlatestRatings( $perpage = 10, $user_id = 0 ) {
			global $wpdb;
			$table_users      = $wpdb->prefix . 'users';
			$table_posts      = $wpdb->prefix . 'posts';
			$table_rating_log = $wpdb->prefix . 'cbxmcratingreview_log';

			$join = $where_sql = $sql_select = '';

			$join = " LEFT JOIN $table_posts AS posts ON posts.ID = log.post_id ";
			$join .= " LEFT JOIN $table_users AS users ON users.ID = log.user_id ";

			if ( $user_id !== 0 ) {
				$where_sql .= ( ( $where_sql != '' ) ? ' AND ' : '' ) . $wpdb->prepare( 'user_id=%d AND posts.post_author=%d', $user_id, $user_id );
			}

			if ( $where_sql == '' ) {
				$where_sql = '1';
			}

			$sql_select = "SELECT log.*, posts.post_title, users.display_name FROM $table_rating_log AS log";

			$orderby       = 'id';
			$order         = 'DESC';
			$sorting_order = " ORDER BY $orderby $order ";

			$page        = 1;
			$start_point = ( $page * $perpage ) - $perpage;
			$limit_sql   = "LIMIT";
			$limit_sql   .= ' ' . $start_point . ',';
			$limit_sql   .= ' ' . $perpage;

			$author_post_latest_ratings = $wpdb->get_results( "$sql_select $join WHERE $where_sql $sorting_order $limit_sql", 'ARRAY_A' );

			return $author_post_latest_ratings;
		}*/


		/**
		 * Render single post avg rating for a form
		 *
		 * @param int  $form_id
		 * @param int  $post_id
		 * @param bool $show_star
		 * @param bool $show_score
		 * @param bool $show_chart
		 *
		 * @return false|string
		 */
		public static function postAvgRatingRender( $form_id = 0, $post_id = 0, $show_star = true, $show_score = true, $show_chart = false ) {


			global $current_user;
			$ok_to_render = false;


			$avg_rating_html = '';

			$cbxmcratingreview_setting = new CBXMCRatingReviewSettings();

			if ( $form_id == 0 ) {
				$default_form = intval( $cbxmcratingreview_setting->get_option( 'default_form', 'cbxmcratingreview_common_config', 0 ) );
				if ( $default_form == 0 ) {
					return $avg_rating_html;
				} else {
					$form_id = $default_form;
				}
			}

			$form = CBXMCRatingReviewHelper::getRatingForm( $form_id );


			$post_id   = intval( $post_id );
			$post_id   = ( $post_id == 0 ) ? intval( get_the_ID() ) : $post_id;
			$post_type = get_post_type( $post_id );
			if ( is_user_logged_in() ) {
				$user_id = get_current_user_id();;
			} else {
				$user_id = 0;
			}

			if ( $user_id == 0 ) {
				$userRoles = array( 'guest' );
			} else {
				$userRoles = $current_user->roles;
			}


			//check if post type supported
			//$post_types_supported = $cbxmcratingreview_setting->get_option( 'post_types', 'cbxmcratingreview_common_config', array() );

			$post_types_supported = isset( $form['post_types'] ) ? $form['post_types'] : array();




			//check if post type is supported
			if ( $post_id > 0 && is_array( $post_types_supported ) && sizeof( $post_types_supported ) > 0 && in_array( $post_type, $post_types_supported ) ) {
				$ok_to_render = true;
			}
			//end post type support check





			//check if user role supported
			if ( $ok_to_render ) {
				//$user_roles_rate = $cbxmcratingreview_setting->get_option( 'user_roles_view', 'cbxmcratingreview_common_config', array() );
				$user_roles_rate = isset( $form['user_roles_view'] ) ? $form['user_roles_view'] : array();

				if ( ! is_array( $user_roles_rate ) ) {
					$user_roles_rate = array();
				}

				$intersectedRoles = array_intersect( $user_roles_rate, $userRoles );
				if ( sizeof( $intersectedRoles ) == 0 ) {
					$ok_to_render = false;
				}
			}
			//end user role checking


			if ( $ok_to_render ) {
				cbxmcratingreview_AddJsCss();


				$avg_rating_info = cbxmcratingreview_postAvgRatingInfo( $form_id, $post_id );

				ob_start();
				include (cbxmcratingreview_locate_template('rating-review-avg-rating.php'));
				$avg_rating_html = ob_get_contents();
				ob_end_clean();

				return $avg_rating_html;
			}

			return $avg_rating_html;
		}//end method postAvgRatingRender


		/**
		 * Render single post details avg rating for a form
		 *
		 * @param int  $form_id
		 * @param int  $post_id
		 * @param bool $show_star
		 * @param bool $show_score
		 * @param bool $show_chart
		 *
		 * @return false|string
		 */
		public static function postAvgDetailsRatingRender( $form_id = 0, $post_id = 0, $show_star = true, $show_score = true, $show_short = true, $show_chart = true ) {


			global $current_user;
			$ok_to_render = false;


			$avg_rating_html = '';

			$cbxmcratingreview_setting = new CBXMCRatingReviewSettings();

			if ( $form_id == 0 ) {
				$default_form = intval( $cbxmcratingreview_setting->get_option( 'default_form', 'cbxmcratingreview_common_config', 0 ) );
				if ( $default_form == 0 ) {
					return $avg_rating_html;
				} else {
					$form_id = $default_form;
				}
			}

			$form = CBXMCRatingReviewHelper::getRatingForm( $form_id );


			$post_id   = intval( $post_id );
			$post_id   = ( $post_id == 0 ) ? intval( get_the_ID() ) : $post_id;
			$post_type = get_post_type( $post_id );
			if ( is_user_logged_in() ) {
				$user_id = get_current_user_id();;
			} else {
				$user_id = 0;
			}

			if ( $user_id == 0 ) {
				$userRoles = array( 'guest' );
			} else {
				$userRoles = $current_user->roles;
			}


			//check if post type supported
			//$post_types_supported = $cbxmcratingreview_setting->get_option( 'post_types', 'cbxmcratingreview_common_config', array() );

			$post_types_supported = isset( $form['post_types'] ) ? $form['post_types'] : array();




			//check if post type is supported
			if ( $post_id > 0 && is_array( $post_types_supported ) && sizeof( $post_types_supported ) > 0 && in_array( $post_type, $post_types_supported ) ) {
				$ok_to_render = true;
			}
			//end post type support check





			//check if user role supported
			if ( $ok_to_render ) {
				//$user_roles_rate = $cbxmcratingreview_setting->get_option( 'user_roles_view', 'cbxmcratingreview_common_config', array() );
				$user_roles_rate = isset( $form['user_roles_view'] ) ? $form['user_roles_view'] : array();

				if ( ! is_array( $user_roles_rate ) ) {
					$user_roles_rate = array();
				}

				$intersectedRoles = array_intersect( $user_roles_rate, $userRoles );
				if ( sizeof( $intersectedRoles ) == 0 ) {
					$ok_to_render = false;
				}
			}
			//end user role checking


			if ( $ok_to_render ) {
				cbxmcratingreview_AddJsCss();


				$avg_rating_info = cbxmcratingreview_postAvgRatingInfo( $form_id, $post_id );

				ob_start();

				include (cbxmcratingreview_locate_template('rating-review-details-avg-rating.php'));

				$avg_rating_html = ob_get_contents();
				ob_end_clean();

				return $avg_rating_html;
			}

			return $avg_rating_html;
		}//end method postAvgDetailsRatingRender


		/**
		 * Render rating form
		 *
		 * @param int $form_id
		 * @param int $post_id
		 *
		 * @return string
		 */
		public static function reviewformRender( $form_id = 0, $post_id = 0 ) {
			global $current_user;
			$ok_to_render = false;

			$rating_form_html = '';

			$post_id   = intval( $post_id );
			$post_id   = ( $post_id == 0 ) ? intval( get_the_ID() ) : $post_id;
			$post_type = get_post_type( $post_id );
			if ( is_user_logged_in() ) {
				$user_id = get_current_user_id();;
			} else {
				$user_id = 0;
			}

			if ( $user_id == 0 ) {
				$userRoles = array( 'guest' );
			} else {
				$userRoles = $current_user->roles;
			}

			$cbxmcratingreview_setting = new CBXMCRatingReviewSettings();
			if ( $form_id == 0 ) {
				$default_form = intval( $cbxmcratingreview_setting->get_option( 'default_form', 'cbxmcratingreview_common_config', 0 ) );
				if ( $default_form == 0 ) {
					return $rating_form_html;
				} else {
					$form_id = $default_form;
				}
			}


			$form = CBXMCRatingReviewHelper::getRatingForm( $form_id );


			//check if post type supported
			//$post_types_supported = $cbxmcratingreview_setting->get_option( 'post_types', 'cbxmcratingreview_common_config', array() );
			$post_types_supported = isset( $form['post_types'] ) ? $form['post_types'] : array();


			if ( $post_id > 0 && is_array( $post_types_supported ) && sizeof( $post_types_supported ) > 0 && in_array( $post_type, $post_types_supported ) ) {
				$ok_to_render = true;
			}
			//end checking post types

			//check if user role supported
			if ( $ok_to_render ) {
				//$user_roles_rate = $cbxmcratingreview_setting->get_option( 'user_roles_rate', 'cbxmcratingreview_common_config', array() );
				$user_roles_rate = isset( $form['user_roles_rate'] ) ? $form['user_roles_rate'] : array();

				if ( ! is_array( $user_roles_rate ) ) {
					$user_roles_rate = array();
				}


				$intersectedRoles = array_intersect( $user_roles_rate, $userRoles );
				if ( sizeof( $intersectedRoles ) == 0 ) {
					$ok_to_render = false;
				}
			}
			//end checking user role support


			//now check if the user rated before
			if ( $ok_to_render == true ) {
				$user_rated_before = cbxmcratingreview_isPostRatedByUser( $form_id, $post_id, $user_id );
				if ( $user_rated_before ) {
					$ok_to_render = false;
				}

				//still put option if we want to allow repeat review
				$ok_to_render = apply_filters( 'cbxmcratingreview_allow_repeat_review', $ok_to_render, $user_rated_before, $user_id, $post_id );
			}


			if ( apply_filters('cbxmcratingreview_render', $ok_to_render, $post_id, $post_type) ) {
				cbxmcratingreview_AddJsCss();
				cbxmcratingreview_AddRatingFormJsCss();


				ob_start();

				//include( apply_filters( 'cbxmcratingreview_tpl_rating-review-form', CBXMCRATINGREVIEW_ROOT_PATH . 'templates/rating-review-form.php' ) );
				include (cbxmcratingreview_locate_template('rating-review-form.php'));

				$rating_form_html = ob_get_contents();
				ob_end_clean();

				return $rating_form_html;
			}

			return $rating_form_html;
		}//end method reviewformRender


		/**
		 * Char Length check  thinking utf8 in mind
		 *
		 * @param $text
		 *
		 * @return int
		 */
		public static function utf8_compatible_length_check( $text ) {
			if ( seems_utf8( $text ) ) {
				$length = mb_strlen( $text );
			} else {
				$length = strlen( $text );
			}

			return $length;
		}//end method utf8_compatible_length_check

		/**
		 * Setup a post object and store the original loop item so we can reset it later
		 *
		 * @param obj $post_to_setup The post that we want to use from our custom loop
		 */
		public static function setup_admin_postdata( $post_to_setup ) {

			//only on the admin side
			if ( is_admin() ) {

				//get the post for both setup_postdata() and to be cached
				global $post;

				//only cache $post the first time through the loop
				if ( ! isset( $GLOBALS['post_cache'] ) ) {
					$GLOBALS['post_cache'] = $post;
				}

				//setup the post data as usual
				$post = $post_to_setup;
				setup_postdata( $post );
			} else {
				setup_postdata( $post_to_setup );
			}
		}//end method setup_admin_postdata


		/**
		 * Reset $post back to the original item
		 *
		 */
		public static function wp_reset_admin_postdata() {

			//only on the admin and if post_cache is set
			if ( is_admin() && ! empty( $GLOBALS['post_cache'] ) ) {

				//globalize post as usual
				global $post;

				//set $post back to the cached version and set it up
				$post = $GLOBALS['post_cache'];
				setup_postdata( $post );

				//cleanup
				unset( $GLOBALS['post_cache'] );
			} else {
				wp_reset_postdata();
			}
		}//end method wp_reset_admin_postdata

		/**
		 * Show action links on the plugin screen.
		 *
		 * @param   mixed $links Plugin Action links.
		 *
		 * @return  array
		 */
		public static function plugin_action_links( $links ) {
			$action_links = array(
				'settings' => '<a href="' . admin_url( 'admin.php?page=cbxmcratingreviewsettings' ) . '" aria-label="' . esc_attr__( 'View settings', 'cbxmcratingreview' ) . '">' . esc_html__( 'Settings', 'cbxmcratingreview' ) . '</a>',
			);

			return array_merge( $action_links, $links );
		}//end method plugin_action_links

		/**
		 * Get all the pages
		 *
		 * @return array page names with key value pairs
		 */
		public static function get_pages() {
			$pages         = get_pages();
			$pages_options = array();
			if ( $pages ) {
				foreach ( $pages as $page ) {
					$pages_options[ $page->ID ] = $page->post_title;
				}
			}

			return $pages_options;
		}//end method get_pages

		public static function reviewToolbarRender( $post_review ) {
			$rating_review_toolbar_html = '';

			ob_start();
			//include( apply_filters( 'cbxmcratingreview_tpl_rating-review-reviews-list-item-toolbar', CBXMCRATINGREVIEW_ROOT_PATH . 'templates/rating-review-reviews-list-item-toolbar.php'
			include (cbxmcratingreview_locate_template('rating-review-reviews-list-item-toolbar.php'));

			$rating_review_toolbar_html = ob_get_contents();
			ob_end_clean();

			return $rating_review_toolbar_html;
		}

		/**
		 * render single review delete button
		 *
		 * @param array $post_review
		 *
		 * @return string
		 */
		public static function reviewDeleteButtonRender( $post_review = array() ) {
			cbxmcratingreview_AddJsCss();

			$report_form_html = '';
			if ( is_array( $post_review ) && sizeof( $post_review ) > 0 ) {
				ob_start();

				//include( apply_filters( 'cbxmcratingreview_tpl_rating-review-review-delete-button', CBXMCRATINGREVIEW_ROOT_PATH . 'templates/rating-review-review-delete-button.php' ) );
				include (cbxmcratingreview_locate_template('rating-review-review-delete-button.php'));

				$report_form_html = ob_get_contents();
				ob_end_clean();
			}

			return $report_form_html;
		}//end method reviewReportButtonRender

		/**
		 * Cookie initialization for the every user
		 */
		/*public static function init_cookie() {
			//global $current_user;

			if ( is_user_logged_in() ) {
				$cookie_value = 'user-' . get_current_user_id();
			} else {
				$cookie_value = 'guest-' . rand( CBXMCRATINGREVIEW_RAND_MIN, CBXMCRATINGREVIEW_RAND_MAX );
			}

			if ( ! isset( $_COOKIE[ CBXMCRATINGREVIEW_COOKIE_NAME ] ) && empty( $_COOKIE[ CBXMCRATINGREVIEW_COOKIE_NAME ] ) ) {
				setcookie( CBXMCRATINGREVIEW_COOKIE_NAME, $cookie_value, CBXMCRATINGREVIEW_COOKIE_EXPIRATION_14DAYS, SITECOOKIEPATH, COOKIE_DOMAIN );

				//$_COOKIE var accepts immediately the value so it will be retrieved on page first load.
				$_COOKIE[ CBXMCRATINGREVIEW_COOKIE_NAME ] = $cookie_value;

			} elseif ( isset( $_COOKIE[ CBXMCRATINGREVIEW_COOKIE_NAME ] ) ) {
				if ( substr( $_COOKIE[ CBXMCRATINGREVIEW_COOKIE_NAME ], 0, 5 ) != 'guest' ) {
					setcookie( CBXMCRATINGREVIEW_COOKIE_NAME, $cookie_value, CBXMCRATINGREVIEW_COOKIE_EXPIRATION_14DAYS, SITECOOKIEPATH, COOKIE_DOMAIN );

					//$_COOKIE var accepts immediately the value so it will be retrieved on page first load.
					$_COOKIE[ CBXMCRATINGREVIEW_COOKIE_NAME ] = $cookie_value;
				}
			}
		}*///end method init_cookie

		/**
		 *  Default criteria and stars
		 *
		 * @return mixed|void
		 */
		public static function form_default_criterias() {
			$form_criteria = array(
				'0' => array
				(
					'label'           => esc_html__( 'Untitled criteria - 0', 'cbxmcratingreview' ),
					'criteria_id'     => 0,
					'stars'           => array
					(
						'0' => array(
							'title'   => esc_html__( 'Worst', 'cbxmcratingreview' )
						),
						'1' => array(
							'title'   => esc_html__( 'Bad', 'cbxmcratingreview' )
						),
						'2' => array(
							'title'   => esc_html__( 'Not Bad', 'cbxmcratingreview' )
						),
						'3' => array(
							'title'   => esc_html__( 'Good', 'cbxmcratingreview' )
						),
						'4' => array(
							'title'   => esc_html__( 'Best', 'cbxmcratingreview' )
						)
					)
				),
				'1' => array
				(
					'label'           => esc_html__( 'Untitled criteria - 1', 'cbxmcratingreview' ),
					'criteria_id'     => 1,
					'stars'           => array
					(
						'0' => array(
							'title'   => esc_html__( 'Worst', 'cbxmcratingreview' )
						),
						'1' => array(
							'title'   => esc_html__( 'Bad', 'cbxmcratingreview' )
						),
						'2' => array(
							'title'   => esc_html__( 'Not Bad', 'cbxmcratingreview' )
						),
						'3' => array(
							'title'   => esc_html__( 'Good', 'cbxmcratingreview' )
						),
						'4' => array(
							'title'   => esc_html__( 'Best', 'cbxmcratingreview' )
						)
					)
				),
				'2' => array
				(
					'label'           => esc_html__( 'Untitled criteria - 2', 'cbxmcratingreview' ),
					'criteria_id'     => 2,
					'stars'           => array
					(
						'0' => array(
							'title'   => esc_html__( 'Worst', 'cbxmcratingreview' )
						),
						'1' => array(
							'title'   => esc_html__( 'Bad', 'cbxmcratingreview' )
						),
						'2' => array(
							'title'   => esc_html__( 'Not Bad', 'cbxmcratingreview' )
						),
						'3' => array(
							'title'   => esc_html__( 'Good', 'cbxmcratingreview' )
						),
						'4' => array(
							'title'   => esc_html__( 'Best', 'cbxmcratingreview' )
						)
					)
				)
			);

			return apply_filters( 'cbxmcratingreview_form_default_criterias', $form_criteria );
		}//end method form_default_criterias

		/**
		 * Default questions
		 *
		 * @return mixed|void
		 */
		public static function form_default_questions() {
			$form_question = array(
				'0' => array(
					'title'       => esc_html__( 'Sample Question Title', 'cbxmcratingreview' ),
					'required'    => 0,
					'enabled'     => 0,
					'placeholder' => esc_html__( 'Write here', 'cbxmcratingreview' ),
					'type'        => 'text'
				),
				/*'1' => array(
					'title'    => esc_html__( 'Sample Question Title 1', 'cbxmcratingreview' ),
					'required' => 0,
					'enabled'  => 0,
					'placeholder'  => esc_html__('Write here', 'cbxmcratingreview'),
					'type'     => 'textarea'
				),
				'2' => array(
					'title'    => esc_html__( 'Sample Question Title 2', 'cbxmcratingreview' ),
					'required' => 0,
					'enabled'  => 0,
					'type'     => 'checkbox'
				),
				'3' => array(
					'title'    => esc_html__( 'Sample Question Title 3', 'cbxmcratingreview' ),
					'required' => 0,
					'enabled'  => 0,
					'type'     => 'multicheckbox',
					'last_count' => 5,
					'options'    => array(
						'0'         => array( 'text' => esc_html__( 'Option 1', 'cbxmcratingreview' ) ),
						'1'         => array( 'text' => esc_html__( 'Option 2', 'cbxmcratingreview' ) ),
						'2'         => array( 'text' => esc_html__( 'Option 3', 'cbxmcratingreview' ) ),
						'3'         => array( 'text' => esc_html__( 'Option 4', 'cbxmcratingreview' ) ),
						'4'         => array( 'text' => esc_html__( 'Option 5', 'cbxmcratingreview' ) )
					)
				),
				'4' => array(
					'title'    => esc_html__( 'Sample Question Title 4', 'cbxmcratingreview' ),
					'required' => 0,
					'enabled'  => 0,
					'type'     => 'radio',
					'last_count' => 5,
					'options'    => array(
						'0'         => array( 'text' => esc_html__( 'Option 1', 'cbxmcratingreview' ) ),
						'1'         => array( 'text' => esc_html__( 'Option 2', 'cbxmcratingreview' ) ),
						'2'         => array( 'text' => esc_html__( 'Option 3', 'cbxmcratingreview' ) ),
						'3'         => array( 'text' => esc_html__( 'Option 4', 'cbxmcratingreview' ) ),
						'4'         => array( 'text' => esc_html__( 'Option 5', 'cbxmcratingreview' ) )
					)
				),
				'5' => array(
					'title'    => esc_html__( 'Sample Question Title 5', 'cbxmcratingreview' ),
					'required' => 0,
					'enabled'  => 0,
					'multiple'  => 0, //0/1 for multiple enable disable
					'type'     => 'select',
					'last_count' => 5,
					'options'    => array(
						'0'         => array( 'text' => esc_html__( 'Option 1', 'cbxmcratingreview' ) ),
						'1'         => array( 'text' => esc_html__( 'Option 2', 'cbxmcratingreview' ) ),
						'2'         => array( 'text' => esc_html__( 'Option 3', 'cbxmcratingreview' ) ),
						'3'         => array( 'text' => esc_html__( 'Option 4', 'cbxmcratingreview' ) ),
						'4'         => array( 'text' => esc_html__( 'Option 5', 'cbxmcratingreview' ) )
					)
				),
				'6' => array(
					'title'    => esc_html__( 'Sample Question Title 6', 'cbxmcratingreview' ),
					'required' => 0,
					'enabled'  => 0,
					'placeholder'  => esc_html__('Write here', 'cbxmcratingreview'),
					'min'  => 0,
					'max'  => 100,
					'step'  => 1,
					'type'     => 'number'
				),*/
			);

			return apply_filters( 'cbxmcratingreview_form_default_questions', $form_question );
		}//end method form_default_questions

		/**
		 * Question formats
		 *
		 * @return mixed|void
		 */
		public static function form_question_formats() {
			$form_question_formats = array(
				'text'          => array(
					'title'           => esc_html__( 'Sample Single line Question', 'cbxmcratingreview' ),
					'required'        => 0,
					'enabled'         => 1,
					'placeholder'     => esc_html__( 'Write here', 'cbxmcratingreview' ),
					'type'            => 'text',
					'admin_renderer'  => array( 'CBXMCRatingReviewQuestionHelper', 'admin_display_text_field' ),
					'public_renderer' => array( 'CBXMCRatingReviewQuestionHelper', 'public_display_text_field' ),
					'answer_renderer' => array( 'CBXMCRatingReviewQuestionHelper', 'answer_display_text_field' )
				),
				'textarea'      => array(
					'title'           => esc_html__( 'Sample Multiline Question', 'cbxmcratingreview' ),
					'required'        => 0,
					'enabled'         => 1,
					'placeholder'     => esc_html__( 'Write here', 'cbxmcratingreview' ),
					'type'            => 'textarea',
					'admin_renderer'  => array( 'CBXMCRatingReviewQuestionHelper', 'admin_display_textarea_field' ),
					'public_renderer' => array( 'CBXMCRatingReviewQuestionHelper', 'public_display_textarea_field' ),
					'answer_renderer' => array( 'CBXMCRatingReviewQuestionHelper', 'answer_display_textarea_field' )
				),
				'number'        => array(
					'title'           => esc_html__( 'Sample Number Field Question', 'cbxmcratingreview' ),
					'required'        => 0,
					'enabled'         => 1,
					'placeholder'     => esc_html__( 'Write here', 'cbxmcratingreview' ),
					'min'             => 0,
					'max'             => 100,
					'step'            => 1,
					'type'            => 'number',
					'admin_renderer'  => array( 'CBXMCRatingReviewQuestionHelper', 'admin_display_number_field' ),
					'public_renderer' => array( 'CBXMCRatingReviewQuestionHelper', 'public_display_number_field' ),
					'answer_renderer' => array( 'CBXMCRatingReviewQuestionHelper', 'answer_display_number_field' )
				),
				'checkbox'      => array(
					'title'           => esc_html__( 'Sample Checkbox Question', 'cbxmcratingreview' ),
					'required'        => 0,
					'enabled'         => 1,
					'type'            => 'checkbox',
					'admin_renderer'  => array( 'CBXMCRatingReviewQuestionHelper', 'admin_display_checkbox_field' ),
					'public_renderer' => array( 'CBXMCRatingReviewQuestionHelper', 'public_display_checkbox_field' ),
					'answer_renderer' => array( 'CBXMCRatingReviewQuestionHelper', 'answer_display_checkbox_field' )
				),
				'multicheckbox' => array(
					'title'           => esc_html__( 'Sample Multi Checkbox Question', 'cbxmcratingreview' ),
					'required'        => 0,
					'enabled'         => 1,
					'type'            => 'multicheckbox',
					'last_count'      => 5,
					'options'         => array(
						'0' => array( 'text' => esc_html__( 'Option 0', 'cbxmcratingreview' ) ),
						'1' => array( 'text' => esc_html__( 'Option 1', 'cbxmcratingreview' ) ),
						'2' => array( 'text' => esc_html__( 'Option 2', 'cbxmcratingreview' ) ),
						'3' => array( 'text' => esc_html__( 'Option 3', 'cbxmcratingreview' ) ),
						'4' => array( 'text' => esc_html__( 'Option 4', 'cbxmcratingreview' ) )
					),
					'admin_renderer'  => array(
						'CBXMCRatingReviewQuestionHelper',
						'admin_display_multicheckbox_field'
					),
					'public_renderer' => array(
						'CBXMCRatingReviewQuestionHelper',
						'public_display_multicheckbox_field'
					),
					'answer_renderer' => array(
						'CBXMCRatingReviewQuestionHelper',
						'answer_display_multicheckbox_field'
					)
				),
				'radio'         => array(
					'title'           => esc_html__( 'Sample Radio Question', 'cbxmcratingreview' ),
					'required'        => 0,
					'enabled'         => 1,
					'type'            => 'radio',
					'last_count'      => 5,
					'options'         => array(
						'0' => array( 'text' => esc_html__( 'Option 0', 'cbxmcratingreview' ) ),
						'1' => array( 'text' => esc_html__( 'Option 1', 'cbxmcratingreview' ) ),
						'2' => array( 'text' => esc_html__( 'Option 2', 'cbxmcratingreview' ) ),
						'3' => array( 'text' => esc_html__( 'Option 3', 'cbxmcratingreview' ) ),
						'4' => array( 'text' => esc_html__( 'Option 4', 'cbxmcratingreview' ) )
					),
					'admin_renderer'  => array( 'CBXMCRatingReviewQuestionHelper', 'admin_display_radio_field' ),
					'public_renderer' => array( 'CBXMCRatingReviewQuestionHelper', 'public_display_radio_field' ),
					'answer_renderer' => array( 'CBXMCRatingReviewQuestionHelper', 'answer_display_radio_field' )
				),
				'select'        => array(
					'title'           => esc_html__( 'Sample Select Question', 'cbxmcratingreview' ),
					'required'        => 0,
					'enabled'         => 1,
					'multiple'        => 0,
					//0/1 for multiple enable disable
					'type'            => 'select',
					'last_count'      => 5,
					'options'         => array(
						'0' => array( 'text' => esc_html__( 'Option 0', 'cbxmcratingreview' ) ),
						'1' => array( 'text' => esc_html__( 'Option 1', 'cbxmcratingreview' ) ),
						'2' => array( 'text' => esc_html__( 'Option 2', 'cbxmcratingreview' ) ),
						'3' => array( 'text' => esc_html__( 'Option 3', 'cbxmcratingreview' ) ),
						'4' => array( 'text' => esc_html__( 'Option 4', 'cbxmcratingreview' ) )
					),
					'admin_renderer'  => array( 'CBXMCRatingReviewQuestionHelper', 'admin_display_select_field' ),
					'public_renderer' => array( 'CBXMCRatingReviewQuestionHelper', 'public_display_select_field' ),
					'answer_renderer' => array( 'CBXMCRatingReviewQuestionHelper', 'answer_display_select_field' )
				)
			);

			return apply_filters( 'cbxmcratingreview_form_question_formats', $form_question_formats );
		}//end method form_question_format

		/**
		 * Rating form question field types
		 *
		 * @return mixed|void
		 */
		public static function question_field_types() {
			$fieldTypes = array(
				'text'          => esc_html__( 'Single Line Field', 'cbxmcratingreview' ),
				'textarea'      => esc_html__( 'Multi Line Field', 'cbxmcratingreview' ),
				'number'        => esc_html__( 'Number Field', 'cbxmcratingreview' ),
				'radio'         => esc_html__( 'Radio Field', 'cbxmcratingreview' ),
				'select'        => esc_html__( 'Dropdown Field', 'cbxmcratingreview' ),
				'checkbox'      => esc_html__( 'Checkbox Field', 'cbxmcratingreview' ),
				'multicheckbox' => esc_html__( 'Multi Checkbox Field', 'cbxmcratingreview' )
			);

			return apply_filters( 'cbxmcratingreview_question_field_types', $fieldTypes );
		}//end method form_field_types


		/**
		 * Rating form default fields
		 *
		 * @return array
		 */
		public static function form_default_fields() {
			$form_default = array(
				'id'   => array(
					'type'    => 'hidden',
					'default' => 0,

				),
				'name' => array(
					'label'       => esc_html__( 'Form Title', 'cbxmcratingreview' ),
					'desc'        => esc_html__( 'Write rating form name', 'cbxmcratingreview' ),
					'type'        => 'text',
					'default'     => esc_html__( 'Example Rating Form', 'cbxmcratingreview' ),
					'placeholder' => esc_html__( 'Rating Form Name', 'cbxmcratingreview' ),
					'required'    => true,
					'min'         => 5,
					'max'         => 500,
					'errormsg'    => esc_html__( 'Form title missing or empty, maximum length 500, minimum length 5', 'cbxmcratingreview' )
				),

				'status' => array(
					'label'    => esc_html__( 'Form Status', 'cbxmcratingreview' ),
					'desc'     => esc_html__( 'Enable disable the form', 'cbxmcratingreview' ),
					'type'     => 'radio',
					'default'  => 1,
					'required' => false,
					'options'  => array(
						'1' => esc_html__( 'Enabled', 'cbxmcratingreview' ),
						'0' => esc_html__( 'Disabled', 'cbxmcratingreview' )
					)

				) // create the form but will be active or inactive
			);

			$default_extra_fields = CBXMCRatingReviewHelper::form_default_extra_fields();
			$form_default         = array_merge( $form_default, $default_extra_fields );

			return $form_default;
		}//end method form_default_fields

		/**
		 * Core extra fields
		 *
		 * @return array|mixed|void
		 */
		public static function form_default_extra_fields() {
			//$postTypes = CBXMCRatingReviewHelper::post_types();
			//$userRoles = CBXMCRatingReviewHelper::user_roles( true, true );

			$post_types = CBXMCRatingReviewHelper::post_types( false );
			//$postTypes       = CBXMCRatingReviewHelper::post_types();
			$userRoles = CBXMCRatingReviewHelper::user_roles( false, true );

			$user_roles_no_guest   = CBXMCRatingReviewHelper::user_roles( false, false );
			$user_roles_with_guest = CBXMCRatingReviewHelper::user_roles( false, true );

			//$editorUserRoles = cbxmcratingreview::editor_user_roles();

			// 9 default extra fields  //note review field is now separeated
			$default_extra_fields = array(
				'question_last_count' => array(
					'type'       => 'hidden',
					'default'    => 1,
					'id'         => 'question_last_count',
					'extrafield' => true
				),
				'criteria_last_count' => array(
					'type'       => 'hidden',
					'default'    => 2,
					'id'         => 'criteria_last_count',
					'extrafield' => true
				),

				'post_types'              => array(
					'label'       => esc_html__( 'Post Type Support', 'cbxmcratingreview' ),
					'desc'        => esc_html__( 'Which post types can have the rating & review features. Please make sure multiple form is not associated with same post type for best performance.', 'cbxmcratingreview' ),
					'type'        => 'select',
					'multiple'    => true,
					'default'     => array( 'post' ),
					'placeholder' => esc_html__( 'Choose post type(s)...', 'cbxmcratingreview' ),
					'required'    => true,
					'options'     => $post_types,
					'errormsg'    => esc_html__( 'Post type is missing or at least one post type must be selected', 'cbxmcratingreview' ),
					'extrafield'  => true,
				),

				'user_roles_rate'         => array(
					'label'       => esc_html__( 'Who Can give Rate & Review', 'cbxmcratingreview' ),
					'desc'        => esc_html__( 'Which user role will have vote capability', 'cbxmcratingreview' ),
					'type'        => 'select',
					'placeholder' => esc_html__( 'Choose User Group ...', 'cbxmcratingreview' ),
					'multiple'    => true,
					'default'     => array( 'administrator', 'editor', 'author', 'contributor', 'subscriber'),
					'required'    => true,
					'options'     => $user_roles_no_guest,
					'errormsg'    => esc_html__( 'User role missing or at least one user role must be selected', 'cbxmcratingreview' ),
					'extrafield'  => true
				),
				'user_roles_view'         => array(
					'label'       => esc_html__( 'Who Can View Rating & Review', 'cbxmcratingreview' ),
					'desc'        => esc_html__( 'Which user role will have view capability', 'cbxmcratingreview' ),
					'type'        => 'select',
					'placeholder' => esc_html__( 'Choose User Group ...', 'cbxmcratingreview' ),
					'multiple'    => true,
					'default'     => array( 'administrator', 'editor', 'author', 'contributor', 'subscriber', 'guest'  ),
					'required'    => true,
					'options'     => $user_roles_with_guest,
					'errormsg'    => esc_html__( 'User role missing or at least one user role must be selected', 'cbxmcratingreview' ),
					'extrafield'  => true
				),
				'enable_auto_integration' => array(
					'label'      => esc_html__( 'Enable Auto Integration', 'cbxmcratingreview' ),
					'desc'       => esc_html__( 'Enable/disable auto integration, ie, add average rating before post content in archive, in details article mode add average rating information before content, rating form & review listing after content', 'cbxmcratingreview' ),
					'type'       => 'radio',
					'default'    => 1,
					'options'    => array(
						'1' => esc_html__( 'On', 'cbxmcratingreview' ),
						'0' => esc_html__( 'Off', 'cbxmcratingreview' ),
					),
					'extrafield' => true,
				),
				'post_types_auto'         => array(
					'label'       => esc_html__( 'Auto Integration for Post Type', 'cbxmcratingreview' ),
					'desc'        => __( 'Enable which post types will have auto integration features. Please note that selected post types should be within the post types selected for <strong>Post Type Support</strong>', 'cbxmcratingreview' ),
					'type'        => 'select',
					'multiple'    => true,
					'default'     => array(),
					'placeholder' => esc_html__( 'Choose post type(s)...', 'cbxmcratingreview' ),
					'options'     => array(),
					'errormsg'    => esc_html__( 'Post type is missing or at least one post type must be selected', 'cbxmcratingreview' ),
					'extrafield'  => true,
				),
				'show_on_single'          => array(
					'label'      => esc_html__( 'Show on Single', 'cbxmcratingreview' ),
					'desc'       => esc_html__( 'Enable disable for single article(post, page or any custom post type), related with auto integration.', 'cbxmcratingreview' ),
					'type'       => 'radio',
					'default'    => 1,
					'required'   => false,
					'options'    => array(
						'1' => esc_html__( 'Yes', 'cbxmcratingreview' ),
						'0' => esc_html__( 'No', 'cbxmcratingreview' )
					),
					'extrafield' => true,
				),
				'show_on_home'    => array(
					'label'      => esc_html__( 'Show on Home/Frontpage', 'cbxmcratingreview' ),
					'desc'       => esc_html__( 'Enable disable for home/frontpage, related with auto integration.', 'cbxmcratingreview' ),
					'type'       => 'radio',
					'default'    => 1,
					'required'   => false,
					'options'    => array(
						'1' => esc_html__( 'Yes', 'cbxmcratingreview' ),
						'0' => esc_html__( 'No', 'cbxmcratingreview' )
					),
					'extrafield' => true,
				), //show on home or frontpage
				'show_on_arcv'    => array(
					'label'      => esc_html__( 'Show on Archives', 'cbxmcratingreview' ),
					'desc'       => esc_html__( 'Enable disable for archive pages, related with auto integration.', 'cbxmcratingreview' ),
					'type'       => 'radio',
					'default'    => 1,
					'required'   => false,
					'options'    => array(
						'1' => esc_html__( 'Yes', 'cbxmcratingreview' ),
						'0' => esc_html__( 'No', 'cbxmcratingreview' )
					),
					'extrafield' => true,
				), //show on any kind of archive

				/*'logging_method' => array(
					'label'       => esc_html__( 'Logging Method', 'cbxmcratingreview' ),
					'desc'        => esc_html__( 'Log user rating by ip or cookie or both to protect multiple rating, useful for guest rating', 'cbxmcratingreview' ),
					'type'        => 'select',
					'multiple'    => true,
					'default'     => array( 'ip', 'cookie' ),
					'tooltip'     => esc_html__( 'Log user rating for guest using ip and cookie', 'cbxmcratingreview' ),
					'placeholder' => esc_html__( 'Choose logging method...', 'cbxmcratingreview' ),

					'required'   => true,
					'options'    => array(
						'ip'     => esc_html__( 'IP', 'cbxmcratingreview' ),
						'cookie' => esc_html__( 'Cookie', 'cbxmcratingreview' )
					),
					'errormsg'   => esc_html__( 'At least one logging method should be enabled', 'cbxmcratingreview' ),
					'extrafield' => true
				), // Logging method*/


				/*'enable_comment'     => array(
					'label'   => esc_html__( 'Enable Comment', 'cbxmcratingreview' ),
					'desc'    => esc_html__( 'Enable Comment with Rating', 'cbxmcratingreview' ),
					'type'    => 'radio',
					'default' => 1,
					'tooltip' => esc_html__( 'Enabled by default', 'cbxmcratingreview' ),

					'required'   => false,
					'options'    => array(
						'1' => esc_html__( 'Yes', 'cbxmcratingreview' ),
						'0' => esc_html__( 'No', 'cbxmcratingreview' )
					),
					'extrafield' => true
				), //enable comment box
				'comment_limit'      => array(
					'label'       => esc_html__( 'Comment Limit Length', 'cbxmcratingreview' ),
					'desc'        => esc_html__( 'Comment limit length prevents user from submitting long comment', 'cbxmcratingreview' ),
					'type'        => 'number',
					'default'     => 200,
					'placeholder' => esc_html__( 'Comment Length', 'cbxmcratingreview' ),
					'required'    => true,
					'errormsg'    => esc_html__( 'Comment limit can not empty or must be numeric', 'cbxmcratingreview' ),
					'extrafield'  => true
				), //limit comment box char limit*/
				'enable_question' => array(
					'label'      => esc_html__( 'Enable Question', 'cbxmcratingreview' ),
					'desc'       => esc_html__( 'Enable Question with Rating', 'cbxmcratingreview' ),
					'type'       => 'radio',
					'default'    => 1,
					'required'   => true,
					'options'    => array(
						'1' => esc_html__( 'Yes', 'cbxmcratingreview' ),
						'0' => esc_html__( 'No', 'cbxmcratingreview' )
					),
					'errormsg'   => esc_html__( 'Enable question field is missing or value must be 0 or 1', 'cbxmcratingreview' ),
					'extrafield' => true
				), // Enable Questions
				/*'view_allowed_users' => array(
					'label'       => esc_html__( 'Allowed User Roles Who Can View Rating', 'cbxmcratingreview' ),
					'desc'        => esc_html__( 'Which user group can view rating', 'cbxmcratingreview' ),
					'type'        => 'select',
					'multiple'    => true,
					'placeholder' => esc_html__( 'Choose User Role ...', 'cbxmcratingreview' ),
					'default'     => array( 'guest', 'administrator', 'editor' ),
					'required'    => true,
					'options'     => $userRoles,
					'extrafield'  => true,
					'errormsg'    => esc_html__( 'You must give access to at least one User Group who can View Rating', 'cbxmcratingreview' )
				), //view allowed user

				'comment_view_allowed_users' => array(
					'label'       => esc_html__( 'Allowed User Roles Who Can View Review', 'cbxmcratingreview' ),
					'desc'        => esc_html__( 'Which user group can view reviews', 'cbxmcratingreview' ),
					'type'        => 'select',
					'multiple'    => true,
					'placeholder' => esc_html__( 'Choose User Role ...', 'cbxmcratingreview' ),
					'default'     => array( 'guest', 'administrator', 'editor' ),
					'required'    => true,
					'options'     => $userRoles,
					'extrafield'  => true,
					'errormsg'    => esc_html__( 'You must give access to at least one User Group who can View Comment', 'cbxmcratingreview' )
				), //review view allowed user
				'comment_required'           => array(
					'label'      => esc_html__( 'Comment required', 'cbxmcratingreview' ),
					'desc'       => esc_html__( 'This option will make the comment box required', 'cbxmcratingreview' ),
					'type'       => 'radio',
					'default'    => 0,
					'required'   => false,
					'options'    => array(
						'1' => esc_html__( 'Yes', 'cbxmcratingreview' ),
						'0' => esc_html__( 'No', 'cbxmcratingreview' )
					),
					'extrafield' => true

				), //comment box while rating required
				'show_user_avatar_in_review' => array(
					'label'      => esc_html__( 'Author Avatar in Review', 'cbxmcratingreview' ),
					'desc'       => esc_html__( 'Show/hide reviewer\'s profile picture or avatar in review', 'cbxmcratingreview' ),
					'type'       => 'radio',
					'default'    => 0,
					'required'   => false,
					'options'    => array(
						'1' => esc_html__( 'Yes', 'cbxmcratingreview' ),
						'0' => esc_html__( 'No', 'cbxmcratingreview' )
					),
					'extrafield' => true
				), // show user's avater or profile picture in review
				'show_user_link_in_review'   => array(
					'label'      => esc_html__( 'Show Author Link in Review', 'cbxmcratingreview' ),
					'desc'       => esc_html__( 'Link user to their author page in each review', 'cbxmcratingreview' ),
					'type'       => 'radio',
					'default'    => 0,
					'required'   => false,
					'options'    => array(
						'1' => esc_html__( 'Yes', 'cbxmcratingreview' ),
						'0' => esc_html__( 'No', 'cbxmcratingreview' )
					),
					'extrafield' => true
				), //show user's link/profile/author link in review
				'show_editor_rating'         => array(
					'label'   => esc_html__( 'Show Editor Rating', 'cbxmcratingreview' ),
					'desc'    => esc_html__( 'Show/hide rating editor user group rating', 'cbxmcratingreview' ),
					'type'    => 'radio',
					'default' => 0,
					'tooltip' => esc_html__( 'Which user group is rating editor is selectable', 'cbxmcratingreview' ),

					'required'   => false,
					'options'    => array(
						'1' => esc_html__( 'Yes', 'cbxmcratingreview' ),
						'0' => esc_html__( 'No', 'cbxmcratingreview' )
					),
					'extrafield' => true
				), // show editor rating on frontend yes/no
				'review_enabled'             => array(
					'label'      => esc_html__( 'Show/Hide Reviews', 'cbxmcratingreview' ),
					'desc'       => esc_html__( 'Control showing reviews on frontend', 'cbxmcratingreview' ),
					'type'       => 'radio',
					'default'    => 1,
					'required'   => false,
					'options'    => array(
						'1' => esc_html__( 'Yes', 'cbxmcratingreview' ),
						'0' => esc_html__( 'No', 'cbxmcratingreview' )
					),
					'extrafield' => true
				), // show hide reviews
				'review_limit'               => array(
					'label'       => esc_html__( 'Review Limit Per Page', 'cbxmcratingreview' ),
					'desc'        => esc_html__( 'How many reviews will be shown per page or in ajax request', 'cbxmcratingreview' ),
					'type'        => 'number',
					'default'     => 10,
					'placeholder' => esc_html__( 'Review Limit', 'cbxmcratingreview' ),
					'required'    => true,
					'extrafield'  => true,
					'errormsg'    => esc_html__( 'Review Limit is required, must be numeric value', 'cbxmcratingreview' )
				), //default per page reviews limit
				'email_verify_guest'         => array(
					'label'      => esc_html__( 'Guest User Email Verify', 'cbxmcratingreview' ),
					'desc'       => esc_html__( 'Review from guest user will not be published instance if this is enabled, guest will need to verify email', 'cbxmcratingreview' ),
					'type'       => 'radio',
					'default'    => 1,
					'required'   => false,
					'options'    => array(
						'1' => esc_html__( 'Yes', 'cbxmcratingreview' ),
						'0' => esc_html__( 'No', 'cbxmcratingreview' )
					),
					'extrafield' => true
				)*/

			);


			$default_extra_fields = apply_filters( 'cbxmcratingreview_default_extra_fields', $default_extra_fields );

			return $default_extra_fields;
		}//end method form_default_extra_fields

		/**
		 * Render single question
		 *
		 * @param int   $question_index
		 * @param array $question
		 * @param array $stored_values
		 *
		 * @return string
		 */
		public static function ratingFormEditRenderQuestion( $question_index = 0, $question = array(), $stored_values = array() ) {
			$field_type = isset( $question['type'] ) ? $question['type'] : '';

			$form_question_formats = CBXMCRatingReviewHelper::form_question_formats();

			$fieldDisplay = '';

			if ( $field_type == '' ) {
				return '';
			}

			if ( isset( $form_question_formats[ $field_type ] ) ) {
				$form_question_format = $form_question_formats[ $field_type ];
				$question_render      = $form_question_format['admin_renderer'];

				if ( is_callable( $question_render ) ) {
					$fieldDisplay = call_user_func( $question_render, $question_index, $question, $stored_values );
				} else {
					return '';
				}

			} else {
				return '';
			}


			if ( $fieldDisplay == '' ) {
				return '';
			}

			$multiple = isset( $question['multiple'] ) ? intval( $question['multiple'] ) : 0;

			$question_title = ( isset( $question['title'] ) ? wp_unslash( $question['title'] ) : esc_html__( 'Untitled Question', 'cbxmcratingreview' ) . ' ' . $question_index . '? (' . esc_html__( 'Click to edit', 'cbxmcratingreview' ) . ')' );

			$output = '                
                <div class="custom-question-table-row custom-question-table-row-item">
                    <div class="custom-question-table-col custom-question-table-col-title" style="width: 20%;">
                        <label id="question-label-' . $question_index . '" class="question-label question-label-editable mouse_normal"      title="' . esc_html__( 'Click to edit', 'cbxmcratingreview' ) . '" 
                                >' . $question_title . '</label>

                        <input id="question-label-input-' . $question_index . '" class="question-label-input question-label-input-editable regular-text disable_field"  type="text" 
                            name="cbxmcratingreview_ratingForm[custom_question][' . $question_index . '][title]"    value="' . $question_title . '" />
                    </div>'; //end .custom-question-table-col-title

			$output .= '<div class="custom-question-table-col custom-question-table-col-control" style="width:20%;">';
			$output .= '<p>' . esc_html__( 'Required Question ?', 'cbxmcratingreview' ) . '</p>
                        <label title="' . esc_html__( 'Yes', 'cbxmcratingreview' ) . '"><input ' . checked( intval( $question['required'] ), 1, $echo = false ) . ' id="edit-custom-question-require-q-' . $question_index . '-1" type="radio" name="cbxmcratingreview_ratingForm[custom_question][' . $question_index . '][required]" value="1"> <span>' . esc_html__( 'Yes', 'cbxmcratingreview' ) . '</span></label>
						<label title="' . esc_html__( 'No', 'cbxmcratingreview' ) . '"><input ' . checked( intval( $question['required'] ), 0, $echo = false ) . ' id="edit-custom-question-require-q-' . $question_index . '-0" type="radio" name="cbxmcratingreview_ratingForm[custom_question][' . $question_index . '][required]" value="0"> <span>' . esc_html__( 'No', 'cbxmcratingreview' ) . '</span></label>';
			$output .= '<p>' . esc_html__( 'Show Question ?', 'cbxmcratingreview' ) . '</p>
                        <label title="' . esc_html__( 'Yes', 'cbxmcratingreview' ) . '"><input ' . checked( intval( $question['enabled'] ), 1, $echo = false ) . ' id="edit-custom-question-enabled-q-' . $question_index . '-1" type="radio" name="cbxmcratingreview_ratingForm[custom_question][' . $question_index . '][enabled]" value="1"> <span>' . esc_html__( 'Yes', 'cbxmcratingreview' ) . '</span></label>
						<label title="' . esc_html__( 'No', 'cbxmcratingreview' ) . '"><input ' . checked( intval( $question['enabled'] ), 0, $echo = false ) . ' id="edit-custom-question-enabled-q-' . $question_index . '-0" type="radio" name="cbxmcratingreview_ratingForm[custom_question][' . $question_index . '][enabled]" value="0"> <span>' . esc_html__( 'No', 'cbxmcratingreview' ) . '</span></label>';

			if ( $field_type == 'select' ) {
				$output .= '<p>' . esc_html__( 'Allow Select Multiple?', 'cbxmcratingreview' ) . '</p>
                        <label title="' . esc_html__( 'Yes', 'cbxmcratingreview' ) . '"><input ' . checked( $multiple, 1, $echo = false ) . ' id="edit-custom-question-require-q-' . $question_index . '-1" type="radio" name="cbxmcratingreview_ratingForm[custom_question][' . $question_index . '][multiple]" value="1"> <span>' . esc_html__( 'Yes', 'cbxmcratingreview' ) . '</span></label>
						<label title="' . esc_html__( 'No', 'cbxmcratingreview' ) . '"><input ' . checked( $multiple, 0, $echo = false ) . ' id="edit-custom-question-require-q-' . $question_index . '-0" type="radio" name="cbxmcratingreview_ratingForm[custom_question][' . $question_index . '][multiple]" value="0"> <span>' . esc_html__( 'No', 'cbxmcratingreview' ) . '</span></label>';

			}
			$output .= '</div>'; //.custom-question-table-col-control
			$output .= '<div class="custom-question-table-col custom-question-table-col-type" style="width: 10%;">' . ucfirst( $field_type ) . '</div>
							<div class="custom-question-table-col custom-question-table-col-preview" style="width: 45%;">' . $fieldDisplay . ' </div>';

			//

			$question_action = '';

			$output .= '<div class="custom-question-table-col custom-question-table-col-actions" style="width: 5%;">' . apply_filters( 'cbxmcratingreview_question_action', $question_action, $question_index, $question, $stored_values ) . '</div>';
			$output .= '<div style="clear:both;"></div>';
			$output .= '</div>'; //.custom-question-table-row-item
			//$output .= '<div style="clear:both;"></div>';

			return $output;
		}//end method ratingFormEditRenderQuestion

		/**
		 * Returns Plain rating form lists with title and id as associative array
		 *
		 * @return array
		 */
		public static function getRatingFormsList() {
			global $wpdb;

			$cbxmcratingreview_setting = new CBXMCRatingReviewSettings();
			$table_rating_form = $wpdb->prefix . 'cbxmcratingreview_form';
			$rating_forms      = array();

			$sql = "SELECT * FROM $table_rating_form ORDER BY name ASC";

			$forms = $wpdb->get_results( $sql, ARRAY_A );

			$default_form = intval( $cbxmcratingreview_setting->get_option( 'default_form', 'cbxmcratingreview_common_config', 0 ) );


			if ( $forms !== null ) {
				foreach ( $forms as $form ) {
					$form_id    = intval( $form['id'] );
					$form_title = esc_attr( $form['name'] );

					if($form_title == '') $form_title = esc_html__('Untitled Form', 'cbxmcratingreview');

					if($form_id == $default_form){
						$form_title .= ' '.esc_html__('(Default)', 'cbxmcratingreview');
					}

					$rating_forms[ $form_id ] = $form_title;
				}
			}

			return $rating_forms;
		}//end method getRatingFormsList

		/**
		 * Get all rating forms
		 *
		 *
		 * @return array
		 */
		public static function getRatingForms() {
			global $wpdb;

			$cbxmcratingreview_setting = new CBXMCRatingReviewSettings();

			$form_default = CBXMCRatingReviewHelper::form_default_fields();
			//$form_question = CBXMCRatingReviewHelper::form_default_questions();
			//$form_criteria = CBXMCRatingReviewHelper::form_default_criterias();

			$default_form = intval( $cbxmcratingreview_setting->get_option( 'default_form', 'cbxmcratingreview_common_config', 0 ) );

			$table_rating_form = $wpdb->prefix . 'cbxmcratingreview_form';


			$sql = "SELECT * FROM $table_rating_form ORDER BY id ASC";


			$results = $wpdb->get_results( $sql, ARRAY_A );

			if ( empty( $results ) ) {
				return array();
			}

			$count = sizeof( $results );

			for ( $i = 0; $i < $count; $i ++ ) {

				$form_id 		= intval($results[ $i ]['id']);
				$form_title 	= esc_attr($results[ $i ]['name']);
				if($form_title == '') $form_title = esc_html__('Untitled Form', 'cbxmcratingreview');

				if($form_id == $default_form){
					$form_title .= ' '.esc_html__('(Default)', 'cbxmcratingreview');
					$results[ $i ]['name'] = $form_title;
				}

				$results[ $i ]["custom_criteria"] = maybe_unserialize( $results[ $i ]["custom_criteria"] );
				$results[ $i ]["custom_question"] = maybe_unserialize( $results[ $i ]["custom_question"] );

				$result      = $results[ $i ];
				$extrafields = maybe_unserialize( $result['extrafields'] );

				$extrafields = (array) $extrafields;


				$result = array_merge( $result, $extrafields );


				foreach ( $form_default as $key => $field ) {
					if ( $field['type'] == 'select' && isset( $field['multiple'] ) && $field['multiple'] == true ) {

						if ( isset( $result[ $key ] ) ) {
							$result[ $key ] = maybe_unserialize( $result[ $key ] ); // warning for new field
						} else {
							$result[ $key ] = $field['default']; // warning for new field
						}
					}
				}

				$custom_criterias = $result["custom_criteria"];
				foreach ( $custom_criterias as $criteria_index => $custom_criteria ) {
					$criteria_id     = isset( $custom_criteria['criteria_id'] ) ? intval( $custom_criteria['criteria_id'] ) : intval( $criteria_index );
					$stars           = isset( $custom_criteria['stars'] ) ? $custom_criteria['stars'] : array();
					$stars_formatted = array();
					if ( is_array( $stars ) && sizeof( $stars ) > 0 ) {
						foreach ( $stars as $star_index => $star ) {
							//$enabled = isset( $star['enabled'] ) ? intval( $star['enabled'] ) : 0;
							//$star_id = isset( $star['star_id'] ) ? intval( $star['star_id'] ) : intval( $star_index );
							$title   = isset( $star['title'] ) ? esc_attr( $star['title'] ) : sprintf( esc_html__( 'Star %d' ), ( $star_index + 1 ) );



							//if ( $enabled ) {
								$stars_formatted[ $star_index ] = $title;
							//}
						}
					}

					$stars_length = sizeof( $stars_formatted );

					$stars_summary           = array();
					$stars_summary['length'] = intval( $stars_length );
					$stars_summary['stars']  = $stars_formatted;

					$custom_criterias[ $criteria_index ]['stars_formatted'] = $stars_summary;

				}

				$result["custom_criteria"] = $custom_criterias;

				$results[ $i ] = $result;

			}


			return apply_filters( 'cbxmcratingreview_get_ratingForms', $results );
		}//end method getRatingForms

		/**
		 * Get Rating form data by id
		 *
		 * @param      $id
		 * @param bool $is_object
		 *
		 * @return array|object
		 */
		public static function getRatingForm( $id ) {

			global $wpdb;

			$table_rating_form = $wpdb->prefix . 'cbxmcratingreview_form';

			$sql = $wpdb->prepare( "SELECT * FROM $table_rating_form WHERE id=%d", $id );

			$form_default = CBXMCRatingReviewHelper::form_default_fields();


			$results = $wpdb->get_results( $sql, ARRAY_A );

			if ( empty( $results ) ) {
				return array();
			}
			//now we are fixing for array


			$result = $results[0];

			$result["custom_criteria"] = maybe_unserialize( $result["custom_criteria"] );
			$result["custom_question"] = maybe_unserialize( $result["custom_question"] );

			$extrafields = maybe_unserialize( $result['extrafields'] );
			$extrafields = (array) $extrafields;

			$result = array_merge( $result, $extrafields );
			foreach ( $form_default as $key => $field ) {
				if ( $field['type'] == 'select' && isset( $field['multiple'] ) && $field['multiple'] == true ) {

					if ( isset( $result[ $key ] ) ) {
						$result[ $key ] = maybe_unserialize( $result[ $key ] ); // warning for new field
					} else {
						$result[ $key ] = $field['default']; // warning for new field
					}
				}
			}

			$custom_criterias = $result["custom_criteria"];
			foreach ( $custom_criterias as $criteria_index => $custom_criteria ) {
				$criteria_id     = isset( $custom_criteria['criteria_id'] ) ? intval( $custom_criteria['criteria_id'] ) : intval( $criteria_index );
				$stars           = isset( $custom_criteria['stars'] ) ? $custom_criteria['stars'] : array();
				$stars_formatted = array();
				if ( is_array( $stars ) && sizeof( $stars ) > 0 ) {
					foreach ( $stars as $star_index => $star ) {
						//$enabled = isset( $star['enabled'] ) ? intval( $star['enabled'] ) : 0;
						//$star_id = isset( $star['star_id'] ) ? intval( $star['star_id'] ) : intval( $star_index );
						$title   = isset( $star['title'] ) ? esc_attr( $star['title'] ) : sprintf( esc_html__( 'Star %d' ), ( $star_index + 1 ) );
						//if ( $enabled ) {
							$stars_formatted[ $star_index ] = $title;
						//}
					}
				}

				$stars_length = sizeof( $stars_formatted );

				$stars_summary           = array();
				$stars_summary['length'] = intval( $stars_length );
				$stars_summary['stars']  = $stars_formatted;

				$custom_criterias[ $criteria_index ]['stars_formatted'] = $stars_summary;

			}

			$result["custom_criteria"] = $custom_criterias;


			return apply_filters( 'cbxmcratingreview_get_ratingForm', $result, $id );
		}//end method get_ratingForm

		/**
		 * Returns rating form count
		 *
		 * @return int
		 */
		public static function getRatingForms_Count() {
			global $wpdb;

			//$table_name = CBXMCRatingReviewHelper::get_table_rating_forms();

			$table_rating_form = $wpdb->prefix . 'cbxmcratingreview_form';

			$count = $wpdb->get_var( "SELECT COUNT(*) as count FROM $table_rating_form  WHERE 1" );
			if ( $count !== null ) {
				return intval( $count );
			}

			return 0;

		}//end method get_ratingForms_Count

		/**
		 * Create or update Rating Form
		 *
		 * @param $ratingForm
		 *
		 * @return bool
		 */
		public static function insert_update_ratingForm( $ratingForm ) {
			global $wpdb;


			if ( is_array( $ratingForm ) && sizeof($ratingForm) > 0 ) {

				$table_rating_form = $wpdb->prefix . 'cbxmcratingreview_form';

				$id = $ratingForm['id'];
				unset( $ratingForm['id'] );

				if ( $id == 0 ) {
					$success = $wpdb->insert( $table_rating_form, $ratingForm, array( '%s', '%d', '%s', '%s', '%s' ) );
					$id      = $wpdb->insert_id;

				} else {
					$success = $wpdb->update( $table_rating_form, $ratingForm, array( "id" => $id ), array(
						'%s',
						'%d',
						'%s',
						'%s',
						'%s'
					), array( '%d' ) );

				}
			}

			return ( $success !== false ) ? $id : false;
		}//end method update_ratingForm

		/**
		 * [deprecated or abandoned]Get nth item from an associative array
		 *
		 *
		 * @param     $arr
		 * @param int $nth
		 *
		 * @return array
		 */
		/*public static function getNthItemFromArr( $arr, $nth = 0 ) {
			$nth = intval( $nth );
			if ( is_array( $arr ) && sizeof( $arr ) > 0 && $nth > 0 ) {
				$arr = array_slice( $arr, $nth - 1, 1, true );
			}

			return $arr;
		}//end method getNthItemFromArr*/
	}//end class CBXMCRatingReviewHelper