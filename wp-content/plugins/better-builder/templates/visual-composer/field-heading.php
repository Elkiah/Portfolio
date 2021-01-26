<?php
$dependency = '';
$param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
$class = isset($settings['class']) ? $settings['class'] : '';
$text = isset($settings['heading']) ? $settings['heading'] : '';
$output = '<h4 '.$dependency.' class="wpb_vc_param_value '.$class.'">'.$text.'</h4>';
$output .= '<input type="hidden" name="'.$settings['param_name'].'" class="wpb_vc_param_value better-param-heading '.$settings['param_name'].' '.$settings['type'].'_field" value="'.$value.'" '.$dependency.'/>';
echo $output;