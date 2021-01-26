<?php

$styles['font-family'] = $font_family;

$styles['font-size'] = is_numeric( $font_size ) ? $font_size . 'px' : $font_size;
$styles['line-height'] = $line_height;
$styles['letter-spacing'] = is_numeric( $letter_spacing ) ? $letter_spacing . 'px' : $letter_spacing;

if ( ! empty( $padding ) ) {
	$styles['padding'] = $padding;
}

if ( ! empty( $margin ) ) {
	$styles['margin'] = $margin;
}

if ( !empty( $responsive ) ) {
	wp_enqueue_script( 'fittext' );

	if ( !isset( $max_font_size ) || $max_font_size == 0 ) $max_font_size = '';
	if ( !isset( $min_font_size ) || $min_font_size == 0 ) $min_font_size = '';

	if ( !empty( $max_font_size ) ) $max_font_size = 'maxFontSize: "' . $max_font_size . 'px"';
	if ( (!empty( $font_size ) && $font_size != 0) && empty( $max_font_size ) ) $max_font_size = 'maxFontSize: "' . $font_size . 'px"';
	if ( !empty( $min_font_size ) ) $min_font_size = 'minFontSize: "' . $min_font_size . 'px"';
	if ( !empty( $max_font_size ) && !empty( $min_font_size ) ) $min_font_size .= ', ';

	$aggressiveness = 1;
	$line_height = 'normal';

	?>

	<script>
		jQuery(window).load(function() {
			jQuery('#<?php echo $id; ?>').fitText(<?php echo $aggressiveness; ?>, {
				<?php echo $min_font_size; ?>
				<?php echo $max_font_size; ?>
			});
		});
	</script>

	<?php

}
?>

<<?php echo esc_attr( $tag ); ?> <?php echo $id_attr; ?> <?php BetterCore()->do_style( $styles );?> class="better-text<?php echo ( ! empty( $class ) ? ' ' . $class : '' ); ?>"><?php echo $content; ?></<?php echo esc_attr( $tag ); ?>>
