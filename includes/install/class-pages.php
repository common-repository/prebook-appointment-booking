<?php
/**
 * Prebook Default Pages.
 *
 * @package Prebook
 * @since 1.0.0
 */
// Prebook namespace.
namespace Prebook\Install;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! class_exists( __NAMESPACE__ . 'Pages' ) ) {

	/**
	 * Prebook Default Pages.
	 *
	 * @package Prebook
	 * @since 1.0.0
	 */
	class Pages extends \Prebook\Base\Controller {

		/**
		 * Get Default Pages.
		 *
		 * @since 1.0.0
		 * @return array
		 */
		protected function get_default_pages() {
			$page_keys = array(
				'admin_page_id' => [
					'title' => __( 'Prebook Admin', 'prebook' ),
					'description' => 'This is the admin app page. Do not delete this page.',
				],
				'my_account_page_id' => [
					'title' => __( 'My Account', 'prebook' ),
					'description' => '[prebook-my-account]',
				],
				'book_an_appointment_page_id' => [
					'title' => __( 'Book an Appointment', 'prebook' ),
					'description' => '<!-- wp:prebook/form --><div data-prebook-booking="true" class="wp-block-prebook-form"></div><!-- /wp:prebook/form -->',
				],
			);

			return apply_filters( 'prebook_default_pages', $page_keys );
		}

		/**
		 * Create Admin App Page.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function create_pages() {

			$default_pages = $this->get_default_pages();

			// Bail, if default pages are not set.
			if ( empty( $default_pages ) ) {
				return;
			}

			foreach ( $default_pages as $option_key => $page ) {
				$page_id = get_option( PREBOOK_PREFIX . $option_key );

				// Get the page.
				$page_object = get_post( $page_id );

				// If the page doesn't exist, then create it.
				if ( ! $page_object ) {
					$page_id = wp_insert_post(
						array(
							'post_title'     => $page['title'],
							'post_content'   => $page['description'],
							'post_status'    => 'publish',
							'post_type'      => 'page',
							'comment_status' => 'closed',
							'ping_status'    => 'closed',
						)
					);
					update_option( PREBOOK_PREFIX . $option_key, $page_id );
				}
			}
		}
	}
}
