<?php
/**
 * Number Class.
 *
 * @package splash-fields
 */

namespace Splash_Fields\Fields;

/**
 * Class Number.
 * 
 */
class Number extends Input {
	/**
	 * Normalize parameters for field.
	 *
	 * @param array $field Field parameters.
	 *
	 * @return array
	 */
	public static function normalize( $field ) {
		$field = parent::normalize( $field );
		$field = wp_parse_args( $field, [
			'step' => 1,
			'min'  => 0,
			'max'  => false,
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
			'step' => $field['step'],
			'max'  => $field['max'],
			'min'  => $field['min'],
		] );
		return $attributes;
	}

	/**
	 * Process and sanitize the submitted value before saving into the database.
	 *
	 * @param mixed $value     The submitted value.
	 * @param int   $object_id The object ID.
	 * @param array $field     The field settings.
	 */
	public static function sanitize( $value ) {
		return is_numeric( $value ) ? $value : '';
	}
}
