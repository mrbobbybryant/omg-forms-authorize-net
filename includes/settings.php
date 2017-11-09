<?php
namespace OMGForms\Authorize\Settings;

function setup() {
	add_action( 'omg-form-settings-hook', __NAMESPACE__ . '\register_form_settings' );
	add_action( 'admin_init', __NAMESPACE__ . '\display_authorize_net_setting_fields' );
}

function display_authorize_net_setting_fields() {
	add_settings_section( 'section', esc_html__( 'Authorize.net Settings' ), null, 'authorize_net_options' );

	add_settings_field(
		'authorize_net_api_key',
		'Authorize.net API Key',
		__NAMESPACE__ . '\display_authorize_net_key_element',
		'authorize_net_options',
		'section'
	);

	add_settings_field(
		'authorize_net_api_token',
		'Authorize.net API Token',
		__NAMESPACE__ . '\display_authorize_net_token_element',
		'authorize_net_options',
		'section'
	);

	register_setting( 'authorize_net-section', 'authorize_net_api_key' );
	register_setting( 'authorize_net-section', 'authorize_net_api_token' );

}

function display_authorize_net_key_element() {
	?>
	<input
		type="text"
		size="55"
		name="authorize_net_api_key"
		value="<?php echo get_option( 'authorize_net_api_key' ); ?>"
	/>
	<?php
}

function display_authorize_net_token_element() {
	?>
    <input
            type="text"
            size="55"
            name="authorize_net_api_token"
            value="<?php echo get_option( 'authorize_net_api_token' ); ?>"
    />
	<?php
}

function register_form_settings() {
	settings_fields( 'authorize_net-section' );
	do_settings_sections( 'authorize_net_options' );
}