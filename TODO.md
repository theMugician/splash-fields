# TODOS

## Fields
### File 
- What to return: attachment_id OR filename OR fileurl?
- ajax error handling

### Select
- add multiple attribute 

### Repeater
- test every field within repeater
- CSS for Options pages, Side Meta Box, User, Term and Gutenberg Sidebar

## TODO
- `Error` Class and handling - Write a value to trigger error for testing purposes
- `select:multiple` Use serialized array instead of adding multiple metakeys of the same name
- `Field::get_value` supercedes `raw_meta` function
    - `repeater` array
    - `checkbox-list` array
    - `image` returns id|url|array or what?
    - `file` returns id|url|array or what?

## QA
### Needs QA:
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

## Test Error
Use `spf-error` as value


## Option_Page
- decide to use settings API or create own settings with options API?

## Gutenberg_Sidebar
- Make class and add fields to it

## Maybe

### `Request` Class 
To grab POST and GET form data  
See:  
- request.php
- rwmb_request


# Scouty
Page Title
Troubleshoot "Elementor"
Remove from menu light/dark toggle
