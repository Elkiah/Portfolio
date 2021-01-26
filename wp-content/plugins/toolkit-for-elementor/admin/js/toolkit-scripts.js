jQuery.fn.setLoading = function(pct) {
    var indicatorID = jQuery(this).attr('id');
    $('#loading-indicator-' + indicatorID).html(pct + '%');
};
jQuery.fn.showLoading = function(options) {
    var indicatorID;
    var settings = {
        'addClass': '',
        'beforeShow': '',
        'afterShow': '',
        'hPos': 'center',
        'vPos': 'center',
        'indicatorZIndex' : 5001,
        'overlayZIndex': 5000,
        'parent': '',
        'waitingText' : '',
        'marginTop': 0,
        'marginLeft': 0,
        'overlayWidth': null,
        'overlayHeight': null
    };
    jQuery.extend(settings, options);
    var loadingDiv = jQuery('<div style="text-align:center"></div>');
    var loadingTextDiv = jQuery('<div style="text-align:center">'+settings.waitingText+'</div>');
    var overlayDiv = jQuery('<div></div>');
    if ( settings.indicatorID ) {
        indicatorID = settings.indicatorID;
    } else {
        indicatorID = jQuery(this).attr('id');
    }
    jQuery(loadingDiv).attr('id', 'loading-indicator-' + indicatorID );
    jQuery(loadingDiv).addClass('loading-indicator');
    jQuery(loadingTextDiv).attr('id', 'loading-indicator-text' );
    jQuery(loadingTextDiv).addClass('loading-indicator-text');
    if ( settings.addClass ){
        jQuery(loadingDiv).addClass(settings.addClass);
    }
    jQuery(overlayDiv).css('display', 'none');
    jQuery(document.body).append(overlayDiv);
    jQuery(overlayDiv).attr('id', 'loading-indicator-' + indicatorID + '-overlay');
    jQuery(overlayDiv).addClass('loading-indicator-overlay');
    if ( settings.addClass ){
        jQuery(overlayDiv).addClass(settings.addClass + '-overlay');
    }
    var overlay_width;
    var overlay_height;
    var border_top_width = jQuery(this).css('border-top-width');
    var border_left_width = jQuery(this).css('border-left-width');
    border_top_width = isNaN(parseInt(border_top_width)) ? 0 : border_top_width;
    border_left_width = isNaN(parseInt(border_left_width)) ? 0 : border_left_width;
    var overlay_left_pos = jQuery(this).offset().left + parseInt(border_left_width);// +  $(document.body).css( "border-left" );
    var overlay_top_pos = jQuery(this).offset().top + parseInt(border_top_width);
    if ( settings.overlayWidth !== null ) {
        overlay_width = settings.overlayWidth;
    } else {
        overlay_width = parseInt(jQuery(this).width()) + parseInt(jQuery(this).css('padding-right')) + parseInt(jQuery(this).css('padding-left'));
    }
    if ( settings.overlayHeight !== null ) {
        overlay_height = settings.overlayWidth;
    } else {
        overlay_height = parseInt(jQuery(this).height()) + parseInt(jQuery(this).css('padding-top')) + parseInt(jQuery(this).css('padding-bottom'));
    }
    jQuery(overlayDiv).css('width', overlay_width.toString() + 'px');
    jQuery(overlayDiv).css('height', overlay_height.toString() + 'px');
    jQuery(overlayDiv).css('left', overlay_left_pos.toString() + 'px');
    jQuery(overlayDiv).css('position', 'absolute');
    jQuery(overlayDiv).css('top', overlay_top_pos.toString() + 'px' );
    jQuery(overlayDiv).css('z-index', settings.overlayZIndex);
    if ( settings.overlayCSS ) {
        jQuery(overlayDiv).css ( settings.overlayCSS );
    }
    jQuery(loadingDiv).css('display', 'none');
    jQuery(document.body).append(loadingDiv);
    jQuery(loadingTextDiv).css('display', 'none');
    jQuery(document.body).append(loadingTextDiv);
    jQuery(loadingDiv).css('position', 'absolute');
    jQuery(loadingDiv).css('z-index', settings.indicatorZIndex);
    jQuery(loadingTextDiv).css('position', 'absolute');
    jQuery(loadingTextDiv).css('z-index', settings.indicatorZIndex);
    var indicatorTop = overlay_top_pos;
    if ( settings.marginTop ) {
        indicatorTop += parseInt(settings.marginTop);
    }
    var indicatorLeft = overlay_left_pos;
    if ( settings.marginLeft ) {
        indicatorLeft += parseInt(settings.marginTop);
    }
    if ( settings.hPos.toString().toLowerCase() == 'center' ) {
        jQuery(loadingDiv).css('left', (indicatorLeft + ((jQuery(overlayDiv).width() - parseInt(jQuery(loadingDiv).width())) / 2)).toString()  + 'px');
        jQuery(loadingTextDiv).css('left', (indicatorLeft + ((jQuery(overlayDiv).width() - parseInt(jQuery(loadingTextDiv).width())) / 2)).toString()  + 'px');
    } else if ( settings.hPos.toString().toLowerCase() == 'left' ) {
        jQuery(loadingDiv).css('left', (indicatorLeft + parseInt(jQuery(overlayDiv).css('margin-left'))).toString() + 'px');
        jQuery(loadingTextDiv).css('left', (indicatorLeft + parseInt(jQuery(overlayDiv).css('margin-left'))).toString() + 'px');
    } else if ( settings.hPos.toString().toLowerCase() == 'right' ) {
        jQuery(loadingDiv).css('left', (indicatorLeft + (jQuery(overlayDiv).width() - parseInt(jQuery(loadingDiv).width()))).toString()  + 'px');
        jQuery(loadingTextDiv).css('left', (indicatorLeft + (jQuery(overlayDiv).width() - parseInt(jQuery(loadingTextDiv).width()))).toString()  + 'px');
    } else {
        jQuery(loadingDiv).css('left', (indicatorLeft + parseInt(settings.hPos)).toString() + 'px');
        jQuery(loadingTextDiv).css('left', (indicatorLeft + parseInt(settings.hPos)).toString() + 'px');
    }
    if ( settings.vPos.toString().toLowerCase() == 'center' ) {
        jQuery(loadingDiv).css('top', (indicatorTop + ((jQuery(overlayDiv).height() - parseInt(jQuery(loadingDiv).height())) / 2)).toString()  + 'px');
        jQuery(loadingTextDiv).css('top', (indicatorTop + ((jQuery(overlayDiv).height() - parseInt(jQuery(loadingTextDiv).height())) / 1.75)).toString()  + 'px');
    } else if ( settings.vPos.toString().toLowerCase() == 'top' ) {
        jQuery(loadingDiv).css('top', indicatorTop.toString() + 'px');
        jQuery(loadingTextDiv).css('top', indicatorTop.toString() + 'px');
    } else if ( settings.vPos.toString().toLowerCase() == 'bottom' ) {
        jQuery(loadingDiv).css('top', (indicatorTop + (jQuery(overlayDiv).height() - parseInt(jQuery(loadingDiv).height()))).toString()  + 'px');
        jQuery(loadingTextDiv).css('top', (indicatorTop + (jQuery(overlayDiv).height() - parseInt(jQuery(loadingDiv).height()))).toString()  + 'px');
    } else {
        jQuery(loadingDiv).css('top', (indicatorTop + parseInt(settings.vPos)).toString() + 'px' );
        jQuery(loadingTextDiv).css('top', (indicatorTop + parseInt(settings.vPos)).toString() + 'px' );
    }
    if ( settings.css ) {
        jQuery(loadingDiv).css ( settings.css );
        jQuery(loadingTextDiv).css ( settings.css );
    }
    var callback_options = {
		'overlay': overlayDiv,
		'indicator': loadingDiv,
		'element': this
	};
    if ( typeof(settings.beforeShow) == 'function' ) {
        settings.beforeShow( callback_options );
    }
    jQuery(overlayDiv).show();
    jQuery(loadingDiv).show();
    jQuery(loadingTextDiv).show();
    if ( typeof(settings.afterShow) == 'function' ) {
        settings.afterShow( callback_options );
    }
    return this;
};
jQuery.fn.hideLoading = function(options) {
    var settings = {};
    jQuery.extend(settings, options);
    if ( settings.indicatorID ) {
        indicatorID = settings.indicatorID;
    } else {
        indicatorID = jQuery(this).attr('id');
    }
    jQuery(document.body).find('#loading-indicator-text' ).remove();
    jQuery(document.body).find('#loading-indicator-' + indicatorID ).remove();
    jQuery(document.body).find('#loading-indicator-' + indicatorID + '-overlay' ).remove();
    return this;
};
jQuery(document).ready(function ($) {
    if( $('.collapse-lbl').length > 0 ){
        $('.collapse-lbl').on('click', function () {
            $('#'+$(this).data('target')).toggleClass('open');
        });
    }
    if( $('.stats-tabs .button').length > 0 ){
        $('.stats-tabs .button').on('click', function () {
            $('.gtmetrix-stats .table-responsive').removeClass('showme');
            $('#'+$(this).data('target')).addClass('showme');
        });
    }
    var leftRowSlctr = $('.gtmetrix-report .report-left > .row');
    if( leftRowSlctr.length > 0 ){
        var rightRowSlctr = $('.gtmetrix-report .report-right > .row');
        if( leftRowSlctr.outerHeight() > rightRowSlctr.outerHeight() ){
            rightRowSlctr.css({'height':leftRowSlctr.outerHeight()+'px'});
		} else {
            leftRowSlctr.css({'height':rightRowSlctr.outerHeight()+'px'});
		}
	}
	var delay = 5000;
	var fadeSpeed = 'slow';
	jQuery('body').on('click', '.toolkit-performance #gtmetrix-history-section .pagination2 a', function() {
		var page_no = jQuery(this).data('page');
		if(page_no){
			jQuery.ajax({
				type: "POST",
				url: toolkit.ajax_url,
				dataType: "json",
				data: {action:'toolkit_performance_gtmetrix_history',page_no:page_no,_nonce:toolkit._nonce},
				beforeSend: function(){
					jQuery('#gtmetrix-history-section').showLoading();
				},
				complete: function(){
				},
				success: function(response){
					jQuery('#gtmetrix-history-section').hideLoading();
					if(response.status==1){
						jQuery('#gtmetrix-history-section').html(response.html);
					} else {
						jQuery('.toolkit-message').removeClass('updated').addClass('error').show().html('<p>'+response.message+'</p>').delay(delay).fadeOut(fadeSpeed);
						jQuery("html, body").animate({ scrollTop: 0 }, "slow");
					}
				},
				error: function(request, status, error) {
					alert(status);
				}
			});
		} else {
			jQuery('.toolkit-message').removeClass('updated').addClass('error').show().html('<p>Invalid Page No.</p>').delay(delay).fadeOut(fadeSpeed);
			jQuery("html, body").animate({ scrollTop: 0 }, "slow");
		}
	});

	//MY WEBSITE PAGINATION
	jQuery('body').on('click', '#toolkit-my-license .activated-site-section .pagination2 a', function() {
		var page_no = jQuery(this).data('page');
		if(page_no){
			jQuery.ajax({
				type: "POST",
				url: toolkit.ajax_url,
				dataType: "json",
				data: {action:'toolkit_my_website_history',page_no:page_no,_nonce:toolkit._nonce},
				beforeSend: function(){
					jQuery('.activated-site-section').showLoading();
				},
				complete: function(){
				},
				success: function(response){
					jQuery('.activated-site-section').hideLoading();
					if(response.status==1){
						jQuery('.activated-site-section').html(response.html);
					} else {
						jQuery('.toolkit-message').removeClass('updated').addClass('error').show().html('<p>'+response.message+'</p>').delay(delay).fadeOut(fadeSpeed);
						jQuery("html, body").animate({ scrollTop: 0 }, "slow");
					}
				},
				error: function(request, status, error) {
					alert(status);
				}
			});
		} else {
			jQuery('.toolkit-message').removeClass('updated').addClass('error').show().html('<p>Invalid Page No.</p>').delay(delay).fadeOut(fadeSpeed);
			jQuery("html, body").animate({ scrollTop: 0 }, "slow");
		}
	});

	jQuery('body').on('click', '.toolkit-performance #gtmetrix-scan-history .download-full-report', function() {
		var report_url = jQuery(this).data('full_report');
		var testid = jQuery(this).data('testid');
		if(report_url && testid){
			jQuery.ajax({
				type: "POST",
				url: toolkit.ajax_url,
				dataType: "html",
				data: {action:'toolkit_performance_gtmetrix_download_report',report_url:report_url,testid:testid},
				beforeSend: function(){
					jQuery('#gtmetrix-history-section').showLoading({'addClass': 'loading-indicator-bars'});
				},
				complete: function(){
				},
				success: function(response){
					jQuery('#gtmetrix-history-section').hideLoading();
					console.log(response.status);
					var json = jQuery.parseJSON(response);
					if(json.status){
						if(json.hasOwnProperty('report')){
							var a = document.createElement("a");
							a.href = 'data:application/pdf;base64,'+json.report;
							a.download = 'report_pdf-'+testid+".pdf"; //update for filename
							document.body.appendChild(a);
							a.click();
							// remove `a` following `Save As` dialog, 
							// `window` regains `focus`
							window.onfocus = function () {                     
								document.body.removeChild(a)
							}
						}
						jQuery.ajax({
							type: "POST",
							url: toolkit.ajax_url,
							dataType: "html",
							data: {action:'toolkit_performance_gtmetrix_scan_result'},
							beforeSend: function(){
								jQuery('.toolkit-gtmetrix-section').showLoading();
							},
							complete: function(){
							},
							success: function(response){
								jQuery('.toolkit-gtmetrix-section').hideLoading();
								jQuery('.toolkit-performance').html(response);
							},
							error: function(request, status, error) {
								alert(status);
							}
						});
					} else {
						jQuery('.toolkit-message').removeClass('updated').addClass('error').show().html('<p>'+json.message+'</p>').delay(delay).fadeOut(fadeSpeed);
						jQuery("html, body").animate({ scrollTop: 0 }, "slow");
					}
				},
				error: function(request, status, error) {
					alert(status);
				}
			});
		} else {
			jQuery('.toolkit-message').removeClass('updated').addClass('error').show().html('<p>Report URL missing.</p>').delay(delay).fadeOut(fadeSpeed);
			jQuery("html, body").animate({ scrollTop: 0 }, "slow");
		}
	});

	jQuery('body').on('click', '.toolkit-performance .toolkit-gtmetrix-section button', function() {
		var elem = jQuery(this);
		var scan_url = jQuery(".toolkit-gtmetrix-section input[name=scan_url]").val();
		if(validURL(scan_url) === false){
			jQuery('.toolkit-message').removeClass('updated').addClass('error').show().html('<p>Error: Please Enter a valid URL to test.</p>').delay(delay).fadeOut(fadeSpeed);
			jQuery("html, body").animate({ scrollTop: 0 }, "slow");
			return false;
		}
		var scan_location = jQuery(".toolkit-gtmetrix-section select[name=location] option:selected").val();
		var scan_browser = jQuery(".toolkit-gtmetrix-section select[name=browser] option:selected").val();
		var _nonce = jQuery(".toolkit-gtmetrix-section input[name=_nonce]").val();
			jQuery.ajax({
				type: "POST",
				url: toolkit.ajax_url,
				dataType: "json",
				data: {action:'toolkit_performance_gtmetrix_scan',scan_url:scan_url,scan_location:scan_location,scan_browser:scan_browser,_nonce:_nonce},
				beforeSend: function(){
					jQuery('.toolkit-gtmetrix-section').showLoading({'addClass': 'loading-indicator-bars',waitingText : 'Performing Scan Now, Please Wait'});
				},
				complete: function(){
				},
				success: function(response){
					jQuery('.toolkit-gtmetrix-section').hideLoading();
					jQuery('.toolkit-message').removeClass('error').addClass('updated').show().html('<p>'+response.message+'</p>').delay(delay).fadeOut(fadeSpeed);
					 setTimeout(function(){
						 window.location.hash = "#toolkit_performance_tool"; 
						 location.reload();
					 }, 2000);
				},
				error: function(request, status, error) {
					alert(status);
				}
			});
	});

	jQuery('body').on('change', '.toolkit-performance #gtmetrix-package-section select[name=gtmetrix-packages]', function() {
		var url = jQuery(this).val();
		if(url){
			// window.open(url,'_blank');
			window.location.href = url;
		}
	});
	
	//SAVE SETTING
	jQuery('#save-minification-settings').on('click', function() {
        var postData = {
            action: 'toolkit_server_setting_save'
        };
        postData.html_minify = ( jQuery('.minification-setting-section input[name=html_minify]').prop('checked') === true ) ? 'on' : 'off';
        postData.css_minify = ( jQuery('.minification-setting-section input[name=css_minify]').prop('checked') === true ) ? 'on' : 'off';
        postData.css_combine = ( jQuery('.minification-setting-section input[name=css_combine]').prop('checked') === true ) ? 'on' : 'off';
        postData.js_minify = ( jQuery('.minification-setting-section input[name=js_minify]').prop('checked') === true ) ? 'on' : 'off';
        postData.js_combine = ( jQuery('.minification-setting-section input[name=js_combine]').prop('checked') === true ) ? 'on' : 'off';
		var _nonce = jQuery(".minification-setting-section input[name=_nonce]").val();
		if(_nonce){
            postData._nonce = _nonce;
			jQuery.ajax({
				type: "POST",
				url: toolkit.ajax_url,
				dataType: "json",
				data: postData,
				beforeSend: function(){
					jQuery('.minification-setting-section').showLoading();
				},
				complete: function(){
				},
				success: function(response){
					jQuery('.minification-setting-section').hideLoading();
					if(response && response.status==1){
						jQuery('.toolkit-message').removeClass('updated').addClass('updated').show().html('<p>'+response.message+'</p>').delay(delay).fadeOut(fadeSpeed);
						jQuery("html, body").animate({ scrollTop: 0 }, "slow");
					} else {
						jQuery('.toolkit-message').removeClass('updated').addClass('error').show().html('<p>'+response.message+'</p>').delay(delay).fadeOut(fadeSpeed);
						jQuery("html, body").animate({ scrollTop: 0 }, "slow");
					}
				},
				error: function(request, status, error) {
					jQuery('.minification-setting-section').hideLoading();
				}
			});
		} else {
			jQuery('.toolkit-message').removeClass('updated').addClass('error').show().html('<p>Nonce missing</p>').delay(delay).fadeOut(fadeSpeed);
			jQuery("html, body").animate({ scrollTop: 0 }, "slow");
		}
	});

    //SAVE lazy load SETTING
    jQuery('#save-lazyload-setting').on('click', function() {
        var postData = {
            action: 'toolkit_lazyload_setting_save'
        };
        postData.image = ( jQuery('.lazyload-setting-section input[name=image]').prop('checked') === true ) ? 'on' : 'off';
        postData.iframe_video = ( jQuery('.lazyload-setting-section input[name=iframe_video]').prop('checked') === true ) ? 'on' : 'off';
        var _nonce = jQuery(".lazyload-setting-section input[name=_nonce]").val();
        if(_nonce){
            postData._nonce = _nonce;
            jQuery.ajax({
                type: "POST",
                url: toolkit.ajax_url,
                dataType: "json",
                data: postData,
                beforeSend: function(){
                    jQuery('.lazyload-setting-section').showLoading();
                },
                complete: function(){
                },
                success: function(response){
                    jQuery('.lazyload-setting-section').hideLoading();
                    if(response && response.status==1){
                        jQuery('.toolkit-message').removeClass('updated').addClass('updated').show().html('<p>'+response.message+'</p>').delay(delay).fadeOut(fadeSpeed);
                        jQuery("html, body").animate({ scrollTop: 0 }, "slow");
                    } else {
                        jQuery('.toolkit-message').removeClass('updated').addClass('error').show().html('<p>'+response.message+'</p>').delay(delay).fadeOut(fadeSpeed);
                        jQuery("html, body").animate({ scrollTop: 0 }, "slow");
                    }
                },
                error: function(request, status, error) {
                    jQuery('.lazyload-setting-section').hideLoading();
                }
            });
        } else {
            jQuery('.lazyload-setting-section').hideLoading();
            jQuery('.toolkit-message').removeClass('updated').addClass('error').show().html('<p>Nonce missing</p>').delay(delay).fadeOut(fadeSpeed);
            jQuery("html, body").animate({ scrollTop: 0 }, "slow");
        }
    });
    //save server tweaks
	jQuery('#save-server-tweaks').on('click', function() {
		var postData = {
			action: 'toolkit_server_setting_save'
		};
        postData.combine_gfonts = ( jQuery('input[name=toolkit_combine_gfonts]').prop('checked') === true ) ? 'on' : 'off';
        postData.encoding_header = ( jQuery('input[name=toolkit_encoding_header]').prop('checked') === true ) ? 'on' : 'off';
        postData.gzip_compression = ( jQuery('input[name=toolkit_gzip_compression]').prop('checked') === true ) ? 'on' : 'off';
        postData.keep_alive = ( jQuery('input[name=toolkit_keep_alive]').prop('checked') === true ) ? 'on' : 'off';
        postData.ninja_etags = ( jQuery('input[name=toolkit_ninja_etags]').prop('checked') === true ) ? 'on' : 'off';
        postData.leverage_caching = ( jQuery('input[name=toolkit_leverage_caching]').prop('checked') === true ) ? 'on' : 'off';
        postData.expire_headers = ( jQuery('input[name=toolkit_expire_headers]').prop('checked') === true ) ? 'on' : 'off';
		var _nonce = jQuery(".server-tweaks-section input[name=_nonce]").val();
		if(_nonce){
            postData._nonce = _nonce;
			jQuery.ajax({
				type: "POST",
				url: toolkit.ajax_url,
				dataType: "json",
				data: postData,
				beforeSend: function(){
					jQuery('.server-tweaks-section').showLoading();
				},
				complete: function(){
				},
				success: function(response){
					jQuery('.server-tweaks-section').hideLoading();
					if(response && response.status==1){
						jQuery('.toolkit-message').removeClass('updated').addClass('updated').show().html('<p>'+response.message+'</p>').delay(delay).fadeOut(fadeSpeed);
						jQuery("html, body").animate({ scrollTop: 0 }, "slow");
						if( ! response.apache ){
							alert('We detect that you are on an NGINX server. NGINX servers do not use htaccess and actually have most of these settings already enabled');
						}
					} else {
						jQuery('.toolkit-message').removeClass('updated').addClass('error').show().html('<p>'+response.message+'</p>').delay(delay).fadeOut(fadeSpeed);
						jQuery("html, body").animate({ scrollTop: 0 }, "slow");
					}
				},
				error: function(request, status, error) {
					jQuery('.server-tweaks-section').hideLoading();
				}
			});
		} else {
			jQuery('.toolkit-message').removeClass('updated').addClass('error').show().html('<p>Nonce missing</p>').delay(delay).fadeOut(fadeSpeed);
			jQuery("html, body").animate({ scrollTop: 0 }, "slow");
		}
	});
	 //disable wordpress widgets
	jQuery('#disable-wordpress-widgets').on('click', function() {		
		var _nonce = jQuery("#wordpress-panel").find("input[name=_nonce]").val();		
		var str = $('#wordpress_widgets_ds').serialize();
		if(_nonce){
            str._nonce = _nonce;
			jQuery.ajax({
				type: "POST",
				url: toolkit.ajax_url,
				dataType: "json",
				data: str,
				beforeSend: function(){
					jQuery('.widgets-tab-warpper').showLoading();
				},
				complete: function(){
				},
				success: function(response){
					jQuery('.widgets-tab-warpper').hideLoading();
					if(response && response.status==1){
						jQuery('.toolkit-message').removeClass('error').addClass('updated').show().html('<p>'+response.message+'</p>').delay(delay).fadeOut(fadeSpeed);
						jQuery("html, body").animate({ scrollTop: 0 }, "slow");
					} else {
						jQuery('.toolkit-message').removeClass('updated').addClass('error').show().html('<p>'+response.message+'</p>').delay(delay).fadeOut(fadeSpeed);
						jQuery("html, body").animate({ scrollTop: 0 }, "slow");
					}
				},
				error: function(request, status, error) {
					jQuery('.widgets-tab-warpper').hideLoading();
				}
			});
		} else {
			jQuery('.toolkit-message').removeClass('updated').addClass('error').show().html('<p>Nonce missing</p>').delay(delay).fadeOut(fadeSpeed);
			jQuery("html, body").animate({ scrollTop: 0 }, "slow");
		}
	});
	 //disable dashboard widgets
	jQuery('#disable-dashboard-widgets').on('click', function() {		
		var _nonce = jQuery("#dashboard-panel").find("input[name=_nonce]").val();		
		var str_dash = $('#dashboard_widgets_ds').serialize();
		if(_nonce){
            str_dash._nonce = _nonce;
			jQuery.ajax({
				type: "POST",
				url: toolkit.ajax_url,
				dataType: "json",
				data: str_dash,
				beforeSend: function(){
					jQuery('.widgets-tab-warpper').showLoading();
				},
				complete: function(){
				},
				success: function(response){
					jQuery('.widgets-tab-warpper').hideLoading();
					if(response && response.status==1){
						jQuery('.toolkit-message').removeClass('error').addClass('updated').show().html('<p>'+response.message+'</p>').delay(delay).fadeOut(fadeSpeed);
						jQuery("html, body").animate({ scrollTop: 0 }, "slow");
					} else {
						jQuery('.toolkit-message').removeClass('updated').addClass('error').show().html('<p>'+response.message+'</p>').delay(delay).fadeOut(fadeSpeed);
						jQuery("html, body").animate({ scrollTop: 0 }, "slow");
					}
				},
				error: function(request, status, error) {
					jQuery('.widgets-tab-warpper').hideLoading();
				}
			});
		} else {
			jQuery('.toolkit-message').removeClass('updated').addClass('error').show().html('<p>Nonce missing</p>').delay(delay).fadeOut(fadeSpeed);
			jQuery("html, body").animate({ scrollTop: 0 }, "slow");
		}
	});
	 //disable dashboard widgets
	jQuery('#disable-elementor-widgets').on('click', function() {		
		var _nonce = jQuery("#elementor-panel").find("input[name=_nonce]").val();	
		var str = $('#elementor-panel').find("input[type='checkbox']:checked").serialize() + '&action=disable_elementor_widgets';
		if(_nonce){
            str._nonce = _nonce;
			jQuery.ajax({
				type: "POST",
				url: toolkit.ajax_url,
				dataType: "json",
				data: str,
				beforeSend: function(){
					jQuery('.widgets-tab-warpper').showLoading();
				},
				complete: function(){
				},
				success: function(response){
					jQuery('.widgets-tab-warpper').hideLoading();
					if(response && response.status==1){
						jQuery('.toolkit-message').removeClass('error').addClass('updated').show().html('<p>'+response.message+'</p>').delay(delay).fadeOut(fadeSpeed);
						jQuery("html, body").animate({ scrollTop: 0 }, "slow");
					} else {
						jQuery('.toolkit-message').removeClass('updated').addClass('error').show().html('<p>'+response.message+'</p>').delay(delay).fadeOut(fadeSpeed);
						jQuery("html, body").animate({ scrollTop: 0 }, "slow");
					}
				},
				error: function(request, status, error) {
					jQuery('.widgets-tab-warpper').hideLoading();
				}
			});
		} else {
			jQuery('.toolkit-message').removeClass('updated').addClass('error').show().html('<p>Nonce missing</p>').delay(delay).fadeOut(fadeSpeed);
			jQuery("html, body").animate({ scrollTop: 0 }, "slow");
		}
	});
    //save theme disable settings
	var themeDisCheck = jQuery('#theme_disable_themeless');
	if( themeDisCheck.length > 0 ){
        themeDisCheck.on('click', function() {
            var themeless;
            if( themeDisCheck.prop('checked') === true ){
                themeless = 'yes';
            } else {
                themeless = 'no';
            }
            if(themeless){
                $.ajax({
                    type: "POST",
                    url: toolkit.ajax_url,
                    dataType: "json",
                    data: {
                        action: 'theme_disable_settings',
                        themeless: themeless
                    },
                    beforeSend: function(){
                        $('#theme-disable-themeless').showLoading();
                    },
                    complete: function(){
                    },
                    success: function(response){
                        $('#theme-disable-themeless').hideLoading();
                        if(response && response.status==1){
                            $('.toolkit-message').removeClass('updated').addClass('updated').show().html('<p>'+response.message+'</p>').delay(delay).fadeOut(fadeSpeed);
                            $("html, body").animate({ scrollTop: 0 }, "slow");
                        } else {
                            $('.toolkit-message').removeClass('updated').addClass('error').show().html('<p>'+response.message+'</p>').delay(delay).fadeOut(fadeSpeed);
                            $("html, body").animate({ scrollTop: 0 }, "slow");
                        }
                    },
                    error: function(request, status, error) {
                        $('#theme-disable-themeless').hideLoading();
                    }
                });
            } else {
                $('.toolkit-message').removeClass('updated').addClass('error').show().html('<p>Nonce missing</p>').delay(delay).fadeOut(fadeSpeed);
                $("html, body").animate({ scrollTop: 0 }, "slow");
            }
        });
	}

    //save unload options
    jQuery('#save-unload-options').on('click', function() {
        var postData = {
            action: 'toolkit_unload_options_save'
        };
        postData.disable_emojis = ( jQuery('input[name=toolkit_disable_emojis]').prop('checked') === true ) ? 'on' : 'off';
        postData.disable_gutenberg = ( jQuery('input[name=toolkit_disable_gutenberg]').prop('checked') === true ) ? 'on' : 'off';
        postData.disable_commentreply = ( jQuery('input[name=toolkit_disable_commentreply]').prop('checked') === true ) ? 'on' : 'off';
        postData.disable_jqmigrate = ( jQuery('input[name=toolkit_disable_jqmigrate]').prop('checked') === true ) ? 'on' : 'off';
        postData.disable_woohomeajax = ( jQuery('input[name=toolkit_disable_woohomeajax]').prop('checked') === true ) ? 'on' : 'off';
        if(postData){
            jQuery.ajax({
                type: "POST",
                url: toolkit.ajax_url,
                dataType: "json",
                data: postData,
                beforeSend: function(){
                    jQuery('.unload-options-section').showLoading();
                },
                complete: function(){
                },
                success: function(response){
                    jQuery('.unload-options-section').hideLoading();
                    if(response && response.status==1){
                        jQuery('.toolkit-message').removeClass('updated').addClass('updated').show().html('<p>'+response.message+'</p>').delay(delay).fadeOut(fadeSpeed);
                        jQuery("html, body").animate({ scrollTop: 0 }, "slow");
                    } else {
                        jQuery('.toolkit-message').removeClass('updated').addClass('error').show().html('<p>'+response.message+'</p>').delay(delay).fadeOut(fadeSpeed);
                        jQuery("html, body").animate({ scrollTop: 0 }, "slow");
                    }
                },
                error: function(request, status, error) {
                    jQuery('.unload-options-section').hideLoading();
                }
            });
        } else {
            jQuery('.toolkit-message').removeClass('updated').addClass('error').show().html('<p>Parameters missing.</p>').delay(delay).fadeOut(fadeSpeed);
            jQuery("html, body").animate({ scrollTop: 0 }, "slow");
        }
    });
	
    //save theme toolkit settings
	var saveToolkitBtn = jQuery('.save-toolkit');
	if( saveToolkitBtn.length > 0 ){
        saveToolkitBtn.on('click', function() {
            $.ajax({
                type: "POST",
                url: toolkit.ajax_url,
                dataType: "json",
                data: {
                    action: 'theme_toolkit_settings',
                    header_code: $('#theme_disable_header').val(),
                    footer_code: $('#theme_disable_footer').val(),
                    bodytag_code: $('#theme_disable_bodytag').val()
                },
                beforeSend: function(){
                    $('#theme-toolkit-themeless').showLoading();
                },
                complete: function(){
                },
                success: function(response){
                    $('#theme-toolkit-themeless').hideLoading();
                    if(response && response.status==1){
                        $('.toolkit-message').removeClass('updated').addClass('updated').show().html('<p>'+response.message+'</p>').delay(delay).fadeOut(fadeSpeed);
                        $("html, body").animate({ scrollTop: 0 }, "slow");
                    } else {
                        $('.toolkit-message').removeClass('updated').addClass('error').show().html('<p>'+response.message+'</p>').delay(delay).fadeOut(fadeSpeed);
                        $("html, body").animate({ scrollTop: 0 }, "slow");
                    }
                },
                error: function(request, status, error) {
                    $('#theme-toolkit-themeless').hideLoading();
                }
            });
        });
	}
    //toolkit tabs toggle
    var toolkitTabs = '.tab-nav ul li';
	if( jQuery(toolkitTabs).length > 0 ){
        jQuery(toolkitTabs).on('click', function () {
        	var tabsHolder = jQuery(this).closest('.tabs-holder');
            jQuery(toolkitTabs, tabsHolder).removeClass('active-tab');
            var tabId = jQuery(this).data('tabid');
            jQuery(this).addClass('active-tab');
            jQuery('.content-tab .single-tab', tabsHolder).hide();
            jQuery( '#' + tabId ).fadeIn('slow');
        });
        jQuery('.tab-nav ul li:eq(0)').trigger('click');
	}
	
	//CHECK UPDATE
	jQuery('body').on('click', '#toolkit-my-license #toolkit-license-verification #check-updates', function() {
		var _nonce = jQuery("#toolkit-license-verification input[name=_nonce]").val();
		if(_nonce){
			jQuery.ajax({
				type: "POST",
				url: toolkit.ajax_url,
				dataType: "json",
				data: {action:'toolkit_check_update',_nonce:_nonce},
				beforeSend: function(){
					jQuery('#toolkit-license-verification').showLoading();
				},
				complete: function(){
				},
				success: function(response){
					jQuery('#toolkit-license-verification').hideLoading();
					if(response && response.status==1){
						jQuery('.toolkit-message').removeClass('error').addClass('updated').show().html('<p>'+response.message+'</p>').delay(delay).fadeOut(fadeSpeed);
						 setTimeout(function(){
							 location.reload();
						 }, 2000);
					} else {
						jQuery('.toolkit-message').removeClass('updated').addClass('error').show().html('<p>'+response.message+'</p>').delay(delay).fadeOut(fadeSpeed);
						jQuery("html, body").animate({ scrollTop: 0 }, "slow");
					}
				},
				error: function(request, status, error) {
					jQuery('#toolkit-license-verification').hideLoading();
					alert(status);
				}
			});
		} else {
			jQuery('.toolkit-message').removeClass('updated').addClass('error').show().html('<p>Enter License Key</p>').delay(delay).fadeOut(fadeSpeed);
			jQuery("html, body").animate({ scrollTop: 0 }, "slow");
		}
	});
	//KEY VERIFY
	jQuery('body').on('click', '#toolkit-my-license #toolkit-license-verification #key-verify', function() {
		var license_key = jQuery("#toolkit-license-verification input[name=template-key]").val();
		/*var prev_license_key = jQuery("#toolkit-license-verification input[name=template-key]").attr('data-license');
		if(prev_license_key!=''){
			var license_key = prev_license_key;
		}*/
		var _nonce = jQuery("#toolkit-license-verification input[name=_nonce]").val();
		if(license_key && _nonce){
			jQuery.ajax({
				type: "POST",
				url: toolkit.ajax_url,
				dataType: "json",
				data: {action:'toolkit_license_key_verify',_nonce:_nonce,license_key:license_key},
				beforeSend: function(){
					jQuery('#toolkit-license-verification').showLoading();
				},
				complete: function(){
				},
				success: function(response){
					jQuery('#toolkit-license-verification').hideLoading();
					if(response && response.status==1){
						jQuery('.toolkit-message').removeClass('error').addClass('updated').show().html('<p>'+response.message+'</p>').delay(delay).fadeOut(fadeSpeed);
						 setTimeout(function(){
							 location.reload();
						 }, 2000);
					} else {
						jQuery('.toolkit-message').removeClass('updated').addClass('error').show().html('<p>'+response.message+'</p>').delay(delay).fadeOut(fadeSpeed);
						jQuery("html, body").animate({ scrollTop: 0 }, "slow");
					}
				},
				error: function(request, status, error) {
					jQuery('#toolkit-license-verification').hideLoading();
					alert(status);
				}
			});
		} else {
			jQuery('.toolkit-message').removeClass('updated').addClass('error').show().html('<p>Enter License Key</p>').delay(delay).fadeOut(fadeSpeed);
			jQuery("html, body").animate({ scrollTop: 0 }, "slow");
		}
		
	});
	//KEY DEACTIVATE
	jQuery('body').on('click', '#key-deactivate', function() {
		var license_key = jQuery("#toolkit-license-verification input[name=template-key]").val();
		var _nonce = jQuery("#toolkit-license-verification input[name=_nonce]").val();
		if(license_key && _nonce){
			jQuery.ajax({
				type: "POST",
				url: toolkit.ajax_url,
				dataType: "json",
				data: {action:'toolkit_deactivate_license',_nonce:_nonce,license_key:license_key},
				beforeSend: function(){
					jQuery('#toolkit-license-verification').showLoading();
				},
				complete: function(){
				},
				success: function(response){
					jQuery('#toolkit-license-verification').hideLoading();
					if(response && response.status==1){
						jQuery('.toolkit-message').removeClass('error').addClass('updated').show().html('<p>'+response.message+'</p>').delay(delay).fadeOut(fadeSpeed);
						 setTimeout(function(){
							 location.reload();
						 }, 2000);
					} else {
						jQuery('.toolkit-message').removeClass('updated').addClass('error').show().html('<p>'+response.message+'</p>').delay(delay).fadeOut(fadeSpeed);
						jQuery("html, body").animate({ scrollTop: 0 }, "slow");
					}
				},
				error: function(request, status, error) {
					jQuery('#toolkit-license-verification').hideLoading();
					alert(status);
				}
			});
		} else {
			jQuery('.toolkit-message').removeClass('updated').addClass('error').show().html('<p>Enter License Key</p>').delay(delay).fadeOut(fadeSpeed);
			jQuery("html, body").animate({ scrollTop: 0 }, "slow");
		}
		
	});

	//DEACTICATE SITE
	jQuery('body').on('click', '#toolkit-my-license .activated-site-section .remove-site, #toolkit-my-license .activated-site-section .hide-site', function() {
		var site_url = jQuery(this).data('site_url');
		var type = jQuery(this).data('type');
		var hide_syncer = jQuery(this).data('hide_syncer');
		var _nonce = jQuery("#toolkit-my-license input[name=_nonce]").val();
		if(site_url &&_nonce){
			jQuery.ajax({
				type: "POST",
				url: toolkit.ajax_url,
				dataType: "json",
				data: {action:'toolkit_site_deactivate',_nonce:_nonce,site_url:site_url,type:type,hide_syncer:hide_syncer},
				beforeSend: function(){
					jQuery('.activated-site-section').showLoading();
				},
				complete: function(){
				},
				success: function(response){
					jQuery('.activated-site-section').hideLoading();
					if(response && response.status==1){
						jQuery('.toolkit-message').removeClass('error').addClass('updated').show().html('<p>'+response.message+'</p>').delay(delay).fadeOut(fadeSpeed);
						 setTimeout(function(){
							 location.reload();
						 }, 2000);
					} else {
						jQuery('.toolkit-message').removeClass('updated').addClass('error').show().html('<p>'+response.message+'</p>').delay(delay).fadeOut(fadeSpeed);
						jQuery("html, body").animate({ scrollTop: 0 }, "slow");
					}
				},
				error: function(request, status, error) {
					jQuery('#toolkit-license-verification').hideLoading();
					alert(status);
				}
			});
		} else {
			jQuery('.toolkit-message').removeClass('updated').addClass('error').show().html('<p>Enter License Key</p>').delay(delay).fadeOut(fadeSpeed);
			jQuery("html, body").animate({ scrollTop: 0 }, "slow");
		}
	});

	function validURL(myURL) {
		return /^(http(s)?:\/\/)?(www\.)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/.test(myURL);
	}
});