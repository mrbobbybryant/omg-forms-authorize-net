<?php
namespace OMGForms\Authorize\Settings;

function setup() {
	add_action( 'admin_init', __NAMESPACE__ . '\display_authorize_net_setting_fields', 10, 2 );
}

function display_authorize_net_setting_fields() {
	add_settings_section( 'authorize-section', esc_html__( 'Authorize.net Settings' ), null, 'form_settings' );

	add_settings_field(
		'authorize_net_api_key',
		'API LOGIN ID',
		__NAMESPACE__ . '\display_authorize_net_key_element',
		'form_settings',
		'authorize-section'
	);

	add_settings_field(
		'authorize_net_api_token',
		'TRANSACTION KEY',
		__NAMESPACE__ . '\display_authorize_net_token_element',
		'form_settings',
		'authorize-section'
	);

	add_settings_field(
		'authorize_net_sandbox_mode',
		'Turn on Sandbox Mode?',
		__NAMESPACE__ . '\display_authorize_net_sandbox_element',
		'form_settings',
		'authorize-section'
	);

	register_setting( 'omg-forms-section', 'authorize_net_api_key' );
	register_setting( 'omg-forms-section', 'authorize_net_api_token' );
	register_setting( 'omg-forms-section', 'authorize_net_sandbox_mode' );

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

function display_authorize_net_sandbox_element() {
    $option = get_option( 'authorize_net_sandbox_mode' );
	?>
    <label for="authorize_net_sandbox_mode">Yes</label>
    <input
        id="authorize_net_sandbox_mode"
        type="checkbox"
        size="55"
        name="authorize_net_sandbox_mode"
        value="1"
        <?php checked( $option, 1, true ); ?>
    />
	<?php
}
