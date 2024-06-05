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
}