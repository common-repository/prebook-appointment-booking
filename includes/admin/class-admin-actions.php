<?php
/**
 * Prebook Admin Actions.
 *
 * @package Prebook
 * @since 1.0.0
 */

// Prebook namespace.
namespace Prebook\Admin;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! class_exists( __NAMESPACE__ . 'Actions' ) ) {

	/**
	 * Prebook Admin Actions.
	 *
	 * @package Prebook
	 * @since 1.0.0
	 */
	class Actions extends \Prebook\Base\Controller {

		/**
		 * Actions.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function actions() {

			// Prebook Init.
			add_action( 'prebook_init', [ $this, 'prebook_init' ], 100 );

			// Admin header.
			add_action( 'prebook_head', [ $this, 'prebook_head' ], 100 );

			// Admin footer.
			add_action( 'prebook_footer', [ $this, 'prebook_footer' ], 100 );
		}

		/**
		 * Prebook init.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function prebook_init() {
			do_action( 'prebook_scripts' );
		}

		/**
		 * Prebook admin header.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function prebook_head() {
			wp_print_styles('prebook-admin');
		}

		/**
		 * Prebook admin footer.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function prebook_footer() {
			// Do enqueue scripts.
			wp_print_scripts('prebook-admin');
		}
	}

	// Run the class.
	Actions::init();
}
