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
class Text extends Input {
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
}