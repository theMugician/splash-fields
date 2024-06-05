<?php
/**
 * Select Class.
 *
 * @package splash-fields
 */

namespace Splash_Fields\Fields;

/**
 * Class Select.
 * 
 */
class Select extends Input {

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
        $output    .= static::html_select_options( $field, $meta );
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
	static public function html_select_options( $field, $meta ) {
        $attributes = static::get_attributes( $field, $meta );

        $output = sprintf(
            '<select %s>',
            self::render_attributes( $attributes ), 
        );
        if ( isset( $field['placeholder'] ) && $field['placeholder'] !== '' ) {
            $output .= sprintf( '<option>%s</option>', esc_html( $field['placeholder'] ) );
        }
        foreach( $field['options'] as $value => $label ) {
            $selected = '';
            if ( $value === $meta ) {
                $selected = ' selected';
            }
            $output .= sprintf( 
				'<option%s value="%s">%s</option>', 
				esc_attr( $selected ),
				esc_attr( $value ),
				esc_html( $label )
			);
        }
        $output .= '</select>';
		return $output;
    }
}
