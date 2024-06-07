<?php

// Getting field value

// Inside a post loop or template where global $post is available
$field_value = spf_get_field( 'my_custom_field' ); // Defaults to 'post'

// Explicitly get a post meta value by post ID
$post_meta_value = spf_get_field( 'my_custom_field', 'post', $post_id );

// Explicitly get a user meta value by user ID
$user_meta_value = spf_get_field( 'my_custom_field', 'user', $user_id );

// Explicitly get a term meta value by term ID
$term_meta_value = spf_get_field( 'my_custom_field', 'term', $term_id );

// Explicitly get an option value
$option_value = spf_get_field( 'my_custom_field', 'option' );

// Automatically detect context (with fallback to 'post')
$meta_value = spf_get_field( 'my_custom_field', null, $object_id );