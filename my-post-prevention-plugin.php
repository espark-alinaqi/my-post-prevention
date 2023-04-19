<?php
/*
Plugin Name: My Post Prevention Plugin
Plugin URI: https://example.com/my-post-prevention-plugin
Description: Prevents post status change for posts of type "post".
Version: 1.0
Author: Your Name
Author URI: https://example.com/
License: GPL2
*/
function prevent_post_change( $data, $postarr ) {
    if ( ! isset($postarr['ID']) || ! $postarr['ID'] ) return $data;
    if ( $postarr['post_type'] !== 'post' ) return $data; // only for posts
    $old = get_post($postarr['ID']); // the post before update
    if (
        $old->post_status !== 'incomplete' &&
        $old->post_status !== 'trash' && // without this post restoring from trash fail
        $data['post_status'] === 'publish' 
    ) {
        // set post to incomplete before being published
        $data['post_status'] = 'incomplete';
    }
    return $data;
}
add_filter( 'wp_insert_post_data', 'prevent_post_change', 20, 2 );
