# Splash Fields
Create custom fields and add them to a metabox, options page, taxonomy page, user settings or gutenberg sidepanel.

# Getting Started

## Meta Box
```php
add_filter( 'spf_meta_boxes', function( $meta_boxes ) {
    $meta_boxes[] = array(
        'title'      => 'SPF Meta Box',
        'fields'     => array(
            array(
                'name' => 'Text',
                'id'   => 'spf-meta-box-test',
                'type' => 'text',
            ),
        ),
    );
    return $meta_boxes;
});
```
## Options Page
```php
add_filter( 'spf_options_pages', function( $options_pages ) {
    $options_pages[] = array(
        'id'            => 'spf-options-page-test',
        'title'         => 'SPF Options Page',
        'menu_title'    => 'SPF Options Page',
        'menu_slug'     => 'spf-options-test',
        'parent_slug'   => 'options-general.php', // Optional - skip it to create top-level menu
        'capability'    => 'manage_options',
        'fields'     => array(
            array(
                'name' => 'Text',
                'id'   => 'spf-options-page-test',
                'type' => 'text',
            ),
        ),
    );
    return $options_pages;
});
```

## User Settings
```php
add_filter( 'spf_user_settings', function( $user_settings ) {
    $user_settings[] = array(
        'title'      => 'SPF User Settings',
        'fields'     => array(
            array(
                'name' => 'Text',
                'id'   => 'spf-user-settings-test',
                'type' => 'text',
            ),
        ),
    );
    return $user_settings;
});
```

## Taxonomy Settings
```php
add_filter( 'spf_taxonomy_settings', function( $taxonomy_settings ) {
    $taxonomy_settings[] = array(
        'id'	    => 'spf-taxonomy-settings-test',
        'title'     => 'SPF Taxonomy Settings',
        'taxonomy'  => array( 'post_tag' ), // Where do you want to show this. Can be an array of multiple taxonomies
        'fields'     => array(
            array(
                'name' => 'Text',
                'id'   => 'spf-taxonomy-settings-test',
                'type' => 'text',
            ),
        ),
    );
    return $taxonomy_settings;
});
```

## Gutenberg Sidepanel
```php
add_filter( 'spf_plugin_sidebars', function( $plugin_sidebars ) {
    $plugin_sidebars[] = array(
        'id'    => 'spf-plugin-sidebars',
        'title'      => 'SPF Plugin Sidebar',
        'fields'     => array(
            array(
                'name' => 'Text',
                'id'   => 'spf-plugin-sidebar-test',
                'type' => 'text',
            ),
        ),
    );
    return $plugin_sidebars;
});
```

# Fields
- Checkbox
- Checkbox List
- Editor
- File
- Image
- Input
- Number
- Radio
- Repeater
- Select
- Text
- Textarea

# Roadmap
- Detailed document
- File and Image field `allowed_types` parameter