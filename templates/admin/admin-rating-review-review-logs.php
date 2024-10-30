<?php
/**
 * Provide a dashboard rating log listing
 *
 * This file is used to markup the admin-facing rating log listing
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
$cbxmcratingreview_review_logs = new CBXMCRatingReviewLog_List_Table();

//Fetch, prepare, sort, and filter CBXMCRatingReviewLog data
$cbxmcratingreview_review_logs->prepare_items();
?>

<div class="wrap">
    <h1 class="wp-heading-inline">
		<?php esc_html_e( 'Rating Log Manager', 'cbxmcratingreview' ); ?>
    </h1>

    <div id="poststuff">
        <div id="post-body" class="metabox-holder">
            <!-- main content -->
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <div class="postbox">
                        <div class="inside">
                            <form id="cbxmcratingreview_review_logs" method="post">
								<?php $cbxmcratingreview_review_logs->views(); ?>

                                <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
								<?php $cbxmcratingreview_review_logs->search_box( esc_html__( 'Search Review Log', 'cbxmcratingreview' ), 'cbxmcratingreviewlogsearch' ); ?>

								<?php $cbxmcratingreview_review_logs->display() ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clear clearfix"></div>
    </div>
</div>