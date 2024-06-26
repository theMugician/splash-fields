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
		$output    = '<fieldset class="spf-input-list">';
        $output    .= static::html_radio_inputs( $field, $meta );
		$output    .= '</fieldset>';
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
	static public function html_radio_inputs( $field, $meta ) {
        $attributes = static::get_attributes( $field, $meta );
		$attributes['class'] = 'spf-radio';
        $output = '';
        foreach( $field['options'] as $value => $label ) {
            $output .= sprintf( '<label for="%s">', esc_attr( $value ) );
            $output .= sprintf( 
				'<input value="%s" %s %s>', 
				esc_attr( $value ),
				self::render_attributes( $attributes ), 
				checked( esc_attr( $value ), $attributes['value'], false )
			);
            $output .= sprintf( '%s</label>', esc_html( $label ) );

        }
		return $output;
    }
}
