jQuery(document).ready(function($) {
    var el, newPoint;
    var rangeSlider = $('.better-ui-form-range-slider');
    var rangeInput = $('.better-ui-form-range-input');
    var rangeHelper = $('.better-ui-form-range-helper');

    rangeSlider.on('input change', function() {
        // Cache this for efficiency
        el = $(this);

        // Measure width of range input
        width = el.width();

        var min = el.attr("min");
        min = min === undefined ? 0 : min;
        var max = el.attr("max");
        max = max === undefined ? 100 : max;
        var value = parseInt(el.val());
        value = value > min ? el.val() : min;

        // Figure out placement percentage between left and right of input
        newPoint = (value - min) / (max - min) * 100 + "%";
        
        el.siblings('.better-ui-form-range-bg').css({ width: newPoint });

        el.parents(rangeHelper).siblings(rangeInput).val(value == 0 || value == 1 || value === '' || value === undefined ? '' : value);
    }).trigger('change');
    
    rangeInput.on('keyup', function() {
        el = $(this);

        // Measure width of range input
        width = el.siblings(rangeHelper).children(rangeSlider).width();

        var min = el.attr("min");
        min = min === undefined ? 0 : min;
        var max = el.attr("max");
        max = max === undefined ? 100 : max;
        var value = parseInt(el.val());
        value = value > min ? el.val() : min;

        // Figure out placement percentage between left and right of input
        newPoint = (value - min) / (max - min) * 100 + "%";

        el.siblings(rangeHelper).children('.better-ui-form-range-bg').css({ width: newPoint });

        el.siblings(rangeHelper).children(rangeSlider).val(value);
    });
});