<?php
namespace OMGForms\Authorize\TransactionErrors;

use OMGForms\Helpers as CoreHelpers;

function handle_authorize_net_form_errors( $response ) {
	$error = $response->getErrorCode();
	$card_number_errors = [ 6, 8 ];
	$expiration_errors = [ 7 ];

	if ( ! in_array( $error, $card_number_errors ) && ! in_array( $error, $expiration_errors ) ) {
		return CoreHelpers\return_form_level_error( $response->getErrorText() );
	}

	if ( in_array( $error, $card_number_errors ) ) {
		return CoreHelpers\return_field_level_error( $response->getErrorText(), [ 'omg-forms-card_number' ] );
	}

	if ( in_array( $error, $expiration_errors ) ) {
		return CoreHelpers\return_field_level_error( $response->getErrorText(), [ 'omg-forms-expiration_date' ] );
	}

	return CoreHelpers\return_form_level_error( $response->getErrorText() );

}
