<?php
/**
 * Prebook Admin Notices.
 *
 * @package Prebook
 * @since 1.0.0
 */

// Prebook namespace.
namespace Prebook\WP_Admin;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! class_exists( __NAMESPACE__ . 'Notices' ) ) {

	/**
	 * Prebook Admin Notices.
	 *
	 * @package Prebook
	 * @since 1.0.0
	 */
	class Notices extends \Prebook\Base\Controller {

		/**
		 * Actions.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function actions() {
			// Admin notices.
			add_action( 'admin_notices', [ $this, 'display_setup_notice' ] );
		}

		/**
		 * Display setup notice.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function display_setup_notice() {

			require_once PREBOOK_INCLUDES . '/models/class-model-service.php';
			require_once PREBOOK_INCLUDES . '/models/class-model-staff.php';

			$show_setup = false;

			$admin_page_link = get_permalink( prebook_get_option( 'admin_page_id' ) );

			// Check if business name is set.
			$business_name = prebook_get_option( 'business_name' );

			// Check if hours are updated.
			$business_hours_updated = wp_validate_boolean( prebook_get_option( 'business_hours_updated' ) );

			$show_setup = apply_filters( 'prebook_show_setup_notice', (
				empty ( $business_name )
				|| \Prebook\Model\Staff::get_instance()->count() < 1
				|| \Prebook\Model\Service::get_instance()->count() < 1
				|| ! $business_hours_updated
			) );

			if ( $show_setup ) {
				?>
				<div class="notice notice-warning">
					
					<p>
						<strong>Prebook: </strong>
						<?php
						echo wp_kses_post ( wp_sprintf( 'Please <a href="%s">Complete Setup</a> to use Prebook', $admin_page_link) );
						?>
					</p>
				</div>
				<?php
			}
		}
	}

	// Run the class.
	Notices::init();
}
