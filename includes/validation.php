<?php
namespace OMGForms\Authorize\Validation;

use OMGForms\Authorize\Helpers;

function valid_authorize_net_forms( $args ) {
	$required = Helpers\get_required_fields();
	if ( isset( $args[ 'form_type' ] ) && 'authorize_net' === $args[ 'form_type' ] ) {
		$errors = array_reduce( $required, function( $prev, $field ) use( $args ) {
			if ( ! field_exists( $field, $args['fields'] ) ) {
				if ( 0 === strlen( $prev ) ) {
					return $field;
				} else {
					$prev = $prev . ', ' . $field;
				}
			}
			return $prev;
		}, false );

		if ( $errors ) {
			throw new \Exception( 'Authorize.net form is missing the following required fields: ' . $errors );
		}
	}
}
add_action( 'omg_form_validation', __NAMESPACE__ . '\valid_authorize_net_forms' );

function field_exists( $field, $fields ) {
	return array_reduce( $fields, function( $prev, $next ) use ( $field ) {
		if ( $field === $next[ 'slug' ] ) {
			$prev = true;
		}

		return $prev;
	}, false );
}