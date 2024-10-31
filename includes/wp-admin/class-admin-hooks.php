<?php
/**
 * Prebook Admin Hooks.
 *
 * @package Prebook
 * @since 1.0.0
 */

// Prebook namespace.
namespace Prebook\WP_Admin;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! class_exists( __NAMESPACE__ . 'Hooks' ) ) {

	/**
	 * Prebook Admin Hooks.
	 *
	 * @package Prebook
	 * @since 1.0.0
	 */
	class Hooks extends \Prebook\Base\Controller {

		/**
		 * Actions.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function actions() {
			// Pages columns content.
			add_filter( 'display_post_states', array( $this, 'add_custom_post_state' ), 10, 2 );
			add_action( 'prebook_option_updated', array( $this, 'prebook_option_updated' ), 10, 2 );
			add_action( 'wp_login', array( $this, 'wp_login' ), 10, 2 );
			// On user created in wp.
			add_action( 'user_register', array( $this, 'user_register' ), 10, 1 );
		}

		/**
		 * Pages columns content.
		 *
		 * @since 1.0.0
		 * @param array  $post_states Post states.
		 * @param object $post Post object.
		 * @return array
		 */
		public function add_custom_post_state( $post_states, $post ) {

			// Admin page.
			if ( prebook_get_option( 'admin_page_id' ) === $post->ID ) {
				$post_states['prebook_admin_app'] = __( 'Prebook Admin App', 'prebook' );
			}

			// My account page.
			// if ( prebook_get_option( 'my_account_page_id' ) == $post->ID ) {
			//  $post_states['prebook_my_account'] = __( 'Prebook My Account Page', 'prebook' );
			// }

			// Book an appointment page.
			if ( prebook_get_option( 'book_an_appointment_page_id' ) === $post->ID ) {
				$post_states['prebook_book_an_appointment'] = __( 'Prebook Default Appointment Page', 'prebook' );
			}

			return $post_states;
		}

		/**
		 * Updated option.
		 *
		 * @since 1.0.0
		 * @param string $option_key Option key.
		 * @param mixed  $value      Value.
		 * @return void
		 */
		public function prebook_option_updated( $option_key, $value ) {
			if ( 'business_hours_Saturday' === $option_key ) {
				prebook_update_option( 'business_hours_updated', true );
			}
		}

		/**
		 * WP login.
		 *
		 * @since 1.0.0
		 * @param string $user_login User login.
		 * @param object $user User object.
		 * @return void
		 */
		public function wp_login( $user_login, $user ) {
			// If user is staff, then redirect to prebook admin page.
			if ( in_array( 'staff', (array) $user->roles, true ) ) {
				$admin_page_link = get_permalink( prebook_get_option( 'admin_page_id' ) );
				wp_redirect( $admin_page_link );
				exit;
			}
		}

		/**
		 * User register.
		 *
		 * @since 1.0.0
		 * @param int $user_id User ID.
		 * @return void
		 */
		public function user_register( $user_id ) {
			// Set status meta to publish for new staff if not set.
			$user = get_user_by( 'ID', $user_id );
			if ( in_array( 'staff', (array) $user->roles, true ) ) {
				$status = get_user_meta( $user_id, 'status', true );
				if ( ! $status ) {
					update_user_meta( $user_id, 'status', 'publish' );
				}

				// Set user email to email meta if not set.
				$email = get_user_meta( $user_id, 'email', true );
				if ( ! $email ) {
					update_user_meta( $user_id, 'email', $user->user_email );
				}
			}
		}
	}

	// Run the class.
	Hooks::init();
}
