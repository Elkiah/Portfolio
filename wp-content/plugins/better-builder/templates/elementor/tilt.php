<#
var styles = 'position: relative; ';
var content_styles = 'position: absolute;width: 100%; ';

var inner = 'better_tilt_inner';
var outer = 'better_tilt_outer';

if ( settings.background_type == 'color' ) {
    styles += 'background-color: ' + settings.bg_color + '; ';
}

if ( settings.background_type == 'linear' ) {
    styles += 'background-color: ' + settings.background_gradient_start + '; ';
    styles += 'background-image: linear-gradient(' + settings.background_gradient_angle.size + 'deg, ' + settings.background_gradient_start + ' 0%, ' + settings.background_gradient_end + ' 100%); ';
}

if ( settings.background_type == 'radial' ) {
    styles += 'background-color: ' + settings.background_gradient_start + '; ';
    styles += 'background-image: radial-gradient(circle at center, ' + settings.background_gradient_start + ' 0%, ' + settings.background_gradient_end + ' 100%); ';
}

if ( settings.background_type != 'image' ) {
    inner = 'better_tilt_inner_resize';
    outer = 'better_tilt_outer_resize';
    content_styles = 'position: relative;width: 100%; ';
}

if ( settings.content_depth !== '' ) {
    content_styles += '-webkit-transform: translateZ(' + settings.content_depth.size + 'px);';
    content_styles += 'transform: translateZ(' + settings.content_depth.size + 'px);';
}

var transition = settings.transition == 'on' ? 'true' : 'false';
var reset = settings.reset == 'on' ? 'true' : 'false';
var glare = settings.glare == 'on' ? 'true' : 'false';
var reverse = settings.reverse == 'on' ? 'true' : 'false';

var html = '<div id="' + settings._element_id + '" class="better ' + settings._element_classes + '" data-tilt="true"  data-tilt-reverse="' + reverse + '" data-tilt-max="' + settings.max.size + '" data-tilt-perspective="' + settings.perspective.size + '" data-tilt-scale="' + settings.scale.size + '" data-tilt-speed="' + settings.speed.size + '" data-tilt-transition="' + transition + '" data-tilt-axis="' + settings.axis + '" data-tilt-reset="' + reset + '" data-tilt-glare="' + glare + '" data-tilt-max-glare="' + settings.max_glare.size + '" style="transform-style: preserve-3d;">';

    html += '<div style="' + styles + '" class="' + outer + '">';

        var hasContent = !! ( settings.content );

        if ( hasContent ) {
            html += '<div class="' + inner + '" style="' + content_styles + '">' + settings.content + '</div>';
        }

        if ( settings.background_type == 'image' && settings.bg_image !== '' ) {

            var image = {
                id: settings.bg_image.id,
                url: settings.bg_image.url,
                size: settings.bg_image_size,
                model: view.getEditModel()
            };

            var image_url = elementor.imagesManager.getImageUrl( image );

            html += '<img src="' + image_url + '" alt="" />';

        }

    html += '</div>';

html += '</div>';

if ( settings.shadow == 'yes' ) { #>
    <style>
        #better_tilt:after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background-color: #333;
            box-shadow: 0 20px 70px -10px rgba(51, 51, 51, 0.7), 0 50px 100px 0 rgba(51, 51, 51, 0.2);
            z-index: -1;
            -webkit-transform: translateZ(-50px);
            transform: translateZ(-50px);
            -webkit-transition: .3s;
            transition: .3s;
        }
    </style>
<# }

print( html );
#>