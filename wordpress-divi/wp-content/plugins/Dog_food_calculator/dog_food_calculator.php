<?php 
/* 
Plugin Name: Dog Food Calculator 
Plugin URI: 
Description: Dog Food Calculator for WordPress Version: 1.0
License: GPLv2 or later 
*/ 
add_action('admin_menu', 'my_admin_menu');

function my_admin_menu () {
  add_management_page('Dog Food Calculator', 'Dog Food Calculator', 'manage_options', __FILE__, 'dog_food_calculator_admin_page');
}

function dog_food_calculator_admin_page () {
	$content = file_get_contents('C:\wamp64\www\wordpress-divi\wp-content\plugins\Dog_food_calculator\calculator.php');
	?>
	<div style="width:100%;text-align:center;padding-top:100px;">
		<h1>Welcome to Dog Food Calculator</h1>
		<h2>Please paste the following shortcode where you want to show Food Calculator</h2>
		<h3><?php echo '[dog_food_calculator]'; ?></h3>
	</div>
	<?php
	return $content;
  //echo 'this is where we will edit the variable';
}
add_shortcode('dog_food_calculator', 'dog_food_calculator_admin_page');
?>