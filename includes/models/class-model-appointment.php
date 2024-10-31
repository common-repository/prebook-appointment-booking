<?php
/**
 * Prebook Appointment model.
 *
 * @package Prebook
 * @since 1.0.0
 */

// Prebook namespace.
namespace Prebook\Model;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );


// Meta trait.
require_once PREBOOK_INCLUDES . 'helper/trait-meta.php';

if ( ! class_exists( __NAMESPACE__ . '\Appointment' ) ) {

	/**
	 * Prebook Appointment model.
	 *
	 * @package Prebook
	 * @since 1.0.0
	 */
	class Appointment extends \Prebook\Base\Model {

		// Meta trait.
		use \Prebook\Helper\Meta;

		/**
		 * The name of the table.
		 *
		 * @since 1.0.0
		 * @var string
		 */
		protected $table_name = 'appointments';

		/**
		 * Primary key of the table.
		 *
		 * @since 1.0.0
		 * @var string
		 */
		protected $primary_key = 'appointment_id';

		/**
		 * Object type.
		 *
		 * @since 1.0.0
		 * @var string
		 */
		protected $object_type = 'appointment';

		/**
		 * Fillable columns.
		 *
		 * @since 1.0.0
		 * @var array
		 */
		protected $fillable = [
			'appointment_id',
			'status',
			'name',
			'customer_id',
			'staff_id',
			'service_id',
			'booking_date',
			'booking_time_start',
			'booking_time_end',
			'created_at',
			'updated_at',
		];

		/**
		 * Integrated Meta keys
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function meta_keys() {
			return apply_filters( 'prebook_appointment_meta', [
				'status' => '',
				'staff_name' => '',
				'staff_first_name' => '',
				'staff_last_name' => '',
				'staff_email' => '',
				'staff_phone' => '',

				'customer_name' => '',
				'customer_first_name' => '',
				'customer_last_name' => '',
				'customer_email' => '',
				'customer_phone' => '',
				'customer_address' => '',

				'service_name' => '',
				'service_price' => '',
				'service_duration' => '',
				'service_duration_unit' => '',

				'price' => '',
				'duration' => '',
				'address' => '',
				'deposit_amount' => '',
				'deposit_type' => '',
				'payment_method' => '',
				'payment_status' => '',

				'customer_note' => '',
				'appointment_type' => '',
			] );
		}

		/**
		 * Get integrated meta.
		 *
		 * @since 1.0.0
		 * @return mixed
		 */
		public function meta() {
			return $this->get_meta( $this->meta_keys(), new \stdClass() );
		}


		/**
		 * Transactions.
		 *
		 * @since 1.0.0
		 * @return mixed
		 */
		public function transactions() {
			global $wpdb;
			$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}prebook_transactions t WHERE t.appointment_id = %d", $this->appointment_id ));

			if ( empty( $results ) ) {
				return false;
			}

			return array_map( [ $this, 'wrap_data' ], $results );
		}

		/**
		 * Is paid.
		 *
		 * @since 1.0.0
		 * @return bool
		 */
		public function is_paid() {
			$transactions = $this->transactions();
			if ( ! $transactions ) {
				return false;
			}

			$paid = false;
			$payment_amount = 0;
			foreach ( $transactions as $transaction ) {
				if ( 'paid' === $transaction->payment_status ) {
					$payment_amount += $transaction->payment_amount;
				}
			}

			if ( $payment_amount >= $this->get_meta( 'price' ) ) {
				$paid = true;
			}

			return $paid;
		}


        /**
		 * Get all.
		 */
		public function get() {
			global $wpdb;

			$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}prebook_appointments" );

			if ( empty( $results ) ) {
				return false;
			}

			return array_map( [ $this, 'wrap_data' ], $results );
		}

		/**
		 * Get by id.
		 */
		public function find( $id ) {
			global $wpdb;
			$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}prebook_appointments t WHERE t.appointment_id = %d", $id ));

			if ( empty( $results ) ) {
				return false;
			}

            return $this->wrap_data( $results[0] );
		}

		/**
		 * Get by date and staff, service.
		 *
		 * @param string $date Date.
		 * @param int    $staff_id Staff ID.
		 * @param int    $service_id Service ID.
		 * @return mixed
		 */
		public function get_by_date( $date, $staff_id = 0, $service_id = 0 ) {
			global $wpdb;

			if ( $staff_id && $service_id ) {
				$results = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM {$wpdb->prefix}prebook_appointments WHERE booking_date = %s AND staff_id = %d AND service_id = %d', $date, $staff_id, $service_id ) );
			} elseif ( $staff_id ) {
				// $query .= ' AND staff_id = %d';
				$results = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM {$wpdb->prefix}prebook_appointments WHERE booking_date = %s AND staff_id = %d', $date, $staff_id ) );
			} elseif ( $service_id ) {
				// $query .= ' AND service_id = %d';
				$results = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM {$wpdb->prefix}prebook_appointments WHERE booking_date = %s AND service_id = %d', $date, $service_id ) );
			} else {
				$results = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM {$wpdb->prefix}prebook_appointments WHERE booking_date = %s', $date ) );
			}

			if ( empty( $results ) ) {
				return false;
			}

			return array_map( [ $this, 'wrap_data' ], $results );
		}

		/**
		 * Get between time on same date.
		 *
		 * @param string $date Date.
		 * @param string $start Start time.
		 * @param string $end End time.
		 * @param int    $customer ID.
		 * @param int    $staff_id Staff ID.
		 * @param int    $service_id Service ID.
		 * @return mixed
		 */
		public function get_between_time( $date, $start, $end, $customer = null, $staff_id = null, $service_id = null ) {
			global $wpdb;

			$query = "SELECT * FROM {$wpdb->prefix}prebook_appointments WHERE booking_date = %s AND booking_time_start < %s AND booking_time_end > %s";

			if ( $customer ) {
				$query .= ' AND customer_id = %d';
			}

			if ( $staff_id ) {
				$query .= ' AND staff_id = %d';
			}

			if ( $service_id ) {
				$query .= ' AND service_id = %d';
			}

			$results = $wpdb->get_results( $wpdb->prepare( $query, $date, $end, $start, $customer, $staff_id, $service_id ) ); // phpcs:ignore

			if ( empty( $results ) ) {
				return false;
			}

			return array_map( [ $this, 'wrap_data' ], $results );
		}

		/**
		 * Get Between Date.
		 *
		 * @return mixed
		 */
		public function get_in_available_days( $service_id = 0, $staff_id = null ) {
			global $wpdb;

			$query = "SELECT a.booking_date, count(*) as count FROM {$wpdb->prefix}prebook_appointments as a WHERE a.booking_date >= %s AND a.booking_date <= %s GROUP BY a.booking_date";

			if ( $service_id ) {
				$query .= ' AND service_id = %d';
			}

			if ( $staff_id ) {
				$query .= ' AND staff_id = %d';
			}

			$max_days = isset( $_REQUEST['max_days'] ) ? intval( wp_unslash( $_REQUEST['max_days'] ) ) : 365;
			$results = $wpdb->get_results( $wpdb->prepare( $query, date( 'Y-m-d' ), date( 'Y-m-d', strtotime( "+{$max_days} days" ) ), $service_id, $staff_id ) ); // phpcs:ignore

			if ( empty( $results ) ) {
				return false;
			}

			return array_map( [ $this, 'wrap_data' ], $results );
		}

		/**
		 * Get staff.
		 *
		 * @since 1.0.0
		 * @return mixed
		 */
		public function get_staff() {
			$staff = new Staff();
			return $staff->find( $this->staff_id );
		}

		/**
		 * Get service.
		 *
		 * @since 1.0.0
		 * @return mixed
		 */
		public function get_service() {
			$service = new Service();
			return $service->find( $this->service_id );
		}


		/**
		 * Get Customer.
		 *
		 * @since 1.0.0
		 * @return mixed
		 */
		public function get_customer() {
			$customer = new Customer();
			return $customer->find( $this->customer_id );
		}
	}
}
