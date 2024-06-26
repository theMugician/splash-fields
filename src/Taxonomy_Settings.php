<?php
/**
 * Taxonomy_Settings Class.
 *
 * @package splash-fields
 */

namespace Splash_Fields;

/**
 * Class Taxonomy_Settings.
 * 
 * @property string $id             Splash_Fields\Taxonomy_Settings Object ID.
 * @property string $taxonomy       Array of taxonomies to add fields to.
 * @property string $title          Taxonomy_Settings title.
 * @property array  $fields         List of fields.
 */

class Taxonomy_Settings {
    /**
     * Taxonomy_Settings parameters.
     *
     * @var array
     */
    public $taxonomy_settings;

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
    protected $object_type = 'term';

    /**
     * The ID of the Splash_Fields\Taxonomy_Settings Object.
     *
     * @var string
     */
    public $id;

    /**
     * An array of taxonomies.
     *
     * @var array
     */
    public $taxonomy;

    /**
     * The title of the taxonomy settings.
     *
     * @var string
     */
    protected $title;

    /**
     * List of fields related to the Taxonomy Settings object.
     *
     * @var array
     */
    protected $fields = array();

    /**
     * Get the ID of the current term object.
     *
     * @var int
     */
    protected $term_id;

    /**
     * Constructor.
     * Include all relevant scripts and custom fields.
     * 
     */
    public function __construct( array $taxonomy_settings ) {
        $taxonomy_settings  = static::normalize( $taxonomy_settings );
        
        $this->taxonomy_settings    = $taxonomy_settings;
        $this->id                   = $taxonomy_settings['id'];
        $this->title                = $taxonomy_settings['title'];
        $this->taxonomy             = $taxonomy_settings['taxonomy'];

        $this->taxonomy_settings['fields'] = static::normalize_fields( $taxonomy_settings['fields'], $this->get_storage() );
        $this->fields         = $this->taxonomy_settings['fields'];

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
     * Specific hooks for taxonomy settings object. Default is 'post'.
     * This should be extended in subclasses to support meta fields for terms, user, settings pages, etc.
     */
    protected function object_hooks() {
        // Add fields.
        foreach ( $this->taxonomy as $taxonomy ) {
            add_action( "{$taxonomy}_add_form_fields", [ $this, 'show_add' ] );
            add_action( "{$taxonomy}_edit_form_fields", [ $this, 'show_edit' ], 10, 2 );
            add_action( "created_{$taxonomy}",  [ $this, 'save_taxonomy' ] );
            add_action( "edited_{$taxonomy}",  [ $this, 'save_taxonomy' ] );
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
         * @param Taxonomy_Settings $object Meta Box object
         */
        do_action( 'spf_enqueue_scripts', $this );
    }

    public function show_add( $taxonomy ) {
        // Container.
        printf(
            '<div class="%s" data-object-type="%s" data-id="%s">',
            esc_attr( trim( "spf-taxonomy-settings--add" ) ),
            esc_attr( $this->object_type ),
            esc_attr( $this->id )
        );

        // wp_nonce_field( "spf-save-{$this->id}", "nonce_{$this->id}" );
        wp_nonce_field( 'spf_taxonomy_settings_' . $this->id, 'spf_taxonomy_settings_' . $this->id . '_nonce' );

        do_action( 'spf_before', $this );
        do_action( "spf_before_{$this->id}", $this );

        if ( ! empty( $this->title ) ) {
            printf( '<h2>%s</h2>', esc_html( $this->title ) );
        }

        foreach ( $this->fields as $field ) {
            Field::call( 'show', $field, 0 );
        }

        do_action( 'spf_after', $this );
        do_action( "spf_after_{$this->id}", $this );

        // End container.
        echo '</div>';
    }

    public function show_edit( $tag, $taxonomy ) {
        // var_dump($tag);
        if ( ! $this->term_id ) {
            $this->term_id = $this->get_current_term_id( $tag );
        }

        // Container.
        printf(
            '<div class="%s" data-object-type="%s" data-id="%s">',
            esc_attr( trim( "spf-taxonomy-settings--edit" ) ),
            esc_attr( $this->object_type ),
            esc_attr( $this->id )
        );

        // wp_nonce_field( "spf-save-{$this->id}", "nonce_{$this->id}" );
        wp_nonce_field( 'spf_taxonomy_settings_' . $this->id, 'spf_taxonomy_settings_' . $this->id . '_nonce' );

        do_action( 'spf_before', $this );
        do_action( "spf_before_{$this->id}", $this );

        if ( ! empty( $this->title ) ) {
            printf( '<h2>%s</h2>', esc_html( $this->title ) );
        }

        foreach ( $this->fields as $field ) {
            Field::call( 'show', $field, $this->term_id );
        }

        do_action( 'spf_after', $this );
        do_action( "spf_after_{$this->id}", $this );

        // End container.
        echo '</div>';
    }

    /**
     * Save data from taxonomy fields
     *
     * @param int $taxonomy_id Taxonomy_Settings ID.
     */
    public function save_taxonomy( $term_id ) {
        if ( ! $this->validate() ) {
            return;
        }

        $this->saved = true;

        if ( ! $this->term_id ) {
            $this->term_id = $term_id;
        }

        // Before save action.
        do_action( 'spf_before_save_taxonomy', $this->id );
        do_action( "spf_{$this->id}_before_save_taxonomy", $this->id );

        array_map( [ $this, 'save_field' ], $this->fields );

        // After save action.
        do_action( 'spf_after_save_taxonomy', $this->id );
        do_action( "spf_{$this->id}_after_save_taxonomy", $this->id );
    }

    public function save_field( array $field ) {
        // Get Posted Value
        $old = Field::call( 'raw_meta', $field, $this->term_id );

        $new = $_POST[$field['id']];

        if ( isset( $field['multiple'] ) && $field['multiple'] ) {
			$new = wp_unslash( $new );
		}

        $new = Field::call( $field, 'process_value', $new , $this->term_id, $field );

        // update_meta with Storage Class
        Field::call( $field, 'save', $new, $old, $this->term_id, $field );
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

    public static function normalize( $taxonomy_settings ) {
        $default_title = __( 'Taxonomy_Settings Settings', 'splash-fields' );
        $taxonomy_settings      = wp_parse_args( $taxonomy_settings, [
            'title'          => $default_title,
            'id'             => ! empty( $meta_box['title'] ) ? sanitize_title( $meta_box['title'] ) : sanitize_title( $default_title ),
            'taxonomy'       => array(),
            'fields'         => [],
        ] );

        // Make sure the post type is an array and is sanitized.
        // $taxonomy_settings['post_types'] = array_filter( array_map( 'sanitize_key', Arr::from_csv( $taxonomy_settings['post_types'] ) ) );

        return $taxonomy_settings;
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
        if ( ! isset( $_POST['spf_taxonomy_settings_' . $this->id  . '_nonce'] ) ) {
            return $valid;
        }

        $nonce = $_POST['spf_taxonomy_settings_' . $this->id  . '_nonce'];
        
        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, 'spf_taxonomy_settings_' . $this->id  ) ) {
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

    public function get_current_term_id( $tag ) {
        return $tag->term_id;
    }
}