<?php

if (!class_exists('Toolkit_Elementor_Syncer')):
class Toolkit_Elementor_Syncer extends \Elementor\TemplateLibrary\Source_Local {
  // The auth class
  private $auth = null;

  private $endpoint = '/wp-json/toolkit/v1/syncer';

  /**
   * Constructor
   *
   */
  public function __construct()
  {
    // Register the api etc
    add_action('rest_api_init', [$this, 'register_rest_api']);
  }

  /**
   * Register rest api
   *
   */
  public function register_rest_api()
  {
    // Get auth
    $this->auth = new \Toolkit_Elementor_Syncer_Auth();

    // Get template
    register_rest_route('toolkit/v1', '/syncer/templates', [
      'methods' => 'GET',
      'callback' => [$this, 'get_templates'],
      'permission_callback' => [$this->auth, 'check_auth_token'],
    ]);

    register_rest_route('toolkit/v1', '/syncer/template/(?P<id>\d+)', [
      'methods' => 'GET',
      'callback' => [$this, 'get_template'],
      'permission_callback' => [$this->auth, 'check_auth_token'],
    ]);

    // Get remote template
    register_rest_route('toolkit/v1', '/syncer/remote/templates', [
      'methods' => 'GET',
      'callback' => [$this, 'get_remote_templates'],
      'permission_callback' => [$this->auth, 'check_auth_token'],
    ]);

    register_rest_route('toolkit/v1', '/syncer/remote/template/(?P<id>\d+)', [
      'methods' => 'GET',
      'callback' => [$this, 'download_remote_template'],
      'permission_callback' => [$this->auth, 'check_auth_token'],
    ]);
  }

  /**
   * Check remote calls
   *
   */
  private function remote_call_checks($raw_data)
  {
    // Check for the auth token
    $result = $this->auth->check_auth_token();
    if (is_wp_error($result)) {
      return new \WP_Error('invalid_token', 'Sorry invalid token for remote calls', $result);
    }

    // Check if there is a site or not
    // maybe to some validation on the site url as well
    if (empty($raw_data['site'])) {
      return new \WP_Error('invalid_site', 'Sorry no site for remote calls');
    }

    return true;
  }

  /**
   * Get remote templates
   *
   */
  public function get_remote_templates($raw_data)
  {
    // Check the incoming data
    $check = $this->remote_call_checks($raw_data);
    if (is_wp_error($check)) {
      return $check;
    }

    // Make a call to remote site
    // Get the token
    $token = $this->auth->generate_auth_code();

    $url = $raw_data['site'] . $this->endpoint . "/templates?token=$token";
    //$response = wp_remote_get($url);
    $response = wp_remote_get($url, [
      'timeout' => 20,
      'blocking' => true,
      'cookie' => $_COOKIE,
      'sslverify' => false,
      'headers' => [
        'HTTP_ORIGIN' => site_url(),
        'HTTP_REFERER' => site_url(),
      ],
    ]);

    // Check the result
    if (is_wp_error($response)) {
      return new WP_Error('error', 'error response from remote', [
        'response' => $response,
        'url' => $url,
        'site' => $raw_data['site'],
        'siteUrl' => site_url(),
      ]);
    }

    if ($response['response']['code'] === 200) {
      return json_decode($response['body']);
    } else {
	  return new WP_Error('error', 'invalid response from remote', [
        'response' => $response,
        'url' => $url,
        'site' => $raw_data['site'],
        'siteUrl' => site_url(),
		]);
    }
  }

  /**
   * Get remote template
   *
   */
  public function download_remote_template($raw_data)
  {
	// This will allow it to import pictures
	include_once( ABSPATH . 'wp-admin/includes/image.php' );

    // Check the incoming data
    $check = $this->remote_call_checks($raw_data);
    if (is_wp_error($check)) {
      return $check;
    }

    // Make a call to remote site
    // Get the token
    $token = $this->auth->generate_auth_code();

	$template_id = $raw_data['id'];
    $url = $raw_data['site'] . $this->endpoint . "/template/$template_id?token=$token";
    //$response = wp_remote_get($url);
    $response = wp_remote_get($url, [
      'timeout' => 20,
      'blocking' => true,
      'cookie' => $_COOKIE,
      'sslverify' => false,
      'headers' => [
        'HTTP_ORIGIN' => site_url(),
        'HTTP_REFERER' => site_url(),
      ],
    ]);

    // Check the result
    if (is_wp_error($response)) {
      return new WP_Error('error', 'error response from remote', [
        'response' => $response,
        'url' => $url,
        'site' => $raw_data['site'],
        'siteUrl' => site_url(),
      ]);
    }

    if ($response['response']['code'] === 200) {
      $data = json_decode($response['body'], true);

      // Change the title, so its easy to differentiate
      //$data['title'] .= ' - from '. $raw_data['site'];

      // Save the template
      return $this->save_template($data);
    } else {
	  return new WP_Error('error', 'invalid response from remote', [
        'response' => $response,
        'url' => $url,
        'site' => $raw_data['site'],
        'siteUrl' => site_url(),
		]);
    }
  }

  /**
   * Get template
   */
  public function get_Templates($raw_data)
  {
    // Use the elementor source to get templates
	  $templates = $this->get_items([
		  'type' =>[
			  'page',
			  'section',
			  'loop',
			  'widget',
			  'popup',
			  'header',
			  'footer',
			  'single',
			  'archive',
			  'product',
			  'product-archive',
		  ],
	  ]);

    return $templates;
  }

  /**
   * Get the template
   */
  public function get_template($raw_data)
  {
    // First get the template
    $template_id = $raw_data['id'];
		$template_data = $this->get_data([
			'template_id' => $template_id,
		]);

		if (empty($template_data['content'])) {
			return new \WP_Error('empty_template', 'The template is empty');
		}

		$template_data['content'] = $this->process_export_import_content($template_data['content'], 'on_export');

		if (get_post_meta($template_id, '_elementor_page_settings', true)) {
			$page = \Elementor\Core\Settings\Manager::get_settings_managers('page')->get_model($template_id);

			$page_settings_data = $this->process_element_export_import_content($page, 'on_export');

			if (!empty($page_settings_data['settings'])) {
				$template_data['page_settings'] = $page_settings_data['settings'];
			}
		}

    // The export data
		$export_data = [
			'version' => \Elementor\DB::DB_VERSION,
			'title' => get_the_title($template_id),
			'type' => self::get_template_type($template_id),
		];
		$export_data += $template_data;

    return $export_data;
  }

  /**
   *
   * Get the current user to admin
   * Because some function in elementor need privileages
   *
   */
  private function setAdminUser()
  {
      // Get a list of users
      $users = get_users([
          'role' => 'administrator',
          'number' => 1,
          'fields' => ['id'],
      ]);

      if (!empty($users)) {
          wp_set_current_user($users[0]->id);
      }
  }

  /**
   * Save template
   *
   */
  protected function save_template($data)
  {
        if (empty($data)) {
            return new \WP_Error('no_data', 'Sorry no template data');
        }

        // Check content
		$content = $data['content'];
		if (!is_array($content)) {
			return new \WP_Error('no_content', 'Template does not have content');
		}

        // Some of the functino wil need privilages
      $this->setAdminUser();
    // Import content
		$content = $this->process_export_import_content($content, 'on_import');

    // Get page setting
		$page_settings = [];
		if (!empty($data['page_settings'])) {
			$page = new \Elementor\Core\Settings\Page\Model([
				'id' => 0,
				'settings' => $data['page_settings'],
			]);

			$page_settings_data = $this->process_element_export_import_content($page, 'on_import');
			if (!empty($page_settings_data['settings'])) {
				$page_settings = $page_settings_data['settings'];
			}
		}

		// Different version of elementor use different save item function
		if (version_compare(ELEMENTOR_VERSION, '2.6.100') === -1) {
			// If its less than 2.7.0 use the old save_item
			$template_id = $this->save_item([
				'content' => $content,
				'title' => $data['title'],
				'type' => $data['type'],
				'page_settings' => $page_settings,
			]);
		} else {
			$template_id = $this->save_item27([
				'content' => $content,
				'title' => $data['title'],
				'type' => $data['type'],
				'page_settings' => $page_settings,
			]);
		}

		if (is_wp_error($template_id)) {
			return $template_id;
		}

		return $this->get_item($template_id);
  }

	/**
	 * Save local template.
	 *
	 * Save new or update existing template on the database.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $template_data Local template data.
	 *
	 * @return \WP_Error|int The ID of the saved/updated template, `WP_Error` otherwise.
	 */
	public function save_item27($template_data) {
		$defaults = [
			'title' => __('(no title)', 'elementor'),
			'page_settings' => [],
			'status' => current_user_can('publish_posts') ? 'publish' : 'pending',
		];
		$template_data = wp_parse_args($template_data, $defaults);
		$document = \Elementor\Plugin::$instance->documents->create(
			$template_data['type'],
			[
				'post_title' => $template_data['title'],
				'post_status' => $template_data['status'],
				'post_type' => self::CPT,
			]
		);
		if (is_wp_error($document)) {
			/**
			 * @var \WP_Error $document
			 */
			return $document;
		}
		$document->save([
			'elements' => $template_data['content'],
			'settings' => $template_data['page_settings'],
		]);
		$template_id = $document->get_main_id();
		/**
		 * After template library save.
		 *
		 * Fires after Elementor template library was saved.
		 *
		 * @since 1.0.1
		 *
		 * @param int   $template_id   The ID of the template.
		 * @param array $template_data The template data.
		 */
		do_action( 'elementor/template-library/after_save_template', $template_id, $template_data );
		/**
		 * After template library update.
		 *
		 * Fires after Elementor template library was updated.
		 *
		 * @since 1.0.1
		 *
		 * @param int   $template_id   The ID of the template.
		 * @param array $template_data The template data.
		 */
		do_action( 'elementor/template-library/after_update_template', $template_id, $template_data );
		return $template_id;
	}

  /**
   * Override the original save_item
   *
   */
	public function save_item($template_data) {
		$type = \Elementor\Plugin::$instance->documents->get_document_type($template_data['type'], false);

		if (!$type) {
			return new \WP_Error('save_error', sprintf('Invalid template type "%s".', $template_data['type']));
		}

		$template_id = wp_insert_post([
			'post_title' => !empty($template_data['title']) ? $template_data['title'] : __('(no title)', 'elementor'),
			'post_status' => 'publish',
			'post_type' => self::CPT,
		]);

		if (is_wp_error($template_id)) {
			return $template_id;
		}

		\Elementor\Plugin::$instance->db->set_is_elementor_page($template_id);

		$this->save_item_type($template_id, $template_data['type']);

		\Elementor\Plugin::$instance->db->save_editor($template_id, $template_data['content']);

		if (!empty($template_data['page_settings'])) {
			\Elementor\Core\Settings\Manager::get_settings_managers('page')->save_settings($template_data['page_settings'], $template_id);
		}

		/**
		 * After template library save.
		 *
		 * Fires after Elementor template library was saved.
		 *
		 * @since 1.0.1
		 *
		 * @param int   $template_id   The ID of the template.
		 * @param array $template_data The template data.
		 */
		do_action('elementor/template-library/after_save_template', $template_id, $template_data);

		/**
		 * After template library update.
		 *
		 * Fires after Elementor template library was updated.
		 *
		 * @since 1.0.1
		 *
		 * @param int   $template_id   The ID of the template.
		 * @param array $template_data The template data.
		 */
		do_action('elementor/template-library/after_update_template', $template_id, $template_data);

		return $template_id;
	}

  /**
   * Override the original save_item_type
   *
   */
	private function save_item_type($post_id, $type) {
		update_post_meta($post_id, \Elementor\Core\Base\Document::TYPE_META_KEY, $type);

		wp_set_object_terms($post_id, $type, self::TAXONOMY_TYPE_SLUG);
	}
}
new Toolkit_Elementor_Syncer();
endif;
