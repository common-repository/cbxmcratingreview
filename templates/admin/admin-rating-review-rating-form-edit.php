<?php
/**
 * Provide a dashboard rating form listing
 *
 * This file is used to markup the admin-facing rating form listing
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


$form_default_fields    = CBXMCRatingReviewHelper::form_default_fields();
$form_default_criterias = CBXMCRatingReviewHelper::form_default_criterias();
$form_default_questions = CBXMCRatingReviewHelper::form_default_questions();


$stored_values   = array();
$formSavableData = array();


//if saved then redirect from here
$errorMessageHtml = '';

$form_id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;

if ( isset( $_SESSION['cbxmcratingreview_form_validation_errors'][ $form_id ] ) && isset( $_GET['cbxupdated'] ) ) {
	$validation_errors = $_SESSION['cbxmcratingreview_form_validation_errors'][ $form_id ];
	//unset( $_SESSION['cbxmcratingreview_form_validation_errors'][$form_id]); //moved this to submit method, if successful then we will unset this.


	$formSavableData = isset( $validation_errors['formSavableData'] ) ? $validation_errors['formSavableData'] : array();


	if ( isset( $validation_errors['errorMessages'] ) ) {

		$errorMessages = array_filter( $validation_errors['errorMessages'] );
		//$errorMessages =  $validation_errors['errorMessages'];



		if ( is_array( $errorMessages ) && sizeof( $errorMessages ) > 0 ) {
			foreach ( $errorMessages as $key => $errorMessage ) {
				if ( is_array( $errorMessage ) && sizeof( $errorMessage ) > 0 ) {
					foreach ( $errorMessage as $error_key => $error_msg ) {
						$errorMessageHtml .= '<p class="error-message error-message-' . $error_key . '">' . $error_msg . '</p>';
					}
				} else {
					$errorMessageHtml .= '<p class="error-message error-message-' . $key . '">' . $errorMessage . '</p>';
				}
			}

			$errorMessageHtml = '<div id="messages" class="updated error">' . $errorMessageHtml . '</div>';
		}
	}

	unset( $_SESSION['cbxmcratingreview_form_validation_errors'][ $form_id ] );
}//end checking if there is validation error


if ( $form_id > 0 ) {
	$stored_values = CBXMCRatingReviewHelper::getRatingForm( $form_id, false );
	if ( is_array( $formSavableData ) && sizeof( $formSavableData ) > 0 ) {
		$stored_values = $formSavableData;
	}
} else {

	$stored_values['custom_criteria'] = $form_default_criterias;
	$stored_values['custom_question'] = $form_default_questions;

	if ( is_array( $formSavableData ) && sizeof( $formSavableData ) > 0 ) {
		$stored_values = $formSavableData;
	}
}
?>
<div class="wrap">
    <h1 class="wp-heading-inline">
		<?php echo sprintf( esc_html__( 'Rating Form Manager: Form ID - %d', 'cbxmcratingreview' ), $form_id ); ?>
    </h1>
	<?php
	if ( isset( $_GET['cbxupdated'] ) && intval( $_GET['cbxupdated'] ) == 1 && intval( $_GET['id'] ) > 0 ) {
		echo '<div id="messages" class="updated success"><p>' . sprintf( esc_html__( 'Form created/updated successfully. Form Id: %d', 'cbxmcratingreview' ), intval( $_GET['id'] ) ) . '</p></div>';
	} else {

		echo $errorMessageHtml;
	}
	?>

    <div id="poststuff">
        <div id="post-body" class="metabox-holder">
            <!-- main content -->
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <div class="postbox">
                        <div class="inside">
                            <form action="" method="post" id="cbxmcratingreview-ratingadminform" accept-charset="UTF-8">
								<?php

								$cbxmcratingreview_tabs = apply_filters( 'cbxmcratingreview_tabs', array(
									'cbxmcratingreview_general_setting'   => esc_html__( 'General Fields', 'cbxmcratingreview' ),
									'cbxmcratingreview_criteria_setting'  => esc_html__( 'Custom Criteria', 'cbxmcratingreview' ),
									'cbxmcratingreview_questions_setting' => esc_html__( 'Custom Questions', 'cbxmcratingreview' )
								) );


								$output = '';
								//building tab interface
								$output .= '<h2 class="nav-tab-wrapper" data-form-id="' . $form_id . '">';

								$tabcount = 0;
								foreach ( $cbxmcratingreview_tabs as $tab_key => $tab_title ) {
									$active_class = ( $tabcount < 1 ) ? ' nav-tab-active' : '';
									$output       .= sprintf( '<a href="#%1$s" class="nav-tab ' . $active_class . '" id="%1$s-tab">%2$s</a>', $tab_key, $tab_title );
									$tabcount ++;
								}
								$output .= '</h2>';
								//end tab interface

								$cbxmcratingreview_tabs_content_start = '';
								$cbxmcratingreview_tabs_content_end   = '';

								$output .= apply_filters( 'cbxmcratingreview_tabs_content_start', $cbxmcratingreview_tabs_content_start, $form_id );

								//general tab content
								$output .= '<div id="cbxmcratingreview_general_setting" class="ratingtabgroup">';
								$output .= '<table class="form-table"><tbody>';
								//general regular fields

								foreach ( $form_default_fields as $key => $item ) {
									if ( ! isset( $item['type'] ) ) {
										continue;
									}

									//field post_types_auto  will have the allowed post types for
									if ( $key == 'post_types_auto' ) {
										$post_types_saved = isset( $stored_values['post_types'] ) ? $stored_values['post_types'] : array();
										//if(sizeof($post_types_saved) > 0) $post_types_saved = array_combine($post_types_saved, $post_types_saved);
										$item['options'] = CBXMCRatingReviewHelper::post_types_filtered( $post_types_saved );
									}

									$desc      = isset( $item['desc'] ) ? $item['desc'] : '';
									$desc_html = ( $desc != '' ) ? '<p class="description">' . $desc . '</p>' : '';

									$multiple_field = isset( $item['multiple'] ) ? (bool) $item['multiple'] : false;
									$multiple_text  = ( $multiple_field ) ? ' multiple ' : '';
									$multiple_name  = ( $multiple_field ) ? '[]' : '';


									///ratingForm
									$extrafield = isset( $item['extrafield'] ) ? (bool) $item['extrafield'] : false;

									$name = ( $extrafield ) ? 'cbxmcratingreview_ratingForm[extrafields][' . $key . ']' : 'cbxmcratingreview_ratingForm[' . $key . ']';


									$savedvalue = isset( $stored_values[ $key ] ) ? $stored_values[ $key ] : $item['default'];

									/*if ( $item['type'] == 'select' && $multiple_field ) {
										$savedvalue = maybe_unserialize( $savedvalue );
									}*/

									$item['default'] = $savedvalue;

									$required_field = isset( $item['required'] ) ? (bool) $item['required'] : false;
									$required_text  = ( $required_field ) ? ' required ' : '';
									$required_class = ( $required_field ) ? ' required ' : '';

									$placeholder      = ( isset( $item['placeholder'] ) && $item['placeholder'] != '' ) ? ' placeholder="' . $item['placeholder'] . ' "' : '';
									$placeholder_text = ( isset( $item['placeholder'] ) && $item['placeholder'] != '' ) ? esc_html( $item['placeholder'] ) : '';


									if ( $item['type'] == 'text' ) {
										$output .= '<tr>
															<th scope="row"><label  for="cbxmcratingreview_' . $key . '">' . $item['label'] . '</label></th>
															<td><input ' . $required_text . $placeholder . ' name="' . $name . '" type="text" id="cbxmcratingreview_' . $key . '" value="' . $item['default'] . '" class="regular-text ' . $required_class . '"/>' . $desc_html . '</td>
														</tr>';
									} else if ( $item['type'] == 'textarea' ) {
										$output .= '<tr>
															<th scope="row"><label  for="cbxmcratingreview_' . $key . '">' . $item['label'] . '</label></th>
															<td><textarea ' . $required_text . $placeholder . ' name="' . $name . '" id="cbxmcratingreview_' . $key . '" class="regular-text ' . $required_class . '">' . $item['default'] . '</textarea>' . $desc_html . '</td>
														</tr>';
									} else if ( $item['type'] == 'number' ) {
										$output .= '<tr>
															<th scope="row"><label  for="cbxmcratingreview_' . $key . '">' . $item['label'] . '</label></th>
															<td><input ' . $required_text . $placeholder . ' name="' . $name . '" type="number" id="cbxmcratingreview_' . $key . '" value="' . $item['default'] . '" class="regular-text ' . $required_class . '"/></td>
														</tr>';
									} else if ( $item['type'] == 'radio' ) {
										$options = $item['options'];

										$radio_html = '';
										$radio_html .= '<tr>
																<th scope="row" >' . $item['label'] . '</th>
																<td>
																	<fieldset>
																		<legend class="screen-reader-text"><span>input type="radio"</span></legend>';
										foreach ( $options as $k => $v ) {
											$radio_html .= '<label title="' . $v . '"><input ' . checked( $item['default'], $k, $echo = false ) . ' type="radio" name="' . $name . '" value="' . $k . '" /> <span>' . $v . '</span></label><br />';
										}
										$radio_html .= '			</fieldset>' . $desc_html . '
																</td>
															</tr>';
										$output     .= $radio_html;
									} else if ( $item['type'] == "select" ) {
										$selectoutput = '<tr valign="top">
											<th scope="row"><label for="cbxmcratingreview_' . $key . '" >' . $item['label'] . '</label></th>
											<td>
												<select id="cbxmcratingreview_' . $key . '" ' . $multiple_text . ' data-placeholder="' . $placeholder_text . '" name="' . $name . $multiple_name . '" class="chosen-select ' . $required_class . '">';

										if ( isset( $item['options'] ) && is_array( $item['options'] ) && sizeof( $item['options'] ) > 0 ) {
											foreach ( $item['options'] as $option_key => $option_value ) {
												if ( is_array( $option_value ) && sizeof( $option_value ) > 0 ) {
													$selectoutput .= '<optgroup label="' . esc_attr( $option_key ) . '">';
													foreach ( $option_value as $option_index => $option_val ) {
														if ( $multiple_field ) {
															$selected = ( in_array( $option_index, $item['default'] ) ) ? 'selected="selected"' : '';
														} else {
															$selected = ( $option_index == $item['default'] ) ? 'selected="selected"' : '';
														}
														$selectoutput .= '<option ' . $selected . ' value="' . $option_index . '">' . esc_attr( $option_val ) . '</option>';
													}
													$selectoutput .= '</optgroup>';


												} else {

													if ( $multiple_field ) {
														$selected = ( in_array( $option_key, $item['default'] ) ) ? 'selected="selected"' : '';
													} else {
														$selected = ( $option_key == $item['default'] ) ? 'selected="selected"' : '';
													}
													$selectoutput .= '<option ' . $selected . ' value="' . $option_key . '">' . esc_attr( $option_value ) . '</option>';

												}

											}
										}


										$selectoutput .= '</select>' . $desc_html . '</td></tr>';
										$output       .= $selectoutput;
									} else if ( $item['type'] == 'hidden' ) {
										if ( $key == 'criteria_last_count' ) {
											//we will handle hidden field 'criteria_last_count' in different way
										} else if ( $key == 'question_last_count' ) {
											//we will handle hidden field 'criteria_last_count' in different way
										} else {
											$id     = isset( $item['id'] ) ? ' id="' . $item['id'] . '" ' : '';
											$output .= '<input type="hidden" name="' . $name . '" value="' . $item['default'] . '" ' . $id . ' />';
										}

									}

								}
								//end foreach

								$output .= '</tbody></table>';
								$output .= '</div>'; //#cbxmcratingreview_general_setting
								//end tab general setting


								//criteria tab content
								$output         .= '<div id="cbxmcratingreview_criteria_setting" class="ratingtabgroup">';
								$customCriteria = isset( $stored_values['custom_criteria'] ) ? maybe_unserialize( $stored_values['custom_criteria'] ) : array();



								$output         .= CBXMCRatingReviewAdminHelper::ratingFormEditRenderCriterias( $form_id, $stored_values, $form_default_fields, $customCriteria );
								$output         .= '</div>'; //#cbxmcratingreview_criteria_setting
								//criteria tab content end

								//question tab content
								$output         .= '<div id="cbxmcratingreview_questions_setting" class="ratingtabgroup">';
								$customQuestion = isset( $stored_values['custom_question'] ) ? maybe_unserialize( $stored_values['custom_question'] ) : array();



								$output .= CBXMCRatingReviewAdminHelper::ratingFormEditRenderQuestions( $form_id, $customQuestion, $stored_values );
								$output .= '</div>'; //#cbxmcratingreview_questions_setting
								//question tab content end

								$output .= apply_filters( 'cbxmcratingreview_tabs_content_end', $cbxmcratingreview_tabs_content_end, $form_id );


								$output .= wp_nonce_field( 'cbxmcratingreview_formedit', 'cbxmcratingreview_wpnonce' ) . '
										<div class="form-actions form-wrapper" id="edit-actions">
                           					 <input type="submit" id="edit-submit" class="button button-primary button-large" name="cbxmcratingreview_form_submit" value="' . esc_html__( 'Save', 'cbxmcratingreview' ) . '" class="form-submit">
                       					 </div>';

								$output .= '';

								echo $output;
								?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clear clearfix"></div>
    </div>
</div>