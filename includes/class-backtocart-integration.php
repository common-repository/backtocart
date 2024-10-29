<?php
/**
 * BackToCart ApiKey Inintegration
 */

if( isset($_POST['b2c_consumer_key']) && !empty($_POST['b2c_consumer_key']) && isset($_POST['b2c_consumer_secret']) && !empty($_POST['b2c_consumer_secret'])) {

    $b2c_consumer_key = trim( strip_tags( $_POST['b2c_consumer_key'] ) );
    $b2c_consumer_secret = trim( strip_tags( $_POST['b2c_consumer_secret'] ) );
    b2c_check_integrate_api_key( $b2c_consumer_key, $b2c_consumer_secret );
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
 * set api key
 */
function b2c_check_integrate_api_key( $b2c_consumer_key, $b2c_consumer_secret )
{

    b2c_include_main_files();

    global $wpdb;
    $wpdb_prefix = $wpdb->prefix;

    $data = "SELECT key_id FROM " . $wpdb_prefix . "woocommerce_api_keys WHERE consumer_key='" . $b2c_consumer_key . "' AND consumer_secret='" . $b2c_consumer_secret . "'";
    $get_results = $wpdb->get_results($data);
    if(count($get_results) === 0){
        return 'wrong api key';
    }else if( $get_results[0]->key_id ) {
        $user_id = get_current_user_id();
        $domain = b2c_get_domain_url();

        b2c_send_integrated_api_key( $b2c_consumer_key, $b2c_consumer_secret, $domain, $user_id );
    }
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
        return "Not appropriate url";
    }
}

/**
 * get domain url
 */
function b2c_send_integrated_api_key( $b2c_consumer_key, $b2c_consumer_secret, $domain, $user_id ) {
    global $wpdb;
    $wpdb_prefix = $wpdb->prefix;

    $data = array(
        'consumer_key' => $b2c_consumer_key,
        'consumer_secret' => $b2c_consumer_secret,
        'domain' => $domain,
        'user_id' => $user_id
    );

    $request = array(
        'method' => 'POST',
        'body' => $data,
    );

    $response = wp_remote_post( 'https://woo.backtocart.co/addKey', $request );

    if( is_wp_error($response) ) {
        $error_message = $response->get_error_message();
    } else {
        if( strtolower ($response['response']['message']) === 'ok'){

            $wpdb->update(
                $wpdb_prefix . 'b2c_cart_options',
                array(
                    'b2c_cart_val' => 'true',
                    'b2c_cart_time' => the_date('Y-m-d'),
                ),
                array('b2c_cart_key' => 'apikey_integration')
            );
        }else{
            $wpdb->update(
                $wpdb_prefix . 'b2c_cart_options',
                array(
                    'b2c_cart_val' => 'false',
                    'b2c_cart_time' => the_date('Y-m-d'),
                ),
                array('b2c_cart_key' => 'apikey_integration')
            );
        }
    }
}
