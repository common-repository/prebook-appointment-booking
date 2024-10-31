<?php
/**
 * Prebook REST Resource.
 *
 * @package Prebook
 * @since 1.0.0
 */

// Prebook namespace.
namespace Prebook\REST;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! class_exists( __NAMESPACE__ . 'Resource' ) ) {

	/**
	 * Prebook REST Resource.
	 *
	 * @package Prebook
	 * @since 1.0.0
	 */
	class Resource extends \Prebook\Base\REST {
		/**
		 * Route.
		 *
		 * @since 1.0.0
		 * @var string
		 * @access protected
		 */
		protected $route;


		/**
		 * Model instance.
		 *
		 * @since 1.0.0
		 * @var object
		 */
		protected $model;

		/**
		 * Custom columns for row.
		 *
		 * @var array
		 */
		protected $columns = [];

		/**
		 * Custom columns for single item.
		 *
		 * @var array
		 */
		protected $row_columns = [];


		/**
		 * Model instance.
		 *
		 * @since 1.0.0
		 * @return object
		 */
		protected function get_model() {
			return new $this->model();
		}

		/**
		 * Custom selection.
		 *
		 * @since 1.0.0
		 * @var string
		 */
		protected $select = '*';


		/**
		 * REST API Endpoints.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function endpoints() {
			return apply_filters(
				wp_sprintf( 'prebook_%s_resource_endpoints', $this->route ), [
					[
						'route' => $this->route,
						'callback' => [ $this, 'get_items' ],
						'methods' => 'GET',
						'args' => $this->get_items_rest_args(),
					],
					[
						'route' => $this->route . '/(?P<id>[\d]+)',
						'callback' => [ $this, 'get_item' ],
						'args' => apply_filters(  wp_sprintf( 'prebook_get_%s_rest_args', $this->route ), [
							'id' => [
								'type' => 'integer',
								'description' => __( 'Unique identifier for the object.', 'prebook' ),
								'required' => true,
							],
							'meta' => [
								'type' => 'boolean|array',
								'description' => __( 'Retrieve meta fields for the object.', 'prebook' ),
								'required' => false,
							],
						] ),
						'methods' => 'GET',
					],
					[
						'route' => $this->route,
						'callback' => [ $this, 'create_item' ],
						'methods' => 'POST',
						'permission_callback' => [ $this, 'permission_callback' ],
						'args' => apply_filters( wp_sprintf( 'prebook_create_%s_rest_args', $this->route ), [] ),
					],
					[
						'route' => $this->route . '/(?P<id>[\d]+)',
						'callback' => [ $this, 'update_item' ],
						'methods' => 'PUT',
						'permission_callback' => [ $this, 'permission_callback' ],
					],
					[
						'route' => $this->route . '/(?P<id>[\d]+)',
						'callback' => [ $this, 'delete_item' ],
						'methods' => 'DELETE',
						'permission_callback' => [ $this, 'permission_callback' ],
					],

					/**
					 * Bulk Actions.
					 */
					[
						'route' => $this->route . '/bulk',
						'callback' => [ $this, 'bulk_update' ],
						'methods' => 'PUT',
						'permission_callback' => [ $this, 'permission_callback' ],
						'args' => [
							'ids' => [
								'type' => 'array',
								'description' => __( 'List of IDs to update.', 'prebook' ),
								'required' => true,
								'items' => [
									'type' => 'integer',
								],
							],
						],
					],
					[
						'route' => $this->route . '/bulk',
						'callback' => [ $this, 'bulk_delete' ],
						'methods' => 'DELETE',
						'permission_callback' => [ $this, 'permission_callback' ],
						'args' => [
							'ids' => [
								'type' => 'array',
								'description' => __( 'List of IDs to delete.', 'prebook' ),
								'required' => true,
								'items' => [
									'type' => 'integer',
								],
							],
						],
					],
				]);
		}

		/**
		 * Get REST Args
		 *
		 * @return array
		 */
		public function get_items_rest_args() {
			$args = [];

			// Page.
			$args['page'] = [
				'type'              => 'number',
				'required'          => false,
				'description' => __( 'Current page number. Default is 1.', 'prebook' ),
				'sanitize_callback' => 'intval',
			];

			// Per Page.
			$args['per_page'] = [
				'type'              => 'number',
				'required'          => false,
				'description' => __( 'Number of items to show per page. Set -1 to list all items.', 'prebook' ),
				'sanitize_callback' => 'intval',
			];

			// Order By.
			$args['order_by'] = [
				'type'              => 'string',
				'required'          => false,
				'description' => __( 'Order by field.', 'prebook' ),
				'sanitize_callback' => 'sanitize_text_field',
			];

			// Order.
			$args['order'] = [
				'type'              => 'string',
				'required'          => false,
				'description' => __( 'Order by field.', 'prebook' ),
				'sanitize_callback' => 'sanitize_text_field',
				'enum' => [ 'ASC', 'DESC' ],
				'default' => 'DESC',
			];

			// Search.
			$args['search'] = [
				'type'              => 'string',
				'required'          => false,
				'description' => __( 'Search term.', 'prebook' ),
				'sanitize_callback' => 'sanitize_text_field',
			];

			// Search In.
			$args['search_in'] = [
				'type'              => 'array',
				'required'          => false,
				'description' => __( 'Search in fields.', 'prebook' ),
				'sanitize_callback' => 'sanitize_text_field',
			];

			return apply_filters( wp_sprintf( 'prebook_get_all_%s_rest_args', $this->route ), $args );
		}

		/**
		 * Get items.
		 *
		 * @since 1.0.0
		 * @param \WP_REST_Request $request Request object.
		 * @return \WP_REST_Response
		 */
		public function get_items( $request ) {

			$model = $this->get_model();

			$page = apply_filters( wp_sprintf( 'prebook_get_%s_page', $this->route ), 1 );

			$per_page = apply_filters( wp_sprintf( 'prebook_get_%s_per_page', $this->route ), 100 );
			$order_by = apply_filters( wp_sprintf( 'prebook_get_%s_order_by', $this->route ), 'created_at' );
			$order = apply_filters( wp_sprintf( 'prebook_get_%s_order', $this->route ), 'DESC' );

			// Page.
			if ( $request->has_param( 'page' ) ) {
				$page = $request->get_param( 'page' );
			}

			// Per Page.
			if ( $request->has_param( 'per_page' ) ) {
				$per_page = $request->get_param( 'per_page' );
			}

			// Order By.
			if ( $request->has_param( 'order_by' ) ) {
				$order_by = $request->get_param( 'order_by' );
			}

			// Order.
			if ( $request->has_param( 'order' ) ) {
				$order = $request->get_param( 'order' );
			}

			// Set limit.
			if ( 0 < $per_page && $page ) {
				$model->limit ( $per_page )->offset ( $page - 1 );
			}

			// Set Order.
			$model->order_by( $order_by, $order);

			// Set Select.
			if ( $this->select ) {
				$model->select( $this->select );
			}

			// IDs.
			if ( $request->has_param( 'ids' ) ) {
				$ids = $request->get_param( 'ids' );
				$model->where_or( $model->get_primary_key(), $ids );
			}

			// Search.
			if ( $request->has_param( 'search' ) ) {
				$search = $request->get_param( 'search' );
				$search_in = apply_filters( wp_sprintf( 'prebook_get_%s_search_in', $this->route ), $request->get_param( 'search_in' ) );
				$model->search( $search, $search_in );
			}

			// Filters.
			$allowed_fields = apply_filters( wp_sprintf( 'prebook_%s_allowed_fields', $this->route ), [
				'name',
				'short_description',
				'status',
			] );

			// If allowed fields found, add it to the query.
			if ( $allowed_fields && ! empty( $allowed_fields ) ) {
				foreach ( $allowed_fields as $field ) {
					if ( $request->has_param( $field ) ) {
						$value = $request->get_param( $field );
						if ( is_array( $value ) ) {
							$model->where( $field, [ 'IN' => $value ], 'AND' );
						} else {
							$model->where( [ $field => $value ], 'AND' );
						}
					}
				}
			}

			$items = $model->get();

			// Bail, if no items found.
			if ( ! $items || empty( $items ) ) {
				return $this->success( [] );
			}

			$items = array_map( function ( $item ) {
				if ( $this->row_columns && is_array( $this->row_columns ) && ! empty( $this->row_columns ) ) {
					foreach ( $this->row_columns as $column ) {
						$item->$column = $item->$column();
					}
				}

				return $item->to_array();
			}, $items);

			return rest_ensure_response( $items );
		}

		/**
		 * Get item.
		 *
		 * @since 1.0.0
		 * @param \WP_REST_Request $request Request object.
		 * @return \WP_REST_Response
		 */
		public function get_item( $request ) {
			$id = $request->get_param( 'id' );

			// Bail, if no id found.
			if ( ! $id ) {
				return $this->error( wp_sprintf( 'Invalid %s ID.', ucfirst( $this->route ) ) );
			}

			$model = $this->get_model();

			// Set Select.
			if ( $this->select ) {
				$model->select( $this->select );
			}

			$item = $model->find( $id );

			// Bail, if no id found.
			if ( ! $item ) {
				return $this->error( wp_sprintf( '%s not found.', ucfirst( $this->route ) ) );
			}

			if ( $this->columns && is_array( $this->columns ) && ! empty( $this->columns ) ) {
				foreach ( $this->columns as $column ) {
					$item->$column = $item->$column();
				}
			}

			return $this->success( $item->to_array() );
		}

		/**
		 * Create item.
		 *
		 * @since 1.0.0
		 * @param \WP_REST_Request $request Request object.
		 * @return \WP_REST_Response
		 */
		public function create_item( $request ) {
			$model = $this->get_model();

			$body = $request->get_params();

			$allowed_fields = apply_filters( wp_sprintf( 'prebook_%s_allowed_fields', $this->route ), [
				'name',
				'short_description',
				'status',
			] );

			// Bail, if allowed fields not found.
			if ( ! $allowed_fields || empty( $allowed_fields ) ) {
				return $this->error( wp_sprintf( 'Invalid %s fields.', ucfirst( $this->route ) ) );
			}

			// Filter body.
			$body = array_intersect_key( $body, array_flip( $allowed_fields ) );

			// Bail, if body not found.
			if ( ! $body || empty( $body ) ) {
				return $this->error( wp_sprintf( 'Invalid %s fields.', ucfirst( $this->route ) ) );
			}

			// Create item.
			$item = $model->insert( $body );

			$item = $model->find( $item->get_id() );

			// Update meta.
			if ( $request->has_param( 'meta' ) && is_array( $request->get_param( 'meta' ) ) && ! empty( $request->get_param( 'meta' ) ) ) {
				foreach ( $request->get_param( 'meta' ) as $key => $value ) {
					$item && $item->update_meta( $key, $value );
				}
			}

			// Hook for created item.
			do_action( wp_sprintf( 'prebook_%s_created', $this->route ), $item, $request );

			return $this->success( $item ? $item->to_array() : [] );
		}

		/**
		 * Update item.
		 *
		 * @since 1.0.0
		 * @param \WP_REST_Request $request Request object.
		 * @return \WP_REST_Response
		 */
		public function update_item( $request ) {
			$id = $request->get_param( 'id' );

			// Bail, if no id found.
			if ( ! $id ) {
				return $this->error( wp_sprintf( 'Invalid %s ID.', ucfirst( $this->route ) ) );
			}

			$model = $this->get_model();

			$item = $model->find( $id );

			// Bail, if no id found.
			if ( ! $item ) {
				return $this->error( wp_sprintf( '%s not found.', ucfirst( $this->route ) ) );
			}

			$body = $request->get_params();

			$allowed_fields = apply_filters( wp_sprintf( 'prebook_%s_allowed_fields', $this->route ), [
				'name',
				'short_description',
				'status',
				'meta',
			] );

			// Bail, if allowed fields not found.
			if ( ! $allowed_fields || empty( $allowed_fields ) ) {
				return $this->error( wp_sprintf( 'Invalid %s fields.', ucfirst( $this->route ) ) );
			}

			// Filter body.
			$body = array_intersect_key( $body, array_flip( $allowed_fields ) );

			// Update item.
			if ( $body && ! empty( $body ) ) {
				$item->update( $body );
			}

			// Update meta.
			$old_meta = $item->meta();
			if ( $request->has_param( 'meta' ) && is_array( $request->get_param( 'meta' ) ) && ! empty( $request->get_param( 'meta' ) ) ) {
				foreach ( $request->get_param( 'meta' ) as $key => $value ) {
					$item->update_meta( $key, $value );
				}
			}

			// Hook for updated item.
			do_action( wp_sprintf( 'prebook_%s_updated', $this->route ), $item, $request, $old_meta );

			return $this->success( wp_sprintf( '%s updated successfully.', ucfirst( $this->route ) ) );
		}

		/**
		 * Delete item.
		 *
		 * @since 1.0.0
		 * @param \WP_REST_Request $request Request object.
		 * @return \WP_REST_Response
		 */
		public function delete_item( $request ) {
			$id = $request->get_param( 'id' );

			// Bail, if no id found.
			if ( ! $id ) {
				return $this->error( wp_sprintf( 'Invalid %s ID.', ucfirst( $this->route ) ) );
			}

			$model = $this->get_model();

			$item = $model->find( $id );

			// Bail, if no id found.
			if ( ! $item ) {
				return $this->error( wp_sprintf( '%s not found.', ucfirst( $this->route ) ) );
			}

			$item->delete();

			// Hook for before delete item.
			do_action( wp_sprintf( 'prebook_%s_deleted', $this->route ), $id );

			return $this->success( wp_sprintf( '%s deleted successfully.', ucfirst( $this->route ) ) );
		}

		/**
		 * Bulk Update.
		 *
		 * @since 1.0.0
		 * @param \WP_REST_Request $request Request object.
		 * @return \WP_REST_Response
		 */
		public function bulk_update( $request ) {
			$model = $this->get_model();

			$body = $request->get_params();

			$ids = $body['ids'];

			// Bail, if no ids found.
			if ( ! $ids || empty( $ids ) ) {
				return $this->error( wp_sprintf( 'Invalid %s IDs.', ucfirst( $this->route ) ) );
			}

			$allowed_fields = apply_filters( wp_sprintf( 'prebook_bulk_%s_allowed_fields', $this->route ), [
				'status',
				'meta',
			] );

			// Bail, if allowed fields not found.
			if ( ! $allowed_fields || empty( $allowed_fields ) ) {
				return $this->error( wp_sprintf( 'Invalid %s fields.', ucfirst( $this->route ) ) );
			}

			// Filter body.
			$body = array_intersect_key( $body, array_flip( $allowed_fields ) );

			foreach ( $ids as $id ) {

				$item = $model->find( $id );

				$updated = false;

				// Update items.
				if ( $body && ! empty( $body ) ) {
					$updated = $model->update( $body, [ $model->get_primary_key() => $id ] ) || $updated;
				}

				if ( $request->has_param( 'meta' ) ) {
					foreach ( $request->get_param( 'meta' ) as $key => $value ) {
						$updated = $item->update_meta( $key, $value ) || $updated;
					}
				}

				// Hook for updated item.
				if ( $updated ) {
					do_action( wp_sprintf( 'prebook_%s_updated', $this->route ), $model, $request );
				}
			}

			// Hook for bulk updated items.
			do_action( wp_sprintf( 'prebook_bulk_%s_updated', $this->route ), $ids, $request );

			return $this->success( wp_sprintf( '%s updated successfully.', ucfirst( $this->route ) ) );
		}

		/**
		 * Bulk Delete.
		 *
		 * @since 1.0.0
		 * @param \WP_REST_Request $request Request object.
		 * @return \WP_REST_Response
		 */
		public function bulk_delete( $request ) {
			$model = $this->get_model();

			$ids = $request->get_param( 'ids' );

			// Bail, if no ids found.
			if ( ! $ids || empty( $ids ) ) {
				return $this->error( wp_sprintf( 'Invalid %s IDs.', ucfirst( $this->route ) ) );
			}

			// Delete items.
			foreach ( $ids as $id ) {
				$model->delete( [ $model->get_primary_key() => $id ] );

				// Hook for before delete item.
				do_action( wp_sprintf( 'prebook_%s_deleted', $this->route ), $model, $request);
			}

			// Hook for bulk deleted items.
			do_action( wp_sprintf( 'prebook_bulk_%s_deleted', $this->route ), $ids );

			return $this->success( wp_sprintf( '%s deleted successfully.', ucfirst( $this->route ) ) );
		}


		/**
		 * Permission callback.
		 *
		 * @since 1.0.0
		 * @param \WP_REST_Request $request Request object.
		 * @return bool
		 */
		public function permission_callback() {
			// Current user role.
			$current_user_role = current( wp_get_current_user()->roles );

			// Bail, if not admin or staff.
			if ( in_array( $current_user_role, [ 'administrator', 'staff' ], true ) ) {
				return true;
			}

			return false;
		}
	}
}
