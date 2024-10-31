<?php
/**
 * Prebook service model.
 *
 * @package Prebook
 * @since 1.0.0
 */

// Prebook namespace.
namespace Prebook\Model;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! class_exists( __NAMESPACE__ . '\User' ) ) {

	/**
	 * Prebook service model.
	 *
	 * @package Prebook
	 * @since 1.0.0
	 */
	class User extends \Prebook\Base\Model {


		/**
		 * Primary key of the table.
		 *
		 * @since 1.0.0
		 * @var string
		 */
		protected $primary_key = 'ID';

		/**
		 * Columns.
		 *
		 * @since 1.0.0
		 * @var array
		 */
		protected $columns = [ 'name' ];

		/**
		 * Fillable columns.
		 *
		 * @since 1.0.0
		 * @var array
		 */
		protected $fillable = [ 'ID', 'user_login', 'user_email', 'display_name' ];


		/**
		 * Get Id.
		 *
		 * @since 1.0.0
		 * @return int
		 */
		public function get_id() {
			return $this->_data[ $this->primary_key ] ?? 0;
		}

		/**
		 * Set id to primary key.
		 *
		 * @since 1.0.0
		 */
		public function set_id( $id ) {
			$this->{$this->primary_key} = $id;
		}



		/**
		 * Get Table name.
		 *
		 * @since 1.0.0
		 * @return string
		 */
		final public function get_table_name() {
			global $wpdb;

			return $wpdb->prefix . 'users';
		}

		/**
		 * Set meta.
		 *
		 * @since 1.0.0
		 * @param string $key Meta key.
		 * @param mixed  $value Meta value.
		 * @return bool
		 */
		final public function update_meta( $key = '', $value = null ) {
			return update_user_meta( $this->get_id(), $key, $value );
		}

		/**
		 * Delete meta.
		 *
		 * @since 1.0.0
		 * @param string $key Meta key.
		 * @return bool
		 */
		final public function delete_meta( $key ) {
			return delete_user_meta( $this->get_id(), $key );
		}

		/**
		 * Get metas.
		 *
		 * @since 1.0.0
		 * @param array|string $keys Array of keys or single key.
		 * @param mixed        $default Default value.
		 * @return mixed
		 */
		final public function get_meta( $keys, $default = null ) {
			$keys = is_array( $keys ) ? $keys : [ $keys ];

			$meta = [];

			foreach ( $keys as $key => $value ) {

				$_key = is_int( $key ) ? $value : $key;

				$_value = get_user_meta( $this->get_id(), $_key, true );

				if ( $_value && ! is_wp_error( $_value ) ) {
					$meta[ $_key ] = $_value;
				} else {
					$meta[ $_key ] = $default;
				}
			}

			return count( $keys ) > 1 ? $meta : $meta[ $keys[0] ];
		}


		 /**
		  * Name.
		  *
		  * @since 1.0.0
		  * @return string
		  */
		final public function name() {
			// Return display name if not empty.
			if ( $this->display_name ) {
				return $this->display_name;
			}

			// Return first name + last name from meta if not empty.
			$first_name = $this->get_meta( 'first_name', '' );
			$last_name  = $this->get_meta( 'last_name', '' );

			if ( $first_name || $last_name ) {
				return trim($first_name . ' ' . $last_name);
			}

			// Return user login if not empty.
			if ( $this->user_login ) {
				return $this->user_login;
			}

			return '';
		}

		/**
		 * Get email.
		 *
		 * @since 1.0.0
		 * @return string
		 */
		public function email() {
			return isset( $this->user_email ) && ! empty( $this->user_email ) ? $this->user_email : $this->get_meta( 'email', '' );
		}

		/**
		 * Set email.
		 *
		 * @since 1.0.0
		 */
		public function set_email( $email ) {
			try {
				$updated = wp_update_user( [
					'ID' => $this->get_id(),
					'user_email' => $email,
				] );
			} catch ( \Exception $e ) {
				return new \WP_Error( 'user_email', $e->getMessage() );
			}
			return $updated;
		}


		/**
		 * Random string.
		 *
		 * @since 1.0.0
		 */
		public function random_string() {
			return substr( md5( time() . wp_rand( 1000, 9999 ) ), 0, 8 );
		}

		/**
		 * Create user.
		 *
		 * @param array $data User data.
		 * @param array $meta User meta.
		 * @return int|\WP_Error User id or WP_Error.
		 */
		public function create( $data, $meta = null ) {
			$user_id = wp_insert_user( $data );

			if ( is_wp_error( $user_id ) ) {
				return $user_id;
			}

			// Set user role.
			$user = new \WP_User( $user_id );
			$user->set_role( $this->role );

			// Set ID.
			$this->set_id( $user_id );

			// Set meta.
			if ( $meta && is_array( $meta ) ) {
				foreach ( $meta as $key => $value ) {
					$this->update_meta( $key, $value );
				}
			}

			return $user_id;
		}

		/**
		 * Insert.
		 *
		 * @param array $data User data.
		 */
		public function insert( $data ) {

			$login = isset( $data['user_login'] ) ? $data['user_login'] : $this->random_string();
			$host = wp_parse_url( site_url(), PHP_URL_HOST );

			$default_data = [
				'user_login' => $this->random_string(),
				'user_pass' => wp_generate_password(),
				'user_email' => $data['user_email'] ?? $login . '@' . $host,
				'display_name' => $data['display_name'] ?? '',
			];

			$data = wp_parse_args( $data, $default_data );

			$user_id = $this->create( $data );

			$this->_data[ $this->primary_key ] = $user_id;

			return $this;
		}

		/**
		 * Get role.
		 *
		 * @since 1.0.0
		 * @return string
		 */
		public function role() {
			$user = new \WP_User( $this->get_id() );
			return isset( $user->roles[0] ) ? $user->roles[0] : '';
		}

			/**
		 * Integrated Meta keys
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function meta_keys() {
			return apply_filters( 'prebook_appointment_meta', [] );
		}

		/**
		 * Get integrated meta.
		 *
		 * @since 1.0.0
		 * @return mixed
		 */
		public function meta() {
			return $this->get_meta( $this->meta_keys() );
		}

		/**
		 * Status
		 *
		 * @since 1.0.0
		 */
		public function status() {
			return $this->get_meta( 'status', 'publish' );
		}


		/**
		 * Before query.
		 */
		protected function before_query() {
			global $wpdb;
			$this->join( $wpdb->usermeta . ' um', 't.ID = um.user_id');
			$this->where( [ 'um.meta_key' => 'wp_capabilities' ] );
			$this->where( [ 'um.meta_value' => [ 'LIKE', '%' . $this->role . '%' ] ] );
		}
	}
}
