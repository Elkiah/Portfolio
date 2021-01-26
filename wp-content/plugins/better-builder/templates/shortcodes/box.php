<?php

// $lazy_load = false;
//
// if ( $lazy_load ) {
//   wp_enqueue_script( 'lazyloadxt' );
//   wp_enqueue_script( 'better.lazyload' );
// }

if ( isset( $id ) ) {
  $content_section_id = $id;
} else {
  $content_section_id = "content-section-" . rand();
}

$border_style = null;
$border_top_style = null;
$border_right_style = null;
$border_bottom_style = null;
$border_left_style = null;
$navigation_attributes = '';

if ( isset( $border ) ) $border_style = "border: " . $border . ' !important;';
if ( isset( $border_top ) ) $border_top_style = "border-top: " . $border_top . ' !important;';
if ( isset( $border_right ) ) $border_right_style = "border-right: " . $border_right . ' !important;';
if ( isset( $border_bottom ) ) $border_bottom_style = "border-bottom: " . $border_bottom . ' !important;';
if ( isset( $border_left ) ) $border_left_style = "border-left: " . $border_left . ' !important;';

//handle case where border_top and border_bottom were only used to set width
$border = $border_style . $border_top_style . $border_right_style . $border_bottom_style . $border_left_style;

if ( !empty ( $border_radius ) ) {
  $border .= 'border-radius:' . $border_radius . ';';
}

// if ( $show_advance_arrow && is_numeric( $padding_bottom ) ) {
//   $padding_bottom += 30; //height of the arrow
// }

if ( is_numeric( $margin_top ) && $margin_top != 0 ) $margin_top .= "px";
if ( is_numeric( $margin_bottom ) && $margin_bottom != 0 ) $margin_bottom .= "px";
if ( is_numeric( $margin_left ) && $margin_left != 0 ) $margin_left .= "px";
if ( is_numeric( $margin_right ) && $margin_right != 0 ) $margin_right .= "px";

if ( is_numeric( $padding_top ) && $padding_top != 0 ) $padding_top .= "px";
if ( is_numeric( $padding_bottom ) && $padding_bottom != 0 ) $padding_bottom .= "px";
if ( is_numeric( $padding_left ) && $padding_left != 0 ) $padding_left .= "px";
if ( is_numeric( $padding_right ) && $padding_right != 0 ) $padding_right .= "px";

wp_enqueue_script( 'better.contentsection' );

// if ($show_navigation) {
//   wp_enqueue_script( 'jquery.appear' );
//   $navigation_attributes  = ' title="' . esc_attr( $title ) . '" data-navigation="true"';
// }

//$background_color = better_get_plugin_color( $background_color );

if ( isset( $background_color ) ) $background_color = 'background-color: ' . $background_color . ';';

if ( is_numeric( $image ) ) {
  $imageid = $image;
} else if ( empty( $image ) && $background_type == 'image' ) {
  $postid = get_the_id();
  $ids = array();

  if ( $postid ) {
    $ids = get_post_thumbnail_id();
    if ( is_array( $ids ) && count( $ids ) > 0 ) {
      $imageid = $ids[0];
    }
  }
} else if ( !empty( $image ) ) {
  $imageurl = $image;
}

if ( !empty( $imageid ) ) {
  $photo_url = wp_get_attachment_image_url( $imageid, $imagesize );
} else if ( !empty( $imageurl ) ) {
  $photo_url = $imageurl;
}

if ( is_numeric( $poster ) ) {
  $poster_id = $poster;
} else if ( !empty( $poster ) ) {
  $poster_url = $poster;
}

if ( !empty( $poster_id ) ) {
  $poster_url = wp_get_attachment_image_url( $poster_id, 'large1600' );
} else if ( !empty( $poster_url ) ) {
  $poster_url = $poster_url;
}

if ( is_numeric( $mp4 ) ) {
  $mp4_id = $mp4;
} else if ( !empty( $mp4 ) ) {
  $mp4_url = $mp4;
}

if ( !empty( $mp4_id ) ) {
  $mp4_url = wp_get_attachment_url( $mp4_id );
} else if ( !empty( $mp4_url ) ) {
  $mp4_url = $mp4_url;
}

if ( is_numeric( $ogv ) ) {
  $ogv_id = $ogv;
} else if ( !empty( $ogv ) ) {
  $ogv_url = $ogv;
}

if ( !empty( $ogv_id ) ) {
  $ogv_url = wp_get_attachment_url( $ogv_id );
} else if ( !empty( $ogv_url ) ) {
  $ogv_url = $ogv_url;
}

if ( is_numeric( $webm ) ) {
  $webm_id = $webm;
} else if ( !empty( $webm ) ) {
  $webm_url = $webm;
}

if ( !empty( $webm_id ) ) {
  $webm_url = wp_get_attachment_url( $webm_id );
} else if ( !empty( $webm_url ) ) {
  $webm_url = $webm_url;
}

if ( is_numeric( $overlay_image ) ) {
  $photo = wp_get_attachment_image_src( $overlay_image, 'full' );
  $overlay_image = "url(" . $photo[0] . ")";
} else if ( !empty( $overlay_image ) ) {
  $overlay_image = "url(" . $overlay_image . ")";
} else {
  $overlay_image = '';
  $overlay_bg_image = '';
}

if ( $overlay_gradient != '' ) {
  $overlay_location = '';
  $overlay_location_standard = '';
  $overlay_start_percent = '';
  $overlay_end_percent = '';

  if ( isset( $overlay_gradient_start_color ) ) {
    $overlaystartcolor = $overlay_gradient_start_color;
  } else {
    $overlaystartcolor = '#000000';
  }

  if ( isset( $overlay_gradient_end_color ) ) {
    $overlayendcolor = $overlay_gradient_end_color;
  } else {
    $overlayendcolor = '#000000';
  }

  if ( !isset( $overlay_gradient_start_opacity ) && !is_numeric( $overlay_gradient_start_opacity ) ) {
    $overlay_gradient_start_opacity = '0';
  }

  if ( !isset( $overlay_gradient_end_opacity ) && !is_numeric( $overlay_gradient_end_opacity ) ) {
    $overlay_gradient_end_opacity = '80';
  }

  if ( isset( $overlay_gradient_start_percent ) && is_numeric( $overlay_gradient_start_percent ) ) {
    $overlay_start_percent = ' ' . $overlay_gradient_start_percent . '%';
  }

  if ( isset( $overlay_gradient_end_percent ) && is_numeric( $overlay_gradient_end_percent ) ) {
    $overlay_end_percent = ' ' . $overlay_gradient_end_percent . '%';
  }

  if ( $overlay_gradient == 'radial' ) {
    if ( !isset( $overlay_start_percent ) ) {
      $overlay_start_percent = ' 20%';
    }

    if ( !isset( $overlay_end_percent ) ) {
      $overlay_end_percent = ' 80%';
    }
  } else {
    if ( $overlay_gradient_direction != 'top' ) {
      $overlay_direction = str_replace( '_', ' ', $overlay_gradient_direction );
      $overlay_location = $overlay_direction . ', ';
      $overlay_location_standard = 'to ' . $overlay_direction . ', ';

      if ( $overlay_gradient_direction == 'bottom' ) {
        $overlay_location_standard = 'to top, ';
      }
    }

    if ( !isset( $overlay_start_percent ) ) {
      $overlay_start_percent = ' 10%';
    }

    if ( !isset( $overlay_end_percent ) ) {
      $overlay_end_percent = ' 0%';
    }
  }

  if ( $overlay_image != '' ) {
    $overlay_bg_image = 'background-image: ' . $overlay_image . ', -webkit-gradient(' . $overlay_gradient . ', left top, left bottom, from(' . better_get_rgb_color( $overlaystartcolor, $overlay_gradient_start_opacity ) . '), to(' . better_get_rgb_color( $overlayendcolor, $overlay_gradient_end_opacity ) . '));';
    $overlay_bg_image .= 'background-image: ' . $overlay_image . ', -webkit-' . $overlay_gradient . '-gradient(' . $overlay_location . better_get_rgb_color( $overlaystartcolor, $overlay_gradient_start_opacity ) . $overlay_start_percent . ', ' . better_get_rgb_color( $overlayendcolor, $overlay_gradient_end_opacity ) . $overlay_end_percent . ');';
    $overlay_bg_image .= 'background-image: ' . $overlay_image . ', -moz-' . $overlay_gradient . '-gradient(' . $overlay_location . better_get_rgb_color( $overlaystartcolor, $overlay_gradient_start_opacity ) . $overlay_start_percent . ', ' . better_get_rgb_color( $overlayendcolor, $overlay_gradient_end_opacity ) . $overlay_end_percent . ');';
    $overlay_bg_image .= 'background-image: ' . $overlay_image . ', -ms-' . $overlay_gradient . '-gradient(' . $overlay_location . better_get_rgb_color( $overlaystartcolor, $overlay_gradient_start_opacity ) . $overlay_start_percent . ', ' . better_get_rgb_color( $overlayendcolor, $overlay_gradient_end_opacity ) . $overlay_end_percent . ');';
    $overlay_bg_image .= 'background-image: ' . $overlay_image . ', -o-' . $overlay_gradient . '-gradient(' . $overlay_location . better_get_rgb_color( $overlaystartcolor, $overlay_gradient_start_opacity ) . $overlay_start_percent . ', ' . better_get_rgb_color( $overlayendcolor, $overlay_gradient_end_opacity ) . $overlay_end_percent . ');';
    $overlay_bg_image .= 'background-image: ' . $overlay_image . ', ' . $overlay_gradient . '-gradient(' . $overlay_location_standard . better_get_rgb_color( $overlaystartcolor, $overlay_gradient_start_opacity ) . $overlay_start_percent . ', ' . better_get_rgb_color( $overlayendcolor, $overlay_gradient_end_opacity ) . $overlay_end_percent . ');';
  } else {
    $overlay_bg_image = 'background-image: -webkit-gradient(' . $overlay_gradient . ', left top, left bottom, from(' . better_get_rgb_color( $overlaystartcolor, $overlay_gradient_start_opacity ) . '), to(' . better_get_rgb_color( $overlayendcolor, $overlay_gradient_end_opacity ) . '));';
    $overlay_bg_image .= 'background-image: -webkit-' . $overlay_gradient . '-gradient(' . $overlay_location . better_get_rgb_color( $overlaystartcolor, $overlay_gradient_start_opacity ) . $overlay_start_percent . ', ' . better_get_rgb_color( $overlayendcolor, $overlay_gradient_end_opacity ) . $overlay_end_percent . ');';
    $overlay_bg_image .= 'background-image: -moz-' . $overlay_gradient . '-gradient(' . $overlay_location . better_get_rgb_color( $overlaystartcolor, $overlay_gradient_start_opacity ) . $overlay_start_percent . ', ' . better_get_rgb_color( $overlayendcolor, $overlay_gradient_end_opacity ) . $overlay_end_percent . ');';
    $overlay_bg_image .= 'background-image: -ms-' . $overlay_gradient . '-gradient(' . $overlay_location . better_get_rgb_color( $overlaystartcolor, $overlay_gradient_start_opacity ) . $overlay_start_percent . ', ' . better_get_rgb_color( $overlayendcolor, $overlay_gradient_end_opacity ) . $overlay_end_percent . ');';
    $overlay_bg_image .= 'background-image: -o-' . $overlay_gradient . '-gradient(' . $overlay_location . better_get_rgb_color( $overlaystartcolor, $overlay_gradient_start_opacity ) . $overlay_start_percent . ', ' . better_get_rgb_color( $overlayendcolor, $overlay_gradient_end_opacity ) . $overlay_end_percent . ');';
    $overlay_bg_image .= 'background-image: ' . $overlay_gradient . '-gradient(' . $overlay_location_standard . better_get_rgb_color( $overlaystartcolor, $overlay_gradient_start_opacity ) . $overlay_start_percent . ', ' . better_get_rgb_color( $overlayendcolor, $overlay_gradient_end_opacity ) . $overlay_end_percent . ');';
  }
} else {
  if ( $overlay_image != '' ) {
    $overlay_bg_image = 'background-image: ' . $overlay_image . ';';
  }
}

if ( isset( $overlay_color ) ) {
  $overlaycolor = $overlay_color;
}

if ( isset( $overlay_opacity ) && isset( $overlaycolor) && strlen( $overlaycolor ) > 0 ) {
  $overlay_style = "background-color: " . better_get_rgb_color( $overlaycolor, $overlay_opacity ) . ";";
} else if ( isset( $overlay_opacity) && $overlay_opacity != "100" ) {
  $overlay_style = ' opacity: 0.' . $overlay_opacity . ';';
} else {
  $overlay_style = '';
}

$background_image = "";
$parallax_image = "";

$output = "";
$bgvideo = "";
$advance = "";
$advance_target = '';
$extra_class = "";
$advance_arrow = '';
$data_attributes = '';
$volume_button = '';
$play_button = '';
$restart_button = '';
$video_buttons = '';
$random = rand();
$button_style = '';
$extra_css = '';
$horizontal = better_coalesce( $image_horizontal_position, '0px' );

if ( isset( $photo_url ) ) {
  $background_image_src = $photo_url;

  if ( $imagemode == 'parallax' ) {
    wp_enqueue_script( "skrollr" );
    $start_location = -160 * ( 1 / $speed );
    $end_location = 80 * ( 1 / $speed );
    $extra_class = "parallax";
    $parallax_image = " data-anchor-target='#" . $content_section_id . "' data-bottom-top='background-position: " . $horizontal . " " . $end_location . "px;' data--1000-top='background-position: " . $horizontal . " " . $start_location . "px;'";
    $background_image .= " background-size: cover; background-position: 50% 50%; background-attachment: fixed; ";
    $speed = ' data-speed="' . $speed . '"';

    BetterCore()->add_dynamic_css( 'better_parallax_bkg', "
      @media only screen and (max-width: 767px) {
        section.better.parallax {
            background-size: cover;
            background-attachment: scroll;
        }
      }
    ");
  } else if ( $imagemode == 'repeat-x' ) {
    $background_image .= " background-repeat: repeat-x; background-size: auto auto; ";
  } else if ( $imagemode == 'repeat-y' ) {
    $background_image .= " background-repeat: repeat-y; background-size: auto auto; ";
  } else if ( $imagemode == 'repeat' ) {
    $background_image .= " background-repeat: repeat; background-size: auto auto; ";
  } else if ( $imagemode == 'full' ) {
    $background_image .= " background-size: cover; background-position: " . $background_image_position . "; ";
  } else if ( $imagemode == 'fixed' ) {
    $extra_class = 'fixed';
    $background_image .= " background-size: cover; background-position: 50% 50%; background-attachment: fixed; ";
  } else if ( $imagemode == 'zoom-in' ) {
    $background_image .= " background-size: cover; background-position: " . $background_image_position . "; ";
    $extra_class = 'zoom-in zoomed-out';
    // $lazy_load = false;
  }

 $background_image .= ' background-image: url(' . $background_image_src . '); ';

  // else  if ( ! empty( $lazy_path ) && file_exists($lazy_path) ) {
  //   WP_Filesystem();
  //   global $wp_filesystem;
  //
  //   $background_image .= ' background-image: url(data:image/jpeg;base64,' . base64_encode( $wp_filesystem->get_contents( $lazy_path ) )  . '); ';
  // }
} else if ( isset( $poster_url ) ) {
  if ( !empty( $video_speed ) && $video_speed != 0 ) {
    //This allows for a parallax video background effect using html5 video
    wp_enqueue_script( "backgroundvideo" );

    $bg_rand = rand();

    $backgroundVideo = '<div id="video-wrap-' . $bg_rand . '"><video id="bgvideo_' . $bg_rand . '" preload="metadata" poster="' . $poster_url . '" autoplay loop playsinline ' . ( $mute_volume == 1 ? 'muted="true"' : '' ) . ' >';

    if ( !empty( $mp4_url ) ) {
      $backgroundVideo .= '<source src="' . $mp4_url . '" type="video/mp4">';
    }

    if ( !empty( $ogv_url ) ) {
      $backgroundVideo .= '<source src="' . $ogv_url . '" type="video/ogv">';
    }

    if ( !empty( $webm_url ) ) {
      $backgroundVideo .= '<source src="' . $webm_url . '" type="video/webm">';
    }

    $backgroundVideo .= '</video>';

    $extra_css = "position:relative; overflow:hidden; z-index: 10;";

    $bgvideo = "<script>
    jQuery(function($) {
      $(window).load(function() {
          $('#bgvideo_" . $bg_rand . "').backgroundVideo({
              videoWrap: $('#video-wrap-" . $bg_rand . "'),
              outerWrap: $('#" . $content_section_id . "'),
              preventContextMenu: true,
              parallaxOptions: {
                  effect: " . $video_speed . "
              }
          });

          " . ( $mute_volume == 1 ? "$('#" . $content_section_id . "').find('video').prop('muted', true);" : "" ) . "
      });
    });
    </script>";

  } else {
    //This is the original video background using html5 video
    wp_enqueue_script( "videoBG" );

    $extra_class .= ' video-background';

    $bgvideo = "<script>
    jQuery(function($) {
      $(window).load(function() {
          $('#" . $content_section_id . "').videoBG({
          scale:true,
          zIndex:0,
          position: 'static', " .
        ( isset( $mp4_url ) ? "mp4: '" . $mp4_url . "'," : "" ) .
        ( isset( $ogv_url ) ? "ogv: '" . $ogv_url . "'," : "" ) .
        ( isset( $webm_url ) ? "webm: '" . $webm_url . "'," : "" ) .
        "poster: '" . $poster_url . "'
        });

        " . ( $mute_volume == 1 ? "$('#" . $content_section_id . "').find('video').prop('muted', true);" : "" ) . "
      });
    });
    </script>";
  }
}

if ( isset( $video ) ) {
  wp_enqueue_script( "okvideo" );
  $extra_class .= ' video-background';
  $data_attributes .= ' data-video-id="' . $video . '"';
  $data_attributes .= ' data-video-volume="' . $volume . '"';
  $data_attributes .= ' data-video-autoplay="' . $autoplay . '"';
  $num_buttons = 0;

  if ( $volumebutton ) {
    $volume_button = '<a href="#"' . ( isset( $button_color ) ? ' style="background-color: ' . $button_color . '"' : '' ) . ' id="' . $content_section_id . '-volume" class="better btn btn-default better-button ' . $content_section_id . '-volume">' . do_shortcode( '[better_icon type="' . ( $volume > 0 ? 'volume-up' : 'volume-off' ) . '" color="' . $button_font_color . '"]' ) . '</a>';
    $num_buttons++;
  }

  if ( $playbutton ) {
    $play_button = '<a href="#"' . ( isset( $button_color ) ? ' style="background-color: ' . $button_color . '"' : '' ) . ' id="' . $content_section_id . '-pause" class="better btn btn-default better-button ' . $content_section_id . '-pause">' . do_shortcode( '[better_icon type="' . ( $autoplay == 0 ? 'play' : 'pause' ) . '" color="' . $button_font_color . '"]' ) . '</a>';
    $num_buttons++;
  }

  if ( $restartbutton ) {
    $restart_button = '<a href="#"' . ( isset( $button_color ) ? ' style="background-color: ' . $button_color . '"' : '' ) . ' id="' . $content_section_id . '-refresh" class="better btn btn-default better-button ' . $content_section_id . '-refresh">' . do_shortcode( '[better_icon type="refresh" color="' . $button_font_color . '"]' ) . '</a>';
    $num_buttons++;
  }

  if ( $volumebutton || $playbutton || $restartbutton ) {
    $offset = $num_buttons * 28;

    if ( $button_position == 'topright' ) {
      $button_style = ' style="position:absolute; top:10px; right:10px;"';
    } elseif ( $button_position == 'bottomright' ) {
      $button_style = ' style="position:absolute; bottom:10px; right:10px;"';
    } elseif ($button_position == 'topcenter' ) {
      $button_style = ' style="left:50%; left: -webkit-calc(50% - ' . $offset . 'px); left: -moz-calc(50% - ' . $offset . 'px); left: calc(50% - ' . $offset . 'px); top:10px; position:absolute;"';
    } elseif ( $button_position == 'bottomcenter' ) {
      $button_style = ' style="left:50%; left: -webkit-calc(50% - ' . $offset . 'px); left: -moz-calc(50% - ' . $offset . 'px); left: calc(50% - ' . $offset . 'px); bottom:10px; position:absolute;"';
    } elseif ( $button_position == 'bottomleft' ) {
      $button_style = ' style="position:absolute; bottom:10px; left:10px;"';
    } else {
      $button_style = ' style="position:absolute; top:10px; left:10px;"';
    }

    $video_buttons = '<div style="display:none;"><svg width="2048" height="2048" viewBox="0 0 2048 2048" xmlns="http://www.w3.org/2000/svg">
        <symbol viewBox="0 0 2048 2048" id="better-font-awesome-play">
          <path d="M1704 1055l-1328 738q-23 13-39.5 3t-16.5-36v-1472q0-26 16.5-36t39.5 3l1328 738q23 13 23 31t-23 31z"/>
        </symbol>
        <symbol viewBox="0 0 2048 2048" id="better-font-awesome-pause">
          <path d="M1792 320v1408q0 26-19 45t-45 19h-512q-26 0-45-19t-19-45v-1408q0-26 19-45t45-19h512q26 0 45 19t19 45zm-896 0v1408q0 26-19 45t-45 19h-512q-26 0-45-19t-19-45v-1408q0-26 19-45t45-19h512q26 0 45 19t19 45z"/>
        </symbol>
        <symbol viewBox="0 0 2048 2048" id="better-font-awesome-volume-off">
          <path d="M1408 480v1088q0 26-19 45t-45 19-45-19l-333-333h-262q-26 0-45-19t-19-45v-384q0-26 19-45t45-19h262l333-333q19-19 45-19t45 19 19 45z"/>
        </symbol>
        <symbol viewBox="0 0 2048 2048" id="better-font-awesome-volume-up">
          <path d="M960 480v1088q0 26-19 45t-45 19-45-19l-333-333h-262q-26 0-45-19t-19-45v-384q0-26 19-45t45-19h262l333-333q19-19 45-19t45 19 19 45zm384 544q0 76-42.5 141.5t-112.5 93.5q-10 5-25 5-26 0-45-18.5t-19-45.5q0-21 12-35.5t29-25 34-23 29-35.5 12-57-12-57-29-35.5-34-23-29-25-12-35.5q0-27 19-45.5t45-18.5q15 0 25 5 70 27 112.5 93t42.5 142zm256 0q0 153-85 282.5t-225 188.5q-13 5-25 5-27 0-46-19t-19-45q0-39 39-59 56-29 76-44 74-54 115.5-135.5t41.5-173.5-41.5-173.5-115.5-135.5q-20-15-76-44-39-20-39-59 0-26 19-45t45-19q13 0 26 5 140 59 225 188.5t85 282.5zm256 0q0 230-127 422.5t-338 283.5q-13 5-26 5-26 0-45-19t-19-45q0-36 39-59 7-4 22.5-10.5t22.5-10.5q46-25 82-51 123-91 192-227t69-289-69-289-192-227q-36-26-82-51-7-4-22.5-10.5t-22.5-10.5q-39-23-39-59 0-26 19-45t45-19q13 0 26 5 211 91 338 283.5t127 422.5z"/>
        </symbol>
      </svg></div>';

    $video_buttons .= '<div id="' . $content_section_id . '-buttons"' . $button_style . ' class="videoButtons ' . $button_position . '">' . $restart_button . ' ' . $play_button . ' ' . $volume_button . '</div>';
  }
}

$vertical_align_style = '';

if ( !empty($vertical_align) ) {
  $vertical_align_style = ' style="position: absolute; top: 50%; transform: translateY(-50%);"';
}

if ( $size == 'partial' ) {
  $extra_class .= ' partial';
  $output .= '<div class="better container' . ( isset( $box_class ) ? " $box_class" : '' ) . '"' . $vertical_align_style . '>';
} else {
  if ( $breakout ) {
    $extra_class .= ' breakout';
  }

  if ( $full_height ) {
    $extra_class .= ' full-height';
  }
}

// if ( $show_advance ) {
//   if ( !isset( $advance_target_id ) ) {
//     $advance_target_id = "content-section-advance-" . rand();
//     $advance_target = '<div id="' . $advance_target_id . '" style="height: 0px;"></div>';
//   }
//
//   $advance_color = better_get_plugin_color( $advance_color );
//
//   if (isset($advance_hover_color)) {
//     $advance_hover_color = better_get_plugin_color( $advance_hover_color );
//   } else {
//     $advance_hover_color = '';
//   }
//
//   $advance = '<div class="advance-button">' .
//     better_run_shortcode( 'better_button', array(
//         'size' => $advance_size,
//         'color' => $advance_color,
//         'hover_color' => $advance_hover_color,
//         'link' => '#' . $advance_target_id ,
//         'icon_source' => $advance_icon_source,
//         'icon' => $advance_icon,
//         'icon_position' => $advance_icon_position,
//         'scroll_speed' => $scroll_speed,
//         'scroll_offset' => $scroll_offset
//       ), $advance_text ) .
//     '</div>';
// }

// if ( $show_advance_arrow ) {
//   $advance_arrow_background_color = better_get_plugin_color( $advance_arrow_background_color );
//
//   $advance_position_left = '';
//   $advance_position_right = '';
//
//   if ( $advance_arrow_position != '50' ) {
//     $advance_position_left = 'width: ' . $advance_arrow_position . '.25%';
//     $advance_position_right = 'width: ' . ( 100 - $advance_arrow_position ) . '.25%';
//   }
//
//   $advance_arrow = '<div class="advance-arrow">
//     <div class="advance-arrow-left" style="' . ( !empty( $advance_arrow_background_color ) ?  'border-bottom-color: ' . $advance_arrow_background_color . ';' : '' ) . $advance_position_left . '"></div>
//     <div class="advance-arrow-right" style="' . ( !empty( $advance_arrow_background_color ) ?  'border-bottom-color: ' . $advance_arrow_background_color . ';' : '' ) . $advance_position_right . '"></div>
//   </div>';
// }

if ( isset( $height_adjustment ) ) {
  $height_adjustment = ' data-height-adjustment="' . $height_adjustment . '"';
}

$output .= '<section id="' . $content_section_id . '"' . $navigation_attributes . ( isset( $background_image_src ) && $imagemode !== 'zoom-in' ? ' data-bg="' . $background_image_src . '"' : '' ) . ' class="better content-section ' . $extra_class . ( !empty( $class ) ? ' ' . esc_attr( $class ) : '' ) . '"' . $parallax_image . $height_adjustment . $speed . ' style="' . ( isset( $height ) ? 'height:' . $height . 'px; ' : '' ) . ' ' . $background_color . $border . ' margin-bottom: ' . $margin_bottom . '; margin-top: ' . $margin_top . '; margin-left: ' . $margin_left . '; margin-right: ' . $margin_right . '; ' . ( $imagemode !== 'zoom-in' ? $background_image : '' ) . ' padding-top: ' . $padding_top . '; padding-bottom:' . $padding_bottom . '; padding-left: ' . $padding_left . '; padding-right:' . $padding_right . '; ' . $extra_css . '"' . $data_attributes . '>';

if ( !empty( $top_divider_type ) ) {
  $output .= do_shortcode('[better_divider type="' . $top_divider_type . '" location="top" primary_color="' . $top_divider_primary_color . '" secondary_color="' . $top_divider_secondary_color . '" tertiary_color="' . $top_divider_tertiary_color . '" ]');
}

if ( isset( $poster_url ) && ( !empty( $video_speed ) && $video_speed != 0 ) ) {
  //This section adds some code around the content so that the content sits over the parallax video background
  $backgroundVideo .= '<div style="position:relative; z-index:15;">';

  if ( $size == 'fullboxed' ) {
    $backgroundVideo .= '<div class="better container ' . ( isset( $box_class ) ? " $box_class" : '' ) . '"' . $vertical_align_style . '>';
  }

  $backgroundVideo .= do_shortcode( $content );

  if ( $size == 'fullboxed' ) {
    $backgroundVideo .= '</div>';
  }

  $backgroundVideo .= '</div>'; //close relative position div set in this section
  $backgroundVideo .= '</div>'; //close video-wrap div that was set above.

  $output .= $backgroundVideo;
} else {
  //This is the original code for setting up the content section
  if ( $size == 'fullboxed' ) {
    $output .= '<div class="better container ' . ( isset( $box_class ) ? " $box_class" : '' ) . '"' . $vertical_align_style . '>';
    $output .= $video_buttons;
  } else {
    $output .= $video_buttons;
  }

  $output .= do_shortcode( $content );

  if ( $size == 'fullboxed' ) {
    $output .= '</div>';
  }
}

if ( !empty( $overlay_bg_image ) || !empty( $overlay_style ) ) {
  $output .= '<div class="overlay-background" style="' . $overlay_bg_image . $overlay_style . '"></div>';
}

if ( $imagemode == 'zoom-in' ) {
  $output .= '<div class="zoom-image" ' . ( isset( $background_image_src ) ? ' data-bg="' . $background_image_src . '"' : '' ) . ' style="' . $background_image . '"></div>';
}

$output .= $bgvideo;
$output .= $advance;
$output .= $advance_arrow;

if ( ! empty( $bottom_divider_type ) ) {
  $output .= do_shortcode('[better_divider type="' . $bottom_divider_type . '" location="bottom" primary_color="' . $bottom_divider_primary_color . '" secondary_color="' . $bottom_divider_secondary_color . '" tertiary_color="' . $bottom_divider_tertiary_color . '" ]');
}

$output .= '</section>';

if ( $size == 'partial' ) {
  $output .= '</div>';
}

$output .= $advance_target;

if( $breakout ) {
    $output .= "<script>var $ = jQuery;
        var browserWidth = $(window).width();
        var element = $('#$content_section_id');

        if (!element.data('original_margin_left')) {
            element.data('original_margin_left', element.css('margin-left'));
        } else {
            element.css('margin-left', element.data('original_margin_left'));
        }

        if (!element.data('original_margin_right')) {
            element.data('original_margin_right', element.css('margin-right'));
        } else {
            element.css('margin-right', element.data('original_margin_right'));
        }

        var leftMargin = element.offset().left;
        var rightMargin = browserWidth - (leftMargin + element.outerWidth());

        element.css('margin-left', '-' + leftMargin + 'px')
            .css('margin-right', '-' + rightMargin + 'px');
      </script>";
}

echo do_shortcode( $output );
