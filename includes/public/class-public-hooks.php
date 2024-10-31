<?php
/**
 * Public Hooks class for Prebook
 *
 * @package Prebook
 * @since 1.0.0
 */

// Prebook namespace.
namespace Prebook\Publics;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );


if ( ! class_exists( __NAMESPACE__ . 'Hooks' ) ) {

	/**
	 * Public Hooks class for Prebook
	 *
	 * @package Prebook
	 * @since 1.0.0
	 */
	class Hooks extends \Prebook\Base\Controller {

		// Actions.
		public function actions() {
			// Enqueue scripts.
			add_action( 'prebook_appointment_booked', [ $this, 'prebook_appointment_booked' ], 10, 3 );

			// Appointment updated.
			add_action( 'prebook_appointment_updated', [ $this, 'prebook_appointment_updated' ], 10, 3 );

			// Appointment created.
			add_action( 'prebook_appointment_created', [ $this, 'prebook_appointment_created' ], 10, 2 );

			// Appointment status changed.
			add_action( 'prebook_appointment_status_changed', [ $this, 'prebook_appointment_status_changed' ], 10, 4);

			// Customer created.
			add_action( 'prebook_customer_registered', [ $this, 'prebook_customer_created' ], 10, 1 );
			add_action( 'prebook_customer_created', [ $this, 'prebook_customer_created' ], 10, 1 );

			// Init.
			add_action( 'init', [ $this, 'validate_payment' ] );
		}

		/**
		 * Appointment booked
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function prebook_appointment_booked( $appointment, $data, $meta ) {
			// Get email template.
			$args = array_merge( $data, $meta );
			$args['appointment_id'] = $appointment->get_id();
			$args['booking_type'] = isset( $meta['booking_type'] ) ? $meta['booking_type'] : 'onsite';

			$template = $this->view( 'emails/booking/confirmation', $args, true );

			// Recipient.
			$recipient = $appointment->get_meta( 'customer_email' );

			// Subject.
			$subject = apply_filters( 'prebook_email_subject', __( 'Appointment Confirmation', 'prebook' ), $appointment, $data, $meta );

			// Headers.
			$headers = apply_filters( 'prebook_email_headers', [
				'Content-Type: text/html; charset=UTF-8',
			], $appointment, $data, $meta );

			// Send email.
			wp_mail( $recipient, $subject, $template, $headers );
		}

		/**
		 * Appointment updated
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function prebook_appointment_updated( $item, $request, $old_meta ) {

			// Status changed.
			if ( $request->has_param( 'meta' ) && $request->get_param( 'meta' ) ) {
				$meta = $request->get_param( 'meta' );

				// Status changed.
				if ( isset( $meta['status'] ) && $meta['status'] !== $old_meta['status'] ) {
					// Appointment status changed.
					do_action( 'prebook_appointment_status_changed', $item, $old_meta['status'], $meta['status'], $request );
				}
			}
		}

		/**
		 * Appointment created
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function prebook_appointment_created( $item, $request = null ) {
			$created_status = $item->get_meta( 'status' );
			$this->prebook_appointment_status_changed( $item, null, $created_status, $request );
		}

		/**
		 * Appointment status changed
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function prebook_appointment_status_changed( $appointment, $old_status, $new_status, $request ) {

			$notification_emails = prebook_get_option( 'notification_emails' );

			$alerts = [
				'approved',
				'pending',
				'canceled',
				'rejected',
			];

			// Bail, if old status is same as new status.
			if ( $old_status === $new_status ) {
				return;
			}

			// Bail, if new status is not in alerts.
			if ( ! in_array( $new_status, $alerts ) ) {
				return;
			}

			$email = $notification_emails[ 'appointment_' . $new_status ];

			// Recipient.
			$recipient = $appointment->get_meta( 'customer_email' );

			if ( $recipient ) {
				$template = $this->build_email_template( $email['customer_body'], $appointment, $recipient);

				// Subject.
				$subject = apply_filters( 'prebook_email_subject', $this->build_email_template( $email['customer_subject'], $appointment, $recipient, false ), $appointment, $request, $appointment->meta() );

				// Headers.
				$headers = apply_filters( 'prebook_email_headers', [
					'Content-Type: text/html; charset=UTF-8',
				], $appointment, $request, $appointment->meta() );

				// If customer email exists.
				if ( $recipient ) {
					// Send email.
					wp_mail( $recipient, $subject, $template, $headers );
				}
			}

			// Staff email.
			$user = get_user_by( 'id', $appointment->staff_id );

			if ( $user ) {
				$staff_email = $user->user_email;

				// If staff email exists.
				if ( $staff_email ) {
					$template = $this->build_email_template( $email['staff_body'], $appointment, $staff_email );

					// Subject.
					$subject = apply_filters( 'prebook_email_subject', $this->build_email_template( $email['staff_subject'], $appointment, $staff_email, false ), $appointment, $request, $appointment->meta() );

					// Headers.
					$headers = apply_filters( 'prebook_email_headers', [
						'Content-Type: text/html; charset=UTF-8',
					], $appointment, $request, $appointment->meta() );

					// Send email.
					wp_mail( $staff_email, $subject, $template, $headers );
				}
			}
		}

		/**
		 * Build email template
		 *
		 * @since 1.0.0
		 * @return string
		 */
		public function build_email_template( $template, $appointment, $to, $footer = true ) {

			// If wp error template.
			if ( is_wp_error( $template ) ) {
				return $template;
			}

			$settings = new \Prebook\Settings();

			$staff = $appointment->get_staff();
			$service = $appointment->get_service();
			$customer = $appointment->get_customer();

			$keys = [
				'site_name' => get_bloginfo( 'name' ),
				'site_url' => get_bloginfo( 'url' ),

				// Appointment.
				'appointment_id' => $appointment->get_id(),
				'booking_date' => $appointment->booking_date,
				'booking_time_start' => $appointment->booking_time_start,
				'booking_time_end' => $appointment->booking_time_end,
				'appointment_status' => ucfirst( $appointment->get_meta( 'status' ) ),

				// Staff.
				'staff_id' => $appointment->staff_id,
				'staff_name' => $staff->name ?? '',
				'staff_email' => $staff->email ?? '',
				'staff_phone' => $staff ? ( $staff->get_meta( 'phone' ) ?? '' ) : '',

				// Customer.
				'customer_id' => $customer->get_ucid(),
				'customer_name' => $customer->name,
				'customer_first_name' => $customer->get_meta( 'first_name' ),
				'customer_last_name' => $customer->get_meta( 'last_name' ),
				'customer_email' => $customer->email,
				'customer_phone' => $customer->get_meta( 'phone' ),
				'customer_address' => $customer->get_meta( 'address' ),

				// Service.
				'service_id' => $appointment->service_id,
				'service_name' => $service->name,
				'service_price' => $appointment->get_meta( 'service_price' ),
				'service_duration' => $appointment->get_meta( 'service_duration' ),

				'address' => $appointment->get_meta( 'address' ),
				'payment_method' => ucfirst( $appointment->get_meta( 'payment_method' ) ),
				'payment_status' => ucfirst( $appointment->get_meta( 'payment_status' ) ),
				'customer_note' => $appointment->get_meta( 'customer_note' ),
			];

			// Merge business keys.
			$business_keys = $settings->get_section( 'business' );
			if ( $business_keys ) {
				foreach ( $business_keys as $key => $value ) {

					if ( 'type' === $key ) {
						$value = ucfirst( $value );
					}

					$keys[ 'business_' . $key ] = $value;
				}

				// Add business country full.
				$countries = \Prebook\Options::get_instance()->get_countries();
				$country = array_search( $keys['business_country'], array_column( $countries, 'code' ) );
				$keys['business_country_name'] = $country ? $countries[ $country ]['name'] : '';
			}

			// Replace keys.
			foreach ( $keys as $key => $value ) {
				$value = ! empty( $value ) ? $value : '';
				$template = str_replace( '{' . $key . '}', $value, $template );
			}

			if ( $footer ) {
				// Footer text.
				$footer_text = wp_sprintf(
					'This email was sent to %s. If you\'d rather not receive this kind of email, you can unsubscribe or manage your email preferences. © %s %s',
				$to, gmdate('Y'), prebook_get_option('business_name'));

				$footer_text = apply_filters( 'prebook_email_footer_text', $footer_text, $appointment, $to );

				$template = $template . '<br/><br/>' . $footer_text;
			}

			// Replace all new lines with <br>.
			$template = nl2br( $template );

			return $template;
		}

		/**
		 * Customer created.
		 *
		 * @since 1.0.0
		 * @param object   $customer Customer.
		 * @param object $request  Request body.
		 * @return void
		 */
		public function prebook_customer_created( $customer ) {
			$customer->generate_ucid();
		}

		/**
		 * Validate payment.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function validate_payment() {
		}
	}

	// Initialize the class.
	Hooks::init();
}
