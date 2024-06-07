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

if ( ! function_exists( 'spf_get_field' ) ) {
	/**
	 * Get field value.
	 *
	 * @param string $field_key The field key.
	 * @param string $context   The context (post, user, term, option). Optional if $object is provided.
	 * @param mixed  $object    The object (post ID, user ID, term ID, option name, or global post object).
	 *
	 * @return mixed The field value.
	 */
	function spf_get_field( $field_key, $context = 'post', $object = null ) {
		// Determine the context if not provided
		if ( is_null( $object ) && isset( $GLOBALS['post'] ) ) {
			$object = $GLOBALS['post'];
		}
	
		if ( is_null( $context ) || $context === 'post' ) {
			if ( is_numeric( $object ) && get_post( $object ) ) {
				$context = 'post';
			} elseif ( is_object( $object ) && isset( $object->ID ) && get_post( $object->ID ) ) {
				$context = 'post';
				$object = $object->ID;
			} elseif ( is_numeric( $object ) && get_userdata( $object ) ) {
				$context = 'user';
			} elseif ( is_numeric( $object ) && get_term( $object ) ) {
				$context = 'term';
			} elseif ( is_string( $object ) && !is_null( get_option( $object ) ) ) {
				$context = 'option';
			} else {
				// Default to 'post' context if unable to determine context
				$context = 'post';
			}
		}
	
		// Get the field value based on context
		switch ( $context ) {
			case 'post':
				return get_post_meta( $object, $field_key, true );
			
			case 'user':
				return get_user_meta( $object, $field_key, true );
			
			case 'term':
				return get_term_meta( $object, $field_key, true );
			
			case 'option':
				return get_option( $field_key );
			
			default:
				return false;
		}
	}
	
}
