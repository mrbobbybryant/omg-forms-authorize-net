<?php
namespace OMGForms\Authorize\Helpers;

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

function get_valid_authorize_net_field_data() {
	return apply_filters( 'omg-form-authorize_net-valid-fields', [
		'first_name', 'last_name', 'email_address'
	] );
}