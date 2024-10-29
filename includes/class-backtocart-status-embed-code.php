<?php
/**
 * BackToCart Status Of Embed Code.
 */

if( isset($_POST['b2c_embed_code_status']) && !empty($_POST['b2c_embed_code_status']) ) {
    $b2c_embed_code_status = trim( strip_tags( (string)$_POST['b2c_embed_code_status'] ) );

    if( $b2c_embed_code_status == 'true' ) {
        b2c_store_embed_code_status( 'true' );
    }else if( $b2c_embed_code_status == 'false' ) {
        b2c_store_embed_code_status( 'false' );
    }else{
        return 'Access denied';
    }
}else{
    return 'Access denied';
}

/**
 * include main files
 */
function b2c_include_main_files() {
    include_once $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';
}

/**
 * Store status of embed code.
 */
function b2c_store_embed_code_status( $b2c_embed_code_status ) {
    b2c_include_main_files();

    global $wpdb;
    $wpdb_prefix = $wpdb->prefix;

    $update = $wpdb->update(
        $wpdb_prefix . 'b2c_cart_options',
        array(
            'b2c_cart_val' => $b2c_embed_code_status,
            'b2c_cart_time' => the_date('Y-m-d'),
        ),
        array('b2c_cart_key' => 'status_embed_code')
    );
    return $update;
}
