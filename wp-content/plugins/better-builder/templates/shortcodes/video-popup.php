<?php
wp_enqueue_script( 'magnific-popup' );
wp_enqueue_script( 'better.video-popup' );
wp_enqueue_style( 'better.video-popup' );
wp_enqueue_style( 'magnific-popup' );

$extra_attrs = ( $link_style == 'better-button' ) ? 'data-color-override="false"' : null;
$the_link_text = ( $link_style == 'better-button' ) ? $link_text : '<span class="play"><span class="inner-wrap"><svg version="1.1"
	 xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="600px" height="800px" x="0px" y="0px" viewBox="0 0 600 800" enable-background="new 0 0 600 800" xml:space="preserve"><path fill="none" d="M0-1.79v800L600,395L0-1.79z"></path> </svg></span></span>';
$color = ( $link_style == 'better-button' ) ? $button_color : $play_button_color;

// if ( $link_style == 'better-button' ) {
//     $color = $play_button_color;
// }

$extra_class = '';

if ( $link_style == 'better-button' ) {
    $extra_class .= ' better_btn';
}

$extra_class .= ' ' . $link_style;

?>

<style>
    #<?php echo $id; ?>.better_video_lightbox:not(.play_button_with_text) path {
        fill: <?php echo $color; ?>;
    }

    #<?php echo $id; ?>.better_video_lightbox:before {
        border: 5px solid <?php echo $color; ?>;
    }

    #<?php echo $id; ?>.better_video_lightbox.play_button_with_text span.play:before,
    #<?php echo $id; ?>.better_video_lightbox.play_button_with_text span.play:after {
        border-color: <?php echo $color; ?>;
    }

    #<?php echo $id; ?>.better_video_lightbox.play_button_with_text span.play > .inner-wrap:before {
        background-color: <?php echo $color; ?>;
    }

    .better-video-box:not([data-hover="zoom_button"]) #<?php echo $id; ?>.better_video_lightbox {
        background-color: <?php echo $color; ?>;
    }

    body .better-video-box[data-hover="zoom_button"] #<?php echo $id; ?>.better_video_lightbox:after {
        background-color: <?php echo $color; ?>;
    }

    #<?php echo $id; ?>.better_video_lightbox.better-button {
        border-radius: <?php echo ( empty( $border_radius ) || $border_radius == 'none' ) ? '0px' : $border_radius; ?>;
        background-color: <?php echo $color; ?>;
    }
</style>

<?php

if ( $link_style == 'play_button_2' ) {

    $image = null;

    if ( ! empty( $preview_image ) ) {
        $video_thumbnail = BetterCore()->get_image_src( $preview_image );
        $image = '<img src="' . $video_thumbnail[0] . '" alt="" />';
    }

    echo '<div class="better-video-box" data-color="' . strtolower( $play_button_color ) . '" data-play-button-size="' . $play_button_size . '" data-border-radius="' . $border_radius . '" data-hover="' . $hover_effect . '" data-shadow="' . $box_shadow . '"><div class="inner-wrap"><a href="' . $video_url . '" class="full-link magnific-popup"></a>' . $image;
}

$pbwt = ( $link_style == 'play_button_with_text' ) ? '<span class="link-text"><h6>' . $link_text . '</h6></span>' : null;

echo '<a ' . $id_attr . ' href="' . esc_url( $video_url ) . '" ' . $extra_attrs . ' data-color="' . strtolower($color) . '" class="large better_video_lightbox magnific-popup' . esc_attr( $extra_class ) . ( !empty( $class ) ? ' ' . esc_attr( $class ) : '' ) . '">' . $the_link_text . $pbwt . '</a>';

if ( $link_style == 'play_button_2' ) {
    echo '</div></div>';
}

