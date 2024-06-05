<?php
/**
 * Image Class.
 *
 * @package splash-fields
 */

namespace Splash_Fields\Fields;

/**
 * Class Image.
 * 
 */
class Image extends Input {
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
		$field['upload_iframe_src'] = static::upload_iframe_src( $post_id );
		$html = sprintf( '<div class="spf-field spf-field-%s">', $field['type'] );
		$html .= static::html( $meta, $field );
		$html .= '</div>';
		echo $html;
	}

	public static function admin_enqueue_scripts() {
		// Register script for Media Field
		wp_enqueue_media();

		wp_register_script(
			'spf-image-js',
			SPF_ASSETS_URL . '/js/image.js',
			array( 'jquery' ),
			false,
			true 
		);

		// wp_localize_script(
        //     'spf-media-js',
        //     'mediaField',
        //     array(
        //         'id' => $field['id']
        //     ) 
        // );

        wp_enqueue_script( 'spf-image-js' );
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
		// Get WordPress' media upload URL
		$upload_link = $field['upload_iframe_src'];

		// See if there's a media id already saved as post meta
		$image_id = $meta;

		// Get the image src
		$image_src = wp_get_attachment_image_src( $image_id, 'full' );

		// For convenience, see if the array is valid
		$has_image = is_array( $image_src );

		$delete_hide_class = ' hide';
		$upload_hide_class = '';

		if ( $has_image ) {
			$delete_hide_class = '';
			$upload_hide_class = ' hide';
		}
		$output = '<div class="spf-field__input">';
		$output .= '<div class="spf-image__image-container">';
		if ( $has_image ) {
			$output .= '<img src="' . esc_url( $image_src[0] ) . '" alt="" />';
		}
		$output .= '</div>';
		$output .= sprintf(
			'<a class="button spf-image__delete%s" href="#">%s</a>',
			$delete_hide_class,
			__(  esc_html( 'Remove this image' ), 'spf' )
		);
		$output .= sprintf(
			'<a class="button spf-image__upload%s" href="%s">%s</a>', 
			$upload_hide_class, 
			$upload_link, 
			__( esc_html( 'Set Custom Image' ), 'spf' )
		);
		$output .= sprintf(
			'<input class="spf-image__image-id" name="%s" type="hidden"  value="%s" />',
			esc_attr( $field['id'] ),
			esc_attr( $image_id )
		);
		$output .= '</div>';
		return $output;
	}
}
