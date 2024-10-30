<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://codeboxr.com
 * @since      1.0.0
 *
 * @package    CBXMCRatingReview
 * @subpackage CBXMCRatingReview/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    CBXMCRatingReview
 * @subpackage CBXMCRatingReview/includes
 * @author     Sabuj Kundu <sabuj@codeboxr.com>
 */
class CBXMCRatingReview {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      CBXMCRatingReview_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->version     = CBXMCRATINGREVIEW_PLUGIN_VERSION;
		$this->plugin_name = CBXMCRATINGREVIEW_PLUGIN_NAME;

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}//end of constructor

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - CBXMCRatingReview_Loader. Orchestrates the hooks of the plugin.
	 * - CBXMCRatingReview_i18n. Defines internationalization functionality.
	 * - CBXMCRatingReview_Admin. Defines all hooks for the admin area.
	 * - CBXMCRatingReview_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxmcratingreview-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxmcratingreview-i18n.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxmcratingreview-setting.php';


		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxmcratingreview-rating-forms.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxmcratingreview-review-logs.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxmcratingreview-rating-avg-logs.php';


		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Html2Text.php';
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Html2TextException.php';
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/emogrifier.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxmcratingreview-emailtemplate.php';

		//mail sending helper
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxmcratingreview-mailhelper.php';
		//all email alert static method
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxmcratingreview-emailalert.php';

		//ajax file upload
		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/BlueimpFileUploadHandler.php';
		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/BlueimpFileUploadHandlerCustom.php';


		/**
		 * Helper functions and classes
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxmcratingreview-helper.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxmcratingreviewadmin-helper.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxmcratingreviewquestion-helper.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxmcratingreview-functions.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cbxmcratingreview-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the frontend site area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-cbxmcratingreview-public.php';

		//Widgets  of the site.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/cbxmcratingreviewmrposts/class-cbxmcratingreviewmrposts-widget.php'; //most rated posts
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/cbxmcratingreviewlratings/class-cbxmcratingreviewlratings-widget.php';  //latest ratings

		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/userlatestratings-widget/userlatestratings-widget.php';
		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/authormostratedposts-widget/authormostratedposts-widget.php';
		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/authorpostlatestratings-widget/authorpostlatestratings-widget.php';


		$this->loader = new CBXMCRatingReview_Loader();
	}//end method load_dependencies

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the CBXMCRatingReview_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new CBXMCRatingReview_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}//end method set_locale

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new CBXMCRatingReview_Admin( $this->get_plugin_name(), $this->get_version() );

		//admin init functionality
		$this->loader->add_action('admin_init', $plugin_admin, 'plugin_fullreset', 0);
		$this->loader->add_action('admin_init', $plugin_admin, 'plugin_migration', 0);
		$this->loader->add_action( 'admin_init', $plugin_admin, 'setting_init' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'review_delete_after_delete_post_init' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'rating_adminform_submit' );

		$this->loader->add_action( 'admin_notices', $plugin_admin, 'migration_message_display' );
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'fullreset_message_display' );

		//create admin menu page
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_pages' );

		$this->loader->add_filter( 'plugin_action_links_' . CBXMCRATINGREVIEW_BASE_NAME, 'CBXMCRatingReviewHelper', 'plugin_action_links' );

		//screen options for admin item listing
		$this->loader->add_filter( 'set-screen-option', $plugin_admin, 'cbxmcratingreview_form_listing_per_page', 10, 3 ); //forms listing
		$this->loader->add_filter( 'set-screen-option', $plugin_admin, 'cbxmcratingreview_review_listing_per_page', 10, 3 ); //logs listing
		$this->loader->add_filter( 'set-screen-option', $plugin_admin, 'cbxmcratingreview_rating_avg_listing_per_page', 10, 3 ); //avg listing


		//setting init and add  setting sub menu in setting menu
		$this->loader->add_action( 'wp_ajax_cbxmcratingreview_review_rating_admin_edit', $plugin_admin, 'review_rating_admin_edit' );

		//add all css and js in backend
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );


		//on review publish
		$this->loader->add_action( 'cbxmcratingreview_review_publish', $plugin_admin, 'review_publish_adjust_avg' );
		$this->loader->add_action( 'cbxmcratingreview_review_unpublish', $plugin_admin, 'review_unpublish_adjust_avg' );
		//on review delete extra process
		$this->loader->add_action( 'cbxmcratingreview_review_delete_after', $plugin_admin, 'review_delete_after' );



		//rating form delete extra process
		$this->loader->add_action( 'cbxmcratingreview_form_delete_after', $plugin_admin, 'form_delete_after' );

		//on user delete
		$this->loader->add_action( 'delete_user', $plugin_admin, 'review_delete_after_delete_user' );
	}//end method define_admin_hooks

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new CBXMCRatingReview_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'init', $plugin_public, 'init_shortcodes' );

		//review rating entry via ajax
		$this->loader->add_action( 'wp_ajax_cbxmcratingreview_review_rating_frontend_submit', $plugin_public, 'review_rating_frontend_submit' );


		//ajax post reviews load more
		$this->loader->add_action( 'wp_ajax_cbxmcratingreview_post_more_reviews', $plugin_public, 'post_more_reviews_ajax_load' );
		$this->loader->add_action( 'wp_ajax_nopriv_cbxmcratingreview_post_more_reviews', $plugin_public, 'post_more_reviews_ajax_load' );

		//ajax review filter
		$this->loader->add_action( 'wp_ajax_cbxmcratingreview_post_filter_reviews', $plugin_public, 'post_filter_reviews_ajax_load' );
		$this->loader->add_action( 'wp_ajax_nopriv_cbxmcratingreview_post_filter_reviews', $plugin_public, 'post_filter_reviews_ajax_load' );

		//widget
		$this->loader->add_action( 'widgets_init', $plugin_public, 'init_register_widget' );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action( 'wp_ajax_cbxmcratingreview_review_rating_front_edit', $plugin_public, 'review_rating_front_edit' );

		//$this->loader->add_action( 'wp_ajax_cbxmcratingreview_review_rating_frontedit_fileupload', $plugin_public, 'review_rating_frontedit_fileupload' );
		//$this->loader->add_action( 'wp_ajax_cbxmcratingreview_review_rating_front_filedelete', $plugin_public, 'review_rating_frontedit_filedelete' );

		//ajax review delete from frontend
		$this->loader->add_action( 'wp_ajax_cbxmcratingreview_review_delete', $plugin_public, 'review_delete_ajax' );


		//special care of review edit for adjustment
		$this->loader->add_action( 'cbxmcratingreview_review_update_without_status', $plugin_public, 'cbxmcratingreview_review_update_without_status_adjust_postavg', 10, 3 );

		$this->loader->add_action( 'cbxmcratingreview_review_list_item_after', $plugin_public, 'cbxmcratingreview_single_review_toolbar', 8, 1 );
		$this->loader->add_action( 'cbxmcratingreview_review_list_item_toolbar_end', $plugin_public, 'cbxmcratingreview_single_review_delete_button' );

		$this->loader->add_filter( 'the_content', $plugin_public, 'the_content_auto_integration' );
	}//end method define_public_hooks

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    CBXMCRatingReview_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}