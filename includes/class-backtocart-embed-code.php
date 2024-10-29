<?php
/**
 * BackToCart Embed Code.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Add Embed Code
 **/
function b2c_embed_code() {
    wp_enqueue_script('b2c_embed_js_code', 'https://static.backtocart.co/embed/bundle.js', array(), date('Ymd-His') );
}
add_action('wp_enqueue_scripts', 'b2c_embed_code');
