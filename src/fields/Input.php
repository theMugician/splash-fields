<?php
/**
 * Text Class.
 *
 * @package splash-fields
 */

namespace Splash_Fields\Fields;
use Splash_Fields\Field;

/**
 * Class Text.
 * 
 */
class Input extends Field {
	/**
	 * Normalize parameters for field.
	 *
	 * @param array $field Field parameters.
	 *
	 * @return array
	 */
	public static function normalize( $field ) {
		$field = parent::normalize( $field );
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

		$attributes = wp_parse_args( $attributes, [
			'value'        => $value,
			'placeholder'  => $field['placeholder'],
			'type'         => $field['type'],
		] );

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
	static public function html( $meta, $field ) {
        $output      = static::html_label( $field );
        $output    	.= static::html_input( $field, $meta );
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
        $field_title = $field['name'];
        $field_id = $field['id'];
		$output  	 =  '<div class="spf-field__label">';
		$output  	.=  sprintf( '<label for="%s">%s</label>', $field_id, $field_title );
		$output 	.=  '</div>';
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
        $attributes = static::get_attributes( $field, $meta );
		$output     = '<div class="spf-field__input">';
        $output    .= sprintf( '<input %s>', self::render_attributes( $attributes ) );
		if ( $field['description'] && strlen( $field['description'] ) > 0 ) {
			$output .= sprintf( '<p class="spf-field__description">%s</p>', esc_html( $field['description'] ) );
		}
		$output    .= '</div>';
		return $output;
    }

	/**
	 * Process the submitted value before saving into the database.
	 *
	 * @param mixed $value     The submitted value.
	 * @param int   $object_id The object ID.
	 * @param array $field     The field settings.
	 */
	public static function process_value( $value, $object_id, array $field ) {
		return sanitize_text_field( $value );
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
}