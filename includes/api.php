<?php
namespace OMGForms\Authorize\API;

use OMGForms\Authorize\Helpers;
use OMGForms\Authorize\Validation;
use OMGForms\Authorize\ProcessCard;
use OMGForms\Helpers as CoreHelpers;

function save_form_as_authorize_net( $args, $form ) {

	if ( ! CoreHelpers\is_form_type( 'authorize_net', $form ) || is_wp_error( $args ) ) {
		return $args;
	}

	$data = Helpers\prepare_authorize_net_form_fields( $args );

	if ( is_wp_error( $data ) ) {
		return $data;
	}

	$data = apply_filters( 'modify_omg_forms_authorize_data', $data, $args, $form );

	$data = Helpers\format_authorize_net_form_fields( $data );

	if ( is_wp_error( $data ) ) {
		return $data;
	}

	$data = Validation\valid_card_information( $data );

	if ( is_wp_error( $data ) ) {
		return $data;
	}

	$data = ProcessCard\process_card( $data, $form[ 'address' ] );

	if ( is_wp_error( $data ) ) {
		return $data;
	}

	return Helpers\remove_sensitive_data( $args );
}

add_filter( 'omg_forms_save_data', __NAMESPACE__ .  '\save_form_as_authorize_net', 10, 2 );
