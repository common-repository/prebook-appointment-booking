<?php
/**
 * Prebook Database Tables
 *
 * @package Prebook
 * @since 1.0.0
 */

// Prebook namespace.
namespace Prebook\Install;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! class_exists( __NAMESPACE__ . 'Tables' ) ) {

	/**
	 * Prebook Database Tables
	 *
	 * @package Prebook
	 * @since 1.0.0
	 */
	class Tables extends \Prebook\Base\Controller {

		/**
		 * Creates default tables.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function create_tables() {

			global $wpdb;

			// Run queries for categories.
			$wpdb->query( "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}prebook_categories (
                `category_id` int(15) NOT NULL AUTO_INCREMENT,
                `name` varchar(255) NULL,
                `short_description` text NULL,
                `status` varchar(50) NOT NULL DEFAULT 'publish',
                `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` datetime NULL,
                PRIMARY KEY (`category_id`)  
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;" );

			// Run queries for services.
			$wpdb->query( "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}prebook_services (
                `service_id` int(15) NOT NULL AUTO_INCREMENT,
                `name` varchar(255) NULL,
                `category_id` int(11) NULL,
                `short_description` text NULL,
                `status` varchar(50) NOT NULL DEFAULT 'publish',
                `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` datetime NULL,
                PRIMARY KEY (`service_id`)  
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;" );

			// Run queries for appointments.
			$wpdb->query( "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}prebook_appointments (
                `appointment_id` int(15) NOT NULL AUTO_INCREMENT,
                `name` varchar(255) NULL,
                `short_description` text NULL,
                `status` varchar(50) NOT NULL DEFAULT 'publish',
                `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` datetime NULL,
				`service_id` int(20) NULL,
                `staff_id` int(11) NULL,
                `booking_date` varchar(50) NULL,
                `booking_time_start` varchar(50) NULL,
                `booking_time_end` varchar(50) NULL,
                `customer_id` int(11) NULL,
                PRIMARY KEY (`appointment_id`)  
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;" );

			// Service staffs.
			$wpdb->query( "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}prebook_service_staffs (
                `id` int(20) NOT NULL AUTO_INCREMENT,
                `service_id` int(11) NOT NULL,
                `staff_id` int(11) NOT NULL,
                `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)  
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;" );

			// Prebook meta.
			$wpdb->query( "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}prebook_meta (
                `meta_id` int(20) NOT NULL AUTO_INCREMENT,
                `object_type` varchar(255) NULL DEFAULT 'service',
                `object_id` int(11) NOT NULL,
                `meta_key` varchar(255) NULL,
                `meta_value` text NULL,
                PRIMARY KEY (`meta_id`)  
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;" );

            // Transactions.
            $wpdb->query( "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}prebook_transactions (
                `transaction_id` int(20) NOT NULL AUTO_INCREMENT,
                `appointment_id` int(11) NOT NULL,
                `customer_id` int(11) NULL,
                `payment_method` varchar(255) NULL,
                `payment_status` varchar(255) NULL,
                `payment_amount` decimal(10,2) NULL,
                `currency` varchar(255) NULL,
                `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`transaction_id`)  
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;" );
		}
	}
}
