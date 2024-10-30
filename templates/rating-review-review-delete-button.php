<?php
/**
 * Provides review delete button
 *
 * This file is used to markup frontend review delete button
 *
 * @link       https://codeboxr.com
 * @since      1.0.0
 *
 * @package    cbxmcratingreview
 * @subpackage cbxmcratingreview/templates
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<span class="cbxmcratingreview_review_list_item_toolbar_item cbxmcratingreview_review_list_item_toolbar_item_deletebutton">
	<a href="#" class="cbxmcratingreview-review-delete" data-busy="0"
       data-reviewid="<?php echo intval( $post_review['id'] ) ?>"
       data-postid="<?php echo intval( $post_review['post_id'] ) ?>"
       title="<?php esc_html_e( 'Click to delete this review', 'cbxmcratingreview' ); ?>"><?php esc_html_e( 'Delete Review', 'cbxmcratingreview' ); ?></a>
</span>