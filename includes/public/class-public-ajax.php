<?php
/**
 * Public AJAX class for Prebook
 *
 * @package Prebook
 * @since 1.0.0
 */

// Prebook namespace.
namespace Prebook\Publics;

// Models.
require_once PREBOOK_INCLUDES . 'models/class-model-category.php';
require_once PREBOOK_INCLUDES . 'models/class-model-service.php';
require_once PREBOOK_INCLUDES . 'models/class-model-appointment.php';
require_once PREBOOK_INCLUDES . 'models/class-model-staff.php';
require_once PREBOOK_INCLUDES . 'models/class-model-customer.php';

// Use models.
use Prebook\Model\Category;
use Prebook\Model\Service;
use Prebook\Model\Appointment;
use Prebook\Model\Staff;
use Prebook\Model\Customer;

// Use Error.
use Prebook\Error;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

// Input trait.
require_once PREBOOK_INCLUDES . 'helper/trait-input.php';

if ( ! class_exists( __NAMESPACE__ . 'Ajax' ) ) {

	/**
	 * Public AJAX class for Prebook
	 *
	 * @package Prebook
	 * @since 1.0.0
	 */
	class Ajax extends \Prebook\Base\Ajax {

		// Use Input trait.
		use \Prebook\Helper\Input;

		/**
		 * Ajax actions.
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function ajax_actions() {
			return [
				'get_settings' => [ $this, 'get_settings' ],
				'get_services' => array( $this, 'get_services' ),
				'get_staffs' => array( $this, 'get_staffs' ),

				'get_available_dates' => array( $this, 'get_available_dates' ),
				'get_available_slots' => array( $this, 'get_available_slots' ),

				'register_customer' => array( $this, 'register_customer' ),
				'login_customer' => array( $this, 'login_customer' ),

				'book_appointment' => array( $this, 'book_appointment' ),
			];
		}

		/**
		 * Whitelisted Ajax actions.
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function whitelist_ajax_actions() {
			return array(
				'get_settings',
				'get_services',
				'get_staffs',
				'get_available_dates',
				'get_available_slots',
				'register_customer',
				'login_customer',
				'book_appointment',
			);
		}

		/**
		 * Get settings.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function get_settings() {
			$settings = \Prebook\Settings::get_instance()->get_settings();

			$output = array(
				'payment' => isset( $settings['payment'] ) ? $settings['payment'] : [],
				'calendar' => isset( $settings['calendar'] ) ? $settings['calendar'] : [],
				'business' => isset( $settings['business'] ) ? $settings['business'] : [],
			);

			wp_send_json_success( $output );
		}

		/**
		 * Get categories.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function get_services() {
			// Check nonce.
			check_ajax_referer( 'prebook_admin_nonce', 'nonce');

			$categories = Category::get_instance()->get_published();

			if ( $categories && ! empty ( $categories ) ) {
				$categories = array_map( function ( $cat ) {
					$row = $cat->get_data();
					$row['color'] = $cat->get_meta('color', '#000');
					$row['priority'] = $cat->get_meta('priority', null);
					return $row;
				}, $categories );
			}

			$services = Service::get_instance()->get_published();

			// Bail, if no services found.
			if ( empty( $services ) ) {
				wp_send_json_success( array() );
			}

			// Filter by status.
			$services = array_filter( $services, function ( $ser ) {
				return false === wp_validate_boolean( $ser->get_meta('is_private') );
			});

			if ( $services && ! empty ( $services ) ) {
				$services = array_map([ $this, 'format_service' ], $services );
			}

			wp_send_json_success( [
				'categories' => $categories && ! empty( $categories ) ? $categories : [],
				'services' => $services && ! empty( $services ) ? $services : [],
			] );
		}

		/**
		 * Format service.
		 *
		 * @param Service $ser Service.
		 * @return mixed
		 */
		public function format_service( $ser ) {
			$row = $ser->get_data();
			$row['color'] = $ser->get_meta('color', '#000');
			$row['order'] = $ser->get_meta('order', null);
			$row['thumbnail_image_url'] = $ser->get_meta('thumbnail_image_url', '');
			$row['required_deposit'] = $ser->get_meta('required_deposit', false);
			$row['deposit_amount'] = $ser->get_meta('deposit_amount', 0);
			$row['deposit_type'] = $ser->get_meta('deposit_type', 0);
			$row['price'] = $ser->get_meta('price', 0);
			$row['duration'] = $ser->get_meta('duration', 0);
			$row['duration_unit'] = $ser->get_meta('duration_unit', 'min');
			$row['booking_type'] = $ser->get_meta('booking_type', []);
			$row['price_type'] = $ser->get_meta('price_type');
			return $row;
		}

		/**
		 * Get staffs.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function get_staffs() {
			// Check nonce.
			check_ajax_referer( 'prebook_admin_nonce', 'nonce');

			$service_id = isset( $_REQUEST['service_id'] ) ? intval( wp_unslash( $_REQUEST['service_id'] ) ) : 0;

			// Bail, if no service id.
			if ( empty( $service_id ) ) {
				wp_send_json( Error::message( __( 'Service id is required.', 'prebook' ) ) );
			}

			$service = Service::get_instance()->find( $service_id );

			// Bail, if no service found.
			if ( empty( $service ) ) {
				wp_send_json( Error::message( __( 'Service not found.', 'prebook' ) ) );
			}

			$staffs = $service->get_staffs();

			// Bail, if no staffs found.
			if ( empty( $staffs ) ) {
				wp_send_json_success( array() );
			}

			// Filter by is_private.
			$staffs = array_filter( $staffs, function ( $staff ) {
				if ( $staff && is_object( $staff ) && true === wp_validate_boolean( $staff->get_meta('is_private', false) ) ) {
					return false;
				}

				// Filter by status.
				if ( 'publish' !== $staff->status ) {
					return false;
				}

				return true;
			});

			$staffs = array_map( function ( $row ) { // phpcs:ignore
				$row = array(
					'staff_id' => $row->staff_id,
					'name' => $row->name,
					'first_name' => $row->get_meta('first_name', ''),
					'last_name' => $row->get_meta('last_name', ''),
					'profile_image_url' => $row->get_meta('profile_image_url', ''),
					'cover_images' => $row->get_meta('cover_images', []),
					'thumbnail_image_url' => $row->get_meta('thumbnail_image_url', ''),
					'bio' => $row->get_meta('bio', ''),
					'highlights' => $row->get_meta('highlights', ''),
					'title' => $row->get_meta('title', ''),
					'color' => $row->get_meta('color', ''),
				);
				return $row;
			}, $staffs );

			wp_send_json_success( $staffs );
		}

		/**
		 * Get available dates.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function get_available_dates() {
			// Check nonce.
			check_ajax_referer( 'prebook_admin_nonce', 'nonce');

			$service_id = isset( $_REQUEST['service_id'] ) ? intval( wp_unslash( $_REQUEST['service_id'] ) ) : 0;

			// Bail, if no service id.
			if ( empty( $service_id ) ) {
				wp_send_json( Error::message( __( 'Service id is required.', 'prebook' ) ) );
			}

			$service = Service::get_instance()->find( $service_id );

			// Bail, if no service found.
			if ( empty( $service ) ) {
				wp_send_json( Error::message( __( 'Service not found.', 'prebook' ) ) );
			}

			$staff_id = 0;

			if ( isset( $_REQUEST['staff_id'] ) && ! empty( $_REQUEST['staff_id'] ) ) {
				$staff_id = intval( wp_unslash( $_REQUEST['staff_id'] ) );

				$staff = Staff::get_instance()->find( $staff_id );

				// Bail, if no staff found.
				if ( empty( $staff ) ) {
					wp_send_json( Error::message( __( 'Staff not found.', 'prebook' ) ) );
				}

				// Bail if staff not assigned to service.
				if ( ! $service->has_staff( $staff_id ) ) {
					wp_send_json( Error::message( __( 'Staff not assigned to service.', 'prebook' ) ) );
				}
			}

			if ( $staff_id > 0 ) {
				$slot = new \Prebook\Slot( $service, $staff );
			} else {
				$slot = new \Prebook\Slot( $service );
			}

			$max_days = isset( $_REQUEST['max_days'] ) ? intval( wp_unslash( $_REQUEST['max_days'] ) ) : 365;
			$get_active_dates = $slot->get_active_dates( $max_days );

			$appointments = Appointment::get_instance()
			->get_in_available_days( $service_id, $staff_id );

			$appointments = $appointments && ! empty( $appointments ) ? $appointments : array();

			$dates = array();

			global $wpdb;
			foreach ( $get_active_dates as $date ) {

				$count = 0;

				if ( ! empty( $appointments ) ) {
					foreach ( $appointments as $appointment ) {
						if ( gmdate( 'Y-m-d', strtotime( $appointment->booking_date ) ) === $date ) {
							$count = intval($appointment->count);
							break;
						}
					}
				}

				$slots = $slot->get_slots_count( $date );

				$dates[] = array(
					'date' => $date,
					'total' => $slots,
					'booked' => $count,
					'available' => $slots - $count,
				);
			}

			wp_send_json_success( $dates );
		}

		/**
		 * Get available slots.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function get_available_slots() {
			// Check nonce.
			check_ajax_referer( 'prebook_admin_nonce', 'nonce');

			$service_id = isset( $_REQUEST['service_id'] ) ? intval( wp_unslash( $_REQUEST['service_id'] ) ) : 0;

			// Bail, if no service id.
			if ( empty( $service_id ) ) {
				wp_send_json( Error::message( __( 'Service id is required.', 'prebook' ) ) );
			}

			$service = Service::get_instance()->find( $service_id );

			// Bail, if no service found.
			if ( empty( $service ) ) {
				wp_send_json( Error::message( __( 'Service not found.', 'prebook' ) ) );
			}

			$addons_staff = prebook_get_option( 'addons_staffs', true );

			if ( $addons_staff ) {

				$staff_id = null;

				if ( isset( $_REQUEST['staff_id'] ) && ! empty( $_REQUEST['staff_id'] ) ) {
					$staff_id = intval( wp_unslash( $_REQUEST['staff_id'] ) );

					$staff = Staff::get_instance()->find( $staff_id );

					// Bail, if no staff found.
					if ( empty( $staff ) ) {
						wp_send_json( Error::message( __( 'Staff not found.', 'prebook' ) ) );
					}

					// Bail if staff not assigned to service.
					if ( ! $service->has_staff( $staff_id ) ) {
						wp_send_json( Error::message( __( 'Staff not assigned to service.', 'prebook' ) ) );
					}
				}
			}

			// Customer id.
			$customer_id = null;
			if ( isset( $_REQUEST['customer_id'] ) && ! empty( $_REQUEST['customer_id'] ) ) {
				$customer_id = intval( wp_unslash( $_REQUEST['customer_id'] ) );
			}

			// Booking date.
			$booking_date = isset( $_REQUEST['date'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['date'] ) ) : '';

			// Set booking date to today, if empty.
			if ( empty( $booking_date ) ) {
				$booking_date = gmdate( 'Y-m-d' );
			} else {
				$booking_date = gmdate( 'Y-m-d', strtotime( $booking_date ) );
			}

			if ( $addons_staff && $staff_id ) {
				$slot = new \Prebook\Slot( $service, $staff );
			} else {
				$slot = new \Prebook\Slot( $service );
			}

			// Bail, if slot is not active.
			if ( ! $slot->is_active_on( $booking_date ) ) {
				wp_send_json_success( array() );
			}

			// Calculate slots.
			$slots = $slot->get_slots($booking_date);

			// Bail, if no slots found.
			if ( empty( $slots ) ) {
				wp_send_json_success( array() );
			}

			// Get appointments for the day.
			global $wpdb;
			$appointments = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}prebook_appointments WHERE booking_date = %s AND ( staff_id = %d OR customer_id = %d)", $booking_date, $staff_id ? $staff_id : 0, $customer_id ? $customer_id : 0 ) );
			$appointments = $appointments && ! empty( $appointments ) ? $appointments : array();

			// Calculate available slots.
			$available_slots = array();

			foreach ( $slots as $slot ) {
				$slot_start = strtotime( $slot['start'] );
				$slot_end = strtotime( $slot['end'] );

				$available = true;
				$message = '';

				global $wpdb;
				$has_appointment = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}prebook_appointments WHERE booking_date = %s AND ( staff_id = %d OR customer_id = %d) AND (
					(booking_time_start < %s AND booking_time_end > %s) OR  -- Case 1: Booking completely overlaps slot
					(booking_time_start > %s AND booking_time_end < %s) OR  -- Case 2: Slot completely overlaps booking
					(booking_time_start < %s AND booking_time_end > %s)     -- Case 3: Booking starts before and ends after slot
				)", $booking_date,
					$staff_id ? $staff_id : 0,
					$customer_id ? $customer_id : 0,
					$slot['end'], // Slot end time
					$slot['start'], // Slot start time
					$slot['start'], // Slot start time (for Case 2)
					$slot['end'],   // Slot end time (for Case 2)
					$slot['start'], // Slot start time (for Case 3)
					$slot['end'] ) );

				if ( ! empty( $has_appointment ) ) {
					// $available = false;

					$available = false;

					$message = __( 'Slot is not available.', 'prebook' );

					// If Same staff.
					if ( ! is_null(  $staff_id ) && $staff_id === $has_appointment->staff_id ) {
						$message = __( 'Staff is not available.', 'prebook' );
					}

					// If Same customer.
					if ( $customer_id === $has_appointment->customer_id ) {
						$message = __( 'You already have an appointment on the same time.', 'prebook' );
					}
				}

				$available_slots[] = array(
					'start' => gmdate( 'H:i', $slot_start ),
					'end' => gmdate( 'H:i', $slot_end ),
					'available' => $available,
					'message' => $message,
				);
			}

			wp_send_json_success( $available_slots );
		}

		/**
		 * Register customer.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function register_customer() {
			// Check nonce.
			check_ajax_referer( 'prebook_admin_nonce', 'nonce');

			$email = isset( $_REQUEST['email'] ) ? sanitize_email( wp_unslash( $_REQUEST['email'] ) ) : '';

			// Bail, if no email.
			if ( empty( $email ) ) {
				wp_send_json( Error::message( __( 'Email is required.', 'prebook' ) ) );
			}

			// Bail, if email is invalid.
			if ( ! is_email( $email ) ) {
				wp_send_json( Error::message( __( 'Invalid email.', 'prebook' ) ) );
			}

			// Bail, if email already exists.
			if ( email_exists( $email ) ) {
				wp_send_json( Error::message( __( 'Email already exists.', 'prebook' ) ) );
			}

			$customer_id = Customer::get_instance()->create( array(
				'user_email' => $email,
				'user_login' => $email,

				// Optional.
				'user_pass' => isset( $_REQUEST['password'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['password'] ) ) : wp_generate_password( 12, false ),

				'first_name' => isset( $_REQUEST['first_name'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['first_name'] ) ) : '',
				'last_name' => isset( $_REQUEST['last_name'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['last_name'] ) ) : '',
			), array(
				'phone' => isset( $_REQUEST['phone'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['phone'] ) ) : '',
				'email' => $email,
			) );

			// Bail, if error.
			if ( is_wp_error( $customer_id ) ) {
				wp_send_json( Error::message( $customer_id->get_error_message() ) );
			}

			// Return customer id.
			$customer = Customer::get_instance()->find( $customer_id );

			// Trigger customer registered action.
			do_action( 'prebook_customer_registered', $customer, $_REQUEST );

			// Make customer login.
			wp_set_current_user( $customer->get_id(), $customer->user_login );
			wp_set_auth_cookie( $customer->get_id() );

			wp_send_json_success( array(
				'customer_id' => $customer->get_id(),
				'name' => $customer->name,
				'email' => $customer->user_email,
				'phone' => $customer->get_meta( 'phone', '' ),
				'address' => $customer->get_meta( 'address', '' ),
				'profile_image_url' => $customer->get_meta( 'profile_image_url', '' ),
				'nonce' => wp_create_nonce( 'prebook_admin_nonce' ),
			) );
		}

		/**
		 * Login customer.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function login_customer() {
			// Check nonce.
			check_ajax_referer( 'prebook_admin_nonce', 'nonce');

			$email = isset( $_REQUEST['email'] ) ? sanitize_email( wp_unslash( $_REQUEST['email'] ) ) : '';

			// Bail, if no email.
			if ( empty( $email ) ) {
				wp_send_json( Error::message( __( 'Email is required.', 'prebook' ) ) );
			}

			// Bail, if email is invalid.
			if ( ! is_email( $email ) ) {
				wp_send_json( Error::message( __( 'Invalid email.', 'prebook' ) ) );
			}

			// Bail, if email already exists.
			if ( ! email_exists( $email ) ) {
				wp_send_json( Error::message( __( 'Email does not exists.', 'prebook' ) ) );
			}

			// Check password.
			$password = isset( $_REQUEST['password'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['password'] ) ) : '';

			// Bail, if no password.
			if ( empty( $password ) ) {
				wp_send_json( Error::message( __( 'Password is required.', 'prebook' ) ) );
			}

			// Check credentials.

			$customer = get_user_by('email', $email);

			// Bail, if error.
			if ( is_wp_error( $customer ) ) {
				wp_send_json( Error::message( $customer->get_error_message() ) );
			}

			// Attempt to authenticate the user
			$user_data = wp_authenticate($customer->user_login, $password);

			if ( is_wp_error( $user_data ) ) {
				wp_send_json( [
					'error' => true,
					'message' => $user_data->get_error_message(),
				] );
				// wp_send_json( Error::message( $customer->get_error_message() ) );
			}

			// Return customer.
			$customer = Customer::get_instance()->find( $customer->ID );

			// Make customer login.
			wp_set_current_user( $customer->get_id(), $customer->user_login );
			wp_set_auth_cookie( $customer->get_id() );

			wp_send_json_success( array(
				'customer_id' => $customer->get_id(),
				'name' => $customer->name,
				'email' => $customer->user_email,
				'phone' => $customer->get_meta( 'phone', '' ),
				'address' => $customer->get_meta( 'address', '' ),
				'profile_image_url' => $customer->get_meta( 'profile_image_url', '' ),
				'nonce' => wp_create_nonce( 'prebook_admin_nonce' ),
			) );
		}

		/**
		 * Book appointment.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function book_appointment() {

			// Check nonce.
			check_ajax_referer( 'prebook_admin_nonce', 'nonce');

			$settings = \Prebook\Settings::get_instance()->get_section( 'appointment' );

			$booking_date = isset( $_REQUEST['booking_date'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['booking_date'] ) ) : gmdate( 'Y-m-d' );
			$booking_time_start = isset( $_REQUEST['booking_time_start'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['booking_time_start'] ) ) : gmdate( 'H:i:s' );
			$booking_time_end = isset( $_REQUEST['booking_time_end'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['booking_time_end'] ) ) : gmdate( 'H:i:s', strtotime( '+1 hour' ) );

			$data = array(
				'name' => 'Appointment #',
				'booking_date' => gmdate('Y-m-d', strtotime( $booking_date )),
				'booking_time_start' => gmdate('H:i', strtotime( $booking_time_start )),
				'booking_time_end' => gmdate('H:i', strtotime( $booking_time_end )),

			);

			$meta = array(
				'status' => $settings['default_status'],
			);

			// If has service id.
			if ( isset( $_REQUEST['service_id'] ) && ! empty( $_REQUEST['service_id'] ) ) {
				$service_id = intval( wp_unslash( $_REQUEST['service_id'] ) );

				$service = Service::get_instance()->find( $service_id );

				// Bail, if no service found.
				if ( empty( $service ) ) {
					wp_send_json( Error::message( __( 'Service not found.', 'prebook' ) ) );
				}

				$data['service_id'] = $service_id;

				// Add service name, duration, price, etc as meta.
				$meta['service_name'] = $service->name;
				$meta['service_price'] = $service->get_meta( 'price', 0 );
				$meta['service_duration'] = $service->get_duration() * ( 'hours' === $service->get_meta( 'duration_unit', 'minutes' ) ? 60 : 1 );
			}

			// If has staff id.
			if ( isset( $_REQUEST['staff_id'] ) && ! empty( $_REQUEST['staff_id'] ) ) {
				$staff_id = intval( wp_unslash( $_REQUEST['staff_id'] ) );

				$staff = Staff::get_instance()->find( $staff_id );

				// Bail, if no staff found.
				if ( empty( $staff ) ) {
					wp_send_json( Error::message( __( 'Staff not found.', 'prebook' ) ) );
				}

				// Bail if staff not assigned to service.
				if ( isset( $service ) && ! $service->has_staff( $staff_id ) ) {
					wp_send_json( Error::message( __( 'Staff not assigned to service.', 'prebook' ) ) );
				}

				$data['staff_id'] = $staff_id;

				// Add staff name, email, phone, etc as meta.
				$meta['staff_name'] = $staff->name;
				$meta['staff_email'] = $staff->user_email;
				$meta['staff_phone'] = $staff->get_meta( 'phone', '' );

			}

			$customer_id = 0;
			// If has customer id.
			if ( isset( $_REQUEST['customer_id'] ) && ! empty( $_REQUEST['customer_id'] ) ) {
				$customer_id = intval( wp_unslash( $_REQUEST['customer_id'] ) );

				$customer = \Prebook\Model\Customer::get_instance()->find( $customer_id );

				// Bail, if no customer found.
				if ( empty( $customer ) ) {
					wp_send_json( Error::message( __( 'Customer not found.', 'prebook' ) ) );
				}

				$data['customer_id'] = $customer_id;

				// Add customer name, email, phone, etc as meta.
				$meta['customer_name'] = $customer->name;
				$meta['customer_email'] = $customer->email;
				$meta['customer_phone'] = $customer->get_meta( 'phone', '' );
				$meta['customer_first_name'] = $customer->get_meta( 'first_name', '' );
				$meta['customer_last_name'] = $customer->get_meta( 'last_name', '' );
				$meta['customer_email'] = $customer->get_meta( 'email', '' );

			} else {
				$allow_guest_booking = apply_filters( 'prebook_allow_guest_booking', false );

				if ( ! $allow_guest_booking ) {
					wp_send_json( Error::message( __( 'No customer found.', 'prebook' ) ) );
				}

				$data['customer_id'] = 0;
			}

			// Customer name, email, phone, etc from input.
			if ( isset( $_REQUEST['customer_name'] ) && ! empty( $_REQUEST['customer_name'] ) ) {
				$meta['customer_name'] = sanitize_text_field( wp_unslash( $_REQUEST['customer_name'] ) );
			}

			if ( isset( $_REQUEST['customer_email'] ) && ! empty( $_REQUEST['customer_email'] ) ) {
				$meta['customer_email'] = sanitize_email( wp_unslash( $_REQUEST['customer_email'] ) );
			}

			if ( isset( $_REQUEST['customer_phone'] ) && ! empty( $_REQUEST['customer_phone'] ) ) {
				$meta['customer_phone'] = sanitize_text_field( wp_unslash( $_REQUEST['customer_phone'] ) );
			}

			// If service and staff exist, check the slot.
			if ( isset( $data['service_id'] ) ) {
				if ( $data['staff_id'] ) {
					$slot = new \Prebook\Slot( $service, $staff );
				} else {
					$slot = new \Prebook\Slot( $service );
				}

				// Bail, if slot is not active.
				$is_slot_available = $slot->is_slot_available( $data['booking_date'], $data['booking_time_start'], $data['booking_time_end'] );
				if ( ! $is_slot_available ) {
					wp_send_json( Error::message( __( 'Slot is not available.', 'prebook' ) ) );
				}
			} else {
				$is_service_required = apply_filters( 'prebook_is_service_required', false );

				if ( $is_service_required ) {
					wp_send_json( Error::message( __( 'Service is required.', 'prebook' ) ) );
				}
			}

			// Check if customer already has an appointment on the same date.
			if ( isset( $data['service_id'] ) ) {
				$overlapps = Appointment::get_instance()->get_between_time( $data['booking_date'], $data['booking_time_start'], $data['customer_id'] );

				if ( ! empty( $overlapps ) ) {
					wp_send_json( Error::message( __( 'You already have an appointment on the same time.', 'prebook' ) ) );
				}
			}

			// Appointment type.
			$appointment_type = isset( $_REQUEST['type'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['type'] ) ) : 'onsite';

			switch ( $appointment_type ) {
				case 'home_service':
					$address = '';

					if ( isset( $_REQUEST['address'] ) && ! empty( $_REQUEST['address'] ) ) {
						$address = sanitize_textarea_field( wp_unslash( $_REQUEST['address'] ) );
					} elseif ( $data['customer_id'] ) {
						$customer = \Prebook\Model\Customer::get_instance()->find( $data['customer_id'] );
						$address = $customer->get_meta( 'address', '' );
					}

					$meta['address'] = $address;
					break;

				case 'online':
					// Zoom link.
					break;

				default:
					$business = \Prebook\Settings::get_instance()->get_section( 'business' );

					$address = [ $business['street'], $business['city'], $business['state'], $business['zip_code'] ];

					$country_name = \Prebook\Options::get_instance()->get_country_by_code( $business['country'] );

					if ( $country_name ) {
						$address[] = $country_name['name'] ?? '';
					}

					$meta['address'] = implode( ', ', array_filter( $address ) );
					break;

			}

			// Pricing.

			if ( isset( $_REQUEST['price'] ) && ! empty( $_REQUEST['price'] ) ) {
				$meta['price'] = sanitize_text_field( wp_unslash( $_REQUEST['price'] ) );
			} elseif ( isset( $data['service_id'] ) ) {
				$meta['price'] = $service->get_meta( 'price', 0 );
			}

			if ( isset( $_REQUEST['deposit_amount'] ) && ! empty( $_REQUEST['deposit_amount'] ) ) {
				$meta['deposit_amount'] = intval( wp_unslash( $_REQUEST['deposit_amount'] ) );
			} elseif ( isset( $data['service_id'] ) ) {
				$meta['deposit_amount'] = $service->get_meta( 'deposit_amount', 0 );
			}

			if ( isset( $_REQUEST['deposit_type'] ) && ! empty( $_REQUEST['deposit_type'] ) ) {
				$meta['deposit_type'] = sanitize_text_field( wp_unslash( $_REQUEST['deposit_type'] ) );
			} elseif ( isset( $data['service_id'] ) ) {
				$meta['deposit_type'] = $service->get_meta( 'deposit_type', 0 );
			}

			// Payment.

			$payment = \Prebook\Settings::get_instance()->get_section( 'payment' );

			$meta['payment_method'] = isset( $_REQUEST['payment_method'] ) && ! empty( $_REQUEST['payment_method'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['payment_method'] ) ) : ( $payment['default_payment_method'] );

			$meta['payment_status'] = isset( $_REQUEST['payment_status'] ) && ! empty( $_REQUEST['payment_status'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['payment_status'] ) ) : ( $payment['default_payment_status'] );

			// Additional.

			// Notes.
			if ( isset( $_REQUEST['customer_note'] ) && ! empty( $_REQUEST['customer_note'] ) ) {
				$meta['customer_note'] = sanitize_textarea_field( wp_unslash( $_REQUEST['customer_note'] ) );
			}

			$data = apply_filters( 'prebook_book_appointment_data', $data, $meta );

			// Create appointment.
			$appointment = Appointment::get_instance()->insert( $data );

			// Update name.
			$appointment->update( array( 'name' => wp_sprintf( 'Appointment #%s', $appointment->appointment_id ) ) );

			// Update meta.
			$meta = apply_filters( 'prebook_book_appointment_meta', $meta, $appointment );

			if ( $meta && ! empty( $meta ) ) {
				foreach ( $meta as $key => $value ) {
					$appointment->update_meta( $key, $value );
				}
			}

			$item = $appointment->find( $appointment->get_id() );

			$output = $item->get_data();
			$output['meta'] = $meta;

			// If customer found. Log in.
			if ( isset( $data['customer_id'] ) && $data['customer_id'] > 0 ) {
				wp_set_auth_cookie( $data['customer_id'], true );
			}

			// Appointment created.
			do_action( 'prebook_appointment_created', $item, $_REQUEST, $meta);

			$output = apply_filters( 'prebook_book_appointment_output', $output, $item, $meta );
			wp_send_json_success( $output );
		}
	}


	// Initialize the class.
	Ajax::init();
}
