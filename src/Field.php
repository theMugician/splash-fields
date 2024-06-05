<?php
/**
 * Field Class.
 *
 * @package splash-fields
 */

namespace Splash_Fields;

/**
 * Class Field.
 * 
 */
class Field {
	/**
	 * Show field HTML
	 *
	 * @param array $field   Field parameters.
	 * @param int   $post_id Post ID.
	 *
	 * @return mixed
	 */
	public static function show( array $field, $post_id = 0 ) {
		$meta = static::raw_meta( $post_id, $field );
		$html = static::html( $meta, $field );
		echo $html;
	}

	/**
	 * Get field HTML.
	 *
	 * @param mixed $meta  Meta value.
	 * @param array $field Field parameters.
	 *
	 * @return string
	 */
	public static function html( $meta, $field ) {
		return '';
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
	 * Get the attributes for a field.
	 *
	 * @param array $field Field parameters.
	 * @param mixed $value Meta value.
	 *
	 * @return array
	 */
	public static function get_attributes( $field, $value = null ) {
		$attributes = wp_parse_args( $field['attributes'], [
			'id'        => $field['id'],
			'class'     => '',
			'name'      => $field['field_name'],
		] );

		$attributes['class'] = trim( implode( ' ', array_merge( [ "spf-field-{$field['type']}" ], (array) $attributes['class'] ) ) );

		$id = $attributes['id'] ?: $field['id'];

		return $attributes;
	}

	public static function render_attributes( array $attributes ) : string {
		$output = '';
		/**
		 * Check if a value is valid for attribute.
		 *
		 * @param mixed $value Input value.
		 * @return bool
		 */
		$attributes = array_filter( $attributes, function ( $attribute_value ) {
			return '' !== $attribute_value && false !== $attribute_value;
		} );

		foreach ( $attributes as $key => $value ) {
			if ( is_array( $value ) ) {
				$value = wp_json_encode( $value );
			}

			$output .= sprintf( ' %s="%s"', $key, esc_attr( $value ) );
		}

		return $output;
	}

	/**
	 * Save meta value.
	 *
	 * @param mixed $new     The submitted meta value.
	 * @param mixed $old     The existing meta value.
	 * @param int   $post_id The post ID.
	 * @param array $field   The field parameters.
	 */
	public static function save( $new, $old, $post_id, $field ) {

		// Old - Might be useful for later
		// if ( empty( $field['id'] ) || ! $field['save_field'] ) {
		if ( empty( $field['id'] ) ) {
			return;
		}

		$name    = $field['id'];
		$storage = $field['storage'];

		// Remove post meta if it's empty.
		// if ( ! RWMB_Helpers_Value::is_valid_for_field( $new ) ) {
		// 	$storage->delete( $post_id, $name );
		// 	return;
		// }

		// Default: just update post meta.
		$storage->update( $post_id, $name, $new );
	}

	/**
	 * Normalize parameters for field.
	 *
	 * @param array|string $field Field settings.
	 * @return array
	 */
	public static function normalize( $field ) {
		// Quick define text fields with "name" attribute only.

		if ( is_string( $field ) ) {
			$field = [
				'name' => $field,
				'id'   => sanitize_key( $field ),
			];
		}
		$field = wp_parse_args( $field, [
			'id'                => '',
			'name'              => '',
			'type'              => 'text',
			'description' 		=> '',
			'placeholder' 		=> '',
			'field_name' 		=> $field['id'],
			'attributes'        => [],
		] );

		return $field;
	}

	/**
	 * Call a method of another extended field. 
	 * Usually based on field type.
	 * Example: \Splash_Fields\Fields\{Type}
	 */
	public static function call() {
		$args = func_get_args();

		$check = reset( $args );

		// Params: method name, field, other params.
		if ( is_string( $check ) ) {
			$method = array_shift( $args );
			$field  = reset( $args ); // Keep field as 1st param.
		} else {
			// Params: field, method name, other params.
			$field  = array_shift( $args );
			$method = array_shift( $args );

			if ( 'raw_meta' === $method ) {
				// Add field param after object id.
				array_splice( $args, 1, 0, [ $field ] );
			} else {
				$args[] = $field; // Add field as last param.
			}
		}

		$class = \Splash_Fields\Helpers\Field::get_class( $field );
		if ( method_exists( $class, $method ) ) {
			return call_user_func_array( [ $class, $method ], $args );
		}
	}
}