<?php
/**
 * Prebook Admin Template
 *
 * @package Prebook
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php prebook_title(); ?></title>
	<?php prebook_head(); ?>
</head>
<body class="<?php prebook_body_class(); ?>">
	<div id="prebook-admin-app"></div>
	<?php prebook_footer(); ?>
</body>
</html>