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
class Checkbox extends Input {
	/**
	 * Get field HTML.
	 *
	 * @param mixed $meta   Meta value.
	 * @param array $field  Field parameters.
	 *
	 * @return string
	 */
	static public function html_input( $field, $meta ) {
		var_dump( $meta );
        $attributes = self::get_attributes( $field, 1 );
		$output     = '<div class="spf-field__input">';
        $output    .= sprintf(
			'<input %s %s>',
			self::render_attributes( $attributes ),
			checked( ! empty( $meta ), 1, false )
		);
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
		return (int) ! empty( $value );
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

		// Remove post meta if $new is empty.
		// $is_valid_for_field = '' !== $new && [] !== $new;

		if ( $new !== 1 ) {
			$storage->delete( $post_id, $name );
			return;
		}

		// Save cloned fields as multiple values instead serialized array.
		if ( $field['multiple'] ) {
			$storage->delete( $post_id, $name );
			$new = (array) $new;
			foreach ( $new as $new_value ) {
				$storage->add( $post_id, $name, $new_value, false );
			}
			return;
		}

		// Default: just update post meta.
		$storage->update( $post_id, $name, $new );
	}
}