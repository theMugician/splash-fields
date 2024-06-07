<?php
/**
	 * Get field HTML.
	 *
	 * @param mixed $meta   Meta value.
	 * @param array $field  Field parameters.
	 *
	 * @return string
	 */
	// static public function html( $field, $meta ) {
    //     $output      = static::html_label( $field );
	// 	$output     .= '<div class="spf-field__input">'; // Open input container
    //     $output    	.= static::html_input( $field, $meta );
	// 	if ( $field['description'] && strlen( $field['description'] ) > 0 ) {
	// 		$output .= sprintf( '<p class="spf-field__description">%s</p>', esc_html( $field['description'] ) );
	// 	}
	// 	$output    .= '</div>'; // Close input container
	// 	return $output;
	// }

    /**
     * Save the field data.
     *
     * @param mixed $new      New value.
     * @param mixed $old      Old value.
     * @param int   $post_id  Post ID.
     * @param array $field    Field configuration.
     */
	/*
    public static function save( $new, $old, $post_id, $field ) {
        if ( empty( $field['id'] ) ) {
            return;
        }

        $name    = $field['id'];
        $storage = $field['storage'];


        if ( empty( $new ) ) {
            $storage->delete( $post_id, $name );
            return;
        }

        // Serialize the array of checkbox values before saving.
        $serialized_value = maybe_serialize( $new );
		// var_dump( $serialized_value ); 
		// die();
        $storage->update( $post_id, $name, $serialized_value );
    }
	*/

    /**
     * Save the field data.
     *
     * @param mixed $new      New value.
     * @param mixed $old      Old value.
     * @param int   $post_id  Post ID.
     * @param array $field    Field configuration.
     */
	/*
    public static function save_option( $new, $old, $post_id, $field ) {
        if ( empty( $field['id'] ) ) {
            return;
        }

        $name    = $field['id'];
        $storage = $field['storage'];


        if ( empty( $new ) ) {
            $storage->delete( $post_id, $name );
            return;
        }

        // Serialize the array of checkbox values before saving.
        $serialized_value = maybe_serialize( $new );
		// var_dump( $serialized_value ); 
		// die();
        $storage->update( $post_id, $name, $serialized_value );
    }
	*/






        /**
     * Save the repeater field data.
     *
     * @param mixed $new     New value.
     * @param mixed $old     Old value.
     * @param int   $post_id Post ID.
     * @param array $field   Field configuration.
     */
    /*
    public static function save( $new, $old, $post_id, $field ) {
        if ( empty( $field['id'] ) ) {
            return;
        }

        $name    = $field['id'];
        $storage = $field['storage'];

        if ( empty( $new ) ) {
            $storage->delete( $post_id, $name );
            return;
        } else {
            if ( $field['multiple'] ) {
                $new = maybe_serialize( $new );
            }
        }

        $storage->update( $post_id, $name, $new );
    }
    */

    /*
	public static function save_option( $new, $old, $field ) {
        if ( empty( $field['id'] )) {
            return;
        }

        $name = $field['id'];

        // Remove option if $new is empty.
        if ( empty( $new ) ) {
            delete_option( $name );
            return;
        }

		// Update option if option is empty and $new has a value.
		update_option( $field['id'], $new );
	}
    */