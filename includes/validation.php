<?php
namespace OMGForms\Authorize\Validation;

use OMGForms\Authorize\Helpers;

function valid_authorize_net_forms( $args ) {
	if ( isset( $args[ 'form_type' ] ) && 'authorize-net' === $args[ 'form_type' ] ) {

	}
}
add_action( 'omg_form_validation', __NAMESPACE__ . '\valid_authorize_net_forms' );