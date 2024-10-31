<?php
/**
 * Prebook Transaction model.
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

if ( ! class_exists( __NAMESPACE__ . '\Transaction' ) ) {

	/**
	 * Prebook Transaction model.
	 *
	 * @package Prebook
	 * @since 1.0.0
	 */
	class Transaction extends \Prebook\Base\Model {

		// Meta trait.
		use \Prebook\Helper\Meta;

		/**
		 * The name of the table.
		 *
		 * @since 1.0.0
		 * @var string
		 */
		protected $table_name = 'transactions';

		/**
		 * Primary key of the table.
		 *
		 * @since 1.0.0
		 * @var string
		 */
		protected $primary_key = 'transaction_id';


        /**
         * Fillable columns.
         *
         * @since 1.0.0
         * @var array
         */
        protected $fillable = [
            'appointment_id',
            'customer_id',
            'payment_method',
            'payment_status',
            'payment_amount',
            'currency',
        ];


		/**
		 * Object type.
		 *
		 * @since 1.0.0
		 * @var string
		 */
		protected $object_type = 'transaction';

			/**
		 * Integrated Meta keys
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function meta_keys() {
			return apply_filters( 'prebook_transaction_meta', [
				'ok' => 'ok',
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
		 * Get all.
		 */
		public function get() {
			global $wpdb;

			$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}prebook_transactions" );

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
			$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}prebook_transactions t WHERE t.transaction_id = %d", $id ));

			if ( empty( $results ) ) {
				return false;
			}

            return $this->wrap_data( $results[0] );
		}
	}
}
