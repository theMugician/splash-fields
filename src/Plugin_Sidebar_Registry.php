<?php
/**
 * Plugin_Sidebar_Registry Class.
 * A registry for storing all plugin sidebars.
 * 
 * @link https://designpatternsphp.readthedocs.io/en/latest/Structural/Registry/README.html
 * 
 * @package splash-fields
 */

namespace Splash_Fields;

/**
 * Class Plugin_Sidebar_Registry.
 */
class Plugin_Sidebar_Registry {
    private $data = [];

    /**
     * Create a plugin sidebar object.
     *
     * @param array $settings Plugin sidebar settings.
     * @return      \Plugin_Sidebar
     */
    public function make( array $settings ) {
        $class_name = apply_filters( 'spf_plugin_sidebar_class_name', 'Splash_Fields\Plugin_Sidebar', $settings );
        $plugin_sidebar = new $class_name( $settings );
        $this->add( $plugin_sidebar );
        return $plugin_sidebar;
    }

    /**
     * Add a plugin sidebar to the registry.
     *
     * @param Plugin_Sidebar $plugin_sidebar Plugin sidebar to add.
     */
    public function add( Plugin_Sidebar $plugin_sidebar ) {
        $this->data[ $plugin_sidebar->id ] = $plugin_sidebar;
    }

    /**
     * Get a plugin sidebar by ID.
     *
     * @param string $id Plugin sidebar ID.
     * @return Plugin_Sidebar|false
     */
    public function get( $id ) {
        return $this->data[ $id ] ?? false;
    }

    /**
     * Get plugin sidebars under some conditions.
     *
     * @param array $args Custom argument to get plugin sidebars by.
     * @return array
     */
    public function get_by( array $args ) : array {
        $plugin_sidebars = $this->data;
        foreach ( $plugin_sidebars as $index => $plugin_sidebar ) {
            foreach ( $args as $key => $value ) {
                $plugin_sidebar_key = 'object_type' === $key ? $plugin_sidebar->get_object_type() : $plugin_sidebar->$key;
                if ( $plugin_sidebar_key !== $value ) {
                    unset( $plugin_sidebars[ $index ] );
                    continue 2; // Skip the plugin sidebar loop.
                }
            }
        }

        return $plugin_sidebars;
    }

    /**
     * Get all plugin sidebars.
     *
     * @return array
     */
    public function all() {
        return $this->data;
    }
}
?>
