<?php
function my_license_settings_display(){
	$obj = new Lazy_load_Settings();
	global $wpdb;
	$offset = !empty($_REQUEST['page_no']) ? (($_REQUEST['page_no'] - 1) * $obj->limit) : 0;
	$args = array('post_type'=>'elementor_library','post_status' => 'publish');
	$templateArray = get_posts( $args );
	$websiteTotalRecord = $wpdb->get_var( "SELECT count(id) FROM {$wpdb->prefix}toolkit_license_log WHERE license_key='".$obj->toolkit_license_key."'" );
	$licenceDetail = get_option('toolkit_license_details');
	$licenceOtherDetail = get_option('toolkit_other_detail');
	$license = get_option( 'toolkit_license_key' );
	$status  = get_option( 'toolkit_license_status' );
	if(!empty($licenceOtherDetail['next_payment_date'])){
		$renewalDate = date('F jS, Y',strtotime($licenceOtherDetail['next_payment_date']));
	} elseif(!empty($licenceDetail['expiration_date'])){
		$renewalDate = date('F jS, Y',strtotime($licenceDetail['expiration_date']));
	} else {
		$renewalDate = 'N/A';
	}
	$getHQMySettings = get_option('toolkit_hq_my_settings');
	$getHQVersionMsg = (get_option('toolkit_hq_my_version_msg'));
	if(!empty($obj->toolkit_license_key)){
		$maskedLicenseKey = $obj->toolkit_license_key;
	}
	$html = '';
	$html .= '
	<div class="wrap" id="toolkit-my-license">
		<div class="col-md-12" style="">';
	
// LEFT SECTION 

	$html .= '<div class="col-md-5">';
	
// My License Panel
	
	$html .= '<div class="col-md-10">';
	if( $status !== false && $status == 'valid' ) {
	$html .= '<div class="row" style="background:#F9F9F9;margin-right;">
			<div class="col-md-12" id="toolkit-license-verification">
				 <h3>MY TOOLKIT LICENSE</h3>
					<div class="form-group">
						  <label for="sel1">License Key</label>
						  <input type="hidden" name="_nonce" value="'.wp_create_nonce($obj->nonce_key).'" />
						  <span style="color:#069825;vertical-align: bottom;">active</span>
						  <input class="form-control" name="template-key" style="border:2px solid #069825!important" data-license="'.(!empty($obj->toolkit_license_key) ? $obj->toolkit_license_key : '').'" value="'.($obj->toolkit_license_key ? $maskedLicenseKey : '').'"/>
					</div>
					<div class="form-group pull-left">
						<button type="button" class="button toolkit-btn" id="key-deactivate">Deactivate License</button>
					</div>';
	} else {
	$html .= '<div class="row" style="background:#F9F9F9;margin-right;">
			<div class="col-md-12" id="toolkit-license-verification">
				 <h3>MY TOOLKIT LICENSE</h3>
					<div class="form-group">
						  <label for="sel1">License Key</label>
						  <input type="hidden" name="_nonce" value="'.wp_create_nonce($obj->nonce_key).'" />
						  <input class="form-control" name="template-key" data-license="'.(!empty($obj->toolkit_license_key) ? $obj->toolkit_license_key : '').'" value="'.($obj->toolkit_license_key ? $maskedLicenseKey : '').'"/>
					</div>
					<div class="form-group pull-left">
						<button type="button" class="button toolkit-btn" id="key-verify">Activate & Sync License</button>
					</div>';	
	}					
$html .=			'</div>
				</div>';
	$html .= '</div>';
	
// MY LICENSE CONTENT AREA

	$html .= '</div>';

	$html.= '</div>
	</div>';
	return $html;
}
