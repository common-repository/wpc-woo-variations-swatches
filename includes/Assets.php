<?php
namespace Wpcommerz\Variation;

class Assets {
    /**
     * Asets handlers Class.
     */
    public function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );
    }

    /**
     * Set and get styles for admin , frontend
     *
     * @since 1.0.0
     * @return array
     */
    public function get_styles() {
        return [
            'wpc-variation-style' => [
                'src'     => WPCVS_PLUGIN_ASSEST . '/css/app.css',
                'version' => filemtime( WPCVS_DIR . '/assets/css/app.css' ),
                'deps'    => array(),
            ],
            'wpc-admin-setting' => [
                'src'     => WPCVS_PLUGIN_ASSEST . '/css/setting.css',
                'version' => filemtime( WPCVS_DIR . '/assets/css/setting.css' ),
                'deps'    => [],
            ],
        ];
    }

    /**
     * Set and get scripts for admin , frontend
     *
     * @since 1.0.0
     * @return array
     */
    public function get_scripts() {
        return [
            'wpc-variation-scripts-admin' => [
                'src'     => WPCVS_PLUGIN_ASSEST . '/js/app.js',
                'version' => filemtime( WPCVS_DIR . '/assets/js/app.js' ),
                'deps'    => [ 'jquery', 'wp-color-picker', 'wp-util' ],
            ],

            'wpc-variation-scripts-frontend' => [
                'src'     => WPCVS_PLUGIN_ASSEST . '/js/frontend.js',
                'version' => filemtime( WPCVS_DIR . '/assets/js/frontend.js' ),
                'deps'    => [ 'jquery' ],
            ],
        ];
    }

    /**
     * Enqueu style ,script register handler
     *
     * @since 1.0.0
     * @return void
     */
    public function enqueue_assets() {
        $styles = $this->get_styles();

        foreach ( $styles as $handle => $style ) {
            $deps = $style['deps'] ? $style['deps'] : false;
            wp_register_style( $handle, $style['src'], $deps, $style['version'] );
        }

        $scripts = $this->get_scripts();

        foreach ( $scripts as $handle => $script ) {
            $deps = $script['deps'] ? $script['deps'] : false;
            wp_register_script( $handle, $script['src'], $deps, $script['version'], true );
        }
    }

}
