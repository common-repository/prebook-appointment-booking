<?php
/**
 * Base controller class for Prebook
 *
 * @package Prebook
 * @since 1.0.0
 */

// Prebook namespace.
namespace Prebook\Base;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! class_exists( __NAMESPACE__ . 'Controller' ) ) {

	/**
	 * Base controller class for Prebook
	 *
	 * @package Prebook
	 * @since 1.0.0
	 */
	abstract class Controller extends \Prebook\Base {

		/**
		 * Initializes the child class.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public static function init() {
			$instance = static::get_instance();

			$instance->actions();
			$instance->filters();
		}
		/**
		 * Executes the actions hooks for the child class.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function actions() {}

		/**
		 * Executes the filter hooks for the child class.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function filters() {}

		/**
		 * Renders the template.
		 *
		 * @since 1.0.0
		 * @param string $template template name.
		 * @param array  $args Arguments to pass to the template.
		 * @param bool   $return_template Whether to return the template or not.
		 * @return mixed
		 */
		protected function view( $template = '', $args = [], $return_template = false ) { // phpcs:ignore
			// Get the template path.
			$template_path = PREBOOK_TEMPLATES . $template . '.php';

			// Check if the template exists.
			if ( ! file_exists( $template_path ) ) {
				$template_path = PREBOOK_TEMPLATES . $template . '.txt';

				if ( ! file_exists( $template_path ) ) {
					return '';
				}
			}

			// If the return_template is true, return the template.
			if ( $return_template ) {
				ob_start();
				include $template_path;
				return ob_get_clean();
			}

			// Include the template.
			include $template_path;

			return '';
		}
	}
}
