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
class Meta_Box {
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
     * The ID of the meta box.
     *
     * @var string
     */
    public $id;

    /**
     * The title of the meta box.
     *
     * @var string
     */
    protected $title;

    /**
     * The display priority of the meta box.
     *
     * @var string
     */
    protected $priority;

    /**
     * The post types where this meta box will appear.
     *
     * @var array
     */
    protected $post_types = array();

    /**
     * Screens where this meta box will appear.
     *
     * @var array
     */
    protected $fields = array();


    /**
     * Screen context where the meta box should display.
     *
     * @var string
     */
    protected $context;

    /**
     * Constructor.
     * Include all relevant scripts and custom fields.
     * 
     */
    public function __construct( array $meta_box ) {
		$meta_box           = static::normalize( $meta_box );

		$this->meta_box     = $meta_box;
        $this->id           = $meta_box['id'];
        $this->title        = $meta_box['title'];
        $this->post_types   = $meta_box['post_types'];
        $this->priority     = $meta_box['priority'];;
        $this->context      = $meta_box['context'];

        $this->meta_box['fields'] = static::normalize_fields( $meta_box['fields'], $this->get_storage() );
        $this->fields       = $this->meta_box['fields'];

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
	 * Specific hooks for meta box object. Default is 'post'.
	 * This should be extended in subclasses to support meta fields for terms, user, settings pages, etc.
	 */
	protected function object_hooks() {
		// Add meta box.
		add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );

		// Save post meta.
		foreach ( $this->post_types as $post_type ) {
			if ( 'attachment' === $post_type ) {
				// Attachment uses other hooks.
				// @see wp_update_post(), wp_insert_attachment().
				add_action( 'edit_attachment', [ $this, 'save_post' ] );
				add_action( 'add_attachment', [ $this, 'save_post' ] );
			} else {
				add_action( "save_post_{$post_type}", [ $this, 'save_post' ] );
			}
		}
	}

	public function enqueue() {
		// Enqueue scripts and styles for fields.
		foreach ( $this->fields as $field ) {
			Field::call( $field, 'admin_enqueue_scripts' );
		}
		/**
		 * Allow developers to enqueue more scripts and styles
		 *
		 * @param Meta_Box $object Meta Box object
		 */
		do_action( 'spf_enqueue_scripts', $this );
	}

    /**
	 * Add meta box for multiple post types
	 */
	public function add_meta_boxes() {
		foreach ( $this->post_types as $post_type ) {
			add_meta_box(
				$this->id,
				$this->title,
				[ $this, 'show' ],
				$post_type,
				$this->context,
				$this->priority
			);
		}
	}

    public function show() {
		if ( null === $this->object_id ) {
			$this->object_id = $this->get_current_object_id();
		}
		// $saved = $this->is_saved();

		// Container.
		printf(
			'<div class="%s" data-object-type="%s" data-object-id="%s">',
			esc_attr( trim( "spf-metabox" ) ),
			esc_attr( $this->object_type ),
			esc_attr( $this->object_id )
		);

		// wp_nonce_field( "spf-save-{$this->id}", "nonce_{$this->id}" );
        wp_nonce_field( 'spf_metabox_' . $this->id, 'spf_metabox_' . $this->id . '_nonce' );

		// Allow users to add custom code before meta box content.
		// 1st action applies to all meta boxes.
		// 2nd action applies to only current meta box.
		do_action( 'spf_before', $this );
		do_action( "spf_before_{$this->id}", $this );

		foreach ( $this->fields as $field ) {
			Field::call( 'show', $field, $this->object_id );
		}

		// Field::call( 'show', $this->fields[0], $this->object_id );

		// \Splash_Fields\Fields\Test::this_method();
		// Allow users to add custom code after meta box content.
		// 1st action applies to all meta boxes.
		// 2nd action applies to only current meta box.
		do_action( 'spf_after', $this );
		do_action( "spf_after_{$this->id}", $this );

		// End container.
		echo '</div>';
	}

	/**
	 * Save data from meta box
	 *
	 * @param int $object_id Object ID.
	 */
	public function save_post( $object_id ) {
		if ( ! $this->validate() ) {
			return;
		}

		$this->saved = true;

		$object_id       = $this->get_real_object_id( $object_id );
		$this->object_id = $object_id;

		// Before save action.
		do_action( 'spf_before_save_post', $object_id );
		do_action( "spf_{$this->id}_before_save_post", $object_id );

		array_map( [ $this, 'save_field' ], $this->fields );

		// After save action.
		do_action( 'spf_after_save_post', $object_id );
		do_action( "spf_{$this->id}_after_save_post", $object_id );
	}

	public function save_field( array $field ) {
        // Get Posted Value
        $old = Field::call( 'raw_meta', $field, $this->object_id );

        $new = $_POST[$field['id']];

		$class = '\\Splash_Fields\\Fields\\' . \Splash_Fields\Helpers\String_Helper::title_case( $field['type'] );

		$new = Field::call( $field, 'process_value', $new , $this->object_id, $field );
		
        // update_meta with Storage Class
        Field::call( $field, 'save', $new, $old, $this->object_id, $field );
	}

    public function register_fields() {
        static $data = [];

		if ( ! isset( $data['field'] ) ) {
			$data['field'] = new Field_Registry();
		}

        $field_registry = $data['field'];

		foreach ( $this->post_types as $post_type ) {
			foreach ( $this->fields as $field ) {
				$field_registry->add( $field, $post_type );
			}
		}
	}

	/**
	 * Get storage object.
	 *
	 * @return Storage
	 */
    public function get_storage() {
		return spf_get_storage( $this->object_type );
        // static $data = [];
        // $data_type = 'storage';
		// $storage_registry_class = 'Splash_Fields\Storage_Registry';
		// $storage_class = '\\Splash_Fields\\Storages\\' . \Splash_Fields\Helpers\String_Helper::title_case( $this->object_type );

		// if ( ! isset( $data[ $data_type ] ) ) {
		// 	$data[ $data_type ] = new $storage_registry_class();
		// }

		// $storage = $data[ $data_type ]->get( $storage_class );

        // return apply_filters( 'spf_get_storage', $storage, $this->object_type, $this );
    }

    public static function normalize( $meta_box ) {
		$default_title = __( 'Meta Box Title', 'meta-box' );
		$meta_box      = wp_parse_args( $meta_box, [
			'title'          => $default_title,
			'id'             => ! empty( $meta_box['title'] ) ? sanitize_title( $meta_box['title'] ) : sanitize_title( $default_title ),
			'context'        => 'normal',
			'priority'       => 'high',
			'post_types'     => array( 'post' ),
			'fields'         => [],
		] );

		// Make sure the post type is an array and is sanitized.
		// $meta_box['post_types'] = array_filter( array_map( 'sanitize_key', Arr::from_csv( $meta_box['post_types'] ) ) );

		return $meta_box;
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
        if ( ! isset( $_POST['spf_metabox_' . $this->id  . '_nonce'] ) ) {
            return $valid;
        }

        $nonce = $_POST['spf_metabox_' . $this->id  . '_nonce'];
        
        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, 'spf_metabox_' . $this->id  ) ) {
            return $valid;
            echo('passed validation');
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

	/**
	 * Get current object id.
	 *
	 * @return int
	 */
	protected function get_current_object_id() {
		return get_the_ID();
	}

	/**
	 * Get real object ID when submitting.
	 *
	 * @param int $object_id Object ID.
	 * @return int
	 */
	protected function get_real_object_id( $object_id ) {
		// Make sure meta is added to the post, not a revision.
		if ( 'post' !== $this->object_type ) {
			return $object_id;
		}
		$parent = wp_is_post_revision( $object_id );

		return $parent ?: $object_id;
	}
}