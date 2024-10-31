<?php
/**
 * Base REST API handler class for Prebook
 *
 * @package Prebook
 * @since 1.0.0
 */

// Prebook namespace.
namespace Prebook\Base;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! class_exists( __NAMESPACE__ . 'REST' ) ) {

	/**
	 * Base REST API handler class for Prebook
	 *
	 * @package Prebook
	 * @since 1.0.0
	 */
	abstract class REST extends Controller {

		/**
		 * Namespace for the REST API.
		 *
		 * @since 1.0.0
		 * @var string
		 * @access protected
		 */
		protected $namespace = 'prebook';

		/**
		 * Permission callback for the REST API.
		 *
		 * @since 1.0.0
		 * @return bool
		 */
		protected function permission_callback() {
			return current_user_can( 'manage_options' );
		}


		/**
		 * Executes the actions hooks for the child class.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function actions() {
			$this->register_rest_endpoints();
		}

		/**
		 * Register the REST API Endpoints.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		protected function register_rest_endpoints() {
			$endpoints = $this->endpoints();

			// Bail if no REST API endpoints are defined.
			if ( empty( $endpoints ) ) {
				return;
			}

			// Register the Ajax actions.
			foreach ( $endpoints as $endpoint ) {
				register_rest_route(
					$this->namespace,
					$endpoint['route'],
					[
						'methods'  => isset( $endpoint['methods'] ) ? $endpoint['methods'] : 'GET',
						'callback' => isset( $endpoint['callback'] ) && is_callable( $endpoint['callback'] ) ? $endpoint['callback'] : [ $this, 'blank_callback' ],
						'permission_callback' => isset ( $endpoint['permission_callback'] ) ? $endpoint['permission_callback'] : [ $this, 'permission_callback' ],
						'args'    => isset( $endpoint['args'] ) ? $endpoint['args'] : [],
					]
				);
			}
		}

		/**
		 * Returns the list of REST API endpoints.
		 *
		 * @since 1.0.0
		 * @return array<string, callable>
		 */
		public function endpoints() {
			return [];
		}

		/**
		 * Calls the blank callback for the REST API.
		 *
		 * @since 1.0.0
		 * @return void
		 * @access public
		 */
		public function blank_callback() {
			return;
		}

		/**
		 * Response with success.
		 *
		 * @since 1.0.0
		 * @param mixed $data Data to return.
		 */
		protected function success( $data ) {
			$payload = [
				'success' => true,
			];

			if ( is_array( $data ) ) {
				$payload['data'] = $data;
			} else {
				$payload['message'] = $data;
			}

			return new \WP_REST_Response( $payload, 200 );
		}

		/**
		 * Response with error.
		 *
		 * @since 1.0.0
		 * @param mixed $data Data to return.
		 */
		protected function error( $data, $code = 200 ) {
			$payload = [
				'success' => false,
			];

			if ( is_array( $data ) ) {
				$payload['data'] = $data;
			} else {
				$payload['message'] = $data;
			}

			return new \WP_REST_Response( $payload, $code );
		}
	}
}
