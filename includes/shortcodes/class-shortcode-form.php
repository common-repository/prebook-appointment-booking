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

if ( ! class_exists( __NAMESPACE__ . 'Form' ) ) {
	/**
	 * Shortcode booking form class.
	 *
	 * @package Prebook
	 * @since 1.0.0
	 */
	class Form extends \Prebook\Base\Controller {
		/**
		 * Actions.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function actions() {
			add_shortcode( 'prebook_form', [ $this, 'render_form' ] );
			add_shortcode( 'prebook-form', [ $this, 'render_form' ] );
		}

		/**
		 * Render form.
		 *
		 * @since 1.0.0
		 * @param array  $atts Shortcode attributes.
		 * @param string $content Shortcode content.
		 * @return mixed
		 */
		public function render_form( $atts = [], $content = null ) {
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
			];

			return $this->view( 'frontend/booking/form', $args, true );
		}
	}

	// Initialize class.
	Form::init();
}
