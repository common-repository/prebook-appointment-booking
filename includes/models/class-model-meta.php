<?php
/**
 * Prebook meta model.
 *
 * @package Prebook
 * @since 1.0.0
 */

// Prebook namespace.
namespace Prebook\Model;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );


if ( ! class_exists( __NAMESPACE__ . '\Meta' ) ) {

	/**
	 * Prebook service model.
	 *
	 * @package Prebook
	 * @since 1.0.0
	 */
	class Meta extends \Prebook\Base\Model {

		/**
		 * The name of the table.
		 *
		 * @since 1.0.0
		 * @var string
		 */
		protected $table_name = 'object_meta';

		/**
		 * Primary key of the table.
		 *
		 * @since 1.0.0
		 * @var string
		 */
		protected $primary_key = 'meta_id';

		/**
		 * Set meta object, object_id is required.
		 *
		 * @param int    $object_id object id.
		 * @param string $object_type object name.
		 * @since 1.0.0
		 */
		public function set_object( $object_id, $object_type = 'service' ) {
			$this->object_id = $object_id;
			$this->object_type = $object_type;

			// Set where clause.
			$this->where([ 'object_id' => $this->object_id ]);
			$this->where([ 'object_type' => $this->object_type ]);

			return $this;
		}
	}
}
