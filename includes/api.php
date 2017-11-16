<?php
namespace OMGForms\Authorize\API;

use OMGForms\Authorize\Helpers;
use OMGForms\Authorize\ProcessCard;

function save_form_as_authorize_net( $result, $args, $form ) {

	if ( $form[ 'form_type' ] === 'authorize_net' ) {

		$data = Helpers\prepare_authorize_net_form_fields( $args );
		$data = Helpers\format_expiration_date( $data );

		if ( is_wp_error( $data ) ) {
			return $data;
		}

		$result = ProcessCard\process_card( $data );

	}

	return $result;
}

add_filter( 'omg_forms_save_data', __NAMESPACE__ .  '\save_form_as_authorize_net', 10, 3 );
