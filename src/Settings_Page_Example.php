<?php
function wpturbo_render_custom_settings_page() {
    ?>
    <h2>My Custom Plugin Settings Page</h2>
    <form action="options.php" method="post">
        <?php 
        settings_fields( 'wpturbo_api_settings_options' );
        do_settings_sections( 'api_key_settings' ); ?>
        <input name="submit" class="button button-primary" type="submit" value="Save Settings" />
    </form>
    <?php
}

function wpturbo_render_settings_section(){
	echo "<p>A quick description of this section displayed after the heading and before the fields.</p>"
}

function wpturbo_render_api_key_settings_field() {
    $options = get_option( 'wpturbo_api_settings_options' );
	$api_key = $options['api_key'];
    echo "<input id='wpturbo-api-key-settings-field' name='wpturbo_api_settings_options[api_key]' type='text' value='" . esc_attr( $api_key ) . "' />";
}

function wpturbo_validate_options( $input ) {
	$input['api_key'] = sanitize_text_field( $input['some_text_field'] );
    return $input;
}

function wpturbo_register_settings() {
    register_setting( 'wpturbo_api_settings_options', 'wpturbo_api_settings_options', 'wpturbo_validate_options' );
	
    add_settings_section( 'api_key_settings', 'API Settings', 'wpturbo_render_settings_section', 'wpturbo-custom-settings-page' );

    add_settings_field( 'wpturbo_api_key', 'Your API Key', 'wpturbo_render_api_key_settings_field', 'wpturbo-custom-settings-page', 'api_key_settings' );
}
add_action( 'admin_init', 'wpturbo_register_settings' );