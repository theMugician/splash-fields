<?php
/**
 * Text Class.
 *
 * @package splash-fields
 */

namespace Splash_Fields\Fields;

/**
 * Class Text.
 * 
 */
class Radio extends Input {

	/**
	 * Get field HTML.
	 *
	 * @param   array    $field  Field name.
	 *
	 * @return  string
	 */
	static public function html_title( $field ) {
        $field_title = $field['name'];
        $field_id = $field['id'];
		$output  =  '<div class="spf-field__title">';
		$output  =  sprintf( '<p>%s</p>', $field_title );
		$output .=  '</div>';
		return $output;
	}

	/**
	 * Get field HTML.
	 *
	 * @param mixed $meta   Meta value.
	 * @param array $field  Field parameters.
	 *
	 * @return string
	 */
	static public function html_field_set( $field, $meta ) {
		$output     = '<div class="spf-field__set">';
        $output    .= static::html_radio_input( $field, $meta );
		$output    .= '</div>';
		return $output;
    }

	/**
	 * Get field HTML.
	 *
	 * @param mixed $meta   Meta value.
	 * @param array $field  Field parameters.
	 *
	 * @return string
	 */
	static public function html_radio_input( $field, $meta ) {
        $attributes = static::get_attributes( $field, $meta );
        $output = '';
        foreach( $field['options'] as $value => $label ) {
            $output    .= sprintf( '<label for="%s">%s</label>', $value, $label );
            $output    .= sprintf( '<input %s>', self::render_attributes( $attributes ) );
        }
		return $output;
    }
}