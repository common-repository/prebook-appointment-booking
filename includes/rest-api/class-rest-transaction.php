<?php
/**
 * Prebook Transaction Resource.
 *
 * @package Prebook
 * @since 1.0.0
 */

// Prebook namespace.
namespace Prebook\REST;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! class_exists( __NAMESPACE__ . 'Transaction' ) ) {

	/**
	 * Prebook Transaction Resource.
	 *
	 * @package Prebook
	 * @since 1.0.0
	 */
	class Transaction extends \Prebook\REST\Resource {

		/**
		 * Route.
		 *
		 * @since 1.0.0
		 * @var string
		 * @access protected
		 */
		protected $route = 'transaction';

		/**
		 * Model
		 */
		protected $model = \Prebook\Model\Transaction::class;

		/**
		 * Single columns.
		 *
		 * @var array
		 */
		protected $row_columns = [ 'meta' ];

		/**
		 * Single columns.
		 *
		 * @var array
		 */
		protected $columns = [ 'meta' ];
	}

	// Init.
	Transaction::init();
}
