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
class Control_Better_StockImage extends Base_Data_Control {

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
		return 'better_stockimage';
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
			//'fa fa-address-book' => 'address-book',
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
		wp_enqueue_script( 'better.stock-image', BetterCore()->assets_url . 'shortcodes/stock-image/better-stock-image.js', array( 'jquery', 'underscore' ), '1.0.0', true );
		wp_enqueue_style( 'better.stock-image', BetterCore()->assets_url . 'shortcodes/stock-image/better-stock-image.css' );
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
		<div class="betterbuilder-stock-image">
			<input type="text" class="elementor-control-tag-area" id="stock-image-searcher" name="stock-image-searcher" placeholder="Search free high-resolution photos..."/>
			<div id="better-stockimage-wrap"></div>
			<input type="hidden" class="better-stockimage" data-setting="{{{ data.name }}}">
		</div>
		<?php
	}
}
