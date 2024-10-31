<?php
/**
 * Prebook Appointment Resource.
 *
 * @package Prebook
 * @since 1.0.0
 */

// Prebook namespace.
namespace Prebook\REST;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! class_exists( __NAMESPACE__ . 'Appointment' ) ) {

	/**
	 * Prebook Appointment Resource.
	 *
	 * @package Prebook
	 * @since 1.0.0
	 */
	class Appointment extends \Prebook\REST\Resource {

		/**
		 * Route.
		 *
		 * @since 1.0.0
		 * @var string
		 * @access protected
		 */
		protected $route = 'appointment';

		/**
		 * Model
		 */
		protected $model = \Prebook\Model\Appointment::class;

		/**
		 * Single columns.
		 *
		 * @var array
		 */
		protected $row_columns = [ 'meta', 'is_paid' ];

		/**
		 * Single columns.
		 *
		 * @var array
		 */
		protected $columns = [ 'meta', 'transactions', 'is_paid' ];
	}

	// Init.
	Appointment::init();
}
