jQuery(document).ready(function($) {

    $(document).on('change', '.post_type select', function() {
        setupLists();
	});

	$(document).on('change', '#taxonomy', function() {
        setupCategory();
    });
    
    elementor.hooks.addAction( 'panel/open_editor/widget/better-posts', function( panel, model, view ) {
        setupLists();
        setupCategory();
        $('.none').hide();
    });

	setupLists();
	setupCategory();

	function setupLists() {
		$("#taxonomy").empty();
		//$("#et_pb_categories").empty();
		$(".categories select").empty();
		$("#template").empty();
		//$("#template_link").empty();

		if ( $(".post_type select").val() != '' ) {
			var $loader = $('<p>Loading...</p>');
			var $loader2 = $loader.clone();
			var $loader3 = $loader.clone();

			$("#taxonomy").after($loader);
			$("#template").after($loader2);
            //$("#template_link").after($loader3);

			$.ajax({
				type: "POST",
				url: ajaxurl + '?action=better_generic_post_action',
				data: {
					//security: '<?php echo $ajax_nonce; ?>',
					postType: $(".post_type select").val(),
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
                        if ( $('#taxonomy').data( 'option' ) === taxonomy.key ) {
                            selected = ' selected="selected"';
                        }
                        $("#taxonomy").append('<option value="' + taxonomy.key + '" ' + selected + '>' + taxonomy.value + '</option>');
                    });
                }

				setupCategory();

				if ( templates ) {
					$.each(templates, function(index, template) {		
						var selected = '';
                        if ( $("#template").data( 'option' ) === template.key ) {
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
        $(".categories select").empty();
        
        var taxonomyVal = $("#taxonomy").val();

		if ( taxonomyVal !== null && taxonomyVal !== '' ) {
			var $loader = $('<p>Loading...</p>');
			var $loader2 = $loader.clone();

			$(".categories select").after($loader);
            $(".exclude select").after($loader2);

			$.ajax({
				type: "POST",
				url: ajaxurl + '?action=better_generic_post_action',
				data: {
					//security: '<?php echo $ajax_nonce; ?>',
					postType: $(".post_type select").val(),
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
                        termArr.push({
                            id: category.key,
                            text: category.value
                        });
                    }

                    $(".categories select").append('<option value="' + category.key + '">' + category.value + '</option>');
                    $(".exclude select").append('<option value="' + category.key + '">' + category.value + '</option>');
                });
                
                $(".categories select").empty().select2({
                    data: termArr
                });

                $(".exclude select").empty().select2({
                    data: termArr
                });

				$loader.remove();
				$loader2.remove();
			});
		}
	}
});