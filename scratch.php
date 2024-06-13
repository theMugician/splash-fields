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


    	/**
	 * Save meta value.
	 * Field::public static function save( $new, $old, $post_id, $field ) {
	 * @param mixed $new     The submitted meta value.
	 * @param mixed $old     The existing meta value.
	 * @param int   $post_id The post ID.
	 * @param array $field   The field parameters.
	 */
	function save( $new, $old, $post_id, $field ) {
		// Old - Might be useful for later
		// if ( empty( $field['id'] ) || ! $field['save_field'] ) {
		if ( empty( $field['id'] ) ) {
			return;
		}

		$name    = $field['id'];
		$storage = $field['storage'];

		// Old - Might be useful for later
		// $is_valid_for_field = '' !== $new && [] !== $new;
		/*
		if ( ! ( '' !== $new && [] !== $new ) ) {
			$storage->delete( $post_id, $name );
			return;
		} else {
            if ( $field['multiple'] ) {
                $new = maybe_serialize( $new );
            }
        }
		*/
		if ( is_array( $new ) ) {
			// Remove object meta if $new is empty.
            if ( empty( $new ) ) {
				$storage->delete( $post_id, $name );
				return;
            } else {
				$storage->update( $post_id, $name, maybe_serialize( $new ) );
				return;
            }
        } else {
			// Remove post meta if $new is empty.
            if ( $new === '' || $new === null ) {
				$storage->delete( $post_id, $name );
				return;
            } else {
				$storage->update( $post_id, $name, $new );
				return;
            }
        }

		// Default: just update post meta.
	}

    	/**
	 * Process the submitted value before saving into the database.
	 * public static Field::process_value
	 * @param mixed $value     The submitted value.
	 * @param int   $object_id The object ID.
	 * @param array $field     The field settings.
	 */
	function process_value( $value, $object_id, array $field ) {

		// TODO: Add Sanitize() Class

		/*
		$old_value = self::call( $field, 'raw_meta', $object_id );

		// Allow field class change the value.
		if ( $field['clone'] ) {
			$value = RWMB_Clone::value( $value, $old_value, $object_id, $field );
		} else {
			$value = self::call( $field, 'value', $value, $old_value, $object_id );
			$value = self::filter( 'sanitize', $value, $field, $old_value, $object_id );
		}
		$value = self::filter( 'value', $value, $field, $old_value, $object_id );
		*/

		return sanitize_text_field( $value );
	}