<h3><?php _e("Toolkit for Elementor", "blank"); ?></h3>
<table class="form-table">
    <tr>
        <th><label for="use_toolkit_features"><?php _e("Allow Use of Toolkit"); ?></label></th>
        <td>
            <?php $useToolkit = user_can( $user, 'use_toolkit_features' ); ?>
            <input type="checkbox" name="use_toolkit_features" id="use_toolkit_features" value="yes" <?php echo ($useToolkit) ? 'checked' : ''; ?>/>
            <span class="description"><?php _e("Please check to allow this user for use of Toolkit."); ?></span>
        </td>
    </tr>
</table>