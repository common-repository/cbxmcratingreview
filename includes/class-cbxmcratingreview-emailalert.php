<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<?php

class CBXMCRatingReviewMailAlert {
	/**
	 * Send email to admin after new review is submitted
	 *
	 * @param array $new_review_info
	 *
	 * @return bool
	 */
	public static function sendNewReviewAdminEmailAlert( $new_review_info ) {
		$cbxmcratingreview_setting = new CBXMCRatingReviewSettings();

		$nr_admin_format = $cbxmcratingreview_setting->get_option( 'nr_admin_format', 'cbxmcratingreview_email_alert', 'html' );
		$nr_admin_from   = $cbxmcratingreview_setting->get_option( 'nr_admin_from', 'cbxmcratingreview_email_alert', get_bloginfo( 'admin_email' ) );
		$nr_admin_name   = $cbxmcratingreview_setting->get_option( 'nr_admin_name', 'cbxmcratingreview_email_alert', '{sitename}' );

		$nr_admin_to = $cbxmcratingreview_setting->get_option( 'nr_admin_to', 'cbxmcratingreview_email_alert', get_bloginfo( 'admin_email' ) );

		$nr_admin_reply_to = $cbxmcratingreview_setting->get_option( 'nr_admin_reply_to', 'cbxmcratingreview_email_alert', '{user_email}' );
		$nr_admin_reply_to = str_replace( '{user_email}', $new_review_info['user_email'], $nr_admin_reply_to ); //replace user email


		$nr_admin_cc  = $cbxmcratingreview_setting->get_option( 'nr_admin_cc', 'cbxmcratingreview_email_alert', '' );
		$nr_admin_bcc = $cbxmcratingreview_setting->get_option( 'nr_admin_bcc', 'cbxmcratingreview_email_alert', '' );

		$nr_admin_subject = $cbxmcratingreview_setting->get_option( 'nr_admin_subject', 'cbxmcratingreview_email_alert', esc_html__( 'New Review Notification', 'cbxmcratingreview' ) );
		$nr_admin_heading = $cbxmcratingreview_setting->get_option( 'nr_admin_heading', 'cbxmcratingreview_email_alert', esc_html__( 'New Review', 'cbxmcratingreview' ) );

		$nr_admin_body    = $cbxmcratingreview_setting->get_option( 'nr_admin_body', 'cbxmcratingreview_email_alert', '' );
		$admin_email_body = $nr_admin_body;

		$exprev_status_arr   = CBXMCRatingReviewHelper::ReviewStatusOptions();
		$modification_status = ( isset( $exprev_status_arr[ $new_review_info['status'] ] ) ? $exprev_status_arr[ $new_review_info['status'] ] : '' );

		//email body syntax parsing
		$admin_email_body = str_replace( '{score}', floatval( $new_review_info['score'] ), $admin_email_body ); //replace score
		$admin_email_body = str_replace( '{headline}', wp_unslash( $new_review_info['headline'] ), $admin_email_body ); //replace headline
		$admin_email_body = str_replace( '{comment}', wp_unslash( $new_review_info['comment'] ), $admin_email_body ); //replace comment
		$admin_email_body = str_replace( '{status}', $modification_status, $admin_email_body ); //replace status

		$post_url         = esc_url( get_permalink( $new_review_info['post_id'] ) );
		$post_title       = esc_html( get_the_title( $new_review_info['post_id'] ) );
		$post_url_link    = sprintf( __( '<a href="%s">' . $post_title . '</a>', 'cbxmcratingreview' ), $post_url );
		$admin_email_body = str_replace( '{post_url}', $post_url_link, $admin_email_body ); //replace post_url

		$review_edit_url      = esc_url( admin_url( 'admin.php?page=cbxmcratingreviewreviewlist&view=addedit&id=' . $new_review_info['id'] ) );
		$review_edit_url_link = sprintf( __( '<a href="%s">' . $review_edit_url . '</a>', 'cbxmcratingreview' ), $review_edit_url );
		$admin_email_body     = str_replace( '{review_edit_url}', $review_edit_url_link, $admin_email_body ); //replace review_edit_url

		//esp = email syntax parse
		$admin_email_body = apply_filters( 'cbxmcratingreview_review_rating_submitalert_adminemailbody', $admin_email_body, $new_review_info );
		//email body syntax parsing

		$emailTemplate = new CBXMCRatingReviewEmailTemplate();
		$message       = $emailTemplate->getHtmlTemplate();
		$message       = str_replace( '{mainbody}', $admin_email_body, $message ); //replace mainbody
		$message       = str_replace( '{emailheading}', $nr_admin_heading, $message ); //replace emailbody

		if ( $nr_admin_format == 'html' ) {
			$message = $emailTemplate->htmlEmeilify( $message );
		} elseif ( $nr_admin_format == 'plain' ) {
			$message = $emailTemplate->htmlEmeilify( $message );
			$message = Html2TextCBXMCRatingReview\Html2Text::convert( $message );
			$message = Html2TextCBXMCRatingReview\Html2Text::fixNewlines( $message );
		}

		$mail_helper        = new CBXMCRatingReviewMailHelper( $nr_admin_format, $nr_admin_from, $nr_admin_name );
		$header             = $mail_helper->email_header( $nr_admin_cc, $nr_admin_bcc, $nr_admin_reply_to );
		$admin_email_status = $mail_helper->wp_mail( $nr_admin_to, $nr_admin_subject, $message, $header );

		/*if ( $admin_email_status === true ) {

			$message    = array(
				'text' => esc_html__( 'Email sent Successfully', 'cbxmcratingreview' ),
				'type' => 'success'
			);
			$messages[] = $message;
		} else {
			$message    = array(
				'text' => esc_html__( 'Email sent failed. ', 'cbxmcratingreview' ),
				'type' => 'danger'
			);
			$messages[] = $message;
		}*/


		return $admin_email_status;
	}

	/**
	 * Send email to user after new review added to db based on setting
	 *
	 * @param $messages array
	 * @param $data     array
	 * @param $meta     array
	 *
	 * @return array
	 */
	public static function sendNewReviewUserEmailAlert( $new_review_info ) {


		$cbxmcratingreview_setting = new CBXMCRatingReviewSettings();

		$nr_user_format = $cbxmcratingreview_setting->get_option( 'nr_user_format', 'cbxmcratingreview_email_alert', 'html' );
		$nr_user_from   = $cbxmcratingreview_setting->get_option( 'nr_user_from', 'cbxmcratingreview_email_alert', get_bloginfo( 'admin_email' ) );
		$nr_user_name   = $cbxmcratingreview_setting->get_option( 'nr_user_name', 'cbxmcratingreview_email_alert', '{sitename}' );

		$nr_user_to = $cbxmcratingreview_setting->get_option( 'nr_user_to', 'cbxmcratingreview_email_alert', '{user_email}' );
		$nr_user_to = str_replace( '{user_email}', $new_review_info['user_email'], $nr_user_to ); //replace user email

		$nr_user_reply_to = $cbxmcratingreview_setting->get_option( 'nr_user_reply_to', 'cbxmcratingreview_email_alert', get_bloginfo( 'admin_email' ) );

		$nr_user_subject = $cbxmcratingreview_setting->get_option( 'nr_user_subject', 'cbxmcratingreview_email_alert', esc_html__( 'New Review Notification', 'cbxmcratingreview' ) );
		$nr_user_heading = $cbxmcratingreview_setting->get_option( 'nr_user_heading', 'cbxmcratingreview_email_alert', esc_html__( 'New Review', 'cbxmcratingreview' ) );

		$nr_user_body    = $cbxmcratingreview_setting->get_option( 'nr_user_body', 'cbxmcratingreview_email_alert', '' );
		$user_email_body = $nr_user_body;


		$exprev_status_arr   = CBXMCRatingReviewHelper::ReviewStatusOptions();
		$modification_status = ( isset( $exprev_status_arr[ $new_review_info['status'] ] ) ? $exprev_status_arr[ $new_review_info['status'] ] : '' );

		//email body syntax parsing
		$user_email_body = str_replace( '{user_name}', $new_review_info['display_name'], $user_email_body ); //replace name
		$user_email_body = str_replace( '{user_email}', $new_review_info['user_email'], $user_email_body ); //replace name
		$user_email_body = str_replace( '{score}', floatval( $new_review_info['score'] ), $user_email_body ); //replace score
		$user_email_body = str_replace( '{headline}', wp_unslash( $new_review_info['headline'] ), $user_email_body ); //replace headline
		$user_email_body = str_replace( '{comment}', wp_unslash( $new_review_info['comment'] ), $user_email_body ); //replace comment
		$user_email_body = str_replace( '{status}', $modification_status, $user_email_body ); //replace status

		$post_url      = esc_url( get_permalink( $new_review_info['post_id'] ) );
		$post_title    = esc_html( get_the_title( $new_review_info['post_id'] ) );
		$post_url_link = sprintf( __( '<a href="%s">' . $post_title . '</a>', 'cbxmcratingreview' ), $post_url );

		$user_email_body = str_replace( '{post_url}', $post_url_link, $user_email_body ); //replace post_url

		//esp = email syntax parse
		$user_email_body = apply_filters( 'cbxmcratingreview_review_rating_submitalert_useremailbody', $user_email_body, $new_review_info );
		//email body syntax parsing

		$emailTemplate = new CBXMCRatingReviewEmailTemplate();
		$message       = $emailTemplate->getHtmlTemplate();
		$message       = str_replace( '{mainbody}', $user_email_body, $message ); //replace mainbody
		$message       = str_replace( '{emailheading}', $nr_user_heading, $message ); //replace emailbody

		if ( $nr_user_format == 'html' ) {
			$message = $emailTemplate->htmlEmeilify( $message );
		} elseif ( $nr_user_format == 'plain' ) {
			$message = $emailTemplate->htmlEmeilify( $message );
			$message = Html2TextCBXMCRatingReview\Html2Text::convert( $message );
			$message = Html2TextCBXMCRatingReview\Html2Text::fixNewlines( $message );
		}

		$mail_helper = new CBXMCRatingReviewMailHelper( $nr_user_format, $nr_user_from, $nr_user_name );
		$header      = $mail_helper->email_header( '', '', $nr_user_reply_to );

		$user_email_status = $mail_helper->wp_mail( $nr_user_to, $nr_user_subject, $message, $header );


		return $user_email_status;
	}

	/**
	 * Send email to user after review status modified based on setting
	 *
	 * @param $new_review_info
	 *
	 * @return array
	 */
	public static function sendReviewStatusUpdateUserEmailAlert( $new_review_info ) {

		$cbxmcratingreview_setting = new CBXMCRatingReviewSettings();

		$rsc_user_format = $cbxmcratingreview_setting->get_option( 'rsc_user_format', 'cbxmcratingreview_email_alert', 'html' );
		$rsc_user_from   = $cbxmcratingreview_setting->get_option( 'rsc_user_from', 'cbxmcratingreview_email_alert', get_bloginfo( 'admin_email' ) );
		$rsc_user_name   = $cbxmcratingreview_setting->get_option( 'rsc_user_name', 'cbxmcratingreview_email_alert', '{sitename}' );

		$rsc_user_to = $cbxmcratingreview_setting->get_option( 'rsc_user_to', 'cbxmcratingreview_email_alert', '{user_email}' );
		$rsc_user_to = str_replace( '{user_email}', $new_review_info['user_email'], $rsc_user_to ); //replace user email

		$rsc_user_reply_to = $cbxmcratingreview_setting->get_option( 'rsc_user_reply_to', 'cbxmcratingreview_email_alert', get_bloginfo( 'admin_email' ) );

		$rsc_user_subject = $cbxmcratingreview_setting->get_option( 'rsc_user_subject', 'cbxmcratingreview_email_alert', esc_html__( 'Review Status Change Notification', 'cbxmcratingreview' ) );
		$rsc_user_heading = $cbxmcratingreview_setting->get_option( 'rsc_user_heading', 'cbxmcratingreview_email_alert', esc_html__( 'Review Status Changed', 'cbxmcratingreview' ) );

		$rsc_user_body   = $cbxmcratingreview_setting->get_option( 'rsc_user_body', 'cbxmcratingreview_email_alert', '' );
		$user_email_body = $rsc_user_body;


		$exprev_status_arr   = CBXMCRatingReviewHelper::ReviewStatusOptions();
		$modification_status = ( isset( $exprev_status_arr[ $new_review_info['status'] ] ) ? $exprev_status_arr[ $new_review_info['status'] ] : '' );

		//email body syntax parsing
		$user_email_body = str_replace( '{user_name}', $new_review_info['display_name'], $user_email_body ); //replace name
		$user_email_body = str_replace( '{user_email}', $new_review_info['user_email'], $user_email_body ); //replace name
		$user_email_body = str_replace( '{score}', floatval( $new_review_info['score'] ), $user_email_body ); //replace score
		$user_email_body = str_replace( '{headline}', wp_unslash( $new_review_info['headline'] ), $user_email_body ); //replace headline
		$user_email_body = str_replace( '{comment}', wp_unslash( $new_review_info['comment'] ), $user_email_body ); //replace comment
		$user_email_body = str_replace( '{status}', $modification_status, $user_email_body ); //replace status


		$post_url      = esc_url( get_permalink( $new_review_info['post_id'] ) );
		$post_title    = esc_html( get_the_title( $new_review_info['post_id'] ) );
		$post_url_link = sprintf( __( '<a href="%s">' . $post_title . '</a>', 'cbxmcratingreview' ), $post_url );

		$user_email_body = str_replace( '{post_url}', $post_url_link, $user_email_body ); //replace post_url

		//esp = email syntax parse
		$user_email_body = apply_filters( 'cbxmcratingreview_review_rating_adminedit_useremailbody', $user_email_body, $new_review_info );
		//email body syntax parsing

		$emailTemplate = new CBXMCRatingReviewEmailTemplate();
		$message       = $emailTemplate->getHtmlTemplate();
		$message       = str_replace( '{mainbody}', $user_email_body, $message ); //replace mainbody
		$message       = str_replace( '{emailheading}', $rsc_user_heading, $message ); //replace emailbody

		if ( $rsc_user_format == 'html' ) {
			$message = $emailTemplate->htmlEmeilify( $message );
		} elseif ( $rsc_user_format == 'plain' ) {
			$message = $emailTemplate->htmlEmeilify( $message );
			$message = Html2TextCBXMCRatingReview\Html2Text::convert( $message );
			$message = Html2TextCBXMCRatingReview\Html2Text::fixNewlines( $message );
		}

		$mail_helper = new CBXMCRatingReviewMailHelper( $rsc_user_format, $rsc_user_from, $rsc_user_name );
		$header      = $mail_helper->email_header( '', '', $rsc_user_reply_to );

		$user_email_status = $mail_helper->wp_mail( $rsc_user_to, $rsc_user_subject, $message, $header );


		return $user_email_status;
	}//end method sendReviewStatusUpdateUserEmailAlert

}//end class CBXMCRatingReviewMailAlert