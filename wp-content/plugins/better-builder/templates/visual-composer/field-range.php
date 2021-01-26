<div class="better-ui-form-range">
    <div class="better-ui-form-range-helper">
        <div class="better-ui-form-range-slider-bg"></div>
        <div class="better-ui-form-range-bg"></div>
        <input class="wpb_vc_param_value better-ui-form-range-slider" type="range" min="<?php echo $settings['min']; ?>" max="<?php echo $settings['max']; ?>" step="<?php echo $settings['step']; ?>" value="<?php echo ! empty( $value ) ? $value : 0; ?>" <?php echo $dependency; ?>>
    </div>
    <input class="wpb_vc_param_value better-ui-form-input better-ui-form-range-input wpb-textinput <?php echo $settings['param_name']; ?> <?php echo $settings['type']; ?>_field" name="<?php echo $settings['param_name']; ?>" type="text" min="<?php echo $settings['min']; ?>" max="<?php echo $settings['max']; ?>" value="<?php echo $value; ?>" <?php echo $dependency; ?>>
</div> 