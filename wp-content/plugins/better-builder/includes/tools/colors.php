<?php
/**
 * Gets a contracting color
 * @param  string $hexcolor the hex color that will be used to find the contrast
 * @return string           the contrasting color (#444 for light colors and #FFF for dark colors)
 */
function better_get_contrast_color( $hexcolor ) {
    $hexcolor = str_replace( "#", "", $hexcolor );

    if ( strlen( $hexcolor ) == 3 ) {
        $r = hexdec( substr( $hexcolor, 0, 1 ).substr( $hexcolor, 0, 1 ) );
        $g = hexdec( substr( $hexcolor, 1, 1 ).substr( $hexcolor, 1, 1 ) );
        $b = hexdec( substr( $hexcolor, 2, 1 ).substr( $hexcolor, 2, 1 ) );
    } else {
        $r = hexdec( substr( $hexcolor, 0, 2 ) );
        $g = hexdec( substr( $hexcolor, 2, 2 ) );
        $b = hexdec( substr( $hexcolor, 4, 2 ) );
    }

    $yiq = ( ( $r*299 ) + ( $g*587 ) + ( $b*114 ) ) / 1000; //get luminosity grayscale value
    return ( $yiq >= 180 ) ? '#444' : '#fff';
}

/**
 * Gets a random color
 * @return string a random hex color
 */
function better_get_random_color( ) {
    $rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
    $color = '#'.
    $rand[rand(0, 12)].
    $rand[rand(10,12)].
    $rand[rand(0, 12)].
    $rand[rand(10,12)].
    $rand[rand(0, 12)].
    $rand[rand(10,12)];

    return $color;
}

/**
 * Adjusts the color brightness by the given number of steps
 * @param  string $hex   Color to adjust
 * @param  int $steps number of steps to adjust the color by
 * @return string        the adjusted hex color
 */
function better_adjust_color_brightness( $hex, $steps ) {
  // Steps should be between -255 and 255. Negative = darker, positive = lighter
  $steps = max( -255, min( 255, $steps ) );

  if ( stripos($hex, 'rgb') !== false ) {
    $hex = better_get_rgba_hex_color( $hex );
  }

  // Format the hex color string
  $hex = str_replace( '#', '', $hex );
  if ( strlen( $hex ) == 3 ) {
    $hex = str_repeat( substr( $hex, 0, 1 ), 2 ).str_repeat( substr( $hex, 1, 1 ), 2 ).str_repeat( substr( $hex, 2, 1 ), 2 );
  }

  // Get decimal values
  $r = hexdec( substr( $hex, 0, 2 ) );
  $g = hexdec( substr( $hex, 2, 2 ) );
  $b = hexdec( substr( $hex, 4, 2 ) );

  // Adjust number of steps and keep it inside 0 to 255
  $r = max( 0, min( 255, $r + $steps ) );
  $g = max( 0, min( 255, $g + $steps ) );
  $b = max( 0, min( 255, $b + $steps ) );

  $r_hex = str_pad( dechex( $r ), 2, '0', STR_PAD_LEFT );
  $g_hex = str_pad( dechex( $g ), 2, '0', STR_PAD_LEFT );
  $b_hex = str_pad( dechex( $b ), 2, '0', STR_PAD_LEFT );

  return '#'.$r_hex.$g_hex.$b_hex;
}

/**
 * Creates CSS for linear gradients
 * @param  string $start start color
 * @param  string $end   end color
 * @return string        CSS for gradient
 */
function better_linear_gradient_css( $start, $end ) {
  $output = "";
  $output .= "background-image: -webkit-gradient(linear, left top, left bottom, from($start), to($end));";
  $output .= "background-image: -webkit-linear-gradient(top, $start, $end);";
  $output .= "background-image: -moz-linear-gradient(top, $start, $end);";
  $output .= "background-image: -ms-linear-gradient(top, $start, $end);";
  $output .= "background-image: -o-linear-gradient(top, $start, $end);";
  $output .= "background-image: linear-gradient(to bottom, $start, $end);";
  $output .= "filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr=$start, endColorstr=$end)";

  return $output;
}

/**
 * Returns a hex color that matches the web color name
 * @param  string $webcolor web color name
 * @return string           hex representation of color
 */
function better_get_web_color_hex( $webcolor ) {
  if ( false == $colors = get_transient( 'better_color_list' ) ) {
    require 'colors-list.php';

    set_transient( 'better_color_list', $colors );
  }

  return ( isset( $colors[ strtolower( $webcolor ) ] ) ? $colors[ strtolower( $webcolor ) ] : null );
}

/**
 * Get the rgb/rgba representation of a color
 * @param  string $hexcolor the hex value of the color
 * @param  int $opacity  the opacity
 * @return string           rgb/rgba equivalent
 */
function better_get_rgb_color( $hexcolor, $opacity = null ) {
    $returnRGB = '';
    $hex = str_replace( "#", "", $hexcolor );
    $a = 0;

    if ( isset( $opacity ) && $opacity > 1 ) {
        $a = $opacity / 100;
    }

    if ( strlen( $hex ) == 3 ) {
        $r = hexdec( substr( $hex, 0, 1 ).substr( $hex, 0, 1 ) );
        $g = hexdec( substr( $hex, 1, 1 ).substr( $hex, 1, 1 ) );
        $b = hexdec( substr( $hex, 2, 1 ).substr( $hex, 2, 1 ) );
    } else {
        $r = hexdec( substr( $hex, 0, 2 ) );
        $g = hexdec( substr( $hex, 2, 2 ) );
        $b = hexdec( substr( $hex, 4, 2 ) );
    }

    if ( isset( $opacity ) ) {
        $returnRGB = "rgba(" . $r . "," . $g . "," . $b . "," . $a . ")";
    } else {
        $returnRGB = "rgb(" . $r . "," . $g . "," . $b . ")";
    }

    return $returnRGB;
}

/**
 * Gets the hex portion of a rgba color
 * @param  string $rgba rgba color
 * @return string       hex part of rgba color
 */
function better_get_rgba_hex_color( $rgba ) {
    $rgba = str_replace( ")", "", str_replace( "rgba(", "", $rgba ) );
    $rgba = explode(",", $rgba);

    $r = trim( $rgba[0] );
    $g = trim( $rgba[1] );
    $b = trim( $rgba[2] );

    if (is_array($r) && sizeof($r) == 3)
        list($r, $g, $b) = $r;

    $r = intval($r); 
    $g = intval($g);
    $b = intval($b);

    $r = dechex($r<0?0:($r>255?255:$r));
    $g = dechex($g<0?0:($g>255?255:$g));
    $b = dechex($b<0?0:($b>255?255:$b));

    $color = (strlen($r) < 2?'0':'').$r;
    $color .= (strlen($g) < 2?'0':'').$g;
    $color .= (strlen($b) < 2?'0':'').$b;

    return '#'.$color;
}

/**
 * Gets the opacity portion of a rgba color
 * @param  string $rgba rgba color
 * @return string       hex part of rgba color
 */
function better_get_rgba_opacity( $rgba ) {  
    $rgba = str_replace( ")", "", str_replace( "rgba(", "", $rgba ) );
    $rgba = explode(",", $rgba);
    
    $a = trim( $rgba[3] );

    return $a;
}
