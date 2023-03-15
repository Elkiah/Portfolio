( function( $ ) {
	"use strict";

	var container = $( '.wp-block-bb-gutenberg-masonry ul' );

	$( document ).ready( function () {

		container.imagesLoaded(function(){
			container.masonry( {
				itemSelector: '.blockgallery--item',
				transitionDuration: '0.2s',
				percentPosition: true,
			} );
		});
	});

} )( jQuery );

/*This file was exported by "Export WP Page to Static HTML" plugin which created by ReCorp (https://myrecorp.com) */