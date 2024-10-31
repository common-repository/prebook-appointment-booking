<?php
/**
 * Prebook Error Handler
 *
 * @package Prebook
 * @since 1.0.0
 */

// Prebook namespace.
namespace Prebook;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! class_exists( __NAMESPACE__ . '\Error' ) ) {
	/**
	 * Error handler class for Prebook
	 *
	 * @package Prebook
	 * @since 1.0.0
	 */
	class Error extends \Prebook\Base {
		/**
		 * Get error codes.
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function get_error_codes() {
			$errors = [
				'category_exists' => __( 'Category Exists', 'prebook' ),
				'category_not_exists' => __( 'Category Not Exists', 'prebook' ),

				'service_exists' => __( 'Service Exists', 'prebook' ),
				'service_not_exists' => __( 'Service Not Exists', 'prebook' ),

				'stuff_exists' => __( 'Staff Exists', 'prebook' ),
				'stuff_not_exists' => __( 'Staff Not Exists', 'prebook' ),

				'appointment_exists' => __( 'Appointment Exists', 'prebook' ),
				'appointment_not_exists' => __( 'Appointment Not Exists', 'prebook' ),

				'invalid_request' => __( 'Invalid Request', 'prebook' ),
				'invalid_request_method' => __( 'Invalid Request Method', 'prebook' ),

				'invalid_category_id' => __( 'Invalid Category ID', 'prebook' ),
				'invalid_service_id' => __( 'Invalid Service ID', 'prebook' ),
				'invalid_staff_id' => __( 'Invalid Staff ID', 'prebook' ),
				'invalid_appointment_id' => __( 'Invalid Appointment ID', 'prebook' ),

			];

			return apply_filters( 'prebook_error_codes', $errors );
		}

		/**
		 * Get error message.
		 *
		 * @since 1.0.0
		 * @param string $code Error code.
		 * @return array Error message.
		 */
		public static function message( $code ) {
			$error = new Error();
			$errors = $error->get_error_codes();

			return isset( $errors[ $code ] ) ? [
				'success' => false,
				'code' => $code,
				'message' => $errors[ $code ],
			] : [
				'success' => false,
				'code' => $code,
				'message' => __( 'Error: ', 'prebook' ) . $code,
			];
		}
	}
}
