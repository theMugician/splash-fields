<?php
/**
 * Repeater Field Class.
 *
 * @package splash-fields
 */

namespace Splash_Fields\Fields;

use Splash_Fields\Field;

class Repeater extends Input {

    /**
     * Enqueue scripts for the repeater field.
     */
    public static function admin_enqueue_scripts() {
        wp_register_script(
            'spf-repeater-js',
            SPF_ASSETS_URL . '/js/repeater.js',
            array( 'jquery' ),
            false,
            true
        );

        wp_enqueue_script( 'spf-repeater-js' );
    }

    /**
     * Display the repeater field.
     *
     * @param array $field   Field configuration.
     * @param int   $post_id Post ID.
     */
    public static function html( $field, $meta  ) {
        $html = '<label class="spf-field__label" for="' . esc_attr( $field['id'] ) . '">' . esc_html( $field['name'] ) . '</label>';
        $html .= '<div class="spf-repeater-wrapper">';

        // Display existing groups if they exist.
        if ( ! empty( $meta ) ) {
            foreach ( $meta as $index => $group_meta ) {
                $html .= static::render_repeater_group( $field, $group_meta, $index );
            }
        }

        $html .= '</div>';
        $html .= '<button type="button" class="button spf-add-repeater-row">Add Row</button>';

        // Add a hidden template for repeater groups.
        $html .= '<script type="text/template" class="spf-repeater-template">';
        $html .= static::render_repeater_group( $field, array(), 0 );
        $html .= '</script>';

        return $html; // WPCS: XSS ok.

    }

    /**
     * Render a repeater group.
     *
     * @param array $field      Field configuration.
     * @param array $group_meta Group metadata.
     * @param int   $index      Group index.
     * @return string
     */
    public static function render_repeater_group( $field, $group_meta, $index ) {
        $group_number = $index + 1;
        $group_html   = '<div class="spf-repeater-group">';
        $group_html  .= '<h3 class="spf-repeater-group__title">Group <span class="spf-repeater-group__number">' . esc_html( $group_number ) . '</span></h3>';

        foreach ( $field['fields'] as $sub_field ) {
            $sub_field['field_name'] = sprintf( '%s[%d][%s]', $field['id'], $index, $sub_field['id'] );
            $sub_field_meta = isset( $group_meta[ $sub_field['id'] ] ) ? $group_meta[ $sub_field['id'] ] : '';

            $group_html .= static::show_sub_field( $sub_field, $sub_field_meta );
        }

        $group_html .= '<a class="spf-delete-repeater-row">Remove</a>';
        $group_html .= '</div>';

        return $group_html;
    }

    /**
     * Display a sub-field.
     *
     * @param array $field Field configuration.
     * @param mixed $meta  Field metadata.
     * @return string
     */
    protected static function show_sub_field( $field, $meta ) {
        $field = Field::call( 'normalize', $field );
        if ( isset( $field['multiple'] ) && $field['multiple'] ) {
            $meta = json_decode( $meta );
            $meta = is_array( $meta ) ? $meta : array();
        }
        $meta  = static::get_default( $field, $meta );

        $html  = sprintf( '<div class="spf-field spf-field-%s">', esc_attr( $field['type'] ) );
        $html .= Field::call( 'html', $field, $meta );
        $html .= '</div>';

        return $html;
    }

    /**
     * Process the value of the repeater field.
     *
     * @param mixed $value   New value.
     * @param int   $post_id Post ID.
     * @param array $field   Field configuration.
     * @return array
     */
    public static function process_value( $value, $post_id, $field ) {
        if ( empty( $value ) || ! is_array( $value ) ) {
            return array();
        }

        $processed_value = array();

        foreach ( $value as $group_index => $group_values ) {
            $processed_group = array();

            foreach ( $field['fields'] as $sub_field ) {
                $sub_field_id    = $sub_field['id'];
                $sub_field_value = isset( $group_values[ $sub_field_id ] ) ? $group_values[ $sub_field_id ] : '';

                // Assuming all fields are simple text fields, process accordingly.
                $processed_group[ $sub_field_id ] = Field::call( $sub_field, 'process_value', $sub_field_value, $post_id, $sub_field );
            }

            $processed_value[] = $processed_group;
        }

        return $processed_value;
    }

    /**
	 * Normalize parameters for field.
	 *
	 * @param array $field Field parameters.
	 * @return array
	 */
	public static function normalize( $field ) {
		$field['multiple'] = true;
		$field             = parent::normalize( $field );

		return $field;
	}
}
