<?php
/**
 * Prebook Roles.
 *
 * @package Prebook
 * @since 1.0.0
 */

// Prebook namespace.
namespace Prebook\WP_Admin;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! class_exists( __NAMESPACE__ . 'Roles' ) ) {

	/**
	 * Prebook Roles.
	 *
	 * @package Prebook
	 * @since 1.0.0
	 */
	class Roles extends \Prebook\Base\Controller {

		/**
		 * Actions.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function actions() {
			// Register role.
			add_action( 'init', array( $this, 'register_roles' ) );
		}


		/**
		 * Register role.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function register_roles() {
			// Add staff if not exists.

			if ( ! get_role( 'staff' ) ) {
				add_role( 'staff', __( 'Staff', 'prebook' ), [
					'manage_prebook_appointments' => true,
				] );
			}

			// Add customer if not exists.
			if ( ! get_role( 'customer' ) ) {
				add_role( 'customer', __( 'Customer', 'prebook' ), array() );
			}

			// Set staff capabilities.
			$staff = get_role( 'staff' );
			$staff->add_cap( 'read' );
			$staff->add_cap( 'manage_prebook_appointments' );
		}
	}

	// Run the class.
	Roles::init();
}
