<?php
/**
 * Prebook Upgrade.
 *
 * @package Prebook
 * @since 1.0.0
 */
// Prebook namespace.
namespace Prebook\Install;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );


if ( ! class_exists( __NAMESPACE__ . 'Upgrade' ) ) {

	/**
	 * Prebook Installer.
	 *
	 * @package Prebook
	 * @since 1.0.0
	 */
	class Upgrade extends \Prebook\Base\Controller {

		/**
		 * Runs the Upgrade.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function run() {
			// Bail, if the prebook_upgraded is set and more or equal to 1.1.2.
			if ( version_compare( get_option( 'prebook_version' ), '1.1.2', '>=' ) ) {
				return;
			}

			// Update key.
			update_option( 'prebook_upgraded', PREBOOK_VERSION );

			$this->update_minutes();
		}

		/**
		 * Update minutes.
		 *
		 * @since 1.1.2
		 * @return void
		 */
		public function update_minutes() {
			global $wpdb;

			// Run queries for appointments.
			$wpdb->query( "UPDATE {$wpdb->prefix}prebook_meta SET meta_value = 'minute' WHERE meta_value = 'minutes' AND meta_key IN ('duration_unit', 'service_duration_unit', 'pre_buffer_unit', 'post_buffer_unit')" );

			// Update the version.
			update_option( 'prebook_version', PREBOOK_VERSION );
		}
	}

	// Init.
	add_action('plugins_loaded', function () {
		Upgrade::get_instance()->run();
	});
}
