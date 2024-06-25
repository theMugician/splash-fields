<?php
/**
 * File Class.
 *
 * @package splash-fields
 */

namespace Splash_Fields\Fields;

/**
 * Class File.
 */
class File extends Input {
    /**
     * Show field HTML.
     *
     * @param array $field   Field parameters.
     * @param int   $post_id Post ID.
     */
    public static function show( array $field, $post_id = 0 ) {
        $meta = static::raw_meta( $post_id, $field );
        $field['upload_iframe_src'] = static::upload_iframe_src( $post_id );
        $html = sprintf( '<div class="spf-field spf-field-%s">', esc_attr( $field['type'] ) );
        $html .= static::html( $field, $meta );
        $html .= '</div>';
        echo $html;
    }

    /**
     * Enqueue admin scripts.
     */
    public static function admin_enqueue_scripts() {
        wp_enqueue_media();

        wp_register_script(
            'spf-file-js',
            SPF_ASSETS_URL . '/js/file.js',
            array( 'jquery' ),
            false,
            true 
        );

        wp_enqueue_script( 'spf-file-js' );
    }

    /**
     * Get the upload iframe source for media item.
     *
     * @link https://developer.wordpress.org/reference/functions/get_upload_iframe_src/
     * 
     * @param int $post_id Post ID.
     * @return string Media iframe source.
     */
    public static function upload_iframe_src( $post_id ) {
        $upload_iframe_src = add_query_arg( 'post_id', $post_id, admin_url( 'media-upload.php' ) );
        $upload_iframe_src = add_query_arg( 'type', 'file', $upload_iframe_src );

        return add_query_arg( 'TB_iframe', true, $upload_iframe_src );
    }

    /**
     * Normalize parameters for field.
     *
     * @param array $field Field parameters.
     * @return array
     */
    public static function normalize( $field ) {
        return wp_parse_args( $field, array(
            'field_name' => $field['id'],
            'upload_iframe_src' => '',
        ) );
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
        if ( is_string( $meta ) && is_json( $meta ) ) {
            $meta = json_decode( $meta, true );
        }
        $file = $meta;

        // Return the formatted HTML string for the file display.
        return sprintf(
            '<div class="spf-file">
                <div class="spf-file__icon">%s</div>
                <div class="spf-file__info">
                    <a href="%s" target="_blank" class="spf-file__title">%s</a>
                    <div class="spf-file__name">%s</div>
                </div>
            </div>',
            wp_get_attachment_image( $file['id'], [48, 64], true ),
            esc_url( $file['url'] ),
            esc_html( $file['name'] ),
            esc_html( $file['name'] ),
        );
    }

    /**
     * HTML and functionality to add/update/delete file.
     *
     * @link https://codex.wordpress.org/Javascript_Reference/wp.media
     * 
     * @param array $field Field parameters.
     * @param mixed $meta  Meta value.
     * @return string
     */
    public static function html_input( $field, $meta ) {
        // Get WordPress' media upload URL
        $upload_link = esc_url( $field['upload_iframe_src'] );

        // Decode the JSON string
        $file_data = json_decode( $meta, true );
        $file_id = isset( $file_data['id'] ) ? $file_data['id'] : '';
        $file_url = isset( $file_data['url'] ) ? $file_data['url'] : '';
        $file_name = isset( $file_data['name'] ) ? $file_data['name'] : '';
        $file_type = isset( $file_data['type'] ) ? $file_data['type'] : '';

        // For convenience, check if we have file data
        $has_file = !empty( $file_id );

        $delete_hide_class = $has_file ? '' : ' hide';
        $upload_hide_class = $has_file ? ' hide' : '';

        $output = '<div class="spf-file__file-container">';
        if ( $has_file ) {
            $output .= self::html_file( $file_data );
            // $output .= '<div>' . esc_html( $file_name ) . '</div>';
        }
        $output .= '</div>';
        $output .= sprintf(
            '<a class="button spf-file__delete%s" href="#">%s</a>',
            $delete_hide_class,
            esc_html__( 'Remove this file', 'spf' )
        );
        $output .= sprintf(
            '<a class="button spf-file__upload%s" href="%s">%s</a>', 
            $upload_hide_class, 
            $upload_link, 
            esc_html__( 'Set Custom File', 'spf' )
        );
        $output .= sprintf(
            '<input class="spf-file__file-data" name="%s" type="hidden" value="%s" />',
            esc_attr( $field['field_name'] ),
            esc_attr( $meta )
        );
        return $output;
    }

    /**
     * Sanitize the meta value.
     *
     * @param string $value The meta value to sanitize.
     * @return string The sanitized meta value.
     */
    public static function sanitize( $value ) {
        // Decode the JSON string.
        $decoded_value = json_decode( $value, true );

        // Check if the decoded value is a single file object.
        if ( is_array( $decoded_value ) && isset( $decoded_value['id'] ) ) {
            $decoded_value['id'] = isset( $decoded_value['id'] ) ? intval( $decoded_value['id'] ) : 0;
            $decoded_value['url'] = isset( $decoded_value['url'] ) ? esc_url_raw( $decoded_value['url'] ) : '';
            $decoded_value['name'] = isset( $decoded_value['name'] ) ? sanitize_text_field( $decoded_value['name'] ) : '';
            $decoded_value['type'] = isset( $decoded_value['type'] ) ? sanitize_text_field( $decoded_value['type'] ) : '';
            return json_encode( $decoded_value );
        }

        // If the value is not an array, return an empty array encoded as a JSON string.
        return json_encode( [] );
    }
}
