<?php
/**
 * Shortcode booking form.
 *
 * @package Prebook
 * @since 1.0.0
 */

// Namespace.
namespace Prebook\Shortcodes;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! class_exists( __NAMESPACE__ . 'Button' ) ) {
	/**
	 * Shortcode booking form class.
	 *
	 * @package Prebook
	 * @since 1.0.0
	 */
	class Button extends \Prebook\Base\Controller {
		/**
		 * Actions.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function actions() {
			add_shortcode( 'prebook_button', [ $this, 'render_modal_trigger' ] );
			add_shortcode( 'prebook-button', [ $this, 'render_modal_trigger' ] );
		}

		/**
		 * Render form.
		 *
		 * @since 1.0.0
		 * @param array  $atts Shortcode attributes.
		 * @param string $content Shortcode content.
		 * @return mixed
		 */
		public function render_modal_trigger( $atts = [], $content = null ) {
			// Get attributes.
			$atts = shortcode_atts(
				[
					'category_id' => '',
					'service_id' => '',
					'staff_id' => '',
					'date' => '',
					'time_start' => '',
					'time_end' => '',
				],
				$atts
			);

			$args = [
				'atts' => $atts,
				'content' => ! empty( $content ) ? $content : __( 'Book Now', 'prebook' ),
			];

			return $this->view( 'frontend/booking/modal-button', $args, true );
		}
	}

	// Initialize class.
	Button::init();
}
