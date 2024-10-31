<?php
/**
 * Prebook Customer Resource.
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

if ( ! class_exists( __NAMESPACE__ . 'Customer' ) ) {

	/**
	 * Prebook Customer Resource.
	 *
	 * @package Prebook
	 * @since 1.0.0
	 */
	class Customer extends \Prebook\REST\Resource {

		/**
		 * Route.
		 *
		 * @since 1.0.0
		 * @var string
		 * @access protected
		 */
		protected $route = 'customer';

		/**
		 * Custom selection.
		 *
		 * @var string
		 * @access protected
		 */
		protected $select = 'ID as customer_id, user_email as email, user_registered as created_at';

		/**
		 * Model
		 */
		protected $model = \Prebook\Model\Customer::class;

		/**
		 * Single columns.
		 *
		 * @var array
		 */
		protected $row_columns = [ 'meta', 'ucid' ];

		/**
		 * Single columns.
		 *
		 * @var array
		 */
		protected $columns = [ 'meta', 'appointments', 'services', 'notes', 'ucid' ];
	}

	// Init.
	Customer::init();
}
