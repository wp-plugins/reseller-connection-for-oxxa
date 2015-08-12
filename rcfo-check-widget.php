<?php defined('ABSPATH') or die("No script kiddies please!");

function rcfo_nh_domain_check() {

	echo '<aside id="rcfo-widget" class="widget rcfo-widget" style="font-size: 100%;">
		<h3 class="widget-title">'. __('Domain check', 'rcfo-nh').'</h3>';
		echo 'Coming soon!';
		echo '</aside>';

}
 
function rcfo_nh_domain_check_control() {

	echo "<p>You can change the settings for this widget on the plugin settings page.</p>";

}

function rcfo_nh_init_sidebar_widget() {
  wp_register_sidebar_widget('rcfo-nh', 'Domain Check Widget', 'rcfo_nh_domain_check', array("description" => "Let your visitors check the availablity of a domain name."));
  wp_register_widget_control('rcfo-nh', 'Domain Check Widget', 'rcfo_nh_domain_check_control');
}

?>