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
class Text_Field extends Field {
	/**
	 * Get field HTML.
	 *
	 * @param mixed $meta   Meta value.
	 * @param array $field  Field parameters.
	 *
	 * @return string
	 */
	public static function html( $meta, $field ) {
		$attributes = self::get_attributes( $field, $meta );
		return sprintf(
			'<textarea %s>%s</textarea>',
			self::render_attributes( $attributes ),
			esc_textarea( $meta )
		);
	}
}