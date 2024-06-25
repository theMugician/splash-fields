<?php
/**
 * Textarea Class.
 *
 * @package splash-fields
 */

namespace Splash_Fields\Fields;

/**
 * Class Textarea.
 * 
 */
class Textarea extends Input {
    /**
	 * Get field HTML.
	 *
	 * @param mixed $meta Meta value.
	 * @param array $field Field parameters.
	 *
	 * @return string
	 */
	public static function html_input( $field, $meta ) {
		$attributes = self::get_attributes( $field, $meta );
		$output = sprintf(
			'<textarea %s>%s</textarea>',
			self::render_attributes( $attributes ),
			esc_textarea( $meta )
		);
		return $output;
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
			'autocomplete' => false,
			'cols'         => false,
			'rows'         => 3,
			'maxlength'    => false,
			'minlength'    => false,
			'wrap'         => false,
			'readonly'     => false,
		] );

		return $field;
	}

	/**
	 * Get the attributes for a field.
	 *
	 * @param array $field Field parameters.
	 * @param mixed $value Meta value.
	 *
	 * @return array
	 */
	public static function get_attributes( $field, $value = null ) {
		$attributes = parent::get_attributes( $field, $value );
		$attributes = wp_parse_args( $attributes, [
			'autocomplete' => $field['autocomplete'],
			'cols'         => $field['cols'],
			'rows'         => $field['rows'],
			'maxlength'    => $field['maxlength'],
			'minlength'    => $field['minlength'],
			'wrap'         => $field['wrap'],
			'readonly'     => $field['readonly'],
			'placeholder'  => $field['placeholder'],
		] );

		return $attributes;
	}

	/**
	 * Process the submitted value before saving into the database.
	 *
	 * @param mixed $value     The submitted value.
	 * @param int   $object_id The object ID.
	 * @param array $field     The field settings.
	 */
	public static function sanitize( $value ) {
		return sanitize_textarea_field( $value );
	}
}
