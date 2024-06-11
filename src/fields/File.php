<?php
/**
 * File Class.
 *
 * @package splash-fields
 */

namespace Splash_Fields\Fields;

/**
 * Class File.
 */
class File extends Input {
    public static function admin_enqueue_scripts() {
        // Register script for Media Field
        wp_register_script(
            'spf-file-js',
            SPF_ASSETS_URL . '/js/file.js',
            array('jquery'),
            false,
            true
        );

        wp_localize_script(
            'spf-file-js',
            'spfFileField',
            array(
                'ajaxurl' => admin_url('admin-ajax.php')
            )
        );

        wp_enqueue_script('spf-file-js');
    }

    /**
     * Show field HTML
     *
     * @param array $field Field parameters.
     * @param int $post_id Post ID.
     *
     * @return mixed
     */
    public static function show(array $field, $post_id = 0) {
        var_dump($field); // Correctly showing the field parameters
        $meta = static::raw_meta($post_id, $field);
        $html = sprintf('<div class="spf-field spf-field-%s">', esc_attr($field['type']));
        $html .= static::html($field, $meta); // Correct order of parameters
        $html .= '</div>';
        echo $html;
    }

    public static function add_actions() {
        add_action('post_edit_form_tag', [__CLASS__, 'post_edit_form_tag']);
        add_action('wp_ajax_spf_file_error', [__CLASS__, 'ajax_error']);
    }

    public static function post_edit_form_tag() {
        echo ' enctype="multipart/form-data"';
    }

    public static function ajax_error() {
        wp_send_json_error($_POST['message']);
    }

    static public function file_add_id($field_id) {
        return "file-add-{$field_id}";
    }

    static public function html_file($meta) {
        $file = json_decode($meta, true);
        $i18n_delete = apply_filters('spf_file_delete_string', _x('Delete', 'file upload', 'splash-fields'));
        $i18n_edit = apply_filters('spf_file_edit_string', _x('Edit', 'file upload', 'splash-fields'));

        return sprintf(
            '<div class="spf-file">
                <div class="spf-file__icon">%s</div>
                <div class="spf-file__info">
                    <a href="%s" target="_blank" class="spf-file__title">%s</a>
                    <div class="spf-file__name">%s</div>
                    <div class="spf-file__actions">
                        %s
                        <a href="#" class="spf-file__delete" data-attachment_id="%s">%s</a>
                    </div>
                </div>
            </div>',
            wp_get_attachment_image($file['id'], [48, 64], true),
            esc_url($file['url']),
            esc_html($file['name']),
            esc_html($file['name']),
            self::edit_link($file['id'], $i18n_edit),
            esc_attr($file['id']),
            esc_html($i18n_delete)
        );
    }

    static private function edit_link($id, $text) {
        $edit_link = get_edit_post_link($id);
        return $edit_link ? sprintf('<a href="%s" class="spf-file__edit" target="_blank">%s</a>', $edit_link, $text) : '';
    }

    /**
     * HTML and functionality to add/update/delete file
     *
     * @param array $field Field parameters.
     * @param array $meta Meta value.
     *
     * @return string
     */
    public static function html_input($field, $meta) {
        $file_add_name = self::file_add_id($field['id']);
        $file_add_class = "spf-file__add";

        $file_add_attributes = ' type="file" id="' . $file_add_name . '" name="' . $file_add_name . '" class="' . $file_add_class . '"';

        $has_file = !empty($meta);
        $delete_file_hide = $has_file ? '' : ' hide';
        $add_file_hide = $has_file ? ' hide' : '';

        $output = '<div class="spf-field__input">';
        $output .= '<div class="spf-file__file-container">';
        if ($has_file) {
            $output .= self::html_file($meta);
        }
        $output .= '</div>';
        $output .= sprintf(
            '<input %s %s/>',
            $add_file_hide,
            $file_add_attributes,
            esc_html__('Add File', 'spf')
        );
        $output .= sprintf(
            '<input id="%s" class="spf-file__file-data" name="%s" type="hidden" value="%s" />',
            esc_attr($field['id']),
            esc_attr($field['id']),
            esc_attr($meta)
        );
        if (isset($field['description']) && strlen($field['description']) > 0) {
            $output .= sprintf('<p class="spf-field__description">%s</p>', esc_html($field['description']));
        }
        $output .= '</div>';
        return $output;
    }

    /**
     * Process the submitted value before saving into the database.
     *
     * @param mixed $value The submitted value.
     * @param int $object_id The object ID.
     * @param array $field The field settings.
     *
     * @return string JSON-encoded array with file data or empty string if there is nothing.
     */
    public static function process_value($value, $object_id, array $field) {
        // Decode the JSON string to an array
        $decoded_value = json_decode(stripslashes($value), true);

        // Check if json_decode returned null due to invalid JSON
        if (is_null($decoded_value)) {
            error_log('Invalid JSON data: ' . print_r($value, true));
            return '';
        }

        // Ensure $decoded_value is an array
        if (is_array($decoded_value)) {
            // If a new file is uploaded, handle it
            $file_add_id = self::file_add_id($field['id']);
            if (!empty($_FILES[$file_add_id]['name'])) {
                $attachment_id = media_handle_upload($file_add_id, $object_id);
                if (is_wp_error($attachment_id)) {
                    $error_message = $attachment_id->get_error_message();
                    self::error_message($error_message);
                    error_log(print_r($error_message, true));
                    return '';
                } else {
                    $decoded_value['id'] = $attachment_id;
                    $decoded_value['url'] = wp_get_attachment_url($attachment_id);
                    $decoded_value['name'] = get_the_title($attachment_id);
                    $decoded_value['type'] = get_post_mime_type($attachment_id);
                }
            }

            // Sanitize each element in the array
            $sanitized_value = array_map('sanitize_text_field', $decoded_value);

            // Re-encode the array to a JSON string
            return wp_json_encode($sanitized_value);
        } else {
            // Log an error if the decoded value is not an array
            error_log('Decoded value is not an array: ' . print_r($decoded_value, true));
            return '';
        }
    }

    public static function error_message($message) {
        echo "<p class='spf-field__error'>{$message}</p>";
    }
}
