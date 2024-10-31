<?php
/**
 * Prebook boot class.
 *
 * @package Prebook
 * @since 1.0.0
 */

// Prebook Namespace.
namespace Prebook;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! class_exists( __NAMESPACE__ . 'Boot' ) ) {

	/**
	 * Prebook boot class.
	 * Loads the plugin, fires the plugin hooks.
	 *
	 * @since 1.0.0
	 */
	class Boot {

		/**
		 * Instance of the Prebook class.
		 *
		 * @var object
		 */
		private static $instance;


		/**
		 * Starts the Prebook plugin.
		 *
		 * @since 1.0.0
		 */
		public static function start() {
			// Can be started only once.
			if ( ! is_null( self::$instance ) ) {
				return;
			}

			self::$instance = new self();

			// Defines constants.
			self::$instance->define_constants();

			register_activation_hook( PREBOOK_FILE, [ self::$instance, 'activate' ] );

			// Loads the plugin.
			self::$instance->load_files();

			// Fires the plugin loaded action.
			do_action( 'prebook_loaded' );
		}

		/**
		 * Defines the constants used by Prebook.
		 *
		 * @since 1.0.0
		 */
		protected function define_constants() {
			// Prebook paths.
			define( 'PREBOOK_PATH', plugin_dir_path( PREBOOK_FILE ) );
			define( 'PREBOOK_INCLUDES', PREBOOK_PATH . 'includes/' );
			define( 'PREBOOK_WP_ADMIN', PREBOOK_INCLUDES . 'wp-admin/' );
			define( 'PREBOOK_ADMIN', PREBOOK_INCLUDES . 'admin/' );
			define( 'PREBOOK_TEMPLATES', PREBOOK_PATH . 'templates/' );
			define( 'PREBOOK_REST', PREBOOK_INCLUDES . 'rest-api/' );

			// Prebook URLs.
			define( 'PREBOOK_URL', plugin_dir_url( PREBOOK_FILE ) );
			define( 'PREBOOK_ASSETS', PREBOOK_URL . 'assets/' );

			// Other constants.
			define( 'PREBOOK_PREFIX', 'prebook_' );
		}

		/**
		 * Loads the files used by Prebook.
		 *
		 * @since 1.0.0
		 */
		protected function load_files() {
			// Loads the core files first.
			$this->load_core_files();

			// Loads the common files next.
			$this->load_common_files();

			$this->load_public_files();

			$this->load_wp_admin_files();

			// Load REST API files.
			// $this->load_rest_files();
			add_action( 'rest_api_init', [ $this, 'load_rest_files' ] );

			// Loads the plugin text domain for translations.
			$this->load_textdomain();
		}

		/**
		 * Loads the core files used by Prebook.
		 *
		 * @since 1.0.0
		 */
		protected function load_core_files() {
			// Prebook procedural functions.
			require_once PREBOOK_INCLUDES . 'functions.php';

			// Base class.
			require_once PREBOOK_INCLUDES . 'abstracts/abstract-base.php';

			// Base controller.
			require_once PREBOOK_INCLUDES . 'abstracts/abstract-controller.php';

			// Base model.
			require_once PREBOOK_INCLUDES . 'abstracts/abstract-model.php';

			// Base Ajax handler.
			require_once PREBOOK_INCLUDES . 'abstracts/abstract-ajax.php';

			// Error handler.
			require_once PREBOOK_INCLUDES . 'common/class-error.php';
		}

		/**
		 * Load models.
		 *
		 * @since 1.0.0
		 */
		protected function load_models() {
			require_once PREBOOK_INCLUDES . 'models/class-model-appointment.php';
			require_once PREBOOK_INCLUDES . 'models/class-model-category.php';
			require_once PREBOOK_INCLUDES . 'models/class-model-customer.php';
			require_once PREBOOK_INCLUDES . 'models/class-model-meta.php';
			require_once PREBOOK_INCLUDES . 'models/class-model-service.php';
			require_once PREBOOK_INCLUDES . 'models/class-model-staff.php';
			require_once PREBOOK_INCLUDES . 'models/class-model-transaction.php';
			require_once PREBOOK_INCLUDES . 'models/class-model-service-staffs.php';
		}

		/**
		 * Loads the common files used by Prebook.
		 *
		 * @since 1.0.0
		 */
		protected function load_common_files() {
			// Upgrade.
			require_once PREBOOK_INCLUDES . 'install/class-upgrade.php';

			// Prebook.
			require_once PREBOOK_INCLUDES . 'common/class-options.php';

			// Prebook.
			require_once PREBOOK_INCLUDES . 'common/class-settings.php';

			require_once PREBOOK_INCLUDES . 'helper/trait-meta.php';

			// Prebook.
			require_once PREBOOK_INCLUDES . 'common/class-slot.php';

			// Admin hooks.
			require_once PREBOOK_INCLUDES . 'gutenberg/class-blocks.php';
		}

		/**
		 * Loads the public files used by Prebook.
		 *
		 * @since 1.0.0
		 */
		protected function load_public_files() {

			// Assets.
			require_once PREBOOK_INCLUDES . 'public/class-public-assets.php';

			// Shortcodes.
			require_once PREBOOK_INCLUDES . 'shortcodes/class-shortcode-form.php';
			require_once PREBOOK_INCLUDES . 'shortcodes/class-shortcode-button.php';

			// Public assets.
			if ( wp_doing_ajax() ) {
				require_once PREBOOK_INCLUDES . 'public/class-public-ajax.php';
			}

			require_once PREBOOK_INCLUDES . 'public/class-public-hooks.php';
		}

		/**
		 * Loads the admin files used by Prebook.
		 *
		 * @since 1.0.0
		 */
		protected function load_wp_admin_files() {

			// Admin menus.
			require_once PREBOOK_WP_ADMIN . 'class-admin-menus.php';

			// Admin hooks.
			require_once PREBOOK_WP_ADMIN . 'class-admin-hooks.php';

			// Admin hooks.
			require_once PREBOOK_WP_ADMIN . 'class-admin-notices.php';

			// Admin hooks.
			require_once PREBOOK_WP_ADMIN . 'class-admin-roles.php';

			// Public assets.
			if ( wp_doing_ajax() ) {
				require_once PREBOOK_WP_ADMIN . 'class-admin-ajax.php';
			}
		}

		/**
		 * Loads the REST API files used by Prebook.
		 *
		 * @since 1.0.0
		 */
		public function load_rest_files() {

			// Load models.
			$this->load_models();

			// Base REST API handler.
			require_once PREBOOK_INCLUDES . 'abstracts/abstract-rest.php';

			// REST Resources.
			require_once PREBOOK_INCLUDES . 'common/class-rest-resource.php';

			// REST Hooks.
			require_once PREBOOK_WP_ADMIN . 'class-rest-hooks.php';

			// REST Resources.
			require_once PREBOOK_REST . 'class-rest-appointment.php';
			require_once PREBOOK_REST . 'class-rest-category.php';
			require_once PREBOOK_REST . 'class-rest-customer.php';
			require_once PREBOOK_REST . 'class-rest-service.php';
			require_once PREBOOK_REST . 'class-rest-settings.php';
			require_once PREBOOK_REST . 'class-rest-staff.php';
			require_once PREBOOK_REST . 'class-rest-transaction.php';
		}

		/**
		 * Loads the plugin text domain for translations.
		 *
		 * @since 1.0.0
		 */
		protected function load_textdomain() {
			load_plugin_textdomain( 'prebook', false, dirname( plugin_basename( PREBOOK_FILE ) ) . '/languages/' );
		}

		/**
		 * Runs on plugin activation.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function activate() {
			require_once PREBOOK_INCLUDES . 'install/class-installer.php';

			// Run the installer.
			$installer = new \Prebook\Install\Installer();
			$installer->run();
		}
	}

	// Initializes the Prebook class.
	Boot::start();
}
