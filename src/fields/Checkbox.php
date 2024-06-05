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
}