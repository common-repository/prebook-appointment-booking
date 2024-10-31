<?php
/**
 * Shortcode booking form template.
 *
 * @package Prebook
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

?>

<div data-prebook-booking  data-config="<?php echo esc_attr( wp_json_encode( $args ) ); ?>"></div>