# TODOS

## TODO

### Misc
- Make sure `$field['field_name']` exists for all fields - crucial for Repeater to work
- Check when json_encoding starts for Checkbox_List, File, Image, Repeater
    - process_value
        - value
        - sanitize
    - save
- `file` and `image` attribute `allowed-types`
- `file` inconsistent save bug - check 
- `Error` Class and handling - Write a value to trigger error for testing purposes
- `select:multiple` Use serialized array instead of adding multiple metakeys of the same name

------------------------------------------

## QA
### Needs QA:
- PHP - Replace `maybe_serialize` with `json_encode` and `maybe_unserialize` with `json_decode`
- Refactor `Field::html_input()` function  
- `spf_get( 'field_id' )` function  
    - post_meta
    - user_meta
    - term_meta
    - options
- `checkbox-list` serialize saved value  

### Testing Protocol:
Test each field (12) with every object type (5)  
*Repeater will need testing with 11 fields within*

#### Test 1st ->
- Gutenberg Sidebar


#### Object Types
- Metabox
- Option Page
- User
- Taxonomy
- Gutenberg Sidebar

#### Fields
- Checkbox
- Checkbox List
- Editor
- File
- Image
- Input
- Number
- Radio
- Select
- Text
- Textarea
- Repeater

#### Get Fields
- `spf_get( 'field_id' )` function  
    - post_meta
    - user_meta
    - term_meta
    - options

------------------------------------------

## Test Error
Use `spf-error` as value

------------------------------------------

## Maybe

### Option_Page
- decide to use settings API or create own settings with options API?

### `Request` Class 
To grab POST and GET form data  
See:  
- request.php
- rwmb_request


# Scouty
Page Title
Troubleshoot "Elementor"
Remove from menu light/dark toggle
