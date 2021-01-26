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
class Control_Better_IconSource extends Base_Data_Control {

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
		return 'better_iconsource';
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
	public static function get_icon_sources() {
        // Icon source input field option
		$icon_sources = array();
		$packs = \BetterSC_Icon::get_icon_sources();
		if ( is_array( $packs ) ) {
		    foreach ($packs as $key => $pack) {
					$icon_sources[ $key ] = $pack['title'] . ' (' . $pack['count'] . ')';
		    }
		}
		return $icon_sources;
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
			'options' => self::get_icon_sources()
		];
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
		<div class="elementor-control-field better_icon_source">
			<label for="<?php echo $control_uid; ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-input-wrapper better_iconsource">
				<select id="<?php echo $control_uid; ?>" class="elementor-control-icon" data-setting="{{ data.name }}" data-placeholder="<?php echo __( 'Select Icon', 'elementor' ); ?>">
					<# _.each( data.options, function( option_title, option_value ) { #>
					<option value="{{ option_value }}">{{{ option_title }}}</option>
					<# } ); #>
				</select>
			</div>
		</div>
		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{ data.description }}</div>
		<# } #>
		<?php
	}
}
