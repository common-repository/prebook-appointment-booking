<?php
/**
 * Prebook Category Resource.
 *
 * @package Prebook
 * @since 1.0.0
 */

// Prebook namespace.
namespace Prebook\REST;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! class_exists( __NAMESPACE__ . 'Category' ) ) {

	/**
	 * Prebook Category Resource.
	 *
	 * @package Prebook
	 * @since 1.0.0
	 */
	class Category extends \Prebook\REST\Resource {

		/**
		 * Route.
		 *
		 * @since 1.0.0
		 * @var string
		 * @access protected
		 */
		protected $route = 'category';

		/**
		 * Model
		 */
		protected $model = \Prebook\Model\Category::class;

		/**
		 * Row Columns.
		 *
		 * @var array
		 */
		protected $row_columns = [ 'meta' ];

		/**
		 * Row Columns.
		 *
		 * @var array
		 */
		protected $columns = [ 'meta' ];
	}

	// Init.
	Category::init();
}
