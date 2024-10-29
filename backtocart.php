<?php
/**
 * Plugin Name: Backtocart Wordpress
 * Plugin URI: https://wordpress.org/plugins/backtocart/
 * Description: This plugin is intended to simplify the process of inserting our embed code into your website, as well as, integrating your eCommerce platforms with your website for our lightboxes to show up and for you to be able to track and analyze the actions of your eCommerce shop visitors.
 * Version: 2.0.0
 * Author: Backtocart
 * Author URI: https://app.backtocart.co/
 *

 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 **/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Define B2C_PLUGIN_FILE.
 **/
if ( ! defined( 'B2C_PLUGIN_FILE' ) ) {
    define( 'B2C_PLUGIN_FILE', __FILE__ );
}

/**
 * Include the main BackToCart class.
 **/
if ( ! class_exists( 'BackToCart' ) ) {
    include_once dirname( __FILE__ ) . '/includes/class-backtocart.php';
}

function b2c_dashboard() {
    add_menu_page( 'Backtocart Page', 'Backtocart', 'manage_options', 'backtocart-plugin', 'b2c_init', '/wp-content/plugins/backtocart/assets/images/favicon.png' );
}

/**
 * Include Admin Dashboard.
 **/
function b2c_init() {
    include_once dirname( __FILE__ ) . '/views/class-backtocart-template-preview.php';
}

/**
 * Add Script To Admin Panel.
 **/
function b2c_load_admin_script() {
    $b2c_admin_js_ver = date('Ymd-His', filemtime( plugin_dir_path( __FILE__ ) . '/assets/js/admin_backtocart.js' ));
    wp_enqueue_script('b2c_admin_js', plugins_url( '/assets/js/admin_backtocart.js', __FILE__ ), array(), $b2c_admin_js_ver );
}
add_action('admin_enqueue_scripts', 'b2c_load_admin_script');

/**
 * Add Style To Admin Panel.
 **/
function b2c_load_admin_style() {
    $b2c_admin_css_ver = date('Ymd-His', filemtime( plugin_dir_path( __FILE__ ) . '/assets/css/admin_backtocart.css' ));
    wp_enqueue_style('b2c_admin_css', plugins_url( '/assets/css/admin_backtocart.css', __FILE__ ), array(), $b2c_admin_css_ver );
}
add_action('admin_enqueue_scripts', 'b2c_load_admin_style');

/**
 * Add Script To User Panel.
 **/
function b2c_load_custom_script() {
    $b2c_js_ver = date('Ymd-His', filemtime( plugin_dir_path( __FILE__ ) . '/assets/js/user_backtocart.js' ));
    wp_enqueue_script('b2c_custom_js', plugins_url( '/assets/js/user_backtocart.js', __FILE__ ), array(), $b2c_js_ver );
}
add_action('wp_enqueue_scripts', 'b2c_load_custom_script');

/**
 * Change Footer Text In Admin Dashboard.
 **/
if (isset($_GET['page']) && 'backtocart-plugin' === $_GET['page']) {
    add_filter('admin_footer_text', 'b2c_foot_monger');
}

/**
 * Changeable Text In Admin Dashboard.
 **/
function b2c_foot_monger() {
    ?>Thank you for using Backtocart<?php
}

/**
 * Main instance of BackToCart.
 */
function b2c() {
    return BackToCart::instance();
}

add_action('admin_menu', 'b2c_dashboard');

/**
 * Global for backwards compatibility.
 */
$GLOBALS['backtocart'] = b2c();

?>