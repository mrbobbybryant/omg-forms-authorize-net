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
			trigger_error( 'Authorize.net form is missing the following required fields: ' . implode( ', ', $errors ), E_USER_ERROR );
		}

		$errors = validate_expiration_data_fields( $args['fields'] );

		if ( $errors ) {
			trigger_error( 'Authorize.net form is missing the following required fields: ' . implode( ', ', $errors ), E_USER_ERROR );
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

function validate_expiration_data_fields( $fields ) {
	if ( field_exists( 'expiration_date', $fields ) ) {
		return false;
	} else {
		$month = field_exists( 'expiration_month', $fields );
		$year = field_exists( 'expiration_year', $fields );
		$field_errors = [];

		if ( ! $month ) {
			$field_errors[] = 'expiration_month';
		}

		if ( ! $year ) {
			$field_errors[] = 'expiration_year';
		}

		return ( empty( $field_errors ) ) ? false : $field_errors;
	}
}