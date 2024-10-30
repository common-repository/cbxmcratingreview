<?php
/**
 * Provide a Admin view for the plugin
 *
 * This file is used to markup the admin facing widget form
 *
 * @link       https://codeboxr.com
 * @since      1.0.0
 *
 * @package    cbxmcratingreview
 * @subpackage cbxmcratingreview/public/templates
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}
?>

<!-- Custom  Title Field -->
<p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'cbxmcratingreview' ); ?></label>

    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
           name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>"/>
</p>
<!-- form listing -->
<p>
	<label for="<?php echo $this->get_field_id( 'form_id' ); ?>"><?php esc_html_e( "Select Form", "cbxmcratingreview" ) ?>

		<select class="widefat" id="<?php echo $this->get_field_id( 'form_id' ); ?>"
				name="<?php echo $this->get_field_name( 'form_id' ); ?>">
			<?php
				foreach ( $forms as $form ) {
					$form_id_ = intval( $form['id'] ) ? intval( $form['id'] ) : 0;
					$form_name = isset($form['name'])? esc_attr($form['name']) : esc_html__('Untitled Form', 'cbxmcratingreview') ;
					echo '<option value="' . $form_id_ . '" ' . ( ( $form_id_ == $form_id ) ? ' selected="selected" ' : '' ) . '>' . $form_name . '</option>';
				}
			?>
		</select>
	</label>
</p>

<!-- Display Limit -->
<p>
    <label for="<?php echo $this->get_field_id( 'limit' ); ?>">
		<?php esc_html_e( 'Display Limit:', "cbxmcratingreview" ); ?>
    </label>

    <input class="widefat" id="<?php echo $this->get_field_id( 'limit' ); ?>"
           name="<?php echo $this->get_field_name( 'limit' ); ?>" type="text" value="<?php echo $limit; ?>"/>
</p>

<!-- Order by Selection -->
<p>
    <label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php esc_html_e( "Order By", "cbxmcratingreview" ) ?>
        <select class="widefat" id="<?php echo $this->get_field_id( 'orderby' ); ?>"
                name="<?php echo $this->get_field_name( 'orderby' ); ?>">
            <option value="id" <?php echo ( $orderby == "id" ) ? 'selected="selected"' : ''; ?>> <?php esc_html_e( "Rating ID", "cbxmcratingreview" ) ?>     </option>
            <option value="post_id" <?php echo ( $orderby == "post_id" ) ? 'selected="selected"' : ''; ?>> <?php esc_html_e( "Article ID", "cbxmcratingreview" ) ?> </option>
            <option value="score" <?php echo ( $orderby == "score" ) ? 'selected="selected"' : ''; ?>> <?php esc_html_e( "Rating Score", "cbxmcratingreview" ) ?> </option>
        </select>
    </label>
</p>
<!-- Selection of Ascending or Descending -->
<p>
    <label for="<?php echo $this->get_field_id( 'order' ); ?>"><?php esc_html_e( "Order", "cbxmcratingreview" ) ?>

        <select class="widefat" id="<?php echo $this->get_field_id( 'order' ); ?>"
                name="<?php echo $this->get_field_name( 'order' ); ?>">
            <option
                    value="asc" <?php echo ( $order == "asc" ) ? 'selected="selected"' : ''; ?>> <?php esc_html_e( "Ascending", "cbxmcratingreview" ) ?> </option>
            <option
                    value="desc" <?php echo ( $order == "desc" ) ? 'selected="selected"' : ''; ?>> <?php esc_html_e( "Descending", "cbxmcratingreview" ) ?> </option>
        </select>
    </label>
</p>
<?php
$all_post_types = CBXMCRatingReviewHelper::post_types( true );
?>
<p>
    <label for="<?php echo $this->get_field_id( 'type' ); ?>"><?php esc_html_e( "Post Type", "cbxmcratingreview" ) ?>

        <select class="widefat" id="<?php echo $this->get_field_id( 'type' ); ?>"
                name="<?php echo $this->get_field_name( 'type' ); ?>">
			<?php
			foreach ( $all_post_types as $post_key => $post_name ) {
				echo '<option value="' . $post_key . '" ' . ( ( $post_key == $type ) ? ' selected="selected" ' : '' ) . '>' . esc_attr( $post_name ) . '</option>';
			}
			?>
        </select>
    </label>
</p>
<?php
do_action( 'cbxmcratingreviewlatestratingswidget_form_admin', $instance, $this )
?>

<input type="hidden" id="<?php echo $this->get_field_id( 'submit' ); ?>"
       name="<?php echo $this->get_field_name( 'submit' ); ?>" value="1"/>

