<?php
/**
 * Admin assets class for Prebook
 *
 * @package Prebook
 * @since 1.0.0
 */

// Prebook namespace.
namespace Prebook\Admin;

// Options class.
require_once PREBOOK_INCLUDES . 'common/class-options.php';

// Model user.
require_once PREBOOK_INCLUDES . 'models/class-model-user.php';

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

			// Inline styles.
			$inline_css = $this->get_inline_css();

			if ( ! empty( $inline_css ) ) {
				wp_add_inline_style( 'prebook-admin', $inline_css );
			}
		}

		/**
		 * Localize scripts
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function localize_scripts() {
			$slot = \Prebook\Slot::get_instance();

			$json = [
				'url' => [
					'ajax' => admin_url( 'admin-ajax.php' ),
					'assets' => PREBOOK_ASSETS,
					'admin' => admin_url(),
					'home' => home_url(),
					'current' => add_query_arg( null, null ),
					'logout' => site_url( 'wp-login.php?action=logout' ) . '&_wpnonce=' . wp_create_nonce( 'log-out' ),
					'api' => rest_url( 'prebook' ),
				],

				'rest_url' => esc_url_raw(rest_url( 'prebook' )),
				'rest_nonce' => wp_create_nonce( 'wp_rest' ),

				'setup' => [
					'hours_updated' => wp_validate_boolean( prebook_get_option( 'business_hours_updated' ) ),
				],

				'nonce'    => wp_create_nonce( 'prebook_admin_nonce' ),
				'debug' => defined( 'WP_DEBUG' ) && WP_DEBUG,
				'is_multisite' => is_multisite(),

				'profile' => $this->get_profile(),

				'current' => [
					'date' => gmdate( 'Y-m-d' ),
					'day' => gmdate( 'l' ),
				],
				'global' => [
					'days' => array_keys( $slot->get_working_days() ),
				],
				'settings' => \Prebook\Settings::get_instance()->get_settings(),
				'options' => \Prebook\Options::get_instance()->get_options(),
				'site' => [
					'name' => get_bloginfo( 'name' ),
					'description' => get_bloginfo( 'description' ),
					'admin_email' => get_bloginfo( 'admin_email' ),
					'url' => get_bloginfo( 'url' ),
					'host' => wp_parse_url( get_bloginfo( 'url' ), PHP_URL_HOST ),
				],
			];

			return apply_filters( 'prebook_admin_localize_scripts', $json );
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

			$user = get_user_by( 'id', get_current_user_id() );

			$email = get_user_meta( $user->ID, 'email', true );

			if ( empty ( $email ) ) {
				$email = $user->user_email;
			}

			$name  = $user->display_name;

			// If display name is empty.
			if ( empty( $name ) ) {
				$name = get_user_meta( $user->ID, 'first_name', true );
			}

			// If display name is empty.
			if ( empty( $name ) ) {
				$name = $user->user_login;
			}

			$profile = [
				'id' => get_current_user_id(),
				'name' => $name,
				'email' => $email,
				'profile_image_url' => get_user_meta( $user->ID, 'profile_image_url', true ),
				'role' => $user->roles[0],
			];

			return apply_filters( 'prebook_profile', $profile );
		}

		/**
		 * Get inline css
		 *
		 * @since 1.0.0
		 * @return string
		 */
		public function get_inline_css() {
			$css = \Prebook\Options::get_instance()->get_printed_fonts();
			return apply_filters( 'prebook_inline_css', $css );
		}
	}


	// Initialize the class.
	Assets::init();
}
