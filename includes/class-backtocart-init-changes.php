<?php
/**
 * BackToCart Init Cart Changes
 */

if( isset($_POST['b2c_change_type']) && !empty($_POST['b2c_change_type']) && isset($_POST['b2c_user_init']) && !empty($_POST['b2c_user_init']) ) {

	$b2c_change_type = trim( strip_tags( $_POST['b2c_change_type'] ) );
	$b2c_user_init = trim( strip_tags( $_POST['b2c_user_init'] ) );
	$b2c_change_date = date('Y-m-d H:i:s');

    $checked_type = b2c_check_got_type( $b2c_change_type, $b2c_user_init, $b2c_change_date );

	if( $checked_type === 'wrong_type' ) {
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
	include_once $_SERVER['DOCUMENT_ROOT'] . '/wp-settings.php';
}

/**
 * check event type
 */
function b2c_check_got_type( $change_type, $user_init, $change_date ) {

	switch($change_type) {
		case 'b2c_cart':
			b2c_get_cart_changes('b2c_cart', b2c_check_user_exist(), $user_init, $change_date);
			break;
		case 'b2c_single_cart':
			b2c_get_cart_changes('b2c_single_cart', b2c_check_user_exist(), $user_init, $change_date);
			break;
		case 'b2c_remove_cart':
			b2c_get_cart_changes('b2c_remove_cart', b2c_check_user_exist(), $user_init, $change_date);
			break;
		default:
			return 'wrong_type';
	}
}

/**
 * check user registered or guest
 */
function b2c_check_user_exist() {

	b2c_include_main_files();

	$user_exist = get_current_user_id();
	if( $user_exist == 0 ) {
		return false;
	} else {
		return true;
	}
}

/**
 * get cookie id for guest
 */
function b2c_get_cookie_name() {
    foreach($_COOKIE as $key=>$value) {
		if (strpos($key, 'wp_woocommerce_session_') !== false) {
			$pieces = explode('||', $value);
			return $pieces[0];
		}
	}
}

/**
 * get current DB changes
 */
function b2c_get_cart_changes( $change_type, $registered_user, $user_init, $change_date ) {
	global $wpdb;
	$wpdb_prefix = $wpdb->prefix;

	if( $registered_user ) {
		$data = "SELECT meta_value FROM " . $wpdb_prefix . "usermeta WHERE meta_key='_woocommerce_persistent_cart_1' AND user_id='" . get_current_user_id() . "'";
	} else {
		$session_key = b2c_get_cookie_name();
		$data = "SELECT session_value FROM " . $wpdb_prefix . "woocommerce_sessions WHERE session_key='" . $session_key . "'";
	}

	sleep(1);
	$get_results = $wpdb->get_results( $data );

    if( $registered_user ) {
        $change_data = $get_results[0] -> meta_value;
    } else {
        $change_data = $get_results[0] -> session_value;
    }

	b2c_send_changes_cart( $change_type, $change_data, $user_init, $change_date );
}

/**
 * get domain url
 */
function b2c_get_domain_url() {

    if( preg_match("/^([-a-z0-9]{2,100})\.([a-z\.]{2,8})$/i", parse_url(get_site_url(), PHP_URL_HOST)) ) {
        return parse_url(get_site_url(), PHP_URL_HOST);
    }elseif( preg_match("/^([-a-z0-9]{2,100})\.([a-z\.]{2,8})$/i", parse_url(get_home_url(), PHP_URL_HOST)) ) {
        return parse_url(get_home_url(), PHP_URL_HOST);
    }elseif( preg_match("/^([-a-z0-9]{2,100})\.([a-z\.]{2,8})$/i", parse_url(site_url(), PHP_URL_HOST)) ) {
        return parse_url(site_url(), PHP_URL_HOST);
    }elseif( preg_match("/^([-a-z0-9]{2,100})\.([a-z\.]{2,8})$/i", parse_url(get_bloginfo('url'), PHP_URL_HOST)) ) {
        return parse_url(get_bloginfo('url'), PHP_URL_HOST);
    }elseif( preg_match("/^([-a-z0-9]{2,100})\.([a-z\.]{2,8})$/i", parse_url(get_option('home'), PHP_URL_HOST)) ) {
        return parse_url(get_option('home'), PHP_URL_HOST);
    }else {
        return 'Not appropriate url';
    }
}

/**
 * send cart changes to analyze
 */
function b2c_send_changes_cart( $change_type, $change_data, $user_init, $change_date ) {
    $domain = b2c_get_domain_url();
	$data = array(
		'change_name' => $change_type,
		'change_data' => $change_data,
		'user_init' => $user_init,
		'change_date' => $change_date,
		'user_domain' => $domain
	);

	$request = array(
		'method' => 'POST',
		'body' => $data,
	);

	$response = wp_remote_post( 'https://woo.backtocart.co/analyzeData', $request );

	if ( is_wp_error($response) ) {
		$error_message = $response->get_error_message();
	} else {
        $success_message = $response;
	}
}
