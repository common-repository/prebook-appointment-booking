<?php
/**
 * Prebook service model.
 *
 * @package Prebook
 * @since 1.0.0
 */

// Prebook namespace.
namespace Prebook\Model;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

// ServiceStaff model.
require_once PREBOOK_INCLUDES . 'models/class-model-service-staffs.php';

// User trait.
require_once __DIR__ . '/class-model-user.php';


if ( ! class_exists( __NAMESPACE__ . '\Staff' ) ) {

	/**
	 * Prebook service model.
	 *
	 * @package Prebook
	 * @since 1.0.0
	 */
	class Staff extends \Prebook\Model\User {

		/**
		 * Columns.
		 *
		 * @since 1.0.0
		 * @var array
		 */
		protected $columns = [ 'name' ];


		/**
		 * Role.
		 *
		 * @since 1.0.0
		 * @var string
		 */
		protected $role = 'staff';


		/**
		 * Get Id.
		 *
		 * @since 1.0.0
		 * @return int
		 */
		public function get_id() {
			return $this->_data['ID'] ?? $this->_data['staff_id'] ?? 0;
		}


		/**
		 * Integrated Meta keys
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function meta_keys() {
			$system_timezone = get_option( 'timezone_string' ) ? get_option( 'timezone_string' ) : 'UTC';

			return apply_filters( 'prebook_staff_meta', [
				'first_name' => '',
				'last_name' => '',
				'phone' => '',
				'email' => '',
				'color' => '#333333',

				'profile_image_url' => get_avatar_url( $this->get_id() ),
				// 'thumbnail_image_url' => '',
                'cover_images' => [],

				'status' => 'publish',
				'is_private' => false,
				'title' => '',
				'highlights' => '',
				'bio' => '',
				'zoom_user_id' => '',
				'timezone' => $system_timezone,

				'custom_working_hours' => false,
				'custom_days_off' => false,
			] );
		}


        /**
		 * Get all.
		 */
		public function get() {
			global $wpdb;

			$results = $wpdb->get_results( $wpdb->prepare(
				"SELECT ID as staff_id, user_email as email, display_name FROM {$wpdb->prefix}users u
				INNER JOIN {$wpdb->prefix}usermeta um ON u.ID = um.user_id
				WHERE um.meta_key = '{$wpdb->prefix}capabilities' AND um.meta_value LIKE %s", '%staff%'
			) );

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

			$results = $wpdb->get_results( $wpdb->prepare(
				"SELECT ID as staff_id, user_email as email, display_name FROM {$wpdb->prefix}users u WHERE u.ID = %d AND u.ID IN (SELECT user_id FROM {$wpdb->prefix}usermeta WHERE meta_key = '{$wpdb->prefix}capabilities' AND meta_value LIKE %s)", $id, '%staff%'
			) );

			if ( empty( $results ) ) {
				return false;
			}

            return $this->wrap_data( $results[0] );
		}

		/**
		 * Count
		 *
		 * @since 1.0.0
		 * @return int
		 */
		public function count() {
			global $wpdb;
			return $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}users u
				INNER JOIN {$wpdb->prefix}usermeta um ON u.ID = um.user_id
				WHERE um.meta_key = '{$wpdb->prefix}capabilities' AND um.meta_value LIKE '%staff%'" );
		}

		/**
		 * Get Services.
		 *
		 * @since 1.0.0
		 * @param bool $return_id_only return id only.
		 * @return mixed
		 */
		public function get_service_ids() {
			global $wpdb;

			$services = $wpdb->get_results( $wpdb->prepare(
				"SELECT s.service_id FROM {$wpdb->prefix}prebook_services s
				INNER JOIN {$wpdb->prefix}prebook_service_staffs ss ON s.service_id = ss.service_id
				WHERE ss.staff_id = %d", $this->get_id()
			) );

			if ( empty( $services ) ) {
				return [];
			}

			return array_map( function ( $service ) {
				return (int) $service->service_id;
			}, $services );
		}


		/**
		 * Get appointments.
		 *
		 * @since 1.0.0
		 * @return mixed
		 */
		public function get_appointments() {
			global $wpdb;
			$appointments = $wpdb->get_results( $wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}prebook_appointments a
				WHERE a.staff_id = %d", $this->get_id()
			) );

			if ( ! $appointments ) {
				return [];
			}

			return array_map( function ( $appointment ) {
				return [
					'booking_date' => $appointment->booking_date,
					'booking_time_start' => $appointment->booking_time_start,
					'booking_time_end' => $appointment->booking_time_end,
				];
			}, $appointments );
		}

		 /**
		  * Get working hours.
		  *
		  * @since 1.0.0
		  * @return array
		  */
		public function working_hours() {
			return \Prebook\Settings::get_instance()->get_section( 'business_hours' );
			// return $this->get_meta( 'working_hours', $business_hours);
		}

		/**
		 * Get days off.
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function days_off() {
			return \Prebook\Settings::get_instance()->get_section( 'days_off_holidays' );
			// return $this->get_meta( 'days_off', $business_hours);
		}


		/**
		 * Services.
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function services() {
			return $this->get_service_ids();
		}

		/**
		 * Add service.
		 *
		 * @since 1.0.0
		 * @param int $service_id service id.
		 * @return bool
		 */
		public function add_service( $service_id ) {
			$current_services = $this->get_service_ids();

            if ( in_array( $service_id, $current_services ) ) {
                return false;
            }

            ServiceStaffs::get_instance()->insert([
                'staff_id' => $this->get_id(),
                'service_id' => $service_id,
            ]);

            return true;
		}

		/**
		 * Delete service.
		 *
		 * @since 1.0.0
		 * @param int $service_id service id.
		 * @return bool
		 */
		public function delete_service( $service_id ) {
			$current_services = $this->get_service_ids();

			if ( ! in_array( $service_id, $current_services ) ) {
				return false;
			}

			ServiceStaffs::get_instance()->delete([
				'staff_id' => $this->get_id(),
				'service_id' => $service_id,
			]);

			return true;
		}

		/**
		 * Set services.
		 *
		 * @since 1.0.0
		 * @param array||int $service_id service ids.
		 * @return bool
		 */
		public function set_services( $service_id ) {
			$current_services = $this->get_service_ids();

			if ( ! is_array( $service_id ) ) {
				$service_id = [ $service_id ];
			}

			$service_id = array_unique( $service_id );

			$services_to_add = array_diff( $service_id, $current_services );
			$services_to_delete = array_diff( $current_services, $service_id );

			foreach ( $services_to_add as $service_id ) {
				$this->add_service( $service_id );
			}

			foreach ( $services_to_delete as $service_id ) {
				$this->delete_service( $service_id );
			}

			return true;
		}
	}
}
