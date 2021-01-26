<?php

wp_enqueue_script( 'better.icon' );

$better_icon = new BetterSC_Icon();

$size = intval( $size );
// $size -= 1;
if( $size < 0 ) {
     $size = 0;
}
$output = "";

$id = ( !empty ( $id ) ) ? ' id="' . esc_attr( $id ) . '"' : '';

if ( !empty( $rotate ) ) {
  $rotate = " icon-rotate-" . $rotate;
}
if ( !empty( $flip ) ) {
  $flip = " icon-flip-" . $flip;
}

if ( $extra_class != '' ) {
  $class = ( !empty ( $class ) ) ? $class . ' ' . $extra_class : $extra_class;
}

$color_style = "";
$stack_color_style = "";

if ( !empty( $color ) ) {
  $color_style = "fill: {$color};";
}

if ( !empty( $stack_type ) ) {

  if ( !empty( $stack_color ) ) {
    $stack_color_style = "fill: ${stack_color};";
  }

  $svg_icon_path = $better_icon->get_svg_path( $stack_source, $stack_type );

  if ( ! file_exists( $svg_icon_path ) ) {
    $stack_source = 'font-awesome';
    $stack_type = 'ban';
  }

  $stack_key = str_replace( ' ', '_', $stack_source . '-' . $stack_type );

  if ( ! isset( BetterCore()->dynamic_svg[ $stack_key ] ) ) {
    $svg = $better_icon->get_svg( $stack_source, $stack_type );

    BetterCore()->add_dynamic_svg( $stack_key, $svg );
  }
}

$icon_path = $better_icon->get_svg_path( $source, $type );

if ( empty( $icon_path ) ) {
  $source = 'font-awesome';
  $type = 'remove-sign';
}

$key = str_replace( ' ', '_', $source . '-' . $type );

if ( !isset( BetterCore()->dynamic_svg[ $key ] ) ) {
  $svg = $better_icon->get_svg( $source, $type );

  BetterCore()->add_dynamic_svg( $key, $svg );
}

$dimensions = '';
$stack_dimensions = '';

if ( is_numeric( $width ) ) $width .= 'px';
if ( is_numeric( $height ) ) $height .= 'px';

$numeric_width = str_ireplace( 'px', '', $width );
$numeric_height = str_ireplace( 'px', '', $height );

if ( !empty( $width ) && !empty( $height ) ) {
  $dimensions = ' width:' . $width . ';height:' . $height . ';';
  $stack_dimensions = ' width:' . $numeric_width * 2 . 'px;height:' . $numeric_height * 2 . 'px;';
} else if ( !empty( $width ) ) {
  $dimensions = ' width:' . $width . ';height:' . $width . ';';
  $stack_dimensions = ' width:' . $numeric_width * 2 . 'px;height:' . $numeric_width * 2 . 'px;';
} else if ( !empty( $height ) ) {
  $dimensions = ' width:' . $height . ';height:' . $height . ';';
  $stack_dimensions = ' width:' . $numeric_height * 2 . 'px;height:' . $numeric_height * 2 . 'px;';
}

$style = $color_style . $dimensions;
$stack_style = $stack_color_style . $stack_dimensions;
$hover_title = '';

if ( !empty( $title ) ) {
  $hover_title = ' title="' . $title . '"';
}


if ( !empty( $align ) && $align !== 'none' ) {
	?>
	<style>
		.bb-icon.alignleft > * {
			float: left;
		}
		.bb-icon.alignright > * {
			float: right;
		}
		.bb-icon.aligncenter > * {
			clear: both;
			display: block;
			margin-left: auto;
			margin-right: auto;
			text-align: center;
		}
	</style>
	<?php
}

$align = $align ? $align : 'none';

$output .=
'<span class="bb-icon align'.$align.'"><i' . strtolower( $id ) . $hover_title . ' class="better icon icon-' . $size . 'x' . $rotate . $flip . ( !empty( $class ) ? ' ' . esc_attr( $class ) : '' ) . ( $spin ? " icon-spin" : "" ) . ( isset( $stack_key ) ? ' icon-stack' : '' ) . '"' . ( isset( $stack_key ) ? ' style="'.$stack_dimensions.'"' : ' style="'.$dimensions.'"' ) . '>' .
  '<svg id="better-icon">' .
    ( isset( $stack_key ) ? '<use xlink:href="#' . esc_attr( $stack_key ) . '" class="stack-base' . ( !empty( $stack_color_style ) ? ' filled' : '' ) . '"' . ( !empty( $stack_style ) ? ' style="' . $stack_style . '"'  : '' ) . '></use>' : '' ) .
    '<use xlink:href="#' . esc_attr( $key ) . '" class="' . ( isset( $stack_key ) ? ' stack-child' : '' ) . ( !empty( $color_style ) ? ' filled' : '' ) . '"' . ( !empty( $style ) ? ' style="' . $style . '"' : '' )  . $dimensions .  '>' .
    '</use>' .
  '</svg>' .
'</i></span>';

echo $output;

?>

<script type="text/javascript">
    jQuery(function($) {
        'use strict';
        $(document).ready(function() {
            //set the use xlink:href to itself to fix a Safari bug
            $('use').each(function() {
                $(this).attr('xlink:href', $(this).attr('xlink:href'));
            });

            $('use.stack-base').each(function() {
                var $child = $(this).next();
                var parentWidth = $(this).parent().width();
                var parentHeight = $(this).parent().height();

                $child.attr('width', parentWidth * 0.5);
                $child.attr('height', parentHeight * 0.5);

                var childWidth = parentWidth * 0.5;
                var childHeight = parentHeight * 0.5;

                $child.attr('x', parentWidth / 2 - childWidth / 2);
                $child.attr('y', parentHeight / 2 - childHeight / 2);

                $(this).parent().css('visibility', 'visible');
            });

            $('.better.icon > svg > use:not(.filled)').each(function() {
                var $container = $(this).parent().parent();

                $(this).css('fill', $container.css('color'));

                $container.parent('a').hover(function() {
                    $(this).find('use').css('fill', $(this).css('color'));
                }, function() {
                    $(this).find('use').css('fill', $(this).css('color'));
                });
            });
        });
    });
</script>
