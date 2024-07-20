<?php
/**
 * Options_Page Class.
 *
 * @package splash-fields
 */

namespace Splash_Fields;

/**
 * Class Options_Page.
 * 
 * @property string $id             	Option Page ID.
 * @property string $title          	Option Page title.
 * @property string $menu_title     	Option Page menu name.
 * @property string $capability   		Option Page capability.
 * @property string $menu_slug     		Option Page menu slug.
 * @property array  $fields         	List of fields.
 */
class Options_Page {
    /**
	 * Option page parameters.
	 *
	 * @var array
	 */
	public $options_page;

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
	protected $object_type = 'options';

	/**
	 * Detect whether the settings have been registered.
	 * Used to prevent duplicated calls.
	 *
	 * @var bool
	 */
	protected $registered = false;

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
     * The title of the meta box.
     *
     * @var string
     */
    protected $menu_title;

    /**
     * The title of the meta box.
     *
     * @var string
     */
    protected $capability;

    /**
     * The title of the meta box.
     *
     * @var string
     */
    protected $menu_slug;

    /**
     * Screens where this meta box will appear.
     *
     * @var array
     */
    protected $fields = array();

	/**
	 * Flags to track if sanitize callback has processed the value for each field.
	 * sanitize_callback runs twice on initial save so this is the fix.
	 * @see https://stackoverflow.com/questions/71974444/wordpress-register-setting-sanitize-callback-runs-twice-on-initial-save
	 * @var array
	 */
	private static $processed_fields = array();

    /**
     * Constructor.
     * Include all relevant scripts and custom fields.
     * 
     */
    public function __construct( array $options_page ) {
		$options_page           = static::normalize( $options_page );

		$this->options_page = $options_page;
        $this->id           = $options_page['id'];
        $this->title        = $options_page['title'];
        $this->menu_title   = $options_page['menu_title'];
        $this->menu_slug   	= $options_page['menu_slug'];
		$this->parent_slug  = isset( $options_page['parent_slug'] ) ? $options_page['parent_slug'] : null;
        $this->capability   = $options_page['capability'];

        $this->options_page['fields'] = static::normalize_fields( $options_page['fields'], $this->get_storage() );
        $this->fields       = $this->options_page['fields'];

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
	 * Specific hooks for options page object. Default is 'post'.
	 * This should be extended in subclasses to support meta fields for terms, user, settings pages, etc.
	 */
	protected function object_hooks() {
		// Add options page.
		add_action( 'admin_menu', [ $this, 'add_options_page' ] );
		// Add settings to that options page.
		add_action( 'admin_init', [ $this, 'register_settings' ] );
	}

	public function enqueue() {
		// Enqueue scripts and styles for fields.
		foreach ( $this->fields as $field ) {
			Field::call( $field, 'admin_enqueue_scripts' );
		}
		/**
		 * Allow developers to enqueue more scripts and styles
		 *
		 * @param Options_Page $object Option Page object
		 */
		do_action( 'spf_enqueue_scripts', $this );
	}

    /**
	 * Add options page to site
	 * 
	 * @link https://developer.wordpress.org/reference/functions/add_options_page/
	 */
	public function add_options_page() {
		if ( $this->parent_slug ) {
			add_submenu_page(
				$this->parent_slug,
				$this->title,
				$this->menu_title,
				$this->capability,
				$this->menu_slug,
				[ $this, 'show' ]
			);
		} else {
			add_menu_page(
				$this->title,
				$this->menu_title,
				$this->capability,
				$this->menu_slug,
				[ $this, 'show' ]
			);
		}
	}

    /**
	 * Add options page to site
	 * 
	 * @link https://developer.wordpress.org/reference/functions/add_options_page/
	 */
	public function register_settings() {

		// Prevent duplicate registration
		if ( $this->registered ) {
			return;
		}
		$this->registered = true;

		// $settings_section_id = 'settings_section_id';
		$settings_section_id = $this->id;

		// 1. create section
		add_settings_section(
			$settings_section_id, // $this->id,	// section ID - same or different than Options?
			'', 		// title (optional)
			'', 		// callback function to display the section (optional)
			$this->menu_slug
		);

		foreach ( $this->fields as $field ) {
			// 2. register fields
			register_setting(
				$this->id, 
				$field['id'],
				array(
					'sanitize_callback' => function( $value ) use ( $field ) {
						if ( isset( self::$processed_fields[ $field['id'] ] ) ) {
							return $value;
						}
						self::$processed_fields[$field['id']] = true;

						if ( $value === '' || $value === null ) {
							// return '__unset__';
							return null;
						}
						$value = Field::call( $field, 'process_value', $value, 0, $field );

						// TODO BUG: Reproduce bug for file value
						// Sometimes the value comes out as """"
						if ( is_array( $value ) ) {
							return json_encode( $value );
						}

						return $value;
					}, 
				),
			);

			// 3. add fields
			add_settings_field(
				$field['id'],
				$field['name'],
				function() use ( $field ) {
					echo Field::call( 'show_in_options_page', $field, $field['id'] ); // function to print the field
				},
				$this->menu_slug,
				$settings_section_id, //$this->id,	// section ID - same or different than Options?
			);

			// Add a dynamic filter for each field - delete option if value is '__unset__'
			/*
			add_filter( 'pre_update_option_' . $field['id'], function( $new_value, $old_value ) use ( $field ) {
				error_log( 'pre_update_option_' . $field['id'] . ' - ' . $new_value );
				if ( $new_value === '__unset__' ) {
					error_log( 'unset_true_' . $field['id'] . ' - ' . $new_value );

					delete_option( $field['id'] );
					return null;
				}
				return $new_value;
			}, 10, 2 );
			*/

		}
		// Reset processed fields after registering all settings
		self::$processed_fields = array();

	}

    public function show() {
		if ( null === $this->object_id ) {
			$this->object_id = $this->get_current_object_id();
		}

		printf(
			'<div class="%s" data-object-type="%s" data-object-id="%s">',
			esc_attr( trim( "spf-settings" ) ),
			esc_attr( $this->object_type ),
			esc_attr( $this->object_id )
		);

		printf( '<h1>%s</h1>', get_admin_page_title() );
		echo '<form action="options.php" method="post" enctype="multipart/form-data">';
		settings_fields( $this->id );
		do_settings_sections( $this->menu_slug );

		printf( '<input name="submit" class="spf-settings__save button button-primary" type="submit" value="%s" />', esc_attr( 'Save' ) );
		echo '</form>';
		// Container.

		// 1st action applies to all meta boxes.
		// 2nd action applies to only current meta box.
		do_action( 'spf_before', $this );
		do_action( "spf_before_{$this->id}", $this );

		// 1st action applies to all meta boxes.
		// 2nd action applies to only current meta box.
		do_action( 'spf_after', $this );
		do_action( "spf_after_{$this->id}", $this );

		// End container.
		echo '</div>';
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

    public function get_storage() {
        static $data = [];
        $type = 'storage';
		$class = 'Splash_Fields\Storage_Registry';

		if ( ! isset( $data[ $type ] ) ) {
			$data[ $type ] = new $class();
		}

		$storage = $data[ $type ]->get( 'Splash_Fields\Storage');

        return apply_filters( 'spf_get_storage', $storage, $this->object_type, $this );
    }
    

    public static function normalize( $options_page ) {
		$default_title = __( 'Option Page Title', 'meta-box' );
		$options_page      = wp_parse_args( $options_page, [
			'title'          => $default_title,
			'id'             => ! empty( $options_page['title'] ) ? sanitize_title( $options_page['title'] ) : sanitize_title( $default_title ),
			'context'        => 'normal',
			'priority'       => 'high',
			'post_types'     => array( 'post' ),
			'fields'         => [],
		] );

		// Make sure the post type is an array and is sanitized.
		// $options_page['post_types'] = array_filter( array_map( 'sanitize_key', Arr::from_csv( $options_page['post_types'] ) ) );

		return $options_page;
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
        if ( ! isset( $_POST['spf_options_page_' . $this->id  . '_nonce'] ) ) {
            return $valid;
        }

        $nonce = $_POST['spf_options_page_' . $this->id  . '_nonce'];
        
        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, 'spf_options_page_' . $this->id  ) ) {
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
}