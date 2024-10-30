<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://codeboxr.com
 * @since             1.0.0
 * @package           CBXMCRatingReview
 *
 * @wordpress-plugin
 * Plugin Name:       CBX Multi Criteria Rating & Review System
 * Plugin URI:        https://codeboxr.com/product/cbx-multi-criteria-rating-review-for-wordpress/
 * Description:       Multi Criteria Rating & Review System for WordPress
 * Version:           1.0.3
 * Author:            Codeboxr
 * Author URI:        https://codeboxr.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cbxmcratingreview
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


defined( 'CBXMCRATINGREVIEW_PLUGIN_NAME' ) or define( 'CBXMCRATINGREVIEW_PLUGIN_NAME', 'cbxmcratingreview' );
defined( 'CBXMCRATINGREVIEW_PLUGIN_VERSION' ) or define( 'CBXMCRATINGREVIEW_PLUGIN_VERSION', '1.0.3' );
defined( 'CBXMCRATINGREVIEW_BASE_NAME' ) or define( 'CBXMCRATINGREVIEW_BASE_NAME', plugin_basename( __FILE__ ) );
defined( 'CBXMCRATINGREVIEW_ROOT_PATH' ) or define( 'CBXMCRATINGREVIEW_ROOT_PATH', plugin_dir_path( __FILE__ ) );
defined( 'CBXMCRATINGREVIEW_ROOT_URL' ) or define( 'CBXMCRATINGREVIEW_ROOT_URL', plugin_dir_url( __FILE__ ) );

defined( 'CBXMCRATINGREVIEW_RAND_MIN' ) or define( 'CBXMCRATINGREVIEW_RAND_MIN', 0 );
defined( 'CBXMCRATINGREVIEW_RAND_MAX' ) or define( 'CBXMCRATINGREVIEW_RAND_MAX', 999999 );
defined( 'CBXMCRATINGREVIEW_COOKIE_EXPIRATION_14DAYS' ) or define( 'CBXMCRATINGREVIEW_COOKIE_EXPIRATION_14DAYS', time() + 1209600 ); //Expiration of 14 days.
defined( 'CBXMCRATINGREVIEW_COOKIE_EXPIRATION_7DAYS' ) or define( 'CBXMCRATINGREVIEW_COOKIE_EXPIRATION_7DAYS', time() + 604800 ); //Expiration of 7 days.
defined( 'CBXMCRATINGREVIEW_COOKIE_NAME' ) or define( 'CBXMCRATINGREVIEW_COOKIE_NAME', 'cbrating-cookie-session' );


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cbxmcratingreview-activator.php
 */
function activate_cbxmcratingreview() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cbxmcratingreview-activator.php';
	CBXMCRatingReview_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cbxmcratingreview-deactivator.php
 */
function deactivate_cbxmcratingreview() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cbxmcratingreview-deactivator.php';
	CBXMCRatingReview_Deactivator::deactivate();
}

/**
 * The code that runs during plugin uninstall.
 * This action is documented in includes/class-cbxmcratingreview-uninstall.php
 */
function uninstall_cbxmcratingreview() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cbxmcratingreview-uninstall.php';
	CBXMCRatingReview_Uninstall::uninstall();
}

register_activation_hook( __FILE__, 'activate_cbxmcratingreview' );
register_deactivation_hook( __FILE__, 'deactivate_cbxmcratingreview' );
register_uninstall_hook( __FILE__, 'uninstall_cbxmcratingreview' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-cbxmcratingreview.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_cbxmcratingreview() {

	$plugin = new CBXMCRatingReview();
	$plugin->run();

}

run_cbxmcratingreview();