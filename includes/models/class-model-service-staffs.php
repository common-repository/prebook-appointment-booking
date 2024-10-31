<?php
/**
 * Prebook service staffs model.
 *
 * @package Prebook
 * @since 1.0.0
 */

// Prebook namespace.
namespace Prebook\Model;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );


if ( ! class_exists( __NAMESPACE__ . '\ServiceStaffs' ) ) {

	/**
	 * Prebook service model.
	 *
	 * @package Prebook
	 * @since 1.0.0
	 */
	class ServiceStaffs extends \Prebook\Base\Model {

		/**
		 * The name of the table.
		 *
		 * @since 1.0.0
		 * @var string
		 */
		protected $table_name = 'service_staffs';

		/**
		 * Primary key of the table.
		 *
		 * @since 1.0.0
		 * @var string
		 */
		protected $primary_key = 'id';

		 /**
		  * Fillable columns.
		  *
		  * @since 1.0.0
		  * @var array
		  */
		protected $fillable = [
			'service_id',
			'staff_id',
			'updated_at',
		];
		/**
		 * Category getter.
		 *
		 * @since 1.0.0
		 * @return mixed
		 */
		public function category() {
			return $this->has_one( \Prebook\Model\Category::class, 'category_id', 'category_id' );
		}

		/**
		 * Get Staffs.
		 *
		 * @since 1.0.0
		 * @return mixed
		 */
		public function get_staffs() {
			return $this->has_many( Staff::class, 'service_id', 'service_id' );
		}
	}
}
