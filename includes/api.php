<?php
namespace OMGForms\Authorize\API;

use OMGForms\Authorize\Helpers;

function save_form_as_authorize_net( $result, $args, $form ) {

	if ( $form[ 'form_type' ] === 'authorize-net' ) {
		$apiKey = get_option( 'authorize_net_api_key' );
		$apiToken = get_option( 'authorize_net_api_token' );

		if ( empty( $apiKey ) ) {
			throw new \Exception( 'You must set the API Key before you Authorize.net form will work.' );
		}

		if ( empty( $apiToken ) ) {
			throw new \Exception( 'You must set the API Token before you Authorize.net form will work.' );
		}

		$data = Helpers\prepare_authorize_net_form_fields( $args );

	}

	return true;
}

add_filter( 'omg_forms_save_data', __NAMESPACE__ .  '\save_form_as_authorize_net', 10, 3 );
