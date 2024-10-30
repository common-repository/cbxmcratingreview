<?php
/**
 * Provides frontend user dashboard
 *
 * This file is used to markup the frontend user dashboard html
 *
 * @link       https://codeboxr.com
 * @since      1.0.0
 *
 * @package    cbxmcratingreview
 * @subpackage cbxmcratingreview/templates
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<?php

do_action( 'cbxmcratingreview_user_dashboard_before' );

if ( is_user_logged_in() ) {
	if ( function_exists( 'cbxmcratingreview_ReviewsByUser' ) ) {
		$current_user = wp_get_current_user();
		$user_id      = $current_user->ID;


		$cbxitems_page = isset( $_GET['cbxrpaged'] ) ? intval( $_GET['cbxrpaged'] ) : 1;
		$user_reviews  = cbxmcratingreview_ReviewsByUser( $form_id, $user_id, $perpage, $cbxitems_page, '', $orderby, $order );


		if ( sizeof( $user_reviews ) == 0 ) {
			$user_dashboard_html .= '<div class="alert alert-info" role="alert">' . esc_html__( 'Sorry, no reviews found.', 'cbxmcratingreview' ) . '</div>';//
			echo  '<div class="alert alert-info" role="alert">' . esc_html__( 'Sorry, no reviews found.', 'cbxmcratingreview' ) . '</div>';
		}
		else {


			$single_review_view_id   = intval( $cbxmcratingreview_setting->get_option( 'single_review_view_id', 'cbxmcratingreview_tools', 0 ) );
			$single_review_edit_id   = intval( $cbxmcratingreview_setting->get_option( 'single_review_edit_id', 'cbxmcratingreview_tools', 0 ) );
			$review_userdashboard_id = intval( $cbxmcratingreview_setting->get_option( 'review_userdashboard_id', 'cbxmcratingreview_tools', 0 ) );
			$allow_review_delete     = intval( $cbxmcratingreview_setting->get_option( 'allow_review_delete', 'cbxmcratingreview_common_config', 1 ) );

			$single_review_view_url   = get_permalink( intval( $single_review_view_id ) );
			$single_review_edit_url   = get_permalink( intval( $single_review_edit_id ) );
			$review_userdashboard_url = get_permalink( intval( $review_userdashboard_id ) );

			$review_statuses = CBXMCRatingReviewHelper::ReviewStatusOptions();


			$reviewsTotal = cbxmcratingreview_totalReviewsCountByUser( $form_id, $user_id, '', '' );
			?>

            <h2 class="h4"><?php esc_html_e( 'Your Reviews & Ratings', 'cbxmcratingreview' ); ?></h2>

            <p>
                <strong><?php esc_html_e( 'Total Reviews' ); ?></strong>: <?php echo intval( $reviewsTotal ); ?>
            </p>
            <div class="table-responsive">
                <table class="table table-hover table-bordered table-striped">
                    <thead>
                    <tr>
                        <th scope="col"><?php esc_html_e( 'Post', 'cbxmcratingreview' ); ?></th>
                        <th scope="col"><?php esc_html_e( 'Rating', 'cbxmcratingreview' ) ?></th>
                        <th scope="col"><?php esc_html_e( 'Headline', 'cbxmcratingreview' ); ?></th>
                        <th scope="col"><?php esc_html_e( 'Comment', 'cbxmcratingreview' ); ?></th>
                        <th scope="col"><?php esc_html_e( 'Status', 'cbxmcratingreview' ); ?></th>
                        <th scope="col"><?php esc_html_e( 'Action', 'cbxmcratingreview' ); ?></th>
                    </tr>
                    </thead>
                    <tbody>

					<?php
					foreach ( $user_reviews as $user_review ) {

						$extraparams = maybe_unserialize( $user_review['extraparams'] );
						$status_key  = $user_review['status'];

						?>
                        <tr>
                            <th scope="row">
								<?php
								$post_id    = intval( $user_review['post_id'] );
								$post_title = get_the_title( $post_id );
								$post_title = ( $post_title == '' ) ? esc_html__( 'Untitled Article', 'cbxmcratingreview' ) : $post_title;
								$post_link  = get_permalink( $post_id );

								echo '<a target="_blank" href="' . esc_url( $post_link ) . '" target="_blank">' . $post_title . '</a>';
								?>

                            </th>
                            <td>
								<?php
								echo number_format_i18n( floatval( $user_review['score'] ), 1 );
								?>
                            </td>

                            <td>
								<?php
								$headline = wp_unslash( $user_review['headline'] );
								if ( strlen( $headline ) > 25 ) {
									$headline = substr( $headline, 0, 25 ) . '...';
								}

								$headline = ( $headline != '' ) ? $headline : esc_html__( 'View Review', 'cbxmcratingreview' );

								if ( $status_key == 1 && function_exists( 'cbxmcratingreview_review_permalink' ) ) {
									$review_view_link = cbxmcratingreview_review_permalink( $user_review['id'] );
									echo '<a title="' . esc_html__( 'View Review', 'cbxmcratingreview' ) . '" target="_blank" href="' . esc_url( $review_view_link ) . '" target="_blank">' . esc_html( $headline ) . '</a>';
								} else {
									echo $headline;
								}
								?>
                            </td>
                            <td>
								<?php
								$comment = wp_unslash( $user_review['comment'] );
								if ( strlen( $comment ) > 25 ) {
									$comment = substr( $comment, 0, 25 ) . '...';
								}

								echo $comment;

								?>
                            </td>
                            <td>
								<?php
								$status_key = intval( $user_review['status'] );
								echo isset( $review_statuses[ $status_key ] ) ? $review_statuses[ $status_key ] : esc_html__( 'Unknown', 'cbxmcratingreview' );
								?>

                            </td>


                            <td>
								<?php
								$status_key = intval( $user_review['status'] );


								//only pending and published can be edited
								if ( $status_key == 0 || $status_key == 1 ) {

									$review_edit_link = add_query_arg( 'review_id', $user_review['id'], $single_review_edit_url );
									echo '  <a href="' . esc_url( $review_edit_link ) . '" target="_blank">' . esc_html__( 'Edit', 'cbxmcratingreview' ) . '</a>';
								}

								if ( intval( $allow_review_delete ) == 1 ) {
									//allow review delete
								}

								//only published can be viewed
								/*if ( $status_key == 1 ) {

									$review_view_link = add_query_arg( 'review_id', $user_review['id'], $single_review_view_url );
									echo '  <a href="' . esc_url( $review_edit_link ) . '" target="_blank">' . esc_html__( 'View', 'cbxmcratingreview' ) . '</a>';
								}*/

								?>
                            </td>
                        </tr>
						<?php
					}
					?>


                    </tbody>
                </table>
            </div>

			<?php


			if ( function_exists( 'cbxmcratingreview_paginate_links_as_bootstrap' ) ) {
				echo cbxmcratingreview_paginate_links_as_bootstrap( array(
					'format'    => '?cbxrpaged=%#%',
					'total'     => ceil( $reviewsTotal / $perpage ),
					'current'   => max( 1, $cbxitems_page ),
					'nav_class' => 'postnav',

				) );
			}
		}

	}
} else {
	$login_url = wp_login_url();
	if ( is_singular() ) {
		$login_url = wp_login_url( get_permalink() );;
	} else {
		global $wp;

		//$login_url =  wp_login_url( home_url( $wp->request ) );;
		$login_url = wp_login_url( home_url( add_query_arg( array(), $wp->request ) ) );;
	}

	echo '<p><a href="' . esc_url( apply_filters( 'cbxmcratingreview_user_dashboard_login_url', $login_url ) ) . '">' . esc_html__( 'Please login to access your ratings & reviews dashboard', 'cbxmcratingreview' ) . '</a></p>';
}

do_action( 'cbxmcratingreview_user_dashboard_after' );
