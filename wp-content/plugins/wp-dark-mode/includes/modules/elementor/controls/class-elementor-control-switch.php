<?php
/**
 * WP_Dark_Mode_Control_Image_Choose extends \Elementor\Base_Data_Control
 *
 * @version 1.0.0
 * @package WP_DARK_MODE
 */

namespace WP_Dark_Mode\Module\Elementor\Controls;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit();

/**
 * WP_Dark_Mode_Control_Image_Choose extends \Elementor\Base_Data_Control
 *
 * @version 1.0.0
 */
class DarkModeSwitch extends \Elementor\Base_Data_Control {

	// Dark Mode Utility.
	use \WP_Dark_Mode\Traits\Utility;

	/**
	 * Get choose control type.
	 *
	 * Retrieve the control type, in this case `choose`.
	 *
	 * @return string Control type.
	 * @since  1.0.0
	 * @access public
	 */
	public function get_type() {
		return 'wp_dark_mode_switch';
	}

	/**
	 * Enqueue control scripts and styles.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function enqueue() {

		// Style
		wp_enqueue_style( 'wp-dark-mode-admin-common', WP_DARK_MODE_ASSETS . '/css/admin-common.css', [], WP_DARK_MODE_VERSION );

		// Script
		wp_register_script(
			'wp-dark-mode-js-elementor-switcher',
			WP_DARK_MODE_ASSETS . 'js/admin.min.js',
			[],
			WP_DARK_MODE_VERSION,
			true
		);

		wp_register_script(
			'wp-dark-mode-js-elementor-switcher-additional',
			plugin_dir_url( WP_DARK_MODE_FILE ) . '/includes/modules/elementor/assets/elementor-switcher.js',
			[],
			WP_DARK_MODE_VERSION,
			true
		);

		wp_enqueue_script( 'wp-dark-mode-js-elementor-switcher' );
	}

	/**
	 * Render choose control output in the editor.
	 *
	 * Used to generate the control HTML in the editor using Underscore JS
	 * template. The variables for the class are available using `data` JS
	 * object.
	 *
	 * @return mixed
	 * @since  1.0.0
	 * @access public
	 */
	public function content_template() {
		$control_uid = $this->get_control_uid( '{{value}}' );

		$enabled = true;
		if ( $control_uid > 3 ) {
			$enabled = false;
		}
		?>
		<div class="elementor-control-field">
			<label class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-input-wrapper">
				<div class="elementor-image-choices">
					<# _.each( data.options, function( options, value ) { #>
						<div class="image-choose-label-block {{value}}">
							<input id="<?php echo esc_attr( $control_uid ); ?>" type="radio" name="elementor-choose-{{ data.name }}-{{ data._cid }}" value="{{ value }}">
							<label class="elementor-image-choices-label" for="<?php echo esc_attr( $control_uid ); ?>" title="{{ options }}">
								<?php echo wp_kses_post( do_shortcode( '[wp-dark-mode-switch style="{{ value }}" size="0.8"][/wp-dark-mode-switch]' ) ); ?>
							</label>
						</div>
						<# } ); #>
				</div>
			</div>
		</div>

		<# if ( data.description ) { #>
			<div class="elementor-control-field-description">{{{ data.description }}}</div>
			<# } #>
		<?php
	}


	/**
	 * Get choose control default settings.
	 *
	 * Retrieve the default settings of the choose control. Used to return the
	 * default settings while initializing the choose control.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @return array Control default settings.
	 */
	protected function get_default_settings() {
		return [
			'options' => [],
			'toggle' => true,
		];
	}
}