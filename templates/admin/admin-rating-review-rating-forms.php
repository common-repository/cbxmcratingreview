<?php
/**
 * Provide a dashboard rating form listing
 *
 * This file is used to markup the admin-facing rating form listing
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
$cbxmcratingreview_review_logs = new CBXMCRatingReviewForm_List_Table();

//Fetch, prepare, sort, and filter CBXMCRatingReviewLog data
$cbxmcratingreview_review_logs->prepare_items();
?>

<div class="wrap">
    <h1 class="wp-heading-inline">
		<?php esc_html_e( 'Rating Forms Manager', 'cbxmcratingreview' ); ?>
    </h1>
    <div id="poststuff">
        <div id="post-body" class="metabox-holder">
            <!-- main content -->
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <div class="postbox">
                        <div class="inside">
                            <a class="button button-primary"
                               href="<?php echo admin_url( 'admin.php?page=cbxmcratingreviewformlist&view=addedit&id=0' ); ?>"><?php esc_html_e( 'Add New Form', 'cbxmcratingreview' ); ?></a>
                            <form id="cbxmcratingreview_review_logs" method="post">
								<?php $cbxmcratingreview_review_logs->views(); ?>

                                <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
								<?php $cbxmcratingreview_review_logs->search_box( esc_html__( 'Search Forms', 'cbxmcratingreview' ), 'cbxmcratingreviewformsearch' ); ?>
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