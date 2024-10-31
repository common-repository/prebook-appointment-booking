<?php
/**
 * Base Ajax handler class for Prebook
 *
 * @package Prebook
 * @since 1.0.0
 */

// Prebook namespace.
namespace Prebook\Base;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! class_exists( __NAMESPACE__ . 'Ajax' ) ) {

	/**
	 * Base ajax handler class for Prebook
	 *
	 * @package Prebook
	 * @since 1.0.0
	 */
	abstract class Ajax extends Controller {

		/**
		 * Executes the actions hooks for the child class.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function actions() {
			// Register the Ajax actions.
			$this->register_ajax_actions();
		}

		/**
		 * Registers the Ajax actions.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		protected function register_ajax_actions() {
			$ajax_actions = $this->ajax_actions();

			// Bail if no Ajax actions are defined.
			if ( empty( $ajax_actions ) ) {
				return;
			}

			// Register the Ajax actions.
			foreach ( $ajax_actions as $action => $callback ) {
				add_action( 'wp_ajax_' . PREBOOK_PREFIX . $action, $callback );

				// Whitelist the Ajax actions.
				if ( in_array( $action, $this->whitelist_ajax_actions(), true ) ) {
					add_action( 'wp_ajax_nopriv_' . PREBOOK_PREFIX . $action, $callback );
				}
			}
		}

		/**
		 * Returns the list of Ajax endpoints.
		 *
		 * @since 1.0.0
		 * @return array<string, callable>
		 */
		public function ajax_actions() {
			return [];
		}

		/**
		 * Whitelists the Ajax actions.
		 *
		 * @since 1.0.0
		 *
		 * @return array<string>
		 */
		public function whitelist_ajax_actions() {
			return [];
		}
	}
}
