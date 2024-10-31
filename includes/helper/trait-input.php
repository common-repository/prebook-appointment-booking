<?php
/**
 * Input helper trait for Prebook
 *
 * @package Prebook
 * @since 1.0.0
 */

// Prebook namespace.
namespace Prebook\Helper;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! trait_exists( __NAMESPACE__ . '\Input' ) ) {

	/**
	 * Input helper trait for Prebook
	 *
	 * @package Prebook
	 * @since 1.0.0
	 */
	trait Input {
		/**
		 * Get Request method.
		 *
		 * @since 1.0.0
		 * @return string
		 */
		final public function get_request_method() {
			return isset( $_SERVER['REQUEST_METHOD'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_METHOD'] ) ) : 'GET';
		}

		/**
		 * Error response.
		 *
		 * @since 1.0.0
		 * @param string $code error code.
		 * @param string $message error message.
		 * @return array
		 */
		final public function error( $code = 'object_not_found', $message = 'No object found.' ) {
			return [
				'success' => false,
				'code' => $code,
				'message' => $message,
			];
		}

		/**
		 * Success response.
		 *
		 * @param mixed $data data.
		 * @since 1.0.0
		 * @return array
		 */
		final public function success( $data = [] ) {
			$response = [
				'success' => true,
			];

			// If data is empty, return response.
			if ( empty( $data ) ) {
				return $response;
			}

			// If data is array, merge with response.
			if ( is_array( $data ) ) {
				$response['data'] = $data;
			} else {
				$response['message'] = $data;
			}

			return $response;
		}
	}
}
