<?php
/**
 * Meta_Box_Registry Class.
 * A registry for storing all meta boxes.
 * 
 * @link https://designpatternsphp.readthedocs.io/en/latest/Structural/Registry/README.html
 * 
 * @package splash-fields
 */

namespace Splash_Fields;

/**
 * Class Meta_Box_Registry.
*/
class Meta_Box_Registry {
	private $data = [];

	/**
	 * Create a meta box object.
	 *
	 * @param array $settings Meta box settings.
	 * @return      \Meta_Box
	 */
	public function make( array $settings ) {
		$class_name = apply_filters( 'spf_meta_box_class_name', 'Splash_Fields\Meta_Box', $settings );
		$meta_box = new $class_name( $settings );
		$this->add( $meta_box );
		return $meta_box;
	}

	public function add( Meta_Box $meta_box ) {
		$this->data[ $meta_box->id ] = $meta_box;
	}

	public function get( $id ) {
		return $this->data[ $id ] ?? false;
	}

	/**
	 * Get meta boxes under some conditions.
	 *
	 * @param array $args Custom argument to get meta boxes by.
	 */
	public function get_by( array $args ) : array {
		$meta_boxes = $this->data;
		foreach ( $meta_boxes as $index => $meta_box ) {
			foreach ( $args as $key => $value ) {
				$meta_box_key = 'object_type' === $key ? $meta_box->get_object_type() : $meta_box->$key;
				if ( $meta_box_key !== $value ) {
					unset( $meta_boxes[ $index ] );
					continue 2; // Skip the meta box loop.
				}
			}
		}

		return $meta_boxes;
	}

	public function all() {
		return $this->data;
	}
}
