<?php
/**
 * Taxonomy_Settings_Registry Class.
 * A registry for storing all options pages.
 * 
 * @link https://designpatternsphp.readthedocs.io/en/latest/Structural/Registry/README.html
 * 
 * @package splash-fields
 */

namespace Splash_Fields;

/**
 * Class Taxonomy_Settings_Registry.
*/
class Taxonomy_Settings_Registry {
	private $data = [];

	/**
	 * Create a taxonomy settings object.
	 *
	 * @param array $settings settings for the taxonomy settings.
	 * @return      \Taxonomy_Settings
	 */
	public function make( array $settings ) {
		$class_name = apply_filters( 'spf_taxonomy_settings_class_name', 'Splash_Fields\Taxonomy_Settings', $settings );
		$taxonomy_settings = new $class_name( $settings );
		$this->add( $taxonomy_settings );
		return $taxonomy_settings;
	}

	public function add( Taxonomy_Settings $taxonomy_settings ) {
		$this->data[ $taxonomy_settings->id ] = $taxonomy_settings;
	}

	public function get( $id ) {
		return $this->data[ $id ] ?? false;
	}

	/**
	 * Get taxonomy settings under some conditions.
	 *
	 * @param array $args Custom argument to get options pages by.
	 */
	public function get_by( array $args ) : array {
		$taxonomy_settings = $this->data;
		foreach ( $taxonomy_settings as $index => $taxonomy_settings_item ) {
			foreach ( $args as $key => $value ) {
				$taxonomy_key = 'object_type' === $key ? $taxonomy_settings_item->get_object_type() : $taxonomy_settings_item->$key;
				if ( $taxonomy_key !== $value ) {
					unset( $taxonomy_settings[$index] );
					continue 2; // Skip the taxonomy settings loop.
				}
			}
		}

		return $users;
	}

	public function all() {
		return $this->data;
	}
}
