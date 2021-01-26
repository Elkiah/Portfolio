<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor icon control.
 *
 * A base control for creating an icon control. Displays a font icon select box
 * field. The control accepts `include` or `exclude` arguments to set a partial
 * list of icons.
 *
 * @since 1.0.0
 */
class Control_Better_Icon extends Base_Data_Control   {

	/**
	 * Get icon control type.
	 *
	 * Retrieve the control type, in this case `icon`.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Control type.
	 */
	public function get_type() {
		return 'better_icon';
	}

	/**
	 * Get icons.
	 *
	 * Retrieve all the available icons.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return array Available icons.
	 */
	public static function get_icons() {
		return [
			'fa fa-500px' => '500px',
			'fa fa-address-book' => 'address-book',
		];
	}

	/**
	 * Get icons control default settings.
	 *
	 * Retrieve the default settings of the icons control. Used to return the default
	 * settings while initializing the icons control.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @return array Control default settings.
	 */
	protected function get_default_settings() {
		return [
			'label_block' => true,
			'options' => self::get_icons()
		];
	}

    /**
	 * Enqueue media control scripts and styles.
	 *
	 * Used to register and enqueue custom scripts and styles used by the icon
	 * control.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function enqueue() {
        wp_enqueue_script( 'better.icon-field', BetterCore()->assets_url . 'shortcodes/icon/icon-field.js', array( 'jquery' ), BetterCore()->_version, true );
		wp_enqueue_script( 'better.selectize', BetterCore()->assets_url . 'js/selectize/dist/js/standalone/selectize.min.js', array( 'jquery' ), '0.9.1', true );
		wp_enqueue_style( 'better.selectize', BetterCore()->assets_url . 'js/selectize/dist/css/selectize.default.min.css', null, '0.9.1' );
	}

	/**
	 * Render icons control output in the editor.
	 *
	 * Used to generate the control HTML in the editor using Underscore JS
	 * template. The variables for the class are available using `data` JS
	 * object.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
		<div class="elementor-control-field better_icon" data-option="{{ data.controlValue }}">
			<label for="<?php echo $control_uid; ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-input-wrapper better_icontype">
				<select id="<?php echo $control_uid; ?>" class="elementor-select2" type="select2" data-option="{{ data.controlValue }}" data-setting="{{ data.name }}" data-placeholder="<?php echo __( 'Select Icon', 'elementor' ); ?>">
					<option value=""><?php echo __( 'Select Icon', 'elementor' ); ?></option>
				</select>
			</div>
		</div>
		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{ data.description }}</div>
		<# } #>
		<?php
	}
}
