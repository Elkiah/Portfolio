/*!
 * Content Section helper JS
 * Copyright (c) 2014 Intense Visions, Inc.
 */
/* global jQuery, skrollr */

// Resize the content-section divs and apply parallax effect

;(function ($) {

  $.fn.intenseParallax = function () {
    var window_width = $(window).width();
    // Parallax Scripts
    return this.each(function (i) {
      var $this = $(this);
      $this.addClass('parallax');

      function updateParallax(initial) {

        var container_height;

        if (window_width < 601) {
          container_height = $this.outerHeight() > 0 ? $this.outerHeight() : $this.find("img").height();
        } else {
          container_height = $this.outerHeight() > 0 ? $this.outerHeight() : 500;
        }

        var $img = $this.find("img").first();
        var img_height = $img.height();
        var parallax_dist = img_height - container_height;
        var bottom = $this.offset().top + container_height;
        var top = $this.offset().top;
        var scrollTop = $(window).scrollTop();
        var windowHeight = window.innerHeight;
        var windowBottom = scrollTop + windowHeight;
        var percentScrolled = (windowBottom - top) / (container_height + windowHeight);
        var parallax = Math.round(parallax_dist * percentScrolled);

        //console.warn(img_height + " - " + container_height + " = " + (img_height - container_height));

        if (initial) {
          $img.css('display', 'block');
        }

        if (bottom > scrollTop && top < scrollTop + windowHeight) {
          $img.css('transform', "translate3D(-50%," + parallax + "px, 0)");
        }
      }

      // Wait for image load
      $this.find("img").one("load", function () {
        updateParallax(true);
      }).each(function () {
        if (this.complete) $(this).trigger("load");
      });

      $(window).scroll(function () {
        window_width = $(window).width();
        updateParallax(false);
      });

      $(window).resize(function () {
        window_width = $(window).width();
        updateParallax(false);
      });
    });
  };
})(jQuery);

function intenseLoadContentSections() {
    'use strict';

    var isMobile = (/Android|iPhone|iPad|iPod|BlackBerry|Windows Phone/i).test(navigator.userAgent || navigator.vendor || window.opera);
    var $ = jQuery;
    var $contentSectionNavigation = $('#content-section-nav');
    var $contentSectionNavList = $('#content-section-nav > ul');
    var showNavigation = false;

    if ($contentSectionNavigation.length === 0) {
        $contentSectionNavigation = $('<nav id="intense-contentsection-nav">');
        $contentSectionNavList = $('<ul rol="navigation"></ul>').appendTo($contentSectionNavigation);

        $('body').on('click', '.nav-link', function() {
            var contentSection = $(this).data('section');

            jQuery("html, body").stop().animate({
                scrollTop: jQuery('#' + contentSection).offset().top
            }, 1000);
        });
    }

    jQuery('.content-section').each(function() {
        var $element = jQuery(this);
        $element.hide();
        var skrollrSection = null;

        if ($element.data('navigation')) {
            var title = $element.attr('title');
            var $navLink = $('<li class="nav-link" role="link" data-section="' + $element.attr('id') + '">' + (title !== '' ? '<span class="label">' + title + '</span>' : '') + '<span class="dot"/></li>');

            showNavigation = true;

            $contentSectionNavList.append($navLink);

            $element.appear(function() {
                $('.nav-link').removeClass('active');
                $navLink.addClass('active');
            },{
                one: false,
                accX: 50,
                accY: -50
            });
        }

        if ($element.hasClass('breakout')) {
            var browserWidth = $(window).width();

            if (!$element.data('original_margin_left')) {
                $element.data('original_margin_left', $element.css('margin-left'));
            } else {
                $element.css('margin-left', $element.data('original_margin_left'));
            }

            if (!$element.data('original_margin_right')) {
                $element.data('original_margin_right', $element.css('margin-right'));
            } else {
                $element.css('margin-right', $element.data('original_margin_right'));
            }

            var leftMargin = $element.offset().left;
            var rightMargin = browserWidth - (leftMargin + $element.outerWidth());

            $element.css('margin-left', '-' + leftMargin + 'px')
                .css('margin-right', '-' + rightMargin + 'px');
        }

        if ($element.hasClass('parallax')) {
            isMobile = true;
            if (!isMobile) {
                skrollrSection = skrollr.init({
                    forceHeight: false,
                    mobileCheck: function() {
                        //hack - forces mobile version to be off
                        return false;
                    }
                });
            } else {
                var bg = $element.data('bg');

                if(bg === undefined || bg === '') {
                    bg = $element.css('background-image');
                }

                bg = bg.replace('url(','').replace(')','').replace(/\"/gi, "");
                $element.find('.parallax').remove();
                $element.addClass('parallax-container').removeClass('parallax').prepend('<div class="parallax"><img src="' + bg + '" /></div>');

                $element.intenseParallax();
            }
        }

        if ($element.hasClass('fixed')) {
            if (isMobile) {
                $element.css('background-position', 'center center');
                $element.css('background-attachment', 'scroll');
            }
        }

        if ($element.hasClass('full-height')) {
            var heightAdjustment = $element.data('height-adjustment');

            if (!heightAdjustment) {
                heightAdjustment = 0;
            }

            $element.css('min-height', (jQuery(window).height() - jQuery('#wpadminbar').outerHeight() - heightAdjustment) + 'px');

            if (skrollrSection) {
                skrollrSection.refresh();
            }
        }

        if ($element.hasClass('zoom-in')) {
            $element.mouseenter(function() {

            });

            $element.mouseleave(function() {
                $element.removeClass('zoomed-in').addClass('zoomed-out');
            });

            $element.mousemove(function() {
                if ( !$element.hasClass('zoomed-in') ) {
                    $element.addClass('zoomed-in').removeClass('zoomed-out');
                }
            });
        }



        if ($element.data('video-id')) {
            if (isMobile) {
                //overlays won't work because they need to be able to
                //press the play button so remove them from mobile
                jQuery('.overlay-background').remove();
            }

            var $contentVideo = $element.okvideo({
                target: $element.attr('id'),
                video: $element.data('video-id'),
                autoplay: $element.data('video-autoplay'),
                disablekeyControl: 0,
                captions: 0,
                loop: !isMobile,
                hd: 1,
                annotations: 0,
                volume: $element.data('video-volume'),
                cover: 1,
                controls: 0,
                onReady: function() {
                    jQuery('#' + $element.attr('id') + '-player-okplayer').css('z-index', '-2');
                }
            });

            var $contentVideoButtons = $('#' + $element.attr('id') + '-buttons');

            //Restart, Play/Pause, and Volume buttons
            //Play/Pause click
            $('a.' + $element.attr('id') + '-pause').on('click', function(e) {
                e.preventDefault();

                jQuery('.okvideo.youtube,.okvideo.vimeo').each(function() {
                    var options = jQuery(this).data('okoptions');
                    var id = $element.attr('id') + '-player';

                    if ( id === options.id ) {
                        var setPlayer = window["okvideoplayer_" + id];

                        if ( jQuery(this).hasClass('youtube')) {
                            if (setPlayer.getPlayerState() === 1) {
                                setPlayer.pauseVideo();
                                $('a.' + $element.attr('id') + '-pause use').attr( 'xlink:href', '#better-font-awesome-play' );
                                $('a.' + $element.attr('id') + '-pause').attr( 'title', 'Play' );
                            } else {
                                setPlayer.playVideo();
                                $('a.' + $element.attr('id') + '-pause use').attr( 'xlink:href', '#better-font-awesome-pause' );
                                $('a.' + $element.attr('id') + '-pause').attr( 'title', 'Pause' );
                            }
                        }

                        if ( jQuery(this).hasClass('vimeo')) {
                            if (setPlayer.paused) {
                                setPlayer.api("play");
                                setPlayer.paused = false;
                                $('a.' + $element.attr('id') + '-pause use').attr( 'xlink:href', '#better-font-awesome-pause' );
                                $('a.' + $element.attr('id') + '-pause').attr( 'title', 'Pause' );
                            } else {
                                setPlayer.api("pause");
                                setPlayer.paused = true;
                                $('a.' + $element.attr('id') + '-pause use').attr( 'xlink:href', '#better-font-awesome-play' );
                                $('a.' + $element.attr('id') + '-pause').attr( 'title', 'Play' );
                            }
                        }


                    }
                });
            });

            //Volume click
            $('a.' + $element.attr('id') + '-volume').on('click', function(e) {
                e.preventDefault();

                jQuery('.okvideo.youtube,.okvideo.vimeo').each(function() {
                    var options = jQuery(this).data('okoptions');
                    var id = $element.attr('id') + '-player';

                    if ( id === options.id ) {
                        var setPlayer = window["okvideoplayer_" + id];

                        if ( jQuery(this).hasClass('youtube')) {
                            if ( setPlayer.getVolume() > 0 ) {
                                setPlayer.setVolume(0);
                                $('a.' + $element.attr('id') + '-volume use').attr( 'xlink:href', '#better-font-awesome-volume-off' );
                                $('a.' + $element.attr('id') + '-volume').attr( 'title', 'Turn Volume On' );
                            } else {
                                setPlayer.setVolume(100);
                                $('a.' + $element.attr('id') + '-volume use').attr( 'xlink:href', '#better-font-awesome-volume-up' );
                                $('a.' + $element.attr('id') + '-volume').attr( 'title', 'Turn Volume Off' );
                            }
                        }

                        if ( jQuery(this).hasClass('vimeo')) {
                            setPlayer.api('getVolume', function (value, player_id) {
                                if ( value > 0 ) {
                                    setPlayer.api('setVolume', 0);
                                    $('a.' + $element.attr('id') + '-volume use').attr( 'xlink:href', '#better-font-awesome-volume-off' );
                                $('a.' + $element.attr('id') + '-volume').attr( 'title', 'Turn Volume On' );
                                } else {
                                    setPlayer.api('setVolume', 1);
                                    $('a.' + $element.attr('id') + '-volume use').attr( 'xlink:href', '#better-font-awesome-volume-up' );
                                $('a.' + $element.attr('id') + '-volume').attr( 'title', 'Turn Volume Off' );
                                }
                            });
                        }
                    }
                });
            });

            //Restart click
            $('a.' + $element.attr('id') + '-refresh').on('click', function(e) {
                e.preventDefault();

                jQuery('.okvideo.youtube,.okvideo.vimeo').each(function() {
                    var options = jQuery(this).data('okoptions');
                    var id = $element.attr('id') + '-player';

                    if ( id === options.id ) {
                        var setPlayer = window["okvideoplayer_" + id];

                        if ( jQuery(this).hasClass('youtube')) {
                            setPlayer.seekTo(1, true);
                        }

                        if ( jQuery(this).hasClass('vimeo')) {
                            setPlayer.api("seekTo", 0);
                        }
                    }
                });
            });
        }
    });

    if (showNavigation) {
        $contentSectionNavigation.appendTo('body');
        $contentSectionNavigation.css('margin-top', $contentSectionNavigation.outerHeight() / -2);
        $contentSectionNavigation.show();
        $contentSectionNavigation.fadeTo(0, 1);
    }
}

(function($) {
    'use stict';

    $(window).load(function() {
        intenseLoadContentSections();

        $(window).resize(function() {
            intenseLoadContentSections();
        });
    });
})(jQuery);
