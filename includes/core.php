<?php
namespace OMGForms\Authorize\Core;

function setup() {
	add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\load_scripts' );
}

function load_scripts() {
	wp_enqueue_script(
		'omg-forms-authorize-js',
		get_stylesheet_directory_uri() . '/vendor/developwithwp/omg-forms-authorize-net/dist/index.bundle.js',
		array(),
		OMG_FORMS_AUTHORIZE_VERSION,
		true
	);
}