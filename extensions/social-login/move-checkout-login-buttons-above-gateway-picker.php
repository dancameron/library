<?php
/*
 * Plugin Name: Easy Digital Downloads - Move Social Login Checkout Buttons
 * Description: By default, the login buttons display below the payment icons for the gateway picker. This plugin moves them above the those icons.
 * Author: EDD Team
 * Version: 1.0
 */

remove_action( 'edd_checkout_form_top', 'edd_show_payment_icons' );
add_action( 'edd_checkout_form_top', 'edd_show_payment_icons', 999 );