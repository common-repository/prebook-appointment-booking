<?php
/**
 * Plugin Name: PreBook - Appointment Booking and Online Scheduling Solution
 * Description: Appointment Booking and Online Scheduling Solution for WordPress
 * Version: 1.1.6
 * Author: PreBook
 * Author URI: https://getprebook.com/
 * License: GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: prebook
 * Domain Path: /languages
 * Requires at least: 5.0
 * Requires PHP: 7.3
 *
 * @package Prebook
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

// PreBook root file.
define( 'PREBOOK_FILE', __FILE__ );

// PreBook version.
define( 'PREBOOK_VERSION', '1.1.6' );

// Include the PreBook boot class.
require_once __DIR__ . '/includes/class-prebook-boot.php';
