<?php
/**
 * Plugin public functions.
 */

if ( ! function_exists( 'spf_get_storage' ) ) {
	/**
	 * Get storage instance.
	 *
	 * @param   string  $object_type Object type. 
     *                  post|term|user|comment
	 * @return  object  Storage Object \Splash_Fields\Storages\Object_Type  
	 */
	function spf_get_storage( $object_type ) {
        static $data = [];
        $data_type = 'storage';
		$storage_registry_class = 'Splash_Fields\Storage_Registry';

        // Storage class based on object_type 
		$storage_class = '\\Splash_Fields\\Storages\\' . \Splash_Fields\Helpers\String_Helper::title_case( $object_type );

		if ( ! isset( $data[ $data_type ] ) ) {
			$data[ $data_type ] = new $storage_registry_class();
		}

		$storage = $data[ $data_type ]->get( $storage_class );

        return apply_filters( 'spf_get_storage', $storage, $object_type );
	}
}