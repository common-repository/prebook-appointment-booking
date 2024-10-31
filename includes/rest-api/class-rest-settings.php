<?php
/**
 * Prebook Settings REST.
 *
 * @package Prebook
 * @since 1.0.0
 */

// Prebook namespace.
namespace Prebook\REST;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! class_exists( __NAMESPACE__ . 'Settings' ) ) {

	/**
	 *  Prebook Settings REST.
	 *
	 * @package Prebook
	 * @since 1.0.0
	 */
	class Settings extends \Prebook\Base\REST {


		/**
		 * Permission callback for the REST API.
		 *
		 * @since 1.0.0
		 * @return bool
		 */
		public function permission_callback() {
			return current_user_can( 'manage_options' );
		}

		/**
		 * Endpoints.
		 *
		 * @since 1.0.0
		 * @return void
		 * @access protected
		 */
		public function endpoints() {
			return apply_filters( 'prebook_rest_settings_endpoints', [
				[
					'route' => '/settings',
					'methods' => 'GET',
					'callback' => [ $this, 'get_settings' ],
				],
				[
					'route' => '/settings/(?P<section>[a-zA-Z0-9-]+)',
					'methods' => 'GET',
					'callback' => [ $this, 'get_settings' ],
				],
				[
					'route' => '/settings/(?P<section>[^/]+)/(?P<key>[^/]+)',
					'methods' => 'GET',
					'callback' => [ $this, 'get_settings' ],
				],
				[
					'route' => '/settings',
					'methods' => [ 'POST', 'PUT' ],
					'callback' => [ $this, 'update_settings' ],
				],
				[
					'route' => '/settings',
					'methods' => [ 'DELETE' ],
					'callback' => [ $this, 'reset_settings' ],
				],
			] );
		}

		/**
		 * Get settings.
		 *
		 * @since 1.0.0
		 * @param \WP_REST_Request $request Request object.
		 * @return \WP_REST_Response
		 */
		public function get_settings( $request ) {
			$settings = \Prebook\Settings::get_instance()->get_settings();

			if ( $request->has_param( 'section' ) ) {
				$section = $settings[ $request->get_param( 'section' ) ];

				if ( $request->has_param( 'key' ) ) {
					$key = $request->get_param( 'key' );
					return rest_ensure_response( isset( $section[ $key ] ) ? $section[ $key ] : null );
				}

				return rest_ensure_response( $section );
			}

			return rest_ensure_response( $settings );
		}

		/**
		 * Update settings.
		 *
		 * @since 1.0.0
		 * @param \WP_REST_Request $request Request object.
		 * @return \WP_REST_Response
		 */
		public function update_settings( $request ) {
			$sections = \Prebook\Settings::get_instance()->get_setting_options();

			foreach ( $sections as $section => $values ) {
				if ( ! $request->has_param( $section ) ) {
					continue;
				}

				foreach ( $values as $key => $value ) {

					if ( ! isset( $request->get_param( $section )[ $key ] ) ) {
						continue;
					}

					$value = $request->get_param( $section )[ $key ];
					if ( 'addons' === $section ) {
						$value = intval( $value );
					}

					prebook_update_option( $section . '_' . $key, $value );
					do_action( 'prebook_option_updated', $section . '_' . $key, $value );
				}
			}

			return $this->success( 'Settings updated' );
		}

		/**
		 * Reset settings.
		 *
		 * @since 1.0.0
		 * @return \WP_REST_Response
		 */
		public function reset_settings() {
			$sections = \Prebook\Settings::get_instance()->get_setting_options();

			foreach ( $sections as $section => $values ) {
				foreach ( $values as $key => $value ) {
					prebook_delete_option( $section . '_' . $key );
				}
			}

			return $this->success( 'Settings reset' );
		}
	}

	// Init.
	Settings::init();
}
