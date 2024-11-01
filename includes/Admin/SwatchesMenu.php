<?php
namespace Wpcommerz\Variation\Admin;

class SwatchesMenu {
    /**
     * Class all register
     *
     * @var array
     */
    public $classes = array();

    /**
     * All register class basename
     *
     * @var array
     */
    public $class_basename = array();

    /**
     * Menu Class handler.
     */
    public function __construct() {
        add_action( 'admin_menu', [ $this, 'wpcvs_admin_menu' ] );

        $this->load_register_file();
        $this->menu_register();
    }

    /**
     * File load all setting register from Register directory
     *
     * Set class name
     * Set class basename
     * @since 1.0.0
     * @return array_push
     */
    public function load_register_file() {
        $file = WPCVS_DIR . '/includes/Admin/Register';

        $scanned_directory = array_diff( scandir( $file ), array( '..', '.' ) );

        foreach ( $scanned_directory as $value ) {
            $ex   = pathinfo( $value, PATHINFO_EXTENSION );
            $file = basename( $value, '.' . $ex );
            array_push( $this->classes, $file );
            array_push( $this->class_basename, strtolower( $file ) );
        }
    }

    /**
     * Get all register setting
     *
     * Register into swatches setting
     *
     * @since 1.0.0
     * @return class_obj
     */
    public function menu_register() {
        foreach ( $this->classes as $class ) {
                $class = __NAMESPACE__ . '\\Register\\' . $class;
                new $class();
        }
    }

    /**
     * Add setting menu in dashboard
     *
     * Add action load enqueu script for setting menu
     *
     * @since 1.0.0
     * @return hook
     */
    public function wpcvs_admin_menu() {
        $hook = add_menu_page( 'variation-swatches-settings', __( 'Swatches Settings', 'wpcvs' ), 'manage_options', Wpcommerz_Variation()->swatches_setting_slug(), [ $this, 'variation_swatches_settings' ], 'dashicons-admin-generic', 31 );
        add_action( 'admin_head-' . $hook, [ $this, 'enqueu_admin' ] );
    }

    /**
     * Load enqueu script from register scripts only swatches settings
     *
     * @since 1.0.0
     * @return void
     */
    public function enqueu_admin() {
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_style( 'wpc-variation-style' );
        wp_enqueue_script( 'wpc-variation-scripts-admin' );
    }

    /**
     * Setting html body handler
     *
     * @since 1.0.0
     * @return void
     */
    public function variation_swatches_settings() {
        settings_errors();
        require_once WPCVS_DIR . '/includes/Admin/views/swatches_setting.php';
    }
}
