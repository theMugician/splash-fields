<?php
/**
 * Checkbox Class.
 *
 * @package splash-fields
 */

namespace Splash_Fields\Fields;

/**
 * Class Checkbox.
 * 
 */
class Checkbox_List extends Input {

	/**
	 * Normalize parameters for field.
	 *
	 * @param array $field Field parameters.
	 * @return array
	 */
	public static function normalize( $field ) {
		$field['multiple'] = true;
		$field             = parent::normalize( $field );

		return $field;
	}

	/**
	 * Get field HTML.
	 *
	 * @param mixed $meta   Meta value.
	 * @param array $field  Field parameters.
	 *
	 * @return string
	 */
	static public function html( $meta, $field ) {
        $output     = static::html_label( $field );
        $output    .= static::html_input( $field, $meta );
		return $output;
	}
	/**
	 * Get field HTML.
	 *
	 * @param   array    $field  Field name.
	 *
	 * @return  string
	 */
	static public function html_label( $field ) {
        $field_name = $field['name'];
        $field_id = $field['id'];
		$output      =  '<div class="spf-field__label">';
		$output     .=  sprintf( '<label>%s</label>', $field_name );
		$output     .=  '</div>';
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
	static public function html_input( $field, $meta ) {
		$output     = '<div class="spf-field__input">';
		$output    .= '<fieldset class="spf-field__input-list">';
        $output    .= static::html_checkbox_inputs( $field, $meta );
		$output    .= '</fieldset>';
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
	static public function html_checkbox_inputs( $field, $meta ) {
		var_dump($meta);
        $attributes = static::get_attributes( $field, 1 );
		$attributes['type'] = 'checkbox';
        $output = '';
        foreach( $field['options'] as $value => $label ) {
            $output .= sprintf( '<label for="%s">', $value );
            $output .= sprintf( 
				'<input %s %s>', 
				self::render_attributes( $attributes ), 
				checked( ! empty( $meta ), 1, false )
			);
            $output .= sprintf( '%s</label>', $label );

        }
		return $output;
    }
}