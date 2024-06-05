<?php
/**
 * String helper functions.
 */
namespace Splash_Fields\Helpers;

class String_Helper {
	public static function title_case( string $text ) : string {
		$text = str_replace( [ '-', '_' ], ' ', $text );
		$text = ucwords( $text );
		$text = str_replace( ' ', '_', $text );

		return $text;
	}
}
