<?php
/**
 * \Helpers\Field Class.
 *
 * @package splash-fields
 */

namespace Splash_Fields\Helpers;

/**
 * Class \Helpers\Field.
 * 
 */
class Field {
    
    private static function get_type( $field ) : string {
		$type = $field['type'] ?? 'text';
		$map  = array_merge(
			[
				$type => $type,
			],
			[
				'file_advanced'  => 'media',
				'plupload_image' => 'image_upload',
				'url'            => 'text',
			]
		);

		return $map[ $type ];
	}

	public static function get_class( $field ) : string {
		$type  = self::get_type( $field );
		$class = '\\Splash_Fields\\Fields\\' . String_Helper::title_case( $type );
		$class = apply_filters( 'spf_field_class', $class, $type );
		return class_exists( $class ) ? $class : '\\Splash_Fields\\Fields\\Text';
	}
}
