( function( $ ) {

	$(document).on('change', '.post_type', function() {
		setupLists();
	});

	$(document).on('change', '#taxonomy', function() {
		setupCategory();
	});

	$(document).on( 'post-type-loaded', function( event, param_name ) {
		setTimeout( function() {
			setupLists();
			setupCategory();
		}, 100 );
	} );

	setupLists();
	setupCategory();

	function setupLists() {
		$("#taxonomy").empty();
		$("#categories").empty();
		$("#exclude_categories").empty();
		$(".cpt_template").empty();
		//$("#template_link").empty();

		if ( $(".post_type").val() != '' ) {
			// var $loader = $('<p>Loading...</p>');
			// var $loader2 = $loader.clone();
			// var $loader3 = $loader.clone();

			$("#taxonomy").prop("disabled", true);
			$(".cpt_template").prop("disabled", true);
			$("#template_link").prop("disabled", true);

			$.ajax({
				type: "POST",
				url: ajaxurl + '?action=better_generic_post_action',
				data: {
					//security: '<?php echo $ajax_nonce; ?>',
					postType: $(".post_type").val(),
					postTaxonomy: $("#taxonomy").val()
				 }
			}).done(function (data) {
				if ( !data ) {
					return;
				}
				var results = jQuery.parseJSON(data);
				var templates = results.templates;
				var taxonomies = results.taxonomies;
				//var links = results.links;

				$.each(taxonomies, function(index, taxonomy) {
					var selected;
					if ( $("#taxonomy").data( 'selected' ) === taxonomy.key ) {
						selected = ' selected="selected"';
					}
					$("#taxonomy").append('<option value="' + taxonomy.key + '" ' + selected + '>' + taxonomy.value + '</option>');
				});

				setupCategory();

				$.each(templates, function(index, template) {	
					var selected = '';
					if ( $(".cpt_template").data( 'option' ) === template.key ) {
						selected = ' selected="selected"';
					}

					$(".cpt_template").append('<option value="' + template.key + '" ' + selected + '>' + template.value + '</option>');
				});

				//$("#template").data('templates', templates);

				//setupTemplateImage();

				// $.each(links, function(index, link) {
				// 	$("#template_link").append('<option value="' + link.key + '">' + link.value + '</option>');
				// });

				// $("#template_link").val('single');

				$("#taxonomy").prop("disabled", false);
				$(".cpt_template").prop("disabled", false);
				//$("#template_link").prop("disabled", false);
			});
		}
	}

	function setupCategory() {
		$("#categories").empty();

		if ( $("#taxonomy").val() !== null && $("#taxonomy").val() !== '' ) {
			// var $loader = $('<p>Loading...</p>');
			// var $loader2 = $loader.clone();

			$("#categories").prop("disabled", true);
			$("#exclude_categories").prop("disabled", true);

			$.ajax({
				type: "POST",
				url: ajaxurl + '?action=better_generic_post_action',
				data: {
					//security: '<?php echo $ajax_nonce; ?>',
					postType: $(".post_type").val(),
					postTaxonomy: $("#taxonomy").val()
				 }
			}).done(function (data) {
				if ( !data ) {
					return;
				}
				
				var results = jQuery.parseJSON(data);
				var terms = results.terms;

				$.each(terms, function( index, category ) {
					var data = selected = '';
					if ( ! Array.isArray( $("#categories").data( 'selected' ) ) ) {
						data = $("#categories").data( 'selected' ).split(',');
					} else {
						data = $("#categories").data( 'selected' );
					}

					for (var i = 0; i < data.length; i++) {
						if ( data[i] == category.key ) {
							selected = ' selected';
						}
					}

					$("#categories").append('<option value="' + category.key + '" ' + selected + '>' + category.value + '</option>');

					$("#exclude_categories").append('<option value="' + category.key + '">' + category.value + '</option>');

					i++;
				});

				$("#categories").prop("disabled", false);
				$("#exclude_categories").prop("disabled", false);
			});
		}
	}

} )( jQuery )