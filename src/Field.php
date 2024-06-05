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
	 * Save meta value.
	 *
	 * @param mixed $new     The submitted meta value.
	 * @param mixed $old     The existing meta value.
	 * @param int   $post_id The post ID.
	 * @param array $field   The field parameters.
	 */
	public static function save( $new, $old, $post_id, $field ) {
		if ( empty( $field['id'] ) || ! $field['save_field'] ) {
			return;
		}
		$name    = $field['id'];
		$storage = $field['storage'];

		// Remove post meta if it's empty.
		if ( ! RWMB_Helpers_Value::is_valid_for_field( $new ) ) {
			$storage->delete( $post_id, $name );
			return;
		}

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
			'label_description' => '',
			'multiple'          => false,
			'std'               => '',
			'desc'              => '',
			'format'            => '',
			'before'            => '',
			'after'             => '',
			'field_name'        => $field['id'] ?? '',
			'placeholder'       => '',
			'save_field'        => true,

			'clone'             => false,
			'min_clone'         => 0,
			'max_clone'         => 0,
			'sort_clone'        => false,
			'add_button'        => __( '+ Add more', 'meta-box' ),
			'clone_default'     => false,
			'clone_as_multiple' => false,

			'class'             => '',
			'disabled'          => false,
			'required'          => false,
			'autofocus'         => false,
			'attributes'        => [],

			'sanitize_callback' => null,
		] );

		// Store the original ID to run correct filters for the clonable field.
		if ( $field['clone'] ) {
			$field['_original_id'] = $field['id'];
		}

		if ( $field['clone_default'] ) {
			$field['attributes'] = wp_parse_args( $field['attributes'], [
				'data-default'       => $field['std'],
				'data-clone-default' => 'true',
			] );
		}

		if ( 1 === $field['max_clone'] ) {
			$field['clone'] = false;
		}

		return $field;
	}
}