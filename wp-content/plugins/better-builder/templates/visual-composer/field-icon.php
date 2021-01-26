<div class="better_icontype">
	<select id="<?php echo $settings['param_name']; ?>" name="<?php echo $settings['param_name']; ?>" class="wpb_vc_param_value wpb-input wpb-select <?php echo $settings['param_name']; ?> <?php echo $settings['type']; ?>" data-option="<?php echo $value; ?>">
		<option value=""><?php echo __( 'Select Icon', 'better' ); ?></option>
	</select>
</div>
<script>
jQuery( 'body' ).trigger( 'icon-param-loaded', [ "<?php echo $settings['param_name']; ?>" ] );
</script>
