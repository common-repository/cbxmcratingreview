<?php

	/**
	 * The admin-specific functionality of the plugin.
	 *
	 * @link       https://codeboxr.com
	 * @since      1.0.0
	 *
	 * @package    CBXMCRatingReview
	 * @subpackage CBXMCRatingReview/admin
	 */

	/**
	 * The admin-specific functionality of the plugin.
	 *
	 * Defines the plugin name, version, and two examples hooks for how to
	 * enqueue the admin-specific stylesheet and JavaScript.
	 *
	 * @package    CBXMCRatingReview
	 * @subpackage CBXMCRatingReview/admin
	 * @author     Sabuj Kundu <sabuj@codeboxr.com>
	 */
	class CBXMCRatingReview_Admin {

		/**
		 * The setting of this plugin.
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      string $version The current version of this plugin.
		 */
		public $setting;
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

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 *
		 * @param      string $plugin_name The name of this plugin.
		 * @param      string $version     The version of this plugin.
		 */
		public function __construct( $plugin_name, $version ) {

			$this->plugin_name = $plugin_name;
			$this->version     = $version;

			$this->setting = new CBXMCRatingReviewSettings();
		}//end of constructor

		/**
		 * Admin rating form listing view
		 */
		public static function display_admin_form_listing_page() {
			if ( isset( $_GET['view'] ) && $_GET['view'] == 'addedit' ) {
				//$template_name = apply_filters( 'cbxmcratingreview_tpl_admin-rating-review-rating-form-edit', CBXMCRATINGREVIEW_ROOT_PATH . 'templates/admin/admin-rating-review-rating-form-edit.php' );
				$template_name = cbxmcratingreview_locate_template( 'admin/admin-rating-review-rating-form-edit.php' );
			} else {
				//$template_name = apply_filters( 'cbxmcratingreview_tpl_admin-rating-review-rating-forms', CBXMCRATINGREVIEW_ROOT_PATH . 'templates/admin/admin-rating-review-rating-forms.php' );
				$template_name = cbxmcratingreview_locate_template( 'admin/admin-rating-review-rating-forms.php' );
			}

			include( $template_name );
		}//end method setting_init

		public function setting_init() {
			//set the settings
			$this->setting->set_sections( $this->get_settings_sections() );
			$this->setting->set_fields( $this->get_settings_fields() );
			//initialize settings
			$this->setting->admin_init();
		}//end method get_settings_sections

		/**
		 * Global Setting Sections and titles
		 *
		 * @return type
		 */
		public function get_settings_sections() {
			$settings_sections = array(
				array(
					'id'    => 'cbxmcratingreview_common_config',
					'title' => esc_html__( 'General', 'cbxmcratingreview' )
				),
				array(
					'id'    => 'cbxmcratingreview_global_email',
					'title' => esc_html__( 'Email Template', 'cbxmcratingreview' )
				),
				array(
					'id'    => 'cbxmcratingreview_email_alert',
					'title' => esc_html__( 'Review Email', 'cbxmcratingreview' )
				),
				array(
					'id'    => 'cbxmcratingreview_tools',
					'title' => esc_html__( 'Pages & Tools', 'cbxmcratingreview' )
				)
			);

			return apply_filters( 'cbxmcratingreview_setting_sections', $settings_sections );
		}//end method get_settings_fields

		/**
		 * Global Setting Fields
		 *
		 * @return array
		 */
		public function get_settings_fields() {

			$cbxmcratingreview_setting = $this->setting;

			$reviews_status_options  = CBXMCRatingReviewHelper::ReviewStatusOptions();
			$reviews_positive_scores = CBXMCRatingReviewHelper::ReviewPositiveScores();


			$user_roles_no_guest   = CBXMCRatingReviewHelper::user_roles( false, false );
			$user_roles_with_guest = CBXMCRatingReviewHelper::user_roles( false, true );
			$post_types            = CBXMCRatingReviewHelper::post_types( false );

			$rating_forms = CBXMCRatingReviewHelper::getRatingFormsList();




			//$rating_forms[''] = esc_html__('Select default rating form', 'cbxmcratingreview');


			$reset_data_link     = add_query_arg( 'cbxmcratingreview_fullreset', 1, admin_url( 'admin.php?page=cbxmcratingreviewsettings' ) );
			$mirgation_data_link = add_query_arg( 'cbxmcratingreview_migrate', 1, admin_url( 'admin.php?page=cbxmcratingreviewsettings' ) );

			$table_names = CBXMCRatingReviewHelper::getAllDBTablesList();
			$table_keys  = CBXMCRatingReviewHelper::getAllDBTablesKeyList();

			$table_html = '<p><a id="cbxmcratingreview_info_trig" href="#">' . esc_html__( 'Show/hide details', 'cbxmcratingreview' ) . '</a></p>';
			$table_html .= '<div id="cbxmcratingreview_resetinfo" style="display: none;">';

			$table_html .= '<p id="cbxmcratingreview_info"><strong>' . esc_html__( 'Following database tables will be reset/deleted and then re-created.', 'cbxmcratingreview' ) . '</strong></p>';

			$table_counter = 1;
			foreach ( $table_names as $key => $value ) {
				$key        = isset( $table_keys[ $key ] ) ? esc_html( $table_keys[ $key ] ) : $key;
				$table_html .= '<p>' . str_pad( $table_counter, 2, '0', STR_PAD_LEFT ) . '. ' . $key . ' - (<code>' . $value . '</code>)</p>';
				$table_counter ++;
			}

			$table_html .= '<p><strong>' . esc_html__( 'Following option values created by this plugin(including addon) from wordpress core option table', 'cbxmcratingreview' ) . '</strong></p>';


			$option_values = CBXMCRatingReviewHelper::getAllOptionNames();
			$table_counter = 1;
			foreach ( $option_values as $key => $value ) {
				$table_html .= '<p>' . str_pad( $table_counter, 2, '0', STR_PAD_LEFT ) . '. ' . $value['option_name'] . ' - ' . $value['option_id'] . ' - (<code style="overflow-wrap: break-word; word-break: break-all;">' . $value['option_value'] . '</code>)</p>';

				$table_counter ++;
			}

			$table_html .= '<p><strong>' . esc_html__( 'Following meta key created by this plugin(including addon) from wordpress core post meta table', 'cbxmcratingreview' ) . '</strong></p>';
			$meta_keys  = CBXMCRatingReviewHelper::getMetaKeys();

			$table_counter = 1;
			foreach ( $meta_keys as $key => $value ) {
				$table_html .= '<p>' . str_pad( $table_counter, 2, '0', STR_PAD_LEFT ) . '. ' . $key . ' - (<code style="overflow-wrap: break-word; word-break: break-all;">' . $value . '</code>)</p>';
				$table_counter ++;
			}

			$table_html .= '</div>';

			$migrate_note = '<p>' . sprintf( __( 'Please read the <a target="_blank" href="%s">migration guide</a> before migrate', 'cbxmcratingreview' ), 'https://codeboxr.com/old-cbx-multi-criteria-rating-review-plugin-data-migration-to-new/' ) . '</p>';

			$cbxmcratingreview_common_config_fields = array(
				/*'logging_method'      => array(
					'name'  => 'logging_method',
					'label' => esc_html__( 'User Presence Tracking', 'cbxmcratingreview' ),
					'desc'  => esc_html__( 'Log user rating by ip or cookie or both to protect multiple rating, useful for guest rating', 'cbxmcratingreview' ),
					'type'  => 'multiselect',

					'default' => array( 'ip', 'cookie' ),
					'options' => array(
						'ip'     => esc_html__( 'IP', 'cbxmcratingreview' ),
						'cookie' => esc_html__( 'Cookie', 'cbxmcratingreview' )
					),

				),*/
				'default_form'        => array(
					'name'        => 'default_form',
					'label'       => esc_html__( 'Default Rating Form', 'cbxmcratingreview' ),
					'desc'        => esc_html__( 'Please choose default rating form. ', 'cbxmcratingreview' ),
					'type'        => 'select',
					'default'     => 0,
					'options'     => $rating_forms,
					'placeholder' => esc_html__( 'Select Form', 'cbxmcratingreview' )
				),
				/*'post_types'               => array(
					'name'        => 'post_types',
					'label'       => esc_html__( 'Post Type Support', 'cbxmcratingreview' ),
					'desc'        => esc_html__( 'Which post types can have the rating & review features', 'cbxmcratingreview' ),
					'type'        => 'multiselect',
					'default'     => array( 'post' ),
					'options'     => $post_types,
					'optgroup'    => 1,
					'placeholder' => esc_html__( 'Select Post Type', 'cbxmcratingreview' )
				),
				'user_roles_rate'          => array(
					'name'        => 'user_roles_rate',
					'label'       => esc_html__( 'Who Can give Rate & Review', 'cbxmcratingreview' ),
					'desc'        => esc_html__( 'which user role will have vote capability', 'cbxmcratingreview' ),
					'type'        => 'multiselect',
					'default'     => array(
						'administrator',
						'editor',
						'author',
						'contributor',
						'subscriber'
					),
					'options'     => $user_roles_no_guest,
					'optgroup'    => 1,
					'placeholder' => esc_html__( 'Select user roles', 'cbxmcratingreview' )
				),
				'user_roles_view'          => array(
					'name'        => 'user_roles_view',
					'label'       => esc_html__( 'Who Can View Rating & Review', 'cbxmcratingreview' ),
					'desc'        => esc_html__( 'Which user role will have view capability', 'cbxmcratingreview' ),
					'type'        => 'multiselect',
					'optgroup'    => 0,
					'default'     => array(
						'administrator',
						'editor',
						'author',
						'contributor',
						'subscriber',
						'guest'
					),
					'options'     => $user_roles_with_guest,
					'optgroup'    => 1,
					'placeholder' => esc_html__( 'Select user roles', 'cbxmcratingreview' )
				),*/
				'allow_review_delete' => array(
					'name'    => 'allow_review_delete',
					'label'   => esc_html__( 'Allow Review Delete', 'cbxmcratingreview' ),
					'desc'    => esc_html__( 'Allow user delete review from frontend', 'cbxmcratingreview' ),
					'type'    => 'select',
					'default' => '1',
					'options' => array(
						'1' => esc_html__( 'Yes', 'cbxmcratingreview' ),
						'0' => esc_html__( 'No', 'cbxmcratingreview' ),
					)
				),
				'half_rating'         => array(
					'name'    => 'half_rating',
					'label'   => esc_html__( 'Allow Half Rating', 'cbxmcratingreview' ),
					'desc'    => esc_html__( 'If half rating enabled, user can rate .5, 1.5, 2.5, 3.5, 4.5 with regular 1, 2,3,4,5 values.', 'cbxmcratingreview' ),
					'type'    => 'select',
					'default' => 0,
					'options' => array(
						'1' => esc_html__( 'Yes', 'cbxmcratingreview' ),
						'0' => esc_html__( 'No', 'cbxmcratingreview' ),
					)
				),
				'default_status'      => array(
					'name'    => 'default_status',
					'label'   => esc_html__( 'Default Review Status', 'cbxmcratingreview' ),
					'desc'    => esc_html__( 'What will be status when a new review is written?', 'cbxmcratingreview' ),
					'type'    => 'select',
					'default' => 1,
					'options' => $reviews_status_options
				),
				'show_headline'       => array(
					'name'    => 'show_headline',
					'label'   => esc_html__( 'Show Headline', 'cbxmcratingreview' ),
					'desc'    => esc_html__( 'Show/hide review headline in rating form', 'cbxmcratingreview' ),
					'type'    => 'select',
					'default' => '1',
					'options' => array(
						'1' => esc_html__( 'Yes', 'cbxmcratingreview' ),
						'0' => esc_html__( 'No', 'cbxmcratingreview' ),
					)
				),
				'require_headline'    => array(
					'name'    => 'require_headline',
					'label'   => esc_html__( 'Headline Required', 'cbxmcratingreview' ),
					'desc'    => esc_html__( 'Is headline mandatory to write a review?', 'cbxmcratingreview' ),
					'type'    => 'select',
					'default' => '1',
					'options' => array(
						'1' => esc_html__( 'Yes', 'cbxmcratingreview' ),
						'0' => esc_html__( 'No', 'cbxmcratingreview' ),
					)
				),
				'show_comment'        => array(
					'name'    => 'show_comment',
					'label'   => esc_html__( 'Show Comment', 'cbxmcratingreview' ),
					'desc'    => esc_html__( 'Show/hide comment in rating form', 'cbxmcratingreview' ),
					'type'    => 'select',
					'default' => '1',
					'options' => array(
						'1' => esc_html__( 'Yes', 'cbxmcratingreview' ),
						'0' => esc_html__( 'No', 'cbxmcratingreview' ),
					)
				),
				'require_comment'     => array(
					'name'    => 'require_comment',
					'label'   => esc_html__( 'Comment Required', 'cbxmcratingreview' ),
					'desc'    => esc_html__( 'Is comment mandatory to write a review?', 'cbxmcratingreview' ),
					'type'    => 'select',
					'default' => '1',
					'options' => array(
						'1' => esc_html__( 'Yes', 'cbxmcratingreview' ),
						'0' => esc_html__( 'No', 'cbxmcratingreview' ),
					)
				),

				'enable_positive_critical' => array(
					'name'    => 'enable_positive_critical',
					'label'   => esc_html__( 'Enable Positive/Critical Score', 'cbxmcratingreview' ),
					'desc'    => esc_html__( 'Enable positivive or critial score functionality', 'cbxmcratingreview' ),
					'type'    => 'select',
					'default' => '1',
					'options' => array(
						'1' => esc_html__( 'Yes', 'cbxmcratingreview' ),
						'0' => esc_html__( 'No', 'cbxmcratingreview' ),
					)
				),
				'positive_score'           => array(
					'name'    => 'positive_score',
					'label'   => esc_html__( 'Positve Review Score value', 'cbxmcratingreview' ),
					'desc'    => esc_html__( 'Select minimum score value for a positive review', 'cbxmcratingreview' ),
					'type'    => 'select',
					'default' => 4,
					'options' => $reviews_positive_scores
				),
				'default_per_page'         => array(
					'name'    => 'default_per_page',
					'label'   => esc_html__( 'Reviews Per Page', 'cbxmcratingreview' ),
					'desc'    => esc_html__( 'Default number of reviews per page in pagination', 'cbxmcratingreview' ),
					'type'    => 'text',
					'default' => '10'
				),
				'show_review_filter'       => array(
					'name'    => 'show_review_filter',
					'label'   => esc_html__( 'Show Review Filter', 'cbxmcratingreview' ),
					'desc'    => esc_html__( 'Show filter box in review listing', 'cbxmcratingreview' ),
					'type'    => 'select',
					'default' => '1',
					'options' => array(
						'1' => esc_html__( 'Yes', 'cbxmcratingreview' ),
						'0' => esc_html__( 'No', 'cbxmcratingreview' ),
					)
				),

			);


			$cbxmcratingreview_global_email_fields = array(
				'headerimage'         => array(
					'name'    => 'headerimage',
					'label'   => esc_html__( 'Header Image', 'cbxmcratingreview' ),
					'desc'    => esc_html__( 'Url To email you want to show as email header.Upload Image by media uploader.', 'cbxmcratingreview' ),
					'type'    => 'file',
					'default' => ''
				),
				'footertext'          => array(
					'name'    => 'footertext',
					'label'   => esc_html__( 'Footer Text', 'cbxmcratingreview' ),
					'desc'    => __( 'The text to appear at the email footer. Syntax available - <code>{sitename}</code>', 'cbxmcratingreview' ),
					'type'    => 'wysiwyg',
					'default' => '{sitename}'
				),
				'basecolor'           => array(
					'name'    => 'basecolor',
					'label'   => esc_html__( 'Base Color', 'cbxmcratingreview' ),
					'desc'    => esc_html__( 'The base color of the email.', 'cbxmcratingreview' ),
					'type'    => 'color',
					'default' => '#557da1'
				),
				'backgroundcolor'     => array(
					'name'    => 'backgroundcolor',
					'label'   => esc_html__( 'Background Color', 'cbxmcratingreview' ),
					'desc'    => esc_html__( 'The background color of the email.', 'cbxmcratingreview' ),
					'type'    => 'color',
					'default' => '#f5f5f5'
				),
				'bodybackgroundcolor' => array(
					'name'    => 'bodybackgroundcolor',
					'label'   => esc_html__( 'Body Background Color', 'cbxmcratingreview' ),
					'desc'    => esc_html__( 'The background colour of the main body of email.', 'cbxmcratingreview' ),
					'type'    => 'color',
					'default' => '#fdfdfd'
				),
				'bodytextcolor'       => array(
					'name'    => 'bodytextcolor',
					'label'   => esc_html__( 'Body Text Color', 'cbxmcratingreview' ),
					'desc'    => esc_html__( 'The body text colour of the main body of email.', 'cbxmcratingreview' ),
					'type'    => 'color',
					'default' => '#505050'
				),
			);

			$cbxmcratingreview_email_alert_fields = array(
				'nr_admin_status_heading' => array(
					'name'    => 'nr_admin_status_heading',
					'label'   => esc_html__( 'New Review Admin Email Alert', 'cbxmcratingreview-comment' ),
					'desc'    => esc_html__( 'New review admin email alert configuration', 'cbxmcratingreview-comment' ),
					'type'    => 'heading',
					'default' => ''
				),
				'nr_admin_status'         => array(
					'name'    => 'nr_admin_status',
					'label'   => esc_html__( 'On/Off', 'cbxmcratingreview' ),
					'desc'    => esc_html__( 'Alert Status', 'cbxmcratingreview' ),
					'type'    => 'checkbox',
					'default' => 'on'
				),
				'nr_admin_format'         => array(
					'name'    => 'nr_admin_format',
					'label'   => esc_html__( 'E-mail Format', 'cbxmcratingreview' ),
					'desc'    => esc_html__( 'Select the format of the E-mail.', 'cbxmcratingreview' ),
					'type'    => 'select',
					'default' => 'html',
					'options' => array(
						'html'      => esc_html__( 'HTML', 'cbxmcratingreview' ),
						'plain'     => esc_html__( 'Plain', 'cbxmcratingreview' ),
						'multipart' => esc_html__( 'Multipart/mixed(attachment)', 'cbxmcratingreview' ),
					)
				),
				'nr_admin_name'           => array(
					'name'    => 'nr_admin_name',
					'label'   => esc_html__( 'From Name', 'cbxmcratingreview' ),
					'desc'    => __( 'Name of sender. Syntax available - <code>{sitename}</code>', 'cbxmcratingreview' ),
					'type'    => 'text',
					'default' => '{sitename}'
				),
				'nr_admin_from'           => array(
					'name'    => 'nr_admin_from',
					'label'   => esc_html__( 'From Email', 'cbxmcratingreview' ),
					'desc'    => esc_html__( 'From Email Address.', 'cbxmcratingreview' ),
					'type'    => 'text',
					'default' => get_bloginfo( 'admin_email' )
				),
				'nr_admin_to'             => array(
					'name'    => 'nr_admin_to',
					'label'   => esc_html__( 'To Email', 'cbxmcratingreview' ),
					'desc'    => esc_html__( 'To Email Address.', 'cbxmcratingreview' ),
					'type'    => 'text',
					'default' => get_bloginfo( 'admin_email' )

				),
				'nr_admin_reply_to'       => array(
					'name'    => 'nr_admin_reply_to',
					'label'   => esc_html__( 'Reply To', 'cbxmcratingreview' ),
					'desc'    => __( 'Reply To Email Address. Syntax available - <code>{user_email}</code>', 'cbxmcratingreview' ),
					'type'    => 'text',
					'default' => '{user_email}'
				),
				'nr_admin_subject'        => array(
					'name'    => 'nr_admin_subject',
					'label'   => esc_html__( 'Subject', 'cbxmcratingreview' ),
					'desc'    => esc_html__( 'Email Subject.', 'cbxmcratingreview' ),
					'type'    => 'text',
					'default' => esc_html__( 'New Review Notification', 'cbxmcratingreview' )
				),
				'nr_admin_heading'        => array(
					'name'    => 'nr_admin_heading',
					'label'   => esc_html__( 'Heading', 'cbxmcratingreview' ),
					'desc'    => esc_html__( 'Email Template heading.', 'cbxmcratingreview' ),
					'type'    => 'text',
					'default' => esc_html__( 'New Review', 'cbxmcratingreview' )
				),
				'nr_admin_body'           => array(
					'name'    => 'nr_admin_body',
					'label'   => esc_html__( 'Body', 'cbxmcratingreview' ),
					'desc'    => __( 'Email Body.  Syntax available - <code>{score}, {headline}, {comment}, {status}, {post_url}, {review_edit_url}</code>', 'cbxmcratingreview' ),
					'type'    => 'wysiwyg',
					'default' => 'Hi, Admin
                            
A new review is made. Here is the details:

Rating: {score}
Title: {headline}
Review: {comment}
Review status: {status}

Post: {post_url}
Review: {review_edit_url}

Please check & do necessary steps and give feedback to client.
Thank you.'
				),
				'nr_admin_cc'             => array(
					'name'    => 'nr_admin_cc',
					'label'   => esc_html__( 'CC', 'cbxmcratingreview' ),
					'desc'    => esc_html__( 'Email CC, for multiple use comma.', 'cbxmcratingreview' ),
					'type'    => 'text',
					'default' => ''
				),
				'nr_admin_bcc'            => array(
					'name'    => 'nr_admin_bcc',
					'label'   => esc_html__( 'BCC', 'cbxmcratingreview' ),
					'desc'    => esc_html__( 'Email BCC, for multiple use comma', 'cbxmcratingreview' ),
					'type'    => 'text',
					'default' => ''
				),
				'nr_user_status_heading'  => array(
					'name'    => 'nr_user_status_heading',
					'label'   => esc_html__( 'New Review User Email Alert', 'cbxmcratingreview-comment' ),
					'desc'    => esc_html__( 'New review user email alert configuration', 'cbxmcratingreview-comment' ),
					'type'    => 'heading',
					'default' => ''
				),
				'nr_user_status'          => array(
					'name'    => 'nr_user_status',
					'label'   => esc_html__( 'On/Off', 'cbxmcratingreview' ),
					'desc'    => esc_html__( 'Status of Email', 'cbxmcratingreview' ),
					'type'    => 'checkbox',
					'default' => 'on'
				),
				'nr_user_format'          => array(
					'name'    => 'nr_user_format',
					'label'   => esc_html__( 'E-mail Format', 'cbxmcratingreview' ),
					'desc'    => esc_html__( 'Select the format of the E-mail.', 'cbxmcratingreview' ),
					'type'    => 'select',
					'default' => 'html',
					'options' => array(
						'html'      => esc_html__( 'HTML', 'cbxmcratingreview' ),
						'plain'     => esc_html__( 'Plain', 'cbxmcratingreview' ),
						'multipart' => esc_html__( 'Multipart/mixed(attachment)', 'cbxmcratingreview' ),
					)
				),
				'nr_user_name'            => array(
					'name'    => 'nr_user_name',
					'label'   => esc_html__( 'From Name', 'cbxmcratingreview' ),
					'desc'    => __( 'Name of sender.  Syntax available - <code>{sitename}</code>', 'cbxmcratingreview' ),
					'type'    => 'text',
					'default' => '{sitename}'
				),
				'nr_user_from'            => array(
					'name'    => 'nr_user_from',
					'label'   => esc_html__( 'From Email', 'cbxmcratingreview' ),
					'desc'    => esc_html__( 'From Email Address.', 'cbxmcratingreview' ),
					'type'    => 'text',
					'default' => get_bloginfo( 'admin_email' )
				),
				'nr_user_to'              => array(
					'name'    => 'nr_user_to',
					'label'   => esc_html__( 'To Email', 'cbxmcratingreview' ),
					'desc'    => __( 'To Email Address. Syntax available - <code>{user_email}</code>', 'cbxmcratingreview' ),
					'type'    => 'text',
					'default' => '{user_email}'
				),
				'nr_user_reply_to'        => array(
					'name'    => 'nr_user_reply_to',
					'label'   => esc_html__( 'Reply To', 'cbxmcratingreview' ),
					'desc'    => esc_html__( 'Reply To Email Address.', 'cbxmcratingreview' ),
					'type'    => 'text',
					'default' => get_bloginfo( 'admin_email' )
				),
				'nr_user_subject'         => array(
					'name'     => 'nr_user_subject',
					'label'    => esc_html__( 'New Review Email Subject', 'cbxmcratingreview' ),
					'desc'     => esc_html__( 'Email subject user will receive when they make an initial review.', 'cbxmcratingreview' ),
					'type'     => 'text',
					'default'  => esc_html__( 'New Review Notification', 'cbxmcratingreview' ),
					'desc_tip' => true
				),
				'nr_user_heading'         => array(
					'name'    => 'nr_user_heading',
					'label'   => esc_html__( 'New Review Email Heading', 'cbxmcratingreview' ),
					'desc'    => esc_html__( 'Email heading user will receive when they make an initial review.', 'cbxmcratingreview' ),
					'type'    => 'text',
					'default' => esc_html__( 'New Review', 'cbxmcratingreview' )
				),
				'nr_user_body'            => array(
					'name'    => 'nr_user_body',
					'label'   => esc_html__( 'New Review Email Body', 'cbxmcratingreview' ),
					'desc'    => sprintf( __( 'Email content user will receive when they make an initial review. Syntax available - <code>{user_name}, {user_email}, {score}, {headline}, {comment}, {status}, {post_url}', 'cbxmcratingreview' ), 1 ),
					'type'    => 'wysiwyg',
					'default' => 'Hi, {user_name}
                            
We got a review for email address {user_email}.

Review Details: 

Rating: {score}
Title: {headline}
Review: {comment}
Review status: {status}

Post: {post_url}

We will check and get back to you soon.
Thank you.'

				),
				'rsc_user_status_heading' => array(
					'name'    => 'rsc_user_status_heading',
					'label'   => esc_html__( 'Review Status Modification User Email Alert', 'cbxmcratingreview-comment' ),
					'desc'    => esc_html__( 'User gets email for review status modification', 'cbxmcratingreview-comment' ),
					'type'    => 'heading',
					'default' => ''
				),

				'rsc_user_status'   => array(
					'name'    => 'rsc_user_status',
					'label'   => esc_html__( 'On/Off', 'cbxmcratingreview' ),
					'desc'    => esc_html__( 'Status of Email', 'cbxmcratingreview' ),
					'type'    => 'checkbox',
					'default' => 'on'
				),
				'rsc_user_format'   => array(
					'name'    => 'rsc_user_format',
					'label'   => esc_html__( 'E-mail Format', 'cbxmcratingreview' ),
					'desc'    => esc_html__( 'Select the format of the E-mail.', 'cbxmcratingreview' ),
					'type'    => 'select',
					'default' => 'html',
					'options' => array(
						'html'      => esc_html__( 'HTML', 'cbxmcratingreview' ),
						'plain'     => esc_html__( 'Plain', 'cbxmcratingreview' ),
						'multipart' => esc_html__( 'Multipart/mixed(attachment)', 'cbxmcratingreview' ),
					)
				),
				'rsc_user_name'     => array(
					'name'    => 'rsc_user_name',
					'label'   => esc_html__( 'From Name', 'cbxmcratingreview' ),
					'desc'    => __( 'Name of sender.  Syntax available - <code>{sitename}</code>', 'cbxmcratingreview' ),
					'type'    => 'text',
					'default' => '{sitename}'
				),
				'rsc_user_from'     => array(
					'name'    => 'rsc_user_from',
					'label'   => esc_html__( 'From Email', 'cbxmcratingreview' ),
					'desc'    => esc_html__( 'From Email Address.', 'cbxmcratingreview' ),
					'type'    => 'text',
					'default' => get_bloginfo( 'admin_email' )
				),
				'rsc_user_to'       => array(
					'name'    => 'rsc_user_to',
					'label'   => esc_html__( 'To Email', 'cbxmcratingreview' ),
					'desc'    => __( 'To Email Address. Syntax available - <code>{user_email}</code>', 'cbxmcratingreview' ),
					'type'    => 'text',
					'default' => '{user_email}'
				),
				'rsc_user_reply_to' => array(
					'name'    => 'rsc_user_reply_to',
					'label'   => esc_html__( 'Reply To', 'cbxmcratingreview' ),
					'desc'    => esc_html__( 'Reply To Email Address.', 'cbxmcratingreview' ),
					'type'    => 'text',
					'default' => get_bloginfo( 'admin_email' )
				),
				'rsc_user_subject'  => array(
					'name'    => 'rsc_user_subject',
					'label'   => esc_html__( 'Review Status Modification Email Subject', 'cbxmcratingreview' ),
					'desc'    => esc_html__( 'Email subject user will receive when admin modify review status.', 'cbxmcratingreview' ),
					'type'    => 'text',
					'default' => esc_html__( 'Review Status Change Notification', 'cbxmcratingreview' )
				),
				'rsc_user_heading'  => array(
					'name'    => 'rsc_user_heading',
					'label'   => esc_html__( 'Review Status Modification Email Heading', 'cbxmcratingreview' ),
					'desc'    => esc_html__( 'Email heading user will receive when admin modify review status.', 'cbxmcratingreview' ),
					'type'    => 'text',
					'default' => esc_html__( 'Review Status Changed', 'cbxmcratingreview' )
				),
				'rsc_user_body'     => array(
					'name'    => 'rsc_user_body',
					'label'   => esc_html__( 'Review Status Modification Email Body', 'cbxmcratingreview' ),
					'desc'    => __( 'Email content user will receive when admin modified review status. Syntax available - <code>{user_name}, {score}, {headline}, {comment}, {status}, {post_url}</code>', 'cbxmcratingreview' ),
					'type'    => 'wysiwyg',
					'default' => 'Hi, {user_name}

Your review has changed to {status}.

Review Details: 

Rating: {score}
Title: {headline}
Review: {comment}
Review status: {status}

Post: {post_url}

Thank you.'
				),
			);

			$single_review_view_id   = intval( $cbxmcratingreview_setting->get_option( 'single_review_view_id', 'cbxmcratingreview_tools', 0 ) );
			$single_review_edit_id   = intval( $cbxmcratingreview_setting->get_option( 'single_review_edit_id', 'cbxmcratingreview_tools', 0 ) );
			$review_userdashboard_id = intval( $cbxmcratingreview_setting->get_option( 'review_userdashboard_id', 'cbxmcratingreview_tools', 0 ) );

			$single_review_view_shortcode_text = __( '<strong>Please note, selected page doesn\'t have the shortcode. Please edit the page and add the shortcode.</strong>', 'cbxmcratingreview' );
			$single_review_edit_shortcode_text = __( '<strong>Please note, selected page doesn\'t have the shortcode. Please edit the page and add the shortcode.</strong>', 'cbxmcratingreview' );
			$user_dashboard_shortcode_text     = __( '<strong>Please note, selected page doesn\'t have the shortcode. Please edit the page and add the shortcode.</strong>', 'cbxmcratingreview' );

			if ( $single_review_view_id > 0 ) {
				$content_post = get_post( $single_review_view_id );
				$content      = $content_post->post_content;
				if ( has_shortcode( $content, 'cbxmcratingreview_singlereview' ) ) {

					$single_review_view_shortcode_text = sprintf( __( '<strong>Shortcode detected on the selected page.</strong>. <a class="button button-secondary" target="_blank" href="%s">Browse</a> the page on frontend.', 'cbxmcratingreview' ), get_permalink( $single_review_view_id ) );
				}
			}

			if ( $single_review_edit_id > 0 ) {
				$content_post = get_post( $single_review_edit_id );
				$content      = $content_post->post_content;
				if ( has_shortcode( $content, 'cbxmcratingreview_editreview' ) ) {

					$single_review_edit_shortcode_text = sprintf( __( '<strong>Shortcode detected on the selected page.</strong>. <a class="button button-secondary" target="_blank" href="%s">Browse</a> the page on frontend.', 'cbxmcratingreview' ), get_permalink( $single_review_edit_id ) );
				}
			}

			if ( $review_userdashboard_id > 0 ) {
				$content_post = get_post( $review_userdashboard_id );
				$content      = $content_post->post_content;

				if ( has_shortcode( $content, 'cbxmcratingreview_userdashboard' ) ) {
					$user_dashboard_shortcode_text = sprintf( __( '<strong>Shortcode detected on the selected page.</strong>. <a class="button button-secondary" target="_blank" href="%s">Browse</a> the page on frontend.', 'cbxmcratingreview' ), get_permalink( $review_userdashboard_id ) );
				}
			}


			$cbxmcratingreview_tools_fields = array(
				'single_review_view_id'   => array(
					'name'    => 'single_review_view_id',
					'label'   => esc_html__( 'Frontend Single Review View Page', 'cbxmcratingreview' ),
					'desc'    => __( 'Select page which will show the single review dynamically. That page must have the shortcode <code>[cbxmcratingreview_singlereview]</code>.', 'cbxmcratingreview' ) . $single_review_view_shortcode_text,
					'type'    => 'select',
					'default' => '',
					'options' => CBXMCRatingReviewHelper::get_pages()
				),
				'single_review_edit_id'   => array(
					'name'    => 'single_review_edit_id',
					'label'   => esc_html__( 'Frontend Single Review Edit Page', 'cbxmcratingreview' ),
					'desc'    => __( 'Select page which will show the single review edit dynamically. That page must have the shortcode <code>[cbxmcratingreview_editreview]</code>.', 'cbxmcratingreview' ) . $single_review_edit_shortcode_text,
					'type'    => 'select',
					'default' => '',
					'options' => CBXMCRatingReviewHelper::get_pages()
				),
				'review_userdashboard_id' => array(
					'name'    => 'review_userdashboard_id',
					'label'   => esc_html__( 'Frontend User Dashboard', 'cbxmcratingreview' ),
					'desc'    => __( 'Select page which will show the the logged in user\'s dashboard to manage rating and reviews. That page must have the shortcode <code>[cbxmcratingreview_userdashboard]</code>.', 'cbxmcratingreview' ) . $user_dashboard_shortcode_text,
					'type'    => 'select',
					'default' => '',
					'options' => CBXMCRatingReviewHelper::get_pages()
				),
				/*'enable_auto_integration' => array(
					'name'    => 'enable_auto_integration',
					'label'   => esc_html__( 'Enable Auto Integration', 'cbxmcratingreview' ),
					'desc'    => '<p>' . esc_html__( 'Enable/disable auto integration, ie, add average rating before post content in archive, in details article mode add average rating information before content, rating form & review listing after content', 'cbxmcratingreview' ),
					'type'    => 'select',
					'default' => '1',
					'options' => array(
						'1' => esc_html__( 'Enabled', 'cbxmcratingreview' ),
						'0' => esc_html__( 'Disabled', 'cbxmcratingreview' ),
					)
				),*/
				/*'post_types_auto'         => array(
					'name'        => 'post_types_auto',
					'label'       => esc_html__( 'Auto Integration for Post Type', 'cbxmcratingreview' ),
					'desc'        => esc_html__( 'Enable which post types will have auto integration', 'cbxmcratingreview' ),
					'type'        => 'multiselect',
					'optgroup'    => 0,
					'default'     => array( 'post' ),
					'options'     => $post_types,
					'optgroup'    => 1,
					'placeholder' => esc_html__( 'Select Post Type', 'cbxmcratingreview' )
				),*/
				'delete_global_config'    => array(
					'name'    => 'delete_global_config',
					'label'   => esc_html__( 'On Uninstall delete plugin data', 'cbxmcratingreview' ),
					'desc'    => '<p>' . __( 'Delete Global Config data and custom table created by this plugin on uninstall.', 'cbxmcratingreview' ) . ' ' . __( 'Details table information is <a href="#cbxmcratingreview_info">here</a>', 'cbxmcratingreview' ) . '</p>' . '<p>' . __( '<strong>Please note that this process can not be undone and it is recommended to keep full database backup before doing this.</strong>', 'cbxmcratingreview' ) . '</p>',
					'type'    => 'radio',
					'options' => array(
						'yes' => esc_html__( 'Yes', 'cbxmcratingreview' ),
						'no'  => esc_html__( 'No', 'cbxmcratingreview' ),
					),
					'default' => 'no'
				),
				'reset_data'              => array(
					'name'    => 'reset_data',
					'label'   => esc_html__( 'Reset all data', 'cbxmcratingreview' ),
					'desc'    => sprintf( __( 'Reset option values and all tables created by this plugin. 
<a class="button button-primary" onclick="return confirm(\'%s\')" href="%s">Reset Data</a>', 'cbxmcratingreview' ), esc_html__( 'Are you sure to reset all data, this process can not be undone?', 'cbxmcratingreview' ), $reset_data_link ) . $table_html,
					'type'    => 'html',
					'default' => 'off'
				),
				'old_data_migration'      => array(
					'name'    => 'old_data_migration',
					'label'   => esc_html__( 'Old Plugin Migration', 'cbxmcratingreview' ),
					'desc'    => sprintf( __( 'Migrate data from old Rating Plugin to New.  
<a class="button button-primary" onclick="return confirm(\'%s\')" href="%s">Migrate Data</a>', 'cbxmcratingreview' ), esc_html__( 'Are you sure to migrate data from old rating plugin, this process can not be undone unless you delete manually from new plugin?', 'cbxmcratingreview' ), $mirgation_data_link ) . $migrate_note,
					'type'    => 'html',
					'default' => 'off'
				)
			);

			$settings_builtin_fields =
				apply_filters( 'cbxmcratingreview_setting_fields', array(
					'cbxmcratingreview_common_config' => apply_filters( 'cbxmcratingreview_common_config_fields', $cbxmcratingreview_common_config_fields ),
					'cbxmcratingreview_global_email'  => apply_filters( 'cbxmcratingreview_global_email_fields', $cbxmcratingreview_global_email_fields ),
					'cbxmcratingreview_email_alert'   => apply_filters( 'cbxmcratingreview_email_alert_fields', $cbxmcratingreview_email_alert_fields ),
					'cbxmcratingreview_tools'         => apply_filters( 'cbxmcratingreview_tools_fields', $cbxmcratingreview_tools_fields )
				) );


			$settings_fields = array(); //final setting array that will be passed to different filters

			$sections = $this->get_settings_sections();

			foreach ( $sections as $section ) {
				if ( ! isset( $settings_builtin_fields[ $section['id'] ] ) ) {
					$settings_builtin_fields[ $section['id'] ] = array();
				}
			}


			foreach ( $sections as $section ) {
				$settings_fields[ $section['id'] ] = $settings_builtin_fields[ $section['id'] ];
			}


			$settings_fields = apply_filters( 'cbxmcratingreview_setting_fields_final', $settings_fields ); //final filter if need

			return $settings_fields;
		}//end method get_settings_fields

		/**
		 * Old plugin data migration
		 */
		public function plugin_migration() {
			if ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] == 'cbxmcratingreviewsettings' && isset( $_REQUEST['cbxmcratingreview_migrate'] ) && $_REQUEST['cbxmcratingreview_migrate'] == 1 ) {

				global $wpdb;
				$table_rating_form_old = $wpdb->prefix . 'cbratingsystem_ratingform_settings';
				$table_rating_log_old  = $wpdb->prefix . 'cbratingsystem_user_ratings';


				$table_rating_form = $wpdb->prefix . 'cbxmcratingreview_form';
				$table_rating_log  = $wpdb->prefix . 'cbxmcratingreview_log';
				$table_rating_avg  = $wpdb->prefix . 'cbxmcratingreview_log_avg';

				$status_arr = array(
					'unverified' => '-2',
					'verified'   => '-1',
					'pending'    => '0',
					'approved'   => '1',
					'unapproved' => '2',
					'spam'       => '3'
				);

				$migration_message       = '';
				$migration_forms_count   = 0;
				$migration_reviews_count = 0;

				if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_rating_form_old'" ) == $table_rating_form_old ) {
					// do something if wp_snippets exists

					$forms_id_rel = array();

					$forms_old = $wpdb->get_results( "SELECT * FROM $table_rating_form_old WHERE 1", ARRAY_A );


					if ( $forms_old !== null && is_array( $forms_old ) && sizeof( $forms_old ) > 0 ) {
						foreach ( $forms_old as $form_old ) {
							$migration_forms_count ++;

							$form_old_id     = $form_old['id'];
							$extrafields     = array();
							$custom_criteria = array();
							$custom_question = array();

							$forms_data                    = array();
							$forms_data['name']            = esc_attr( $form_old['name'] );
							$forms_data['status']          = intval( $form_old['is_active'] );
							$forms_data['custom_criteria'] = maybe_serialize( $custom_criteria );
							$forms_data['custom_question'] = maybe_serialize( $custom_question );


							//handle criteria
							$extrafields['criteria_last_count'] = 0;
							$custom_criteria_old                = maybe_unserialize( $form_old['custom_criteria'] );
							if ( is_array( $custom_criteria_old ) && sizeof( $custom_criteria_old ) > 0 ) {
								$criteria_index = 0;
								foreach ( $custom_criteria_old as $criteria_index_old => $criteria_old ) {
									$criteria = array();

									$enabled = intval( $criteria_old['enabled'] );
									if ( $enabled ) {
										$label     = esc_attr( $criteria_old['label'] );
										$stars_old = $criteria_old['stars'];
										$stars     = array();


										$criteria['label']       = $label;
										$criteria['criteria_id'] = $criteria_index_old;

										if ( is_array( $stars_old ) && sizeof( $stars_old ) > 0 ) {
											$star_old_count = 0;
											foreach ( $stars_old as $star_old ) {
												$star_enabled = intval( $star_old['enabled'] );
												if ( $star_enabled ) {

													$title                    = esc_attr( $star_old['title'] );
													$stars[ $star_old_count ] = array( 'title' => $title );
													$star_old_count ++;
												}


											}

											if ( $star_old_count > 5 ) {
												$stars = array_slice( $stars, 0, 5, true );
											} else if ( $star_old_count < 5 ) {
												for ( $star_old_count; $star_old_count < 5; $star_old_count ++ ) {
													$stars[ $star_old_count ] = array( 'title' => esc_html__( 'Unknown star', 'cbxmcratingreview' ) );
												}
											}

											$criteria['stars'] = $stars;
										} else {
											$criteria['stars'] = array(
												'0' => array(
													'title' => esc_html__( 'Worst', 'cbxmcratingreview' )
												),
												'1' => array(
													'title' => esc_html__( 'Bad', 'cbxmcratingreview' )
												),
												'2' => array(
													'title' => esc_html__( 'Not Bad', 'cbxmcratingreview' )
												),
												'3' => array(
													'title' => esc_html__( 'Good', 'cbxmcratingreview' )
												),
												'4' => array(
													'title' => esc_html__( 'Best', 'cbxmcratingreview' )
												)
											);
										}


										$custom_criteria[ $criteria_index ] = $criteria;
										$criteria_index ++;

									}

								}
							}
							$forms_data['custom_criteria'] = maybe_serialize( $custom_criteria );


							//handle question

							$custom_question_old                = maybe_unserialize( $form_old['custom_question'] );
							$extrafields['question_last_count'] = 0;

							$extrafields['enable_question'] = intval( $form_old['enable_question'] );

							if ( is_array( $custom_question_old ) && sizeof( $custom_question_old ) > 0 ) {

								$extrafields['question_last_count'] = sizeof( $custom_question_old );

								foreach ( $custom_question_old as $question_index => $question_old ) {
									//we have to deal with field type text, radio, checkbox(if we find multiple option still we need to take one), checkbox if seperated == 1
									$question             = array();
									$question['title']    = esc_attr( $question_old['title'] );
									$question['required'] = intval( $question_old['required'] );
									$question['enabled']  = intval( $question_old['enabled'] );
									$question['type']     = $type = esc_attr( $question_old['field']['type'] );

									if ( $type != 'text' ) {
										if ( $type == 'radio' ) {
											$count                  = intval( $question_old['field'][ $type ]['count'] );
											$question['last_count'] = $count;
											unset( $question_old['field'][ $type ]['count'] );

											$options             = $question_old['field'][ $type ];
											$question['options'] = array_slice( $options, 0, $count, true );
										} else if ( $type == 'checkbox' ) {
											$separated = intval( $question_old['field'][ $type ]['seperated'] );
											$count     = intval( $question_old['field'][ $type ]['count'] );

											$question['last_count'] = $count;


											if ( $separated ) {
												$question['type'] = 'multicheckbox';
												unset( $question_old['field'][ $type ]['seperated'] );
												unset( $question_old['field'][ $type ]['count'] );

												$options             = $question_old['field'][ $type ];
												$question['options'] = array_slice( $options, 0, $count, true );
											}
										}
									}

									$custom_question[ $question_index ] = $question;
								}

								$forms_data['custom_question'] = maybe_serialize( $custom_question );
							}


							//handle extra fields
							$extrafields_old = maybe_unserialize( $form_old['extrafields'] );


							$extrafields['show_on_single']          = intval( $form_old['show_on_single'] );
							$extrafields['show_on_home']            = intval( $form_old['show_on_home'] );
							$extrafields['show_on_arcv']            = intval( $form_old['show_on_arcv'] );
							$extrafields['post_types']              = maybe_unserialize( $form_old['post_types'] );
							$extrafields['user_roles_rate']         = maybe_unserialize( $form_old['allowed_users'] );
							$extrafields['user_roles_view']         = maybe_unserialize( $extrafields_old['view_allowed_users'] );
							$extrafields['enable_auto_integration'] = 1;
							$extrafields['post_types_auto']         = $extrafields['post_types'];
							$extrafields['hide_all_user_name']      = isset( $extrafields['hide_all_user_name'] ) ? intval( $extrafields['hide_all_user_name'] ) : 0;
							$allow_user_to_hide_f                     = isset( $extrafields['allow_user_to_hide'] ) ? $extrafields['allow_user_to_hide'] : 0;
							$extrafields['allow_user_to_hide']      = $allow_user_to_hide_f;
							$extrafields['hide_avatar']             = isset( $extrafields['show_user_avatar_in_review'] ) ? intval( $extrafields['show_user_avatar_in_review'] ) : 0; //field name change
							$show_user_link_in_review               = isset( $extrafields['show_user_link_in_review'] ) ? intval( $extrafields['show_user_link_in_review'] ) : 1;
							$extrafields['hide_author_link']        = ( $show_user_link_in_review ) ? 0 : 1;

							$forms_data['extrafields'] = maybe_serialize( $extrafields );


							$success = $wpdb->insert( $table_rating_form, $forms_data, array(
								'%s',
								'%d',
								'%s',
								'%s',
								'%s'
							) );

							$form_id = $wpdb->insert_id;

							$forms_id_rel[ $form_old_id ] = $form_id;
							//if we migrate form id successfully then we will go for logs for that form id
							if ( intval( $form_id ) > 0 && $wpdb->get_var( "SHOW TABLES LIKE '$table_rating_log_old'" ) == $table_rating_log_old ) {
								$logs_old = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_rating_log_old WHERE form_id= %d", $form_old_id ), ARRAY_A );
								if ( $logs_old !== null && is_array( $logs_old ) && sizeof( $logs_old ) > 0 ) {
									foreach ( $logs_old as $log_old ) {
										$log = array();

										$post_id = $log_old['post_id'];
										//$forms_id = $log_old['form_id'];
										$user_id   = $log_old['user_id'];
										$post_type = $log_old['post_type'];
										$comment   = $log_old['comment'];
										$status    = $log_old['comment_status'];
										$status    = isset( $status_arr[ $status ] ) ? $status_arr[ $status ] : 0;

										$allow_user_to_hide = isset($log_old['allow_user_to_hide'])? intval($log_old['allow_user_to_hide']) : 0;

										if($allow_user_to_hide_f == 0){
											$allow_user_to_hide = 0;
										}

										if ( $status == 1 ) {
											$status = 0;
										} //set log to pending so that admin can manually publish them which is needed.

										$ratings_old = maybe_unserialize( $log_old['rating'] );


										//$ratings = array();
										$ratings_stars = array();

										$rating_score_total = 0;
										$rating_score_count = 0;

										if ( is_array( $ratings_old ) && sizeof( $ratings_old ) > 0 ) {
											foreach ( $ratings_old as $rating_old_index => $rating_old ) {
												if ( is_numeric( $rating_old_index ) ) {
													$rating_score_count ++;
													$starCount   = $ratings_old[ $rating_old_index . '_starCount' ];
													$actualValue = $ratings_old[ $rating_old_index . '_actualValue' ];

													$rating_score     = number_format( ( $actualValue / $starCount ) * 5, 2 );
													$score_percentage = ( $rating_score * 100 ) / 5;
													$score_standard   = ( $score_percentage != 0 ) ? ( ( $score_percentage * 5 ) / 100 ) : 0;
													$score_round      = ceil( $rating_score );
													$round_percentage = ( $score_round * 100 ) / 5;

													$ratings_stars[ $rating_old_index ] = array(
														//'star_id'          => $star_id,
														'stars_length'     => 4,
														'score'            => $rating_score,
														'score_percentage' => $score_percentage,
														'score_standard'   => number_format( $score_standard, 2 ),
														//score in 5
														'score_round'      => $score_round,
														'round_percentage' => $round_percentage
													);

													$rating_score_total += floatval( $score_percentage );
												}

											}
										}

										$rating_avg_percentage = $rating_score_total / $rating_score_count; //in 100%

										$rating_avg_score = ( $rating_avg_percentage != 0 ) ? ( $rating_avg_percentage * 5 ) / 100 : 0; //scale within 5

										$ratings = array(
											'ratings_stars'  => $ratings_stars,
											'avg_percentage' => $rating_avg_percentage,
											'avg_score'      => $rating_avg_score
										);

										$questions_old = maybe_unserialize( $log_old['question'] );
										$questions     = array();


										if ( is_array( $questions_old ) && sizeof( $questions_old ) > 0 ) {
											foreach ( $questions_old as $question_old_index => $question_old ) {
												if ( is_array( $question_old ) ) {
													$question_old = maybe_serialize( array_filter( $question_old, array(
														'CBXMCRatingReviewQuestionHelper',
														'arrayFilterRemoveEmpty'
													) ) );
												}

												$questions[ $question_old_index ] = $question_old;
											}
										}


										$user_email = $log_old['user_email'];
										$user_name  = $log_old['user_name'];
										if ( $user_name == '' ) {
											$user_name = esc_html__( 'Unknown User', 'cbxmcratingreview' );
										}

										if ( $user_id == 0 ) {
											//find user id or create user
											$user = get_user_by( 'email', $user_email );
											if ( $user !== false ) {
												$user_id = $user->ID;

											} else {
												$user_id = username_exists( $user_email );
												if ( ! $user_id and email_exists( $user_email ) == false ) {
													$random_password = wp_generate_password( $length = 12, $include_standard_special_chars = false );
													$user_id         = wp_create_user( $user_email, $random_password, $user_email );
													if ( ! is_wp_error( $user_id ) ) {
														wp_update_user( array(
															'ID'           => $user_id,
															'display_name' => $user_name
														) );
													} else {
														continue;
													}
												}


											}
										}

										$migration_reviews_count ++;

										$score = number_format( ( $log_old['average'] / 100 ) * 5, 2 );


										$extraparams = array();

										$extraparams['allow_user_to_hide'] = $allow_user_to_hide;

										$log['post_id']      = $post_id;
										$log['form_id']      = $form_id;
										$log['post_type']    = $post_type;
										$log['user_id']      = $user_id;
										$log['score']        = $score;
										$log['headline']     = '';
										$log['comment']      = $comment;
										$log['extraparams']  = maybe_serialize( $extraparams );
										$log['attachment']   = maybe_serialize( array() );
										$log['status']       = $status;
										$log['date_created'] = date( 'Y-m-d H:i:s', $log_old['created'] );
										$log['ratings']      = maybe_serialize( $ratings );
										$log['questions']    = maybe_serialize( $questions );

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

										$log_insert_status = $wpdb->insert(
											$table_rating_log,
											$log,
											$data_format
										);
									}
								}
							}


						}//for each old form
					}//if forms found

				}//end old form table exists

				$_SESSION['cbxmcratingreview_migration_message'] = sprintf( __( '%d forms and %d reviews migrated. Migrated reviews are all put as non published mode. Please go to <a href="%s">reviews</a> and publish the migrated reviews as need. Rating avg is updated only when reviews are published.', 'cbxmcratingreview' ), $migration_forms_count, $migration_reviews_count, admin_url( 'admin.php?page=cbxmcratingreviewreviewlist' ) );

				wp_safe_redirect( admin_url( 'admin.php?page=cbxmcratingreviewsettings#cbxmcratingreview_tools' ) );
				exit();

			}
		}//end method plugin_migration

		/**
		 * Display migration messages
		 */
		public function migration_message_display() {
			if ( isset( $_SESSION['cbxmcratingreview_migration_message'] ) ) {
				$message = $_SESSION['cbxmcratingreview_migration_message'];
				unset( $_SESSION['cbxmcratingreview_migration_message'] );

				if ( $message != '' ):
					?>
					<div class="notice notice-success is-dismissible">
						<p><?php echo $message; ?></p>
					</div>
				<?php
				endif;

			}

		}//end method migration_message_display


		/**
		 * Full reset
		 *
		 */
		public function plugin_fullreset() {
			if ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] == 'cbxmcratingreviewsettings' && isset( $_REQUEST['cbxmcratingreview_fullreset'] ) && $_REQUEST['cbxmcratingreview_fullreset'] == 1 ) {

				global $wpdb;
				$option_prefix = 'cbxmcratingreview_';

				//delete plugin global options
				$option_values = CBXMCRatingReviewHelper::getAllOptionNames();

				foreach ( $option_values as $option_value ) {
					delete_option( $option_value['option_name'] );
				}

				//delete tables created by this plugin
				$table_names  = CBXMCRatingReviewHelper::getAllDBTablesList();
				$sql          = "DROP TABLE IF EXISTS " . implode( ', ', array_values( $table_names ) );
				$query_result = $wpdb->query( $sql );

				//delete meta values by keys
				$meta_keys = CBXMCRatingReviewHelper::getMetaKeys();

				foreach ( $meta_keys as $key => $value ) {
					delete_post_meta_by_key( $key );
				}

				//hooks to do more after uninstall
				do_action( 'cbxmcratingreview_plugin_reset' );

				require_once plugin_dir_path( __FILE__ ) . '../includes/class-cbxmcratingreview-activator.php';
				//create tables
				CBXMCRatingReview_Activator::createTables();


				$cbxmcratingreview_setting = $this->setting;


				$cbxmcratingreview_setting->set_sections( $this->get_settings_sections() );
				$cbxmcratingreview_setting->set_fields( $this->get_settings_fields() );
				$cbxmcratingreview_setting->admin_init();

				$_SESSION['cbxmcratingreview_fullreset_message'] = esc_html__( 'CBX Multi Criteria Rating & Review System plugin data has been reset which all setting, database table, meta keys related with this plugin are deleted, setting and database table recreated. ', 'cbxmcratingreview' );

				wp_safe_redirect( admin_url( 'admin.php?page=cbxmcratingreviewsettings#cbxmcratingreview_tools' ) );
				exit();
			}
		}//end method admin_pages

		/**
		 * Display migration messages
		 */
		public function fullreset_message_display() {
			if ( isset( $_SESSION['cbxmcratingreview_fullreset_message'] ) ) {
				$message = $_SESSION['cbxmcratingreview_fullreset_message'];
				unset( $_SESSION['cbxmcratingreview_fullreset_message'] );

				if ( $message != '' ):
					?>
					<div class="notice notice-success is-dismissible">
						<p><?php echo $message; ?></p>
					</div>
				<?php
				endif;

			}

		}//end method fullreset_message_display

		/**
		 * Show Admin Pages
		 */
		public function admin_pages() {
			if ( ! session_id() ) {
				session_start();
			}

			$cbxmcratingreview_setting = $this->setting;
			$page                      = isset( $_GET['page'] ) ? $_GET['page'] : '';


			//review listing page
			$review_listing_page_hook = add_menu_page( esc_html__( 'CBX Multi Criteria Rating & Review: Log Manager', 'cbxmcratingreview' ), esc_html__( 'CBX Reviews', 'cbxmcratingreview' ), 'manage_options', 'cbxmcratingreviewreviewlist',
				array( $this, 'display_admin_review_listing_page' ), 'dashicons-chart-line' );

			//add screen option save option
			if ( isset( $_GET['page'] ) && $_GET['page'] == 'cbxmcratingreviewreviewlist' && ! isset( $_GET['view'] ) ) {
				add_action( "load-$review_listing_page_hook", array( $this, 'cbxmcratingreview_review_listing' ) );
			}


			$forms_listing_page_hook = add_submenu_page( 'cbxmcratingreviewreviewlist', esc_html__( 'Rating Form Listing', 'cbxmcratingreview' ), esc_html__( 'Rating Forms', 'cbxmcratingreview' ), 'manage_options', 'cbxmcratingreviewformlist',
				array( $this, 'display_admin_form_listing_page' ) );

			//add screen option save option
			if ( $page == 'cbxmcratingreviewformlist' && ! isset( $_GET['view'] ) ) {
				add_action( "load-$forms_listing_page_hook", array( $this, 'cbxmcratingreview_form_listing' ) );
			}


			//rating avg listing pageadmin_pages
			$rating_avg_listing_page_hook = add_submenu_page(
				'cbxmcratingreviewreviewlist', esc_html__( 'CBX Multi Criteria Rating & Review: Average Log Manager', 'cbxmcratingreview' ), esc_html__( 'Rating Average', 'cbxmcratingreview' ),
				'manage_options', 'cbxmcratingreviewratingavglist', array(
					$this,
					'display_admin_rating_avg_listing_page'
				)
			);
			//add screen option save option
			if ( isset( $_GET['page'] ) && $_GET['page'] == 'cbxmcratingreviewratingavglist' ) {
				add_action( "load-$rating_avg_listing_page_hook", array(
					$this,
					'cbxmcratingreview_rating_avg_listing'
				) );
			}


			//add settings for this plugin
			$setting_page_hook = add_submenu_page(
				'cbxmcratingreviewreviewlist', esc_html__( 'Setting', 'cbxmcratingreview' ), esc_html__( 'Setting', 'cbxmcratingreview' ),
				'manage_options', 'cbxmcratingreviewsettings', array( $this, 'display_plugin_admin_settings' )
			);

		}//end method display_plugin_admin_settings

		/**
		 * Display plugin setting page
		 */
		public function display_plugin_admin_settings() {
			global $wpdb;

			//include( apply_filters( 'cbxmcratingreview_tpl_admin-settings-display', CBXMCRATINGREVIEW_ROOT_PATH . 'templates/admin/admin-settings-display.php' ) );
			include( cbxmcratingreview_locate_template( 'admin/admin-settings-display.php' ) );
		}//end method cbxmcratingreview_review_listing_per_page

		/**
		 * Set options for review listing result
		 *
		 * @param $new_status
		 * @param $option
		 * @param $value
		 *
		 * @return mixed
		 */
		public function cbxmcratingreview_review_listing_per_page( $new_status, $option, $value ) {
			if ( 'cbxmcratingreview_review_listing_per_page' == $option ) {
				return $value;
			}

			return $new_status;
		}//end method cbxmcratingreview_review_listing

		/**
		 * Add screen option for review listing
		 */
		public function cbxmcratingreview_review_listing() {

			$option = 'per_page';
			$args   = array(
				'label'   => esc_html__( 'Number of items per page', 'cbxmcratingreview' ),
				'default' => 50,
				'option'  => 'cbxmcratingreview_review_listing_per_page'
			);
			add_screen_option( $option, $args );
		}//end method cbxmcratingreview_form_listing_per_page

		/**
		 * Set options for form listing result
		 *
		 * @param $new_status
		 * @param $option
		 * @param $value
		 *
		 * @return mixed
		 */
		public function cbxmcratingreview_form_listing_per_page( $new_status, $option, $value ) {
			if ( 'cbxmcratingreview_form_listing_per_page' == $option ) {
				return $value;
			}

			return $new_status;
		}//end method cbxmcratingreview_form_listing

		/**
		 * Add screen option for form listing page
		 */
		public function cbxmcratingreview_form_listing() {
			$option = 'per_page';
			$args   = array(
				'label'   => esc_html__( 'Number of items per page', 'cbxmcratingreview' ),
				'default' => 50,
				'option'  => 'cbxmcratingreview_form_listing_per_page'
			);
			add_screen_option( $option, $args );
		}//end method display_admin_review_listing_page

		/**
		 * Admin review listing view
		 */
		public function display_admin_review_listing_page() {
			if ( isset( $_GET['view'] ) && $_GET['view'] == 'addedit' ) {
				//$template_name = apply_filters( 'cbxmcratingreview_tpl_admin-rating-review-review-log-edit', CBXMCRATINGREVIEW_ROOT_PATH . 'templates/admin/admin-rating-review-review-log-edit.php' );
				$template_name = cbxmcratingreview_locate_template( 'admin/admin-rating-review-review-log-edit.php' );
			} elseif ( isset( $_GET['view'] ) && $_GET['view'] == 'view' ) {
				//$template_name = apply_filters( 'cbxmcratingreview_tpl_admin-rating-review-review-log-view', CBXMCRATINGREVIEW_ROOT_PATH . 'templates/admin/admin-rating-review-review-log-view.php' );
				$template_name = cbxmcratingreview_locate_template( 'admin/admin-rating-review-review-log-view.php' );
			} else {
				//$template_name = apply_filters( 'cbxmcratingreview_tpl_admin-rating-review-review-logs', CBXMCRATINGREVIEW_ROOT_PATH . 'templates/admin/admin-rating-review-review-logs.php' );
				$template_name = cbxmcratingreview_locate_template( 'admin/admin-rating-review-review-logs.php' );
			}

			include( $template_name );
		}//end method display_admin_form_listing_page

		/**
		 * Set options for review listing result
		 *
		 * @param $new_status
		 * @param $option
		 * @param $value
		 *
		 * @return mixed
		 */
		public function cbxmcratingreview_rating_avg_listing_per_page( $new_status, $option, $value ) {
			if ( 'cbxmcratingreview_rating_avg_listing_per_page' == $option ) {
				return $value;
			}

			return $new_status;
		}

		/**
		 * Add screen option for rating avg listing
		 */
		public function cbxmcratingreview_rating_avg_listing() {

			$option = 'per_page';
			$args   = array(
				'label'   => esc_html__( 'Number of items per page', 'cbxmcratingreview' ),
				'default' => 50,
				'option'  => 'cbxmcratingreview_rating_avg_listing_per_page'
			);
			add_screen_option( $option, $args );
		}//end method cbxmcratingreview_rating_avg_listing

		/**
		 * Admin review listing view
		 */
		public function display_admin_rating_avg_listing_page() {
			//include( apply_filters( 'cbxmcratingreview_tpl_admin-rating-review-rating-avg-logs', CBXMCRATINGREVIEW_ROOT_PATH . 'templates/admin/admin-rating-review-rating-avg-logs.php' ) );
			include( cbxmcratingreview_locate_template( 'admin/admin-rating-review-rating-avg-logs.php' ) );
		}//end method display_admin_rating_avg_listing_page


		/**
		 * Register the stylesheets for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles( $hook ) {

			$current_page = isset( $_GET['page'] ) ? esc_attr( $_GET['page'] ) : '';

			$cbxmcratingreview_setting = $this->setting;
			$ratingform_css_dep        = array();
			$ratingforms_css_dep       = array();


			do_action( 'cbxmcratingreview_reg_admin_styles_before' );

			wp_register_style( 'jquery-cbxmcratingreview-raty', plugin_dir_url( __FILE__ ) . '../assets/css/jquery.cbxmcratingreview_raty.css', array(), $this->version, 'all' );

			wp_register_style( 'jquery-ui', plugin_dir_url( __FILE__ ) . '../assets/css/ui-lightness/jquery-ui.min.css', array(), $this->version );


			wp_register_style( 'sweetalert2', plugin_dir_url( __FILE__ ) . '../assets/js/sweetalert2/sweetalert2.css', array(), $this->version, 'all' );
			wp_register_style( 'chosen', plugin_dir_url( __FILE__ ) . '../assets/css/chosen.min.css', array(), $this->version, 'all' );


			$ratingform_css_dep[] = 'jquery-ui';
			$ratingform_css_dep[] = 'chosen';
			$ratingform_css_dep[] = 'jquery-cbxmcratingreview-raty';


			$ratingforms_css_dep[] = 'jquery-ui';
			$ratingforms_css_dep[] = 'chosen';
			$ratingforms_css_dep[] = 'sweetalert2';
			$ratingforms_css_dep[] = 'wp-color-picker';


			wp_register_style( 'cbxmcratingreview-ratingform', plugin_dir_url( __FILE__ ) . '../assets/css/cbxmcratingreview-ratingform.css', $ratingform_css_dep, $this->version, 'all' );

			wp_register_style( 'cbxmcratingreview-adminforms', plugin_dir_url( __FILE__ ) . '../assets/css/cbxmcratingreview-adminforms.css', $ratingforms_css_dep, $this->version, 'all' );

			wp_register_style( 'cbxmcratingreview-admin', plugin_dir_url( __FILE__ ) . '../assets/css/cbxmcratingreview-admin.css', array(
				'jquery-cbxmcratingreview-raty',
				'jquery-ui'
			), $this->version, 'all' );

			$ratingform_css_dep[] = 'cbxmcratingreview-admin';

			wp_register_style( 'cbxmcratingreview-setting', plugin_dir_url( __FILE__ ) . '../assets/css/cbxmcratingreview-setting.css', array( 'chosen' ), $this->version, 'all' );

			do_action( 'cbxmcratingreview_reg_admin_styles' );

			//except setting, other main plugin's views

			if ( $current_page == 'cbxmcratingreviewreviewlist' || $current_page == 'cbxmcratingreviewratingavglist' ) {
				// enqueue styles
				wp_enqueue_style( 'jquery-cbxmcratingreview-raty' );
				wp_enqueue_style( 'jquery-ui' );
				wp_enqueue_style( 'chosen' );
				wp_enqueue_style( 'sweetalert2' );
				wp_enqueue_style( 'cbxmcratingreview-admin' );

				if ( $current_page == 'cbxmcratingreviewreviewlist' && ( isset( $_GET['view'] ) && $_GET['view'] == 'addedit' ) ) {
					wp_enqueue_style( 'cbxmcratingreview-admin' );
					wp_enqueue_style( 'jquery-cbxmcratingreview-raty' );
					wp_enqueue_style( 'cbxmcratingreview-ratingform' );
				}
			}

			//only for setting
			if ( $current_page == 'cbxmcratingreviewsettings' ) {
				wp_enqueue_style( 'cbxmcratingreview-setting' );
			}

			//add css for form listing and edit page
			if ( $current_page == 'cbxmcratingreviewformlist' ) {
				wp_enqueue_style( 'jquery-ui' );
				wp_enqueue_style( 'chosen' );
				wp_enqueue_style( 'sweetalert2' );
				wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_style( 'cbxmcratingreview-adminforms' );
			}

			do_action( 'cbxmcratingreview_reg_admin_styles' );

		}//end method enqueue_styles

		/**
		 * Register the JavaScript for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts( $hook ) {

			$current_page = isset( $_GET['page'] ) ? esc_attr( $_GET['page'] ) : '';

			$cbxmcratingreview_setting = $this->setting;

			$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';


			$require_headline = intval( $cbxmcratingreview_setting->get_option( 'require_headline', 'cbxmcratingreview_common_config', 1 ) );
			$require_comment  = intval( $cbxmcratingreview_setting->get_option( 'require_comment', 'cbxmcratingreview_common_config', 1 ) );
			$half_rating      = intval( $cbxmcratingreview_setting->get_option( 'half_rating', 'cbxmcratingreview_common_config', 0 ) );


			$ratingform_js_dep = array();

			do_action( 'cbxmcratingreview_reg_admin_scripts_before' );

			wp_register_script( 'cbxmcratingreview-events', plugin_dir_url( __FILE__ ) . '../assets/js/cbxmcratingreview-events.js', array(), $this->version, true );

			wp_enqueue_script( 'jquery' );

			$cbxmcratingreview_ratingform_js_vars = apply_filters( 'cbxmcratingreview_ratingform_adminedit_js_vars', array(
				'ajaxurl'    => admin_url( 'admin-ajax.php' ),
				'nonce'      => wp_create_nonce( 'cbxmcratingreview' ),
				'rating'     => array(
					'half_rating' => $half_rating,
					'cancelHint'  => esc_html__( 'Cancel this rating!', 'cbxmcratingreview' ),
					'hints'       => CBXMCRatingReviewHelper::ratingHints(),
					'noRatedMsg'  => esc_html__( 'Not rated yet!', 'cbxmcratingreview' ),
					'img_path'    => apply_filters( 'cbxmcratingreview_star_image_url', CBXMCRATINGREVIEW_ROOT_URL . 'assets/images/stars/' )
				),
				'validation' => array(
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
					'require_comment'  => $require_comment
				),
				'sort_text'            => esc_html__( 'Drag and Sort', 'cbxmcratingreview' ),
				'forms'                => array()
			) );

			$cbxmcratingreview_admin_js_vars = apply_filters( 'cbxmcratingreview_admin_js_vars', array(
				'ajaxurl'            => admin_url( 'admin-ajax.php' ),
				'nonce'              => wp_create_nonce( 'cbxmcratingreview' ),
				'rating'             => array(
					'half_rating' => $half_rating,
					'cancelHint'  => esc_html__( 'Cancel this rating!', 'cbxmcratingreview' ),
					'hints'       => CBXMCRatingReviewHelper::ratingHints(),
					'noRatedMsg'  => esc_html__( 'Not rated yet!', 'cbxmcratingreview' ),
					'img_path'    => apply_filters( 'cbxmcratingreview_star_image_url', CBXMCRATINGREVIEW_ROOT_URL . 'assets/images/stars/' )
				),
				'delete_error'       => esc_html__( 'Sorry! delete failed!', 'cbxmcratingreview' ),
				'delete_text'        => esc_html__( 'Delete', 'cbxmcratingreview' ),
				'sort_text'          => esc_html__( 'Drag and Sort', 'cbxmcratingreview' ),
				'button_text_ok'     => esc_html__( 'Ok', 'cbxmcratingreview' ),
				'button_text_cancel' => esc_html__( 'Cancel', 'cbxmcratingreview' ),
				'delete_error'       => esc_html__( 'Sorry! Some problem during deletion.', 'cbxmcratingreview' ),
				'reviews_arr'        => array(),

			) );

			wp_register_script( 'jquery-cbxmcratingreview-raty', plugin_dir_url( __FILE__ ) . '../assets/js/jquery.cbxmcratingreview_raty.js', array( 'jquery' ), $this->version, true );
			wp_register_script( 'sweetalert2', plugin_dir_url( __FILE__ ) . '../assets/js/sweetalert2/sweetalert2.js', array( 'jquery' ), $this->version, true );
			wp_register_script( 'chosen-jquery', plugin_dir_url( __FILE__ ) . '../assets/js/chosen.jquery.min.js', array( 'jquery' ), $this->version, true );
			wp_register_script( 'jquery-validate', plugin_dir_url( __FILE__ ) . '../assets/js/jquery.validate' . $suffix . '.js', array( 'jquery' ), $this->version, true );


			$ratingform_js_dep[] = 'cbxmcratingreview-events';
			$ratingform_js_dep[] = 'jquery';
			$ratingform_js_dep[] = 'jquery-ui-datepicker';
			$ratingform_js_dep[] = 'jquery-cbxmcratingreview-raty';
			$ratingform_js_dep[] = 'jquery-validate';
			$ratingform_js_dep[] = 'chosen-jquery';

			do_action( 'cbxmcratingreview_reg_admin_scripts' );


			wp_register_script( 'cbxmcratingreview-admin', plugin_dir_url( __FILE__ ) . '../assets/js/cbxmcratingreview-admin.js', array(
				'cbxmcratingreview-events',
				'jquery',
				'jquery-cbxmcratingreview-raty',
				'jquery-ui-datepicker',
				'sweetalert2'
			), $this->version, true );

			$ratingform_js_dep[] = 'cbxmcratingreview-admin'; // adding the common js file admin, same logic like public version

			$ratingform_js_dep = apply_filters( 'cbxmcratingreview_ratingadminform_js_dep', $ratingform_js_dep );

			wp_register_script( 'cbxmcratingreview-ratingform-adminedit', plugin_dir_url( __FILE__ ) . '../assets/js/cbxmcratingreview-ratingform-adminedit.js', $ratingform_js_dep, $this->version, true );

			if ( $current_page == 'cbxmcratingreviewreviewlist' && ( isset( $_GET['view'] ) && $_GET['view'] == 'addedit' ) ) {

				wp_localize_script( 'cbxmcratingreview-ratingform-adminedit', 'cbxmcratingreview_ratingform', $cbxmcratingreview_ratingform_js_vars );

				wp_enqueue_script( 'cbxmcratingreview-events' );
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'jquery-ui-datepicker' );

				wp_enqueue_script( 'jquery-cbxmcratingreview-raty' );
				wp_enqueue_script( 'jquery-validate' );
				wp_enqueue_script( 'chosen-jquery' );

				do_action( 'cbxmcratingreview_enq_admin_ratingform_scripts' );

				wp_enqueue_script( 'cbxmcratingreview-ratingform-adminedit' );

				do_action( 'cbxmcratingreview_enq_admin_ratingform_scripts_after' );
			}

			//only for review listing, review edit
			if ( $current_page == 'cbxmcratingreviewreviewlist' ) {

				wp_enqueue_media();

				wp_localize_script( 'cbxmcratingreview-admin', 'cbxmcratingreview_admin', $cbxmcratingreview_admin_js_vars );


				// enqueue scripts
				wp_enqueue_script( 'cbxmcratingreview-events' );

				wp_enqueue_script( 'jquery-cbxmcratingreview-raty' );
				wp_enqueue_script( 'jquery-ui-datepicker' );
				wp_enqueue_script( 'sweetalert2' );
				wp_enqueue_script( 'cbxmcratingreview-admin' );
			}

			//only for setting page
			if ( $current_page == 'cbxmcratingreviewsettings' ) {

				wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_media();

				wp_enqueue_script( 'wp-color-picker' );

				wp_register_script( 'cbxmcratingreview-setting', plugin_dir_url( __FILE__ ) . '../assets/js/cbxmcratingreview-setting.js', array(
					'cbxmcratingreview-events',
					'jquery',
					'wp-color-picker',
					'chosen-jquery'
				), $this->version, true );
				wp_localize_script( 'cbxmcratingreview-setting', 'cbxmcratingreview_admin', $cbxmcratingreview_admin_js_vars );

				do_action( 'cbxmcratingreview_enq_admin_setting_js_before' );

				wp_enqueue_script( 'cbxmcratingreview-events' );
				wp_enqueue_script( 'cbxmcratingreview-setting' );

				do_action( 'cbxmcratingreview_enq_admin_setting_js_after' );
			}

			if ( $current_page == 'cbxmcratingreviewformlist' ) {

				wp_enqueue_media();

				wp_enqueue_script( 'wp-color-picker' );

				wp_register_script( 'cbxmcratingreview-adminforms', plugin_dir_url( __FILE__ ) . '../assets/js/cbxmcratingreview-adminforms.js', array(
					'cbxmcratingreview-events',
					'jquery',
					'wp-color-picker',
					'chosen-jquery',
					'sweetalert2'
				), $this->version, true );
				wp_localize_script( 'cbxmcratingreview-adminforms', 'cbxmcratingreview_admin', $cbxmcratingreview_admin_js_vars );

				do_action( 'cbxmcratingreview_enq_admin_forms_js_before' );

				wp_enqueue_script( 'cbxmcratingreview-events' );
				wp_enqueue_script( 'sweetalert2' );
				wp_enqueue_script( 'cbxmcratingreview-adminforms' );

				do_action( 'cbxmcratingreview_enq_admin_forms_js_after' );
			}

		}//end method enqueue_scripts


		//on publish review calculate avg
		public function review_publish_adjust_avg( $review_info ) {
			//calculate avg
			CBXMCRatingReviewHelper::calculatePostAvg( $review_info );
		}//end method review_publish_adjust_avg

		//on unpublish review adjust avg
		public function review_unpublish_adjust_avg( $review_info ) {
			CBXMCRatingReviewHelper::adjustPostwAvg( $review_info );
		}//end method review_unpublish_adjust_avg

		/**
		 * Do some extra cleanup on after review delete
		 *
		 * @param $review_info
		 */
		public function review_delete_after( $review_info ) {
			global $wpdb;

			$review_id = intval( $review_info['id'] );

			//adjust avg
			CBXMCRatingReviewHelper::adjustPostwAvg( $review_info );

		}//end method review_delete_after

		/**
		 * After rating form delete
		 *
		 * @param $form_info
		 */
		public function form_delete_after( $form_info ) {
			global $wpdb;

			$table_cbxmcratingreview_review = $wpdb->prefix . 'cbxmcratingreview_log';

			$form_id = isset( $form_info['id'] ) ? intval( $form_info['id'] ) : 0;
			if ( $form_id > 0 ) {
				$reviews = cbxmcratingreview_Reviews( $form_id, - 1, - 1 );
				if ( is_array( $reviews ) && sizeof( $reviews ) > 0 ) {
					foreach ( $reviews as $review ) {
						$id          = intval( $review['id'] );
						$review_info = cbxmcratingreview_singleReview( $id );
						do_action( 'cbxmcratingreview_review_delete_before', $review_info );

						$delete_status = $wpdb->query( $wpdb->prepare( "DELETE FROM $table_cbxmcratingreview_review WHERE id=%d", $id ) );

						if ( $delete_status !== false ) {
							do_action( 'cbxmcratingreview_review_delete_after', $review_info );
						}
					}
				}
			}
		}//end method form_delete_after

		/**
		 * On user delete delete reviews
		 *
		 * @param $user_id
		 */
		public function review_delete_after_delete_user( $user_id ) {
			global $wpdb;
			$table_cbxmcratingreview_review = $wpdb->prefix . 'cbxmcratingreview_log';

			$forms = CBXMCRatingReviewHelper::getRatingFormsList();
			if ( is_array( $forms ) && sizeof( $forms ) > 0 ) {
				foreach ( $forms as $form_id => $form_name ) {

					//get all reviews for this user
					$reviews = cbxmcratingreview_ReviewsByUser( $form_id, $user_id, - 1 );
					foreach ( $reviews as $review ) {

						do_action( 'cbxmcratingreview_review_delete_before', $review );

						$delete_status = $wpdb->query( $wpdb->prepare( "DELETE FROM $table_cbxmcratingreview_review WHERE id=%d", intval( $review['id'] ) ) );

						if ( $delete_status !== false ) {
							do_action( 'cbxmcratingreview_review_delete_after', $review );
						}

					}
				}
			}

		}//end method review_delete_after_delete_user

		/**
		 * Post delete hook init
		 */
		public function review_delete_after_delete_post_init() {
			add_action( 'delete_post', array( $this, 'review_delete_after_delete_post' ), 10 );
		}//end method review_delete_after_delete_post_init

		/**
		 * On post  delete delete reviews
		 *
		 * @param $post_id
		 */
		public function review_delete_after_delete_post( $post_id ) {
			global $wpdb;
			$table_cbxmcratingreview_review = $wpdb->prefix . 'cbxmcratingreview_log';

			//get all reviews for this post
			$reviews = cbxmcratingreview_postReviews( $post_id, - 1 );
			if ( is_array( $reviews ) && sizeof( $reviews ) > 0 ) {
				foreach ( $reviews as $review ) {

					do_action( 'cbxmcratingreview_review_delete_before', $review );

					$delete_status = $wpdb->query( $wpdb->prepare( "DELETE FROM $table_cbxmcratingreview_review WHERE id=%d", $review['id'] ) );

					if ( $delete_status !== false ) {
						do_action( 'cbxmcratingreview_review_delete_after', $review );
					}
				}
			}
		}//end method review_delete_after_delete_post

		/**
		 * Ajax review edit
		 */
		public function review_rating_admin_edit() {
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

				//get the form setting
				$form = CBXMCRatingReviewHelper::getRatingForm( $form_id );

				$enable_question  = isset( $form['enable_question'] ) ? intval( $form['enable_question'] ) : 0;
				$custom_criterias = isset( $form['custom_criteria'] ) ? $form['custom_criteria'] : array();
				$custom_questions = isset( $form['custom_question'] ) ? $form['custom_question'] : array();

				$rating_scores      = isset( $submit_data['ratings'] ) ? $submit_data['ratings'] : array();
				$rating_score_total = 0;
				$rating_score_count = 0;

				//$rating_score    = isset( $submit_data['cbxmcratingreview_rating_score'] ) ? floatval( $submit_data['cbxmcratingreview_rating_score'] ) : 0;

				//$review_headline = isset( $submit_data['cbxmcratingreview_review_headline'] ) ? sanitize_text_field( $submit_data['cbxmcratingreview_review_headline'] ) : '';
				//$review_comment  = isset( $submit_data['cbxmcratingreview_review_comment'] ) ? wp_kses( $submit_data['cbxmcratingreview_review_comment'], CBXMCRatingReviewHelper::allowedHtmlTags() ) : '';

				$review_headline = isset( $submit_data['headline'] ) ? sanitize_text_field( $submit_data['headline'] ) : '';
				$review_comment  = isset( $submit_data['comment'] ) ? wp_kses( $submit_data['comment'], CBXMCRatingReviewHelper::allowedHtmlTags() ) : '';

				$questions_store = array();
				$ratings_stars   = array();


				$default_status = apply_filters( 'cbxmcratingreview_review_review_default_status', $default_status, $post_id );
				$new_status     = isset( $submit_data['status'] ) ? intval( $submit_data['status'] ) : $default_status;


				if ( $review_id <= 0 ) {
					$validation_errors['top_errors']['log_id']['log_id_wrong'] = esc_html__( 'Sorry! Invalid review id. Please check and try again.', 'cbxmcratingreview' );
				} else {
					$review_info_old = cbxmcratingreview_singleReview( $review_id );
					if ( $review_info_old == null ) {
						$validation_errors['top_errors']['log']['log_wrong'] = esc_html__( 'Sorry! Invalid review. Please check and try again.', 'cbxmcratingreview' );
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
										$validation_errors['cbxmcratingreview_questions_error'][ $question_index ] = sprintf( __( 'Sorry! Question <strong>%s</strong> is blank but required. Please check and try again.', 'cbxmcratingreview' ), $title );
									}
								} else if ( $field_type == 'select' && $multiple ) {

									if ( $required && sizeof( array_filter( $answer, array(
											'CBXMCRatingReviewQuestionHelper',
											'arrayFilterRemoveEmpty'
										) ) ) == 0 ) {
										$validation_errors['cbxmcratingreview_questions_error'][ $question_index ] = sprintf( __( 'Sorry! Question <strong>%s</strong> is not answered but required. Please check and try again.', 'cbxmcratingreview' ), $title );
									}
								} else if ( $field_type == 'checkbox' ) {

								} else if ( $field_type == 'multicheckbox' ) {
									if ( $required && sizeof( array_filter( $answer, array(
											'CBXMCRatingReviewQuestionHelper',
											'arrayFilterRemoveEmpty'
										) ) ) == 0 ) {
										$validation_errors['cbxmcratingreview_questions_error'][ $question_index ] = sprintf( __( 'Sorry! Question <strong>%s</strong> is not answered but required. Please check and try again.', 'cbxmcratingreview' ), $title );
									}
								}


								//now store the answer
								if ( is_array( $answer ) ) {
									$answer = maybe_serialize( array_filter( $answer, array(
										'CBXMCRatingReviewQuestionHelper',
										'arrayFilterRemoveEmpty'
									) ) );
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

			$validation_errors = apply_filters( 'cbxmcratingreview_review_adminedit_validation_errors', $validation_errors, $form_id, $post_id, $submit_data );

			if ( sizeof( $validation_errors ) > 0 ) {

			} else {


				$old_status = $review_info_old['status'];


				$ok_to_process = 1;

				global $wpdb;

				$table_rating_log = $wpdb->prefix . 'cbxmcratingreview_log';


				$log_update_status = false;

				$attachment = maybe_unserialize( $review_info_old['attachment'] );

				//$attachment['photos'] = $review_photos;
				//$attachment['video']  = $review_video;

				$attachment = apply_filters( 'cbxmcratingreview_review_adminedit_attachment', $attachment, $form_id, $post_id, $submit_data, $review_id );

				$extraparams = maybe_unserialize( $review_info_old['extraparams'] );
				$extraparams = apply_filters( 'cbxmcratingreview_review_adminedit_extraparams', $extraparams, $form_id, $post_id, $submit_data, $review_id );

				$user_id = intval( get_current_user_id() );

				$rating_avg_percentage = $rating_score_total / $rating_score_count; //in 100%

				$rating_avg_score = ( $rating_avg_percentage != 0 ) ? ( $rating_avg_percentage * 5 ) / 100 : 0; //scale within 5

				$ratings = array(
					'ratings_stars'  => $ratings_stars,
					'avg_percentage' => $rating_avg_percentage,
					'avg_score'      => $rating_avg_score
				);

				// insert rating log
				$data = array(
					'score'         => number_format( $rating_avg_score, 2 ),
					'headline'      => $review_headline,
					'comment'       => $review_comment,
					'extraparams'   => maybe_serialize( $extraparams ),
					'attachment'    => maybe_serialize( $attachment ),
					'status'        => $new_status,
					'mod_by'        => $user_id,
					'date_modified' => current_time( 'mysql' ),
					'ratings'       => maybe_serialize( $ratings ),
					'questions'     => maybe_serialize( $questions_store )
				);

				$data = apply_filters( 'cbxmcratingreview_review_adminedit_data', $data, $form_id, $post_id, $submit_data, $review_id );

				$data_format = array(
					'%f', // score
					'%s', // headline
					'%s', // comment
					//'%s', // review_date
					//'%s', // location
					//'%f', // lat
					//'%f', // lon
					'%s', // extraparams
					'%s', // attachment
					'%s', // status
					'%d', // mod_by
					'%s', // date_modified
					'%s', // ratings
					'%s', // questions
				);

				$data_format = apply_filters( 'cbxmcratingreview_review_adminedit_data_format', $data_format, $post_id, $submit_data, $review_id );

				$data_where = array(
					'id'      => $review_id,
					'post_id' => $post_id
				);

				$data_where_format = array(
					'%d', // id
					'%d' // post_id
				);

				$data_where        = apply_filters( 'cbxmcratingreview_review_adminedit_where', $data_where, $form_id, $post_id, $submit_data, $review_id );
				$data_where_format = apply_filters( 'cbxmcratingreview_review_adminedit_where_format', $data_where_format, $form_id, $post_id, $submit_data, $review_id );

				$log_update_status = $wpdb->update(
					$table_rating_log,
					$data,
					$data_where,
					$data_format,
					$data_where_format
				);

				if ( $log_update_status != false ) {
					$review_id = $review_id;

					do_action( 'cbxmcratingreview_review_adminedit_just_success', $form_id, $post_id, $submit_data, $review_id );


					$success_msg_class = 'success';

					$success_msg_info = esc_html__( 'Review updated successfully', 'cbxmcratingreview' );

					$success_msg_info = apply_filters( 'cbxmcratingreview_review_adminedit_success_info', $success_msg_info, 'success' );

					$review_info = cbxmcratingreview_singleReview( $review_id );

					//if status change
					if ( $old_status != $new_status ) {
						if ( $new_status == 1 ) {
							do_action( 'cbxmcratingreview_review_publish', $review_info, $review_info_old );
						} else {
							do_action( 'cbxmcratingreview_review_unpublish', $review_info, $review_info_old );

						}
						do_action( 'cbxmcratingreview_review_status_change', $old_status, $new_status, $review_info, $review_info_old );

						//send email to user for review status change if enabled
						$rsc_user_status = $cbxmcratingreview_setting->get_option( 'rsc_user_status', 'cbxmcratingreview_email_alert', 'on' );
						if ( $rsc_user_status == 'on' ) {
							$rsc_user_email_status = CBXMCRatingReviewMailAlert::sendReviewStatusUpdateUserEmailAlert( $review_info );
						}

					}//end status change detected
					else {
						//simple update without status change
						do_action( 'cbxmcratingreview_review_update_without_status', $new_status, $review_info, $review_info_old );
					}

					$response_data_arr = apply_filters( 'cbxmcratingreview_review_adminedit_response_data', $response_data_arr, $post_id, $submit_data, $review_info );


					do_action( 'cbxmcratingreview_review_adminedit_success', $form_id, $post_id, $submit_data, $review_info, $review_info_old );

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
		}//end method review_rating_admin_edit

		/**
		 * Admin rating form submit
		 */
		public function rating_adminform_submit() {
			if ( isset( $_POST['cbxmcratingreview_form_submit'] ) && check_admin_referer( 'cbxmcratingreview_formedit', 'cbxmcratingreview_wpnonce' ) ) {

				$form_fields = CBXMCRatingReviewHelper::form_default_fields();
				$star_titles = CBXMCRatingReviewHelper::star_default_titles();
				$form_count  = CBXMCRatingReviewHelper::getRatingForms_Count();


				$ratingFormData = $_POST['cbxmcratingreview_ratingForm'];

				$form_edit_url  = admin_url( 'admin.php?page=cbxmcratingreviewformlist&view=addedit' );
				$errorHappened  = false;
				$errorMessages  = array();
				$affectedFields = array();


				$formSavableData = array();


				//special care for id
				$formSavableData['id'] = $form_id = ( isset( $ratingFormData['id'] ) && ( intval( $ratingFormData['id'] ) > 0 ) ) ? intval( $ratingFormData['id'] ) : 0;


				if ( $form_id == 0 && $form_count > 0 ) {
					$can_add_form = apply_filters( 'cbxmcratingreview_add_more_forms', false );
				} else {
					$can_add_form = true;
				}

				//if not unlimited form
				if ( $can_add_form === false ) {
					$errorHappened                 = true;
					$errorMessages['limitedforms'] = esc_html__( 'Sorry, in free version only one form can be created', 'cbxmcratingreview' );
					$affectedFields[]              = 'limitedforms';
				}

				//let's merge the extrafield fields with the main array to make it go it easy

				$ratingFormData = array_merge( $ratingFormData, $ratingFormData['extrafields'] );

				//general fields starts
				foreach ( $form_fields as $key => $field ) {
					$singlefield_error = false;
					$extrafield        = ( isset( $field['extrafield'] ) && $field['extrafield'] ) ? true : false;

					if ( ! isset( $ratingFormData[ $key ] ) ) {
						continue;
					}


					$value = $ratingFormData[ $key ]; //we merged the extrafields with defaults for easy access

					$field_errorsmg = ( isset( $field['errormsg'] ) && $field['errormsg'] != '' ) ? $field['errormsg'] : '';

					$required_field = ( isset( $field['required'] ) && $field['required'] ) ? $field['required'] : false;
					$multiple_field = ( isset( $field['multiple'] ) && $field['multiple'] ) ? $field['multiple'] : false;


					//text type field
					if ( $field['type'] == 'text' ) {
						$value = esc_attr( $value );

						if ( $required_field && strlen( $value ) == '' ) {
							$errorHappened                     = true;
							$errorMessages[ $key ]['required'] = $field['errormsg'];
							$affectedFields[]                  = $key;

						}

					} else if ( $field['type'] == 'number' ) {
						if ( $required_field && floatval( $value ) == 0 ) {
							$errorHappened                     = true;
							$errorMessages[ $key ]['required'] = $field['errormsg'];
							$affectedFields[]                  = $key;
						} else if ( isset( $field['min'] ) && floatval( $value ) < $field['min'] ) {
							$errorHappened                = true;
							$errorMessages[ $key ]['min'] = sprintf( esc_html__( ' Field "%s" minimum allowed value %f', 'cbxmcratingreview' ), $field['label'], $field['min'] );
							$affectedFields[]             = $key;

						} else if ( isset( $field['max'] ) && floatval( $value ) > $field['max'] ) {
							$errorHappened                = true;
							$errorMessages[ $key ]['max'] = sprintf( esc_html__( ' Field "%s" maximum allowed value %f', 'cbxmcratingreview' ), $field['label'], $field['min'] );
							$affectedFields[]             = $key;

						}
					} else if ( $field['type'] == 'radio' ) { //radio type field


					} else if ( $field['type'] == 'select' ) { //multi select field

						if ( $multiple_field ) {
							//multi checkbox used for multiple select
							if ( empty( $value ) || ! is_array( $value ) ) {
								$errorHappened                     = true;
								$errorMessages[ $key ]['required'] = $field_errorsmg;
								$affectedFields[]                  = $key;

							}
						}

					}


					//field is ok, let move save for db entry
					if ( $extrafield ) {
						$formSavableData['extrafields'][ $key ] = $value;
					} else {
						$formSavableData[ $key ] = $value;
					}
					//end not required

				}//end foreach for generic fields
				//general fields ends.


				//$criteria_enable_count = 0;
				//validating custom criteria
				if ( isset( $ratingFormData['custom_criteria'] ) && ! empty( $ratingFormData['custom_criteria'] ) ) {

					$criteria_index = 0;

					//process every single criteria
					foreach ( $ratingFormData['custom_criteria'] as $criteria ) {

						$criteria_label = ( isset( $criteria['label'] ) && $criteria['label'] != '' ) ? sanitize_text_field( $criteria['label'] ) : sprintf( __( 'Criteria %d', 'cbxmcratingreview' ), ( $criteria_index + 1 ) );
						//$criteria_enabled = isset( $criteria['enabled'] ) ? absint( $criteria['enabled'] ) : 0;
						$criteria_id = isset( $criteria['criteria_id'] ) ? absint( $criteria['criteria_id'] ) : $criteria_index;

						//$formSavableData['custom_criteria'][ $criteria_index ]['enabled']     = $criteria_enabled;
						$formSavableData['custom_criteria'][ $criteria_index ]['label']       = $criteria_label;
						$formSavableData['custom_criteria'][ $criteria_index ]['criteria_id'] = $criteria_id;

						$star_index = 0;
						//$star_enable_count = 0;
						if ( isset( $criteria['stars'] ) && sizeof( $criteria['stars'] ) > 0 ) {
							//process every single star
							foreach ( $criteria['stars'] as $stars ) {

								$star_title = ( isset( $stars['title'] ) && ( $stars['title'] != '' ) ) ? sanitize_text_field( $stars['title'] ) : $star_titles[ $star_index % 5 ];

								//$star_enabled = isset( $stars['enabled'] ) ? absint( $stars['enabled'] ) : 0; //single star enabled status
								//$star_id      = isset( $stars['star_id'] ) ? absint( $stars['star_id'] ) : $star_index;


								$formSavableData['custom_criteria'][ $criteria_index ]['stars'][ $star_index ]['title'] = $star_title;
								//$formSavableData['custom_criteria'][ $criteria_index ]['stars'][ $star_index ]['enabled'] = $star_enabled;
								//$formSavableData['custom_criteria'][ $criteria_index ]['stars'][ $star_index ]['star_id'] = $star_id;


								/*if ( $star_enabled == 1 ) {
									$star_enable_count ++;
								}*/

								$star_index ++;
							}//end star process

							/*if ( $criteria_enabled == 1 && $star_enable_count == 0 ) {
								$errorHappened    = true;
								$errorText        = sprintf( esc_html__( 'Criteria "%s" must have at least one star enabled', 'cbxmcratingreview' ), $criteria_label );
								$errorMessages[]  = $errorText;
								$affectedFields[] = 'custom_criteria';
							}*/
						} else {
							$errorHappened                                       = true;
							$errorMessages['custom_criteria'][ $criteria_index ] = sprintf( esc_html__( 'Criteria "%s" must have at least one star', 'cbxmcratingreview' ), $criteria_label );
							$affectedFields[]                                    = 'custom_criteria_' . $criteria_index;
						}


						//$star_last_count                                                          = isset( $criteria['star_last_count'] ) ? absint( $criteria['star_last_count'] ) : $star_index;
						//$formSavableData['custom_criteria'][ $criteria_index ]['star_last_count'] = $star_last_count;

						/*if ( $criteria_enabled ) {
							$criteria_enable_count ++;
						}*/


						$criteria_index ++;

					}//end criteria process


					$formSavableData['custom_criteria'] = maybe_serialize( $formSavableData['custom_criteria'] );

					/*if ( ( $criteria_enable_count == 0 ) ) {
						$errorHappened                               = true;
						$errorMessages['custom_criteria']['general'] = esc_html__( 'You must enable and name at least one criteria enabled', 'cbxmcratingreview' );;

						$affectedFields[] = 'custom_criteria';
					}*/

				} else {
					$errorHappened                               = true;
					$errorMessages['custom_criteria']['general'] = esc_html__( 'The form must have at least one criteria', 'cbxmcratingreview' );
					$affectedFields[]                            = 'custom_criteria';
				}
				//end validating custom criteria

				$formSavableData['custom_question'] = array();

				//validating custom questions
				if ( isset( $ratingFormData['custom_question'] ) && ! empty( $ratingFormData['custom_question'] ) ) {
					$emptyTitle = 0;
					foreach ( $ratingFormData['custom_question'] as $index => $question ) {
						if ( empty( $question['type'] ) ) {
							continue;
						}
						if ( ! empty( $question['title'] ) ) {

							$formSavableData['custom_question'][ $index ] = array();

							$formSavableData['custom_question'][ $index ]['title'] = sanitize_text_field( $question['title'] );

							$formSavableData['custom_question'][ $index ]['required'] = isset( $question['required'] ) ? intval( $question['required'] ) : 0;
							$formSavableData['custom_question'][ $index ]['enabled']  = isset( $question['enabled'] ) ? intval( $question['enabled'] ) : 0;

							if ( isset( $question['multiple'] ) ) {
								$formSavableData['custom_question'][ $index ]['multiple'] = intval( $question['multiple'] );
							}
							if ( isset( $question['last_count'] ) ) {
								$formSavableData['custom_question'][ $index ]['last_count'] = intval( $question['last_count'] );
							}
							if ( isset( $question['placeholder'] ) ) {
								$formSavableData['custom_question'][ $index ]['placeholder'] = sanitize_text_field( $question['placeholder'] );
							}

							$type                                                 = sanitize_text_field( $question['type'] );
							$formSavableData['custom_question'][ $index ]['type'] = $type;


							if ( isset( $question['options'] ) ) {
								$options = $question['options'];
								foreach ( $options as $option_index => $option ) {
									$option_title                                                                     = sanitize_text_field( $option['text'] );
									$formSavableData['custom_question'][ $index ]['options'][ $option_index ]['text'] = ( $option_title != '' ) ? $option_title : esc_html__( 'Untitled Option', 'cbxmcratingreview' );

									if ( $option_title == '' ) {
										$emptyTitle ++;
									}
								}
							}

						} else {
							$emptyTitle ++;
						}
					}//end validating custom questions

					$formSavableData['custom_question'] = maybe_serialize( $formSavableData['custom_question'] );

					if ( ( $emptyTitle > 0 ) ) {
						$errorHappened    = true;
						$errorText        = esc_html__( 'One of your question title or option title field is empty', 'cbxmcratingreview' );
						$errorMessages[]  = $errorText;
						$affectedFields[] = 'custom_question';
					}
				} else {
					$formSavableData['custom_question'] = maybe_serialize( array() ); //anything better
				}

				//if not error happened then update/insert
				if ( ! $errorHappened && empty( $errorMessages ) ) {

					$formSavableData['extrafields'] = maybe_serialize( $formSavableData['extrafields'] );

					$form_id = CBXMCRatingReviewHelper::insert_update_ratingForm( $formSavableData );

					if ( $form_id !== false ) {

						if ( isset( $_SESSION['cbxmcratingreview_form_alidation_errors'][ $form_id ] ) ) {
							unset( $_SESSION['cbxmcratingreview_form_alidation_errors'][ $form_id ] );
						}

						//check total form count if count == 1, set this form as default, if //todo:


						/*if ( CBXMCRatingReviewHelper::current_user_can_view_ratingsystem( $form_id ) === true ) {
							update_option( 'cbxmcratingreview_defaultratingForm', $form_id );
						}*/

						$redirect_url = add_query_arg( array(
							'id'         => intval( $form_id ),
							'cbxupdated' => 1,
						), $form_edit_url );

						CBXMCRatingReviewHelper::redirect( $redirect_url );

					} else {
						$errorHappened            = true;
						$errorMessages['overall'] = esc_html__( 'Form save failed. Please check all fields are filled properly.', 'cbxmcratingreview' );
						$affectedFields[]         = 'overall';
					}

				}//end if not error happened then update/insert

				//if validation failed or error happened
				if ( $errorHappened && ! empty( $errorMessages ) ) {
					$error_data = array(
						'affectedFields'  => $affectedFields,
						'formSavableData' => $formSavableData,
						'errorMessages'   => $errorMessages
					);

					$_SESSION['cbxmcratingreview_form_validation_errors'][ intval( $form_id ) ] = $error_data;


					$redirect_url = add_query_arg( array(
						'id'         => intval( $form_id ),
						'cbxupdated' => 0,
					), $form_edit_url );


					CBXMCRatingReviewHelper::redirect( $redirect_url );
				}
			}
		}//end method ratingform_submit

	}//end class CBXMCRatingReview_Admin