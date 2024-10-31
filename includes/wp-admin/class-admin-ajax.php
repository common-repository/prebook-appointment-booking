<?php
/**
 * Admin AJAX class for Prebook
 *
 * @package Prebook
 * @since 1.0.0
 */

// Prebook namespace.
namespace Prebook\WP_Admin;

// Models.
require_once PREBOOK_INCLUDES . 'models/class-model-category.php';
require_once PREBOOK_INCLUDES . 'models/class-model-service.php';
require_once PREBOOK_INCLUDES . 'models/class-model-appointment.php';
require_once PREBOOK_INCLUDES . 'models/class-model-staff.php';
require_once PREBOOK_INCLUDES . 'models/class-model-customer.php';
require_once PREBOOK_INCLUDES . 'models/class-model-transaction.php';

// Use models.
use Prebook\Model\Category;
use Prebook\Model\Service;
use Prebook\Model\Appointment;
use Prebook\Model\Staff;
use Prebook\Model\Customer;
use Prebook\Model\Transaction;

// Use Error.
use Prebook\Error;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

// Input trait.
require_once PREBOOK_INCLUDES . 'helper/trait-input.php';

if ( ! class_exists( __NAMESPACE__ . 'Ajax' ) ) {

	/**
	 * Admin AJAX class for Prebook
	 *
	 * @package Prebook
	 * @since 1.0.0
	 */
	class Ajax extends \Prebook\Base\Ajax {

		// Use Input trait.
		use \Prebook\Helper\Input;

		/**
		 * Ajax actions.
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function ajax_actions() {
			return [
				// Media.
				'admin_get_media' => array( $this, 'get_media' ),
				'admin_upload_media' => array( $this, 'upload_media' ),
			];
		}

		/**
		 * Whitelisted Ajax actions.
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function whitelist_ajax_actions() {
			return array_keys( $this->ajax_actions() );
		}

		/**
		 * Get media.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function get_media() {
			// Check nonce.
			check_ajax_referer( 'prebook_admin_nonce', 'nonce');

			// Get all wp media.
			global $wpdb;

			// Join image url.

			$image_url = wp_get_upload_dir()['baseurl'] . '/';

			$media = $wpdb->get_results( $wpdb->prepare( "SELECT p.ID as id, p.post_title as name, CONCAT(%s, pm.meta_value) as url, pm2.meta_value as meta FROM {$wpdb->prefix}posts AS p LEFT JOIN {$wpdb->prefix}postmeta as pm ON p.ID = pm.post_id LEFT JOIN {$wpdb->prefix}postmeta as pm2 ON p.ID = pm2.post_id WHERE p.post_type = 'attachment' AND p.post_mime_type LIKE %s AND pm.meta_key = '_wp_attached_file' AND pm2.meta_key = '_wp_attachment_metadata'", $image_url, 'image/%%' ) );

			// Bail, if no media found.
			if ( empty( $media ) ) {
				wp_send_json_success( array() );
			}

			$media = array_map( function ( $media ) {
				$row = $media;
				$row->meta = unserialize( $media->meta );
				return $row;
			}, $media );

			wp_send_json_success( $media );
		}

		/**
		 * Upload media.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function upload_media() {
			// Check nonce.
			check_ajax_referer( 'prebook_admin_nonce', 'nonce');

			error_log( print_r( $_FILES, true ) );

			// Allow blob type.
			if ( ! isset( $_FILES['image']['type'] ) ) {
				$_FILES['image']['type'] = 'image/png';
			}

			try {
				// Upload media to wp media library, native wp function.
				$attachment_id = media_handle_upload( 'image', 0 );
			} catch ( \Exception $e ) {
				wp_send_json_error( $e->getMessage() );
			}

			if ( is_wp_error( $attachment_id ) ) {
				wp_send_json_error( $attachment_id->get_error_message() );
			}

			// Get attachment.
			$attachment = get_post( $attachment_id );

			// Get attachment url.
			$attachment_url = wp_get_attachment_url( $attachment_id );

			$output = array(
				'media_id' => $attachment_id,
				'name' => $attachment->post_title,
				'meta' => maybe_unserialize( $attachment->meta ),
				'url' => $attachment_url,
			);

			wp_send_json_success( $output );
		}
	}


	// Initialize the class.
	Ajax::init();
}
