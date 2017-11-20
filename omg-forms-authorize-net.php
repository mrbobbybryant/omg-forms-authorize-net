<?php

if ( !defined( 'OMG_FORMS_AUTHORIZE_VERSION' ) ) {
	define( 'OMG_FORMS_AUTHORIZE_VERSION', '0.1.2' );
}

if ( !defined( 'OMG_FORMS_AUTHORIZE_DIR' ) ) {
	define( 'OMG_FORMS_AUTHORIZE_DIR', dirname( __FILE__ ) );
}

if ( !defined( 'OMG_FORMS_AUTHORIZE_FILE' ) ) {
	define( 'OMG_FORMS_AUTHORIZE_FILE', __FILE__ );
}


\AaronHolbrook\Autoload\autoload( OMG_FORMS_AUTHORIZE_DIR . '/includes' );

\OMGForms\Authorize\Settings\setup();
\OMGForms\Authorize\Core\setup();

function authorize_net_force_rest( $args ) {
	if ( OMGForms\Helpers\is_form_type( 'authorize_net', $args ) ) {
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
