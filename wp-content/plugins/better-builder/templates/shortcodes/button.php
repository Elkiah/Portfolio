<?php
$hover_styles = array();

//$styles['background-color'] = $background_color;
$styles['color'] = $text_color;

$padding_tb = is_numeric( $padding_tb ) ? $padding_tb . 'px' : $padding_tb;
$padding_lr = is_numeric( $padding_lr ) ? $padding_lr . 'px' : $padding_lr;
$border_width = is_numeric( $border_width ) ? $border_width . 'px' : $border_width;
$border_radius = is_numeric( $border_radius ) ? $border_radius . 'px' : $border_radius;
$font_size = is_numeric( $font_size ) ? $font_size . 'px' : $font_size;

if ( ! empty( $border_radius ) ) {
	$styles['border-radius'] = $border_radius;
}

if ( ! empty( $font_size ) ) {
	$styles['font-size'] = $font_size;
}

if ( ! empty( $padding_lr ) ) {
	$styles['padding-left'] = $padding_lr;
	$styles['padding-right'] = $padding_lr;
}

if ( ! empty( $padding_tb ) ) {
	$styles['padding-top'] = $padding_tb;
	$styles['padding-bottom'] = $padding_tb;
}

if ( $button_style === 'outline' ) {
	$styles['background-color'] = 'transparent';
	$styles['border-color'] = $border_color;
	$styles['border-width'] = $border_width;
	$styles['color'] = $border_color;

	$hover_styles['background-color'] = $border_color_hover;
	$hover_styles['border-color'] = $border_color_hover;

	$class .= ' better_btn-outline';
} elseif ( $button_style === 'gradient' ) {
	$styles['background-color'] = $background_gradient_start;
	$styles['background-image'] = 'linear-gradient( 45deg, ' . $background_gradient_start . ' 0%, ' . $background_gradient_end . ' 100%)';

	$hover_styles['background-color'] = $background_gradient_start_hover;
	if ( $background_gradient_end_hover !== '' ) {
		$hover_styles['background-image'] = 'linear-gradient( 45deg, ' . $background_gradient_start_hover . ' 0%, ' . $background_gradient_end_hover . ' 100%)';
	} else {
		$hover_styles['background-image'] = 'none';
	}
} else {
	$styles['background-color'] = $background_color;
	$hover_styles['background-color'] = $background_color_hover;
}

$hover_styles['color'] = $text_color_hover !== '' ? $text_color_hover : '';

$attributes = array(
	'target' => $target,
	'rel' => $rel
);

// url for wpbakery element
if ( ! empty( $url ) && function_exists( 'vc_build_link' ) ) {
	$vc_link = vc_build_link( $atts['url'] );
	if ( $vc_link ) {
		$url = $vc_link['url'];
	}
}

?>

<style>
	<?php echo '#' . $id; ?>:hover {
		<?php
		foreach ( $hover_styles as $key => $value ) {
			if ( !empty( $value ) ) {
				echo $key . ': ' . $value . ' !important;';
			}
		}
		?>
	}
</style>

<div class="btn_align<?php echo $align; ?>">
	<a href="<?php echo esc_url( $url ); ?>" <?php echo $id_attr; ?> class="better_btn <?php echo $class; ?>" <?php BetterCore()->do_attributes( $attributes ); ?> <?php BetterCore()->do_style( $styles ); ?>><?php echo $content; ?></a>
</div>