<?php
/**
 * Base Model class for Prebook
 *
 * @package Prebook
 * @since 1.0.0
 */

// Prebook namespace.
namespace Prebook\Base;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! class_exists( __NAMESPACE__ . 'Model' ) ) {

	/**
	 * Base model class for Prebook
	 *
	 * @package Prebook
	 * @since 1.0.0
	 */
	abstract class Model extends \Prebook\Base {

		/**
		 * The name of the table.
		 *
		 * @since 1.0.0
		 * @var string
		 */
		protected $table_name;

		/**
		 * Primary key of the table.
		 *
		 * @since 1.0.0
		 * @var string
		 */
		protected $primary_key = 'id';

		/**
		 * Created at column of the table.
		 *
		 * @since 1.0.0
		 * @var bool
		 */
		protected $created_at = true;

		/**
		 * Updated at column of the table.
		 *
		 * @since 1.0.0
		 * @var bool
		 */
		protected $updated_at = true;

		/**
		 * Fillable columns of the table.
		 *
		 * @since 1.0.0
		 * @var array
		 */
		protected $fillable = [
			'name',
			'short_description',
			'status',
			'updated_at',
		];


		/**
		 * Query args.
		 *
		 * @since 1.0.0
		 * @var array
		 */
		protected $default_args = [
			'select' => '*',
			'where_and' => [],
			'where_or' => [],
			'order_by' => '',
			'order' => 'ASC',
			'limit' => 0,
			'offset' => 0,
			'group_by' => '',
			'join' => [],
		];

		/**
		 * Query args.
		 *
		 * @since 1.0.0
		 * @var array
		 */
		protected $args = [];

		/**
		 * Return type.
		 *
		 * @since 1.0.0
		 * @var string
		 */
		protected $return = 'all'; // all, one, count.

		/**
		 * RAW query.
		 *
		 * @since 1.0.0
		 * @var string
		 */
		protected $query;

		/**
		 * Columns to be added to data.
		 *
		 * @since 1.0.0
		 * @var array
		 */
		protected $columns = [];

		/**
		 * Data holder.
		 *
		 * @since 1.0.0
		 * @var array
		 */
		public $_data = [];

		/**
		 * Constructor.
		 *
		 * @param array $_data Data.
		 * @since 1.0.0
		 */
		public function __construct( $_data = [] ) {
			$this->_data = $_data;

			// Reset query args.
			$this->args = $this->default_args;

			// Reset query.
			$this->query = '';

			// Reset return type.
			$this->return = 'all';
		}

		/**
		 * Get data.
		 *
		 * @since 1.0.0
		 * @param string $key Key.
		 * @return mixed
		 */
		public function __get( $key ) {

			// Return method if method exists.
			$restricted_methods = [
				'get_data',
				'get_table_name',
				'get_query',
				'get_where_query',
				'get_join_query',
				'get',
				'find',
				'first',
				'last',
				'insert',
				'update',
				'delete',
				'count',
				'wrap_data',
				'wrap_data_value',
			];
			if ( method_exists( $this, $key ) && ! in_array( $key, $restricted_methods ) ) {
				return $this->$key( $key );
			}

			// Return data if key exists.
			if ( array_key_exists( $key, $this->_data ) ) {
				return $this->_data[ $key ];
			}

			return null;
		}

		/**
		 * Set data.
		 *
		 * @since 1.0.0
		 * @param string $key Key.
		 * @param mixed  $value Value.
		 * @return void
		 */
		public function __set( $key, $value ) {
			$this->_data[ $key ] = $value;
		}

		/**
		 * Get data.
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function get_data() {
			$data = $this->_data;

			// Add columns to data.
			foreach ( $this->columns as $column ) {
				$data[ $column ] = $this->$column;
			}

			return $data;
		}

		/**
		 * Get primary key.
		 *
		 * @since 1.0.0
		 * @return string
		 */
		public function get_primary_key() {
			return $this->primary_key;
		}

		/**
		 * Get ID.
		 *
		 * @since 1.0.0
		 * @return int
		 */
		public function get_id() {
			return isset ( $this->_data[ $this->primary_key ] ) ? intval( $this->_data[ $this->primary_key ] ) : 0;
		}

		/**
		 * To array.
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function to_array() {
			return (array) $this->get_data();
		}

		/**
		 * Get Table name.
		 *
		 * @since 1.0.0
		 * @return string
		 */
		public function get_table_name() {
			global $wpdb;

			return $wpdb->prefix . PREBOOK_PREFIX . $this->table_name;
		}

		/**
		 * Query args.
		 *
		 * @since 1.0.0
		 * @param array $args Query args.
		 * @return \Prebook\Base\Model
		 */
		public function args( $args ) {
			$this->args = wp_parse_args( $args, $this->default_args );

			return $this;
		}

		/**
		 * Select columns.
		 *
		 * @since 1.0.0
		 * @param mixed $select String or array.
		 * @return \Prebook\Base\Model
		 */
		public function select( $select ) {

			// Convert array to string.
			if ( is_array( $select ) ) {
				$select = implode( ', ', $select );
			}

			$this->args['select'] = $select;

			return $this;
		}

		/**
		 * Where clause.
		 *
		 * @since 1.0.0
		 * @param array  $condition [ 'column' => [ 'operator', 'value' ] ].
		 * @param string $logic AND|OR.
		 * @return \Prebook\Base\Model
		 */
		public function where( $condition = [], $logic = 'and' ) {

			// Bail early if condition is not array or empty.
			if ( ! is_array( $condition ) || empty( $condition ) ) {
				return $this;
			}

			$logic = strtolower( $logic );

			// Add condition to where.
			$this->args[ "where_{$logic}" ][] = $condition;
			// Merge condition with existing where.

			return $this;
		}

		/**
		 * Order by clause.
		 *
		 * @since 1.0.0
		 * @param string $column Column name.
		 * @param string $order ASC|DESC.
		 * @return \Prebook\Base\Model
		 */
		public function order_by( $column, $order = 'ASC' ) {
			$this->args['order_by'] = $column;
			$this->args['order'] = $order;

			return $this;
		}

		/**
		 * Limit clause.
		 *
		 * @since 1.0.0
		 * @param int $limit Limit.
		 * @return \Prebook\Base\Model
		 */
		public function limit( $limit ) {
			$this->args['limit'] = $limit;

			return $this;
		}

		/**
		 * Offset clause.
		 *
		 * @since 1.0.0
		 * @param int $offset Offset.
		 * @return \Prebook\Base\Model
		 */
		public function offset( $offset ) {
			$this->args['offset'] = $offset;

			return $this;
		}

		/**
		 * Group by clause.
		 *
		 * @since 1.0.0
		 * @param string $column Column name.
		 * @return \Prebook\Base\Model
		 */
		public function group_by( $column ) {
			$this->args['group_by'] = $column;

			return $this;
		}

		/**
		 * Join clause.
		 *
		 * @since 1.0.0
		 * @param string $table Table name.
		 * @param string $condition Condition.
		 * @param string $type INNER|LEFT|RIGHT.
		 * @return \Prebook\Base\Model
		 */
		public function join( $table, $condition, $type = 'INNER' ) {
			$this->args['join'][] = " {$type} JOIN {$table} ON {$condition}";

			return $this;
		}

		/**
		 * Search clause.
		 *
		 * @since 1.0.0
		 * @param mixed $value Search value.
		 * @param mixed $search_in Search in column.
		 * @return \Prebook\Base\Model
		 */
		public function search( $value, $search_in = null ) {

			// Bail early if value is empty.
			if ( empty( $value ) ) {
				return $this;
			}

			if ( ! $search_in ) {
				$search_in = [ 'name', 'short_description' ];
			}

			// Convert search_in to array.
			if ( is_string( $search_in ) ) {
				$search_in = [ $search_in ];
			}

			// Bail early if  search_in is empty.
			if ( empty( $search_in ) ) {
				return $this;
			}

			foreach ( $search_in as $column ) {
				$this->where( [
					$column => [ 'LIKE', "%{$value}%" ],
				], 'OR' );
			}

			return $this;
		}


		/**
		 * Get Where query.
		 *
		 * @since 1.0.0
		 * @param array  $conditions [ 'column' => [ 'operator', 'value' ] ].
		 * @param string $logic AND|OR.
		 * @return string
		 */
		public function get_where_query( $conditions, $logic = 'AND' ) {
			$where_query = [];

			foreach ( $conditions as $condition ) {
				$key = key( $condition );
				$value = $condition[ $key ];

				$operator = is_array( $value ) ? $value[0] : '=';
				$value = is_array( $value ) ? $value[1] : $value;

				// Wrap value with parenthesis if value is array.
				if ( is_array( $value ) ) {

					$value = array_map( function ( $item ) {
						return is_string( $item ) ? "'{$item}'" : $item;
					}, $value );

					$value = '(' . implode( ', ', $value ) . ')';
				} elseif ( is_string( $value ) ) {
					$value = "'{$value}'";
				}

				$where_query[] = "{$key} {$operator} {$value}";
			}

			return implode( " {$logic} ", $where_query );
		}

		/**
		 * Get join query.
		 *
		 * @since 1.0.0
		 * @param array $join [ 'table', 'condition', 'type' ].
		 * @return string
		 */
		public function get_join_query( $join ) {
			return implode( ' ', $join );
		}

		/**
		 * Before query.
		 *
		 * @since 1.0.0
		 */
		protected function before_query() {}

		/**
		 * Get query.
		 *
		 * @since 1.0.0
		 * @return string
		 */
		public function get_query() {

			// Return query if query is already set.
			if ( ! empty( $this->query ) ) {
				return $this->query;
			}

			$query = "SELECT {$this->args['select']} FROM {$this->get_table_name()} AS t";

			// Join.
			if ( ! empty( $this->args['join'] ) ) {
				$query .= $this->get_join_query( $this->args['join'] );
			}

			// Where.
			if ( ! empty( $this->args['where_and'] ) || ! empty( $this->args['where_or'] ) ) {
				$query .= ' WHERE ';

				$where = [];

				$where_and = $this->get_where_query( $this->args['where_and'], 'AND' );
				$where_or = $this->get_where_query( $this->args['where_or'], 'OR' );

				if ( ! empty( $where_and ) ) {
					$where[] = $where_and;
				}

				if ( ! empty( $where_or ) ) {
					$where[] = $where_or;
				}

				$query .= implode( ' AND ', $where );
			}

			// Order by.
			if ( ! empty( $this->args['order_by'] ) ) {
				$query .= " ORDER BY {$this->args['order_by']} {$this->args['order']}";
			}

			// Limit.
			if ( ! empty( $this->args['limit'] ) ) {
				$query .= " LIMIT {$this->args['limit']}";
			}

			// Offset.
			if ( ! empty( $this->args['offset'] ) ) {
				$query .= " OFFSET {$this->args['offset']}";
			}

			// Group by.
			if ( ! empty( $this->args['group_by'] ) ) {
				$query .= " GROUP BY {$this->args['group_by']}";
			}

			return apply_filters( 'prebook_model_query', $query );
		}


		/**
		 * First record from the table.
		 *
		 * @since 1.0.0
		 * @return mixed
		 */
		public function first() {

			// Set limit.
			$this->limit( 1 );

			// Order by primary key in ascending order.
			$this->order_by( $this->primary_key, 'ASC' );

			// Set return type.
			$this->return = 'one';

			return $this->get();
		}

		/**
		 * Last record from the table.
		 *
		 * @since 1.0.0
		 * @return mixed
		 */
		public function last() {

			// Set limit.
			$this->limit( 1 );

			// Order by primary key in descending order.
			$this->order_by( $this->primary_key, 'DESC' );

			// Set return type.
			$this->return = 'one';

			return $this->get();
		}

		/**
		 * Get columns
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function columns() {
			return apply_filters( 'prebook_model_columns', $this->fillable, $this );
		}

		/**
		 * Insert a record into the table.
		 *
		 * @since 1.0.0
		 * @param array $data [ 'column' => 'value' ].
		 * @throws \Exception If data is empty.
		 * @return mixed
		 */
		public function insert( $data ) {

			// Bail early if data is empty.
			if ( empty( $data ) ) {
				return false;
			}

			// Remove non fillable columns.
			$fillable = $this->columns();
			$data = array_intersect_key( $data, array_flip( $fillable ) );

			global $wpdb;

			try {
				$wpdb->insert( $this->get_table_name(), $data );
			} catch ( \Exception $e ) {
				throw new \Exception( esc_html( $e->getMessage() ) );
			}

			$this->_data[ $this->primary_key ] = $wpdb->insert_id;

			return new static( $this->_data );
		}

		/**
		 * Update a record in the table.
		 *
		 * @since 1.0.0
		 * @param array $data [ 'column' => 'value' ].
		 * @param array $where [ 'column' => [ 'operator', 'value' ] ].
		 * @throws \Exception If data is empty.
		 * @throws \Exception If ID is empty.
		 * @return mixed
		 */
		public function update( $data = [], $where = null ) {

			// Bail early if data is empty.
			if ( empty( $data ) ) {
				return false;
			}

			if ( empty( $where ) ) {
				// If data is empty, throw error.
				if ( empty( $this->get_id() ) ) {
					throw new \Exception( esc_html__( 'ID is required to update a record.', 'prebook' ) );
				}

				$where = [
					$this->primary_key => $this->get_id(),
				];
			}

			// If updated_at exists, update it.
			if ( $this->updated_at ) {
				$data['updated_at'] = gmdate( 'Y-m-d H:i:s' );
			}

			// Remove non fillable columns.
			$fillable = $this->columns();
			$data = array_intersect_key( $data, array_flip( $fillable ) );

			global $wpdb;

			try {
				$updated = $wpdb->update( $this->get_table_name(), $data, $where );

				// If updated, return updated object.
				if ( $updated ) {
					return new static( $this->_data );
				}
			} catch ( \Exception $e ) {
				throw new \Exception( esc_html($e->getMessage()) );
			}

			return $this;
		}

		/**
		 * Delete a record from the table.
		 *
		 * @since 1.0.0
		 * @param array $where [ 'column' => [ 'operator', 'value' ] ].
		 * @throws \Exception If ID is empty.
		 * @throws \Exception If data is empty.
		 * @return mixed
		 */
		public function delete( $where = [] ) {

			// If where is empty, set primary key as where.
			if ( empty( $where ) ) {

				// If data is empty, throw error.
				if ( empty( $this->get_id() ) ) {
					throw new \Exception( esc_html__( 'ID is required to delete a record.', 'prebook' ) );
				}

				$where = [
					$this->primary_key => $this->get_id(),
				];
			}

			try {
				global $wpdb;
				return $wpdb->delete( $this->get_table_name(), $where );
			} catch ( \Exception $e ) {
				throw new \Exception( esc_html($e->getMessage()) );
			}

			return false;
		}


		/**
		 * Data wrapper methods.
		 *
		 * @since 1.0.0
		 * @param mixed $row Array.
		 * @return object
		 */
		protected function wrap_data( $row ) {

			// Bail early if row is empty.
			if ( empty( $row ) ) {
				return $row;
			}

			$formatted_row = [];

			foreach ( $row as $key => $value ) {
				$formatted_row[ $key ] = $this->wrap_data_value( $key, $value );
			}

			return new static( $formatted_row );
		}

		/**
		 * Data wrapper value.
		 *
		 * @since 1.0.0
		 * @param string $key Key.
		 * @param mixed  $value Value.
		 * @return mixed
		 */
		protected function wrap_data_value( $key, $value ) {

			// Bail early if value is empty.
			if ( empty( $value ) ) {
				return $value;
			}

			switch ( $key ) {

				case $this->primary_key:
				case 'category_id':
				case 'appointment_id':
				case 'staff_id':
				case 'id':
					$value = intval( $value );
					break;

				case 'created_at':
				case 'updated_at':
					$value = gmdate( 'Y-m-d H:i:s', strtotime( $value ) );
					break;

				default:
					$value = $value;
					break;

			}

			return $value;
		}

		/**
		 * Relation methods.
		 */

		/**
		 * One to one relation.
		 *
		 * @since 1.0.0
		 * @param string $model Return model.
		 * @param string $foreign_key Foreign key.
		 * @param string $local_key Local key.
		 * @return mixed
		 */
		public function has_one( $model, $foreign_key = null, $local_key = null ) {
			$local_key = ! is_null( $local_key ) ? $local_key : $this->primary_key;
			$foreign_key = ! is_null( $foreign_key ) ? $foreign_key : $this->primary_key;

			$model = new $model();

			$model->where( [
				$foreign_key => $this->$local_key,
			] );

			return $model->first();
		}

		/**
		 * One to many relation.
		 *
		 * @since 1.0.0
		 * @param string $model Return model.
		 * @param string $foreign_key Foreign key.
		 * @param string $local_key Local key.
		 * @param bool   $return_key Return key.
		 * @return mixed
		 */
		public function has_many( $model, $foreign_key = null, $local_key = null, $return_key = false ) {
			$local_key = ! is_null( $local_key ) ? $local_key : $this->primary_key;
			$foreign_key = ! is_null ( $foreign_key ) ? $foreign_key : $this->primary_key;

			$model = new $model();

			if ( $return_key ) {
				$return_key = true === $return_key ? $foreign_key : $return_key;
				$model->select( $foreign_key );
			}

			$model->where( [
				't.' . $foreign_key => $this->get_id(),
			] );

			$result = $model->get();
			if ( $return_key && ! empty( $result ) ) {
				$result = array_map( function ( $item ) use( $return_key ) {
					return intval($item->$return_key);
				}, $result );
			}

			return $result ? $result : [];
		}

		/**
		 * Many to many relation.
		 *
		 * @since 1.0.0
		 * @param string $model Return model.
		 * @param string $through_model Through model.
		 * @param string $model_foreign_key Model foreign key.
		 * @param string $through_foreign_key Through foreign key.
		 * @param string $model_local_key Model local key.
		 * @param string $through_local_key Through local key.
		 * @param bool   $return_key Return key.
		 * @return mixed
		 */
		public function has_many_through(
			$model,
			$through_model,
			$model_foreign_key = null,
			$through_foreign_key = null,
			$model_local_key = null,
			$through_local_key = null,
			$return_key = false
		) {

			$model_local_key = ! is_null( $model_local_key ) ? $model_local_key : $this->primary_key;
			$through_local_key = ! is_null( $through_local_key ) ? $through_local_key : $this->primary_key;

			$model_foreign_key = ! is_null( $model_foreign_key ) ? $model_foreign_key : $this->primary_key;
			$through_foreign_key = ! is_null ( $through_foreign_key ) ? $through_foreign_key : $this->primary_key;

			$model = new $model();
			$through_model = new $through_model();

			if ( $return_key ) {
				$return_key = true === $return_key ? $model_foreign_key : $return_key;
				$model->select( 't.' . $return_key );
			} else {
				$model->select( 't.*' );
			}

			$model->join( $through_model->get_table_name() . ' AS th', "t.{$model_foreign_key} = th.{$through_foreign_key}" );

			$model->where( [
				"th.{$through_local_key}" => $this->get_id(),
			] );

			$result = $model->get();

			if ( $return_key && ! empty( $result ) ) {
				$result = array_map( function ( $item ) use ( $return_key ) {
					return $item->$return_key;
				}, $result );
			}

			return $result ? $result : false;
		}
	}
}
