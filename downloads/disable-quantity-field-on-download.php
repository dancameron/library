<?php
/*
 * Plugin Name: Easy Digital Downloads - Disable Quantity Field on Download
 * Description: Disables the quantity field on the purchase button added in EDD v2.2
 * Author: Pippin Williamson
 * Version: 1.0
 */

remove_action( 'edd_purchase_link_top', 'edd_download_purchase_form_quantity_field', 10 );