<?php
/**
 * Prebook Slot class.
 *
 * @package Prebook
 * @since 1.0.0
 */

// Prebook namespace.
namespace Prebook;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! class_exists( __NAMESPACE__ . '\Slot' ) ) {
	/**
	 * Prebook Slot class.
	 *
	 * @package Prebook
	 * @since 1.0.0
	 */
	class Slot extends \Prebook\Base {
		/**
		 * Working days.
		 *
		 * @since 1.0.0
		 * @var array
		 */
		protected $working_hours = [];

		/**
		 * Days off.
		 *
		 * @since 1.0.0
		 * @var array
		 */
		protected $days_off = [];

		/**
		 * Pre buffer.
		 *
		 * @since 1.0.0
		 * @var int
		 */
		protected $pre_buffer = 0;

		/**
		 * Post buffer.
		 *
		 * @since 1.0.0
		 * @var int
		 */
		protected $post_buffer = 0;

		/**
		 * Duration.
		 *
		 * @since 1.0.0
		 * @var int
		 */

		protected $duration = 20;

		/**
		 * Business hours.
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function get_business_hours() {
			return prebook_get_option( 'business_hours', [] );
		}

		/**
		 * Business days off.
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function get_business_days_off() {

			$addons_holidays_days_off = prebook_get_option( 'addons_holidays_days_off', true );

			if ( ! $addons_holidays_days_off ) {
				return [];
			}

			return prebook_get_option( 'days_off_holidays', [] );
		}

		/**
		 * Constructor.
		 *
		 * @param mixed $service Service.
		 * @param mixed $staff Staff.
		 * @throws \Exception If service or staff not found.
		 * @since 1.0.0
		 */
		public function __construct( $service = null, $staff = null ) {

			if ( $service ) {
				// Service.
				if ( is_int( $service ) ) {
					$service = \Prebook\Model\Service::get_instance()->find( $service );

					// Throw error if service not found.
					if ( ! $service ) {
						throw new \Exception( esc_html__( 'Service not found.', 'prebook' ) );
					}
				}

				// Add working days.
				$this->working_hours = $service->get_service_hours();

				$this->duration = $service->get_duration();

				$this->pre_buffer = $service->get_pre_buffer();

				$this->post_buffer = $service->get_post_buffer();

				// Staff is optional.
				if ( $staff ) {
					if ( is_int( $staff ) ) {
						$staff = \Prebook\Model\Staff::get_instance()->find( $staff );

						// Throw error if staff not found.
						if ( ! $staff ) {
							throw new \Exception( esc_html__( 'Staff not found.', 'prebook' ) );
						}
					}

					// Add days off from staff.
					$this->days_off = $staff->days_off();

					// Intersect working days.
					$this->intersect_working_hours( $staff->working_hours() );
				}
			}
		}


		/**
		 * Intersect working hours.
		 *
		 * @param array $hours Days.
		 * @since 1.0.0
		 * @return object
		 */
		public function intersect_working_hours( $hours ) {
			// Intersect between current value and new value, keep only the common keys.
			$this->working_hours = array_intersect_key( $this->working_hours, $hours );
			return $this;
		}

		/**
		 * Get working hours.
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function get_working_hours() {
			return $this->working_hours;
		}

		/**
		 * Get working days.
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function get_working_days() {
			$days = array_filter( $this->get_working_hours(), function ( $day ) {
				return isset( $day['enabled'] ) ? wp_validate_boolean( $day['enabled'] ) : false;
			} );

			return array_keys( $days );
		}

		/**
		 * Get days off.
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function get_holidays() {
			return $this->days_off ? $this->days_off : $this->get_business_days_off();
		}

		/**
		 * Is day off.
		 *
		 * @param string $date Date.
		 * @since 1.0.0
		 * @return bool
		 */
		public function is_day_off( $date = 'today' ) {
			// When holidays are disabled, return false.
			$addons_holidays_days_off = prebook_get_option( 'addons_holidays_days_off', true );

			if ( ! $addons_holidays_days_off ) {
				return false;
			}

			$holidays = $this->get_holidays();

			// Bail, if array is empty.
			if ( empty( $holidays ) ) {
				return false;
			}

			foreach ( $holidays as $holiday ) {

				// If holiday array has two values, it's a range.
				if ( count( $holiday ) > 1 ) {
					$start = strtotime( $holiday[0]['date'] );
					$end = strtotime( $holiday[1]['date'] );
					$date = strtotime( $date );

					if ( $date >= $start && $date <= $end ) {
						return true;
					}
				} else {
					if ( $date === $holiday[0]['date'] ) {
						return true;
					}
				}
			}

			return in_array( $date, $this->get_holidays() );
		}

		/**
		 * Is active on.
		 *
		 * @param string $date Date.
		 * @since 1.0.0
		 * @return bool
		 */
		public function is_active_on( $date = 'today' ) {
			$day = gmdate( 'l', strtotime( $date ) );
			return in_array( $day, $this->get_working_days() );
		}


		/**
		 * Get active dates.
		 *
		 * @param int $max_days Max days.
		 * @since 1.0.0
		 * @return array
		 */
		public function get_active_dates( $max_days = 30 ) {
			$dates = [];
			$i = 0;
			while ( $i < $max_days ) {
				$date = gmdate( 'Y-m-d', strtotime( "+{$i} days" ) );
				if ( ! $this->is_day_off( $date ) && $this->is_active_on( $date ) ) {
					$dates[] = $date;
				}
				$i++;
			}
			return $dates;
		}


		/**
		 * Get working hours.
		 *
		 * @param string $date Date.
		 * @since 1.0.0
		 * @return array
		 */
		public function get_hours( $date = 'today' ) {

			$day = gmdate( 'l', strtotime( $date ) );

			if ( ! $this->is_active_on( $date ) ) {
				return [];
			}

			return isset( $this->get_working_hours()[ $day ]['hours'] ) ? $this->get_working_hours()[ $day ]['hours'] : [];
		}

		/**
		 * Get slots.
		 *
		 * @param string $date Date.
		 * @param int    $duration Duration.
		 * @param int    $pre_buffer Pre buffer.
		 * @param int    $post_buffer Post buffer.
		 * @since 1.0.0
		 * @return array
		 */
		public function get_slots( $date = 'today', $duration = null, $pre_buffer = null, $post_buffer = null ) {

			// Bail is date is not in working days.
			if ( ! $this->is_active_on( $date ) || $this->is_day_off( $date ) ) {
				return [];
			}

			// Set default duration, pre buffer and post buffer.
			$duration = isset( $duration ) && ! empty( isset( $duration ) ) ? $duration : $this->duration;
			$pre_buffer = isset( $pre_buffer ) && ! empty( isset( $pre_buffer ) ) ? $pre_buffer : $this->pre_buffer;
			$post_buffer = isset( $post_buffer ) && ! empty( isset( $post_buffer ) ) ? $post_buffer : $this->post_buffer;

			$hours = $this->get_hours($date);

			// If no hours, default from business hours.
			if ( empty( $hours ) ) {
				$hours = isset( $this->get_business_hours()[ gmdate( 'l', strtotime( $date ) ) ]['hours'] ) ? $this->get_business_hours()[ gmdate( 'l', strtotime( $date ) ) ]['hours'] : [];
			}

			$slots = [];

			foreach ( $hours as $hour ) {
				$start_time = strtotime($hour['start']);
				$end_time = strtotime($hour['end']);

				// Calculate the duration of the slot including buffer times.
				$slot_duration = $duration + $pre_buffer + $post_buffer;

				if ( $slot_duration < 1 ) {
					continue;
				}

				// Iterate through the time range within the hour.
				for ( $current_time = $start_time; $current_time < $end_time; $current_time += $slot_duration * 60 ) {
					$slot_start = ( $current_time + $pre_buffer * 60 );
					$slot_end = $slot_start + $duration * 60;

					// Ensure that the slot end time does not exceed the maximum end time.
					if ( $slot_end <= $end_time ) {
						$slots[] = [
							'start' => gmdate('H:i', $slot_start),
							'end' => gmdate('H:i', $slot_end),
						];
					}
				}
			}

			return $slots;
		}

		/**
		 * Get slots count.
		 *
		 * @param string $date Date.
		 * @param int    $duration Duration.
		 * @param int    $pre_buffer Pre buffer.
		 * @param int    $post_buffer Post buffer.
		 * @since 1.0.0
		 * @return int
		 */
		public function get_slots_count( $date = 'today', $duration = null, $pre_buffer = null, $post_buffer = null ) {
			return count($this->get_slots($date, $duration, $pre_buffer, $post_buffer));
		}

		/**
		 * Is slot available.
		 *
		 * @param string $date Date.
		 * @param string $time_start Time start.
		 * @param string $time_end Time end.
		 * @return bool
		 */
		public function is_slot_available( $date, $time_start, $time_end ) {

			$slots = $this->get_slots( $date );
			$slots = array_filter( $slots, function ( $slot ) use ( $time_start, $time_end ) {
				return gmdate( 'H:i', strtotime( $slot['start'] ) ) === gmdate( 'H:i', strtotime( $time_start ) ) && gmdate( 'H:i', strtotime( $slot['end'] ) ) === gmdate( 'H:i', strtotime( $time_end ) );
			} );

			return count( $slots ) > 0;
		}
	}
}
