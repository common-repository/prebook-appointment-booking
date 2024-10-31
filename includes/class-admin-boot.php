<?php
/**
 * Prebook admin boot.
 *
 * @package Prebook
 * @since 1.0.0
 */

// Prebook Namespace.
namespace Prebook\Admin;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! class_exists( __NAMESPACE__ . 'Boot' ) ) {

	/**
	 * Prebook admin boot.
	 *
	 * @since 1.0.0
	 */
	class Boot {

		/**
		 * Instance of the class.
		 *
		 * @since 1.0.0
		 * @var object|null $instance Instance of the class.
		 */
		protected static $instance;


		/**
		 * Starts the Prebook plugin.
		 *
		 * @since 1.0.0
		 */
		public static function start() {
			// Can be started only once.
			if ( ! is_null( self::$instance ) ) {
				return;
			}

			self::$instance = new self();

			// Loads the plugin.
			self::$instance->load_files();

			// Fires the plugin loaded action.
			do_action( 'prebook_admin_loaded' );
		}

		/**
		 * Loads the core files used by Prebook.
		 *
		 * @since 1.0.0
		 */
		protected function load_files() {
			require_once PREBOOK_INCLUDES . 'common/class-settings.php';
			require_once PREBOOK_ADMIN . 'class-admin-actions.php';
			require_once PREBOOK_ADMIN . 'class-admin-assets.php';
		}
	}

	// Initializes the Prebook class.
	Boot::start();
}
