<?php

if (!class_exists('Toolkit_Elementor_Syncer_Auth')):
class Toolkit_Elementor_Syncer_Auth {
  /**
   * Generate hash base on the license information
   *
   */
  public function get_license_hash()
  {
    // Get the Authentication code
    // Basically md5 hash of the license key and available sites
    $key = get_option('toolkit_license_key');

    // Get all the site's host name
    $parts = [
      $key,
    ];

	$parts[] = gmdate('Y-m-d');

	/*
	$licenseLog = get_toolkit_license_log();

	// Double check if the template sites is there
	if (!empty($licenseLog)) {
		foreach ($licenseLog as $site) {
		  $parts[] = $site['domain'];
		}
	}
	 */

    return md5(implode(',', $parts));
  }

  /**
   * Generate auth code for the caller
   *
   */
  public function generate_auth_code()
  {
    $license_hash = $this->get_license_hash();
    $parsed_url = parse_url(site_url());

    // Hash of the current site host plus the license hash
    //return md5($parsed_url['host'] . $license_hash);
    return md5($license_hash);
  }

  /**
   * Check Auth code agaist caller's auth token
   *
   */
  public function check_auth_token()
  {
    // Get the incoming token
    $incoming_token = $_REQUEST['token'];

    // Hash of the current site host plus the license hash
    $license_hash = $this->get_license_hash();

		// Figure out caller origin
		if (array_key_exists('HTTP_ORIGIN', $_SERVER)) {
			$origin = $_SERVER['HTTP_ORIGIN'];
		} else if (array_key_exists('HTTP_REFERER', $_SERVER)) {
			$origin = $_SERVER['HTTP_REFERER'];
		} else {
			$origin = $_SERVER['REMOTE_ADDR'];
		}

		// Use the that to hash with license hash
    $parsed_url = parse_url($origin);
    //$local_token = md5($parsed_url['host'] . $license_hash);
    $local_token = md5($license_hash);

    // Check if the incoming hash is the same is the local generated hash
    if ($incoming_token === $local_token) {
      return true;
    } else {
      return new \WP_Error('denied', __('Sorry invalid token'), [
        'parsed_url' => $parsed_url,
        'license_hash' => $license_hash,
        'incoming_token' => $incoming_token,
      ]);
    }
  }
}
endif;
