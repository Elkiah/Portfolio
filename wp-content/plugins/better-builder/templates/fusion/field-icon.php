<div class="better_icontype">
	<select id="{{ param.param_name }}" name="{{ param.param_name }}" data-option="{{ option_value }}">
		<option value="">Select Icon</option>
	</select>
</div>
<#
	jQuery( 'body' ).trigger( 'icon-param-loaded', [ param.param_name ] );
#>
