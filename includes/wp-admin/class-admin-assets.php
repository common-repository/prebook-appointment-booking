<?php
/**
 * Admin assets class for Prebook
 *
 * @package Prebook
 * @since 1.0.0
 */

// Prebook namespace.
namespace Prebook\WP_Admin;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! class_exists( __NAMESPACE__ . 'Assets' ) ) {

	/**
	 * Admin assets class for Prebook
	 *
	 * @package Prebook
	 * @since 1.0.0
	 */
	class Assets extends \Prebook\Base\Controller {
		/**
		 * Actions
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function actions() {
			// Enqueue scripts.
			add_action( 'prebook_scripts', [ $this, 'prebook_scripts' ] );
		}

		/**
		 * Head scripts
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function prebook_scripts() {
			// Enqueue styles.
			wp_enqueue_style( 'prebook-admin', PREBOOK_ASSETS . 'css/admin.min.css', [], PREBOOK_VERSION );
			wp_enqueue_script( 'prebook-admin', PREBOOK_ASSETS . 'js/admin.min.js', [], PREBOOK_VERSION, true );

			// Localize scripts.
			wp_localize_script( 'prebook-admin', 'prebook_admin', $this->localize_scripts() );
		}

		/**
		 * Localize scripts
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function localize_scripts() {
			$json = [
				'url' => [
					'ajax' => admin_url( 'admin-ajax.php' ),
					'assets' => PREBOOK_ASSETS,
					'admin' => admin_url(),
					'home' => home_url(),
					'current' => add_query_arg( null, null ),
				],

				'nonce'    => wp_create_nonce( 'prebook_nonce' ),

				'data' => $this->get_pre_loaded_data(),

				'profile' => $this->get_profile(),

				'is_debug' => defined( 'WP_DEBUG' ) && WP_DEBUG,
				'is_multisite' => is_multisite(),
			];

			return apply_filters( 'prebook_localize_scripts', $json );
		}

		/**
		 * Get pre loaded data
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function get_pre_loaded_data() {
			$data = [
				'admin' => [
					'page_id' => prebook_get_option( 'admin_page_id' ),
				],
			];

			return apply_filters( 'prebook_pre_loaded_data', $data );
		}

		/**
		 * Get profile
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function get_profile() {

			// Bail, if not a logged in user.
			if ( ! is_user_logged_in() ) {
				return [];
			}

			$user = wp_get_current_user();

			$profile = [
				'id' => get_current_user_id(),
				'username' => $user->user_login,
				'first_name' => $user->first_name,
				'last_name' => $user->last_name,
				'display_name' => $user->display_name,
				'email' => $user->user_email,
				'picture' => get_user_meta( $user->ID, 'prebook_profile_picture', true ),
				'role' => $user->roles[0],
				'permissions' => [],
			];

			return apply_filters( 'prebook_profile', $profile );
		}
	}


	// Initialize the class.
	Assets::init();
}
