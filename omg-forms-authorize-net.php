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

\AaronHolbrook\Autoload\autoload( OMG_FORMS_AUTHORIZE_DIR . '/includes' );

\OMGForms\Authorize\Settings\setup();

function authorize_net_force_rest( $args ) {
	if ( $args['form_type'] === 'authorize-net' ) {
		$args[ 'rest_api' ] = true;
	}
	return $args;
}
add_filter( 'omg_form_filter_register_args', 'authorize_net_force_rest' );