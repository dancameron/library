<?php
/*
 * Plugin Name: Commissions - Withholding Tax Example
 * Description: Withhold 30% of each commission amount when the customer address matches the store country for withholding tax purposes.
 * Author: David Sherlock
 * Author URI: https://sellcomet.com/
 * Version: 1.0.0
 */


function ds_edd_adjust_commission_rate_for_withholding_tax( $recipient, $commission_amount, $rate, $download_id, $commission_id, $payment_id ) {

	$payment      = new EDD_Payment( $payment_id );
	$commission   = new EDD_Commission( $commission_id );
	$address      = $payment->address;
	$shop_country = edd_get_shop_country();

	// Set the commission meta key
	$meta_key = '_eddc_withheld_amount';

	// Sanity check - fail if purchase ID is invalid
	$payment_exists = $payment->ID;
	if ( empty( $payment_exists ) ) {
		return;
	}

	// If the payment country matches the shop country reduce the commission amount by 30%
	// Store the withheld amount as commission meta (_eddc_withheld_amount)
	if ( $address[ 'country' ] === $shop_country ) {
		$withheld_amount        = round( (float) $commission->amount * 0.30, 2 );
		$commission->amount     = (float) $commission->amount - round( $commission_amount * 0.30, 2 );
		$commission->update_meta( $meta_key, $withheld_amount );
		$commission->save();
	}

	// Add a note to the payment record recording the tax amount withheld
	$note = sprintf(
		__( 'Tax of %s withheld for %s &ndash; <a href="%s">View</a>', 'eddc' ),
		edd_currency_filter( edd_format_amount( $withheld_amount ) ),
		get_userdata( $recipient )->display_name,
		admin_url( 'edit.php?post_type=download&page=edd-commissions&payment=' . $payment_id )
	);

	$payment->add_note( $note );

}
add_action( 'eddc_insert_commission', 'ds_edd_adjust_commission_rate_for_withholding_tax', 10, 6 );
