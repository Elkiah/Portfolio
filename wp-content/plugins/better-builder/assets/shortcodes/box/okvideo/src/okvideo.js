/*
 * OKVideo by OKFocus v2.3.2
 * http://okfoc.us
 *
 * Copyright 2014, OKFocus
 * Licensed under the MIT license.
 *
 */

var player, OKEvents, options;

(function($) {

  "use strict";

  var BLANK_GIF = "data:image/gif;base64,R0lGODlhAQABAPABAP///wAAACH5BAEKAAAALAAAAAABAAEAAAICRAEAOw%3D%3D";
  $.okvideo = function(options) {

    // if the option var was just a string, turn it into an object
    if (typeof options !== 'object') options = {
      'video': options
    };

    var base = this;

    // kick things off
    base.init = function() {
      base.options = $.extend({}, $.okvideo.options, options);

      // support older versions of okvideo
      if (base.options.video === null) base.options.video = base.options.source;
      if (!base.options.target) base.options.target = $('body');

      base.setOptions();

      var target = base.options.target || $('body');
      var position = target[0] == $('body')[0] ? 'fixed' : 'absolute';
      var vidRatio = 9 / 16;
      var canvasRatio = target.height() / target.width();
      var setHeight = target.height();
      var setWidth = target.width();
      var finalHeight;
      var finalWidth;
      var scale;
      var moveLeft = 0;
      var moveTop = 0;

      target.addClass('okvideo');
      target.addClass(base.options.video.provider);

      if (base.options.cover === 1) {
        if (target[0] == $('body')[0]) {
          canvasRatio = window.innerHeight / window.innerWidth;
          setHeight = window.innerHeight;
          setWidth = window.innerWidth;
        }

        if (canvasRatio > vidRatio) {
          scale = setHeight / 9;
          finalWidth = Math.round(16 * scale, 0);
          finalHeight = setHeight;
          moveLeft = (Math.round((finalWidth - setWidth) / 2, 0) * -1) + 'px';
        } else {
          scale = setWidth / 16;
          finalHeight = Math.round(9 * scale, 0);
          finalWidth = setWidth;
          moveTop = (Math.round((finalHeight - setHeight) / 2, 0) * -1) + 'px';
        }
      }

      target.css({
        position: 'relative'
      });

      var zIndex = base.options.controls === 3 ? -999 : "auto";
      var mask = '<div id="' + base.options.id + '-mask" style="position:' + position + ';left:0;top:0;overflow:hidden;z-index:-998;height:100%;width:100%;"></div>';

      if (OKEvents.utils.isMobile()) {
        target.append('<div id="' + base.options.id + '-okplayer" style="position:' + position + ';left:' + moveLeft + ';top:' + moveTop + ';overflow:hidden;z-index:' + zIndex + ';height:100%;width:100%;"></div>');
      } else {
        if (base.options.controls === 3) {
          target.append(mask)
        }
        if (base.options.adproof === 1) {
          target.append('<div id="' + base.options.id + '-okplayer" style="position:' + position + ';left:-10%;top:-10%;overflow:hidden;z-index:' + zIndex + ';height:120%;width:120%;"></div>');
        } else {
          target.append('<div id="' + base.options.id + '-okplayer" style="position:' + position + ';left:' + moveLeft + ';top:' + moveTop + ';overflow:hidden;z-index:' + zIndex + ';height:' + finalHeight + 'px;width:' + finalWidth + 'px;"></div>');
        }
      }

      $("#" + base.options.id + "-mask").css("background-image", "url(" + BLANK_GIF + ")");

      if (base.options.playlist.list === null) {
        if (base.options.video.provider === 'youtube') {
          base.options.video.height = finalHeight;
          base.options.video.width = finalWidth;
          base.loadYouTubeAPI();
        } else if (base.options.video.provider === 'vimeo') {
          base.options.volume /= 100;
          base.loadVimeoAPI();
        }
      } else {
        base.loadYouTubeAPI();
      }
    };

    // clean the options
    base.setOptions = function() {
      // exchange 'true' for '1' and 'false' for 3
      for (var key in this.options) {
        if (this.options[key] === true) this.options[key] = 1;
        if (this.options[key] === false) this.options[key] = 3;
      }

      if (base.options.playlist.list === null) {
        base.options.video = base.determineProvider();
      }

      if (!base.options.id) {
        var containerID = $(base.options.target).attr('id');

        if (!containerID) {
          containerID = 'better_' + Math.floor((Math.random() * 1000) + 1);
          $(base.options.target).attr('id', containerID);
        }

        base.options.id = containerID + '-player';
      }

      // pass options to the target
      $(base.options.target).data('okoptions', base.options);
    };

    // load the youtube api
    base.loadYouTubeAPI = function(callback) {
      base.insertJS('//www.youtube.com/player_api');
    };

    base.loadYouTubePlaylist = function() {
      player.loadPlaylist(base.options.playlist.list, base.options.playlist.index, base.options.playlist.startSeconds, base.options.playlist.suggestedQuality);
    };

    // load the vimeo api by replacing the div with an iframe and loading js
    base.loadVimeoAPI = function() {
      $('#' + base.options.id + '-okplayer').replaceWith(function() {
        return '<iframe src="//player.vimeo.com/video/' + base.options.video.id + '?api=1&title=0&byline=0&portrait=0&playbar=0&loop=' + base.options.loop + '&autoplay=' + (base.options.autoplay === 1 ? 1 : 0) + '&player_id=' + base.options.id + '-okplayer" frameborder="0" style="' + $(this).attr('style') + 'visibility:hidden;background-color:black;" id="' + $(this).attr('id') + '"></iframe>';
      });

      console.log( window.location);

      //var protocol = window.location.indexOf("https://")==0?"https":"http";
      var vimeo_link = '//a.vimeocdn.com/js/froogaloop2.min.js';
      //console.log(protocol);

      // if ( protocol == 'https' ) {
      //   vimeo_link = '//secure-a.vimeocdn.com/js/froogaloop2.min.js';
      // }
      // if necessary, debug with the most recent version of froogaloop
      // base.insertJS('https://rawgithub.com/vimeo/player-api/master/javascript/froogaloop.js', function(){
      base.insertJS( vimeo_link , function() {
        vimeoPlayerReady();
      });
    };

    // insert js into the head and exectue a callback function
    base.insertJS = function(src, callback) {
      var tag = document.createElement('script');

      if (callback) {
        if (tag.readyState) { //IE
          tag.onreadystatechange = function() {
            if (tag.readyState === "loaded" ||
              tag.readyState === "complete") {
              tag.onreadystatechange = null;
              callback();
            }
          };
        } else {
          tag.onload = function() {
            callback();
          };
        }
      }
      tag.src = src;
      var firstScriptTag = document.getElementsByTagName('script')[0];
      firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
    };

    // is it from youtube or vimeo?
    base.determineProvider = function() {
      var a = document.createElement('a');
      a.href = base.options.video;

      if (/youtube.com/.test(base.options.video)) {
        return {
          "provider": "youtube",
          "id": a.href.slice(a.href.indexOf('v=') + 2).toString()
        };
      } else if (/vimeo.com/.test(base.options.video)) {
        return {
          "provider": "vimeo",
          "id": a.href.split('/')[3].toString()
        };
      } else if (/[-A-Za-z0-9_]+/.test(base.options.video)) {
        var id = new String(base.options.video.match(/[-A-Za-z0-9_]+/));
        if (id.length == 11) {
          return {
            "provider": "youtube",
            "id": id.toString()
          };
        } else {
          for (var i = 0; i < base.options.video.length; i++) {
            if (typeof parseInt(base.options.video[i]) !== "number") {
              throw 'not vimeo but thought it was for a sec';
            }
          }
          return {
            "provider": "vimeo",
            "id": base.options.video
          };
        }
      } else {
        throw "OKVideo: Invalid video source";
      }
    };

    base.init();
  };

  $.okvideo.options = {
    source: null, // Deprecate dis l8r
    video: null,
    playlist: { // eat ur heart out @brokyo
      list: null,
      index: 0,
      startSeconds: 0,
      suggestedQuality: "default" // options: small, medium, large, hd720, hd1080, highres, default
    },
    disableKeyControl: 1,
    captions: 0,
    loop: 1,
    hd: 1,
    cover: 1,
    volume: 50,
    adproof: false,
    unstarted: null,
    onFinished: null,
    onReady: null,
    onPlay: null,
    onPause: null,
    buffering: null,
    controls: false,
    autoplay: true,
    annotations: true,
    cued: null
  };

  $.fn.okvideo = function(options) {
    options.target = this;
    return this.each(function() {
      (new $.okvideo(options));
    });
  };

})(jQuery);

// vimeo player ready
function vimeoPlayerReady() {
  jQuery('.okvideo.vimeo').each(function() {
    var options = jQuery(this).data('okoptions');

    var iframe = jQuery('#' + options.id + '-okplayer')[0];
    player = $f(iframe);

    // hide player until Vimeo hides controls...
    window.setTimeout(function() {
      jQuery('#' + options.id + '-okplayer').css('visibility', 'visible');
    }, 2000);

    player.addEvent('ready', function() {
      var iframe = jQuery('#' + options.id + '-okplayer')[0];
      player = $f(iframe);
      OKEvents.v.onReady(options.id + '-okplayer');

      // "Do not try to add listeners or call functions before receiving this event."
      if (OKEvents.utils.isMobile()) {
        // mobile devices cannot listen for play event
        OKEvents.v.onPlay(options.id + '-okplayer');
      } else {
        player.addEvent('play', OKEvents.v.onPlay);
        player.addEvent('pause', OKEvents.v.onPause);
        player.addEvent('finish', OKEvents.v.onFinish);
      }

      player.api('play');
    });

    window["okvideoplayer_" + options.id] = player;
  });
}

// youtube player ready
function onYouTubePlayerAPIReady() {
  jQuery('.okvideo.youtube').each(function() {
    var options = jQuery(this).data('okoptions');

    player = new YT.Player(options.id + '-okplayer', {
      videoId: options.video ? options.video.id : null,
      playerVars: {
        'autohide': 1,
        'autoplay': options.autoplay,
        'height': options.video.height,
        'width': options.video.width,
        'disablekb': options.keyControls,
        'cc_load_policy': options.captions,
        'controls': options.controls,
        'volume': options.volume,
        'target': options.target,
        'enablejsapi': 1,
        'fs': 0,
        'modestbranding': 1,
        'iv_load_policy': options.annotations,
        'loop': options.loop,
        'showinfo': 0,
        'rel': 0,
        'wmode': 'opaque',
        'hd': options.hd,
        'theme': 'dark',
        //'wmode': 'transparent',
      },
      events: {
        'onReady': OKEvents.yt.ready,
        'onStateChange': OKEvents.yt.onStateChange,
        'onError': OKEvents.yt.error
      }
    });

    window["okvideoplayer_" + options.id] = player;
  });
}

OKEvents = {
  yt: {
    ready: function(event) {
      var options = OKEvents.utils.getPlayerOptions(event.target);

      event.target.setVolume(options.volume);
      if (options.autoplay === 1 && !OKEvents.utils.isMobile()) {
        if (options.playlist.list) {
           event.target.loadPlaylist(options.playlist.list, options.playlist.index, options.playlist.startSeconds, options.playlist.suggestedQuality);
        } else {
           event.target.playVideo();
        }
      }
      OKEvents.utils.isFunction(options.onReady) && options.onReady();
    },
    onStateChange: function(event) {
      var options = OKEvents.utils.getPlayerOptions(event.target);

      switch (event.data) {
        case -1:
          OKEvents.utils.isFunction(options.unstarted) && options.unstarted();
          break;
        case 0:
          OKEvents.utils.isFunction(options.onFinished) && options.onFinished();
          options.loop && !OKEvents.utils.isMobile() && event.target.playVideo();
          break;
        case 1:
          OKEvents.utils.isFunction(options.onPlay) && options.onPlay();
          break;
        case 2:
          OKEvents.utils.isFunction(options.onPause) && options.onPause();
          break;
        case 3:
          OKEvents.utils.isFunction(options.buffering) && options.buffering();
          break;
        case 5:
          OKEvents.utils.isFunction(options.cued) && options.cued();
          break;
        default:
          throw "OKVideo: received invalid data from YT player.";
      }
    },
    error: function(event) {
      throw event;
    }
  },
  v: {
    onReady: function(id) {
      var options = jQuery("#" + id).parents('.okvideo').data('okoptions');
      OKEvents.utils.isFunction(options.onReady) && options.onReady();
    },
    onPlay: function(id) {
      var options = jQuery("#" + id).parents('.okvideo').data('okoptions');
      if (!OKEvents.utils.isMobile()) player.api('setVolume', options.volume);
      OKEvents.utils.isFunction(options.onPlay) && options.onPlay();
    },
    onPause: function(id) {
      var options = jQuery("#" + id).parents('.okvideo').data('okoptions');
      OKEvents.utils.isFunction(options.onPause) && options.onPause();
    },
    onFinish: function(id) {
      var options = jQuery("#" + id).parents('.okvideo').data('okoptions');
      OKEvents.utils.isFunction(options.onFinish) && options.onFinish();
    }
  },
  utils: {
    isFunction: function(func) {
      if (typeof func === 'function') {
        return true;
      } else {
        return false;
      }
    },
    isMobile: function() {
      if (navigator.userAgent.match(/(iPhone|iPod|iPad|Android|BlackBerry)/)) {
        return true;
      } else {
        return false;
      }
    },
    getPlayerOptions: function(target) {
      var options = null;

      // Loop through all the properties looking for the div that contains the player. Use that to find the options.
      jQuery.each(Object.getOwnPropertyNames(target), function(i, e) {
        if (  typeof(target[e].id) !== 'undefined' && target[e].tagName.toLowerCase() === 'div' && target[e].id.indexOf('okplayer') > -1 ) {
          options = jQuery("#" + target[e].id).parents('.okvideo').data('okoptions');
        }
      });

      return options;
    }
  }
};
