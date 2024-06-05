<?php
/**
 * File Class.
 *
 * @package splash-fields
 */

namespace Splash_Fields\Fields;

/**
 * Class File.
 * 
 */
class File extends Input {
    public static function admin_enqueue_scripts() {
		// Register script for Media Field

		wp_register_script(
			'spf-file-js',
			SPF_ASSETS_URL . '/js/file.js',
			array( 'jquery' ),
			false,
			true 
		);

		wp_localize_script(
            'spf-file-js',
            'spfFileField',
            array(
                'ajaxurl' => admin_url( 'admin-ajax.php' )
            ) 
        );

        wp_enqueue_script( 'spf-file-js' );
	}

	/**
	 * Show field HTML
	 *
	 * @param array $field   Field parameters.
	 * @param int   $post_id Post ID.
	 *
	 * @return mixed
	 */
	public static function show( array $field, $post_id = 0 ) {
		$meta = static::raw_meta( $post_id, $field );
		// $field['upload_iframe_src'] = static::upload_iframe_src( $post_id );
		$html = sprintf( '<div class="spf-field spf-field-%s">', $field['type'] );
		$html .= static::html( $meta, $field );
		$html .= '</div>';
		echo $html;
	}


	public static function add_actions() {
		add_action( 'post_edit_form_tag', [ __CLASS__, 'post_edit_form_tag' ] );
		add_action( 'wp_ajax_spf_add_file', [ __CLASS__, 'ajax_add_file' ] );
		add_action( 'wp_ajax_spf_delete_file', [ __CLASS__, 'ajax_delete_file' ] );
	}

	public static function post_edit_form_tag() {
		echo ' enctype="multipart/form-data"';
	}

	/**
	 * Normalize parameters for field.
	 * Get the upload iframe source for media item
	 * 
	 * @link			https://developer.wordpress.org/reference/functions/get_upload_iframe_src/
	 * 
	 * @param int 		$post_id Post ID
	 * @return string	Media iframe source
	 */
    static public function upload_iframe_src( $post_id ) {
		$upload_iframe_src = add_query_arg( 'post_id', $post_id, admin_url( 'media-upload.php' ) );
		$upload_iframe_src = add_query_arg( 'type', 'image', $upload_iframe_src );

		return add_query_arg( 'TB_iframe', true, $upload_iframe_src );
	}

	/**
	 * Normalize parameters for field.
	 *
	 * @param array $field Field parameters.
	 *
	 * @return array
	 */
    static public function normalize( $field ) {
		$field = wp_parse_args( $field, [
			'upload_iframe_src'  =>  '',
		] );
		return $field;
    }

	static public function file_add_id( $field_id ) {
        return "file-add-{$field_id}";
    }

    static public function html_file( $meta ) {
        $i18n_delete = apply_filters( 'spf_file_delete_string', _x( 'Delete', 'file upload', 'splash-fields' ) );
		$i18n_edit   = apply_filters( 'spf_file_edit_string', _x( 'Edit', 'file upload', 'splash-fields' ) );
        $file = array(
            'icon'      => wp_get_attachment_image( $meta, [ 48, 64 ], true ),
            'name'      => basename( get_attached_file( $meta ) ),
            'url'       => wp_get_attachment_url( $meta ),
            'title'     => get_the_title( $meta ),
            'edit_link' => '',
        );
        $edit_link = get_edit_post_link( $meta );
        if ( $edit_link ) {
            $file['edit_link'] = sprintf( '<a href="%s" class="spf-file__edit" target="_blank">%s</a>', $edit_link, $i18n_edit );
        }

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
			$file['icon'],
			esc_url( $file['url'] ),
			esc_html( $file['title'] ),
			esc_html( $file['name'] ),
			$file['edit_link'],
			esc_attr( $meta ),
			esc_html( $i18n_delete ),
		);
    }

	/**
	 * HTML and functionality to add/update/delete image
	 * 
	 * @link		https://codex.wordpress.org/Javascript_Reference/wp.media
	 * 
	 * @param array $field Field parameters.
	 * @param array $meta  Meta value.
	 *
	 * @return array
	 */
	static public function html_input( $field, $meta ) {
        $file_add_name = self::file_add_id( $field['id'] );
        $file_add_class = "spf-field-file__add-file";

        $file_add_attributes = ' type="file" id="' . $file_add_name . '" name="' . $file_add_name . '" class="' . $file_add_class . '"';

        $has_file = false;
		$delete_file_hide = ' hide';
		$add_file_hide = '';

        if ( $meta !== '' && $meta !== null ) {
            $has_file = true;
        }

		if ( $has_file ) {
			$delete_file_hide = '';
			$add_file_hide = ' hide';
		}
		$output = '<div class="spf-field__input">';
		$output .= '<div class="spf-field-file__file-container">';
		if ( $has_file ) {
            $output .= self::html_file( $meta );
		}
		$output .= '</div>';
		$output .= sprintf(
            '<input %s %s/>',
			$add_file_hide,
            $file_add_attributes,
			__( esc_html( 'Add File' ), 'spf' )
		);
		$output .= sprintf(
			'<input id="%s" class="spf-field-file__file-id" name="%s" type="hidden"  value="%s" />',
            $field['id'],
			$field['id'],
			esc_attr( $meta )
		);
		$output .= '</div>';
		return $output;
	}

	public static function check_for_file_object( $value, $object_id, array $field ) {
        return $value || false;
    }

    // TODO
	public static function check_supported_file_types( $value ) {
        /*
        // Setup the array of supported file types. In this case, it's just PDF. 
        $supported_mime_types = array( 'application/pdf' );
        
        // Get the file type of the upload 
        $arr_file_type = wp_check_filetype( basename( $_FILES[$field['id']]['name'] ) );
        $uploaded_type = $arr_file_type['type'];
        
        // Check if the type is supported. If not, throw an error. 
        if ( ! in_array( $uploaded_type, $supported_mime_types ) ) {
            wp_die( 'The file type that you\'ve uploaded is not a PDF.' );
        }
        */
    }

    /**
	 * Process the submitted value before saving into the database.
     * 
	 * @see                 https://developer.wordpress.org/reference/functions/media_handle_upload/
     * 
	 * @param   mixed       $value      The submitted value.
	 * @param   int         $object_id  The object ID.
	 * @param   array       $field      The field settings.
     * 
     * @return  int|string  $value      File attachment ID or empty string if there is nothing.
	 */
	public static function process_value( $value, $object_id, array $field ) {
        // Existing File or Deleted File    : $value = $field['id'] || ''
        // Add File                         : $value = $new_meta from $_FILES

        // Add will not work in AJAX because $_FILES is only available upon posting form
        // Delete should be AJAX, it's not needed in this method

        $value = ''; // By default start out as empty string
        // Empty string value will result in metadata removal in ::save() function

        // Get existing field value from hidden input
        $existing_value = $_POST[$field['id']];
        $file_add_id = self::file_add_id( $field['id'] );

        // If no new $_FILES name exists or no existing value return empty string.
        if ( empty( $_FILES[$file_add_id]['name'] ) && empty( $existing_value ) ) {
            return $value;
        }

        // Make value existing file value - don't change meta or make empty string
        // $value = 'existing_meta_value' OR ''
        $value = $existing_value;

        // Make value equal chosen file attachment ID if $_FILES exists
        if ( ! empty( $_FILES[$file_add_id]['name'] ) ) {

            // TODO
            self::check_supported_file_types( $value );

            $attachment_id = media_handle_upload( $file_add_id, $object_id );

            if ( is_wp_error( $attachment_id ) ) {
                // TODO: Error Handling
                // There was an error uploading the image.
            } else {
                $value = $attachment_id;	
            }
        }

        return $value;
    }

    /**
	 * Save meta value.
	 *
	 * @param mixed $new     The submitted meta value.
	 * @param mixed $old     The existing meta value.
	 * @param int   $post_id The post ID.
	 * @param array $field   The field parameters.
	 */
	public static function save( $new, $old, $post_id, $field ) {
		// Old - Might be useful for later
		// if ( empty( $field['id'] ) || ! $field['save_field'] ) {
		if ( empty( $field['id'] ) ) {
			return;
		}

		$name    = $field['id'];
		$storage = $field['storage'];

		// Remove post meta if $new is empty.
		// $is_valid_for_field = '' !== $new && [] !== $new;
		if ( ! ( '' !== $new && [] !== $new ) ) {
			$storage->delete( $post_id, $name );
			return;
		}

		// Save cloned fields as multiple values instead serialized array.
		if ( $field['multiple'] ) {
			$storage->delete( $post_id, $name );
			$new = (array) $new;
			foreach ( $new as $new_value ) {
				$storage->add( $post_id, $name, $new_value, false );
			}
			return;
		}

		// Default: just update post meta.
		$storage->update( $post_id, $name, $new );
	}
}
