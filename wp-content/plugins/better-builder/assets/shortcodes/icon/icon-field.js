( function( $ ) {
	function better_icon_selectize() {
		var xhr = [];
	
		$('.better_iconsource select, select.better_iconsource').selectize({
			sortField: 'text',
		});
	
		$('.better_icontype select, select.better_icontype').selectize({
			create: true,
			sortField: 'text',
			render: {
				option: function(item, escape) {
					return '<div class=\"better icon selected\" style=\"float: left; width: 32px; height:32px; margin: 1px; text-align: center;\">' +
					item.svg +
					'</div>';
				},
				item: function(item, escape) {
					return '<div><div class=\"better icon\" style=\"float: left; width: 20px; height:20px; margin-right: 5px; text-align: center;\">' +
					item.svg + '</div> ' + item.text  +
					'</div>';
				}
			},
			load: function(callback) { }
		});
	
		$('.better_iconsource select, select.better_iconsource').change( function() {
			var self = $(this);
			var icon_source = $(this).val();
			var icon_type_wrapper = $(this).parents('[class*="better_icon"]').next('[class*="better_icon"]');
			var icon_type_id = icon_type_wrapper.find('select').attr('id');
			var icon_type = jQuery('#' + icon_type_id)[0].selectize;
	
			icon_type.disable();
			icon_type.clear();
			icon_type.clearOptions();
	
			icon_type.load(function(callback) {
				xhr[icon_type_id] && xhr[icon_type_id].abort();
				xhr[icon_type_id] = $.ajax({
					type: 'POST',
					url: ajaxurl + '?action=better_icon_type_action',
					dataType: 'json',
					cache: false,
					data: {
						source: icon_source,
						type_id: icon_type_id
					},
					success: function(results) {
						var select = jQuery('#' + results.type_id);
						var icon_type = select[0].selectize;
						var classList = select.parents('[class*="better_icon"]').attr('class').split(/\s+/);
	
						var defaultVal = '';
						if ( select.attr('data-option') && select.attr('data-option').length > 0 ) {
							defaultVal = select.attr('data-option');
						} else if ( select.attr('data-saved_value') && select.attr('data-saved_value').length > 0 ) {
							defaultVal = select.attr('data-saved_value');
						}
	
						// $.each( classList, function(index, item){
						// 	if (item[0] === '_' && item[1] === '_') {
						// 	defaultVal = item.replace('__', '', 'gi');
						// 	}
						// });
	
						icon_type.enable();
						icon_type.refreshOptions();
						icon_type.clearOptions();
						icon_type.renderCache = {};
						callback(results.icons);
	
						icon_type.setValue(defaultVal);
					},
					error: function() {
						callback();
					}
				});
			});
	
		} );
	
		$('.better_iconsource select:not(.initialized), select.better_iconsource:not(.initialized)').addClass('initialized').change();
	}
	
	if ( typeof elementor !== 'undefined' ) {
		elementor.hooks.addAction( 'panel/open_editor/widget/better-icon', function ( panel, model, view ) {
			better_icon_selectize();
		} );
	}
	
	$(document).on( 'icon-param-loaded', function ( event, param_name ) {
		setTimeout( function() {
			better_icon_selectize();
		}, 500);
	} );
}( jQuery ) );
