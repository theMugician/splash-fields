<?php
/**
 * Options_Page Class.
 *
 * @package splash-fields
 */

namespace Splash_Fields;

/*
string $page_title, string $menu_title, string $capability, string $menu_slug, callable $callback = ”
*/

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
	protected $object_type = 'options';

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
		add_options_page(
			$this->title,
			$this->menu_title,
			$this->capability,
			$this->menu_slug,
			[ $this, 'show' ],
		);
	}

    /**
	 * Add options page to site
	 * 
	 * @link https://developer.wordpress.org/reference/functions/add_options_page/
	 */
	public function register_settings() {

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
			// var_dump(Field::call( 'show_in_options_page', $field, $field['id'] ));
			// 2. register fields
			register_setting(
				$this->id, 
				$field['id'],
				array(
					'sanitize_callback' => function( $value ) use ( $field ) {
						// return $value;
						// die();
						error_log( print_r( 'sanitize_callback: ' . $value, true ) );

						return Field::call( $field, 'process_value', $value, 0, $field );
					}, 
				),
				// [ $this, 'spf_sanitize_checkbox' ]
			);
			// register_setting( $this->id, $field['id'], [ $this, 'spf_sanitize_checkbox' ] );
			// register_setting( $this->id, $field['id'], 'spf_sanitize_checkbox' );

			// 3. add fields
			add_settings_field(
				$field['id'],
				$field['name'],
				// \Splash_Fields\Fields\Checkbox::show_in_options_page( $field, $field['id'] ),
				function() use ( $field ) {
					echo Field::call( 'show_in_options_page', $field, $field['id'] ); // function to print the field
				},
				// function() use ( $field ) {
				// 	$this->rudr_checkbox( $field );
				// },
				$this->menu_slug,
				$settings_section_id, //$this->id,	// section ID - same or different than Options?
			);
		}

		// register_setting( $this->id, $field['id'], 'rudr_sanitize_checkbox' );

		// // 3. add fields
		// add_settings_field(
		// 	$field['id'],
		// 	$field['name'],
		// 	'rudr_checkbox',
		// 	$this->menu_slug,
		// 	$settings_section_id, //$this->id,	// section ID - same or different than Options?
		// );



		function spf_sanitize_checkbox( $value ) {
			return 1 === int( $value ) ? 1 : 0;
		}

	}

	public function rudr_checkbox( $field ) {
		$value = get_option( $field['id'] );
		?>
			<label>
				<input value="1" type="checkbox" name="<?php echo $field['id']; ?>" <?php checked( $value, 1 ) ?> /> Yes
			</label>
		<?php
	}
	
	public function rudr_sanitize_checkbox( $value ) {
		return 'on' == $value ? 'yes' : 'no';
	}

    public function show() {
		if ( null === $this->object_id ) {
			$this->object_id = $this->get_current_object_id();
		}
		// $saved = $this->is_saved();
		printf(
			'<div class="%s" data-object-type="%s" data-object-id="%s">',
			esc_attr( trim( "spf-settings" ) ),
			esc_attr( $this->object_type ),
			esc_attr( $this->object_id )
		);

		printf( '<h2>%s</h2>', get_admin_page_title() );
		echo '<form action="options.php" method="post" enctype="multipart/form-data">';
		settings_fields( $this->id );
		do_settings_sections( $this->menu_slug );
		// foreach ( $this->fields as $field ) {
		// 	echo Field::call( 'show_in_options_page', $field, $field['id'] ); // function to print the field
		// }

		printf( '<input name="submit" class="button button-primary" type="submit" value="%s" />', esc_attr( 'Save' ) );
		echo '</form>';
		// Container.


		// wp_nonce_field( "spf-save-{$this->id}", "nonce_{$this->id}" );
        // wp_nonce_field( 'spf_options_page_' . $this->id, 'spf_options_page_' . $this->id . '_nonce' );

		// Allow users to add custom code before meta box content.
		// 1st action applies to all meta boxes.
		// 2nd action applies to only current meta box.
		do_action( 'spf_before', $this );
		do_action( "spf_before_{$this->id}", $this );

		// foreach ( $this->fields as $field ) {
		// 	Field::call( 'show', $field, $this->object_id );
		// }

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

        // TODO: Sanitize
        // Write Sanitizer Class and function

        // update_meta with Storage Class
        Field::call( $field, 'save', $new, $old, $this->object_id, $field );
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