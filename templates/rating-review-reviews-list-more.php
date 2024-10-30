<?php
/**
 * Provides review list item more button
 *
 * This file is used to markup frontend review list item more button
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
<?php
echo '<p style="' . ( ( $maximum_pages > 1 ) ? 'display:block;' : 'display:none;' ) . '" class="cbxmcratingreview_post_more_reviews" id="cbxmcratingreview_post_more_reviews_' . intval( $post_id ) . '"><a class="cbxmcratingreview_loadmore" data-busy="0" data-maxpage="' . $maximum_pages . '" data-score="' . $score . '" data-postid="' . intval( $post_id ) . '" data-formid="' . intval( $form_id ) . '" data-perpage="' . intval( $perpage ) . '" data-page="' . ( $page + 1 ) . '" data-orderby="' . $orderby . '" data-order="' . $order . '" href="#">' . esc_html__( 'Load more', 'cbxmcratingreview' ) . '</a></p>';