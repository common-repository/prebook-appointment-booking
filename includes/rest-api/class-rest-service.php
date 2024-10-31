<?php
/**
 * Prebook Service Resource.
 *
 * @package Prebook
 * @since 1.0.0
 */

// Prebook namespace.
namespace Prebook\REST;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! class_exists( __NAMESPACE__ . 'Service' ) ) {

	/**
	 * Prebook Service Resource.
	 *
	 * @package Prebook
	 * @since 1.0.0
	 */
	class Service extends \Prebook\REST\Resource {

		/**
		 * Route.
		 *
		 * @since 1.0.0
		 * @var string
		 * @access protected
		 */
		protected $route = 'service';

		/**
		 * Model
		 */
		protected $model = \Prebook\Model\Service::class;

		/**
		 * Single columns.
		 *
		 * @var array
		 */
		protected $row_columns = [ 'meta', 'prices', 'staffs' ];

		/**
		 * Single columns.
		 *
		 * @var array
		 */
		protected $columns = [ 'meta', 'staffs', 'get_service_hours', 'prices' ];
	}

	// Init.
	Service::init();
}
