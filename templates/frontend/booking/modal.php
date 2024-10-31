<?php
/**
 * PreBook Modal template.
 *
 * @package Prebook
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

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

.prebook_modal{
	position: fixed;
	top: 0;
	left: 0;
	z-index: 99999;
	display: none;
	align-items: center;
	justify-content: center;
	height: 100%;
	width: 100%;
	background: #222222de;
}

.prebook_modal_show{
	display:flex
}
.prebook_modal_body{
	width: 80%;
	max-height: 90%;
	overflow-y: auto;
}
.prebook_modal_body > div {
	width: 100%;
}
</style>

<!-- Modal goes here  -->
<div class="prebook_modal prebook_modal_hide" id="prebook_modal_content">
	<!-- <div class="prebook_modal_overlay"></div> -->
	<div class="prebook_modal_body">
	<div data-prebook-booking></div>
	</div>
</div>

<script>
let modalButton = document.querySelector('.prebook_modal_open');
let modalContent = document.querySelector('#prebook_modal_content');

// show modal
const prebookModalShow =()=>{
	document.body.style.position = 'relative';
	modalContent.classList.add('prebook_modal_show');

	localStorage.setItem('prebook_modal_show', 'true');
	// Add data-prebook-booking attribute to the div
	// modalContent.querySelector('.prebook_modal_body > div').setAttribute('data-prebook-booking');
}

// hide modal
const prebookModalHide =()=>{
	modalContent.classList.remove('prebook_modal_show');
	localStorage.setItem('prebook_modal_show', 'false');
}

document.addEventListener('click', (event)=>{
	// if click on button
	if(event.target === modalButton){
	   prebookModalShow()
	}

	// if click outside remove modal
	if(event.target === modalContent){
		prebookModalHide()
	}
})

// exc key press hide modal
document.addEventListener('keydown', (event) => {
	if (event.key === 'Escape') {
		prebookModalHide()
	}
});

window.onload = (event) => {
  if(localStorage.getItem('prebook_modal_show') == 'true'){
	prebookModalShow()
  }
};

 </script>