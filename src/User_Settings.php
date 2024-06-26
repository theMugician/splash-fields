<?php
/**
 * User_Settings Class.
 *
 * @package splash-fields
 */

namespace Splash_Fields;

/**
 * Class User_Settings.
 * 
 * @property string $id             Splash_Fields\User_Settings Object ID.
 * @property string $user_id        Current WP_User ID.
 * @property string $title          User_Settings title.
 * @property array  $fields         List of fields.
 */

class User_Settings {
    /**
	 * User_Settings parameters.
	 *
	 * @var array
	 */
	public $user_settings;

	/**
	 * The object ID.
	 * TODO : Remove? - Can't get object ID from this. It's not like a post.
	 * @var int
	 */
	public $object_id = null;

	/**
	 * The object type.
	 *
	 * @var string
	 */
	protected $object_type = 'user';

    /**
     * The ID of the Splash_Fields\User_Settings Object.
     *
     * @var string
     */
    public $id;

    /**
     * The ID of the current user.
     *
     * @var string
     */
    public $user_id;

    /**
     * The title of the user settings.
     *
     * @var string
     */
    protected $title;

    /**
     * List of fields related to the User Settings object.
     *
     * @var array
     */
    protected $fields = array();

    /**
     * Constructor.
     * Include all relevant scripts and custom fields.
     * 
     */
    public function __construct( array $user_settings ) {
		$user_settings           = static::normalize( $user_settings );
		
		$this->user         = $user_settings;
        $this->id           = $user_settings['id'];
        $this->user_id      = get_current_user_id();
        $this->title        = $user_settings['title'];

        $this->user['fields'] = static::normalize_fields( $user_settings['fields'], $this->get_storage() );
        $this->fields         = $this->user['fields'];

        $this->object_hooks();
        $this->global_hooks();
    }

	protected function global_hooks() {
		// Enqueue common styles and scripts.
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue' ] );

		// Add additional actions for fields.
		foreach ( $this->fields as $field ) {
			Field::call( $field, 'add_actions' );
		}
	}

	/**
	 * Specific hooks for user settings object. Default is 'post'.
	 * This should be extended in subclasses to support meta fields for terms, user, settings pages, etc.
	 */
	protected function object_hooks() {
		// Add fields.
        add_action( 'show_user_profile', [ $this, 'show' ] );
        add_action( 'edit_user_profile', [ $this, 'show' ] );

		// Save user meta.
        add_action( 'personal_options_update',  [ $this, 'save_user' ] );
        add_action( 'edit_user_profile_update', [ $this, 'save_user' ] );
	}

	public function enqueue() {
		// Enqueue scripts and styles for fields.
		foreach ( $this->fields as $field ) {
			Field::call( $field, 'admin_enqueue_scripts' );
		}
		/**
		 * Allow developers to enqueue more scripts and styles
		 *
		 * @param User_Settings $object Meta Box object
		 */
		do_action( 'spf_enqueue_scripts', $this );
	}

    public function show() {
		// Container.
		printf(
			'<div class="%s" data-object-type="%s" data-id="%s">',
			esc_attr( trim( "spf-user-settings" ) ),
			esc_attr( $this->object_type ),
			esc_attr( $this->id )
		);

		// wp_nonce_field( "spf-save-{$this->id}", "nonce_{$this->id}" );
        wp_nonce_field( 'spf_user_' . $this->id, 'spf_user_' . $this->id . '_nonce' );

		// Allow users to add custom code before user settings content.
		// 1st action applies to all user settingses.
		// 2nd action applies to only current user settings.
		do_action( 'spf_before', $this );
		do_action( "spf_before_{$this->id}", $this );

		if ( ! empty( $this->title ) ) {
			printf( '<h2>%s</h2>', esc_html( $this->title ) );
		}

		foreach ( $this->fields as $field ) {
			Field::call( 'show', $field, $this->user_id );
		}

		// \Splash_Fields\Fields\Test::this_method();
		// Allow users to add custom code after user settings content.
		// 1st action applies to all user settingses.
		// 2nd action applies to only current user settings.
		do_action( 'spf_after', $this );
		do_action( "spf_after_{$this->id}", $this );

		// End container.
		echo '</div>';
	}

	/**
	 * Save data from user fields
	 *
	 * @param int $user_id User_Settings ID.
	 */
	public function save_user( $user_id ) {
		if ( ! $this->validate() ) {
			return;
		}

		$this->saved = true;

        if ( ! $user_id ) {
            $user_id = get_current_user_id();
        }

		// Before save action.
		do_action( 'spf_before_save_user', $user_id );
		do_action( "spf_{$this->id}_before_save_user", $user_id );

		array_map( [ $this, 'save_field' ], $this->fields );

		// After save action.
		do_action( 'spf_after_save_user', $user_id );
		do_action( "spf_{$this->id}_after_save_user", $user_id );
	}

	public function save_field( array $field ) {
        // Get Posted Value
        $old = Field::call( 'raw_meta', $field, $this->user_id );

        $new = $_POST[$field['id']];
		
		if ( isset( $field['multiple'] ) && $field['multiple'] ) {
			$new = wp_unslash( $new );
		}

		$new = Field::call( $field, 'process_value', $new , $this->user_id, $field );

        // update_meta with Storage Class
        Field::call( $field, 'save', $new, $old, $this->user_id, $field );
	}

    public function register_fields() {
        static $data = [];

		if ( ! isset( $data['field'] ) ) {
			$data['field'] = new Field_Registry();
		}

        $field_registry = $data['field'];

        foreach ( $this->fields as $field ) {
            $field_registry->add( $field, $this->object_type );
        }
	}

	/**
	 * Get storage object.
	 *
	 * @return Storage
	 */
    public function get_storage() {
		return spf_get_storage( $this->object_type );
    }

    public static function normalize( $user_settings ) {
		$default_title = __( 'User_Settings Settings', 'splash-fields' );
		$user_settings      = wp_parse_args( $user_settings, [
			'title'          => $default_title,
			'id'             => ! empty( $meta_box['title'] ) ? sanitize_title( $meta_box['title'] ) : sanitize_title( $default_title ),
			'user_id'        => get_current_user_id(),
			'fields'         => [],
		] );

		// Make sure the post type is an array and is sanitized.
		// $user_settings['post_types'] = array_filter( array_map( 'sanitize_key', Arr::from_csv( $user_settings['post_types'] ) ) );

		return $user_settings;
    }

    public static function normalize_fields( array $fields, $storage = null ) : array {
		foreach ( $fields as $k => $field ) {
			$field = Field::call( 'normalize', $field );

			// Allow to add default values for fields.
			$field = apply_filters( 'spf_normalize_field', $field );
			$field = apply_filters( "spf_normalize_{$field['type']}_field", $field );
			$field = apply_filters( "spf_normalize_{$field['id']}_field", $field );

			$field['storage'] = $storage;

			$fields[ $k ] = $field;
		}

		return $fields;
	}

    /**
     * 
     * Save settings from POST
     * @param   null 
     * @return  bool    $valid
     */
    public function validate() {
    
        $valid = false;

        // Check if our nonce is set.
        if ( ! isset( $_POST['spf_user_' . $this->id  . '_nonce'] ) ) {
            return $valid;
        }

        $nonce = $_POST['spf_user_' . $this->id  . '_nonce'];
        
        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, 'spf_user_' . $this->id  ) ) {
            return $valid;
            echo('Not passed validation');
        }

        /*
        * If this is an autosave, our form has not been submitted,
        * so we don't want to do anything.
        */
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $valid;
        }

        // Check the user's permissions.
		/*
        if ( 'page' == $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_page', $this->object_id ) ) {
                return $valid;
            }
        } else {
            if ( ! current_user_can( 'edit_post', $this->object_id ) ) {
				var_dump('current user can NOT edit this post');
				die();
                return $valid;
            }
        }
		*/

        $valid = true;

        return $valid;
    }

}