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
        $this->fields         = $plugin_sidebar['fields'];

        add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue' ) );
        add_action( 'enqueue_block_assets', array( $this, 'enqueue_block_assets' ) );
        add_action( 'init', array( $this, 'register_meta_fields' ) );
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
                        'type'           => isset( $field['type'] ) ? $field['type'] : 'string',
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
     * Validate the plugin sidebar.
     *
     * @return bool
     */
    public function validate() {
        $valid = false;

        // Check if our nonce is set.
        if ( ! isset( $_POST[ 'spf_sidebar_' . $this->id . '_nonce' ] ) ) {
            return $valid;
        }

        $nonce = $_POST[ 'spf_sidebar_' . $this->id . '_nonce' ];

        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, 'spf_sidebar_' . $this->id ) ) {
            return $valid;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $valid;
        }

        // Check the user's permissions.
        if ( 'page' == $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_page', $this->object_id ) ) {
                return $valid;
            }
        } else {
            if ( ! current_user_can( 'edit_post', $this->object_id ) ) {
                return $valid;
            }
        }

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
