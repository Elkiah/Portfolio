<?php
// Syncer template
function syncer_template_settings_display() {
	$license_key = trim( get_option( 'toolkit_license_key' ) );
	// Get the license key and active site info
	$availableSites = get_toolkit_available_sites();
	$keyVerified = is_toolkit_for_elementor_activated();
	// Check if there is license key or not
	if ($keyVerified) {
		// Get auth
		$auth = new \Toolkit_Elementor_Syncer_Auth();
		$token = $auth->generate_auth_code();
		// Get the syncer template
		ob_start();
		include_once(__DIR__ . '/syncer.php');
		$syncerTemplate = ob_get_contents();
		ob_end_clean();
	} else {
		$syncerTemplate = 'Oops, looks like you do not have a active license yet, please activate your license first in My License';
	}

	// Output the template
	ob_start();
	?>
	<div class="wrap toolkit-my-templates elementor-syncer">
		<div class="col-md-12" style="">
			<div class="row" style="background:#F9F9F9;padding:10px;">
				<?php echo $syncerTemplate ?>
			</div>
		</div>
	</div>
	<?php
	// Get the content
	$html = ob_get_contents();
	ob_end_clean();

	return $html;
}
