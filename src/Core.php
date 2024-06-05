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
        add_action( 'init', [ $this, 'register_users' ], 20 );
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
        /*
            @see meta-box-registry.php
        */
		// $registry = rwmb_get_registry( 'meta_box' );
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

	public function register_users() {
		$configs  = apply_filters( 'spf_users', [] );
        /*
            @see meta-box-registry.php
        */
		// $registry = rwmb_get_registry( 'meta_box' );
        static $data = [];

		if ( ! isset( $data['user'] ) ) {
			$data['user'] = new User_Registry();
		}

        $registry = $data['user'];

		foreach ( $configs as $config ) {
			if ( ! is_array( $config ) || empty( $config ) ) {
				continue;
			}
			$user = $registry->make( $config );
			$user->register_fields();
		}
	}
}