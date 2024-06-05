<?php
/**
 * Meta_Box Class.
 *
 * @package splash-fields
 */

namespace Splash_Fields;

/**
 * Class Meta_Box.
 * 
 * @property string $id             Meta Box ID.
 * @property string $title          Meta Box title.
 * @property array  $fields         List of fields.
 * @property array  $post_types     List of post types that the meta box is created for.
 * @property string $priority       The meta box priority.
 * @property string $context        Where the meta box is displayed.
 */
class Meta_Box_Old {
    /**
	 * Meta box parameters.
	 *
	 * @var array
	 */
	public $meta_box;

	/**
	 * Detect whether the meta box is saved at least once.
	 * Used to prevent duplicated calls like revisions, manual hook to wp_insert_post, etc.
	 *
	 * @var bool
	 */
	public $saved = false;

	/**
	 * The object ID.
	 *
	 * @var int
	 */
	public $object_id = null;

	/**
	 * The object type.
	 *
	 * @var string
	 */
	protected $object_type = 'post';

    /**
     * Constructor.
     * Include all relevant scripts and custom fields.
     * 
     */
    public function __construct( array $meta_box )
    {
        // Add admin styles and scripts
        // add_action( 'admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));


        // Include field text object
        // require plugin_dir_path(__FILE__) . 'includes/class-splash-fields-text.php';
        // $this->text_field = new Splash_Fields_Text;

        // // Include field radio object
        // require plugin_dir_path(__FILE__) . 'includes/class-splash-fields-radio.php';
        // $this->radio_field = new Splash_Fields_Radio;

    }


    public function enqueue_admin_scripts() {
        // wp_enqueue_style( 'splash-fields-css', plugins_url( 'admin/css/splash-fields.css', __FILE__ ), null, '');
        wp_enqueue_style( 'splash-fields-css', '/wp-content/themes/wordpress-starter/splash-meta-box/admin/css/splash-fields.css', null, '');
    }
    
    /**
     * Render the content of the meta box using a PHP template.
     * Rendering fields 
     * @return void
     */
    public function render( $post ) {
        $this->post_id = $post->ID;
        $this->init_settings();
        wp_nonce_field( 'metabox_' . $this->id, 'metabox_' . $this->id . '_nonce' );
        if ( empty( $this->fields ) || null === $this->fields  ) {
            echo '<p>' . __( 'There are no settings on this page.', 'textdomain' ) . '</p>';
            return;
        } else {
            foreach ( $this->fields as $field ) {
                if ( isset( $field['type'] ) ) {
                    $type = $field['type'];
                }
                $render_type = 'render_' . $type;
                call_user_func( array( $this, $render_type ), $field );
            }
        }
    }
    
    /**
     * Add meta box to a post.
     *
     */
    public function register( $id, $title, $context = 'advanced', $priority = 'default', $screens = array() )
    {
        if ( is_string( $screens ) ) {
            $screens = (array) $screens;
        }

        $this->id = $id;
        $this->title = $title;
        $this->context = $context;
        $this->priority = $priority;
        $this->screens = $screens;
        $this->settings_id = $this->id; 
        // $this->fields = array();

        add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
        add_action( 'save_post', array( $this, 'save_meta_settings' ) );
    }

    public function add_meta_box() {
        add_meta_box( $this->id, $this->title, array( $this, 'render' ), $this->screens );
    }

    /**
     * Add field to the meta box.
     *
     */
    public function add_field( $settings ) {
        $allowed_field_types = array(
            'text',
            'textarea',
            'wpeditor',
            'select',
            'radio',
            'checkbox',
            'image'
        );

        // If a type is set that is now allowed, don't add the field
        if ( isset( $settings['type'] ) && $settings['type'] != '' && ! in_array( $settings['type'], $allowed_field_types ) ) {
            return;
        }
        
        $defaults = array(
            'id' => '',
            'title' => '',
            'default' => '',
            'placeholder' => '',
            'type' => 'text',
            'options' => array(),
            'description' => '',
        );

        $settings = array_merge( $defaults, $settings );

        if ( $settings['id'] == '' ) {
            return;
        }

        foreach ( $this->fields as $field ) {
            if( isset( $this->fields[ $settings['id'] ] ) ) {
                trigger_error( 'There is alreay a field with name ' . $settings['id'] );
                return;
            }
        }
        
        $this->fields[ $settings['id'] ] = $settings;
    }

    /**
     * Get the settings from the database.
     * @return void 
     */
    public function init_settings() {
        $post_id = $this->post_id;
        $this->settings = get_post_meta( $post_id, $this->settings_id, true );
    
        foreach ( $this->fields as $field ) {
            if( isset( $this->settings[ $field['id'] ] ) ) {
                $this->fields[ $field['id'] ]['default'] = $this->settings[ $field['id'] ];
            }
        }
    }

    public static function normalize( $meta_box ) {
		$default_title = __( 'Meta Box Title', 'meta-box' );
		$meta_box      = wp_parse_args( $meta_box, [
			'title'          => $default_title,
			'id'             => ! empty( $meta_box['title'] ) ? sanitize_title( $meta_box['title'] ) : sanitize_title( $default_title ),
			'context'        => 'normal',
			'priority'       => 'high',
			'post_types'     => 'post',
			'fields'         => [],
		] );

		// Make sure the post type is an array and is sanitized.
		$meta_box['post_types'] = array_filter( array_map( 'sanitize_key', Arr::from_csv( $meta_box['post_types'] ) ) );

		return $meta_box;
    }

    /**
     * 
     * Save settings from POST
     * @param WP_Post $post_id
     */
    public function save_meta_settings( $post_id ) {
    
        // Check if our nonce is set.
        if ( ! isset( $_POST['metabox_' . $this->id  . '_nonce'] ) ) {
            return $post_id;
        }
        
        $nonce = $_POST['metabox_' . $this->id  . '_nonce'];
        
        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, 'metabox_' . $this->id  ) ) {
            echo('passed validation');
        }
        
        /*
        * If this is an autosave, our form has not been submitted,
        * so we don't want to do anything.
        */
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
        
        // Check the user's permissions.
        if ( 'page' == $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }
        
        foreach( $this->fields as $field ) {
            $key = $field['id'];
            $type = $field['type'];
            $this->settings[ $key ] = $this->{ 'validate_' . $type } ( $key );
        }
        update_post_meta( $post_id, $this->settings_id, $this->settings );	

    }

    /**
     * Gets a field from the settings API, using defaults if necessary to prevent undefined notices.
     *
     * @param  string $key
     * @param  mixed  $empty_value
     * @return mixed  The value specified for the field or a default value for the field.
     */
    public function get_field( $key, $empty_value = null ) {
        if ( empty( $this->settings ) ) {
            $this->init_settings();
        }
        // Get field default if unset.
        if ( ! isset( $this->settings[ $key ] ) ) {
            $form_fields = $this->fields;
            foreach ( $form_fields as $field ) {
                if( isset( $form_fields[ $key ] ) ) {
                    $this->settings[ $key ] = isset( $form_fields[ $key ]['default'] ) ? $form_fields[ $key ]['default'] : '';
                }
            }
        }
        if ( ! is_null( $empty_value ) && empty( $this->settings[ $key ] ) && '' === $this->settings[ $key ] ) {
            $this->settings[ $key ] = $empty_value;
        }
        return $this->settings[ $key ];
    }

    // Render text field
    public function render_text( $array ) {
        $this->text_field->render( $array );
    }

    // Validate text field
    public function validate_text( $key ) {
        return $this->text_field->validate( $key );
    }

    // Render radio field
    public function render_radio( $array ) {
        $this->radio_field->render( $array );
    }

    // Validate text field
    public function validate_radio( $key ) {
        return $this->radio_field->validate( $key );
    }
}