(function($) {
	// Search function
	var StockImageSearchResults = _.debounce( function( search ) {
		var UNSPLASH_URL = 'https://api.unsplash.com/search/photos/?page=1&per_page=16&client_id=cc94fd94a4ed85dabeeb55269d8633970bfe65edba14cfc6407a99ab659ec1f4&query=';

		var icon_wrapper = $('#better-stockimage-wrap');
		var spinner = '<span class="spinner is-active"></span>';
		
		$.getJSON( UNSPLASH_URL + encodeURI( search ) ).done(function(data) {
			
			var results = data.results.map(function(stock) {
				return stock;
			});

			icon_wrapper.empty();

			if ( results.length ) {
				$.each( results, function( i, stock ) {
					var active = stock.urls.full === $('.better-stockimage').val() ? 'active' : '';
					var output = 
					'<div class="stock-image ' + active + '" data-sizes=' + JSON.stringify(stock.urls) + ' data-url="' + stock.urls.regular + '">' +
							'<img src="' + stock.urls.thumb + ' " alt="' + stock.description + '"/>' +
							'<div class="stock-image__likes">' +
								'<div class="stock-image__likes--icon"><span class="dashicons dashicons-heart"></span></div>' +
								'<div class="stock-image__likes--count"><span>' + stock.likes + '</span></div>' +
							'</div>' +
							'<div class="stock-image__photo-info">' + 
								'<div class="stock-image__photo-info--profile">' + 
									'<a href="' + stock.user.links.html + '"><img src="' + stock.user.profile_image.small + '" alt="' + stock.description + '"></a>' +
								'</div>' +
								'<div class="stock-image__photo-info--user"><a href="' + stock.user.links.html + '">' + stock.user.name + '</a>' +
								'<a href="' + stock.user.links.html + '">@' + stock.user.instagram_username + '</a></div>' +
							'</div>' +
					'</div>';
					icon_wrapper.append(output);
				} );
			}

		});

		icon_wrapper.html(spinner);

	}, 1000 );

	function capitalizeFirstLetter(string) {
		return string.charAt(0).toUpperCase() + string.slice(1);
	}

	function has_search_value( val ) {
		if ( val !== '' || val === 0 ) {
			StockImageSearchResults( val );
		}
	}

	jQuery(document).ready(function($) {

		var stock = $('.betterbuilder-stock-image-search').find('input');
		var $stockimage_preview = $('#better-stockimage-preview');

		// Initialized when fusion/divi modal is open
		$( 'body' ).on( 'stockimage-param-loaded', function( event, param_name ) {
			setTimeout( function() {
				var $option;
				/**
				 * Check if using divi or fusion builder
				 */
				if ( $( '[data-option-id="' + param_name + '"]' ).length ) {
					// data attribute for fusion builder
					$option = $( '[data-option-id="' + param_name + '"]' );
				} else if ( $( '[data-option_name="' + param_name + '"]' ).length ) {
					// data attribute for fusion divi
					$option = $( '[data-option_name="' + param_name + '"]' );
				}
				
				if ( $option.length ) {
					// Run api fetching here.
					$option.find( '#better-stockimage-preview' ).html( '<img src="' + $('.better-stockimage').val() + '" width="100%" />' );
	
					// Add listener to input search.
					$option.find( '#stock-image-searcher' ).on( 'keyup', function( event ) {
						StockImageSearchResults( $( event.currentTarget ).val() );
					} );
				}
			}, 100 );
		} );

		if ( $stockimage_preview.length ) {
			$stockimage_preview.html( '<img src="' + $('.better-stockimage').val() + '" width="100%" />' );
		}

		// Check the saved value to load the photos for default
		//has_search_value( stock.val() );

		$(document).on('keyup', '#stock-image-searcher', function( event ) {
			StockImageSearchResults( $( event.currentTarget ).val() );
		});

		$(document).on('click', '.stock-image', function(e) {
			var image_url = $(this).data( 'url' );
			var image_sizes = $(this).data( 'sizes' );
			var hidden_input = $(this).parent().siblings( 'input' );
			$(this).siblings().removeClass( 'active' ).end().toggleClass( 'active' );
			hidden_input.last().attr( 'value', image_url );
			
			var size_opts = _.map( image_sizes, function( url, size ) {
				return '<option value="' + url + '">' + capitalizeFirstLetter( size ) + '</option>';
			} );

			if ( $('.stock-image-size').length > 0 ) {
				$('.stock-image-size').remove();
			}

			$( $(this).parent().parent().find( '#stock-image-searcher' ) ).first().before( "<select name=\"stock-image-size\" class=\"stock-image-size regular-text fusion-select-field\" style=\"margin-bottom: 10px\">" + size_opts.join('') + "</select>" );

			// trigger hidden put for elementor to detect it changed and update the field
			$( 'input[type="hidden"]' ).trigger( 'input' );

			if ( $('#better-stockimage-preview').length ) {
				$('#better-stockimage-preview').find( 'img' ).attr('src', image_url );
			}

			e.preventDefault();
		});

		$(document).on( 'change', '.stock-image-size', function() {
			var url = $(this).val();
			if ( $('#better-stockimage-preview').length ) {
				$('#better-stockimage-preview').find( 'img' ).attr('src', url );
			}
			$( 'input.better-stockimage' ).attr( 'value', url );
			// trigger hidden put for elementor to detect it changed and update the field
			$( 'input[type="hidden"]' ).trigger( 'input' );
		} );

	});

	/**
	 * Check is elementor editor is active
	 * 
	 * trigger when elementor element stock image setting is open
	 */
	if ( $('body').hasClass('elementor-editor-active') ) {
		elementor.hooks.addAction( 'panel/open_editor/widget/better-stock-image', function( panel, model, view ) {
			var $element_search = panel.$el.find( '.betterbuilder-stock-image-search input' );
			panel.$el.find( '#stock-image-searcher' ).on( 'keyup', function( event ) {
				StockImageSearchResults( $( event.currentTarget ).val() );
			} );
		});
	}

})( jQuery );
