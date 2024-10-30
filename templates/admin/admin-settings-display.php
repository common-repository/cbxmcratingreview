<?php
/**
 * Provide a dashboard Setting
 *
 * This file is used to markup the admin setting page
 *
 * @link       https://codeboxr.com
 * @since      1.0.0
 *
 * @package    cbxmcratingreview
 * @subpackage cbxmcratingreview/templates/admin
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div class="wrap">
    <h2><?php esc_html_e( 'CBX Multi Criteria Rating & Review: Setting', 'cbxmcratingreview' ); ?></h2>
    <div id="poststuff">
        <div id="post-body" class="metabox-holder">
            <!-- main content -->
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <div class="postbox">
                        <div class="inside">
							<?php
							$this->setting->show_navigation();
							$this->setting->show_forms();
							?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clear clearfix"></div>
    </div>
</div>