<?php

/**
 * Fired during plugin activation
 *
 * @link       https://codeboxr.com
 * @since      1.0.0
 *
 * @package    CBXMCRatingReview
 * @subpackage CBXMCRatingReview/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    CBXMCRatingReview
 * @subpackage CBXMCRatingReview/includes
 * @author     Sabuj Kundu <sabuj@codeboxr.com>
 */
class CBXMCRatingReview_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		//check if can activate plugin
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}


		$plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
		check_admin_referer( "activate-plugin_{$plugin}" );

		//create tables
		CBXMCRatingReview_Activator::createTables();
	}//end method activate

	/**
	 * Create  necessary tables needed for 'cbxmcratingreview'
	 */
	public static function createTables() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		//tables
		$table_rating_form = $wpdb->prefix . 'cbxmcratingreview_form';
		$table_rating_log  = $wpdb->prefix . 'cbxmcratingreview_log';
		$table_rating_avg  = $wpdb->prefix . 'cbxmcratingreview_log_avg';


		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		//create rating form table
		$table_rating_form_sql = "CREATE TABLE $table_rating_form (
                        id mediumint(8) unsigned not null auto_increment,
                        name text not null,
                        status tinyint(1) not null,                                                                                                                                            
                        custom_criteria longtext not null,                        
                        custom_question longtext not null,                       
                        extrafields longtext NOT NULL  DEFAULT '',
                        PRIMARY KEY  (`id`)) $charset_collate; ";

		dbDelta( $table_rating_form_sql );

		//create rating log table
		$table_rating_log_sql = "CREATE TABLE $table_rating_log (
                          id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                          form_id mediumint(8) unsigned NOT NULL COMMENT 'foreign key of cbxmcratingreview_form table',
                          post_id bigint(20) unsigned NOT NULL COMMENT 'foreign key of posts table',
                          post_type varchar(50) NOT NULL COMMENT 'post type e.g. post, page, media',
                          user_id bigint(20) unsigned NOT NULL COMMENT 'foreign key of users table',
                          score float NOT NULL DEFAULT '0' COMMENT 'user given avg rating, can be half i.e. 3.5',   
                          headline varchar(255) NOT NULL DEFAULT '' COMMENT 'review short title',
                          comment text NOT NULL DEFAULT '' COMMENT 'review full desc',
                          ratings longtext not null COMMENT 'all criteria details rating',
                          questions longtext not null COMMENT 'answer of all questions',
                          attachment text NOT NULL DEFAULT '' COMMENT 'photos or video url',                             
                          extraparams text NOT NULL DEFAULT '' COMMENT 'extra parameters for future new fields',  
                          status varchar(20) NOT NULL DEFAULT 0 COMMENT '0 pending, 1 published, 2 unpublished, 3 spam',                          
                          mod_by bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'foreign key of user table. who last modify this list',
                          date_created datetime NOT NULL COMMENT 'created date',
                          date_modified datetime DEFAULT NULL COMMENT 'modified date',
                          PRIMARY KEY (id)
                        ) $charset_collate; ";

		dbDelta( $table_rating_log_sql );

		//create table for rating avg log
		$table_rating_avg_log_sql = "CREATE TABLE $table_rating_avg (
                          id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                          form_id mediumint(8) unsigned NOT NULL COMMENT 'foreign key of cbxmcratingreview_form table',
                          post_id bigint(20) unsigned NOT NULL COMMENT 'foreign key of posts table',
                          post_type varchar(50) NOT NULL COMMENT 'post type e.g. post, page, media',
                          avg_rating float NOT NULL DEFAULT '0' COMMENT 'user given rating avg',  
                          total_count bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'total user rate for this post',
                          rating_stat text NOT NULL DEFAULT '' COMMENT 'statistics about how many user give 1, 2, 3, 4, 5 rating',  
                		  date_created datetime NOT NULL COMMENT 'created date',
                          date_modified datetime DEFAULT NULL COMMENT 'modified date',
                          PRIMARY KEY (id)
                        ) $charset_collate; ";

		dbDelta( $table_rating_avg_log_sql );
	}//end method createTables

}//end class CBXMCRatingReview_Activator