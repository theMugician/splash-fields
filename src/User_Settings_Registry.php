<?php
/**
 * User_Settings_Registry Class.
 * A registry for storing all options pages.
 * 
 * @link https://designpatternsphp.readthedocs.io/en/latest/Structural/Registry/README.html
 * 
 * @package splash-fields
 */

namespace Splash_Fields;

/**
 * Class User_Settings_Registry.
*/
class User_Settings_Registry {
	private $data = [];

	/**
	 * Create a user settings object.
	 *
	 * @param array $settings settings for the user settings.
	 * @return      \User_Settings
	 */
	public function make( array $settings ) {
		$class_name = apply_filters( 'spf_user_settings_class_name', 'Splash_Fields\User_Settings', $settings );
		$user_settings = new $class_name( $settings );
		$this->add( $user_settings );
		return $user_settings;
	}

	public function add( User_Settings $user_settings ) {
		$this->data[ $user_settings->id ] = $user_settings;
	}

	public function get( $id ) {
		return $this->data[ $id ] ?? false;
	}

	/**
	 * Get user settings under some conditions.
	 *
	 * @param array $args Custom argument to get options pages by.
	 */
	public function get_by( array $args ) : array {
		$user_settings = $this->data;
		foreach ( $user_settings as $index => $user ) {
			foreach ( $args as $key => $value ) {
				$user_key = 'object_type' === $key ? $user->get_object_type() : $user->$key;
				if ( $user_key !== $value ) {
					unset( $user_settings[ $index ] );
					continue 2; // Skip the meta box loop.
				}
			}
		}

		return $users;
	}

	public function all() {
		return $this->data;
	}
}
