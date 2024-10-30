<?php
/**
 * Provide a dashboard rating log view
 *
 * This file is used to markup the admin-facing rating log view
 *
 * @link       https://codeboxr.com
 * @since      1.0.7
 *
 * @package    cbxmcratingreview
 * @subpackage cbxmcratingreview/templates/admin
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<?php
global $wpdb;
$log_id = ( isset( $_GET['id'] ) && intval( $_GET['id'] ) > 0 ) ? intval( $_GET['id'] ) : 0;

$cbxmcratingreview_setting = new CBXMCRatingReviewSettings();
//$require_headline          = intval( $cbxmcratingreview_setting->get_option( 'require_headline', 'cbxmcratingreview_common_config', 1 ) );
//$require_comment           = intval( $cbxmcratingreview_setting->get_option( 'require_comment', 'cbxmcratingreview_common_config', 1 ) );


?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
    <div id="cbxmcratingreviewloading" style="display:none"></div>
    <h2>
		<?php esc_html_e( 'Review Log Readonly View', 'cbxmcratingreview' ); ?>

    </h2>

    <div id="poststuff">

        <div id="post-body" class="metabox-holder">

            <!-- main content -->
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">


                    <div class="postbox">
                        <div class="inside cbxmcratingreview-review-readonly-section">

							<?php

							$review_info = null;
							if ( $log_id > 0 ) {
								$review_info = cbxmcratingreview_singleReview( $log_id );

							}

							if ( ! is_null( $review_info ) ): ?>

								<?php
								$form_id   = isset( $review_info['form_id'] ) ? intval( $review_info['form_id'] ) : 0;
								$post_id   = isset( $review_info['post_id'] ) ? intval( $review_info['post_id'] ) : 0;
								$review_id = isset( $review_info['id'] ) ? intval( $review_info['id'] ) : 0;
								$score     = isset( $review_info['score'] ) ? floatval( $review_info['score'] ) : 0;
								$headline  = isset( $review_info['headline'] ) ? wp_unslash( $review_info['headline'] ) : '';
								$comment   = isset( $review_info['comment'] ) ? wp_unslash( $review_info['comment'] ) : '';


								$date_created = date_format( date_create( $review_info['date_created'] ), 'Y-m-d' );

								$attachment = isset( $review_info['attachment'] ) ? maybe_unserialize( $review_info['attachment'] ) : array();

								$status = isset( $review_info['status'] ) ? $review_info['status'] : '';

								$exprev_status_arr = CBXMCRatingReviewHelper::ReviewStatusOptions();
								if ( isset( $exprev_status_arr[ $status ] ) ) {
									$status = $exprev_status_arr[ $status ];
								}

								$mod_by = intval( $review_info['mod_by'] );

								if ( $mod_by > 0 ) {
									$mod_by_userdata = get_userdata( $mod_by );
									$date_modified   = date_format( date_create( $review_info['date_modified'] ), 'Y-m-d' );
								}


								$form = CBXMCRatingReviewHelper::getRatingForm( $form_id );
								$form_question_formats = CBXMCRatingReviewHelper::form_question_formats();

								$questions = maybe_unserialize( $review_info['questions'] );
								$ratings   = maybe_unserialize( $review_info['ratings'] );
								$ratings_stars = isset($ratings['ratings_stars'])?  $ratings['ratings_stars']: array();

								$enable_question = isset( $form['enable_question'] ) ? intval( $form['enable_question'] ) : 0;


								$post_link  = get_permalink( intval( $review_info['post_id'] ) );
								$post_title = get_the_title( intval( $review_info['post_id'] ) );
								$post_title = ( $post_title == '' ) ? esc_html__( 'Untitled article', 'cbxmcratingreview' ) : $post_title;


								do_action( 'cbxmcratingreview_review_rating_adminviewform_before', $form_id, $post_id, $log_id, $review_info );
								?>
                                <table class="widefat">
									<?php
									do_action( 'cbxmcratingreview_review_rating_adminviewform_start', $form_id, $post_id, $log_id, $review_info );
									?>
                                    <tr valign="top">
                                        <th class="row-title lowpadding_th" scope="row">
											<?php esc_html_e( 'Written By', 'cbxmcratingreview' ); ?>
                                        </th>

                                        <td class="lowpadding_th">
                                            <a target="_blank"
                                               href="<?php echo esc_url( get_edit_user_link( intval( $review_info['user_id'] ) ) ) ?>"><?php esc_html_e( $review_info['display_name'] ); ?></a>
                                        </td>
                                    </tr>
                                    <tr valign="top" class="alternate">
                                        <th class="row-title lowpadding_th" scope="row">
                                            <label for="tablecell"><?php esc_html_e( 'Created', 'cbxmcratingreview' ); ?></label>
                                        </th>

                                        <td class="lowpadding_th">
											<?php esc_attr_e( $date_created ); ?>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th class="row-title lowpadding_th" scope="row">
											<?php esc_html_e( 'Post', 'cbxmcratingreview' ); ?>
                                        </th>

                                        <td class="lowpadding_th">
                                            <a target="_blank"
                                               href="<?php echo esc_url( $post_link ); ?>"><?php echo esc_html( $post_title ); ?></a>
                                        </td>
                                    </tr>
                                    <tr valign="top" class="alternate">
                                        <th class="row-title lowpadding_th" scope="row">
											<?php esc_html_e( 'Criteria Average', 'cbxmcratingreview' ); ?>
                                        </th>

                                        <td class="lowpadding_th">
                                            <div class="cbxmcratingreview_readonlyrating_score_js" data-processed="0"
                                                 data-score="<?php echo $score; ?>"></div>
                                        </td>
                                    </tr>
									<tr>
										<td colspan="2">
											<?php
												if ( isset( $form['custom_criteria'] ) && is_array( $form['custom_criteria'] ) && sizeof( $form['custom_criteria'] ) > 0 ) {
													$custom_criterias = isset( $form['custom_criteria'] ) ? $form['custom_criteria'] : array();

													echo '<ul class="cbxmcratingreview_review_readonly_criterias">';
													foreach ( $custom_criterias as $custom_index => $custom_criteria ) {
														$enabled = isset( $custom_criteria['enabled'] ) ? intval( $custom_criteria['enabled'] ) : 0;
														//if ( $enabled ) {


															$criteria_id = isset( $custom_criteria['criteria_id'] ) ? intval( $custom_criteria['criteria_id'] ) : intval( $custom_index );
															$label       = isset( $custom_criteria['label'] ) ? esc_attr( $custom_criteria['label'] ) : sprintf( esc_html__( 'Criteria %d' ), $criteria_index );

															$stars_formatted = is_array( $custom_criteria['stars_formatted'] ) ? $custom_criteria['stars_formatted'] : array();

															$stars_length = isset( $stars_formatted['length'] ) ? intval( $stars_formatted['length'] ) : 0;
															$stars_hints  = isset( $stars_formatted['stars'] ) ? $stars_formatted['stars'] : array();


															$rating       = isset( $ratings_stars[ $criteria_id ] ) ? $ratings_stars[ $criteria_id ] : array();
															$rating_score = isset( $rating['score'] ) ? $rating['score'] : 0;
															//$star_id      = isset( $rating['star_id'] ) ? $rating['star_id'] : '';


															/*if(!isset($stars_hints[$star_id]))  {
																$rating_score = 0;
															}*/

															echo '<li class="cbxmcratingreview_review_readonly_criteria" data-criteria_id="' . $criteria_id . '">';
															echo '<p>' . esc_attr( $label ) . '('.number_format_i18n($rating_score, 1).'/'.number_format_i18n($stars_length).')</p>';
															echo '<div data-processed="0" class="cbxmcratingreview_readonlyrating_score_js" data-score="'.$rating_score.'"  data-hints=\'' . json_encode( array_values( $stars_hints ) ) . '\'></div>';
														//}
														echo '</li>';
													}
													echo '</ul>';

												}

											?>
										</td>
									</tr>
                                    <tr valign="top">
                                        <th class="row-title lowpadding_th" scope="row">
											<?php esc_html_e( 'Review Headline', 'cbxmcratingreview' ); ?>
                                        </th>
                                        <td class="lowpadding_th"><?php echo $headline; ?></td>
                                    </tr>
                                    <tr valign="top" class="alternate">
                                        <th class="row-title lowpadding_th" scope="row">
											<?php esc_html_e( 'Comment', 'cbxmcratingreview' ); ?>
                                        </th>
                                        <td class="lowpadding_th"><?php echo $comment; ?></td>
                                    </tr>
									<?php if($enable_question): ?>
									<tr>
										<td colspan="2">
											<div class="cbxmcratingreview_review_custom_questions">
												<?php
													if ( isset( $form['custom_question'] ) && is_array( $form['custom_question'] ) && sizeof( $form['custom_question'] ) > 0 ) {

														$customQuestion = $form['custom_question'];

														echo '<h3>' . esc_html__( 'Questions and Answers', 'cbxmcratingreview' ) . '</h3>';

														foreach ( $customQuestion as $question_index => $question ) {

															$field_type = isset( $question['type'] ) ? $question['type'] : '';
															$enabled    = isset( $question['enabled'] ) ? intval( $question['enabled'] ) : 0;

															if ( $field_type == '' || ( $enabled == 0 ) ) {
																continue;
															} //if the field type is not proper then move for next item in loop

															$user_answer = isset($questions[$question_index])? $questions[$question_index]: '';


															echo '<div class="cbxmcratingreview-form-field cbxmcratingreview_review_custom_question cbxmcratingreview_review_custom_question_' . $field_type . '" id="cbxmcratingreview_review_custom_question_' . intval( $question_index ) . '">';

															$form_question_format = $form_question_formats[ $field_type ];
															$question_render      = $form_question_format['answer_renderer'];

															if ( is_callable( $question_render ) ) {
																echo call_user_func( $question_render, $question_index, $question, $user_answer );
															}

															echo '</div>';
														}
													}
												?>

											</div>
										</td>
									</tr>
									<?php endif; ?>

                                    <tr valign="top">
                                        <th class="row-title lowpadding_th" scope="row">
											<?php esc_html_e( 'Status', 'cbxmcratingreview' ); ?>
                                        </th>
                                        <td class="lowpadding_th"><?php echo $status; ?></td>
                                    </tr>
									<?php
									if ( $mod_by > 0 ):

										?>

                                        <tr valign="top" class="alternate">
                                            <th class="row-title lowpadding_th" scope="row">
                                                <label for="tablecell"><?php esc_html_e( 'Last Update', 'cbxmcratingreview' ); ?></label>
                                            </th>
                                            <td class="lowpadding_th">
												<?php esc_attr_e( $date_modified ); ?>
                                            </td>
                                        </tr>
                                        <tr class="alternate">
                                            <th class="row-title lowpadding_th" scope="row">
                                                <label for="tablecell"><?php esc_html_e( 'Last Update By', 'cbxmcratingreview' ); ?></label>
                                            </th>
                                            <td class="lowpadding_th">
                                                <a target="_blank"
                                                   href="<?php echo esc_url( get_edit_user_link( $mod_by ) ) ?>"><?php esc_attr_e( $mod_by_userdata->display_name ); ?></a>
                                            </td>
                                        </tr>
									<?php endif; ?>
									<?php
									do_action( 'cbxmcratingreview_review_rating_adminviewform_end', $form_id, $post_id, $log_id, $review_info );
									?>

                                </table>
								<?php
								do_action( 'cbxmcratingreview_review_rating_adminviewform_after', $form_id, $post_id, $log_id, $review_info );
								?>

                                <p>
                                    <a class="button-primary"
                                       title="<?php esc_html_e( 'Edit Review', 'cbxmcratingreview' ); ?>"
                                       href="<?php echo admin_url( 'admin.php?page=cbxmcratingreviewreviewlist&view=addedit&id=' . $log_id ); ?>"><?php esc_html_e( 'Edit Review', 'cbxmcratingreview' ); ?></a>
									<?php echo '<a class="button button-secondary button-large" href="' . admin_url( 'admin.php?page=cbxmcratingreviewreviewlist' ) . '">' . esc_attr__( 'Back to Review Lists', 'cbxmcratingreview' ) . '</a>'; ?>
                                </p>
							<?php else :
								echo '<div class="notice notice-error inline"><p>' . esc_html__( 'No data Found', 'cbxmcratingreview' ) . '</p></div>';
								?>
							<?php endif; ?>
                        </div> <!-- .inside -->
                    </div> <!-- .postbox -->
                </div> <!-- .meta-box-sortables .ui-sortable -->
            </div> <!-- post-body-content -->
        </div> <!-- #post-body .metabox-holder -->
        <div class="clear clearfix"></div>
    </div> <!-- #poststuff -->
</div> <!-- .wrap -->