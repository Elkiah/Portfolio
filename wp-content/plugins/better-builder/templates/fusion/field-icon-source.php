<div class='better_iconsource'>
	<select id="{{ param.param_name }}" name="{{ param.param_name }}">
		<# _.each( param.value, function( name, value ) { #>
			<option value="{{ value }}" {{ typeof( option_value ) !== 'undefined' && value === option_value ?  ' selected="selected"' : '' }} >{{ name }}</option>
		<# }); #>
	</select>
</div>
