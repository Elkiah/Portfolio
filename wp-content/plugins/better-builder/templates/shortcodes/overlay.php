<?php

wp_enqueue_style( 'better.overlay' );

if ( ! empty( $effect ) ) {
	wp_enqueue_style( 'better.' . $effect );
	wp_enqueue_script( 'better.subtle' );
}

if ( !empty( $split_title ) || ( $effect == 'ls-boxed' ) ) {
	$split_title = explode( ' ', $title, 3 );
} else {
	$split_title = array();
	$split_title[0] = $title;
}

if ( is_numeric( $link ) ) {
	$link = get_permalink( $link );
}

$image_alt = '';

if ( !empty( $image ) ) {
	$alt = get_post_meta( $image, '_wp_attachment_image_alt', true );

	if ( $alt ) {
		$image_alt =  $alt;
	}
}

if ( is_numeric( $font_size ) ) {
	$font_size .= "px";
}

$font_size = ( !empty( $font_size ) ? " font-size: " . $font_size . ';' : '' );
$font_color = ( !empty( $font_color ) ? " color: " . $font_color . ';' : '' );

if ( !empty( $image ) ) {
	$image = BetterCore()->get_image_src( $image, $image_size ); 
}

?>

<?php if ( !empty( $link ) ) { ?>
<a href="<?php echo $link; ?>">
<?php } ?>

	<div class="better overlay subtle-effect <?php echo ( !empty( $class ) ? esc_attr( $class ) : '' ) ?>">
		<figure class='better effect-<?php echo $effect; ?>' style='<?php echo ( !empty( $link ) ? 'cursor: pointer;' : 'cursor: default;' ) ?>'>

			<?php if ( !empty( $image ) ) { ?>
			<img class="subtle-effect-img" src="<?php echo $image[0]; ?>" alt="<?php echo $image_alt; ?>" style="max-width: 100%;" />
			<?php } ?>

			<?php if ( $effect == 'ls-boxed' ) { ?>
			<div class='title'>
				<div>
					<h2 style="<?php echo $font_color . $font_size; ?>"><?php echo ( !empty( $split_title[0] ) ? esc_html( $split_title[0] ) : '' ); ?></h2>
					<h4 style="<?php echo $font_color . $font_size; ?>"><?php echo ( !empty( $split_title[1] ) ? esc_html( $split_title[1] ) : '' ) . ( !empty( $split_title[2] ) ? ' ' . esc_html( $split_title[2] ) : '' ); ?></h4>
				</div>
			</div>
			<?php } ?>

			<figcaption class="subtle-effect-caption">
				<?php if ( $effect != 'ls-boxed' ) { ?>
				<h2 style="<?php echo $font_color . $font_size; ?>"><?php echo ( !empty( $split_title[0] ) ? esc_html( $split_title[0] ) : '' ) . '<span>' . ( !empty( $split_title[1] ) ? ' ' . esc_html( $split_title[1] ) : '' ) . '</span>' . ( !empty( $split_title[2] ) ? ' ' . esc_html( $split_title[2] ) : '' ); ?></h2>
				<?php } ?>
				<div class="effect-description"><?php echo $content; ?></div>
			</figcaption>
		</figure>
	</div>

<?php if ( !empty( $link ) ) { ?>
</a>
<?php } ?>
