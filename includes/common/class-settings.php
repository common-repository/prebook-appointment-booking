<?php
/**
 * Prebook Settings class.
 *
 * @package Prebook
 * @since 1.0.0
 */

// Prebook namespace.
namespace Prebook;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! class_exists( __NAMESPACE__ . '\Settings' ) ) {
	/**
	 * Prebook Settings class.
	 *
	 * @package Prebook
	 * @since 1.0.0
	 */
	class Settings extends \Prebook\Base {

		/**
		 * Get settings keys.
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function get_setting_options() {
			$options = [
				'appointment' => [
					'default_status' => 'approved',
					'default_buffer_time' => 0,
				],
				'calendar' => [
					'available_period_days' => 90,
					'first_day' => 'Monday',
					'time_format' => '12',
					'date_format' => 'm/d/Y',
				],
				'business' => [
					'logo_url' => '',
					'full_logo_url' => '',
					'name' => '',
					'type' => '',
					'about' => '',
					'street' => '',
					'city' => '',
					'state' => '',
					'zip_code' => '',
					'country' => '',
					'phone' => '',
					'website' => '',
					'email' => '',
					'provider_first_name' => '',
					'provider_last_name' => '',
					'provider_display_name' => '',
					'provider_bio' => '',
				],
				'business_hours' => [
					'Saturday' => [
						'enabled' => false,
					],
					'Sunday' => [
						'enabled' => false,
					],
					'Monday' => [
						'enabled' => true,
						'hours' => [
							[
								'start' => '9:00 AM',
								'end' => '5:00 PM',
							],
						],
					],
					'Tuesday' => [
						'enabled' => true,
						'hours' => [
							[
								'start' => '9:00 AM',
								'end' => '5:00 PM',
							],
						],
					],
					'Wednesday' => [
						'enabled' => true,
						'hours' => [
							[
								'start' => '9:00 AM',
								'end' => '5:00 PM',
							],
						],
					],
					'Thursday' => [
						'enabled' => true,
						'hours' => [
							[
								'start' => '9:00 AM',
								'end' => '5:00 PM',
							],
						],
					],
					'Friday' => [
						'enabled' => true,
						'hours' => [
							[
								'start' => '9:00 AM',
								'end' => '5:00 PM',
							],
						],
					],
				],
				'days_off' => [
					'holidays' => [],
				],
				'payment' => [
					'currency' => 'USD',
					'currency_symbol' => '$',
					'currency_position' => 'before',
					'price_separator' => 'Comma-Dot',
					'price_decimals' => '2',

					'methods' => [
						[
							'id' => 'cash',
							'enabled' => true,
							'name' => 'Cash',
							'label' => 'Cash',
							'description' => 'Pay with cash when you arrive at the business',
						],
						// [
						//  'id' => 'stripe',
						//  'enabled' => false,
						//  'name' => 'Stripe',
						//  'label' => 'Stripe',
						//  'description' => 'Pay with your credit card using Stripe',
						//  'secret_key' => '',
						// ]
					],
					'default_payment_method' => 'cash',
					'default_payment_status' => 'unpaid',
				],
				'notification' => [
					'admin_email' => get_option( 'admin_email' ),
					'emails' => [
						'appointment_approved' => [
							'enabled' => true,
							'title' => 'Appointment Approved',
							'customer_subject' => 'Your appointment with {business_name} has been Approved',
							'customer_body' => $this->get_email_template('appointment/customer-approved'),
							'staff_subject' => 'New appointment approved - {customer_name}',
							'staff_body' => $this->get_email_template('appointment/staff-approved'),
						],
						'appointment_pending' => [
							'enabled' => true,
							'title' => 'Appointment Pending',
							'customer_subject' => 'Your appointment with {business_name} is Pending Approval',
							'customer_body' => $this->get_email_template('appointment/customer-pending'),
							'staff_subject' => 'New appointment pending approval - {customer_name}',
							'staff_body' => $this->get_email_template('appointment/staff-pending'),
						],
						'appointment_rejected' => [
							'enabled' => true,
							'title' => 'Appointment Rejected',
							'customer_subject' => 'Your appointment with {business_name} has been Rejected',
							'customer_body' => $this->get_email_template('appointment/customer-rejected'),
							'staff_subject' => 'Appointment Rejected - {customer_name}',
							'staff_body' => $this->get_email_template('appointment/staff-rejected'),
						],
						'appointment_canceled' => [
							'enabled' => true,
							'title' => 'Appointment Canceled',
							'customer_subject' => 'Your appointment with {business_name} has been Canceled',
							'customer_body' => $this->get_email_template('appointment/customer-canceled'),
							'staff_subject' => 'Appointment Canceled - {customer_name}',
							'staff_body' => $this->get_email_template('appointment/staff-canceled'),
						],
					],
				],
				'customizer' => [
					'service' => [
						'title' => 'Select Service',
						'show_price' => true,
						'show_hours' => true,
						'show_image' => true,
						'layout_type' => 'row01',
					],
					'staff' => [
						'title' => 'Select Staff',
						'show_image' => true,
						'show_details' => true,
						'layout_type' => 'grid04',
					],
					'date' => [
						'title' => 'Select Date',
						'show_progress' => true,
					],
					'time' => [
						'title' => 'Select Time',
						'show_time' => true,
						'show_time_icon' => true,
						'slot_subtitle' => 'Pick a time slot for',
						'continue_button' => 'Continue',
					],
					'customer_information' => [
						'title' => 'Enter information',
						'subtitle' => 'Login or create an account if you are new here',
						'show_placeholder' => true,
						'signup_title' => 'Create New Account',
						'signup_firstname_label' => 'First Name',
						'signup_lastname_label' => 'Last Name',
						'signup_email_label' => 'Email',
						'signup_phonenumber_label' => 'Phone',
						'signup_password_label' => 'Password',
						'login_title' => 'Login',
						'login_email_label' => 'Email',
						'login_password_label' => 'Password',
						'login_button' => 'Login',
						'signup_button' => 'Signup',
						'signup_firstname_placeholder' => 'First name',
						'signup_lastname_placeholder' => 'Last name',
						'signup_email_placeholder' => 'you@gmail.com',
						'signup_phonenumber_placeholder' => '+1 (555) 000-0000',
						'signup_password_placeholder' => 'Password',
						'login_email_placeholder' => 'Your email address',
						'login_password_placeholder' => 'Password',
					],
					'booking_overview' => [
						'title' => 'Overview',
						'show_staff' => true,
						'show_customer' => true,
						'show_address' => true,
						'show_payment_method' => true,
						'show_payment_summary' => true,
					],
					'sidebar' => [
						'show_background' => false,
						'background_type' => '',
						'background_colors' => [
							'#004D65',
							'#012F7B',
							'#1A0A52',
							'#450D59',
							'#551029',
							'#831100',
							'#7B2900',
							'#7A4A00',
							'#6F760A',
							'#38571A',
							'#016E8F',
							'#0042A9',
							'#2C0977',
							'#61187C',
							'#791A3D',
							'#B51A00',
							'#AD3E00',
							'#A96800',
							'#9BA50E',
							'#4E7A27',
							'#008CB4',
							'#0056D6',
							'#371A94',
							'#7A219E',
							'#99244F',
							'#E22400',
							'#DA5100',
							'#D38301',
							'#C3D117',
							'#669D34',
							'#00A1D8',
							'#0061FD',
							'#4D22B2',
							'#982ABC',
							'#B92D5D',
							'#FF4015',
							'#FF6A00',
							'#FFAB01',
							'#D9EC37',
							'#76BB40',
							'#01C7FC',
							'#3A87FD',
							'#5E30EB',
							'#BE38F3',
							'#E63B7A',
							'#FE6250',
							'#FD920F',
							'#FFBD07',
							'#E4EF65',
							'#96D35F',
							'#52D6FC',
							'#74A7FF',
							'#7F56D9',
							'#D357FE',
							'#EE719E',
							'#FF8C82',
							'#FEA57D',
							'#FEC777',
							'#EAF28F',
							'#B1DD8B',
							'#FFFEFE',
							'#474747',
							'#333333',
							'#000000',
						],
						'background_color' => '#7F56D9',
						'background_image' => '',
						'content_position' => 'Left top',
						'business_name_color' => '#101828',
						'business_about_color' => '#475467',
						'provider_display_name_color' => '#101828',
						'provider_bio_color' => '#475467',
						'business_custom_logo' => '',
						'business_custom_name' => '',
						'business_custom_about' => '',
					],
					'design' => [
						'brand_color' => '#7F56D9',
						'corner_style' => 'Rounded',
						'font_family' => 'Plus Jakarta Sans',
						'typography_title_font_color' => '#101828',
						'typography_title_font_weight' => 'SemiBold',
						'typography_subtitle_font_color' => '#101828',
						'typography_subtitle_font_weight' => 'SemiBold',
						'input_border_color' => '#d0d5dd',
						'input_border_style' => 'Border',
						'button_text_font_color' => '#ffffff',
						'button_text_font_weight' => 'SemiBold',
						'button_background_color' => '#7F56D9',
						'button_background_style' => 'Filled',
						'button_border_radius' => 8,
					],
				],
				'addons' => [
					'calendar' => true,
					'holidays_days_off' => true,
					'staffs' => true,
					'payment' => true,
				],
			];

			return apply_filters( 'prebook_setting_options', $options );
		}

		/**
		 * Get settings keys.
		 *
		 * @since 1.0.0
		 * @param bool $with_prefix Whether to include prefix or not.
		 * @return array
		 */
		public function get_setting_fields( $with_prefix = false ) {
			$keys = [];

			$sections = $this->get_setting_options();

			foreach ( $sections as $section_key => $section_value ) {
				foreach ( $section_value as $key => $value ) {
					$keys[ $with_prefix ? PREBOOK_PREFIX . $section_key . '_' . $key : $section_key . '_' . $key ] = $value;
				}
			}

			return $keys;
		}

		/**
		 * Get settings.
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function get_settings() {

			global $prebook_settings;

			if ( ! empty( $prebook_settings ) ) {
				return $prebook_settings;
			}

			global $wpdb;

			$settings = $this->get_setting_options();

			// Fields with prefix.
			$fields = $this->get_setting_fields(true);

			// Get from wp_options table.
			$db_results = $wpdb->get_results(
				$wpdb->prepare( // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.ReplacementsWrongNumber
					'SELECT option_name, option_value FROM ' . $wpdb->options . ' WHERE option_name IN (' . implode(', ', array_fill( 0, count( $fields ), '%s' )) . ')',
					array_keys( $fields )
				), ARRAY_A );

			// If no results, return default settings.
			if ( ! $db_results ) {
				return $settings;
			}

			$db_results = wp_list_pluck( $db_results, 'option_value', 'option_name' );

			foreach ( $settings as $section_key => $fields ) {
				foreach ( $fields as $field_key => $field_value ) {
					if ( isset( $db_results[ PREBOOK_PREFIX . $section_key . '_' . $field_key ] ) ) {
						$settings[ $section_key ][ $field_key ] = maybe_unserialize( $db_results[ PREBOOK_PREFIX . $section_key . '_' . $field_key ] );

						// Decode JSON.
						if ( is_string( $settings[ $section_key ][ $field_key ] ) && is_array( json_decode( $settings[ $section_key ][ $field_key ], true ) ) && ( json_last_error() === JSON_ERROR_NONE ) ) {
							$settings[ $section_key ][ $field_key ] = json_decode( $settings[ $section_key ][ $field_key ], true );
						}

						// Return boolean if value is 0 or 1.
						if ( '0' === $settings[ $section_key ][ $field_key ] || '1' === $settings[ $section_key ][ $field_key ] ) {
							$settings[ $section_key ][ $field_key ] = wp_validate_boolean( $settings[ $section_key ][ $field_key ] );
						}
					} else {
						$settings[ $section_key ][ $field_key ] = $field_value;
					}
				}
			}

			$settings = apply_filters( 'prebook_settings', $settings );

			return $settings;
		}

		/**
		 * Get Section.
		 *
		 * @since 1.0.0
		 * @param string $section Section name.
		 * @return array
		 */
		public function get_section( $section = 'appointment' ) {
			$settings = $this->get_settings();

			return isset( $settings[ $section ] ) ? $settings[ $section ] : [];
		}

		/**
		 * Get Field.
		 *
		 * @since 1.0.0
		 * @param string $field Field name.
		 * @param string $section Section name.
		 * @param mixed  $default Default value.
		 * @return mixed
		 */
		public function get_field( $field = '', $section = 'appointment', $default = '' ) {
			$settings = $this->get_settings();

			return isset( $settings[ $section ][ $field ] ) ? $settings[ $section ][ $field ] : $default;
		}

		/**
		 * Get email template.
		 *
		 * @since 1.0.0
		 * @param string $file File name.
		 * @return string
		 */
		public function get_email_template( $file = '' ) {
			$file = PREBOOK_TEMPLATES . 'emails/' . $file . '.html';

			if ( file_exists( $file ) ) {
				return file_get_contents( $file ); // phpcs:ignore
			}

			return '';
		}
	}
}
