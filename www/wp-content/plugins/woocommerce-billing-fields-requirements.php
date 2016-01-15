<?php
/*
 * Plugin Name: WooCommerce Billing Fields Requirements
 * Plugin URI:
 * Description: Disables the Phone Number Requirement on the WooCommerce Checkout Page.
 * Version: 1.0.0
 * Author: Markus Reiter
 * Author URI: http://reitermark.us
 * License: GPL
 */


function disable_plugin_deactivation($actions, $plugin_file, $plugin_data, $context) {
  if(array_key_exists('deactivate', $actions) && in_array($plugin_file, array(basename(__FILE__)))) {
    unset($actions['deactivate']);
  }
  return $actions;
}
add_filter('plugin_action_links', 'disable_plugin_deactivation', 10, 4);


function woocommerce_billing_fields_requirements($billing_fields) {
	$billing_fields['billing_phone']['required'] = false;
	return $billing_fields;
}
add_filter('woocommerce_billing_fields', 'woocommerce_billing_fields_requirements', 10, 1);


?>
