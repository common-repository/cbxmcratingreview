<?php
/**
 * Provides review list
 *
 * This file is used to markup frontend review list and includes sub templates
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
do_action( 'cbxmcratingreview_review_list_before', $post_reviews );
?>

    <ul class="<?php echo apply_filters( 'cbxmcratingreview_review_list_items_class', 'cbxmcratingreview_review_list_items' ); ?>"
        id="cbxmcratingreview_review_list_items_<?php echo intval( $post_id ); ?>">
		<?php
		if ( sizeof( $post_reviews ) > 0 ) {
			foreach ( $post_reviews as $index => $post_review ) { ?>
                <li id="cbxmcratingreview_review_list_item_<?php echo intval( $post_review['id'] ); ?>"
                    class="<?php echo apply_filters( 'cbxmcratingreview_review_list_item_class', 'cbxmcratingreview_review_list_item' ); ?>">
					<?php
						//include( 'rating-review-reviews-list-item.php' );
						include (cbxmcratingreview_locate_template('rating-review-reviews-list-item.php'));
					?>
                </li>
			<?php }
		} else {
			?>
            <li class="<?php echo apply_filters( 'cbxmcratingreview_review_list_item_class_notfound_class', 'cbxmcratingreview_review_list_item cbxmcratingreview_review_list_item_notfound' ); ?>">
                <p class="no_reviews_found"><?php esc_html_e( 'No reviews yet!', 'cbxmcratingreview' ); ?></p>
            </li>
			<?php
		}

		?>
    </ul>

<?php
do_action( 'cbxmcratingreview_review_list_after', $post_reviews );