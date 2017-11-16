<?php
namespace OMGForms\Authorize\TransactionErrors;

function handle_authorize_net_form_errors( $response ) {
	$error = $response->getErrorCode();
	$card_number_errors = [ 6, 8 ];
	$expiration_errors = [ 7 ];

	if ( ! in_array( $error, $card_number_errors ) && ! in_array( $error, $expiration_errors ) ) {
		return new \WP_Error(
			'omg-form-submission-error',
			$response->getErrorText(),
			array( 'status' => 400 )
		);
	}

	if ( in_array( $error, $card_number_errors ) ) {
		return new \WP_Error(
			'omg-form-field-error',
			$response->getErrorText(),
			array( 'status' => 400, 'fields' => [ 'omg-forms-card_number' ] )
		);
	}

	if ( in_array( $error, $expiration_errors ) ) {
		return new \WP_Error(
			'omg-form-field-error',
			$response->getErrorText(),
			array( 'status' => 400, 'fields' => [ 'omg-forms-expiration_date' ] )
		);
	}

}

function get_authorize_net_error( $error_code ) {
	$request = wp_remote_request( 'https://developer.authorize.net/api/reference/dist/json/responseCodes.json' );

	if ( ! is_wp_error( $request ) ) {
		$response_body = wp_remote_retrieve_body( $request );

		$errors = json_decode( $response_body, true );

		$error = array_values( array_filter( $errors, function( $item ) use( $error_code ) {
			return $error_code === $item[ 'code' ];
		} ) );

		return ( ! empty( $error ) ) ? $error[0] : false;

	}
	return false;
}