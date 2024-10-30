<?php
/**
 * The helper functionality of the plugin admin sides
 *
 * @link       https://codeboxr.com
 * @since      1.0.0
 *
 * @package    CBXMCRatingReview
 * @subpackage CBXMCRatingReview/includes
 */


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<?php

class CBXMCRatingReviewAdminHelper {
	/**
	 * Render rating form edit - Render questions
	 *
	 * @param int $form_id
	 * @param array $customQuestion
	 * @param bool $stored_values
	 *
	 * @return string
	 */
	public static function ratingFormEditRenderQuestions( $form_id = 0, $customQuestion = array(), $stored_values = array() ) {
		if ( ! is_array( $customQuestion ) ) {
			$customQuestion = array();
		}

		$qs_count = sizeof( $customQuestion );

		$output = '<h3>' . esc_html__( 'Questions', 'cbxmcratingreview' ) . '</h3>';
		$output .= '<p>' . esc_html__( 'Questions are optional. Each question can be set enabled or disabled, required. Multiple types of questions including Radio, Checkbox, Text, Textarea and more.', 'cbxmcratingreview' ) . '</p>';

		$question_last_count = isset( $stored_values['question_last_count'] ) ? absint( $stored_values['question_last_count'] ) : $qs_count;


		$output .= '<input id="question_last_count" type="hidden" name="cbxmcratingreview_ratingForm[extrafields][question_last_count]" value="' . $question_last_count . '" />';

		$output .= '
        <div class="form-item" id="custom-question">            
            <div class="edit-custom-question-fields-wrapper custom-question-table">                
                <div class="custom-question-table-row custom-question-table-row-header">
                    <div class="custom-question-table-col custom-question-table-col-header" style="width: 20%;">' . esc_html__( 'Question Title(Click to Edit)', 'cbxmcratingreview' ) . '</div>
                    <div class="custom-question-table-col custom-question-table-col-header" style="width:20%;">' . esc_html__( 'Controls', 'cbxmcratingreview' ) . '</div>
                    <div class="custom-question-table-col custom-question-table-col-header" style="width:10%;">' . esc_html__( 'Field Type', 'cbxmcratingreview' ) . '</div>
                    <div class="custom-question-table-col custom-question-table-col-header" style="width: 45%;">' . esc_html__( 'Field Preview (Click to Edit)', 'cbxmcratingreview' ) . '</div>
                    <div class="custom-question-table-col custom-question-table-col-header" style="width: 5%;">' . esc_html__( 'Actions', 'cbxmcratingreview' ) . '</div>
                    <div style="clear:both;"></div>
                </div>
                <div style="clear:both;"></div>';

		//for each questions
		foreach ( $customQuestion as $question_index => $question ) {

			$field_type = isset( $question['type'] ) ? $question['type'] : '';

			if ( $field_type == '' ) {
				continue;
			} //if the field type is not proper then move for next item in loop

			$output .= CBXMCRatingReviewHelper::ratingFormEditRenderQuestion( $question_index, $question, $stored_values );
		}//end for each question

		$output .= '</div>'; //.custom-question-table
		//
		$question_msg = '<p>' . esc_html__( 'Info: Unlimited Question available in pro version.', 'cbxmcratingreview' ) . '</p>';
		$question_msg = apply_filters( 'cbxmcratingreview_add_more_question', $question_msg, $qs_count, $form_id, $customQuestion, $stored_values );

		$output .= $question_msg;
		$output .= '</div>'; // #custom-question

		return $output;
	}//end method ratingFormEditRenderQuestions


	/**
	 * Render rating form edit - Render Criteria wrapper
	 *
	 * @param int $form_id
	 * @param array $stored_values
	 * @param array $form_default_fields
	 * @param array $customCriteria
	 *
	 * @return string
	 */
	public static function ratingFormEditRenderCriterias( $form_id = 0, $stored_values = array(), $form_default_fields = array(), $customCriteria = array() ) {

		//note: index and id is different thing here, index is generic loop index, id is used for further reference

		$criteria_wrapper_before      = '';
		$criteria_wrapper_after       = '';
		$criteria_wrapper_loop_before = '';
		$criteria_wrapper_loop_after  = '';

		$output = '';
		$output .=
			'<div class="cb-ratingForm-edit-custom-criteria-container form-item" id="custom-criteria">
                <h3>' . esc_html__( 'Custom Criterias', 'cbxmcratingreview' ) . ' <span class="form-required" title="' . esc_html__( 'This field is required', 'cbxmcratingreview' ) . '">*</span></h3>';

		$output .= apply_filters( 'cbxmcratingreview_criteria_wrapper_before', $criteria_wrapper_before, $form_id, $stored_values, $form_default_fields, $customCriteria );

		$output .= '<div id="edit-custom-criteria-fields-wrapper" class="edit-custom-criteria-fields-wrapper">';

		$criteria_total = sizeof( $customCriteria );

		$output .= apply_filters( 'cbxmcratingreview_criteria_wrapper_loop_before', $criteria_wrapper_loop_before, $form_id, $stored_values, $form_default_fields, $customCriteria );

		$criteria_index = 0;

		for ( $criteria_index = 0; $criteria_index < $criteria_total; $criteria_index ++ ) {
			$output .= CBXMCRatingReviewAdminHelper::ratingFormEditRenderSingleCriteria( $form_id, $stored_values, $form_default_fields, $customCriteria, $criteria_index, $customCriteria[ $criteria_index ] );
		}//end criteria loop

		$criteria_last_count = isset( $stored_values['criteria_last_count'] ) ? absint( $stored_values['criteria_last_count'] ) : $criteria_index;




		$output .= apply_filters( 'cbxmcratingreview_criteria_wrapper_loop_after', $criteria_wrapper_loop_after, $form_id, $stored_values, $form_default_fields, $customCriteria, $criteria_index );

		$output .= '</div>'; //end of #edit-custom-criteria-fields-wrapper .edit-custom-criteria-fields-wrapper
		$output .= '<input id="criteria_last_count" type="hidden" name="cbxmcratingreview_ratingForm[extrafields][criteria_last_count]" value="' . $criteria_last_count . '" />';

		$output .= apply_filters( 'cbxmcratingreview_criteria_wrapper_after', $criteria_wrapper_after, $form_id, $stored_values, $form_default_fields, $customCriteria, $criteria_index );

		$output .= '</div>';

		return $output;
	}//end method ratingFormEditRenderCriterias

	/**
	 * Render Single criteria box
	 *
	 * @param int $form_id
	 * @param array $stored_values
	 * @param array $form_default_fields
	 * @param array $customCriteria
	 * @param int $criteria_index
	 * @param       $criteria
	 *
	 * @return string
	 */
	public static function ratingFormEditRenderSingleCriteria( $form_id = 0, $stored_values = array(), $form_default_fields = array(), $customCriteria = array(), $criteria_index = 0, $criteria ) {
		$output                         = '';
		$criteria_wrapper_start         = '';
		$criteria_wrapper_end           = '';
		$criteria_wrapper_inside_before = '';
		$criteria_wrapper_inside_after  = '';
		$criteria_wrapper_inside_start  = '';
		$criteria_wrapper_inside_end    = '';


		//$criteria_enabled = isset( $criteria['enabled'] ) ? absint( $criteria['enabled'] ) : 0;
		$criteria_id      = isset( $criteria['criteria_id'] ) ? intval( $criteria['criteria_id'] ) : $criteria_index;
		$criteria_label   = isset( $criteria['label'] ) ? wp_unslash( $criteria['label'] ) : sprintf( esc_html__( 'Untitled criteria - %d' ), $criteria_id );

		$output .=
			'<div class="custom-criteria-wrapper custom-criteria-wrapper-criteria-index-' . $criteria_index . '" data-criteria-index="' . $criteria_index . '" data-criteria-id="' . $criteria_id . '">
								' . apply_filters( 'cbxmcratingreview_criteria_wrapper_start', $criteria_wrapper_start, $form_id, $stored_values, $form_default_fields, $criteria_index, $criteria, $criteria_id ) . '								
								<a data-state="0" title="' . esc_html__( 'Click to configure rating stars', 'cbxmcratingreview' ) . '" href="#" class="tools-star-block button button-small" ></a>		
								<input required type="hidden" name="cbxmcratingreview_ratingForm[custom_criteria][' . $criteria_index . '][criteria_id]" value="' . $criteria_id . '" />															                              
									                     			                    <div   class="form-type-textfield form-item-custom-criteria-label">
			                        <input required type="text" name="cbxmcratingreview_ratingForm[custom_criteria][' . $criteria_index . '][label]" value="' . $criteria_label . '" class="form-text"  />		                                    </div>
			                    ';

		$output .= apply_filters( 'cbxmcratingreview_criteria_wrapper_inside_before', $criteria_wrapper_inside_before, $form_id, $stored_values, $form_default_fields, $criteria_index, $criteria, $criteria_id ); //hooks and filter

		$output .= '<div class="cbxclear"></div>';
		$output .= '<div class="custom-criteria-wrapper_inside">';


		$output .= '<div class="form-item-custom-criteria-stars form-type-checkboxes">';

		$output .= apply_filters( 'cbxmcratingreview_criteria_wrapper_inside_start', $criteria_wrapper_inside_start, $form_id, $stored_values, $form_default_fields, $criteria_index, $criteria, $criteria_id ); //hooks and filter

		$output .= '<div  class="form-item-custom-criteria-stars-box form-checkboxes">';


		$star_total = isset( $criteria['stars'] ) ? sizeof( $criteria['stars'] ) : 0;
		$star_index = 0;

		for ( $star_index = 0; $star_index < $star_total; $star_index ++ ) {
			$output .= CBXMCRatingReviewAdminHelper::ratingFormEditRenderSingleCriteriaSingleStar( $form_id, $stored_values, $form_default_fields, $customCriteria, $criteria_index, $criteria_id, $criteria['stars'][ $star_index ], $star_index );
		}//end star loop


		//$star_last_count = isset( $criteria['star_last_count'] ) ? absint( $criteria['star_last_count'] ) : $star_index;
		//$output .= '<input class="star_last_count" type="hidden" name="cbxmcratingreview_ratingForm[custom_criteria][' . $criteria_index . '][star_last_count]" value="'.$star_last_count.'" />';


		$output .= '</div>';  //.form-item-custom-criteria-stars-box
		//$output .= '<input class="star_last_count" type="hidden" name="cbxmcratingreview_ratingForm[custom_criteria][' . $criteria_index . '][star_last_count]" value="' . $star_last_count . '" />';

		$output .= apply_filters( 'cbxmcratingreview_criteria_wrapper_inside_end', $criteria_wrapper_inside_end, $form_id, $stored_values, $form_default_fields, $criteria_index, $criteria, $criteria_id, $star_index ); //hooks and filter
		$output .= '</div>'; //.form-item-custom-criteria-stars


		$output .= '</div>'; //.custom-criteria-wrapper_inside

		$output .= apply_filters( 'cbxmcratingreview_criteria_wrapper_inside_after', $criteria_wrapper_inside_after, $form_id, $stored_values, $form_default_fields, $criteria_index, $criteria, $criteria_id, $star_index ); //hooks and filter

		$output .= apply_filters( 'cbxmcratingreview_criteria_wrapper_end', $criteria_wrapper_end, $form_id, $stored_values, $form_default_fields, $criteria_index, $criteria, $criteria_id, $star_index ); //hooks and filter
		$output .= '</div>'; //.custom-criteria-wrapper


		return $output;
	}//end method ratingFormEditRenderSingleCriteria

	/**
	 * Render Single star box
	 *
	 * @param int $form_id
	 * @param array $stored_values
	 * @param array $form_default_fields
	 * @param array $customCriteria
	 * @param int $criteria_index
	 * @param int $criteria_id
	 * @param array $star
	 * @param int $star_index
	 *
	 * @return string
	 */
	public static function ratingFormEditRenderSingleCriteriaSingleStar( $form_id = 0, $stored_values = array(), $form_default_fields = array(), $customCriteria = array(), $criteria_index = 0, $criteria_id = 0, $star = array(), $star_index = 0 ) {

		$star_titles = CBXMCRatingReviewHelper::star_default_titles();

		$output                     = '';
		$criteria_star_start_before = '';
		$criteria_star_start        = '';
		$criteria_star_end_after    = '';
		$criteria_star_end          = '';

		//$star_enabled = isset( $star['enabled'] ) ? absint( $star['enabled'] ) : 0;
		//$star_id      = isset( $star['star_id'] ) ? intval( $star['star_id'] ) : $star_index;
		$star_title   = isset( $star['title'] ) ? wp_unslash( $star['title'] ) : sprintf( esc_html__( 'Untitled star - %d', 'cbxmcratingreviewpro' ), $star_index );


		$output .= apply_filters( 'criteria_star_start_before', $criteria_star_start_before, $form_id, $stored_values, $form_default_fields, $customCriteria, $criteria_index, $criteria_id, $star, $star_index );
		$output .=
			'<div class="form-item form-type-checkbox form-item-custom-criteria-star" data-criteria-index="' . $criteria_index . '" data-criteria-id="' . $criteria_id . '" data-star-index="' . $star_index . '" >
									' . apply_filters( 'cbxmcratingreview_criteria_star_start', $criteria_star_start, $form_id, $stored_values, $form_default_fields, $customCriteria, $criteria_index, $criteria_id, $star, $star_index ) . '
																	              																				                
									<input required type="text" name="cbxmcratingreview_ratingForm[custom_criteria][' . $criteria_index . '][stars][' . $star_index . '][title]" value="' . $star_title . '"		class="form-text edit-custom-criteria-label-text-star">																			
									' . apply_filters( 'cbxmcratingreview_criteria_star_end', $criteria_star_end, $form_id, $stored_values, $form_default_fields, $customCriteria, $criteria_index, $criteria_id, $star, $star_index ) . '
						</div>';

		$output .= apply_filters( 'criteria_star_start_after', $criteria_star_end_after, $form_id, $stored_values, $form_default_fields, $customCriteria, $criteria_index, $criteria_id, $star, $star_index );

		return $output;
	}//end method ratingFormEditRenderSingleCriteriaSingleStar
}//end class CBXMCRatingReviewAdminHelper