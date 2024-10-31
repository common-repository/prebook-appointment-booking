<?php
/**
 * Prebook REST API Hooks.
 *
 * @package Prebook
 * @since 1.0.0
 */

// Prebook namespace.
namespace Prebook\WP_Admin\REST;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! class_exists( __NAMESPACE__ . 'Hooks' ) ) {

	/**
	 * Prebook REST API Hooks.
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
			// Service assigned to staff.
			add_action( 'prebook_service_created', array( $this, 'service_updated' ), 10, 2 );
			add_action( 'prebook_service_updated', array( $this, 'service_updated' ), 10, 2 );

			// Staff assigned to service.
			add_action( 'prebook_service_created', array( $this, 'service_updated' ), 10, 2 );
			add_action( 'prebook_service_updated', array( $this, 'service_updated' ), 10, 2 );

			// Customer updated or created.
			add_action( 'prebook_customer_created', array( $this, 'customer_updated' ), 10, 2 );
			add_action( 'prebook_customer_updated', array( $this, 'customer_updated' ), 10, 2 );

			// Staff updated.
			add_action( 'prebook_staff_created', array( $this, 'staff_updated' ), 10, 2 );
			add_action( 'prebook_staff_updated', array( $this, 'staff_updated' ), 10, 2 );
		}

		/**
		 * Filters.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function filters() {
			add_filter( 'rest_attachment_collection_params', array( $this, 'filter_rest_attachment_query' ), 10, 2 );

			// Appointment allowed fields.
			add_filter( 'prebook_appointment_allowed_fields', array( $this, 'appointment_allowed_fields' ) );

			// Service allowed fields.
			add_filter( 'prebook_service_allowed_fields', array( $this, 'service_allowed_fields' ) );

			// Users and staffs allowed fields.
			add_filter( 'prebook_customer_allowed_fields', array( $this, 'user_allowed_fields' ) );
			add_filter( 'prebook_staff_allowed_fields', array( $this, 'user_allowed_fields' ) );

			// Transaction allowed fields.
			add_filter( 'prebook_transaction_allowed_fields', array( $this, 'transaction_allowed_fields' ) );

			// User order by.
			add_filter( 'prebook_get_customer_order_by', array( $this, 'user_order_by' ) );
			add_filter( 'prebook_get_staff_order_by', array( $this, 'user_order_by' ) );

			//
		}

		/**
		 * Service created or updated.
		 *
		 * @since 1.0.0
		 * @param object   $service Category.
		 * @param object $request    Request body.
		 * @return void
		 */
		public function service_updated( $service, $request ) {

			// Staffs.
			if ( is_object( $service ) && $request->has_param( 'staffs' ) ) {
				$service->set_staffs( $request->get_param( 'staffs' ) );
			}

			// Prices.
			if ( is_object( $service ) && $request->has_param( 'prices' ) ) {
				$service->update_meta( 'prices', $request->get_param( 'prices' ) );
			}
		}

		/**
		 * Appointment allowed fields.
		 *
		 * @since 1.0.0
		 * @param array $allowed_fields Allowed fields.
		 * @return array
		 */
		public function appointment_allowed_fields( $allowed_fields ) {
			return array_merge( $allowed_fields, [ 'service_id', 'customer_id', 'staff_id', 'booking_date', 'booking_time_start', 'booking_time_end' ] );
		}

		/**
		 * Service allowed fields.
		 *
		 * @since 1.0.0
		 * @param array $allowed_fields Allowed fields.
		 * @return array
		 */
		public function service_allowed_fields( $allowed_fields ) {
			return array_merge( $allowed_fields, [ 'category_id' ] );
		}

		/**
		 * Filter rest attachment query.
		 *
		 * @since 1.0.0
		 * @param array           $args Array of arguments for the query.
		 * @param \WP_REST_Request $request The request used.
		 * @return array
		 */
		public function filter_rest_attachment_query( $args, $request ) {
			$args['per_page']['maximum'] = 100;
			return $args;
		}

		/**
		 * User order by.
		 *
		 * @since 1.0.0
		 * @param array $order_by Order by.
		 * @return array
		 */
		public function user_order_by( $order_by ) {
			return 'display_name';
		}

		/**
		 * User allowed fields.
		 *
		 * @since 1.0.0
		 * @param array $allowed_fields Allowed fields.
		 * @return array
		 */
		public function user_allowed_fields( $allowed_fields ) {
			return [
				'display_name',
			];
		}

		/**
		 * Transaction allowed fields.
		 *
		 * @since 1.0.0
		 * @param array $allowed_fields Allowed fields.
		 * @return array
		 */
		public function transaction_allowed_fields( $allowed_fields ) {
			return [ 'appointment_id', 'customer_id', 'payment_method', 'payment_status', 'payment_amount', 'currency', 'meta' ];
		}

		/**
		 * Customer created or updated.
		 *
		 * @since 1.0.0
		 * @param object   $customer Category.
		 * @param object $request    Request body.
		 * @return void
		 */
		public function customer_updated( $customer, $request ) {
			// Update notes.
			if ( is_object( $customer ) && $request->has_param ('notes') ) {
				$customer->update_meta( 'notes', $request->get_param( 'notes' ) );
			}

			// Update email.
			if ( is_object( $customer ) && $request->has_param ('email') ) {
				$customer->set_email( $request->get_param( 'email' ) );
			}
		}

		/**
		 * Appointment created.
		 *
		 * @since 1.0.0
		 * @param object   $appointment Appointment.
		 * @param object $request     Request body.
		 * @return void
		 */
		public function appointment_created( $appointment, $request ) {
			// If paid_amount is set, create a transaction.
			if ( $request->has_param( 'paid_amount' ) ) {
				$transaction = new \Prebook\Model\Transaction();

				$default_currency = prebook_get_option( 'payment_currency', 'USD' );
				$default_status = prebook_get_option( 'payment_default_payment_status', 'unpaid' );
				$default_method = prebook_get_option( 'payment_default_payment_method', 'cash' );

				$transaction->insert( [
					'appointment_id' => $appointment->appointment_id,
					'customer_id'    => $appointment->customer_id,
					'payment_method' => $request->has_param( 'payment_method' ) ? $request->get_param( 'payment_method' ) : $default_method,
					'payment_status' => $request->has_param( 'payment_status' ) ? $request->get_param( 'payment_status' ) : $default_status,
					'payment_amount' => $request->get_param( 'paid_amount' ),
					'currency'       => $request->has_param( 'currency' ) ? $request->get_param( 'currency' ) : $default_currency,
				] );
			}
		}

		/**
		 * Staff created or updated.
		 *
		 * @since 1.0.0
		 * @param object   $staff Category.
		 * @param object $request    Request body.
		 */
		public function staff_updated( $staff, $request ) {
			// Services.
			if ( is_object( $staff ) && $request->has_param( 'services' ) ) {
				$staff->set_services( $request->get_param( 'services' ) );
			}

			// Working hours.
			if ( is_object( $staff ) && $request->has_param( 'working_hours' ) ) {
				$staff->update_meta( 'working_hours', $request->get_param( 'working_hours' ) );
			}

			// Days off.
			if ( is_object( $staff ) && $request->has_param( 'days_off' ) ) {
				$staff->update_meta( 'days_off', $request->get_param( 'days_off' ) );
			}

			// Update email.
			if ( is_object( $staff ) && $request->has_param ('meta') && $request->get_param( 'meta' )['email'] ) {
				$staff->set_email( $request->get_param( 'meta' )['email'] );
			}
		}
	}

	// Run the class.
	Hooks::init();
}
