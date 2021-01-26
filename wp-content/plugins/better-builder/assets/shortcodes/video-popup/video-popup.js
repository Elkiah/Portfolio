jQuery(document).ready(function($) {
    $('a.magnific-popup.better_video_lightbox, .better-video-box a.full-link.magnific-popup').magnificPopup({ 
        type: 'iframe', 
        fixedContentPos: false,
        mainClass: 'mfp-zoom-in', 
        removalDelay: 400 
    });
});