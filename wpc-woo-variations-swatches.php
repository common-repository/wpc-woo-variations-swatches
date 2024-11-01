<?php
/**
 *Plugin Name: Variation Swatches for WooCommerce
 *Plugin URI: https://wordpress.org/plugins/wpc-woo-variations-swatches
 *Description: Build your eCommerce with Beautiful colors, images and label variation swatches for woocommerce product attributes.
 *Version: 1.0.2
 *Author: WpCommerz
 *Author URI: https://wpcommerz.com
 *WC requires at least: 3.0
 *WC tested up to: 5.1.0
 *License: GPLv2 or later
 *Text Domain: wpcvs
*/

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Wpcommerz_Variation' ) ) {
    final class Wpcommerz_Variation {
        /**
         * Plugin version
         *
         * @var string
         */
        public $version = '1.0.2';

        /**
         * Instance of self
         *
         * @var Wpcommerz_Variation
         */
        private static $instance = null;

        /**
         * Minimum PHP version required
         *
         * @var string
         */
        private $min_php = '7.1.0';

        /**
         * Constructor for the Wpcommerz_Variation class
         *
         * Sets up all the appropriate hooks and actions
         * within our plugin.
         */
        private function __construct() {
            require_once __DIR__ . '/vendor/autoload.php';

            $this->define_constants();

            register_activation_hook( __FILE__, [ $this, 'activate' ] );
            register_deactivation_hook( __FILE__, [ $this, 'deactivate' ] );

            add_action( 'woocommerce_loaded', [ $this, 'init_plugin' ] );
            add_action( 'admin_notices', [ $this, 'render_missing_woocommerce_notice' ] );
            add_action( 'admin_notices', [ $this, 'render_version_php_notice' ] );
        }

        /**
         * Load the plugin after Frontend is loaded
         *
         * @return void
         */
        public function init_plugin() {
            $this->init_hooks();
            \Wpcommerz\Variation\Installer::init();
        }

        /**
         * Initialize the actions
         *
         * @return void
         */
        public function init_hooks() {
            // Localize our plugin
            add_action( 'init', [ $this, 'localization_setup' ] );

            add_action( 'plugins_loaded', [ $this, 'after_plugins_loaded' ] );

            add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), [ $this, 'plugin_action_links' ] );
        }

        /**
         * Initializes the Wpcommerz_Variation() class
         *
         * Checks for an existing Wpcommerz_Variation() instance
         * and if it doesn't find one, creates it.
         */
        public static function init() {
            if ( self::$instance === null ) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        public function swatches_setting_slug() {
            return 'wpc-variation-swatches';
        }

        /**
         * Define all constants
         *
         * @return void
        */
        public function define_constants() {
            define( 'WPCVS_PLUGIN_VERSION', $this->version );
            define( 'WPCVS_FILE', __FILE__ );
            define( 'WPCVS_DIR', __DIR__ );
            define( 'WPCVS_INC_DIR', __DIR__ . '/includes' );
            define( 'WPCVS_PLUGIN_ASSEST', plugins_url( 'assets', __FILE__ ) );
            define( 'WPCVS_URL', plugins_url( '', WPCVS_FILE ) );
            define( 'WPCVS_ASSETS', WPCVS_URL . '/assets' );
        }

        /**
         * Executed after all plugins are loaded
         *
         * At this point wpc variation is loaded settings
         *
         * @since 1.0.0
         *
         * @return void
         */
        public function after_plugins_loaded() {
            $installed = get_option( 'wpcvs_installed' );

            if ( ! $installed ) {
                update_option( 'wpcvs_installed', time() );
            }
            update_option( 'wpcvs_version', WPCVS_PLUGIN_VERSION );
        }

        /**
         * Get the plugin path.
         *
         * @return string
         */
        public function plugin_path() {
            return untrailingslashit( plugin_dir_path( __FILE__ ) );
        }

        /**
         * Placeholder for activation function
         */
        public function activate() {
            flush_rewrite_rules();

            $setting = get_option( 'basic_settings_option_name' );

            if ( ! $setting ) {
                update_option( 'basic_settings_option_name', [] );
            }

            if ( ! $this->has_woocommerce() ) {
                set_transient( 'wpcvs_wc_missing_notice', true );
            }

            if ( ! $this->is_supported_php() ) {
                set_transient( 'wpcvs_php_version_notice', true );
                exit;
            }
        }

        /**
         * Placeholder for deactivation function
         *
         * Nothing being called here yet.
         */
        public function deactivate() {
            delete_transient( 'wpcvs_wc_missing_notice', true );
        }

        /**
         * Check whether woocommerce is installed or not
         *
         * @since 1.0.0
         * @return bool
         */
        public function has_woocommerce() {
            return class_exists( 'WooCommerce' );
        }

        /**
         * Check if the PHP version is supported
         *
         * @since 1.0.0
         * @return bool
         */
        public function is_supported_php() {
            if ( version_compare( PHP_VERSION, $this->min_php, '<=' ) ) {
                return false;
            }

            return true;
        }

        /**
         * Initialize plugin for localization
         *
         * @uses load_plugin_textdomain()
         */
        public function localization_setup() {
            load_plugin_textdomain( 'wpcvs' );
        }

        /**
         * Plugin action links
         *
         * @param array $links
         *
         * @since  1.0.0
         * @return array
         */
        public function plugin_action_links( $links ) {
            $links[] = '<a href="' . admin_url( 'admin.php?page=wpc-variation-swatches' ) . '">' . __( 'Settings', 'wpcvs' ) . '</a>';
            return $links;
        }

        /**
         * Missing woocomerce notice
         *
         * @since 1.0.0
         * @return void
         */
        public function render_missing_woocommerce_notice() {
            if ( ! get_transient( 'wpcvs_wc_missing_notice' ) ) {
                return;
            }

            if ( $this->has_woocommerce() ) {
                return delete_transient( 'wpcvs_wc_missing_notice' );
            }

            $plugin_url = self_admin_url( 'plugin-install.php?s=woocommerce&tab=search&type=term' );
            $message = sprintf( 'WPC Variation Swatches requires WooCommerce to be installed and active. You can activate <a href="%s">WooCommerce</a> here.', $plugin_url );
            echo wp_kses_post( sprintf( '<div class="error"><p><strong>%1$s</strong></p></div>', $message ) );
        }

        /**
         * PHP version requirement
         *
         * @since 1.0.0
         * @return void
         */
        public function render_version_php_notice() {
            if ( ! get_transient( 'wpcvs_php_version_notice' ) ) {
                return;
            }

            if ( $this->has_woocommerce() ) {
                return delete_transient( 'wpcvs_php_version_notice' );
            }

            $message = sprintf( 'The Minimum PHP Version Requirement for <b>Wpc Variation Swatches</b> is %1$s. You are Running PHP %2$s', $this->min_php, phpversion() );

            echo wp_kses_post( sprintf( '<div class="error"><p><strong>%1$s</strong></p></div>', $message ) );
        }
    }
}

/**
 * Load Wpcommerz_Variation Plugin when all plugins loaded
 *
 * @return Wpcommerz_Variation
 */
function wpcommerz_variation() {
    return Wpcommerz_Variation::init();
}

// Lets Go....
wpcommerz_variation();
