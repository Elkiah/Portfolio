<?php
$values = get_post_custom( $post->ID );
$template_type = isset( $values['toolkit_template_type'] ) ? esc_attr( $values['toolkit_template_type'][0] ) : '';
//$display_on_canvas = isset( $values['toolkit_template_on_canvas'] ) ? true : false;
wp_nonce_field( 'toolkit_template_nonce', 'toolkit_template_nonce' );
?>
<table class="template-table widefat">
    <tbody>
    <tr class="template-row">
        <td class="template-row-heading">
            <label for="toolkit_template_type"><?php _e( 'Select Elementor Global Template Type', 'toolkit-for-elementor' ); ?></label>
        </td>
        <td class="template-row-content">
            <select name="toolkit_template_type" id="toolkit_template_type">
                <option value="" <?php selected( $template_type, '' ); ?>><?php _e( 'Select Option', 'toolkit-for-elementor' ); ?></option>
                <option value="type_header" <?php selected( $template_type, 'type_header' ); ?>><?php _e( 'Global Header', 'toolkit-for-elementor' ); ?></option>
                <option value="type_footer" <?php selected( $template_type, 'type_footer' ); ?>><?php _e( 'Global Footer', 'toolkit-for-elementor' ); ?></option>
            </select>
        </td>
    </tr>
   </tbody>
</table>
