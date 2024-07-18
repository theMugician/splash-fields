# TODOS

## TODO

### Misc
- `file` and `image` attribute `allowed-types`
- `Error` Class and handling - Write a value to trigger error for testing purposes
- `select:multiple` Use serialized array instead of adding multiple metakeys of the same name

------------------------------------------

## ERRORS & BUGS
### TODO
1. Meta_Box - The toolbar shows up on the top and bottom when Editor is first loaded in the Repeater
2. `wp.editPost.PluginSidebar is deprecated since version 6.6. Please use wp.editor.PluginSidebar instead.`
3. PluginSidebar bug with File and Image
    - Delete Image and File meta from database
    - `Image` - Add an image and leave `File` empty
    - Click Save
    - DB - Image contains JSON string info of image while File contains `[]`
    * The data only gets saved when there has been a change in one of the fields
    * When you click remove image or file it and click save it will delete that meta from DB

### DONE
Decide on outputted empty value: `""`, `"[]"`, `Array` or what? 
1. Event listener needs to be added to new image and file buttons when they get added to repeater group. - Will probably solve most of the repeater bugs. 
2. If file is empty this error pops up - File::sanitize JSON Error: Syntax error  
3. Refactor `$new = $_POST[$field['id']];` Error comes up when field value is empty/unselected. 
PHP Warning:  Undefined array key "spf-meta-boxes-radio" in /Applications/MAMP/htdocs/sandbox/wp-content/plugins/splash-fields/src/Meta_Box.php on line 247 
4. Repeater > Image/File - Check how JSON string is being saved in Repeater. Meta not being outputted because of incorrect JSON string.
5. Setting a file and/or image within Repeater saves bad value (null, empty)? in Repeater
`[11-Jul-2024 20:09:40 UTC] File::sanitize JSON Error: Syntax error`
`[11-Jul-2024 20:09:40 UTC] Image::sanitize JSON Error: Syntax error`
6. Repeater field with Editor field breaks page. No CSS is being loaded.
7. Repeater $meta JSON string is not being decoded when it includes an editor value with an image. Even though the JSON string looks correct.
`Repeater.php line 62`
8. <File>
    SyntaxError: Unexpected end of JSON input
        at JSON.parse (<anonymous>)
        at http://localhost:8888/sandbox/wp-content/plugins/splash-fields/assets/js/plugin-sidebar.js?ver=6.6:2:5681

## QA
### Needs QA:
- Make sure `$field['field_name']` exists for all fields - crucial for Repeater to work
- Check when json_encoding starts for Checkbox_List, File, Image, Repeater
    - process_value
        - value
        - sanitize
    - save
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
    - Post ✅
    - Page ✅
    - CPT  ✅
- Option Page 🛠
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
    - post_meta 🛠
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
