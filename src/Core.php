<?php
/**
 * Core Class.
 *
 * @package splash-fields
 */

namespace Splash_Fields;

/**
 * Class Core.
 */
class Core {

	/**
	 * The single instance of the class.
	 *
	 * @var Core
	 */
	protected static $instance = null;

	/**
	 * Ensure only one instance of the class is loaded or can be loaded.
	 *
	 * @return Core - The single instance of the class.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

    public function init() {
        add_action( 'init', [ $this, 'register_meta_boxes' ], 20 );
        add_action( 'init', [ $this, 'register_options_pages' ], 20 );
        add_action( 'init', [ $this, 'register_user_settings' ], 20 );
        add_action( 'init', [ $this, 'register_taxonomy_settings' ], 20 );
        add_action( 'init', [ $this, 'register_plugin_sidebars' ], 20 );
    }

    public function register_meta_boxes() {
        $configs  = apply_filters( 'spf_meta_boxes', [] );
        /*
            @see meta-box-registry.php
        */
        // $registry = rwmb_get_registry( 'meta_box' );
        static $data = [];

        if ( ! isset( $data['meta_box'] ) ) {
            $data['meta_box'] = new Meta_Box_Registry();
        }

        $registry = $data['meta_box'];

        foreach ( $configs as $config ) {
            if ( ! is_array( $config ) || empty( $config ) ) {
                continue;
            }
            $meta_box = $registry->make( $config );
            $meta_box->register_fields();
        }
    }

    public function register_options_pages() {
        $configs = apply_filters( 'spf_options_pages', [] );
        /*
        static $data = [];
        
        if ( ! isset( $data['options_page'] ) ) {
            $data['options_page'] = new Options_Page_Registry();
        }

        $registry = $data['options_page'];
        */
        $registry = Options_Page_Registry::get_instance();

        foreach ( $configs as $config ) {
            if ( ! is_array( $config ) || empty( $config ) ) {
                continue;
            }
            $options_page = $registry->make( $config );
            $options_page->register_fields();
        }
    }

    public function register_user_settings() {
        $configs  = apply_filters( 'spf_user_settings', [] );

        static $data = [];

        if ( ! isset( $data['user_settings'] ) ) {
            $data['user_settings'] = new User_Settings_Registry();
        }

        $registry = $data['user_settings'];

        foreach ( $configs as $config ) {
            if ( ! is_array( $config ) || empty( $config ) ) {
                continue;
            }
            $user_settings = $registry->make( $config );
            $user_settings->register_fields();
        }
    }

    public function register_taxonomy_settings() {
        $configs  = apply_filters( 'spf_taxonomy_settings', [] );

        static $data = [];

        if ( ! isset( $data['taxonomy_settings'] ) ) {
            $data['taxonomy_settings'] = new Taxonomy_Settings_Registry();
        }

        $registry = $data['taxonomy_settings'];

        foreach ( $configs as $config ) {
            if ( ! is_array( $config ) || empty( $config ) ) {
                continue;
            }
            $taxonomy_settings = $registry->make( $config );
            $taxonomy_settings->register_fields();
        }
    }

    public function register_plugin_sidebars() {
        $configs  = apply_filters( 'spf_plugin_sidebars', [] );

        static $data = [];

        if ( ! isset( $data['plugin_sidebar'] ) ) {
            $data['plugin_sidebar'] = new Plugin_Sidebar_Registry();
        }

        $registry = $data['plugin_sidebar'];

        foreach ( $configs as $config ) {
            if ( ! is_array( $config ) || empty( $config ) ) {
                continue;
            }
            $plugin_sidebar = $registry->make( $config );
            $plugin_sidebar->register_fields();
            $plugin_sidebar->register_meta_fields();
        }
    }
}
