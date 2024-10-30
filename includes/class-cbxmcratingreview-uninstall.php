<?php

/**
 * Fired during plugin uninstall
 *
 * @link       codeboxr.com
 * @since      1.0.0
 *
 * @package    CBXMCRatingReview
 * @subpackage CBXMCRatingReview/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's uninstallation.
 *
 * @since      1.0.0
 * @package    CBXMCRatingReview
 * @subpackage CBXMCRatingReview/includes
 * @author     CBX Team  <info@codeboxr.com>
 */
class CBXMCRatingReview_Uninstall {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function uninstall() {

		global $wpdb;

		$settings = new CBXMCRatingReviewSettings();

		$delete_global_config = $settings->get_option( 'delete_global_config', 'cbxmcratingreview_tools', 'no' );
		if ( $delete_global_config == 'yes' ) {
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
			do_action( 'cbxmcratingreview_plugin_uninstall' );

		}
	}//end method uninstall

}