<?php
namespace OMGForms\Authorize\Helpers;

use OMGForms\Helpers as CoreHelpers;

function prepare_authorize_net_form_fields( $args ) {
	return array_reduce( array_keys( $args ), function( $acc, $arg ) use( $args ) {
		$key = format_field_name( $arg );
		$valid_keys = get_valid_authorize_net_field_data();

		if ( ! in_array( $key, $valid_keys ) ) {
			return $acc;
		}

		$acc[ $key ] = $args[ $arg ];

		return $acc;
	}, [] );
}

function format_field_name( $field_key ) {
	$key = str_replace( 'omg-forms-', '', $field_key );
	return str_replace( '-', '_', $key );
}

function format_expiration_date( $data ) {
	if ( in_array( 'expiration_date', $data ) ) {
		return $data;
	}

	if ( isset( $data[ 'expiration_month' ] ) && isset( $data[ 'expiration_year' ] ) ) {
		$data[ 'expiration_date' ] = sprintf( '%s-%s', $data[ 'expiration_year' ], $data[ 'expiration_month' ] );
		return $data;
	}

	return CoreHelpers\return_form_level_error( 'Authorize.net form is missing expiration date fields.' );
}

function get_valid_authorize_net_field_data() {
	return apply_filters( 'omg-form-authorize_net-valid-fields', [
		'first_name',
		'last_name',
		'email_address',
		'address',
		'city',
		'state',
		'zip_code',
		'country',
		'card_number',
		'card_code',
		'expiration_date',
		'expiration_month',
		'expiration_year',
		'transaction_amount'
	] );
}

function get_required_fields() {
	return apply_filters( 'omg-form-authorize_net-required-fields', [
		'first_name',
		'last_name',
		'address',
		'city',
		'state',
		'zip_code',
		'card_number',
		'card_code'
	] );
}

function sanitize_credit_card( $value ) {
	$card_number = format_credit_card( $value );
	$card_number = limit_credit_card_length( $card_number );
	return sanitize_text_field( $card_number );
}

function format_credit_card( $number ) {
	if ( strpos( $number, ' ' ) ) {
		return str_replace( ' ', '', $number );
	}

	if ( strpos( $number, '-' ) ) {
		return str_replace( '-', '', $number );
	}

	return apply_filters( 'omg-forms-format-credit-card', $number );
}

function limit_credit_card_length( $number ) {
	return substr( $number, 0, 16 );
}

function register_credit_card_field( $args ) {
	return wp_parse_args( $args, [
		'slug'          =>  'card_number',
		'type'          =>  'number',
		'required'      =>  true,
		'sanitize_cb'   =>  '\OMGForms\Authorize\Helpers\sanitize_credit_card'
	] );
}

function remove_sensitive_data( $data ) {
	unset( $data['card_code'] );
	unset( $data['expiration_month'] );
	unset( $data['expiration_year'] );
	unset( $data['expiration_date'] );
	unset( $data['card_number'] );
	return $data;
}
