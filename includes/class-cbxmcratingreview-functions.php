<?php
	// If this file is called directly, abort.
	if ( ! defined( 'WPINC' ) ) {
		die;
	}
?>
<?php

	/**
	 * This function ensures that all necessary js and css for this plugin is added properly
	 *
	 * enqueue css and js
	 */
	function cbxmcratingreview_AddJsCss() {
		return CBXMCRatingReviewHelper::AddJsCss();
	}//end method cbxmcratingreview_AddJsCss

	/**
	 * All necessary css and js for review form
	 */
	function cbxmcratingreview_AddRatingFormJsCss() {
		return CBXMCRatingReviewHelper::AddRatingFormJsCss();
	}//end method cbxmcratingreview_AddRatingFormJsCss

	/**
	 * All necessary css and js for review edit form
	 *
	 *
	 */
	function cbxmcratingreview_AddRatingEditFormJsCss() {
		return CBXMCRatingReviewHelper::AddRatingEditFormJsCss();
	}//end method cbxmcratingreview_AddRatingEditFormJsCss

	/**
	 * is this post rated at least once
	 *
	 * @param int $form_id
	 * @param int $post_id
	 *
	 * @return null|string
	 */
	function cbxmcratingreview_isPostRated( $form_id = 0, $post_id = 0 ) {
		return CBXMCRatingReviewHelper::isPostRated( $form_id, $post_id );
	}//end method cbxmcratingreview_isPostRated

	/**
	 * Total reviews count of a Post
	 *
	 * @param int    $form_id
	 * @param int    $post_id
	 * @param string $status
	 * @param string $score
	 *
	 * @return mixed
	 */
	function cbxmcratingreview_totalPostReviewsCount( $form_id = 0, $post_id = 0, $status = '', $score = '' ) {
		return CBXMCRatingReviewHelper::totalPostReviewsCount( $form_id, $post_id, $status, $score );
	}//end method cbxmcratingreview_totalPostReviewsCount

	/**
	 * is this post rated by user at least once
	 *
	 * @param int $form_id
	 * @param int $post_id
	 * @param int $user_id
	 *
	 * @return boolean - true/false
	 */
	function cbxmcratingreview_isPostRatedByUser( $form_id = 0, $post_id = 0, $user_id = 0 ) {
		return CBXMCRatingReviewHelper::isPostRatedByUser( $form_id, $post_id, $user_id );
	}//end method cbxmcratingreview_isPostRatedByUser

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
	function cbxmcratingreview_totalPostReviewsCountByUser( $form_id = 0, $post_id = 0, $user_id = 0, $status = '' ) {
		return CBXMCRatingReviewHelper::totalPostReviewsCountByUser( $form_id, $post_id, $user_id, $status );
	}//end method cbxmcratingreview_totalPostReviewsCountByUser

	/**
	 * User's last review date for a post by user id
	 *
	 * @param int $post_id
	 * @param int $user_id
	 *
	 * @return boolean - true/false
	 */
	function cbxmcratingreview_lastPostReviewDateByUser( $form_id = 0, $post_id = 0, $user_id = 0 ) {
		return CBXMCRatingReviewHelper::lastPostReviewDateByUser( $form_id, $post_id, $user_id );
	}//end method


	/**
	 * Single review data
	 *
	 * @param int $post_id
	 *
	 * @return null|string
	 */
	function cbxmcratingreview_singleReview( $review_id = 0 ) {
		return CBXMCRatingReviewHelper::singleReview( $review_id );
	}//end method cbxmcratingreview_singleReview

	/**
	 * Single review data render
	 *
	 * @param int $post_id
	 *
	 * @return null|string
	 */
	function cbxmcratingreview_singleReviewRender( $review_id = 0 ) {
		cbxmcratingreview_AddJsCss(); //moved here from static class

		return CBXMCRatingReviewHelper::singleReviewRender( $review_id );
	}//end method cbxmcratingreview_singleReviewRender

	/**
	 * Single review edit data render
	 *
	 * @param int $post_id
	 *
	 * @return null|string
	 */
	function cbxmcratingreview_singleReviewEditRender( $review_id = 0 ) {
		return CBXMCRatingReviewHelper::singleReviewEditRender( $review_id );
	}//end method cbxmcratingreview_singleReviewEditRender

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
	function cbxmcratingreview_postReviews( $form_id = 0, $post_id = 0, $perpage = 10, $page = 1, $status = '', $score = '', $orderby = 'id', $order = 'DESC' ) {
		return CBXMCRatingReviewHelper::postReviews( $form_id, $post_id, $perpage, $page, $status, $score, $orderby, $order );
	}//end method cbxmcratingreview_postReviews

	/**
	 * Review lists data of a Post by a User
	 *
	 * @param int    $form_id
	 * @param int    $post_id
	 * @param int    $user_id
	 * @param int    $perpage
	 * @param int    $page
	 * @param string $status
	 * @param string $score
	 * @param string $orderby
	 * @param string $order
	 *
	 * @return mixed
	 */
	/*function cbxmcratingreview_postReviewsByUser($post_id = 0, $user_id = 0, $perpage = 10, $page = 1, $status = '', $score = '', $orderby = 'id', $order = 'DESC') {
		return CBXMCRatingReviewHelper::postReviewsByUser($post_id, $user_id, $perpage, $page, $status, $score, $orderby, $order );
	}*/

	function cbxmcratingreview_postReviewsFilterRender( $form_id = 0, $post_id = 0, $perpage = 10, $page = 1, $score = '', $orderby = 'id', $order = 'DESC' ) {
		return CBXMCRatingReviewHelper::postReviewsFilterRender( $form_id, $post_id, $perpage, $page, 1, $score, $orderby, $order );
	}//end method cbxmcratingreview_postReviewsFilterRender

	/**
	 * Render Review lists data of a Post
	 *
	 * @param int    $form_id
	 * @param int    $post_id
	 * @param int    $perpage
	 * @param int    $page
	 * @param string $score
	 * @param string $orderby
	 * @param string $order
	 * @param bool   $load_more
	 * @param bool   $show_filter
	 *
	 * @return string
	 */
	function cbxmcratingreview_postReviewsRender( $form_id = 0, $post_id = 0, $perpage = 10, $page = 1, $score = '', $orderby = 'id', $order = 'DESC', $load_more = false, $show_filter = true ) {
		cbxmcratingreview_AddJsCss(); //moved here from static class

		return CBXMCRatingReviewHelper::postReviewsRender( $form_id, $post_id, $perpage, $page, 1, $score, $orderby, $order, $load_more, $show_filter );
	}//end method cbxmcratingreview_postReviewsRender


	/**
	 * Reviews list data
	 *
	 * @param int    $form_id
	 * @param int    $perpage
	 * @param int    $page
	 * @param string $status
	 * @param string $orderby
	 * @param string $order
	 * @param string $score
	 *
	 * @return mixed
	 */
	function cbxmcratingreview_Reviews( $form_id = '', $perpage = 10, $page = 1, $status = '', $orderby = 'id', $order = 'DESC', $score = '' ) {
		return CBXMCRatingReviewHelper::Reviews( $form_id, $perpage, $page, $status, $orderby, $order, $score );
	}//end method cbxmcratingreview_Reviews

	/**
	 * Total reviews count
	 *
	 * @param int/string $form_id
	 * @param string $status
	 * @param string $filter_score
	 *
	 * @return mixed
	 */
	function cbxmcratingreview_totalReviewsCount( $form_id = '', $status = '', $filter_score = '' ) {
		return CBXMCRatingReviewHelper::totalReviewsCount( $form_id, $status, $filter_score );
	}//end method cbxmcratingreview_totalReviewsCount

	/**
	 * Total reviews count by post type, status and filter
	 *
	 * @param int/string $form_id
	 * @param string $post_type
	 * @param string $status
	 * @param string $filter_score
	 *
	 * @return mixed
	 */
	function cbxmcratingreview_totalReviewsCountPostType( $form_id = '', $post_type = 'post', $status = '', $filter_score = '' ) {
		return CBXMCRatingReviewHelper::totalReviewsCountPostType( $form_id, $post_type, $status, $filter_score );
	}//end method cbxmcratingreview_totalReviewsCountPostType

	/**
	 * Total reviews count by User
	 *
	 * @param int/string $form_id
	 * @param int    $user_id
	 * @param string $status
	 * @param string $filter_score
	 *
	 * @return mixed
	 */
	function cbxmcratingreview_totalReviewsCountByUser( $form_id = '', $user_id = 0, $status = '', $filter_score = '' ) {
		return CBXMCRatingReviewHelper::totalReviewsCountByUser( $form_id, $user_id, $status, $filter_score );
	}//end method cbxmcratingreview_totalReviewsCountByUser


	/**
	 * Reviews list data by a User
	 *
	 * @param int/string    $form_id
	 * @param int    $user_id
	 * @param int    $perpage
	 * @param int    $page
	 * @param string $status
	 * @param string $orderby
	 * @param string $order
	 * @param string $filter_score
	 *
	 * @return array|null|object
	 */
	function cbxmcratingreview_ReviewsByUser( $form_id = '', $user_id = 0, $perpage = 10, $page = 1, $status = '', $orderby = 'id', $order = 'DESC', $filter_score = '' ) {
		return CBXMCRatingReviewHelper::ReviewsByUser( $form_id, $user_id, $perpage, $page, $status, $orderby, $order, $filter_score );
	}//end method cbxmcratingreview_ReviewsByUser


	/**
	 * Average rating information of a post by post id
	 *
	 * @param int $form_id
	 * @param int $post_id
	 *
	 * @return null|string
	 */
	function cbxmcratingreview_postAvgRatingInfo( $form_id = 0, $post_id = 0 ) {
		return CBXMCRatingReviewHelper::postAvgRatingInfo( $form_id, $post_id );
	}//end method cbxmcratingreview_postAvgRatingInfo

	/**
	 * Render single post avg rating for a form
	 *
	 * @param int     $form_id
	 * @param int     $post_id
	 * @param boolean $show_chart
	 * @param boolean $show_score
	 */
	function cbxmcratingreview_postAvgRatingRender( $form_id = 0, $post_id = 0, $show_star = true, $show_score = true, $show_chart = false ) {
		return CBXMCRatingReviewHelper::postAvgRatingRender( $form_id, $post_id, $show_star, $show_score, $show_chart );
	}//end method cbxmcratingreview_postAvgRatingRender

	/**
	 * Render single post avg rating for a form
	 *
	 * @param int     $form_id
	 * @param int     $post_id
	 * @param boolean $show_chart
	 * @param boolean $show_score
	 */
	function cbxmcratingreview_postAvgDetailsRatingRender( $form_id = 0, $post_id = 0, $show_star = true, $show_score = true, $show_short = true, $show_chart = true ) {
		return CBXMCRatingReviewHelper::postAvgDetailsRatingRender( $form_id, $post_id, $show_star, $show_score, $show_short, $show_chart );
	}//end method cbxmcratingreview_postAvgDetailsRatingRender

	/**
	 * Single avg rating info by avg id
	 *
	 * @param int $avg_id
	 *
	 * @return null|string
	 */
	function cbxmcratingreview_singleAvgRatingInfo( $avg_id = 0 ) {
		return CBXMCRatingReviewHelper::singleAvgRatingInfo( $avg_id );
	}//end method cbxmcratingreview_singleAvgRatingInfo

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
	function cbxmcratingreview_most_rated_posts( $form_id = 0, $limit = 10, $orderby = 'avg_rating', $order = 'DESC', $type = 'post' ) {
		return CBXMCRatingReviewHelper::most_rated_posts( $form_id, $limit, $orderby, $order, $type );
	}//end method cbxmcratingreview_mostRatedPosts

	/**
	 * latest ratings of all post
	 *
	 * @param int    $form_id
	 * @param int    $limit
	 * @param string $orderby
	 * @param string $order
	 * @param string $type
	 * @param int    $user_id
	 *
	 * @return array|null|object
	 */
	function cbxmcratingreview_lastest_ratings( $form_id = 0, $limit = 10, $orderby = 'id', $order = 'DESC', $type = 'post', $user_id = 0 ) {
		return CBXMCRatingReviewHelper::lastest_ratings( $form_id, $limit, $orderby, $order, $type, $user_id );
	}//end method cbxmcratingreview_latestRatings

	/**
	 * latest ratings of author post
	 */
	/*function cbxmcratingreview_authorpostlatestRatings( $limit = 10, $user_id = 0 ) {
		return CBXMCRatingReviewHelper::authorpostlatestRatings( $limit, $user_id );
	}//end method cbxmcratingreview_authorpostlatestRatings*/

	/**
	 * Render rating review form
	 *
	 * @param int $form_id
	 * @param int $post_id
	 *
	 * @return string
	 */
	function cbxmcratingreview_reviewformRender( $form_id = 0, $post_id = 0 ) {
		return CBXMCRatingReviewHelper::reviewformRender( $form_id, $post_id );
	}//end method cbxmcratingreview_reviewformRender

	/**
	 * paginate_links_as_bootstrap()
	 * JPS 20170330
	 * Wraps paginate_links data in Twitter bootstrap pagination component
	 *
	 * @param array $args      {
	 *                         Optional. {@see 'paginate_links'} for native argument list.
	 *
	 * @type string $nav_class classes for <nav> element. Default empty.
	 * @type string $ul_class  additional classes for <ul.pagination> element. Default empty.
	 * @type string $li_class  additional classes for <li> elements.
	 * }
	 * @return array|string|void String of page links or array of page links.
	 */
	function cbxmcratingreview_paginate_links_as_bootstrap( $args = '' ) {
		$args['type'] = 'array';
		$defaults     = array(
			'nav_class' => '',
			'ul_class'  => '',
			'li_class'  => ''
		);
		$args         = wp_parse_args( $args, $defaults );
		$page_links   = paginate_links( $args );

		if ( $page_links ) {
			$r         = '';
			$nav_class = empty( $args['nav_class'] ) ? '' : 'class="' . $args['nav_class'] . '"';
			$ul_class  = empty( $args['ul_class'] ) ? '' : ' ' . $args['ul_class'];

			//$r .= '<nav '. $nav_class .' aria-label="navigation">' . "\n\t";
			$r .= '<div ' . $nav_class . ' aria-label="navigation">' . "\n\t";

			$r .= '<ul class="pagination' . $ul_class . '">' . "\n";
			foreach ( $page_links as $link ) {
				$li_classes = explode( " ", $args['li_class'] );
				strpos( $link, 'current' ) !== false ? array_push( $li_classes, 'active' ) : ( strpos( $link, 'dots' ) !== false ? array_push( $li_classes, 'disabled' ) : '' );
				$class = empty( $li_classes ) ? '' : 'class="' . join( " ", $li_classes ) . '"';
				$r     .= "\t\t" . '<li ' . $class . '>' . $link . '</li>' . "\n";
			}
			$r .= "\t</ul>";
			$r .= "\n</div>";

			return '<div class="clearfix"></div><nav class="blog-page--pagination cbxmcratingreview_paginate_links">' . $r . '</nav><div class="clearfix"></div>';
		}
	}//end function cbxmcratingreview_paginate_links_as_bootstrap


	if ( ! function_exists( 'get_reviewer_posts_url' ) ) {
		/**
		 * Return author profile by user id
		 *
		 * @param int $user_id
		 */
		/*	function get_reviewer_posts_url( $user_id = 0 ) {
				global $wp_rewrite;

				$user_id = ( $user_id == 0 ) ? get_current_user_id() : $user_id;

				$publicprofile_page_id  = intval( get_theme_mod( 'publicprofile_page_id', 0 ) );
				$publicprofile_page_url = get_permalink( $publicprofile_page_id );

				$get_reviewer_posts_url = '';

				if ( $wp_rewrite->using_permalinks() ) {
					$get_reviewer_posts_url = trailingslashit( $publicprofile_page_url ) . $user_id;
				} else {
					$get_reviewer_posts_url = add_query_arg( array(
						'member_id' => $user_id
					), $publicprofile_page_url );
				}

				return $get_reviewer_posts_url;
			}

			add_filter( 'cbxmcratingreview_reviewer_posts_url', 'cbxmcratingreview_reviewer_posts_url_modified', 10, 2 );

			function cbxmcratingreview_reviewer_posts_url_modified( $current_url, $user_id ) {
				return get_reviewer_posts_url( $user_id );
			}*/
	}//end method get_reviewer_posts_url


	/**
	 * Review permalink
	 *
	 * @param $review_id
	 *
	 * @return string
	 */
	function cbxmcratingreview_review_permalink( $review_id ) {
		$cbxmcratingreview_setting = new CBXMCRatingReviewSettings();;

		//global $wp_rewrite;
		$review_id = intval( $review_id );


		if ( $review_id == 0 ) {
			return '#';
		}

		$singlereview_page_id = intval( $cbxmcratingreview_setting->get_option( 'single_review_view_id', 'cbxmcratingreview_tools', 0 ) );


		if ( $singlereview_page_id > 0 ) {

			$singlereview_page_link = get_permalink( $singlereview_page_id );

			return add_query_arg( array( 'review_id' => $review_id ), $singlereview_page_link ) . '#cbxmcratingreview_review_list_item_' . $review_id;
		}

		return '#';
	}//end method cbxmcratingreview_review_permalink

	/**
	 * Reviews toolbar render
	 *
	 * @param $post_review
	 *
	 * @return false|string
	 */
	function cbxmcratingreview_reviewToolbarRender( $post_review ) {
		return CBXMCRatingReviewHelper::reviewToolbarRender( $post_review );
	}//end method cbxmcratingreview_reviewToolbarRender

	/**
	 * render single review delete button
	 *
	 * @param array $post_review
	 *
	 * @return string
	 */
	function cbxmcratingreview_reviewDeleteButtonRender( $post_review = array() ) {
		return CBXMCRatingReviewHelper::reviewDeleteButtonRender( $post_review );
	}//end method cbxmcratingreview_reviewDeleteButtonRender

	/**
	 * Get the template path.
	 *
	 * @return string
	 */
	function cbxmcratingreview_template_path() {
		return apply_filters( 'cbxmcratingreview_template_path', 'cbxmcratingreview/' );
	}//end method cbxmcratingreview_template_path

	/**
	 * Locate a template and return the path for inclusion.
	 *
	 * This is the load order:
	 *
	 * yourtheme/$template_path/$template_name
	 * yourtheme/$template_name
	 * $default_path/$template_name
	 *
	 * @param string $template_name Template name.
	 * @param string $template_path Template path. (default: '').
	 * @param string $default_path  Default path. (default: '').
	 *
	 * @return string
	 */
	function cbxmcratingreview_locate_template( $template_name, $template_path = '', $default_path = '' ) {
		if ( ! $template_path ) {
			$template_path = cbxmcratingreview_template_path();
		}

		if ( ! $default_path ) {
			$default_path = CBXMCRATINGREVIEW_ROOT_PATH . 'templates/';
		}

		// Look within passed path within the theme - this is priority.
		$template = locate_template(
			array(
				trailingslashit( $template_path ) . $template_name,
				$template_name,
			)
		);

		// Get default template/.
		if ( ! $template ) {
			$template = $default_path . $template_name;
		}

		// Return what we found.
		return apply_filters( 'cbxmcratingreview_locate_template', $template, $template_name, $template_path );
	}//end function cbxmcratingreview_locate_template

	/**
	 * Like wc_get_template, but returns the HTML instead of outputting.
	 *
	 * @see   wc_get_template
	 * @since 2.5.0
	 *
	 * @param string $template_name Template name.
	 * @param array  $args          Arguments. (default: array).
	 * @param string $template_path Template path. (default: '').
	 * @param string $default_path  Default path. (default: '').
	 *
	 * @return string
	 */
	function cbxmcratingreview_get_template_html( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
		ob_start();
		cbxmcratingreview_get_template( $template_name, $args, $template_path, $default_path );

		return ob_get_clean();
	}//end function cbxmcratingreview_get_template_html

	/**
	 * Get other templates (e.g. product attributes) passing attributes and including the file.
	 *
	 * @param string $template_name Template name.
	 * @param array  $args          Arguments. (default: array).
	 * @param string $template_path Template path. (default: '').
	 * @param string $default_path  Default path. (default: '').
	 */
	function cbxmcratingreview_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args ); // @codingStandardsIgnoreLine
		}

		$located = cbxmcratingreview_locate_template( $template_name, $template_path, $default_path );

		if ( ! file_exists( $located ) ) {
			/* translators: %s template */
			wc_doing_it_wrong( __FUNCTION__, sprintf( __( '%s does not exist.', 'cbxmcratingreview' ), '<code>' . $located . '</code>' ), '1.0.0' );

			return;
		}

		// Allow 3rd party plugin filter template file from their plugin.
		$located = apply_filters( 'cbxmcratingreview_get_template', $located, $template_name, $args, $template_path, $default_path );

		do_action( 'cbxmcratingreview_before_template_part', $template_name, $template_path, $located, $args );

		include $located;

		do_action( 'cbxmcratingreview_after_template_part', $template_name, $template_path, $located, $args );
	}//end function cbxmcratingreview_get_template