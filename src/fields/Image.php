<?php
/**
 * Image Class.
 *
 * @package splash-fields
 */

namespace Splash_Fields\Fields;

/**
 * Class Image.
 */
class Image extends Input {
    /**
     * Show field HTML.
     *
     * @param array $field   Field parameters.
     * @param int   $post_id Post ID.
     */
    /*
    public static function show( array $field, $post_id = 0 ) {
        // if ( isset( $field['meta'] ) && $field['meta'] ) {
        //     return;
        // } else {

        // }
        $meta = static::raw_meta( $post_id, $field );
        $field['upload_iframe_src'] = static::upload_iframe_src( $post_id );
        $html = sprintf( '<div class="spf-field spf-field-%s">', esc_attr( $field['type'] ) );
        $html .= static::html( $field, $meta );
        $html .= '</div>';
        echo $html;
    }
    */

    /**
     * Enqueue admin scripts.
     */
    public static function admin_enqueue_scripts() {
        wp_enqueue_media();

        wp_register_script(
            'spf-image-js',
            SPF_ASSETS_URL . '/js/image.js',
            array( 'jquery' ),
            false,
            true 
        );

        wp_enqueue_script( 'spf-image-js' );
    }

    /**
     * Get the upload iframe source for media item.
     *
     * @link https://developer.wordpress.org/reference/functions/get_upload_iframe_src/
     * 
     * @param int $post_id Post ID.
     * @return string Media iframe source.
     */
    public static function upload_iframe_src( $post_id = 0 ) {
        $upload_iframe_src = admin_url( 'media-upload.php' );
        if ( $post_id ) {
            $upload_iframe_src = add_query_arg( 'post_id', $post_id, $upload_iframe_src );
        }
        // $upload_iframe_src = add_query_arg( 'post_id', $post_id, admin_url( 'media-upload.php' ) );
        $upload_iframe_src = add_query_arg( 'type', 'image', $upload_iframe_src );
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
            'multiple' => true,
            'field_name' => $field['id'],
            'upload_iframe_src' => '',
        ) );
    }

    /**
     * Markup for the file input field.
     * 
     * @param   array   $field  Field parameters.
     * @param   string  $meta   JSON string of metadata value.
     * @return  string  $output HTML markup for the file input field.
     */
    public static function html_input( $field, $meta ) {
        // $field['upload_iframe_src'] = static::upload_iframe_src( $field['post_id'] );

        // Get WordPress' media upload URL
        // $upload_link = esc_url( $field['upload_iframe_src'] );
        $upload_link = esc_url( static::upload_iframe_src( $field['post_id'] ) );
        var_dump( $upload_link );
        // Decode the JSON string
        $image_data = json_decode( $meta, true );
        $image_id = isset( $image_data['id'] ) ? $image_data['id'] : '';
        $image_url = isset( $image_data['url'] ) ? $image_data['url'] : '';
        $image_name = isset( $image_data['name'] ) ? $image_data['name'] : '';
        $image_alt = isset( $image_data['alt'] ) ? $image_data['alt'] : '';

        // For convenience, check if we have image data
        $has_image = !empty( $image_id );

        $delete_hide_class = $has_image ? '' : ' hide';
        $upload_hide_class = $has_image ? ' hide' : '';

        $output = '<div class="spf-image__image-container">';
        if ( $has_image ) {
            $output .= '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( $image_alt ) . '" />';
        }
        $output .= '</div>';
        $output .= sprintf(
            '<a class="button spf-image__delete%s" href="#">%s</a>',
            $delete_hide_class,
            esc_html__( 'Remove this image', 'spf' )
        );
        $output .= sprintf(
            '<a class="button spf-image__upload%s" href="%s">%s</a>', 
            $upload_hide_class, 
            $upload_link, 
            esc_html__( 'Set Custom Image', 'spf' )
        );
        $output .= sprintf(
            '<input class="spf-image__image-data" name="%s" type="hidden" value="%s" />',
            esc_attr( $field['field_name'] ),
            esc_attr( $meta )
        );
        return $output;
    }

    /**
     * Sanitize the meta value.
     *
     * @param   string  $value JSON string meta value to sanitize.
     * @return  mixed   The sanitized meta value JSON string or empty array.
     */
    public static function sanitize( $value ) {
        // Decode the JSON string.
        $decoded_value = json_decode( $value, true );

        // Check if the decoded value is a single image object.
        if ( is_array( $decoded_value ) && isset( $decoded_value['id'] ) ) {
            $decoded_value['id'] = isset( $decoded_value['id'] ) ? intval( $decoded_value['id'] ) : 0;
            $decoded_value['url'] = isset( $decoded_value['url'] ) ? esc_url_raw( $decoded_value['url'] ) : '';
            $decoded_value['name'] = isset( $decoded_value['name'] ) ? sanitize_text_field( $decoded_value['name'] ) : '';
            $decoded_value['alt'] = isset( $decoded_value['alt'] ) ? sanitize_text_field( $decoded_value['alt'] ) : '';
            return json_encode( $decoded_value );
        }

        // If the value is not an array, return an empty array encoded as a JSON string.
        return json_encode( [] );
    }

}
