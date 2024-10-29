<?php
/**
 * BackToCart Init User Type
 */

if( isset($_POST['b2c_user_type']) && !empty($_POST['b2c_user_type']) ) {
	$b2c_user_type = trim( strip_tags( $_POST['b2c_user_type'] ) );

    if( $b2c_user_type === 'check' ) {
        b2c_check_current_user_type();
    }else{
        return 'Access denied';
    }
} else {
	return 'Access denied';
}

/**
 * include main files
 */
function b2c_include_main_files() {
	include_once $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';
}

/**
 * get current user
 */
function b2c_check_current_user_type() {

    b2c_include_main_files();

    if( is_user_logged_in() ) {
        echo get_current_user_id();
    }else{
        echo 'unknown';
    }
}
