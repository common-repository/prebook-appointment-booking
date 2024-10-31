<?php
/**
 * Prebook Admin Menus.
 *
 * @package Prebook
 * @since 1.0.0
 */

// Prebook namespace.
namespace Prebook\WP_Admin;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! class_exists( __NAMESPACE__ . 'Menus' ) ) {

	/**
	 * Prebook Admin Menus.
	 *
	 * @package Prebook
	 * @since 1.0.0
	 */
	class Menus extends \Prebook\Base\Controller {

		/**
		 * Actions.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function actions() {
			// Add admin menus.
			add_action( 'admin_menu', [ $this, 'add_menus' ] );

			// Add admin bar menus.
			add_action( 'admin_bar_menu', [ $this, 'add_admin_bar_menus' ], 100 );

			// Handle Template Redirect.
			add_action( 'template_redirect', [ $this, 'handle_template_redirect' ], 0 );

			// Footer.
			add_action( 'admin_footer', [ $this, 'menu_icon_styles' ] );

			// Plugin action links.
			add_filter( 'plugin_action_links_' . plugin_basename( PREBOOK_FILE ), [ $this, 'plugin_action_links' ] );
		}

		/**
		 * Adds admin menus.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function add_menus() {

			$admin_page_link = get_permalink( prebook_get_option( 'admin_page_id' ) );

			$prebook_logo_url = PREBOOK_ASSETS . 'images/prebook.svg';

			$user = wp_get_current_user();

			// Bail if the user role is not admin or staff.
			if ( ! in_array( 'administrator', $user->roles, true ) && ! in_array( 'staff', $user->roles, true ) ) {
				return;
			}

			// Add main menu.
			add_menu_page(
				__( 'Prebook', 'prebook' ),
				__( 'Prebook', 'prebook' ),
				'read',
				$admin_page_link,
				'',
				$prebook_logo_url,
				20
			);

			// Dashboard.
			add_submenu_page(
			 $admin_page_link,
			 __( 'Dashboard', 'prebook' ),
			 __( 'Dashboard', 'prebook' ),
			 'read',
			 $admin_page_link,
			 ''
			);

			// Calendar.
			add_submenu_page(
				$admin_page_link,
				__( 'Calendar', 'prebook' ),
				__( 'Calendar', 'prebook' ),
				'read',
				$admin_page_link . '/#/calendar'
			);

			// Appointments.
			add_submenu_page(
				$admin_page_link,
				__( 'Appointments', 'prebook' ),
				__( 'Appointments', 'prebook' ),
				'read',
				$admin_page_link . '/#/appointment'
			);
			// Customers.
			add_submenu_page(
				$admin_page_link,
				__( 'Customers', 'prebook' ),
				__( 'Customers', 'prebook' ),
				'read',
				$admin_page_link . '/#/customer'
			);
			// Payments.
			add_submenu_page(
				$admin_page_link,
				__( 'Payments', 'prebook' ),
				__( 'Payments', 'prebook' ),
				'read',
				$admin_page_link . '/#/transaction'
			);

			// Services.
			add_submenu_page(
				$admin_page_link,
				__( 'Services', 'prebook' ),
				__( 'Services', 'prebook' ),
				'read',
				$admin_page_link . '/#/service',
				''
			);

			// Staffs.
			add_submenu_page(
				$admin_page_link,
				__( 'Staffs', 'prebook' ),
				__( 'Staffs', 'prebook' ),
				'read',
				$admin_page_link . '/#/staff',
				''
			);

			// Notifications.
			add_submenu_page(
				$admin_page_link,
				__( 'Notifications', 'prebook' ),
				__( 'Notifications', 'prebook' ),
				'read',
				$admin_page_link . '/#/notification',
				''
			);

			// Customizer.
			add_submenu_page(
				$admin_page_link,
				__( 'Customizer', 'prebook' ),
				__( 'Customizer', 'prebook' ),
				'manage_options',
				$admin_page_link . '/#/customizer',
				''
			   );

			// Settings.
			add_submenu_page(
				$admin_page_link,
				__( 'Settings', 'prebook' ),
				__( 'Settings', 'prebook' ),
				'read',
				$admin_page_link . '/#/settings',
				''
			);
		}

		/**
		 * Adds admin bar menus.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function add_admin_bar_menus() {

			// Bail if the user role is not admin or staff.
			$user = wp_get_current_user();
			if ( ! in_array( 'administrator', $user->roles, true ) && ! in_array( 'staff', $user->roles, true ) ) {
				return;
			}

			global $wp_admin_bar;

			$admin_page_link = get_permalink( prebook_get_option( 'admin_page_id' ) );

			// Add admin bar menus.
			$wp_admin_bar->add_menu(
				[
					'id'    => 'prebook',
					'title' => __( 'Prebook', 'prebook' ),
					'href'  => $admin_page_link,
					'meta'  => [
						'class' => 'prebook-admin-bar',
					],
				]
			);

			// Dashboard.
			$wp_admin_bar->add_menu(
			 [
			     'id'     => 'prebook-dashboard',
			     'parent' => 'prebook',
			     'title'  => __( 'Dashboard', 'prebook' ),
			     'href'   => $admin_page_link,
			 ]
			);

			// Calendar.
			$wp_admin_bar->add_menu(
				[
					'id'     => 'prebook-calendar',
					'parent' => 'prebook',
					'title'  => __( 'Calendar', 'prebook' ),
					'href'   => $admin_page_link . '/#/calendar',
				]
			);

			// Appointments.
			$wp_admin_bar->add_menu(
				[
					'id'     => 'prebook-appointments',
					'parent' => 'prebook',
					'title'  => __( 'Appointments', 'prebook' ),
					'href'   => $admin_page_link . '/#/appointment',
				]
			);

			// Customers.
			$wp_admin_bar->add_menu(
				[
					'id'     => 'prebook-customers',
					'parent' => 'prebook',
					'title'  => __( 'Customers', 'prebook' ),
					'href'   => $admin_page_link . '/#/customer',
				]
			);

			// Payments.
			$wp_admin_bar->add_menu(
				[
					'id'     => 'prebook-payments',
					'parent' => 'prebook',
					'title'  => __( 'Payments', 'prebook' ),
					'href'   => $admin_page_link . '/#/transaction',
				]
			);

			// Services.
			$wp_admin_bar->add_menu(
				[
					'id'     => 'prebook-services',
					'parent' => 'prebook',
					'title'  => __( 'Services', 'prebook' ),
					'href'   => $admin_page_link . '/#/service',
				]
			);
			// Staffs.
			$wp_admin_bar->add_menu(
				[
					'id'     => 'prebook-staffs',
					'parent' => 'prebook',
					'title'  => __( 'Staffs', 'prebook' ),
					'href'   => $admin_page_link . '/#/staff',
				]
			);

			// Notifications.
			$wp_admin_bar->add_menu(
				[
					'id'     => 'prebook-notifications',
					'parent' => 'prebook',
					'title'  => __( 'Notifications', 'prebook' ),
					'href'   => $admin_page_link . '/#/notification',
				]
			);

			// Customizer.
			$wp_admin_bar->add_menu(
				[
					'id'     => 'prebook-customizer',
					'parent' => 'prebook',
					'title'  => __( 'Customizer', 'prebook' ),
					'href'   => $admin_page_link . '/#/customizer',
				]
			   );

			// Settings.
			$wp_admin_bar->add_menu(
				[
					'id'     => 'prebook-settings',
					'parent' => 'prebook',
					'title'  => __( 'Settings', 'prebook' ),
					'href'   => $admin_page_link . '/#/settings',
				]
			);

			// Settings sub menus.

			// General.
			$wp_admin_bar->add_menu(
				[
					'id'     => 'prebook-settings-general',
					'parent' => 'prebook-settings',
					'title'  => __( 'General', 'prebook' ),
					'href'   => $admin_page_link . '/#/settings/general',
				]
			);

			// Business.
			$wp_admin_bar->add_menu(
				[
					'id'     => 'prebook-settings-business',
					'parent' => 'prebook-settings',
					'title'  => __( 'Business', 'prebook' ),
					'href'   => $admin_page_link . '/#/settings/business',
				]
			);

			// Business Hours.
			$wp_admin_bar->add_menu(
				[
					'id'     => 'prebook-settings-working-hours',
					'parent' => 'prebook-settings',
					'title'  => __( 'Business Hours', 'prebook' ),
					'href'   => $admin_page_link . '/#/settings/business-hours',
				]
			);

			// payments.
			$wp_admin_bar->add_menu(
				[
					'id'     => 'prebook-settings-payments',
					'parent' => 'prebook-settings',
					'title'  => __( 'Payments', 'prebook' ),
					'href'   => $admin_page_link . '/#/settings/payments',
				]
			);

			// Add New Appointment.
			$wp_admin_bar->add_menu(
				[
					'id'     => 'prebook-add-new-appointment',
					'parent' => 'new-content',
					'title'  => __( 'Appointment', 'prebook' ),
					'href'   => $admin_page_link . '/#/appointment/add',
				]
			);

			// Add New Service.
			$wp_admin_bar->add_menu(
				[
					'id'     => 'prebook-add-new-service',
					'parent' => 'new-content',
					'title'  => __( 'Service', 'prebook' ),
					'href'   => $admin_page_link . '/#/service/add',
				]
			);

			// Add New Staff.
			$wp_admin_bar->add_menu(
				[
					'id'     => 'prebook-add-new-staff',
					'parent' => 'new-content',
					'title'  => __( 'Staff', 'prebook' ),
					'href'   => $admin_page_link . '/#/staff/add',
				]
			);
		}

		/**
		 * Handles template redirect.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function handle_template_redirect() {

			// Bail, if not the prebook admin page.
			if ( get_the_ID() !== intval( prebook_get_option( 'admin_page_id' ) ) ) {
				return;
			}

			// Bail, if not a logged in user.
			if ( ! is_user_logged_in() ) {
				// Send the user to the login page and redirect back to the current page.
				wp_redirect( wp_login_url( get_permalink() ) );

				exit;
			}

			// Current user role.
			$current_user_role = current( wp_get_current_user()->roles );

			// Bail, if not admin or staff.
			if ( ! in_array( $current_user_role, [ 'administrator', 'staff' ], true ) ) {
				wp_redirect( home_url() );
				exit;
			}

			// Load the admin boot.

			require_once PREBOOK_INCLUDES . 'class-admin-boot.php';

			do_action( 'prebook_init' );

			\Prebook\Admin\Boot::start();

			// Get the template.
			$this->view( 'admin/main' );
			exit(0);
		}

		/**
		 * Menu icon styles.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function menu_icon_styles() {
			?>
			<style type="text/css">
				.wp-menu-image img[src$="prebook.svg"] {
					width: 20px;
					height: 20px;
				}
			</style>
			<?php
		}

		/**
		 * Plugin action links.
		 *
		 * @since 1.0.0
		 * @param array $links Plugin action links.
		 * @return array
		 */
		public function plugin_action_links( $links ) {
			// Unshift the link.
			array_unshift(
				$links,
				sprintf(
					'<a href="%s">%s</a>',
					get_permalink( prebook_get_option( 'admin_page_id' ) ),
					__( 'Dashboard', 'prebook' )
				)
			);
			return $links;
		}
	}

	// Run the class.
	Menus::init();
}
