<?php
	/**
	 * Provide a dashboard rating log edit
	 *
	 * This file is used to markup the admin-facing rating log edit
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


	$show_headline    = intval( $cbxmcratingreview_setting->get_option( 'show_headline', 'cbxmcratingreview_common_config', 1 ) );
	$show_comment     = intval( $cbxmcratingreview_setting->get_option( 'show_comment', 'cbxmcratingreview_common_config', 1 ) );

	$require_headline = intval( $cbxmcratingreview_setting->get_option( 'require_headline', 'cbxmcratingreview_common_config', 1 ) );
	$require_comment  = intval( $cbxmcratingreview_setting->get_option( 'require_comment', 'cbxmcratingreview_common_config', 1 ) );


?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
	<h2>
		<?php echo sprintf( esc_html__( 'Review Log ID: %d', 'cbxmcratingreview' ), $log_id ); ?>
	</h2>
	<p><?php echo '<a class="button button-primary button-large" href="' . admin_url( 'admin.php?page=cbxmcratingreviewreviewlist' ) . '">' . esc_attr__( 'Back to Review Lists', 'cbxmcratingreview' ) . '</a>'; ?></p>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder">

			<!-- main content -->
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">
					<div class="postbox">
						<h3><span><?php esc_html_e( 'Edit Review', 'cbxmcratingreview' ); ?></span></h3>
						<div class="inside">
							<?php
								$review_info = null;
								if ( $log_id > 0 ) {
									$review_info = cbxmcratingreview_singleReview( $log_id );
									if ( ! is_null( $review_info ) ) {

										$attachment_info = isset( $review_info['attachment'] ) ? maybe_unserialize( $review_info['attachment'] ) : array();

										$post_id  = isset( $review_info['post_id'] ) ? intval( $review_info['post_id'] ) : 0;
										$form_id  = isset( $review_info['form_id'] ) ? intval( $review_info['form_id'] ) : 0;
										//$score    = isset( $review_info['score'] ) ? $review_info['score'] : '';
										$headline = isset( $review_info['headline'] ) ? wp_unslash( $review_info['headline'] ) : '';
										$comment  = isset( $review_info['comment'] ) ? wp_unslash( $review_info['comment'] ) : '';
										$status   = isset( $review_info['status'] ) ? $review_info['status'] : '';

										$status_arr = CBXMCRatingReviewHelper::ReviewStatusOptions();
										$mod_by     = intval( $review_info['mod_by'] );
										if ( $mod_by > 0 ) {
											$mod_by_userdata = get_userdata( $mod_by );
											$date_modified   = date_format( date_create( $review_info['date_modified'] ), 'Y-m-d' );
										}

										$date_created = date_format( date_create( $review_info['date_created'] ), 'Y-m-d' );


										$form = CBXMCRatingReviewHelper::getRatingForm( $form_id );

										$enable_question = isset( $form['enable_question'] ) ? intval( $form['enable_question'] ) : 0;
										$questions = maybe_unserialize( $review_info['questions'] );
										$ratings   = maybe_unserialize( $review_info['ratings'] );
										$ratings_stars = isset($ratings['ratings_stars'])?  $ratings['ratings_stars']: array();

									}//end review information
								}
							?>
							<?php if ( ! is_null( $review_info ) ) :

								$enable_question = isset( $form['enable_question'] ) ? intval( $form['enable_question'] ) : 0;
								$form_question_formats = CBXMCRatingReviewHelper::form_question_formats();
								?>
								<div class="cbxmcratingreviewmainwrap" id="cbxmcratingreviewmainwrap">
									<div class="cbxmcratingreview-form-section">
										<div class="cbxmcratingreview_global_msg"></div>
										<?php
											do_action( 'cbxmcratingreview_review_rating_admineditform_before', $form_id, $post_id, $log_id, $review_info );
										?>
										<form class="cbxmcratingreview-form" method="post" enctype="multipart/form-data"
											  data-busy="0" data-form_id="<?php echo intval( $form_id ); ?>" data-postid="<?php echo intval( $post_id ); ?>">

											<table class="widefat">
												<tbody>
												<tr>
													<td class="row-title">
														<label for="tablecell"><?php esc_html_e( 'Reviewed By', 'cbxmcratingreview' ); ?></label>
													</td>
													<td>
														<a target="_blank"
														   href="<?php echo esc_url( get_edit_user_link( intval( $review_info['user_id'] ) ) ) ?>"><?php esc_html_e( $review_info['display_name'] ); ?></a>
													</td>
												</tr>
												<tr class="alternate">
													<td class="row-title">
														<label for="tablecell"><?php esc_html_e( 'Created', 'cbxmcratingreview' ); ?></label>
													</td>
													<td>
														<?php esc_attr_e( $date_created ); ?>
													</td>
												</tr>
												<tr>
													<td class="row-title">
														<label for="tablecell"><?php esc_html_e( 'Post', 'cbxmcratingreview' ); ?></label>
													</td>
													<td>
														<a target="_blank"
														   href="<?php echo esc_url( get_permalink( intval( $review_info['post_id'] ) ) ) ?>"><?php esc_attr_e( get_the_title( intval( $review_info['post_id'] ) ) ); ?></a>
													</td>
												</tr>
												<?php
													if ( $mod_by > 0 ): ?>
														<tr class="alternate">
															<td class="row-title">
																<label for="tablecell"><?php esc_html_e( 'Last Update', 'cbxmcratingreview' ); ?></label>
															</td>
															<td>
																<?php esc_attr_e( $date_modified ); ?>
															</td>
														</tr>
														<tr class="alternate">
															<td class="row-title">
																<label for="tablecell"><?php esc_html_e( 'Last Update By', 'cbxmcratingreview' ); ?></label>
															</td>
															<td>
																<a target="_blank"
																   href="<?php echo esc_url( get_edit_user_link( $mod_by ) ) ?>"><?php esc_attr_e( $mod_by_userdata->display_name ); ?></a>
															</td>
														</tr>
													<?php endif; ?>

												</tbody>
											</table>
											<br />
											<?php
												do_action( 'cbxmcratingreview_review_rating_admineditform_start', $form_id, $post_id, $log_id, $review_info );
											?>

											<div class="cbxmcratingreview-form-field">
												<label class="cbxmcratingreview-form-field-urrating"><?php esc_html_e( 'Edit Your Rating Experience', 'cbxmcratingreview' ); ?></label>
												<?php
													if ( isset( $form['custom_criteria'] ) && is_array( $form['custom_criteria'] ) && sizeof( $form['custom_criteria'] ) > 0 ) {
														$custom_criterias = isset( $form['custom_criteria'] ) ? $form['custom_criteria'] : array();

														echo '<ul class="cbxmcratingreview_review_custom_criterias">';
														foreach ( $custom_criterias as $custom_index => $custom_criteria ) {
															//$enabled = isset( $custom_criteria['enabled'] ) ? intval( $custom_criteria['enabled'] ) : 0;
															//if ( $enabled ) {


																$criteria_id = isset( $custom_criteria['criteria_id'] ) ? intval( $custom_criteria['criteria_id'] ) : intval( $custom_index );
																$label       = isset( $custom_criteria['label'] ) ? esc_attr( $custom_criteria['label'] ) : sprintf( esc_html__( 'Criteria %d' ), $criteria_index );

																$stars_formatted = is_array( $custom_criteria['stars_formatted'] ) ? $custom_criteria['stars_formatted'] : array();

																$stars_length = isset( $stars_formatted['length'] ) ? intval( $stars_formatted['length'] ) : 0;
																$stars_hints  = isset( $stars_formatted['stars'] ) ? $stars_formatted['stars'] : array();


																$rating       = isset( $ratings_stars[ $criteria_id ] ) ? $ratings_stars[ $criteria_id ] : array();
																$rating_score = isset( $rating['score'] ) ? $rating['score'] : 0;
																//$star_id      = isset( $rating['star_id'] ) ? $rating['star_id'] : '';



																echo '<li class="cbxmcratingreview_review_custom_criteria" data-criteria_id="' . $criteria_id . '">';
																echo '<p>' . esc_attr( $label ) . '</p>';


																/*if(!isset($stars_hints[$star_id]))  {
																	$rating_score = 0;
																}*/


																echo '<div class="cbxmcratingreview_rating_trigger" data-score="'.$rating_score.'" data-number="' . intval( $stars_length ) . '" data-hints=\'' . json_encode( array_values( $stars_hints ) ) . '\'></div>';
																echo '<input type="hidden" name="cbxmcratingreview_ratingForm[ratings][' . $criteria_id . ']" class="cbxmcratingreview-form-field-input cbxmcratingreview-form-field-input-hidden cbxmcratingreview_rating_score" value="'.$rating_score.'" required  data-rule-required="true" data-rule-min="0.5" data-rule-max="' . intval( $stars_length ) . '" data-msg-required="' . esc_html__( 'Rating missing!', 'cbxmcratingreview' ) . '" />';
															//}
															echo '</li>';
														}
														echo '</ul>';

													}

												?>
											</div>
											<div class="cbxmcratingreview-form-field">
												<label for="cbxmcratingreview_review_headline"
													   class=""><?php esc_html_e( 'Review Headline', 'cbxmcratingreview' ); ?></label>
												<input type="text" name="cbxmcratingreview_ratingForm[headline]" id="cbxmcratingreview_review_headline"
													   class="cbxmcratingreview-form-field-input cbxmcratingreview-form-field-input-text cbxmcratingreview_review_headline" <?php echo ( $require_headline && $show_headline ) ? 'required' : ''; ?>
													   data-rule-minlength="2"
													   placeholder="<?php esc_html_e( 'One line review', 'cbxmcratingreview' ); ?>"
													   value="<?php echo esc_attr( $headline ); ?>" />
											</div>
											<div class="cbxmcratingreview-form-field">
												<label for="cbxmcratingreview_review_comment"
													   class=""><?php esc_html_e( 'Your Review', 'cbxmcratingreview' ); ?></label>

												<?php wp_editor( $comment, 'cbxmcratingreview_review_comment', $settings = array(
													'teeny'         => true,
													'media_buttons' => false,
													'textarea_name' => 'cbxmcratingreview_ratingForm[comment]',
													'editor_class'  => 'cbxmcratingreview-form-field-input cbxmcratingreview-form-field-input-vtextarea cbxmcratingreview_review_comment',
													'textarea_rows' => 8,
													'quicktags'     => false,
													'tinymce'       => array(
														'init_instance_callback' => 'function(editor) {
							editor.on("change", function(){
								tinymce.triggerSave();
								jQuery("#" + editor.id).valid();
						    });
						}'
													)
												) ); ?>
											</div>

											<?php if($enable_question): ?>
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
															$question_render      = $form_question_format['public_renderer'];

															if ( is_callable( $question_render ) ) {
																echo call_user_func( $question_render, $question_index, $question, $user_answer );
															}

															echo '</div>';
														}
													}
												?>

											</div>
											<?php endif; ?>

											<?php
												do_action( 'cbxmcratingreview_review_rating_admineditform_end', $form_id, $post_id, $log_id, $review_info );
											?>
											<div class="cbxmcratingreview-form-field">

												<label for="cbxmcratingreview_review_status"><?php esc_html_e( 'Status', 'cbxmcratingreview' ); ?></label>

												<select name="cbxmcratingreview_ratingForm[status]">
													<?php
														foreach ( $status_arr as $status_key => $status_name ) { ?>
															<option value="<?php echo $status_key; ?>" <?php if ( $status_key == $status ) {
																echo 'selected="selected"';
															} ?>><?php echo $status_name; ?></option>
														<?php } ?>
												</select>

											</div>
											<input type="hidden" id="cbxmcratingreview-form-id" name="cbxmcratingreview_ratingForm[form_id]"
												   value="<?php echo intval( $form_id ); ?>" />
											<input type="hidden" id="cbxmcratingreview-post-id" name="cbxmcratingreview_ratingForm[post_id]"
												   value="<?php echo $post_id; ?>" />
											<input type="hidden" id="cbxmcratingreview-review-id" name="cbxmcratingreview_ratingForm[log_id]"
												   value="<?php echo $log_id; ?>" />
											<p class="label-cbxmcratingreview-submit-processing"
											   style="display: none;"><?php esc_html_e( 'Please wait, we are taking account of your review. Do not close this window.', 'cbxmcratingreview' ) ?></p>
											<button type="submit"
													class="btn btn-primary button button-primary btn-cbxmcratingreview-submit"><?php esc_html_e( 'Submit Edit', 'cbxmcratingreview' ); ?></button>

										</form>
										<?php
											do_action( 'cbxmcratingreview_review_rating_admineditform_after', $form_id, $post_id, $log_id, $review_info );
										?>
									</div>
								</div>
							<?php else :
								echo '<div class="notice notice-error inline"><p>' . esc_html__( 'Sorry No data Found', 'cbxmcratingreview' ) . '</p></div>';
								?>
							<?php endif; ?>
						</div> <!-- .inside -->
					</div>
				</div>
			</div>
		</div>
		<div class="clear clearfix"></div>
	</div>
</div>