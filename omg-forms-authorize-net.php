<?php

if ( !defined( 'OMG_FORMS_AUTHORIZE_VERSION' ) ) {
	define( 'OMG_FORMS_AUTHORIZE_VERSION', '0.1.0' );
}

if ( !defined( 'OMG_FORMS_AUTHORIZE_DIR' ) ) {
	define( 'OMG_FORMS_AUTHORIZE_DIR', dirname( __FILE__ ) );
}

if ( !defined( 'OMG_FORMS_AUTHORIZE_FILE' ) ) {
	define( 'OMG_FORMS_AUTHORIZE_FILE', __FILE__ );
}

require_once OMG_FORMS_AUTHORIZE_DIR . '/includes/api.php';
require_once OMG_FORMS_AUTHORIZE_DIR . '/includes/validation.php';
require_once OMG_FORMS_AUTHORIZE_DIR . '/includes/helpers.php';
require_once OMG_FORMS_AUTHORIZE_DIR . '/includes/settings.php';

\OMGForms\Authorize\Settings\setup();

function authorize_net_force_rest( $args ) {
	if ( $args['form_type'] === 'authorize_net' ) {
		$args[ 'rest_api' ] = true;
	}
	return $args;
}
add_filter( 'omg_form_filter_register_args', 'authorize_net_force_rest' );

function authorize_net_filter_form_fields( $args ) {
	$args = \OMGForms\Core\register_supplementary_field(
		'authorize_net', [
		'slug' =>   'transaction_amount',
		'type'  =>  'hidden'
		],
		$args
	);

	return $args;
}
add_filter( 'omg_form_filter_register_args', __NAMESPACE__ . '\authorize_net_filter_form_fields' );