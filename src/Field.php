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
	public static function add_actions() {}

	public static function admin_enqueue_scripts() {}
	
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
		/**
		 * Remove this - At the moment only 3 ['checkbox-list', 'image', 'file'] field types have multiple attribute.
		 * 'file' and 'image' fields need the raw json string to add to their hidden input values.
		 * Decode the json string to an array in the field's html method.
		 */
		/*
		if ( isset( $field['multiple'] ) && $field['multiple'] ) {
			if ( is_string( $meta ) && is_json( $meta ) ) {
				$meta = json_decode( $meta, true );
			}
			$meta = is_array( $meta ) ? $meta : array();
		}
		*/

		$field['post_id'] = $post_id;

		// On Save
		$html = sprintf( '<div class="spf-field spf-field-%s">', $field['type'] );
		$html .= static::html( $field, $meta );
		$html .= '</div>';
		echo $html;
	}

	/**
	 * Show field HTML in options page
	 *
	 * @param array $field   Field parameters.
	 * @param int   $post_id Post ID.
	 *
	 * @return mixed
	 */
	public static function show_in_options_page( array $field, $option_name = '' ) {
		$meta = get_option( $option_name );
		$html = sprintf( '<div class="spf-field spf-field-%s">', $field['type'] );
		$html .= static::html( $field, $meta );
		$html .= '</div>';
		echo $html;
	}

	/**
	 * Show field HTML in options page
	 *
	 * @param array $field   Field parameters.
	 * @param mixed $meta  meta value.
	 * 
	 * @return		$meta
	 *
	 * @return mixed
	 */
	public static function get_default( $field, $meta ) {
		if ( ( $meta === '' || $meta === false ) && ! empty( $field['default'] ) ) {
			$meta = $field['default'];
		}

		return $meta;
	}

	/**
	 * Get field HTML.
	 *
	 * @param mixed $meta  Meta value.
	 * @param array $field Field parameters.
	 *
	 * @return string
	 */
	public static function html( $field, $meta ) {
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
	 * Process the submitted value before saving into the database.
	 *
	 * @param mixed $value     The submitted value.
	 * @param int   $object_id The object ID.
	 * @param array $field     The field settings.
	 */
	public static function process_value( $value, $object_id, array $field ) {

		$value = static::value( $value, $object_id, $field );

		$value = static::sanitize( $value );

		return $value;
	}

	/**
	 * Format the value before saving into the database.
	 *
	 * @param mixed $value     The submitted value.
	 * @param int   $object_id The object ID.
	 * @param array $field     The field settings.
	 */
	public static function value( $value, $object_id, array $field ) {
		return $value;
	}

	/**
	 * Sanitize the submitted value before saving into the database.
	 *
	 * @param mixed $value The submitted value.
	 *
	 * @return mixed
	 */
	public static function sanitize( $value ) {
		return sanitize_text_field( $value );
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

		$attributes['class'] = trim( implode( ' ', array_merge( [ "spf-{$field['type']}" ], (array) $attributes['class'] ) ) );

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

		// Old - Might be useful for later
		// $is_valid_for_field = '' !== $new && [] !== $new;
		/*
		if ( ! ( '' !== $new && [] !== $new ) ) {
			$storage->delete( $post_id, $name );
			return;
		} else {
            if ( $field['multiple'] ) {
                $new = maybe_serialize( $new );
            }
        }
		*/
		if ( is_array( $new ) ) {
			// Remove object meta if $new is empty.
            if ( empty( $new ) ) {
				$storage->delete( $post_id, $name );
				return;
            } else {
				$storage->update( $post_id, $name, json_encode( $new ) );
				return;
            }
        } else {
			// Remove post meta if $new is empty.
            if ( $new === '' || $new === null ) {
				$storage->delete( $post_id, $name );
				return;
            } else {
				$storage->update( $post_id, $name, $new );
				return;
            }
        }

		// Default: just update post meta.
	}

	public static function save_option( $new, $old, $field ) {
		if ( is_array( $new ) ) {
			// Remove object meta if $new is empty.
            if ( empty( $new ) ) {
				delete_option( $field['id'] );
				return;
            } else {
				update_option( $field['id'], $new );
				return;
            }
        } else {
			// Remove post meta if $new is empty.
            if ( $new === '' || $new === null ) {
				delete_option( $field['id'] );
				return;
            } else {
				update_option( $field['id'], $new );
				return;
            }
        }
		/*
		// Remove option if $new is empty.
		if ( ! ( '' !== $new && [] !== $new ) ) {
			delete_option( $field['id'] );
			return;
		}

		// Add option if option is empty and $new has a value.
		if ( empty( get_option( $field['id'] )	) ) {
			add_option( $field['id'], $new );
			return;
		}

		// Update option if option is empty and $new has a value.
		update_option( $field['id'], $new );
		*/
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
	 * Call a method of another extended Field Class. 
	 * Based on field type.
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
