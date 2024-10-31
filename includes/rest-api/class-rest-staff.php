<?php
/**
 * Prebook Staff Resource.
 *
 * @package Prebook
 * @since 1.0.0
 */

// Prebook namespace.
namespace Prebook\REST;

// Required classes.
require_once PREBOOK_INCLUDES . 'models/class-model-user.php';

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! class_exists( __NAMESPACE__ . 'Staff' ) ) {

	/**
	 * Prebook Staff Resource.
	 *
	 * @package Prebook
	 * @since 1.0.0
	 */
	class Staff extends \Prebook\REST\Resource {

		/**
		 * Route.
		 *
		 * @since 1.0.0
		 * @var string
		 * @access protected
		 */
		protected $route = 'staff';

		/**
		 * Custom selection.
		 *
		 * @var string
		 * @access protected
		 */
		protected $select = 'ID as staff_id, user_email as email, user_registered as created_at, display_name';

		/**
		 * Model
		 */
		protected $model = \Prebook\Model\Staff::class;

		/**
		 * Single columns.
		 *
		 * @var array
		 */
		protected $row_columns = [ 'meta', 'working_hours', 'services', 'name' ];

		/**
		 * Single columns.
		 *
		 * @var array
		 */
		protected $columns = [ 'meta', 'status', 'services', 'working_hours', 'days_off', 'name' ];
	}

	// Init.
	Staff::init();
}
