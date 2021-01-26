<div class="betterbuilder-stock-image">
	<div id="better-stockimage-preview"></div>

	<input type="text" class="fusion-hide-from-atts" id="stock-image-searcher" name="stock-image-searcher" placeholder="Search free high-resolution photos..."/>

	<div id="better-stockimage-wrap"></div>

	<input 
		type="hidden" 
		id="{{ param.param_name }}" 
		name="{{ param.param_name }}" 
		class="better-stockimage" 
		value="{{ option_value }}"
	>
</div>
<#
	jQuery( 'body' ).trigger( 'stockimage-param-loaded', [ param.param_name ] );
#>