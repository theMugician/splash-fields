<?php
/**
 * Editor Class.
 *
 * @package splash-fields
 */

namespace Splash_Fields\Fields;

/**
 * Class Editor.
 * 
 */
class Editor extends Input {
	/**
	 * Get field HTML.
	 * @link	https://developer.wordpress.org/reference/functions/wp_editor/
	 * @link	https://developer.wordpress.org/reference/classes/_wp_editors/parse_settings/ Editor Settings
	 * 
	 * @param 	mixed $meta  Meta value.
	 * @param 	array $field Field parameters.
	 * @return 	string
	 */
	public static function html_input( $field, $meta ) {
		// Using output buffering because wp_editor() echos directly.
		ob_start();

		$attributes = self::get_attributes( $field );
		$options                  = $field['options'];
		var_dump($options);

		$options['textarea_name'] = $field['field_name'];
		if ( ! empty( $attributes['required'] ) ) {
			$options['editor_class'] .= ' spf-editor-required';
		}

		wp_editor( $meta, $attributes['id'], $options );
		// echo '<script class="rwmb-wysiwyg-id" type="text/html" data-id="', esc_attr( $attributes['id'] ), '" data-options="', esc_attr( wp_json_encode( $options ) ), '"></script>';
		// echo '<script>if ( typeof rwmb !== "undefined" ) rwmb.$document.trigger( "mb_init_editors" );</script>';

		return ob_get_clean();
	}


	/**
	 * Normalize parameters for field.
	 *
	 * @param array $field Field parameters.
	 * @return array
	 */
	public static function normalize( $field ) {
		$field = parent::normalize( $field );
		$field = wp_parse_args( $field, [
			'raw'     => false,
			'options' => [],
		] );

		$field['options'] = wp_parse_args( $field['options'], [
			'editor_class' => 'spf-editor',
			'dfw'          => true, // Use default WordPress full screen UI.
		] );

		// Keep the filter to be compatible with previous versions.
		$field['options'] = apply_filters( 'spf_editor_settings', $field['options'] );

		return $field;
	}
}
