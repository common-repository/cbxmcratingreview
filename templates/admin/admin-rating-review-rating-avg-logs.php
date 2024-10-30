<?php
/**
 * Provide a dashboard rating log avgs listing
 *
 * This file is used to markup the admin-facing rating avg log listing
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
$cbxmcratingreview_rating_avg_logs = new CBXMCRatingReviewRatingAvgLog_List_Table();

//Fetch, prepare, sort, and filter CBXMCRatingReviewRatingAvgLog data
$cbxmcratingreview_rating_avg_logs->prepare_items();
?>

<div class="wrap">
    <h1 class="wp-heading-inline">
		<?php esc_html_e( 'Average Log Manager', 'cbxmcratingreview' ); ?>
    </h1>

    <div id="poststuff">
        <div id="post-body" class="metabox-holder">
            <!-- main content -->
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <div class="postbox">
                        <div class="inside">
                            <form id="cbxmcratingreview_rating_avg_logs" method="post">
								<?php $cbxmcratingreview_rating_avg_logs->views(); ?>

                                <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
								<?php $cbxmcratingreview_rating_avg_logs->search_box( esc_html__( 'Search Rating Avg Log', 'cbxmcratingreview' ), 'experienceratingavglogsearch' ); ?>

								<?php $cbxmcratingreview_rating_avg_logs->display() ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clear clearfix"></div>
    </div>
</div>