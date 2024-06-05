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
	public function init() {
        add_action( 'init', [ $this, 'register_meta_boxes' ], 20 );
        add_action( 'init', [ $this, 'register_options_pages' ], 20 );
        add_action( 'init', [ $this, 'register_user_settings' ], 20 );
        add_action( 'init', [ $this, 'register_taxonomy_settings' ], 20 );
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
		$configs  = apply_filters( 'spf_options_pages', [] );

        static $data = [];

		if ( ! isset( $data['options_page'] ) ) {
			$data['options_page'] = new Options_Page_Registry();
		}

        $registry = $data['options_page'];

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
}