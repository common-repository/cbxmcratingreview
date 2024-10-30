<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class CBXMCRatingReviewQuestionHelper
 */
class CBXMCRatingReviewQuestionHelper {
	/**
	 * Text (Single line input) field preview display
	 *
	 * @param  int $question_index
	 * @param  array $question
	 * @param  array $stored_values
	 *
	 * @return string
	 */
	public static function admin_display_text_field( $question_index = 0, $question = array(), $stored_values = array() ) {
		$question_index = intval( $question_index );
		$question_title = isset( $question['title'] ) ? esc_attr( $question['title'] ) : esc_html__( 'Untitled Question!', 'cbxmcratingreview' );
		$placeholder    = isset( $question['placeholder'] ) ? esc_attr( $question['placeholder'] ) : '';




		$output = '';
		$output .= '<div data-q-id="' . $question_index . '" class="form_item form_item_q_id-' . $question_index . ' form_item_text form_item_field_display form_item_text_q_id-' . $question_index . ' ">
				<input type="hidden" name="cbxmcratingreview_ratingForm[custom_question][' . $question_index . '][type]" value="text" value="" />
                <label for="cbxmcratingreview_q_field_' . $question_index . '">' . esc_html__( 'Placeholder Text', 'cbxmcratingreview' ) . '</label><br/>
                <input id="cbxmcratingreview_q_field_' . $question_index . '" class="regular-text" type="text" name="cbxmcratingreview_ratingForm[custom_question][' . $question_index . '][placeholder]" placeholder="' . $placeholder . '" value="' . $placeholder . '" />
            </div>';

		return $output;
	}//end method  admin_display_text_field


	/**
	 * Textarea Field Display
	 *
	 * @param  int $question_index
	 * @param  array $question
	 * @param  array $stored_values
	 *
	 * @return string
	 */
	public static function admin_display_textarea_field( $question_index = 0, $question = array(), $stored_values = array() ) {
		$question_index = intval( $question_index );
		$question_title = isset( $question['title'] ) ? esc_attr( $question['title'] ) : esc_html__( 'Untitled Question!', 'cbxmcratingreview' );
		$placeholder    = isset( $question['placeholder'] ) ? esc_attr( $question['placeholder'] ) : '';


		$output = '';
		$output .= '<div data-q-id="' . $question_index . '" class="form_item form_item_q_id-' . $question_index . ' form_item_text form_item_field_display form_item_text_q_id-' . $question_index . ' ">
				<input type="hidden" name="cbxmcratingreview_ratingForm[custom_question][' . $question_index . '][type]" value="textarea" />
                <label for="cbxmcratingreview_q_field_' . $question_index . '">' . esc_html__( 'Placeholder Text', 'cbxmcratingreview' ) . '</label><br/>
                <textarea rows="5" cols="20" id="cbxmcratingreview_q_field_' . $question_index . '" class="regular-text" type="text" name="cbxmcratingreview_ratingForm[custom_question][' . $question_index . '][placeholder]" placeholder="' . $placeholder . '" /></textarea>
            </div>';

		return $output;
	}//end method  admin_display_textarea_field


	/**
	 * Number field display
	 *
	 * @param  int $question_index
	 * @param  array $question
	 * @param  array $stored_values
	 *
	 * @return string
	 */
	public static function admin_display_number_field( $question_index = 0, $question = array(), $stored_values = array() ) {
		$question_index = intval( $question_index );
		$question_title = isset( $question['title'] ) ? esc_attr( $question['title'] ) : esc_html__( 'Untitled Question!', 'cbxmcratingreview' );
		$placeholder    = isset( $question['placeholder'] ) ? esc_attr( $question['placeholder'] ) : '';
		$min            = isset( $question['min'] ) ? floatval( $question['min'] ) : 0;
		$max            = isset( $question['max'] ) ? floatval( $question['max'] ) : 100;
		$step           = isset( $question['step'] ) ? floatval( $question['step'] ) : 1;

		$output = '';
		$output .= '<div data-q-id="' . $question_index . '" class="form_item form_item_q_id-' . $question_index . ' form_item_text form_item_field_display form_item_text_q_id-' . $question_index . ' ">
				<input type="hidden" name="cbxmcratingreview_ratingForm[custom_question][' . $question_index . '][type]" value="number" />
                <label for="cbxmcratingreview_q_field_' . $question_index . '_placeholder">' . esc_html__( 'Placeholder Text', 'cbxmcratingreview' ) . '</label><br/>
                <input id="cbxmcratingreview_q_field_' . $question_index . '_placeholder" class="regular-text" type="text" name="cbxmcratingreview_ratingForm[custom_question][' . $question_index . '][placeholder]" placeholder="' . $placeholder . '" value="' . $placeholder . '" /><br/>
                <label for="cbxmcratingreview_q_field_' . $question_index . '_min">' . esc_html__( 'Minimum Value', 'cbxmcratingreview' ) . '</label><br/>
                <input id="cbxmcratingreview_q_field_' . $question_index . '_min" class="regular-text" type="text" name="cbxmcratingreview_ratingForm[custom_question][' . $question_index . '][min]"  value="' . $min . '" /><br/>
                <label for="cbxmcratingreview_q_field_' . $question_index . '_max">' . esc_html__( 'Maximum Value', 'cbxmcratingreview' ) . '</label><br/>
                <input id="cbxmcratingreview_q_field_' . $question_index . '_max" class="regular-text" type="text" name="cbxmcratingreview_ratingForm[custom_question][' . $question_index . '][max]"  value="' . $max . '" /><br/>
                <label for="cbxmcratingreview_q_field_' . $question_index . '_step">' . esc_html__( 'Step Value', 'cbxmcratingreview' ) . '</label><br/>
                <input id="cbxmcratingreview_q_field_' . $question_index . '_step" class="regular-text" type="text" name="cbxmcratingreview_ratingForm[custom_question][' . $question_index . '][step]"  value="' . $step . '" />
            </div>';

		return $output;
	}//end method  admin_display_number_field

	/**
	 * Checkbox field display
	 *
	 * @param      $question_index
	 * @param      $question
	 * @param      $stored_values
	 *
	 * @return string
	 */
	public static function admin_display_checkbox_field( $question_index = 0, $question = array(), $stored_values = array() ) {
		$question_index = intval( $question_index );
		$question_title = isset( $question['title'] ) ? esc_attr( $question['title'] ) : esc_html__( 'Untitled Question!', 'cbxmcratingreview' );

		$output = '';
		$output .= '
            <div data-q-id="' . $question_index . '" class="form_item form_item_q_id-' . $question_index . ' form_item_checkbox form_item_field_display form_item_checkbox_q_id-' . $question_index . '" style="float:left;">
                <input type="hidden" name="cbxmcratingreview_ratingForm[custom_question][' . $question_index . '][type]" value="checkbox" />
                <label>
                	<input name="" type="checkbox" value="1" class="form-checkbox" />
                	' . $question_title . '
				</label>                                    
        	</div>';

		return $output;
	}//end method admin_display_checkbox_field

	/**
	 * Add option for multicheckbox field
	 *
	 * @param int $option_index
	 * @param array $option
	 * @param int $question_index
	 * @param array $question
	 * @param array $stored_values
	 *
	 * @return string
	 */
	public static function admin_display_multicheckbox_field_option( $option_index = 0, $option = array(), $question_index = 0, $question = array(), $stored_values = array() ) {

		$question_start_extra = '';
		$question_end_extra   = '';
		$label                = isset( $option['text'] ) ? esc_attr( $option['text'] ) : esc_html__( 'Untitled Option!', 'cbxmcratingreview' );
		$output               = '<div class="question-option-label-box">
					' . apply_filters( 'cbxmcratingreview_question_multicheckbox_start', $question_start_extra, $option_index, $question_index, $question, $stored_values ) . '  
                    <input type="checkbox" name="" value="1" class="form-checkbox">
                    <label id="question-option-label-' . $question_index . '-' . $option_index . '" class="question-option-label question-label-editable mouse_normal" title="Click to edit">' . wp_unslash( $label ) . '</label>
                    <input id="question-option-label-input-' . $question_index . '-' . $option_index . '" class="question-option-label-input question-label-input-editable regular-text disable_field"  type="text" name="cbxmcratingreview_ratingForm[custom_question][' . $question_index . '][options][' . $option_index . '][text]" style=""  value="' . wp_unslash( $label ) . '"/>       
                    ' . apply_filters( 'cbxmcratingreview_question_multicheckbox_end', $question_end_extra, $option_index, $question_index, $question, $stored_values ) . '           
                </div>'; //.question-option-label-box

		return $output;
	}//end method admin_display_multicheckbox_field_option

	/**
	 * Radio Field display
	 *
	 * @param      $question_index
	 * @param      $question
	 * @param      $stored_values
	 *
	 * @return string
	 */
	public static function admin_display_radio_field( $question_index = 0, $question = array(), $stored_values = array() ) {
		$question_index = intval( $question_index );
		$question_title = isset( $question['title'] ) ? esc_attr( $question['title'] ) : esc_html__( 'Untitled Question!', 'cbxmcratingreview' );
		$last_count     = isset( $question['last_count'] ) ? intval( $question['last_count'] ) : 0;
		$options        = isset( $question['options'] ) ? (array) $question['options'] : array();

		$output = '<div class="question-option-label-boxes-wrapper question-option-label-boxes-wrapper-radio">
                <input type="hidden" name="cbxmcratingreview_ratingForm[custom_question][' . $question_index . '][type]" value="radio" />
               <input type="hidden" name="cbxmcratingreview_ratingForm[custom_question][' . $question_index . '][last_count]"  class="lastcount" value="' . intval( $last_count ) . '" />';


		$question_before_extra = '';
		$question_after_extra  = '';

		$output .= '<p>' . wp_unslash( $question_title ) . esc_html__( ' (Click any label to edit)', 'cbxmcratingreview' ) . '</p>';
		$output .= apply_filters( 'cbxmcratingreview_question_radio_before', $question_before_extra, $question_index, $question, $stored_values );
		$output .= '<div class="question-option-label-boxes question-option-label-boxes-radio">';
		foreach ( $options as $option_index => $option ) {
			$output .= CBXMCRatingReviewQuestionHelper::admin_display_radio_field_option( $option_index, $option, $question_index, $question, $stored_values );
		}//end for each option
		$output .= '</div>'; //.question-option-label-boxes
		$output .= apply_filters( 'cbxmcratingreview_question_radio_after', $question_after_extra, $question_index, $question, $stored_values );
		$output .= '</div>';

		return $output;
	}//end admin_display_radio_field

	/**
	 * Add option for radio field type
	 *
	 * @param int $option_index
	 * @param array $option
	 * @param int $question_index
	 * @param array $question
	 * @param array $stored_values
	 *
	 * @return string
	 */
	public static function admin_display_radio_field_option( $option_index = 0, $option = array(), $question_index = 0, $question = array(), $stored_values = array() ) {
		$question_start_extra = '';
		$question_end_extra   = '';

		$output = '';
		$label  = isset( $option['text'] ) ? esc_attr( $option['text'] ) : esc_html__( 'Untitled Option!', 'cbxmcratingreview' );
		$output .= '<div class="question-option-label-box">
' . apply_filters( 'cbxmcratingreview_question_radio_start', $question_start_extra, $option_index, $question_index, $question, $stored_values ) . '  
                    <input type="radio" name="" value="1" class="form-radio">
                     <label id="question-option-label-' . $question_index . '-' . $option_index . '" class="question-option-label question-label-editable mouse_normal" title="Click to edit">' . wp_unslash( $label ) . '</label>
                    <input id="question-option-label-input-' . $question_index . '-' . $option_index . '" class="question-option-label-input question-label-input-editable regular-text disable_field"  type="text" name="cbxmcratingreview_ratingForm[custom_question][' . $question_index . '][options][' . $option_index . '][text]" style=""  value="' . wp_unslash( $label ) . '"/>  
                    ' . apply_filters( 'cbxmcratingreview_question_radio_end', $question_end_extra, $option_index, $question_index, $question, $stored_values ) . ' 
                 </div>';

		return $output;
	}//end method admin_display_radio_field_option


	/**
	 * Select Field display
	 *
	 * @param      $question_index
	 * @param      $question
	 * @param      $stored_values
	 *
	 * @return string
	 */
	public static function admin_display_select_field( $question_index = 0, $question = array(), $stored_values = array() ) {
		$question_index = intval( $question_index );
		$question_title = isset( $question['title'] ) ? esc_attr( $question['title'] ) : esc_html__( 'Untitled Question!', 'cbxmcratingreview' );
		$last_count     = isset( $question['last_count'] ) ? intval( $question['last_count'] ) : 0;
		$multiple       = isset( $question['multiple'] ) ? intval( $question['multiple'] ) : 0;
		$options        = isset( $question['options'] ) ? (array) $question['options'] : array();


		$question_before_extra = '';
		$question_after_extra  = '';

		$output = '<div class="question-option-label-boxes-wrapper question-option-label-boxes-wrapper-select">
                <input type="hidden" name="cbxmcratingreview_ratingForm[custom_question][' . $question_index . '][type]" value="select" />                
               <input type="hidden" name="cbxmcratingreview_ratingForm[custom_question][' . $question_index . '][last_count]"  class="lastcount" value="' . intval( $last_count ) . '" />';

		$output .= '<p>' . $question_title . '</p>';

		$output .= '<select ' . ( ( $multiple ) ? ' multiple ' : '' ) . '>';
		foreach ( $options as $option_index => $option ) {
			$label  = isset( $option['text'] ) ? esc_attr( $option['text'] ) : esc_html__( 'Untitled Option!', 'cbxmcratingreview' );
			$output .= '<option value="' . $option_index . '">' . $label . '</option>';
		}
		$output .= '</select><br/>';
		$output .= '<p>' . esc_html__( 'Drop Down Options (Click any label to edit)', 'cbxmcratingreview' ) . '</p>';

		$output .= apply_filters( 'cbxmcratingreview_question_select_before', $question_before_extra, $question_index, $question, $stored_values );
		$output .= '<div class="question-option-label-boxes question-option-label-boxes-select">';
		foreach ( $options as $option_index => $option ) {
			$output .= CBXMCRatingReviewQuestionHelper::admin_display_select_field_option( $option_index, $option, $question_index, $question, $stored_values );
		}//end for each option
		$output .= '</div>'; //.question-option-label-boxes
		$output .= apply_filters( 'cbxmcratingreview_question_select_after', $question_after_extra, $question_index, $question, $stored_values );
		$output .= '</div>';

		return $output;
	}//end method admin_display_select_field

	/**
	 * Add option for select field type
	 *
	 * @param int $option_index
	 * @param array $option
	 * @param int $question_index
	 * @param array $question
	 * @param array $stored_values
	 *
	 * @return string
	 */
	public static function admin_display_select_field_option( $option_index = 0, $option = array(), $question_index = 0, $question = array(), $stored_values = array() ) {
		$question_start_extra = '';
		$question_end_extra   = '';

		$label  = isset( $option['text'] ) ? esc_attr( $option['text'] ) : esc_html__( 'Untitled Option!', 'cbxmcratingreview' );
		$output = '<div class="question-option-label-box">
' . apply_filters( 'cbxmcratingreview_question_select_start', $question_start_extra, $option_index, $question_index, $question, $stored_values ) . '         
                <label id="question-option-label-' . $question_index . '-' . $option_index . '" class="question-option-label question-label-editable mouse_normal" title="Click to edit">' . wp_unslash( $label ) . '</label>
                    <input id="question-option-label-input-' . $question_index . '-' . $option_index . '" class="question-option-label-input question-label-input-editable regular-text disable_field"  type="text" name="cbxmcratingreview_ratingForm[custom_question][' . $question_index . '][options][' . $option_index . '][text]" style=""  value="' . wp_unslash( $label ) . '"/>  
                     ' . apply_filters( 'cbxmcratingreview_question_select_end', $question_end_extra, $option_index, $question_index, $question, $stored_values ) . '
                </div>';

		return $output;
	}//end method admin_display_select_field_option

	/**
	 * Multi checkbox field display
	 *
	 * @param      $question_index
	 * @param      $question
	 * @param      $stored_values
	 *
	 * @return string
	 */
	public static function admin_display_multicheckbox_field( $question_index = 0, $question = array(), $stored_values = array() ) {
		$question_index = intval( $question_index );
		$question_title = isset( $question['title'] ) ? esc_attr( $question['title'] ) : esc_html__( 'Untitled Question!', 'cbxmcratingreview' );
		$last_count     = isset( $question['last_count'] ) ? intval( $question['last_count'] ) : 0;
		$options        = isset( $question['options'] ) ? (array) $question['options'] : array();

		$output = '<div class="question-option-label-boxes-wrapper question-option-label-boxes-wrapper-multicheckbox">
               <input type="hidden" name="cbxmcratingreview_ratingForm[custom_question][' . $question_index . '][type]" value="multicheckbox" />
               <input type="hidden" name="cbxmcratingreview_ratingForm[custom_question][' . $question_index . '][last_count]"  class="lastcount" value="' . intval( $last_count ) . '" />';


		$output .= '<p>' . wp_unslash( $question_title ) . esc_html__( ' (Click any label to edit)', 'cbxmcratingreview' ) . '</p>';


		$question_before_extra = '';
		$question_after_extra  = '';

		$output .= apply_filters( 'cbxmcratingreview_question_multicheckbox_before', $question_before_extra, $question_index, $question, $stored_values );
		$output .= '<div class="question-option-label-boxes question-option-label-boxes-multicheckbox">';
		foreach ( $options as $option_index => $option ) {
			$output .= CBXMCRatingReviewQuestionHelper::admin_display_multicheckbox_field_option( $option_index, $option, $question_index, $question, $stored_values );
		}//end for each option
		$output .= '</div>'; //.question-option-label-boxes
		$output .= apply_filters( 'cbxmcratingreview_question_multicheckbox_after', $question_after_extra, $question_index, $question, $stored_values );
		$output .= '</div>';

		return $output;
	}//end method admin_display_multicheckbox_field

	/**
	 * Text (Single line input) field form display
	 *
	 * @param  int $question_index
	 * @param  array $question
	 * @param  array $stored_values = array()
	 *
	 * @return string
	 */
	public static function public_display_text_field( $question_index = 0, $question = array(), $stored_values = '' ) {
		$question_index = intval( $question_index );
		$required       = isset( $question['required'] ) ? intval( $question['required'] ) : 0;
		$title          = isset( $question['title'] ) ? esc_attr( $question['title'] ) : sprintf( esc_html__( 'Untitled Question %d', 'cbxmcratingreview' ), intval( $question_index ) );
		$placeholder    = isset( $question['placeholder'] ) ? esc_attr( $question['placeholder'] ) : '';

		$required_text      = ( $required ) ? ' required ' : '';
		$required_data_text = ( $required ) ? ' data-rule-required="true" ' : '';

		//$user_answer = isset($question['user_answer'])? $question['user_answer']: '';


		$output = '<label class="cbxmcratingreview_q_field_label cbxmcratingreview_q_field_label_text" for="cbxmcratingreview_q_field_' . $question_index . '">' . $title . '</label>';
		$output .= '<input ' . $required_text . $required_data_text . ' id="cbxmcratingreview_q_field_' . $question_index . '" class="regular-text cbxmcratingreview_q_field cbxmcratingreview_q_field_text" type="text" name="cbxmcratingreview_ratingForm[questions][' . $question_index . ']" placeholder="' . $placeholder . '" value="' . $stored_values . '" />';

		return $output;
	}//end method  admin_display_text_field

	/**
	 * Text (Single line input) field answer display
	 *
	 * @param  int $question_index
	 * @param  array $question
	 * @param  array $stored_values = array()
	 *
	 * @return string
	 */
	public static function answer_display_text_field( $question_index = 0, $question = array(), $stored_values = '' ) {
		$question_index = intval( $question_index );
		$required       = isset( $question['required'] ) ? intval( $question['required'] ) : 0;
		$title          = isset( $question['title'] ) ? esc_attr( $question['title'] ) : sprintf( esc_html__( 'Untitled Question %d', 'cbxmcratingreview' ), intval( $question_index ) );
		$placeholder    = isset( $question['placeholder'] ) ? esc_attr( $question['placeholder'] ) : '';

		$required_text      = ( $required ) ? ' required ' : '';
		$required_data_text = ( $required ) ? ' data-rule-required="true" ' : '';

		//$user_answer = isset($question['user_answer'])? $question['user_answer']: '';


		$output = '<p class="cbxmcratingreview_q_field_answer cbxmcratingreview_q_field_answer_text" for="cbxmcratingreview_q_field_' . $question_index . '">' . $title . '</p>';
		$output .= '<p>'.$stored_values.'</p>';
		//$output .= '<input ' . $required_text . $required_data_text . ' id="cbxmcratingreview_q_field_' . $question_index . '" class="regular-text cbxmcratingreview_q_field cbxmcratingreview_q_field_text" type="text" name="cbxmcratingreview_ratingForm[questions][' . $question_index . ']" placeholder="' . $placeholder . '" value="' . $stored_values . '" />';
		//$output .= $stored_values;

		return $output;
	}//end method  answer_display_text_field

	/**
	 * Textarea Field Display
	 *
	 * @param  int $question_index
	 * @param  array $question
	 * @param  string $stored_values
	 *
	 * @return string
	 */
	public static function public_display_textarea_field( $question_index = 0, $question = array(), $stored_values = '' ) {
		$question_index     = intval( $question_index );
		$required           = isset( $question['required'] ) ? intval( $question['required'] ) : 0;
		$title              = isset( $question['title'] ) ? esc_attr( $question['title'] ) : sprintf( esc_html__( 'Untitled Question %d', 'cbxmcratingreview' ), intval( $question_index ) );
		$placeholder        = isset( $question['placeholder'] ) ? esc_attr( $question['placeholder'] ) : '';
		$required_text      = ( $required ) ? ' required ' : '';
		$required_data_text = ( $required ) ? ' data-rule-required="true" ' : '';


		$output = '<label class="cbxmcratingreview_q_field_label cbxmcratingreview_q_field_label_textarea" for="cbxmcratingreview_q_field_' . $question_index . '">' . $title . '</label>';
		$output .= '<textarea ' . $required_text . ' rows="5" cols="20" id="cbxmcratingreview_q_field_' . $question_index . '" class="regular-text cbxmcratingreview_q_field cbxmcratingreview_q_field_textarea" type="text" name="cbxmcratingreview_ratingForm[questions][' . $question_index . ']" placeholder="' . $placeholder . '" />' . $stored_values . '</textarea>';

		return $output;
	}//end method  admin_display_textarea_field

	/**
	 * Textarea answer Display
	 *
	 * @param  int $question_index
	 * @param  array $question
	 * @param  string $stored_values
	 *
	 * @return string
	 */
	public static function answer_display_textarea_field( $question_index = 0, $question = array(), $stored_values = '' ) {
		$question_index     = intval( $question_index );
		$required           = isset( $question['required'] ) ? intval( $question['required'] ) : 0;
		$title              = isset( $question['title'] ) ? esc_attr( $question['title'] ) : sprintf( esc_html__( 'Untitled Question %d', 'cbxmcratingreview' ), intval( $question_index ) );
		$placeholder        = isset( $question['placeholder'] ) ? esc_attr( $question['placeholder'] ) : '';
		$required_text      = ( $required ) ? ' required ' : '';
		$required_data_text = ( $required ) ? ' data-rule-required="true" ' : '';


		$output = '<p class="cbxmcratingreview_q_field_answer cbxmcratingreview_q_field_answer_textarea" for="cbxmcratingreview_q_field_' . $question_index . '">' . $title . '</p>';
		//$output .= '<textarea ' . $required_text . ' rows="5" cols="20" id="cbxmcratingreview_q_field_' . $question_index . '" class="regular-text cbxmcratingreview_q_field cbxmcratingreview_q_field_textarea" type="text" name="cbxmcratingreview_ratingForm[questions][' . $question_index . ']" placeholder="' . $placeholder . '" />' . $stored_values . '</textarea>';

		$output .= $stored_values;

		return $output;
	}//end method  answer_display_textarea_field

	/**
	 * Number field display
	 *
	 * @param  int $question_index
	 * @param  array $question
	 * @param  array $stored_values
	 *
	 * @return string
	 */
	public static function public_display_number_field( $question_index = 0, $question = array(), $stored_values = '' ) {
		$question_index     = intval( $question_index );
		$required           = isset( $question['required'] ) ? intval( $question['required'] ) : 0;
		$title              = isset( $question['title'] ) ? esc_attr( $question['title'] ) : sprintf( esc_html__( 'Untitled Question %d', 'cbxmcratingreview' ), intval( $question_index ) );
		$placeholder        = isset( $question['placeholder'] ) ? esc_attr( $question['placeholder'] ) : '';
		$required_text      = ( $required ) ? ' required ' : '';
		$required_data_text = ( $required ) ? ' data-rule-required="true" ' : '';


		$min  = isset( $question['min'] ) ? floatval( $question['min'] ) : 0;
		$max  = isset( $question['max'] ) ? floatval( $question['max'] ) : 100;
		$step = isset( $question['step'] ) ? floatval( $question['step'] ) : 1;

		$output = '<label class="cbxmcratingreview_q_field_label cbxmcratingreview_q_field_label_number" for="cbxmcratingreview_q_field_' . $question_index . '">' . $title . '</label>';
		$output .= '<input id="cbxmcratingreview_q_field_' . $question_index . '" class="regular-text cbxmcratingreview_q_field cbxmcratingreview_q_field_number" type="number" name="cbxmcratingreview_ratingForm[questions][' . $question_index . ']"  value="' . $stored_values . '" min="' . $min . '" max="' . $max . '" step="' . $step . '" ' . $required_text . $required_data_text . ' />';

		return $output;
	}//end method  admin_display_number_field

	/**
	 * Number answer display
	 *
	 * @param  int $question_index
	 * @param  array $question
	 * @param  array $stored_values
	 *
	 * @return string
	 */
	public static function answer_display_number_field( $question_index = 0, $question = array(), $stored_values = '' ) {
		$question_index     = intval( $question_index );
		$required           = isset( $question['required'] ) ? intval( $question['required'] ) : 0;
		$title              = isset( $question['title'] ) ? esc_attr( $question['title'] ) : sprintf( esc_html__( 'Untitled Question %d', 'cbxmcratingreview' ), intval( $question_index ) );
		$placeholder        = isset( $question['placeholder'] ) ? esc_attr( $question['placeholder'] ) : '';
		$required_text      = ( $required ) ? ' required ' : '';
		$required_data_text = ( $required ) ? ' data-rule-required="true" ' : '';


		$min  = isset( $question['min'] ) ? floatval( $question['min'] ) : 0;
		$max  = isset( $question['max'] ) ? floatval( $question['max'] ) : 100;
		$step = isset( $question['step'] ) ? floatval( $question['step'] ) : 1;

		$output = '<p class="cbxmcratingreview_q_field_answer cbxmcratingreview_q_field_answer_number" for="cbxmcratingreview_q_field_' . $question_index . '">' . $title . ' : '.$stored_values.'</p>';
		//$output .= '<input id="cbxmcratingreview_q_field_' . $question_index . '" class="regular-text cbxmcratingreview_q_field cbxmcratingreview_q_field_number" type="number" name="cbxmcratingreview_ratingForm[questions][' . $question_index . ']"  value="' . $stored_values . '" min="' . $min . '" max="' . $max . '" step="' . $step . '" ' . $required_text . $required_data_text . ' />';


		return $output;
	}//end method  answer_display_number_field

	/**
	 * Checkbox field display
	 *
	 * @param      $question_index
	 * @param      $question
	 * @param      $stored_values
	 *
	 * @return string
	 */
	public static function public_display_checkbox_field( $question_index = 0, $question = array(), $stored_values = 0 ) {
		$question_index = intval( $question_index );
		$required       = isset( $question['required'] ) ? intval( $question['required'] ) : 0;
		$title          = isset( $question['title'] ) ? esc_attr( $question['title'] ) : sprintf( esc_html__( 'Untitled Question %d', 'cbxmcratingreview' ), intval( $question_index ) );
		//$placeholder = isset($question['placeholder']) ? esc_attr($question['placeholder']) : '';
		$required_text      = ( $required ) ? ' required ' : '';
		$required_data_text = ( $required ) ? ' data-rule-required="true" ' : '';

		$stored_values = intval( $stored_values );

		$output = '<label class="cbxmcratingreview_q_field_label cbxmcratingreview_q_field_label_checkbox" for="cbxmcratingreview_q_field_' . $question_index . '"><input class="cbxmcratingreview_q_field cbxmcratingreview_q_field_checkbox" id="cbxmcratingreview_q_field_' . $question_index . '" ' . $required_text . $required_data_text . ' name="cbxmcratingreview_ratingForm[questions][' . $question_index . ']" type="checkbox" ' . checked( $stored_values, 1, false ) . ' value="1"  />' . $title . '</label>';

		return $output;
	}//end method admin_display_checkbox_field


	/**
	 * Checkbox answer display
	 *
	 * @param      $question_index
	 * @param      $question
	 * @param      $stored_values
	 *
	 * @return string
	 */
	public static function answer_display_checkbox_field( $question_index = 0, $question = array(), $stored_values = 0 ) {
		$question_index = intval( $question_index );
		$required       = isset( $question['required'] ) ? intval( $question['required'] ) : 0;
		$title          = isset( $question['title'] ) ? esc_attr( $question['title'] ) : sprintf( esc_html__( 'Untitled Question %d', 'cbxmcratingreview' ), intval( $question_index ) );
		//$placeholder = isset($question['placeholder']) ? esc_attr($question['placeholder']) : '';
		$required_text      = ( $required ) ? ' required ' : '';
		$required_data_text = ( $required ) ? ' data-rule-required="true" ' : '';

		$stored_values = intval( $stored_values );

		$output = '<p class="cbxmcratingreview_q_field_answer cbxmcratingreview_q_field_answer_checkbox" for="cbxmcratingreview_q_field_' . $question_index . '">' . $title . ' : '.(($stored_values == 1)? esc_html__('Yes', 'cbxmcratingreview'): esc_html__('No', 'cbxmcratingreview')).'</p>';

		return $output;
	}//end method answer_display_checkbox_field

	/**
	 * Multi checkbox field display
	 *
	 * @param      $question_index
	 * @param      $question
	 * @param      $stored_values
	 *
	 * @return string
	 */
	public static function public_display_multicheckbox_field( $question_index = 0, $question = array(), $stored_values = array() ) {
		$question_index = intval( $question_index );
		$required       = isset( $question['required'] ) ? intval( $question['required'] ) : 0;
		$title          = isset( $question['title'] ) ? esc_attr( $question['title'] ) : sprintf( esc_html__( 'Untitled Question %d', 'cbxmcratingreview' ), intval( $question_index ) );
		$options        = isset( $question['options'] ) ? (array) $question['options'] : array();

		$required_text           = ( $required ) ? ' required ' : '';
		$required_minlength_text = ( $required ) ? ' data-rule-cbxmcratingreview_multicheckbox="1" ' : '';

		$stored_values = maybe_unserialize($stored_values);
		if(!is_array($stored_values)) $stored_values = array();


		$stored_values = array_keys($stored_values);

		$output = '<label class="cbxmcratingreview_q_field_label cbxmcratingreview_q_field_label_multicheckbox">' . $title . '</label>';
		$output .= '<div class="cbxmcratingreview_q_field_label_multicheckboxes">';
		foreach ( $options as $option_index => $option ) {
			$label        = isset( $option['text'] ) ? esc_attr( $option['text'] ) : esc_html__( 'Untitled Option!', 'cbxmcratingreview' );

			$stored_value = in_array($option_index, $stored_values)? 1: 0;

			$output       .= '<label class="cbxmcratingreview_q_field_label_option cbxmcratingreview_q_field_label_option_multicheckbox" for="cbxmcratingreview_q_field_option_' . $question_index . '_' . $option_index . '"><input class="cbxmcratingreview_q_field_option cbxmcratingreview_q_field_option_multicheckbox" id="cbxmcratingreview_q_field_option_' . $question_index . '_' . $option_index . '"  name="cbxmcratingreview_ratingForm[questions][' . $question_index . '][' . $option_index . ']" type="checkbox" ' . checked( $stored_value, 1, false ) . ' value="1" ' . $required_minlength_text . ' />' . $label . '</label>';
		}//end for each option
		$output .= '</div>'; //.cbxmcratingreview_q_field_label_multicheckboxes

		return $output;
	}//end method admin_display_multicheckbox_field


	/**
	 * Multi checkbox answer display
	 *
	 * @param      $question_index
	 * @param      $question
	 * @param      $stored_values
	 *
	 * @return string
	 */
	public static function answer_display_multicheckbox_field( $question_index = 0, $question = array(), $stored_values = array() ) {

		$question_index = intval( $question_index );
		$required       = isset( $question['required'] ) ? intval( $question['required'] ) : 0;
		$title          = isset( $question['title'] ) ? esc_attr( $question['title'] ) : sprintf( esc_html__( 'Untitled Question %d', 'cbxmcratingreview' ), intval( $question_index ) );
		$options        = isset( $question['options'] ) ? (array) $question['options'] : array();

		$required_text           = ( $required ) ? ' required ' : '';
		$required_minlength_text = ( $required ) ? ' data-rule-cbxmcratingreview_multicheckbox="1" ' : '';


		$stored_values = maybe_unserialize($stored_values);


		if(!is_array($stored_values)) $stored_values = array();
		$stored_values = array_keys($stored_values);

		$output = '<p class="cbxmcratingreview_q_field_answer cbxmcratingreview_q_field_answer_multicheckbox">' . $title . '</p>';
		$output .= '<div class="cbxmcratingreview_q_field_label_multicheckboxes">';

		$answer_output = '';
		foreach ( $options as $option_index => $option ) {
			$label        = isset( $option['text'] ) ? esc_attr( $option['text'] ) : esc_html__( 'Untitled Option!', 'cbxmcratingreview' );

			$stored_value = in_array($option_index, $stored_values)? 1: 0;

			if($stored_value == 1){
				if($answer_output != '') $answer_output .= ', ';
				$answer_output       .= $label ;
			}
		}//end for each option

		if($answer_output != ''){
			$output .= '<p class="cbxmcratingreview_q_field_answer_option cbxmcratingreview_q_field_answer_option_multicheckbox" >'.$answer_output.'</p>';
		}
		$output .= '</div>'; //.cbxmcratingreview_q_field_label_multicheckboxes

		return $output;
	}//end method answer_display_multicheckbox_field

	/**
	 * Radio Field display
	 *
	 * @param      $question_index
	 * @param      $question
	 * @param      $stored_values
	 *
	 * @return string
	 */
	public static function public_display_radio_field( $question_index = 0, $question = array(), $stored_values = '' ) {
		$question_index     = intval( $question_index );
		$required           = isset( $question['required'] ) ? intval( $question['required'] ) : 0;
		$title              = isset( $question['title'] ) ? esc_attr( $question['title'] ) : sprintf( esc_html__( 'Untitled Question %d', 'cbxmcratingreview' ), intval( $question_index ) );
		$options            = isset( $question['options'] ) ? (array) $question['options'] : array();
		$required_text      = ( $required ) ? ' required ' : '';
		$required_data_text = ( $required ) ? ' data-rule-required="true" ' : '';

		$output = '<p class="cbxmcratingreview_q_field_label cbxmcratingreview_q_field_label_radio">' . $title . '</p>';
		$output .= '<div class="cbxmcratingreview_q_field_label_radios">';
		foreach ( $options as $option_index => $option ) {
			$label        = isset( $option['text'] ) ? esc_attr( $option['text'] ) : esc_html__( 'Untitled Option!', 'cbxmcratingreview' );

			$output       .= '<p class="cbxmcratingreview_q_field_label_option cbxmcratingreview_q_field_label_option_radio" for="cbxmcratingreview_q_field_option_' . $question_index . '_' . $option_index . '"><input ' . $required_text . $required_data_text . '  class="cbxmcratingreview_q_field_option cbxmcratingreview_q_field_option_radio" id="cbxmcratingreview_q_field_option_' . $question_index . '_' . $option_index . '"  name="cbxmcratingreview_ratingForm[questions][' . $question_index . ']" type="radio" ' . checked( $stored_values, $option_index, false ) . ' value="' . $option_index . '" class="form-checkbox" />' . $label . '</p>';
		}//end for each option
		$output .= '</div>'; //.cbxmcratingreview_q_field_label_radios

		return $output;


	}//end admin_display_radio_field

	/**
	 * Radio answer display
	 *
	 * @param      $question_index
	 * @param      $question
	 * @param      $stored_values
	 *
	 * @return string
	 */
	public static function answer_display_radio_field( $question_index = 0, $question = array(), $stored_values = '' ) {
		$question_index     = intval( $question_index );
		$required           = isset( $question['required'] ) ? intval( $question['required'] ) : 0;
		$title              = isset( $question['title'] ) ? esc_attr( $question['title'] ) : sprintf( esc_html__( 'Untitled Question %d', 'cbxmcratingreview' ), intval( $question_index ) );
		$options            = isset( $question['options'] ) ? (array) $question['options'] : array();
		$required_text      = ( $required ) ? ' required ' : '';
		$required_data_text = ( $required ) ? ' data-rule-required="true" ' : '';

		$output = '<p class="cbxmcratingreview_q_field_answer cbxmcratingreview_q_field_answer_radio">' . $title . '</p>';
		$output .= '<div class="cbxmcratingreview_q_field_answer_radios">';
		foreach ( $options as $option_index => $option ) {
			$label        = isset( $option['text'] ) ? esc_attr( $option['text'] ) : esc_html__( 'Untitled Option!', 'cbxmcratingreview' );

			if(intval($stored_values) === $option_index){
				//$output       .= '<p class="cbxmcratingreview_q_field_answer_option cbxmcratingreview_q_field_label_option_select" for="cbxmcratingreview_q_field_option_' . $question_index . '_' . $option_index . '">' . $label . ' : '.((intval($stored_values) === $option_index)? esc_html__('Yes', 'cbxmcratingreview'): esc_html__('No', 'cbxmcratingreview')).'</p>';
				$output       .= '<p class="cbxmcratingreview_q_field_answer_option cbxmcratingreview_q_field_label_option_select" for="cbxmcratingreview_q_field_option_' . $question_index . '_' . $option_index . '">' . $label . '</p>';
			}
		}//end for each option
		$output .= '</div>'; //.cbxmcratingreview_q_field_label_radios

		return $output;
	}//end answer_display_radio_field

	/**
	 * Select Field display
	 *
	 * @param      $question_index
	 * @param      $question
	 * @param      $stored_values
	 *
	 * @return string
	 */
	public static function public_display_select_field( $question_index = 0, $question = array(), $stored_values = '' ) {

		$question_index     = intval( $question_index );
		$required           = isset( $question['required'] ) ? intval( $question['required'] ) : 0;
		$multiple           = isset( $question['multiple'] ) ? intval( $question['multiple'] ) : 0;
		$title              = isset( $question['title'] ) ? esc_attr( $question['title'] ) : sprintf( esc_html__( 'Untitled Question %d', 'cbxmcratingreview' ), intval( $question_index ) );
		$options            = isset( $question['options'] ) ? (array) $question['options'] : array();
		$required_text      = ( $required ) ? ' required ' : '';
		$multiple_text      = ( $multiple ) ? ' multiple ' : '';
		$required_data_text = ( $required ) ? ' data-rule-required="true" ' : '';

		$name = 'cbxmcratingreview_ratingForm[questions][' . $question_index . ']';
		if ( $multiple ) {
			$name .= '[]';
		}


		if($multiple){
			$stored_values = maybe_unserialize($stored_values);
			if(!is_array($stored_values)) $stored_values = array();
			$stored_values = array_values($stored_values);
		}




		$output = '<label class="cbxmcratingreview_q_field_label cbxmcratingreview_q_field_label_select" for="cbxmcratingreview_q_field_' . $question_index . '">' . $title . '</label>';
		$output .= '<select name="' . $name . '" ' . $multiple_text . '  ' . $required_text . $required_data_text . ' class="cbxmcratingreview_q_field cbxmcratingreview_q_field_select" id="cbxmcratingreview_q_field_' . $question_index . '">';
		$output .= '<option value="">' . esc_html__( 'Please select', 'cbxmcratingreview' ) . '</option>';
		foreach ( $options as $option_index => $option ) {
			$label        = isset( $option['text'] ) ? esc_attr( $option['text'] ) : esc_html__( 'Untitled Option!', 'cbxmcratingreview' );
			//$stored_value = isset( $stored_values[ $option_index ] ) ? intval( $stored_values[ $option_index ] ) : '';

			if($multiple){
				$stored_value = in_array($option_index, $stored_values)? $option_index : '';

				$output .= '<option value="' . $option_index . '" ' . selected( $stored_value, $option_index, false ) . '">' . $label . '</option>';
			}
			else{
				$output .= '<option value="' . $option_index . '" ' . selected( $stored_values, $option_index, false ) . '">' . $label . '</option>';
			}


		}//end for each option
		$output .= '</select>'; //.cbxmcratingreview_q_field_label_select

		return $output;
	}//end method admin_display_select_field

	/**
	 * Select answer display
	 *
	 * @param      $question_index
	 * @param      $question
	 * @param      $stored_values
	 *
	 * @return string
	 */
	public static function answer_display_select_field( $question_index = 0, $question = array(), $stored_values = '' ) {

		$question_index     = intval( $question_index );
		$required           = isset( $question['required'] ) ? intval( $question['required'] ) : 0;
		$multiple           = isset( $question['multiple'] ) ? intval( $question['multiple'] ) : 0;
		$title              = isset( $question['title'] ) ? esc_attr( $question['title'] ) : sprintf( esc_html__( 'Untitled Question %d', 'cbxmcratingreview' ), intval( $question_index ) );
		$options            = isset( $question['options'] ) ? (array) $question['options'] : array();
		$required_text      = ( $required ) ? ' required ' : '';
		$multiple_text      = ( $multiple ) ? ' multiple ' : '';
		$required_data_text = ( $required ) ? ' data-rule-required="true" ' : '';

		$name = 'cbxmcratingreview_ratingForm[questions][' . $question_index . ']';
		if ( $multiple ) {
			$name .= '[]';
		}


		if($multiple){
			$stored_values = maybe_unserialize($stored_values);
			if(!is_array($stored_values)) $stored_values = array();
			$stored_values = array_values($stored_values);
		}


		$output = '<p class="cbxmcratingreview_q_field_answer cbxmcratingreview_q_field_answer_select" for="cbxmcratingreview_q_field_' . $question_index . '">' . $title . '</p>';
		//$output .= '<select name="' . $name . '" ' . $multiple_text . '  ' . $required_text . $required_data_text . ' class="cbxmcratingreview_q_field cbxmcratingreview_q_field_select" id="cbxmcratingreview_q_field_' . $question_index . '">';

		$output .= '<div class="cbxmcratingreview_q_field_label_selects">';

		$answer_output = '';
		foreach ( $options as $option_index => $option ) {
			$label        = isset( $option['text'] ) ? esc_attr( $option['text'] ) : esc_html__( 'Untitled Option!', 'cbxmcratingreview' );
			//$stored_value = isset( $stored_values[ $option_index ] ) ? intval( $stored_values[ $option_index ] ) : '';

			if($multiple){
				$stored_value = in_array($option_index, $stored_values)? $option_index : '';

				if(intval($stored_value) === $option_index){
					//$output       .= '<p class="cbxmcratingreview_q_field_answer_option cbxmcratingreview_q_field_answer_option_radio" for="cbxmcratingreview_q_field_option_' . $question_index . '_' . $option_index . '">' . $label . '</p>';
					if($answer_output != '') $answer_output .= ', ';
					$answer_output .= $label;
				}

			}
			else{
				//$output .= '<option value="' . $option_index . '" ' . selected( $stored_values, $option_index, false ) . '">' . $label . '</option>';

				if(intval($stored_values) === $option_index){
					$output       .= '<p class="cbxmcratingreview_q_field_answer_option cbxmcratingreview_q_field_answer_option_select" for="cbxmcratingreview_q_field_option_' . $question_index . '_' . $option_index . '">' . $label . '</p>';
				}


			}


		}//end for each option

		if($answer_output != ''){
			$output .= '<p class="cbxmcratingreview_q_field_answer_option cbxmcratingreview_q_field_answer_option_radio">'.$answer_output.'</p>';
		}

		//$output .= '</select>'; //.cbxmcratingreview_q_field_label_select
		$output .= '</div>';

		return $output;
	}//end method answer_display_select_field

	public static function arrayFilterRemoveEmpty($var){
		if($var == '') return false;
		else return true;

	}
}//end method CBXMCRatingReviewQuestionHelper