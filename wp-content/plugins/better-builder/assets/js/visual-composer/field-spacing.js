jQuery(function($) {
	$(document).ready(function() {
		$('.vc-spacing-parameter').each(function() {
			var $field = $(this);
			var $valueField = $('input[type="hidden"]', $field);
			var $inputFields = $('input[type="text"]', $field);

			$inputFields.keyup(function() {
				var value = '';

				$inputFields.each(function() {
					var fieldValue = $(this).val();

					if (fieldValue == '' || fieldValue == 0) {
						value += '0 ';
					} else if ($.isNumeric(fieldValue)) {
						value += fieldValue + 'px ';
					} else {
						value += fieldValue + ' ';
					}
				});

				$valueField.val(value.trim());
			});						
		});
	});
});