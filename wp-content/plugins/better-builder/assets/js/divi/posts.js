jQuery(document).ready(function($) {
	
    function split( val ) {
        return val.split( /,\s*/ );
	}
	
	function checkIfArray( el ) {
		var data = '';
		if ( $(el).val() && $(el).val().indexOf( ',' ) != -1 ) {
			data = $(el).val().split(',');
		} else {
			data = $(el).val();
		}
		return data;
	}

	function checkedTerms( list, key ) {
		var checked = '';
		if ( Array.isArray( list ) ) {
			for (var i = 0; i < list.length; i++) {
				if ( list[i] == key ) {
					checked = ' checked="checked"';
				}
			}
		} else {
			if ( list == key ) {
				checked = ' checked="checked"';
			}
		}
		
		return checked;
	}

    $(document).on('change', '.post_type', function() {
        setupLists();
	});

	$(document).on('change', '#taxonomy', function() {
        setupCategory();
	});

	$( document ).on( 'posts-loaded', function( event, param_name ) {
		setTimeout( function() {
			setupLists();
            setupCategory();
            bb_autocomplete();
			$('.none').hide();
			
			onChangeTerms('#categories');
			onChangeTerms('#exclude_categories');
		}, 100 );
	} );

	setupLists();
	setupCategory();

	function onChangeTerms( selector ) {
		$( selector + ' input[type=checkbox]').change(function() {
			let values = [];
			// check each checked checkbox and store the value in array
			$.each($( selector + ' input[type=checkbox]:checked'), function(){
				values.push($(this).val());
			});

			$(selector).siblings('.categories').val(values.toString());
		});
	}

	function setupLists() {
		$("#taxonomy").empty();
		$("#categories").empty();
		//$("#et_pb_categories").val('');
		$("#exclude_categories").empty();
		$("#template").empty();
		$("#template_link").empty();

		if ( $(".post_type").val() == 'product') {
			$('.woocommerce').show();			
		} else {
			$('.woocommerce').hide();
		}

		if ( $(".post_type").val() != '' ) {
			var $loader = $('<p>Loading...</p>');
			var $loader2 = $loader.clone();
			var $loader3 = $loader.clone();

			$("#taxonomy").after($loader.first());
			$("#et_pb_template").after($loader2);
            //$("#template_link").after($loader3);

			$.ajax({
				type: "POST",
				url: ajaxurl + '?action=better_generic_post_action',
				data: {
					//security: '<?php echo $ajax_nonce; ?>',
					postType: $(".post_type").val(),
					postTaxonomy: $("#taxonomy").val()
				}
			}).done(function (data) {
				if ( ! data ) {
					return;
				}
				var results = jQuery.parseJSON(data);
				var templates = results.templates;
				var taxonomies = results.taxonomies;
                //var links = results.links;
                
                if ( taxonomies ) {
                    $.each(taxonomies, function(index, taxonomy) {
                        var selected = '';
                        if ( $("#et_pb_taxonomy").val() === taxonomy.key ) {
                            selected = ' selected="selected"';
                        }
                        $("#taxonomy").append('<option value="' + taxonomy.key + '" ' + selected + '>' + taxonomy.value + '</option>');
                    });
                }

				setupCategory();
                onChangeTerms('#categories');
                onChangeTerms('#exclude_categories');

				if ( templates ) {
					$.each(templates, function(index, template) {		
						var selected = '';
                        if ( $("#et_pb_template").val() === template.key ) {
                            selected = ' selected="selected"';
						}
						
						$("#template").append('<option value="' + template.key + '" ' + selected + '>' + template.value + '</option>');
					});
				}

				//setupTemplateImage();

				// $.each(links, function(index, link) {
				// 	$("#template_link").append('<option value="' + link.key + '">' + link.value + '</option>');
				// });

				// $("#template_link").val('single');

				$loader.remove();
				$loader2.remove();
				$loader3.remove();
			});
		}
	}

	function setupCategory() {
		$("#categories").empty();
		$("#exclude_categories").empty();
        
        var taxonomyVal = $("#taxonomy").val();

		if ( taxonomyVal !== null && taxonomyVal !== '' ) {

            // copy selected value to divi field
            $('#et_pb_taxonomy').val(taxonomyVal);

			var $loader = $('<p>Loading...</p>');
			var $loader2 = $loader.clone();

			$("#categories").after($loader.first());
			$("#exclude_categories").after($loader2.first());

			$.ajax({
				type: "POST",
				url: ajaxurl + '?action=better_generic_post_action',
				data: {
					//security: '<?php echo $ajax_nonce; ?>',
					postType: $(".post_type").val(),
					postTaxonomy: taxonomyVal
				 }
			}).done(function (data) {
				if ( !data ) {
					return;
				}
				
				var results = jQuery.parseJSON(data);
				var terms = results.terms;

                var termArr = new Array();

				$.each(terms, function( index, category ) {
                    if ( category ) {
                        termArr.push(category.value);
					}

					var termChecked = checkIfArray( '#et_pb_categories' );
					var excludeChecked = checkIfArray( '#et_pb_exclude_categories' );

					var selectedTerm = checkedTerms( termChecked, category.key );
					var selectedExclude = checkedTerms( excludeChecked, category.key );
					
					$("#categories").append('<label><input type="checkbox" name="categories" value="' + category.key + '" ' + selectedTerm + '> ' + category.value + '</label><br>');

					$("#exclude_categories").append('<label><input type="checkbox" name="exclude_categories" value="' + category.key + '" ' + selectedExclude + '> ' + category.value + '</label><br>');
                });

                if ( terms.length > 0 ) {
                    if ( Array.isArray( termArr ) ) {
                        $('.terms').attr('data-terms', termArr);
                    }
                } else {
                    $('.terms').attr('data-terms', '');
                }
                
                onChangeTerms('#categories');
                onChangeTerms('#exclude_categories');

				$loader.remove();
				$loader2.remove();
			});
		}
	}

    function extractLast( term ) {
        return split( term ).pop();
    }

    function bb_autocomplete() {

		var selector = $( ".divi_el_type_autocomplete" );
		
		// [ {key: 'category-1', value: 'Category 1'}, {key: 'category-2', value: 'Category 2'} ]

        var terms = $('.terms').attr('data-terms');

        if ( terms && ! Array.isArray( terms ) ) {
            terms = terms.split(',');
        }

        selector.on( "keydown", function( event ) {
            if ( event.keyCode === $.ui.keyCode.TAB && $( this ).autocomplete( "instance" ).menu.active ) {
                event.preventDefault();
            }
        })
        .autocomplete({
            minLength: 0,
            source: function( request, response ) {
                // delegate back to autocomplete, but extract the last term
                response( $.ui.autocomplete.filter(
                    terms, extractLast( request.term ) ) );
            },
            focus: function() {
                // prevent value inserted on focus
                return false;
            },
            select: function( event, ui ) {
                var terms = split( this.value );
                // remove the current input
                terms.pop();
                // add the selected item
                terms.push( ui.item.value );
                // add placeholder to get the comma-and-space at the end
                terms.push( "" );
                this.value = terms.join( ", " );
                return false;
            },
            open: function() {
                $(this).autocomplete('widget').css('z-index', 9999);
                return false;
            },
        });
    }
});