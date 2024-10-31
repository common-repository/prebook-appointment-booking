<?php
/**
 * PreBook Modal button template.
 *
 * @package Prebook
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

$content = isset( $args['content'] ) ? $args['content'] : '';

?>

<style>
.prebook_modal_button {
	border: 1px solid #7F56D9;
	background-color: #7F56D9;
	color: #fff;
	box-shadow: 0px 1px 2px 0px rgba(16, 24, 40, 0.05);
	padding:8px 14px;
	border-radius:4px;

	&:hover {
		border: 1px solid #6941C6;
		background-color: #6941C6;
	}

	&:focus {
		border: 1px solid #7F56D9;
		background-color: #7F56D9;
		box-shadow: 0px 0px 0px 4px #F4EBFF, 0px 1px 2px 0px rgba(16, 24, 40, 0.05);
	}

	&:disabled {
		border: 1px solid #E9D7FE;
		background-color: #E9D7FE;
	}
}

</style>

<!-- Modal goes here  -->
<div class="prebook_modal_button_wrapper">
	<button class="prebook_modal_button prebook_modal_open" data-config="<?php echo esc_attr( wp_json_encode( $args ) ); ?>">
		<?php echo esc_html( $content ); ?>
	</button>
</div>
