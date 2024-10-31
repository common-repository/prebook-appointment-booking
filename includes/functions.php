<?php
/**
 * Procedural functions for Prebook
 *
 * @package Prebook
 * @since 1.0.0
 */

// Prebook title.
if ( ! function_exists( 'prebook_title' ) ) {

	/**
	 * Prebook title.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	function prebook_title() {
		echo esc_html( apply_filters( 'prebook_title', __( 'Prebook Admin', 'prebook' ) ) );
	}
}

// Prebook head.
if ( ! function_exists( 'prebook_head' ) ) {

	/**
	 * Prebook head.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	function prebook_head() {
		do_action( 'prebook_head' );
	}
}

// Prebook body class.
if ( ! function_exists( 'prebook_body_class' ) ) {

	/**
	 * Prebook body class.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	function prebook_body_class() {
		echo esc_attr( apply_filters( 'prebook_body_class', 'prebook-admin' ) );
	}
}

// Prebook footer.
if ( ! function_exists( 'prebook_footer' ) ) {

	/**
	 * Prebook footer.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	function prebook_footer() {
		do_action( 'prebook_footer' );
	}
}


// Options.
if ( ! function_exists( 'prebook_get_option' ) ) {

	/**
	 * Get option.
	 *
	 * @since 1.0.0
	 * @param string $option_key Option key.
	 * @param mixed  $default    Default value.
	 * @return mixed
	 */
	function prebook_get_option( $option_key, $default = null ) {

		// Gte default from settings.
		if ( null === $default ) {
			$settings = new \Prebook\Settings();
			$fields = $settings->get_setting_fields();
			$default = isset( $fields[ $option_key ] ) ? $fields[ $option_key ] : null;
		}

		$value = get_option( PREBOOK_PREFIX . $option_key, $default );

		if ( is_serialized( $value ) ) {
			$value = maybe_unserialize( $value );
		}

		// JSON.
		if ( is_string( $value ) && is_array( json_decode( $value, true ) ) && ( json_last_error() === JSON_ERROR_NONE ) ) {
			$value = json_decode( $value, true );
		}

		return $value;
	}
}

if ( ! function_exists( 'prebook_update_option' ) ) {

	/**
	 * Update option.
	 *
	 * @since 1.0.0
	 * @param string $option_key Option key.
	 * @param mixed  $value      Value.
	 * @return bool
	 */
	function prebook_update_option( $option_key, $value ) {
		return update_option( PREBOOK_PREFIX . $option_key, $value );
	}
}

if ( ! function_exists( 'prebook_delete_option' ) ) {

	/**
	 * Delete option.
	 *
	 * @since 1.0.0
	 * @param string $option_key Option key.
	 * @return bool
	 */
	function prebook_delete_option( $option_key ) {
		return delete_option( PREBOOK_PREFIX . $option_key );
	}
}

// Add option.
if ( ! function_exists( 'prebook_add_option' ) ) {

	/**
	 * Add option.
	 *
	 * @since 1.0.0
	 * @param string $option_key Option key.
	 * @param mixed  $value      Value.
	 * @return bool
	 */
	function prebook_add_option( $option_key, $value ) {
		return add_option( PREBOOK_PREFIX . $option_key, $value );
	}
}
