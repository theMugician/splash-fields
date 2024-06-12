<?php
/**
 * Plugin_Sidebar Class.
 *
 * @package splash-fields
 */

namespace Splash_Fields;

/**
 * Class Plugin_Sidebar.
 *
 * @property string $id             Plugin Sidebar ID.
 * @property string $title          Plugin Sidebar title.
 * @property array  $fields         List of fields.
 * @property array  $post_types     List of post types that the plugin sidebar is created for.
 */
class Plugin_Sidebar {

    /**
     * Plugin sidebar parameters.
     *
     * @var array
     */
    public $plugin_sidebar;

    /**
     * Detect whether the plugin sidebar is saved at least once.
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
     * The ID of the plugin sidebar.
     *
     * @var string
     */
    public $id;

    /**
     * The title of the plugin sidebar.
     *
     * @var string
     */
    protected $title;

    /**
     * The post types where this plugin sidebar will appear.
     *
     * @var array
     */
    protected $post_types = array();

    /**
     * Fields to be displayed in the plugin sidebar.
     *
     * @var array
     */
    protected $fields = array();

    /**
     * Fields to be displayed in the plugin sidebar.
     *
     * @var array
     */
    protected $data_types = array();

    /**
     * Constructor.
     * Include all relevant scripts and custom fields.
     *
     * @param array $plugin_sidebar Plugin sidebar parameters.
     */
    public function __construct( array $plugin_sidebar ) {
        $plugin_sidebar = static::normalize( $plugin_sidebar );

        $this->plugin_sidebar = $plugin_sidebar;
        $this->id             = $plugin_sidebar['id'];
        $this->title          = $plugin_sidebar['title'];
        $this->post_types     = $plugin_sidebar['post_types'];
        $this->data_types     = static::get_data_types();
        $this->plugin_sidebar['fields'] = static::normalize_fields( $plugin_sidebar['fields'], $this->get_storage() );
        $this->fields       = $this->plugin_sidebar['fields'];

        add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue' ) );
        add_action( 'enqueue_block_assets', array( $this, 'enqueue_block_assets' ) );
    }

    /**
     * Normalize parameters for the plugin sidebar.
     *
     * @param array $plugin_sidebar Plugin sidebar parameters.
     * @return array
     */
    protected static function normalize( array $plugin_sidebar ) {
        $plugin_sidebar = wp_parse_args( $plugin_sidebar, array(
            'id'         => '',
            'title'      => '',
            'post_types' => array( 'post' ),
            'fields'     => array(),
        ) );

        return $plugin_sidebar;
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
     * Enqueue scripts and styles for the block editor.
     */
    public function enqueue() {
        wp_enqueue_script(
            'plugin-sidebar-js',
            SPF_ASSETS_URL . '/js/plugin-sidebar.js',
            array( 'wp-plugins', 'wp-edit-post', 'wp-element', 'wp-components', 'wp-data' ),
            // filemtime( SPF_ASSETS_URL . '/js/plugin-sidebar.js' )
        );
        wp_localize_script(
            'plugin-sidebar-js',
            'fields',
            $this->fields
        );

    }

    /**
     * Enqueue scripts and styles for the block assets.
     */
    public function enqueue_block_assets() {
        wp_enqueue_style(
            'plugin-sidebar-css',
            SPF_ASSETS_URL . '/css/plugin-sidebar.css',
            array(),
            // SPF_ASSETS_URL . '/css/plugin-sidebar.css'
        );
    }

    protected static function get_data_types() {
        $data_types = array(
            'checkbox' => 'boolean',
            'checkbox-list' => 'array',
            'editor' => 'string',
            'file' => 'string',
            'image' => 'integer',
            'number' => 'integer',
            'radio' => 'string',
            'repeater' => 'array',
            'select' => 'string',
            'text' => 'string',
            'textarea' => 'string',
        );
        return $data_types;
    }
    
    /**
     * Register meta fields.
     */
    public function register_meta_fields() {
        foreach ( $this->fields as $field ) {
            foreach ( $this->post_types as $post_type ) {
                register_meta(
                    'post',
                    $field['id'],
                    array(
                        'object_subtype' => $post_type,
                        'show_in_rest'   => true,
                        'single'         => true,
                        'type'           => $this->data_types[$field['type']],
                        'sanitize_callback' => function( $value ) use ( $field ) {
                            // return Field::call( $field, 'process_value', $value, 0, $field );
                            return $value;
                        },
                        'auth_callback'  => function() {
                            return current_user_can( 'edit_posts' );
                        },
                    )
                );
                
            }
        }
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
    }
}
