<?php
/**
 * Repeater Field Class.
 *
 * @package splash-fields
 */

namespace Splash_Fields\Fields;
use Splash_Fields\Field;

class Repeater extends Input {
    public static function admin_enqueue_scripts() {
		// Register script for Media Field

		wp_register_script(
			'spf-repeater-js',
			SPF_ASSETS_URL . '/js/repeater.js',
			array( 'jquery' ),
			false,
			true 
		);

        wp_enqueue_script( 'spf-repeater-js' );
	}

    public static function show( array $field, $post_id = 0 ) {
        $meta = static::raw_meta( $post_id, $field );
        $meta = static::get_default( $field, $meta );
        $html = sprintf( '<div class="spf-field spf-field-%s" data-field-id="%s">', $field['type'], $field['id'] );
        $html .= '<div class="spf-repeater-wrapper">';

        if ( is_array( $meta ) && ! empty( $meta ) ) {
            foreach ( $meta as $index => $group_meta ) {
                $html .= static::render_repeater_group( $field, $group_meta, $index );
            }
        } else {
            $html .= static::render_repeater_group( $field, [], 0 );
        }

        $html .= '</div>';
        $html .= '<button type="button" class="button spf-add-repeater-row">Add Row</button>';
        $html .= '</div>';

        echo $html;
    }

    protected static function render_repeater_group( $field, $group_meta, $index ) {
        $group_html = '<div class="spf-repeater-group">';

        foreach ( $field['fields'] as $sub_field ) {
            $sub_field['field_name'] = sprintf('%s[%d][%s]', $field['id'], $index, $sub_field['id']);
            $sub_field_meta = isset( $group_meta[$sub_field['id']] ) ? $group_meta[$sub_field['id']] : '';

            $group_html .= static::show_sub_field( $sub_field, $sub_field_meta );
        }

        $group_html .= '<button type="button" class="button spf-delete-repeater-row">Remove</button>';
        $group_html .= '</div>';

        return $group_html;
    }

    protected static function show_sub_field( $field, $meta ) {
        $field = Field::call( 'normalize', $field );

        $meta  = static::get_default( $field, $meta );

        $html = sprintf( '<div class="spf-sub-field spf-sub-field-%s">', $field['type'] );

        $html .= Field::call( 'html', $field, $meta );

        $html .= '</div>';

        return $html;
    }

    public static function save( $new, $old, $post_id, $field ) {
        if ( empty( $field['id'] ) ) {
            return;
        }

        $name    = $field['id'];
        $storage = $field['storage'];

        if ( empty( $new ) ) {
            $storage->delete( $post_id, $name );
            return;
        }

        if ( is_array( $new ) ) {
            $cleaned_values = [];

            foreach ( $new as $group_index => $group_values ) {
                $cleaned_group = [];

                foreach ( $field['fields'] as $sub_field ) {
                    $sub_field_id = $sub_field['id'];
                    $sub_field_value = isset( $group_values[$sub_field_id] ) ? $group_values[$sub_field_id] : '';

                    $cleaned_group[$sub_field_id] = sanitize_text_field( $sub_field_value );
                }

                $cleaned_values[] = $cleaned_group;
            }

            $storage->update( $post_id, $name, $cleaned_values );
        }
    }
}
