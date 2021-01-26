/*!
 * Lazy Load Helper
 * Copyright (c) 2015 Intense Visions, Inc.
 */
/* global jQuery */

(function($) {
    'use strict';

    var options = $.lazyLoadXT,
        srcAttr = options.srcAttr || 'data-src';

    options.selector += ',[' + srcAttr + ']';
    options.edgeY = 600;

    // remove lazy load from slider images
    $(document).on('lazyinit', function(e) {
        var $this = $(e.target);

        if ($this.parents('.intense.slider').length > 0) {
            $this.attr('src', $this.attr(srcAttr));
            $this.removeAttr(srcAttr);
            $this.addClass('lazy-loaded');
        }
    });

    //remove lazy load from lightboxes
    $('.intense.lightbox a[href*="#"]').each(function() {
        var $el = $(this);

        //select the lightbox target so we can find the lazy load children
        $($el.attr('href')).find('[data-src]').each(function() {
            var $this = $(this);

            $this.attr('src', $this.attr(srcAttr));
            $this.removeAttr(srcAttr);
            $this.addClass('lazy-loaded');
        });
    });

    $(document).on('lazyload', function(e) {
        var $this = $(e.target);

        if ($this.parents('.gallery-item').length > 0) {
            $this.parents('.gallery-item').css('opacity', '1');
            $this
                .removeClass('lazy-hidden')
                .addClass('animated ' + $this.attr('data-effect'));
        }

        setTimeout(function() {
            if ($this.parents(".intense.masonry").length > 0) {
                $this.parents(".intense.masonry")
                    .css('width', '100%')
                    .packery();
            }

            $('.lazy-loaded').removeClass('lazy-loaded').removeClass('lazy-loading').removeClass('lazy-hidden');
        }, 500);
    });

    var bgAttr = options.bgAttr || 'data-bg';
    options.selector += ',[' + bgAttr + ']';

    $(document).on('lazyshow', function(e) {        
        var $this = $(e.target),
            url = $this.attr(bgAttr);
        if (!!url) {
            var $element = $this;
            $('<img>').attr('src', url).load(function() {
                $element
                    .css('background-image', "url('" + url + "')")                    
                    .removeAttr(bgAttr)
                    .triggerHandler('load');
            });
        }
        
        $('.lazy-instant').removeClass('lazy-hidden');
    });

    $(window).load(function() {
        $('.intense.lazy-instant').each(function() {
            var $this = $(this);
            var url = $this.attr(srcAttr);

            if (!!url) {
                var $element = $this;

                 $('<img>').attr('src', url).load(function() {
                    $element
                        .attr('src', url)
                        .removeAttr(srcAttr)
                        .removeClass('lazy-loaded')
                        .removeClass('lazy-loading')
                        .removeClass('lazy-hidden');
                });
             }
        });
    });
})(window.jQuery || window.Zepto || window.$);