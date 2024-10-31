<?php

/**
 * Prebook customer model.
 *
 * @package Prebook
 * @since 1.0.0
 */

// Prebook namespace.
namespace Prebook\Model;

// Exit if accessed directly.
defined('ABSPATH') || exit(1);


// User trait.
require_once __DIR__ . '/class-model-user.php';

if ( ! class_exists(__NAMESPACE__ . '\Customer') ) {

	/**
	 * Prebook Customer model.
	 *
	 * @package Prebook
	 * @since 1.0.0
	 */
	class Customer extends \Prebook\Model\User {


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
		protected $role = 'customer';

		/**
		 * Get Id.
		 *
		 * @since 1.0.0
		 * @return int
		 */
		public function get_id() {
			return $this->_data['ID'] ?? $this->_data['customer_id'] ?? 0;
		}

		/*
			Get UCID
		*/
		public function get_ucid() {
			$format = apply_filters('prebook_ucid_format', '#%d');
			return wp_sprintf($format, $this->get_meta('ucid', 0));
		}

		/**
		 * UCID.
		 */
		public function ucid() {
			return $this->get_ucid();
		}

		/**
		 * Get appointments.
		 *
		 * @since 1.0.0
		 * @return mixed
		 */
		public function get_appointments() {
			$appointments = $this->has_many(Appointment::class, 'customer_id');

			if ( ! $appointments ) {
				return [];
			}

			return array_map(function ( $appointment ) {
				return [
					'booking_date' => $appointment->booking_date,
					'booking_time_start' => $appointment->booking_time_start,
					'booking_time_end' => $appointment->booking_time_end,
				];
			}, $appointments);
		}

		/**
		 * Integrated Meta keys
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function meta_keys() {
			return apply_filters('prebook_customer_meta', [
				'first_name' => '',
				'last_name' => '',
				'phone' => '',
				'email' => '',
				'color' => '#333333',
				'profile_image_url' => get_avatar_url($this->get_id()),
				'status' => 'publish',
				'address' => '',
				'priority' => false,
			]);
		}



		/**
		 * Get all.
		 */
		public function get() {
			global $wpdb;

			$results = $wpdb->get_results("SELECT ID as customer_id, user_email as email, display_name FROM {$wpdb->prefix}users u WHERE u.ID IN (SELECT user_id FROM {$wpdb->prefix}usermeta WHERE meta_key = '{$wpdb->prefix}capabilities' AND meta_value LIKE '%customer%')");

			if ( empty($results) ) {
				return false;
			}

			return array_map([ $this, 'wrap_data' ], $results);
		}

		/**
		 * Get by id.
		 */
		public function find( $id ) {
			global $wpdb;
			// Users with role customer.
			$results = $wpdb->get_results($wpdb->prepare(
				"SELECT ID as customer_id, user_email as email, display_name FROM {$wpdb->prefix}users u WHERE u.ID = %d AND u.ID IN (SELECT user_id FROM {$wpdb->prefix}usermeta WHERE meta_key = '{$wpdb->prefix}capabilities' AND meta_value LIKE %s)",
				$id,
				'%customer%'
			));

			if ( empty($results) ) {
				return false;
			}

			return $this->wrap_data($results[0]);
		}

		/**
		 * Appointments.
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function appointments() {
			// $appointments = $this->has_many( Appointment::class, 'customer_id', 'ID', true);
			global $wpdb;
			$appointments = $wpdb->get_results($wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}prebook_appointments WHERE customer_id = %d",
				$this->get_id()
			));

			if ( ! $appointments ) {
				return [];
			}

			return $appointments;
		}

		/**
		 * Services.
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function services() {
			// $appointments = $this->has_many( Appointment::class, 'customer_id', 'ID', false );
			global $wpdb;
			$appointments = $wpdb->get_results($wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}prebook_appointments WHERE customer_id = %d",
				$this->get_id()
			));

			if ( ! $appointments ) {
				return [];
			}

			$services = [];

			foreach ( $appointments as $appointment ) {
				$services[] = intval($appointment->service_id);
			}

			return array_unique($services);
		}

		/**
		 * Notes.
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function notes() {
			return $this->get_meta('notes', []);
		}

		/**
		 * Generate UCID.
		 *
		 * @since 1.0.0
		 * @return int
		 */
		public function generate_ucid() {
			// Bail if UCID already exists.
			if ( $this->get_meta('ucid') ) {
				return;
			}

			// Last Unique Customer ID from database user_meta.
			global $wpdb;

			// Get the max UCID with the table locked.
			$max_ucid = $wpdb->get_var( "SELECT MAX(CAST(meta_value AS UNSIGNED)) FROM {$wpdb->prefix}usermeta WHERE meta_key = 'ucid' FOR UPDATE" );

			// If no UCID found, start from 1.
			if ( ! $max_ucid ) {
				$max_ucid = 0;
			}

			// Increment by 1.
			$max_ucid++;

			// Update UCID.
			update_user_meta($this->get_id(), 'ucid', $max_ucid);
		}
	}
}
