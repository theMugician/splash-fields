<?php
/**
 * User_Registry Class.
 * A registry for storing all options pages.
 * 
 * @link https://designpatternsphp.readthedocs.io/en/latest/Structural/Registry/README.html
 * 
 * @package splash-fields
 */

namespace Splash_Fields;

/**
 * Class User_Registry.
*/
class User_Registry {
	private $data = [];

	/**
	 * Create a user object.
	 *
	 * @param array $settings user settings.
	 * @return      \User
	 */
	public function make( array $settings ) {
		$class_name = apply_filters( 'spf_user_class_name', 'Splash_Fields\User', $settings );
		$user = new $class_name( $settings );
		$this->add( $user );
		return $user;
	}

	public function add( User $user ) {
		$this->data[ $user->id ] = $user;
	}

	public function get( $id ) {
		return $this->data[ $id ] ?? false;
	}

	/**
	 * Get users under some conditions.
	 *
	 * @param array $args Custom argument to get options pages by.
	 */
	public function get_by( array $args ) : array {
		$users = $this->data;
		foreach ( $users as $index => $user ) {
			foreach ( $args as $key => $value ) {
				$user_key = 'object_type' === $key ? $user->get_object_type() : $user->$key;
				if ( $user_key !== $value ) {
					unset( $users[ $index ] );
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
