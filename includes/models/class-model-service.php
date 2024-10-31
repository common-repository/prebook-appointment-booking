<?php
/**
 * Prebook Service model.
 *
 * @package Prebook
 * @since 1.0.0
 */

// Prebook namespace.
namespace Prebook\Model;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

// Category model.
require_once PREBOOK_INCLUDES . 'models/class-model-category.php';
// ServiceStaff model.
require_once PREBOOK_INCLUDES . 'models/class-model-service-staffs.php';
// Appointment model.
require_once PREBOOK_INCLUDES . 'models/class-model-appointment.php';

// Meta trait.
require_once PREBOOK_INCLUDES . 'helper/trait-meta.php';

if ( ! class_exists( __NAMESPACE__ . '\Service' ) ) {

    /**
     * Prebook service model.
     *
     * @package Prebook
     * @since 1.0.0
     */
    class Service extends \Prebook\Base\Model {

        // Meta trait.
        use \Prebook\Helper\Meta;

        /**
         * The name of the table.
         *
         * @since 1.0.0
         * @var string
         */
        protected $table_name = 'services';

        /**
         * Primary key of the table.
         *
         * @since 1.0.0
         * @var string
         */
        protected $primary_key = 'service_id';

        /**
         * Object type.
         *
         * @since 1.0.0
         * @var string
         */
        protected $object_type = 'service';

        /**
         * Fillable columns.
         *
         * @since 1.0.0
         * @var array
         */
        protected $fillable = [
            'name',
            'short_description',
            'status',
            'category_id',
            'updated_at',
        ];

        /**
		 * Integrated Meta keys
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function meta_keys() {
			return apply_filters( 'prebook_service_meta', [
				'color' => '#333333',
                'is_private' => 0,
                'thumbnail_image_url' => '',
                'gallery_images' => [],

                'enable_random_staff' => 0,

                'has_buffer_time' => 0,
                'pre_buffer' => 0,
                'pre_buffer_unit' => 'minutes',
                'post_buffer' => 0,
                'post_buffer_unit' => 'minutes',
                'required_deposit' => 0,
                'deposit_amount' => 0,
                'deposit_type' => 'fixed',
                'order' => 0,
                'price' => 0,
                'duration' => 0,
                'duration_unit' => 'minute',
                'has_booking_type' => 0,
                'booking_type' => [],
                'price_type' => 'fixed',
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
         * Prices.
         *
         * @since 1.0.0
         * @return mixed
         */
        public function prices() {
            return $this->get_meta( 'prices', [] );
        }

        /**
         * Category getter.
         *
         * @since 1.0.0
         * @return mixed
         */
        public function get_category() {
            return $this->has_one( Category::class );
        }

        /**
		 * Get all.
		 */
		public function get() {
			global $wpdb;

			$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}prebook_services" );

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
			$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}prebook_services t WHERE t.service_id = %d LIMIT 1", $id ));

			if ( empty( $results ) ) {
				return false;
			}

            return $this->wrap_data( $results[0] );
		}

        /**
         * Count
         *
         * @since 1.0
         * @return int
         */
        public function count() {
            global $wpdb;
			return $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}prebook_services t" );
        }

        /**
         * Get published.
         *
         * @since 1.0.0
         * @return mixed
         */
        public function get_published() {
            global $wpdb;

            // Multiple meta keys.
            $results = $wpdb->get_results( $wpdb->prepare("
            SELECT t.name, t.category_id, t.service_id
            FROM {$wpdb->prefix}prebook_services t  WHERE t.status = %s ORDER BY t.service_id ASC", 'publish'));

            if ( empty( $results ) ) {
                return [];
            }

            return array_map( [ $this, 'wrap_data' ], $results );
        }
        /**
         * Get Staffs ID.
         *
         * @since 1.0.0
         * @return mixed
         */
        public function get_staffs_id() {
            global $wpdb;
            $results = $wpdb->get_results( $wpdb->prepare( "SELECT staff_id FROM {$wpdb->prefix}prebook_service_staffs WHERE service_id = %d", $this->get_id() ) );

            if ( empty( $results ) ) {
                return [];
            }

            return array_map( function ( $result ) {
                return (int) $result->staff_id;
            }, $results );
        }

        /**
         * Staffs.
         *
         * @since 1.0.0
         * @return mixed
         */
        public function staffs() {
            return $this->get_staffs_id();
        }


		/**
         * Has Staff.
         *
         * @since 1.0.0
         * @param int $staff_id staff id.
         * @return bool
         */
        public function has_staff( $staff_id ) {
            $current_staffs = $this->get_staffs_id();

            if ( in_array( $staff_id, $current_staffs ) ) {
                return true;
            }

            return false;
        }

         /**
         * Get Staffs.
         *
         * @since 1.0.065
         * @return mixed
         */
        public function get_staffs() {
            global $wpdb;
            $results = $wpdb->get_results( $wpdb->prepare( "SELECT staff_id FROM {$wpdb->prefix}prebook_service_staffs WHERE service_id = %d", $this->get_id() ) );

            if ( empty( $results ) ) {
                return [];
            }

            return array_map( function ( $result ) {
                return Staff::get_instance()->find( $result->staff_id );
            }, $results );
        }

        /**
         * Add Staff.
         *
         * @since 1.0.0
         * @param int $staff_id staff id.
         * @return bool
         */
        public function add_staff( $staff_id ) {
            $current_staffs = $this->get_staffs_id();

            if ( in_array( $staff_id, $current_staffs ) ) {
                return false;
            }

            ServiceStaffs::get_instance()->insert([
                'service_id' => $this->get_id(),
                'staff_id' => $staff_id,
            ]);

            return true;
        }

        /**
         * Delete Staff.
         *
         * @since 1.0.0
         * @param int $staff_id staff id.
         * @return bool
         */
        public function delete_staff( $staff_id ) {
            $current_staffs = $this->get_staffs_id();

            if ( ! in_array( $staff_id, $current_staffs ) ) {
                return false;
            }

            ServiceStaffs::get_instance()->delete([
                'service_id' => $this->get_id(),
                'staff_id' => $staff_id,
            ]);

            return true;
        }

        /**
         * Set Staffs.
         *
         * @since 1.0.0
         * @param array||int $staff_id staff ids.
         * @return bool
         */
        public function set_staffs( $staff_id ) {

            $final_staffs = is_array( $staff_id ) ? $staff_id : [ $staff_id ];
            $current_staffs = $this->get_staffs_id();

            // Delete all staffs if not in the final list, and add new staffs if not in the current list.
            if ( $current_staffs && ! empty( $current_staffs ) ) {
                foreach ( $current_staffs as $id ) {

                    if ( ! in_array( $id, $final_staffs ) || ! $final_staffs ) {
                        $this->delete_staff( $id );
                    }
                }
            }

            if ( empty( $final_staffs ) ) {
                return true;
            }

            foreach ( $final_staffs as $id ) {
                if ( ! in_array( $id, $current_staffs ) ) {
                    $this->add_staff( $id );
                }
            }

            return true;
        }


        /**
         * Appointments.
         *
         * @since 1.0.0
         * @return mixed
         */
        public function get_appointments() {
            global $wpdb;
            $results = $wpdb->get_results( $wpdb->prepare( "SELECT appointment_id FROM {$wpdb->prefix}prebook_appointments WHERE service_id = %d", $this->get_id() ) );

            if ( empty( $results ) ) {
                return false;
            }

            return array_column( $results, 'appointment_id' );
        }

        /**
         * Get service hours.
         *
         * @since 1.0.0
         * @return array
         */
        public function get_service_hours() {
            $business_hours = \Prebook\Settings::get_instance()->get_section( 'business_hours' );
            return $this->get_meta( 'service_hours', $business_hours);
        }

        /**
         * Get duration.
         *
         * @since 1.0.0
         * @return int
         */
        public function get_duration() {
            $duration = $this->get_meta( 'duration', 0 );

            $duration_unit = $this->get_meta( 'duration_unit', 'minute' );

            if ( 'hour' === $duration_unit ) {
                $duration = $duration * 60;
            }

            return $duration;
        }

        /**
         * Has buffer time.
         *
         * @since 1.0.0
         * @return bool
         */
        public function has_buffer_time() {
            return wp_validate_boolean( $this->get_meta( 'has_buffer_time', false ) );
        }

        /**
         * Get pre buffer.
         *
         * @since 1.0.0
         * @return int
         */
        public function get_pre_buffer() {

            // If buffer time is not enabled, return 0.
            if ( ! $this->has_buffer_time() ) {
                return 0;
            }

            $pre_buffer = $this->get_meta( 'pre_buffer', 0 );

            $pre_buffer_unit = $this->get_meta( 'pre_buffer_unit', 'minutes' );

            if ( 'hours' === $pre_buffer_unit ) {
                $pre_buffer = $pre_buffer * 60;
            }

            return $pre_buffer;
        }

        /**
         * Get post buffer.
         *
         * @since 1.0.0
         * @return int
         */
        public function get_post_buffer() {

            // If buffer time is not enabled, return 0.
            if ( ! $this->has_buffer_time() ) {
                return 0;
            }

            $post_buffer = $this->get_meta( 'post_buffer', 0 );

            $post_buffer_unit = $this->get_meta( 'post_buffer_unit', 'minutes' );

            if ( 'hours' === $post_buffer_unit ) {
                $post_buffer = $post_buffer * 60;
            }

            return $post_buffer;
        }
    }
}
