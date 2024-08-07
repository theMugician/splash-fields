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


/**
 * File Class.
 * Deprecated.
 * @package splash-fields
 */

namespace Splash_Fields\Fields;

/**
 * Class File.
 */
class File extends Input {
    public static function admin_enqueue_scripts() {
        // Register script for Media Field
        wp_register_script(
            'spf-file-js',
            SPF_ASSETS_URL . '/js/file.js',
            array('jquery'),
            false,
            true
        );

        wp_localize_script(
            'spf-file-js',
            'spfFileField',
            array(
                'ajaxurl' => admin_url('admin-ajax.php')
            )
        );

        wp_enqueue_script('spf-file-js');
    }

    /**
     * Show field HTML
     *
     * @param array $field Field parameters.
     * @param int $post_id Post ID.
     *
     * @return mixed
     */
    public static function show(array $field, $post_id = 0) {
        $meta = static::raw_meta($post_id, $field);
        $html = sprintf('<div class="spf-field spf-field-%s">', esc_attr($field['type']));
        $html .= static::html($field, $meta); // Correct order of parameters
        $html .= '</div>';
        echo $html;
    }

    public static function add_actions() {
        add_action('post_edit_form_tag', [__CLASS__, 'post_edit_form_tag']);
        add_action('wp_ajax_spf_file_error', [__CLASS__, 'ajax_error']);
    }

    public static function post_edit_form_tag() {
        echo ' enctype="multipart/form-data"';
    }

    public static function ajax_error() {
        wp_send_json_error($_POST['message']);
    }

    static public function file_add_id($field_id) {
        return "file-add-{$field_id}";
    }
    
    /**
     * Generates the HTML for displaying an uploaded file with options to edit or delete.
     *
     * This function takes a file's metadata, decodes it if necessary, and returns the HTML
     * markup for displaying the file's icon, title, name, and action links (edit and delete).
     *
     * @param mixed $meta The file metadata. This can be either a JSON-encoded string or an array.
     *
     * @return string The HTML markup for the file display.
     */
    static public function html_file( $meta ) {
        // Decode the JSON string if $meta is a string.
        if ( is_string( $meta ) ) {
            $meta = json_decode( $meta, true );
        }
        $file = $meta;

        // Localization strings for the delete and edit actions.
        $i18n_delete = apply_filters('spf_file_delete_string', _x('Delete', 'file upload', 'splash-fields'));
        $i18n_edit = apply_filters('spf_file_edit_string', _x('Edit', 'file upload', 'splash-fields'));

        // Return the formatted HTML string for the file display.
        return sprintf(
            '<div class="spf-file">
                <div class="spf-file__icon">%s</div>
                <div class="spf-file__info">
                    <a href="%s" target="_blank" class="spf-file__title">%s</a>
                    <div class="spf-file__name">%s</div>
                    <div class="spf-file__actions">
                        %s
                        <a href="#" class="spf-file__delete" data-attachment_id="%s">%s</a>
                    </div>
                </div>
            </div>',
            wp_get_attachment_image( $file['id'], [48, 64], true ),
            esc_url( $file['url'] ),
            esc_html( $file['name'] ),
            esc_html( $file['name'] ),
            self::edit_link( $file['id'], $i18n_edit ),
            esc_attr( $file['id'] ),
            esc_html( $i18n_delete )
        );
    }

    static private function edit_link($id, $text) {
        $edit_link = get_edit_post_link($id);
        return $edit_link ? sprintf('<a href="%s" class="spf-file__edit" target="_blank">%s</a>', $edit_link, $text) : '';
    }

    /**
     * HTML and functionality to add/update/delete file
     *
     * @param array $field Field parameters.
     * @param array $meta Meta value.
     *
     * @return string
     */
    public static function html_input($field, $meta) {
        $file_add_name = self::file_add_id($field['id']);
        $file_add_class = "spf-file__add";

        $file_add_attributes = ' type="file" id="' . $file_add_name . '" name="' . $file_add_name . '" class="' . $file_add_class . '"';

        $has_file = ! ( $meta === '' || $meta === null || $meta === false || ( is_array( $meta ) && empty( $meta ) ) );

        $delete_file_hide = $has_file ? '' : ' hide';
        $add_file_hide = $has_file ? ' hide' : '';

        $output = '<div class="spf-field__input">';
        $output .= '<div class="spf-file__file-container">';
        if ($has_file) {
            $output .= self::html_file($meta);
        }
        $output .= '</div>';
        $output .= sprintf(
            '<input %s %s/>',
            $add_file_hide,
            $file_add_attributes,
            esc_html__('Add File', 'spf')
        );
        $output .= sprintf(
            '<input id="%s" class="spf-file__file-data" name="%s" type="hidden" value="%s" />',
            esc_attr($field['id']),
            esc_attr($field['id']),
            esc_attr( json_encode ( $meta, true ) )
        );
        if (isset($field['description']) && strlen($field['description']) > 0) {
            $output .= sprintf('<p class="spf-field__description">%s</p>', esc_html($field['description']));
        }
        $output .= '</div>';
        return $output;
    }

    public static function value( $value, $object_id, array $field ) {
        // Unserialize the value to an array.
        $decoded_value = json_decode( $value, true );

        // Check if decode_value returned an array.
        if ( ! is_array( $decoded_value ) ) {
            return '';
        }

        // If a new file is uploaded, handle it.
        $file_add_id = self::file_add_id( $field['id'] );

        if ( ! empty( $_FILES[ $file_add_id ]['name'] ) ) {
            $attachment_id = media_handle_upload( $file_add_id, $object_id );
            if ( is_wp_error( $attachment_id ) ) {
                $error_message = $attachment_id->get_error_message();
                self::error_message( $error_message );
                error_log( print_r( $error_message, true ) );
                return '';
            } else {
                $decoded_value['id']    = $attachment_id;
                $decoded_value['url']   = wp_get_attachment_url( $attachment_id );
                $decoded_value['name']  = get_the_title( $attachment_id );
                $decoded_value['type']  = get_post_mime_type( $attachment_id );
            }
        }

        // Sanitize each element in the array.
        $value = $decoded_value;

        // Re-serialize the array to a string.
        return json_encode( $value );
    }

    public static function sanitize( $value ) {
        if ( $value === '' || is_null( $value ) ) {
            return '';
        }
        $value = json_decode( $value, true );
        if ( is_array( $value ) && ! empty( $value ) ) {
            $value = array_map( 'sanitize_text_field', $value );
        } else {
            $value = sanitize_text_field( $value );
        }
        return json_encode( $value );
    }

    public static function error_message($message) {
        echo "<p class='spf-field__error'>{$message}</p>";
    }

    /**
	 * Normalize parameters for field.
	 *
	 * @param array $field Field parameters.
	 * @return array
	 */
	public static function normalize( $field ) {
		$field['multiple'] = true;
		$field             = parent::normalize( $field );

		return $field;
	}
}
