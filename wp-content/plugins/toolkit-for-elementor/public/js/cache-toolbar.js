window.addEventListener('load', function(){
	jQuery(document).ready(function(){
		jQuery("body").append('<div id="revert-loader-toolbar"></div>');

		jQuery("#wp-admin-bar-wptec-toolbar-parent-default li").click(function(e){
			if(typeof ajaxurl != "undefined" || typeof wptec_ajaxurl != "undefined"){
				var ajax_url = (typeof ajaxurl != "undefined") ? ajaxurl : wptec_ajaxurl;
				var id = (typeof e.target.id != "undefined" && e.target.id) ? e.target.id : jQuery(e.target).parent("li").attr("id");
				var action = "";
				
				if(id == "wp-admin-bar-wptec-toolbar-parent-delete-cache"){
					action = "wptec_delete_cache";
				}else if(id == "wp-admin-bar-wptec-toolbar-parent-delete-cache-and-minified"){
					action = "wptec_delete_cache_and_minified";
				}else if(id == "wp-admin-bar-wptec-toolbar-parent-clear-cache-of-this-page"){
					action = "wptec_delete_current_page_cache";
				}

				jQuery("#revert-loader-toolbar").show();
				jQuery.ajax({
					type: 'GET',
					url: ajax_url,
					data : {"action": action, "path" : window.location.pathname},
					dataType : "json",
					cache: false, 
					success: function(data){
						if(data[1] == "error"){
							if(typeof data[2] != "undefined" && data[2] == "alert"){
								alert(data[0]);
							}else{
								Wpfc_New_Dialog.dialog("wptec-modal-permission", {close: "default"});
								Wpfc_New_Dialog.show_button("close");
							}
						}

						if(typeof WpFcCacheStatics != "undefined"){
							WpFcCacheStatics.update();
						}else{
							jQuery("#revert-loader-toolbar").hide();
						}
					}
				});
			}else{
				alert("AjaxURL has NOT been defined");
			}
		});
	});
});

