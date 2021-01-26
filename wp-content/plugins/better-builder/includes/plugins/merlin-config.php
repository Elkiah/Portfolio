<?php
/**
 * Merlin WP configuration file.
 *
 * @package @@pkg.name
 * @version @@pkg.version
 * @author  @@pkg.author
 * @license @@pkg.license
 */

if ( ! class_exists( 'Merlin' ) ) {
	return;
}

/**
 * Set directory locations, text strings, and other settings for Merlin WP.
 */
$wizard = new Merlin(
	// Configure Merlin with custom settings.
	$config = array(
		'directory'				=> BetterCore()->dir . '/includes/plugins',						// Location where the 'merlin' directory is placed.
		'url' 					=> BetterCore()->url . '/includes/plugins/merlin/',
		'demo_directory'		=> 'demo/',					// Location where the theme demo files exist.
		'merlin_url'			=> 'merlin',					// Customize the page URL where Merlin WP loads.
		'child_action_btn_url'	=> 'https://codex.wordpress.org/Child_Themes',  // The URL for the 'child-action-link'.
		'help_mode'				=> false,					// Set to true to turn on the little wizard helper.
		'dev_mode'				=> true,					// Set to true if you're testing or developing.
		'branding'				=> false,					// Set to false to remove Merlin WP's branding.
		'slug'					=> 'better_builder',
		'product'				=> 'Better Builder',
		'plugin'				=> plugin_basename( BetterCore()->file ),
		'admin_page'			=> 'plugins.php',
		'author'				=> '<a href="https://intensevisions.com/" target="_blank">Intense Visions</a>'
	),
	// Text strings.
	$strings = array(
		'admin-menu' 			=> esc_html__( 'Plugin Setup' , 'better' ),
		'title%s%s%s%s' 		=> esc_html__( '%s%s Plugins &lsaquo; Plugin Setup: %s%s' , 'better' ),

		'return-to-dashboard' 		=> esc_html__( 'Return to the dashboard' , 'better' ),

		'btn-skip' 			=> esc_html__( 'Skip' , 'better' ),
		'btn-back' 			=> esc_html__( 'Back' , 'better' ),
		'btn-next' 			=> esc_html__( 'Next' , 'better' ),
		'btn-start' 			=> esc_html__( 'Start' , 'better' ),
		'btn-no' 			=> esc_html__( 'Cancel' , 'better' ),
		'btn-activate' 		=> esc_html__( 'Activate' , 'better' ),
		'btn-authenticate' 		=> esc_html__( 'Start' , 'better' ),
		'btn-register' 		=> esc_html__( 'Register' , 'better' ),
		'btn-plugins-install' 		=> esc_html__( 'Install' , 'better' ),
		'btn-child-install' 		=> esc_html__( 'Install' , 'better' ),
		'btn-content-install' 		=> esc_html__( 'Install' , 'better' ),
		'btn-import' 			=> esc_html__( 'Import' , 'better' ),

		'welcome-header%s' 		=> esc_html__( 'Welcome to %s' , 'better' ),
		'welcome-header-success%s' 	=> esc_html__( 'Hi. Welcome back' , 'better' ),
		'welcome%s' 			=> esc_html__( 'This wizard will set up Better Builder, install plugins, and import content. It is optional & should take only a few minutes.' , 'better' ),
		'welcome-success%s' 		=> esc_html__( 'You may have already run this setup wizard. If you would like to proceed anyway, click on the "Start" button below.' , 'better' ),

		'activation-header' 			=> esc_html__( 'Activate Plugin' , 'better' ),
		'activation-header-success' 		=> esc_html__( 'Activated. You\'re good to go!' , 'better' ),
		'activation' 			=> esc_html__( 'Let\'s activate the plugin for automatic updates and support. You will need an Envato token with these permissions:' , 'better' ),
		'activation-success' 		=> esc_html__( 'You\'re good to go!' , 'better' ),
		'activation-success%s' 		=> esc_html__( 'The plugin has been activated and you are all set for automatic updates and support.' , 'better' ),
		'activation-action-link' 		=> esc_html__( 'Generate a Token' , 'better' ),

		'registration-header' 			=> esc_html__( 'Register' , 'better' ),
		'registration-header-success' 		=> esc_html__( 'You\'re good to go!' , 'better' ),
		'registration' 			=> esc_html__( 'Let\'s register with Intense Visions to get future product news and information.' , 'better' ),
		'registration-success' 		=> esc_html__( 'You\'re good to go!' , 'better' ),

		'child-header' 			=> esc_html__( 'Install Child Theme' , 'better' ),
		'child-header-success' 		=> esc_html__( 'You\'re good to go!' , 'better' ),
		'child' 			=> esc_html__( 'Let\'s build & activate a child theme so you may easily make theme changes.' , 'better' ),
		'child-success%s' 		=> esc_html__( 'Your child theme has already been installed and is now activated, if it wasn\'t already.' , 'better' ),
		'child-action-link' 		=> esc_html__( 'Learn about child themes' , 'better' ),
		'child-json-success%s' 		=> esc_html__( 'Awesome. Your child theme has already been installed and is now activated.' , 'better' ),
		'child-json-already%s' 		=> esc_html__( 'Awesome. Your child theme has been created and is now activated.' , 'better' ),

		'plugins-header' 		=> esc_html__( 'Install Plugins' , 'better' ),
		'plugins-header-success' 	=> esc_html__( 'You\'re up to speed!' , 'better' ),
		'plugins' 			=> esc_html__( 'Let\'s install some essential WordPress plugins to get your site up to speed.' , 'better' ),
		'plugins-success%s' 		=> esc_html__( 'The required WordPress plugins are all installed and up to date. Press "Next" to continue the setup wizard.' , 'better' ),
		'plugins-action-link' 		=> esc_html__( 'Advanced' , 'better' ),

		'import-header' 		=> esc_html__( 'Import Content' , 'better' ),
		'import' 			=> esc_html__( 'Let\'s import content to your website, to help you get familiar with the theme.' , 'better' ),
		'import-action-link' 		=> esc_html__( 'Advanced' , 'better' ),

		'ready-header' 			=> esc_html__( 'All done. Have fun!' , 'better' ),
		'ready%s' 			=> esc_html__( 'Your plugin has been all set up. Enjoy your new plugin by %s.' , 'better' ),
		'ready-action-link' 		=> esc_html__( 'Extras' , 'better' ),
		'ready-big-button' 		=> esc_html__( 'View your website' , 'better' ),

		'ready-link-1'            	=> wp_kses( sprintf( '<a href="%1$s" target="_blank">%2$s</a>', 'https://wordpress.org/support/', esc_html__( 'Explore WordPress', 'better' ) ), array( 'a' => array( 'href' => array(), 'target' => array() ) ) ),
		'ready-link-2'            	=> wp_kses( sprintf( '<a href="%1$s" target="_blank">%2$s</a>', 'https://intensevisions.com/support/', esc_html__( 'Get Plugin Support', 'better' ) ), array( 'a' => array( 'href' => array(), 'target' => array() ) ) ),
		'ready-link-3'           	=> wp_kses( sprintf( '<a href="'.admin_url( 'customize.php' ).'" target="_blank">%s</a>', esc_html__( 'Start Customizing', 'better' ) ), array( 'a' => array( 'href' => array(), 'target' => array() ) ) ),
	)
);

add_filter( $wizard->slug . '_merlin_steps', 'better_merlin_steps', true);

//delete_option( 'merlin_' . $merlin->slug . '_completed' );

function better_merlin_steps( $steps ) {
	unset( $steps['child'] );

	$steps = better_array_slice_assoc( $steps, 1, array(
		'activate' => array(
			'name'    => esc_html__( 'Activate', 'better' ),
			'view'    => 'better_merlin_activate',
		),
		'register' => array(
			'name'    => esc_html__( 'Register', 'better' ),
			'view'    => 'better_merlin_register',
		)
	));

	return $steps;
}

function better_array_slice_assoc( $array, $offset, $item ) {
	return array_slice($array, 0, $offset, true) +
		$item +
    	array_slice($array, $offset, NULL, true);
}

function better_merlin_activate( $wizard ) {
	// Variables.
	$is_activated 			= false;
	$action_url 			= "https://build.envato.com/create-token/?purchase:download=t&purchase:verify=t&purchase:list=t&user:username=t&user:email=t&user:account=t";
	$product 				= $wizard->product;

	// Strings passed in from the config file.
	$strings = $wizard->strings;

	// Text strings.
	$header 				= ! $is_activated ? $strings['activation-header'] : $strings['activation-header-success'];
	$action 				= $strings['activation-action-link'];
	$skip 					= $strings['btn-skip'];
	$back 					= $strings['btn-back'];
	$next 					= $strings['btn-next'];
	$paragraph 				= ! $is_activated ? $strings['activation'] : $strings['activation-success%s'];
	$activate 				= $strings['btn-activate'];
	$authenticate			= $strings['btn-authenticate'];

	?>
	<style>

		.form {
			text-align: left;

			border-radius: 3px;

			padding: 10px;
		}
		label {
			display: block;
		}

		.form input  {
			width: 100%;
			border: none;
			border-bottom: 1px solid #ccc;
			box-shadow: none;
			margin: 0;
			margin-bottom: 10px;
			padding: 0;
		}

		.form input:focus {
			outline: none;
			border-bottom-color: cornflowerblue;
			box-shadow: none;
		}

		label.success i {
			border-color: #46b450;
			background-color: #46b450;
			-webkit-transition: background-color 400ms cubic-bezier(0.18, 1, 0.21, 1) 220ms, border-color 400ms cubic-bezier(0.18, 1, 0.21, 1) 220ms;
			transition: background-color 400ms cubic-bezier(0.18, 1, 0.21, 1) 220ms, border-color 400ms cubic-bezier(0.18, 1, 0.21, 1) 220ms;
		}

		ul li span {
			font-size: 12px;
		}

		ul li {
			margin-bottom: 0;
			padding: 0 25px;
			text-align: left;
		}

		ul li i {
			display: inline-block;
			position: relative;
			width: 5px;
			height: 5px;
			margin-top: 5px;
			margin-right: 5px;
			border: 2px solid #b2b7ba;
			border-radius: 50%;
			text-align: left;
			border-color: #46b450;
			background-color: #46b450;
			-webkit-transition: background-color 400ms cubic-bezier(0.18, 1, 0.21, 1) 220ms, border-color 400ms cubic-bezier(0.18, 1, 0.21, 1) 220ms;
			transition: background-color 400ms cubic-bezier(0.18, 1, 0.21, 1) 220ms, border-color 400ms cubic-bezier(0.18, 1, 0.21, 1) 220ms;
		}
	</style>
	<script>
		(function($) {

			Merlin.callbacks.activate_product = function(btn){
				var activate = new ActivateManager();
	            activate.init(btn);
	        };

	        function ActivateManager() {
		    	var body 		= $('.merlin__body');
		        var complete, notice 	= $("#child-theme-text");

		        function ajax_callback(r) {

		            if (typeof r.done !== "undefined") {
		            	setTimeout(function(){
					        notice.addClass("lead");
					    },0);
					    setTimeout(function(){
					        notice.addClass("success");
					        notice.html(r.message);
					    },600);

		                complete();
		            } else {
		                notice.addClass("lead error");
		                notice.html(r.error);
		            }
		        }

		        function do_ajax() {
		        	var token = $("#token").val();

		            jQuery.post(merlin_params.ajaxurl, {
		                action: "merlin_activate_product",
		                wpnonce: merlin_params.wpnonce,
		                token: token
		            }, ajax_callback).fail(ajax_callback);
		        }

		        return {
		            init: function(btn) {
		                complete = function() {
		                	setTimeout(function(){
								$(".merlin__body").addClass('js--finished');
							},1500);

		                	body.removeClass( drawer_opened );

		                	setTimeout(function(){
								$('.merlin__body').addClass('exiting');
							},3500);

		                    setTimeout(function(){
								window.location.href=btn.href;
							},4000);

		                };

		                do_ajax();
		            }
		        }
		    }
	    })(jQuery);
	</script>
	<div class="merlin__content--transition">

		<?php echo wp_kses( $wizard->svg( array( 'icon' => 'license' ) ), $wizard->svg_allowed_html() ); ?>

		<svg class="icon icon--checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
			<circle class="icon--checkmark__circle" cx="26" cy="26" r="25" fill="none"/><path class="icon--checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
		</svg>

		<h1><?php echo esc_html( $header ); ?></h1>

		<p id="activation-text"><?php echo esc_html( sprintf( $paragraph, $product ) ); ?></p>

		<?php if ( !$is_activated ) : ?>
			<ul>
				<li>
					<i></i><span>View Your Envato Account Username</span>
				</li>
				<li>
					<i></i><span>View Your Email Address</span>
				</li>
				<li>
					<i></i><span>View Your Account Profile Details</span>
				</li>
				<li>
					<i></i><span>Download Your Purchased Items</span>
				</li>
				<li>
					<i></i><span>List Purchases You've Made</span>
				</li>
				<li>
					<i></i><span>Verify Purchases You've Made</span>
				</li>
			</ul>

			<a class="merlin__button merlin__button--blue merlin__button--fullwidth" href="<?php echo esc_url( $action_url ); ?>" target="_blank"><?php echo esc_html( $action ); ?></a>

			<div class="form">
				<p>
					<label>Envato Token</label>
					<input type="text" id="token" name="token" value="6zbJWGzzjLtvZTDPhbTgOKljP3Y1HMfj" />
				</p>
			</div>

		<?php endif; ?>

	</div>

	<footer class="merlin__content__footer">

		<?php if ( ! $is_activated ) : ?>

			<a href="<?php echo esc_url( $wizard->step_next_link() ); ?>" class="merlin__button merlin__button--skip merlin__button--proceed"><?php echo esc_html( $skip ); ?></a>

			<a href="<?php echo esc_url( $wizard->step_back_link() ); ?>" class="merlin__button merlin__button--back button-back"><?php echo esc_html( $back ); ?></a>
			<a href="<?php echo esc_url( $wizard->step_next_link() ); ?>" class="merlin__button merlin__button--next button-next" data-callback="activate_product">
				<span class="merlin__button--loading__text"><?php echo esc_html( $activate ); ?></span><?php echo $wizard->loading_spinner(); ?>
			</a>

		<?php else : ?>
			<a href="<?php echo esc_url( $wizard->step_next_link() ); ?>" class="merlin__button merlin__button--next merlin__button--proceed merlin__button--colorchange"><?php echo esc_html( $next ); ?></a>
		<?php endif; ?>
		<?php wp_nonce_field( 'merlin' ); ?>
	</footer>


<?php
}

function better_merlin_register( $wizard ) {
	// Variables.
	$is_registered 			= false;
	$action_url 			= $wizard->child_action_btn_url;
	$product 				= $wizard->product;

	// Strings passed in from the config file.
	$strings = $wizard->strings;

	// Text strings.
	$header 				= ! $is_registered ? $strings['registration-header'] : $strings['registration-header-success'];
	$action 				= $strings['child-action-link'];
	$skip 					= $strings['btn-skip'];
	$back 					= $strings['btn-back'];
	$next 					= $strings['btn-next'];
	$paragraph 				= ! $is_registered ? $strings['registration'] : $strings['registration-success%s'];
	$register 				= $strings['btn-register'];
	?>
	<style>

		.form {
			text-align: left;

			border-radius: 3px;
			margin: 10px 0;
			padding: 10px;
		}
		label {
			display: block;
		}

		.form input  {
			width: 100%;
			border: none;
			border-bottom: 1px solid #ccc;
			box-shadow: none;
			margin: 0;
			margin-bottom: 10px;
			padding: 0;
		}

		.form input:focus {
			outline: none;
			border-bottom-color: cornflowerblue;
			box-shadow: none;
		}
	</style>
	<div class="merlin__content--transition">

		<?php echo wp_kses( $wizard->svg( array( 'icon' => 'license' ) ), $wizard->svg_allowed_html() ); ?>

		<svg class="icon icon--checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
			<circle class="icon--checkmark__circle" cx="26" cy="26" r="25" fill="none"/><path class="icon--checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
		</svg>

		<h1><?php echo esc_html( $header ); ?></h1>

		<p id="activation-text"><?php echo esc_html( sprintf( $paragraph, $product ) ); ?></p>

		<?php if ( !$is_registered ) : ?>
			<div class="form">

				<p>
					<label>First Name</label>
					<input type="text" id="first-name" name="first-name" />
				</p>
				<p>
					<label>Last Name</label>
					<input type="text" id="last-name" name="last-name" />
				</p>
				<p>
					<label>Email Address</label>
					<input type="text" id="email" name="email" />
				</p>

			</div>
		<?php endif; ?>

	</div>

	<footer class="merlin__content__footer">

		<?php if ( ! $is_registered ) : ?>

			<a href="<?php echo esc_url( $wizard->step_next_link() ); ?>" class="merlin__button merlin__button--skip merlin__button--proceed"><?php echo esc_html( $skip ); ?></a>

			<a href="<?php echo esc_url( $wizard->step_back_link() ); ?>" class="merlin__button merlin__button--back button-back"><?php echo esc_html( $back ); ?></a>

			<a href="<?php echo esc_url( $wizard->step_next_link() ); ?>" class="merlin__button merlin__button--next merlin__button--proceed merlin__button--colorchange" data-callback="authenticate_license">
				<span class="merlin__button--loading__text"><?php echo esc_html( $register ); ?></span><?php echo $wizard->loading_spinner(); ?>
			</a>

		<?php else : ?>
			<a href="<?php echo esc_url( $wizard->step_next_link() ); ?>" class="merlin__button merlin__button--next merlin__button--proceed merlin__button--colorchange"><?php echo esc_html( $next ); ?></a>
		<?php endif; ?>
		<?php wp_nonce_field( 'merlin' ); ?>
	</footer>
<?php
}

add_action( 'wp_ajax_merlin_activate_product', 'merlin_activate_product', 10, 0 );

function merlin_activate_product() {

	if ( ! check_ajax_referer( 'merlin_nonce', 'wpnonce' ) || empty( $_POST['token'] ) ) {
		exit( 0 );
	}

	require_once( BetterCore()->path . 'includes/lib/class-betterbuilder-envato.php');

	$token = $_POST['token'];

	$api = new BetterBuilder_Envato_API( $token );
	$scopes = $api->get_token_scopes();

	if ( $api->check_token_scopes( $scopes ) ) {
		$plugins = $api->plugins();
		$themes = $api->themes();
		$account = $api->account();

		$activation = array (
			'username' => $api->username(),
			'email' => $api->email(),
			'firstname' => $account['firstname'],
			'lastname' => $account['surname'],
			'country' => $account['country'],
			'image' => $account['image'],
			'plugins' => $plugins,
			'themes' => $themes,
		);

		$json = $activation;
	} else {
		$json = array( 'done' => 0, 'message' => esc_html__( 'The token doesn\'t have enough permission to authenticate.', 'better' ) );
	}

	if ( $json ) {
		$json['hash'] = md5( serialize( $json ) );
		wp_send_json( $json );
	} else {
		wp_send_json( array( 'done' => 1, 'message' => esc_html__( 'Success', 'better' ) ) );
	}

	exit;
}
