<?php
/**
 * Media Class.
 *
 * @package splash-fields
 */

namespace Splash_Fields\Fields;
use WP;

/**
 * Class Media.
 * 
 */
class Media extends Input {
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
		$html = sprintf( '<div class="spf-field spf-field-%s">', $field['type'] );
		$html .= static::html( $meta, $field );
		$html .= '</div>';
		echo $html;
	}

	public static function admin_enqueue_scripts() {
		// Register script for Instant Quote forms
		wp_enqueue_media();

		wp_register_script(
			'spf-media-js',
			SPF_ASSETS_URL . '/js/media.js',
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

        wp_enqueue_script( 'spf-media-js' );
	}
    /**
	 * Get the attributes for a field.
	 *
	 * @param array $field Field parameters.
	 * @param mixed $value Meta value.
	 *
	 * @return array
	 */
	// public static function get_attributes( $field, $value = null ) {
	// 	$attributes = parent::get_attributes( $field, $value );
    //     if ( $value === null ) {
    //         $value = '';
    //     }

	// 	$attributes = wp_parse_args( $attributes, [
	// 		'value'        => $value,
	// 		'type'         => 'file',
	// 	] );

	// 	return $attributes;
	// }

	// static public function html_input( $field, $meta ) {
    //     $attributes = self::get_attributes( $field, $meta );
	// 	$output     = '<div class="spf-field__input">';
    //     $output    .= sprintf(
	// 		'<input %s>',
	// 		self::render_attributes( $attributes ),
	// 	);
	// 	$output    .= '</div>';
	// 	return $output;
    // }


	/**
	 * Normalize parameters for field.
	 *
	 * @param array $field Field parameters.
	 * @link	
	 * @return array
	 */
    static public function upload_iframe_src( $post_id ) {
		$upload_iframe_src = add_query_arg( 'post_id', $post_id, admin_url( 'media-upload.php' ) );
		return add_query_arg( 'TB_iframe', true, $upload_iframe_src );
		// return esc_url( get_upload_iframe_src( 'image', $post_id ) );
	}

	/**
	 * Normalize parameters for field.
	 *
	 * @param array $field Field parameters.
	 *
	 * @return array
	 */
    static public function normalize( $field ) {
		$post_id = 1;
		$upload_iframe_src = static::upload_iframe_src( $post_id );
		// $field = parent::normalize( $field );

		// var_dump($field['storage']);
		$field = wp_parse_args( $field, [
			'upload_iframe_src'  =>  $upload_iframe_src,
		] );
		return $field;
    }

	static public function base_html( $field, $meta ) {

        $html = '<input id="post_media" type="file" name="post_media" value="" size="25" />';
        $html .= '<p class="description">';
        if( '' == get_post_meta( $post->ID, 'umb_file', true ) ) {
            $html .= __( 'You have no file attached to this post.', 'umb' );
        } else {
            $html .= get_post_meta( $post->ID, 'umb_file', true );
        } // end if
        $html .= '</p><!-- /.description -->';
        echo $html;
    }

	static public function html( $meta, $field ) {
		// Get WordPress' media upload URL
		// $upload_link = esc_url( get_upload_iframe_src( 'image', 1 ) );
		$upload_link = $field['upload_iframe_src'];

		// See if there's a media id already saved as post meta
		// $image_id = get_post_meta( 1, $field['id'], true );
		$image_id = $meta;

		// Get the image src
		$image_src = wp_get_attachment_image_src( $image_id, 'full' );

		// For convenience, see if the array is valid
		$has_image = is_array( $image_src );
		?>
		<div class="spf-field-media__image-container">
			<?php if ( $has_image ) : ?>
				<img src="<?php echo $image_src[0] ?>" alt="" style="max-width:100%;" />
			<?php endif; ?>
		</div>

		<!-- Your add & remove image links -->
		<p class="hide-if-no-js">
			<a class="spf-field-media__upload-image <?php if ( $has_image  ) { echo 'hidden'; } ?>" 
			href="<?php echo $upload_link ?>">
				<?php _e('Set custom image') ?>
			</a>
			<a class="spf-field-media__delete-image <?php if ( ! $has_image  ) { echo 'hidden'; } ?>" 
			href="#">
				<?php _e('Remove this image') ?>
			</a>
		</p>

		<!-- A hidden input to set and post the chosen image id -->
		<input 
			class="spf-field-media__image-id" 
			name="<?php echo $field['id']; ?>" 
			type="hidden" 
			value="<?php echo esc_attr( $image_id ); ?>" 
		/>
		<?php
	}
}