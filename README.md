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
        'title'      => 'SPF Options Page',
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
        'title'      => 'SPF Taxonomy Settings',
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
- Editor *In development*
- File
- Image
- Input
- Number
- Radio
- Select
- Text
- Textarea

# Roadmap
- Detailed document
- Fix Editor within Repeater Bug
- File and Image field `allowed_types` parameter