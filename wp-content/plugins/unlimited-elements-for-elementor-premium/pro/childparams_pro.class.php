<?php
/**
* @package Unlimited Elements
* @author UniteCMS.net
* @copyright (C) 2017 Unite CMS, All Rights Reserved. 
* @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

class UniteCreatorChildParamsPro extends UniteCreatorAddonViewChildParams{
		
	
	
	/**
	 * add code examples params
	 * override the free functionality
	 */
	public function getCodeExamplesParams_php($arrParams){
	
		
			$key = "Run PHP Function";
			$text = "
{# run any wp action, and any custom PHP function. Use add_action to create the actions. \n The function support up to 3 custom params #}
\n
{{ do_action('some_action') }}
{{ do_action('some_action','param1','param2','param3') }}
";
		
			$arrParams[] = $this->createChildParam_code($key, $text);
		
			$key = "Get Data From PHP";
			$text = "
{# apply any WordPress filters, and any custom PHP function. Use apply_filters to create the actions. \n The function support up to 2 custom params #}
\n
{% set myValue = apply_filters('my_filter') }}
{% set myValue = apply_filters('my_filter',value, param2, param3) }}

";
			$arrParams[] = $this->createChildParam_code($key, $text);
			
		
		return($arrParams);
	}
	
	
}