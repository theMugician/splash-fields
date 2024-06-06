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
            array('jquery'),
            false,
            true
        );

        wp_enqueue_script('spf-repeater-js');
    }

    /**
     * Display the repeater field.
     *
     * @param array $field
     * @param int $post_id
     */
    public static function show(array $field, $post_id = 0) {
        // Retrieve meta data
        $meta = static::raw_meta( $post_id, $field );
        $meta = is_array($meta) ? $meta : [];

        $html = sprintf('<div class="spf-field spf-field-%s" data-field-id="%s">', $field['type'], $field['id']);
        $html .= '<div class="spf-repeater-wrapper">';    
        // Display existing groups if they exist
        if (!empty($meta)) {
            foreach ($meta as $index => $group_meta) {
                $html .= static::render_repeater_group($field, $group_meta, $index);
            }
        }
        $html .= '</div>';
        $html .= '<button type="button" class="button spf-add-repeater-row">Add Row</button>';
        $html .= '</div>';

        // Add a hidden template for repeater groups
        $html .= '<script type="text/template" id="spf-repeater-template">';
        $html .= static::render_repeater_group($field, [], 0);
        $html .= '</script>';

        echo $html;
    }

    /**
     * Render a repeater group.
     *
     * @param array $field
     * @param array $group_meta
     * @param int $index
     * @return string
     */
    public static function render_repeater_group($field, $group_meta, $index) {
        $group_html = '<div class="spf-repeater-group">';

        foreach ($field['fields'] as $sub_field) {
            $sub_field['field_name'] = sprintf('%s[%d][%s]', $field['id'], $index, $sub_field['id']);
            $sub_field_meta = $group_meta[$sub_field['id']] ?? '';

            $group_html .= static::show_sub_field($sub_field, $sub_field_meta);
        }

        $group_html .= '<button type="button" class="button spf-delete-repeater-row">Remove</button>';
        $group_html .= '</div>';

        return $group_html;
    }

    /**
     * Display a sub-field.
     *
     * @param array $field
     * @param mixed $meta
     * @return string
     */
    protected static function show_sub_field($field, $meta) {
        $field = Field::call('normalize', $field);
        $meta  = static::get_default($field, $meta);

        $html = sprintf('<div class="spf-sub-field spf-sub-field-%s">', $field['type']);
        $html .= Field::call('html', $field, $meta);
        $html .= '</div>';

        return $html;
    }

    /**
     * Process the value of the repeater field.
     *
     * @param mixed $value
     * @param int $post_id
     * @param array $field
     * @return array
     */
    public static function process_value($value, $post_id, $field) {
        if (empty($value) || !is_array($value)) {
            return [];
        }

        $processed_value = [];

        foreach ($value as $group_index => $group_values) {
            $processed_group = [];

            foreach ($field['fields'] as $sub_field) {
                $sub_field_id = $sub_field['id'];
                $sub_field_value = $group_values[$sub_field_id] ?? '';

                // Assuming all fields are simple text fields, process accordingly
                $processed_group[$sub_field_id] = Field::call( $sub_field, 'process_value', $sub_field_value , $post_id, $sub_field );

                // $processed_group[$sub_field_id] = sanitize_text_field($sub_field_value);
            }

            $processed_value[] = $processed_group;
        }

        return $processed_value;
    }

    /**
     * Save the repeater field data.
     *
     * @param mixed $new
     * @param mixed $old
     * @param int $post_id
     * @param array $field
     */
    public static function save($new, $old, $post_id, $field) {
        if (empty($field['id'])) {
            return;
        }

        $name = $field['id'];
        $storage = $field['storage'];

        if (empty($new)) {
            $storage->delete($post_id, $name);
            return;
        }

        $storage->update($post_id, $name, $new);
    }

}
