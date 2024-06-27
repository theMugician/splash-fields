<?php
/**
 * Checkbox_List Class.
 *
 * @package splash-fields
 */

namespace Splash_Fields\Fields;

/**
 * Class Checkbox_List.
 * 
 */
class Checkbox_List extends Input {

	/**
	 * Get raw meta value.
	 *
	 * @param int   $object_id Object ID.
	 * @param array $field     Field parameters.
	 * @param array $args      Arguments of {@see rwmb_meta()} helper.
	 *
	 * @return mixed
	 */
	public static function raw_meta( $object_id, $field, $args = [] ) {
		if ( empty( $field['id'] ) ) {
			return '';
		}
		/**
		 *	For now there is only one type of Storage Class 
		 */
		/*
			} elseif ( isset( $args['object_type'] ) ) {
				$storage = rwmb_get_storage( $args['object_type'] );
			} else {
				$storage = rwmb_get_storage( 'post' );
			}
		*/

		$args['single'] = true;

		if ( isset( $field['storage'] ) ) {
			$storage = $field['storage'];
		} else {
			// Get instance of Storage Class
			$storage_type = 'storage';
			$storage_class = 'Splash_Fields\Storage_Registry';
			if ( ! isset( $data[ $storage_type ] ) ) {
				$data[ $storage_type ] = new $storage_class();
			}
			$storage = $data[ $storage_type ]->get( 'Splash_Fields\Storage');
		}

		$value = $storage->get( $object_id, $field['id'], $args );
		return $value;
	}

	public static function sanitize( $value ) {

		$return_value = '';
		if ( is_array( $value ) ) {
			$return_value = array();
			foreach ( $value as $item_key => $item_value ) {
				$return_value[] = sanitize_text_field( $item_value );
			}
		} else {
			$return_value = sanitize_text_field( $value );
		}
		return $return_value;

	}

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
	 * Get the attributes for a field.
	 *
	 * @param array $field Field parameters.
	 * @param mixed $value Meta value.
	 *
	 * @return array
	 */
	public static function get_attributes( $field, $value = null ) {
		$attributes = parent::get_attributes( $field, $value );
        if ( $value === null ) {
            $value = '';
        }
		// $attribute_name = $field['id'] . '[]';
		// $attributes = wp_parse_args( $attributes, [
		// 	'name' => $attribute_name,
		// ] );
		$attributes['name'] = $field['id'] . '[]';
		return $attributes;
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
		$output    = '<fieldset class="spf-input-list">';
        $output    .= static::html_checkbox_inputs( $field, $meta );
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
	static public function html_checkbox_inputs( $field, $meta ) {
		if ( is_string( $meta ) && is_json( $meta ) ) {
			$meta = json_decode( $meta, true );
		}
		$meta = is_array( $meta ) ? $meta : array();
        $output = '';
		foreach ( $field['options'] as $option_value => $option_label ) {
			$checked = in_array( $option_value, $meta, true ) ? ' checked' : '';
			$output   .= sprintf(
				'<label><input type="checkbox" name="%s[]" value="%s"%s> %s</label>',
				esc_attr( $field['field_name'] ),
				esc_attr( $option_value ),
				$checked,
				esc_html( $option_label )
			);
		}
        // foreach( $field['options'] as $value => $label ) {
		// 	$attributes = static::get_attributes( $field, $value );
		// 	$attributes['type'] = 'checkbox';
        //     $output .= sprintf( '<label for="%s">', $value );
        //     $output .= sprintf(
		// 		'<input %s %s>',
		// 		self::render_attributes( $attributes ), 
		// 		checked( ! empty( $meta_exists[$value] ), 1, false )
		// 	);
        //     $output .= sprintf( '%s</label>', $label );

        // }
		return $output;
    }
}
