<?php
/**
 * Prebook Installer.
 *
 * @package Prebook
 * @since 1.0.0
 */
// Prebook namespace.
namespace Prebook\Install;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );


// Load database tables installer.
require_once PREBOOK_INCLUDES . 'install/class-tables.php';

// Load page installer.
require_once PREBOOK_INCLUDES . 'install/class-pages.php';

// Load page installer.
require_once PREBOOK_INCLUDES . 'models/class-model-customer.php';


if ( ! class_exists( __NAMESPACE__ . 'Installer' ) ) {

	/**
	 * Prebook Installer.
	 *
	 * @package Prebook
	 * @since 1.0.0
	 */
	class Installer extends \Prebook\Base\Controller {

		/**
		 * Runs the installer.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function run() {

			// Define the Prebook Installing.
			define( 'PREBOOK_INSTALLING', true );

			// Create tables.
			$this->create_tables();

			// Add prebook logs.
			$this->add_prebook_logs();

			// Create default pages.
			$this->create_default_pages();

			// Generate UCIDs.
			$this->generate_ucids();
		}

		/**
		 * Creates PreBook core tables.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		protected function create_tables() {
			// Create tables.
			$tables = new Tables();
			$tables->create_tables();
		}

		/**
		 * Add prebook logs.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		protected function add_prebook_logs() {
			// Prebook first installed at.
			$prebook_first_installed_at = get_option( 'prebook_first_installed_at', null );

			if ( is_null( $prebook_first_installed_at ) ) {
				prebook_add_option( 'first_installed_at', time() );
			}

			// Prebook last installed at.
			prebook_update_option( 'installed_at', time() );

			// Last installed version.
			prebook_update_option( 'installed_version', PREBOOK_VERSION );
		}

		/**
		 * Create default pages.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		protected function create_default_pages() {
			// Create pages.
			$pages = new Pages();
			$pages->create_pages();
		}

		/**
		 * Generate UCIDs.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		protected function generate_ucids() {
			// Get all customers.
			$customers = get_users( [
				'role' => 'customer',
				'fields' => 'ID',
				'number' => -1,
				'orderby' => 'ID',
			] );

			// Generate UCIDs.
			foreach ( $customers as $customer_id ) {
				$customer = new \Prebook\Model\Customer();
				$customer->set_id( $customer_id );
				$customer->generate_ucid();
			}
		}
	}
}
