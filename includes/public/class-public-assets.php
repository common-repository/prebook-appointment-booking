<?php
/**
 * Public assets class for Prebook
 *
 * @package Prebook
 * @since 1.0.0
 */

// Prebook namespace.
namespace Prebook\Publics;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! class_exists( __NAMESPACE__ . 'Assets' ) ) {

	/**
	 * Public assets class for Prebook
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
			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ], 10000 );
			add_action ( 'wp_footer', [ $this, 'render_prebook_modal' ] );
		}

		/**
		 * Enqueue scripts
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function enqueue_scripts() {
			// Enqueue styles.
			wp_enqueue_style( 'prebook', PREBOOK_ASSETS . 'css/frontend.min.css', [], PREBOOK_VERSION );
			wp_enqueue_script( 'prebook', PREBOOK_ASSETS . 'js/frontend.min.js', [], PREBOOK_VERSION, true );

			// Localize scripts.
			wp_localize_script( 'prebook', 'prebook', $this->localize_scripts() );
			// Inline styles.
			$inline_css = $this->get_inline_css();

			if ( ! empty( $inline_css ) ) {
				wp_add_inline_style( 'prebook', $inline_css );
			}
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

				'nonce'    => wp_create_nonce( 'prebook_admin_nonce' ),
				'is_debug' => defined( 'WP_DEBUG' ) && WP_DEBUG,
				'customer'  => $this->get_profile(),

				'settings' => [
					'calendar' => [
						'available_period_days' => intval( prebook_get_option( 'calendar_available_period_days', 365 ) ),
						'first_day' => prebook_get_option( 'calendar_first_day', 'Monday' ),
						'time_format' => intval( prebook_get_option( 'calendar_time_format', 12 ) ),
						'date_format' => prebook_get_option( 'calendar_date_format', 'm/d/Y' ),
					],
					'payment' => [
						'currency' => prebook_get_option( 'payment_currency', 'USD' ),
						'currency_symbol' => prebook_get_option( 'payment_currency_symbol', '$' ),
						'currency_position' => prebook_get_option( 'payment_currency_position', 'left' ),
						'default_payment_method' => prebook_get_option( 'payment_default_payment_method', 'cash' ),
						'methods' => prebook_get_option( 'payment_methods', [] ),
						'default_payment_status' => prebook_get_option( 'payment_default_payment_status', 'pending' ),
					],
				],
				'business' => $this->get_business_info(),
				'customizer' => $this->get_customizer_info(),
				'addons' => $this->get_addons_info(),
				'options' => $this->get_options(),
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
				'customer_id' => get_current_user_id(),
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

		/**
		 * Render prebook modal
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function render_prebook_modal() {
			$this->view( 'frontend/booking/modal');
		}

		/**
		 * Get business info
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function get_business_info() {

			$country_code = prebook_get_option( 'business_country' );

			// Get country name from options class.
			$country = \Prebook\Options::get_instance()->get_country_by_code( $country_code );

			return [
				'logo_url' => prebook_get_option( 'business_logo_url' ),
				'full_logo_url' => prebook_get_option( 'full_logo_url' ),
				'name' => prebook_get_option( 'business_name' ),
				'type' => prebook_get_option( 'business_type' ),
				'about' => prebook_get_option( 'business_about' ),
				'phone' => prebook_get_option( 'business_phone' ),
				'email' => prebook_get_option( 'business_email' ),
				'website' => prebook_get_option( 'business_website' ),
				'address' => [
					'street' => prebook_get_option( 'business_street' ),
					'city' => prebook_get_option( 'business_city' ),
					'state' => prebook_get_option( 'business_state' ),
					'zip_code' => prebook_get_option( 'business_zip_code' ),
					'country' => prebook_get_option( 'business_country' ),
					'country_name' => $country ? $country['name'] : '',
				],
			];
		}

		/**
		 * Get customizer info
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function get_customizer_info() {
			$settings = new \Prebook\Settings();
			return $settings->get_section( 'customizer' );
		}

		/**
		 * Get addon info
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function get_addons_info() {
			$settings = new \Prebook\Settings();
			return $settings->get_section( 'addons' );
		}

		/**
		 * Get options.
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function get_options() {
			return [
				'color_sets' => \Prebook\Options::get_instance()->get_color_sets(),
			];
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
