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
	 * Get field HTML.
	 *
	 * @param mixed $meta   Meta value.
	 * @param array $field  Field parameters.
	 *
	 * @return string
	 */
	static public function html( $field, $meta ) {

        $output      = static::html_label( $field );
		$output     .= '<div class="spf-field__input">'; // Open input container
        $output    	.= static::html_input( $field, $meta );
		if ( $field['description'] && strlen( $field['description'] ) > 0 ) {
			$output .= sprintf( '<p class="spf-field__description">%s</p>', esc_html( $field['description'] ) );
		}
		$output    .= '</div>'; // Close input container
		return $output;
	}

	static public function html_( $field, $meta ) {

        $html = sprintf(
            '<div class="spf-field spf-field-%s" data-field-id="%s">',
            esc_attr( $field['type'] ),
            esc_attr( $field['id'] )
        );

        foreach ( $field['options'] as $option_value => $option_label ) {
            $checked = in_array( $option_value, $meta, true ) ? ' checked' : '';
            $html   .= sprintf(
                '<label><input type="checkbox" name="%s[]" value="%s"%s> %s</label>',
                esc_attr( $field['id'] ),
                esc_attr( $option_value ),
                $checked,
                esc_html( $option_label )
            );
        }

        $html .= '</div>';

        echo $html; // WPCS: XSS ok.
    }

    /**
     * Save the field data.
     *
     * @param mixed $new      New value.
     * @param mixed $old      Old value.
     * @param int   $post_id  Post ID.
     * @param array $field    Field configuration.
     */
    public static function save( $new, $old, $post_id, $field ) {
        if ( empty( $field['id'] ) ) {
            return;
        }

        $name    = $field['id'];
        $storage = $field['storage'];

        if ( empty( $new ) ) {
            $storage->delete( $post_id, $name );
            return;
        }

        // Serialize the array of checkbox values before saving.
        $serialized_value = maybe_serialize( $new );
        $storage->update( $post_id, $name, $serialized_value );
    }

	
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

		$args['single'] = false;

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


	/**
	 * Process the submitted value before saving into the database.
	 *
	 * @param mixed $value     The submitted value.
	 * @param int   $object_id The object ID.
	 * @param array $field     The field settings.
	 */
	public static function process_value( $value, $object_id, array $field ) {
		$return_value = '';
		if ( is_array( $value ) ) {
			$return_value = array();
			foreach ( $value as $item_key => $item_value ) {
				$return_value[] = sanitize_text_field( $item_value );
			}
		} else {
			$return_value = sanitize_text_field( $value );
		}
		// TODO: Add Sanitize() Class

		/*
		$old_value = self::call( $field, 'raw_meta', $object_id );

		// Allow field class change the value.
		if ( $field['clone'] ) {
			$value = RWMB_Clone::value( $value, $old_value, $object_id, $field );
		} else {
			$value = self::call( $field, 'value', $value, $old_value, $object_id );
			$value = self::filter( 'sanitize', $value, $field, $old_value, $object_id );
		}
		$value = self::filter( 'value', $value, $field, $old_value, $object_id );
		*/

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
	 * @param mixed $meta   Meta value.
	 * @param array $field  Field parameters.
	 *
	 * @return string
	 */
	/*
	static public function html( $meta, $field ) {
        $output     = static::html_label( $field );
        $output    .= static::html_input( $field, $meta );
		return $output;
	}
	*/

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
		// $meta_exists = [];
		// if ( $meta && count( $meta ) > 0 ) {
		// 	foreach( $meta as $key => $value ) {
		// 		$meta_exists[$value] = 1;
		// 	}
		// }
        $output = '';
		foreach ( $field['options'] as $option_value => $option_label ) {
			$checked = in_array( $option_value, $meta, true ) ? ' checked' : '';
			$ouput   .= sprintf(
				'<label><input type="checkbox" name="%s[]" value="%s"%s> %s</label>',
				esc_attr( $field['id'] ),
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

