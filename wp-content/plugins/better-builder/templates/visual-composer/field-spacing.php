<?php  
    $value_parts = explode( ' ', $value );

    if ( is_array( $value_parts ) ) {
        $top = $value_parts[0];
        $right = $value_parts[1];
        $bottom = $value_parts[2];
        $left = $value_parts[3];
    }
?>

<div class="vc-spacing-parameter">

    <label><i class="dashicons dashicons-arrow-up-alt"></i></label>
    <input name="<?php $settings['param_name']; ?>_top" class="spacing-top" type="text" value="<?php echo $top; ?>" />

    <label><i class="dashicons dashicons-arrow-right-alt"></i></label>
    <input name="<?php $settings['param_name']; ?>_right" class="spacing-right" type="text" value="<?php echo $right; ?>" />
    
    <label><i class="dashicons dashicons-arrow-down-alt"></i></label>
    <input name="<?php $settings['param_name']; ?>_bottom" class="spacing-bottom" type="text" value="<?php echo $bottom; ?>" />
    
    <label><i class="dashicons dashicons-arrow-left-alt"></i></label>
    <input name="<?php $settings['param_name']; ?>_left" class="spacing-left" type="text" value="<?php echo $left; ?>" />
    
    <input 
        name="<?php echo $settings['param_name']; ?>" 
        class="wpb_vc_param_value wpb-textinput <?php echo $settings['param_name']; ?> <?php echo $settings['type']; ?>_field" 
        type="hidden" 
        value="<?php echo $value; ?>" 
        <?php echo $dependency; ?>
    />

</div>