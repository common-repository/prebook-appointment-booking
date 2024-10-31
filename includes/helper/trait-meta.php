<?php
/**
 * Object meta model.
 *
 * @package Prebook
 * @since 1.0.0
 */

// Prebook namespace.
namespace Prebook\Helper;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! trait_exists( __NAMESPACE__ . '\Meta' ) ) {

	/**
	 * Object meta model.
	 *
	 * @package Prebook
	 * @since 1.0.0
	 */
	trait Meta {

		/**
		 * Return Meta Table Name
		 *
		 * @since 1.0.0
		 * @return string
		 */
		final public function get_meta_table_name() {
			global $wpdb;
			return $wpdb->prefix . PREBOOK_PREFIX . 'meta';
		}

		 /**
		  * Meta
		  *
		  * @since 1.0.0
		  * @param mixed $meta_keys meta key.
		  * @param mixed $default default value.
		  * @return mixed
		  */
		final public function get_meta( $meta_keys, $default = null ) {

			$meta = [];

			$meta_keys = is_array( $meta_keys ) ? $meta_keys : array( $meta_keys );

			// Key value pair.
			foreach ( $meta_keys as $key => $value ) {
				if ( is_int( $key ) ) {
					$meta[ $value ] = $default;
				} else {
					$meta[ $key ] = $value;
				}
			}

			$keys = array_keys( $meta );

			global $wpdb;

			$row = $wpdb->get_results(
				$wpdb->prepare( // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.ReplacementsWrongNumber
					"SELECT * FROM {$wpdb->prefix}prebook_meta WHERE `object_id` = %d AND `object_type` = %s AND `meta_key` IN (" . implode( ', ', array_fill( 0, count( $keys ), '%s' ) ) . ')',
					$this->get_id(),
					$this->object_type,
					...$keys
				)
			);

			if ( ! $row ) {
				return count( $meta_keys ) > 1 ? $meta : $default;
			}

			foreach ( $row as $r ) {

				switch ( true ) {
					case 'true' === $r->meta_value || 'false' === $r->meta_value:
						$r->meta_value = wp_validate_boolean( $r->meta_value );
						break;
					case 'price' === $r->meta_key:
						// $r->meta_value =;
						// If has decimal, return numberformat + float. Otherwise, return int.
						$r->meta_value = strpos( $r->meta_value, '.' ) ? number_format( $r->meta_value, 2 ) : (int) $r->meta_value;
						break;
					case is_numeric( $r->meta_value ):
						$r->meta_value = (int) $r->meta_value;
						break;
					case is_serialized( $r->meta_value ):
						$r->meta_value = maybe_unserialize( $r->meta_value );
						break;

					default:
						break;
				}

				$meta[ $r->meta_key ] = maybe_unserialize( $r->meta_value );
			}

			return count( $meta_keys ) > 1 ? $meta : $meta[ $meta_keys[0] ];
		}

		/**
		 * Add meta.
		 *
		 * @param string $meta_key meta key.
		 * @param mixed  $meta_value meta value.
		 * @return bool
		 */
		final public function add_meta( $meta_key, $meta_value ) {

			// Throw error if no object id found.
			if ( ! $this->get_id() ) {
				return new \Exception( 'No object id found.' );
			}

			global $wpdb;

			$added = $wpdb->insert(
				$this->get_meta_table_name,
				array(
					'meta_key' => $meta_key,
					'meta_value' => maybe_serialize( $meta_value ),
					'object_id' => $this->get_id(),
					'object_type' => $this->object_type,
				)
			);

			return $added ? true : false;
		}

		/**
		 * Update meta.
		 *
		 * @param string $meta_key meta key.
		 * @param mixed  $meta_value meta value.
		 * @param bool   $add add if not exists.
		 * @return mixed
		 */
		final public function update_meta( $meta_key, $meta_value, $add = true ) {

			// Throw error if no object id found.
			if ( ! $this->get_id() ) {
				return new \Exception( 'No object id found.' );
			}

			// Check if meta exists.
			$meta = $this->get_meta( $meta_key );

			if ( is_null( $meta ) ) {

				if ( ! $add ) {
					return false;
				}

				return $this->add_meta( $meta_key, $meta_value );
			}

			global $wpdb;

			$value = array(
				'meta_value' => maybe_serialize( $meta_value ),
			);

			$where = array(
				'meta_key' => $meta_key,
				'object_id' => $this->get_id(),
				'object_type' => $this->object_type,
			);

			$updated = $wpdb->update(
				$this->get_meta_table_name(), $value, $where
			);

			return $updated;
		}

		/**
		 * Delete meta.
		 *
		 * @param string $meta_key meta key.
		 * @return bool
		 */
		final public function delete_meta( $meta_key ) {

			// Throw error if no object id found.
			if ( ! $this->get_id() ) {
				return new \Exception( 'No object id found.' );
			}

			global $wpdb;

			$deleted = $wpdb->delete(
				$this->meta_object()->get_table_name(),
				array(
					'meta_key' => $meta_key,
					'object_id' => $this->get_id(),
					'object_type' => $this->object_type,
				)
			);

			return $deleted ? true : false;
		}
	}
}
