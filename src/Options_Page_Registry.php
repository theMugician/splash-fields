<?php
/**
 * Options_Page_Registry Class.
 * A registry for storing all options pages.
 * 
 * @link https://designpatternsphp.readthedocs.io/en/latest/Structural/Registry/README.html
 * 
 * @package splash-fields
 */

namespace Splash_Fields;

/**
 * Class Options_Page_Registry.
*/
class Options_Page_Registry {
    /**
     * The single instance of the class.
     *
     * @var Options_Page_Registry
     */
    protected static $instance = null;

    /**
     * List of registered options pages.
     *
     * @var array
     */
    private $data = [];

	/**
     * Ensure only one instance of the class is loaded or can be loaded.
     *
     * @return Options_Page_Registry - The single instance of the class.
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

	/**
	 * Create a meta box object.
	 *
	 * @param array $settings Meta box settings.
	 * @return      \Options_Page
	 */
	public function make( array $settings ) {
		/*
		$class_name = apply_filters( 'spf_options_page_class_name', 'Splash_Fields\Options_Page', $settings );
		$options_page = new $class_name( $settings );
		$this->add( $options_page );
		return $options_page;
		*/
		if ( ! isset( $this->data[ $settings['id'] ] ) ) {
			$class_name   = apply_filters( 'spf_options_page_class_name', 'Splash_Fields\Options_Page', $settings );
			$options_page = new $class_name( $settings );
			$this->add( $options_page );
			return $options_page;
		}

		return $this->data[ $settings['id'] ];
	}

	public function add( Options_Page $options_page ) {
		$this->data[ $options_page->id ] = $options_page;
	}

	public function get( $id ) {
		return $this->data[ $id ] ?? false;
	}

	/**
	 * Get options pages under some conditions.
	 *
	 * @param array $args Custom argument to get options pages by.
	 */
	public function get_by( array $args ) : array {
		$options_pages = $this->data;
		foreach ( $options_pages as $index => $options_page ) {
			foreach ( $args as $key => $value ) {
				$options_page_key = 'object_type' === $key ? $options_page->get_object_type() : $options_page->$key;
				if ( $options_page_key !== $value ) {
					unset( $options_pages[ $index ] );
					continue 2; // Skip the meta box loop.
				}
			}
		}

		return $options_pages;
	}

	public function all() {
		return $this->data;
	}
}
