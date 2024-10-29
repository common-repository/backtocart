<?php
/**
 * BackToCart setup
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Main BackToCart Class.
 *
 * @class BackToCart
 */
final class BackToCart {

    /**
     * BackToCart version.
     */
    public $version = '2.0.0';

	/**
	 * The single instance of the class.
	 */
	protected static $_instance = null;

	/**
	 * Access Embed Code.
	 */
	public $b2c_code_access;

	/**
	 * Access Embed Code.
	 */
	public $b2c_key_access;

	/**
	 * Cart instance.
	 */
	public $cart = null;

	/**
	 * Main BackToCart Instance.
	 *
	 * @return BackToCart - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * BackToCart Constructor.
	 */
	public function __construct() {
	    $this->wpdb_activate();
		$this->define_constants();
        $this->b2c_code_access = $this->ckeck_embed_code();
        $this->b2c_key_access = $this->ckeck_integration();
	}

    /**
     * Option table for Backtocart.
     */
    function wpdb_activate() {
        global $wpdb;

        $wcap_collate = '';
        if ( $wpdb->has_cap( 'collation' ) ) {
            $wcap_collate = $wpdb->get_charset_collate();
        }
        $cart_table_name = $wpdb->prefix . 'b2c_cart_options';

        $sql_cart = "CREATE TABLE IF NOT EXISTS $cart_table_name (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `b2c_cart_key` varchar(200) COLLATE utf8_general_ci NOT NULL,
                    `b2c_cart_val` text COLLATE utf8_general_ci NOT NULL,
                    `b2c_cart_time` TIMESTAMP,
                    PRIMARY KEY (`id`)
                    ) $wcap_collate AUTO_INCREMENT=1 ";

        require_once ( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql_cart );
    }

	/**
	 * Define B2C Constants.
	 */
	private function define_constants() {
		$this->define( 'B2C_ABSPATH', dirname( B2C_PLUGIN_FILE ) );
		$this->define( 'B2C_EMBED_CODE', dirname( B2C_PLUGIN_FILE ) . '/includes/class-backtocart-embed-code.php' );
	}

	/**
	 * Check Status Of Embed Code.
	 */
	private function ckeck_embed_code() {
        global $wpdb;
        $wpdb_prefix = $wpdb->prefix;
        $data = "SELECT	b2c_cart_val FROM " . $wpdb_prefix . "b2c_cart_options WHERE b2c_cart_key='status_embed_code'";
        $get_results  = $wpdb->get_results( $data );

        if( count($get_results) === 0 ) {
            $wpdb->insert(
                $wpdb_prefix . 'b2c_cart_options',
                array(
                    'b2c_cart_key' => 'status_embed_code',
                    'b2c_cart_val' => 'false',
                    'b2c_cart_time' => the_date('Y-m-d'),
                ),
                array( '%s', '%s', '%d' )
            );
            return 'false';
        }else{
            if($get_results[0]->b2c_cart_val === 'false') {
                return 'false';
            }else{
                include_once B2C_EMBED_CODE;
                return 'true';
            }
        }
	}

    /**
     * Check Integration With Woocommerce.
     */
    private function ckeck_integration() {
        global $wpdb;
        $wpdb_prefix = $wpdb->prefix;
        $data = "SELECT	b2c_cart_val FROM " . $wpdb_prefix . "b2c_cart_options WHERE b2c_cart_key='apikey_integration'";
        $get_results  = $wpdb->get_results( $data );

        if( count($get_results) === 0 ) {
            $wpdb->insert(
                $wpdb_prefix . 'b2c_cart_options',
                array(
                    'b2c_cart_key' => 'apikey_integration',
                    'b2c_cart_val' => 'false',
                    'b2c_cart_time' => the_date('Y-m-d'),
                ),
                array( '%s', '%s', '%d' )
            );
            return 'false';
        }else{
            if( $get_results[0]->b2c_cart_val === 'false' ) {
                return 'false';
            }else{
                return 'true';
            }
        }
    }

	/**
	 * Define constant if not already set.
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Check the active theme.
	 */
	private function is_active_theme( $theme ) {
		return get_template() === $theme;
	}
}
