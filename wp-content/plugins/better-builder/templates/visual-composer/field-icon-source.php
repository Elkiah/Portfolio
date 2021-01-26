<?php
$output = '<div class="better_iconsource">';
$output .= '<select
        name="'
        . $settings['param_name']
        . '" class="wpb_vc_param_value wpb-input wpb-select '
        . $settings['param_name']
        . ' ' . $settings['type'] . '">';

$packs = BetterSC_Icon::get_icon_sources();

if ( is_array( $packs ) ) {
    foreach ($packs as $key => $pack) {
        $selected = '';
        $option_value_string = (string) $key;
        $value_string = (string) $value;
        if ( '' !== $value && $option_value_string === $value_string ) {
            $selected = ' selected="selected"';
        }
        $output .= '<option class="' . $key . '" value="' . $key . '" ' . $selected . '>' . $pack['title'] . ' (' . $pack['count'] . ')' . '</option>';
    }
}

$output .= '</select>';
$output .= '</div>';

echo $output;
