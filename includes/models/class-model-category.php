<?php
/**
 * Prebook Category model.
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

if ( ! class_exists( __NAMESPACE__ . '\Category' ) ) {

	/**
	 * Prebook Category model.
	 *
	 * @package Prebook
	 * @since 1.0.0
	 */
	class Category extends \Prebook\Base\Model {

		// Meta trait.
		use \Prebook\Helper\Meta;

		/**
		 * The name of the table.
		 *
		 * @since 1.0.0
		 * @var string
		 */
		protected $table_name = 'categories';

		/**
		 * Primary key of the table.
		 *
		 * @since 1.0.0
		 * @var string
		 */
		protected $primary_key = 'category_id';


		/**
		 * Object type.
		 *
		 * @since 1.0.0
		 * @var string
		 */
		protected $object_type = 'category';

		/**
		 * Integrated Meta keys
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function meta_keys() {
			return apply_filters( 'prebook_category_meta', [
				'color' => '#333333',
				'is_private' => false,
				'priority' => 0,
			] );
		}

		/**
		 * Getter for service_count
		 *
		 * @since 1.0.0
		 * @return mixed
		 */
		public function meta() {
			return $this->get_meta( $this->meta_keys(), new \stdClass());
		}

		/**
		 * Get Services
		 *
		 * @since 1.0.0
		 * @return mixed
		 */
		public function get_services() {
			global $wpdb;
			$services = $wpdb->get_results( $wpdb->prepare( "SELECT service_id FROM {$wpdb->prefix}prebook_services WHERE category_id = %d", $this->get_id() ) );

			if ( empty( $services ) ) {
				return false;
			}

			return array_column( $services, 'service_id' );
		}

		/**
		 * Get all appointments.
		 */
		public function get() {
			global $wpdb;

			$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}prebook_categories" );

			if ( empty( $results ) ) {
				return false;
			}

			return array_map( [ $this, 'wrap_data' ], $results );
		}

		/**
		 * Get published.
		 *
		 * @since 1.0.0
		 * @return mixed
		 */
		public function get_published() {
			global $wpdb;

			// Order by prebook_meta priority and exclude private categories, when the meta is_private is not true.

			$results = $wpdb->get_results( "SELECT t.name, t.category_id FROM {$wpdb->prefix}prebook_categories t" );

			if ( empty( $results ) ) {
				return false;
			}

			return array_map( [ $this, 'wrap_data' ], $results );
		}

		/**
		 * Get appointment by id.
		 */
		public function find( $id ) {
			global $wpdb;
			$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}prebook_categories t WHERE t.category_id = %d", $id ));

			if ( empty( $results ) ) {
				return false;
			}

            return $this->wrap_data( $results[0] );
		}
	}
}
