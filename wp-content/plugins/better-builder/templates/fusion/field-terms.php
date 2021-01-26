<# var choice = option_value; #>
<# if ( 'undefined' !== typeof choice && '' !== choice && null !== choice ) { #>
	<# var choices = ( jQuery.isArray( choice ) ) ? choice : choice.split( ',' ); #>
<# } else { #>
	<# var choices = ''; #>
<# } #>
<select id="{{ param.param_name }}" name="{{ param.param_name }}" multiple="multiple" class="fusion-input better-multi-input" data-selected="{{ choice }}" style="height: auto !important; background: none; border: 1px solid #d9d9d9 !important; padding: 2px !important;">
	<# _.each( param.value, function( name, value ) { #>
		<# var selected = ( jQuery.inArray( value, choices ) > -1 ) ? ' selected="selected"' : ''; #>
		<option value="{{ value }}"{{ selected }} >{{ name }}</option>
	<# } ); #>
</select>

