<?php
namespace Wpcommerz\Variation;

class Installer {
    /**
     * Instance of self
     *
     * @var Installer
     */
    private static $instance = null;

    /**
     * Installer handaler
     *
     * @since 1.0.0
     */
    private function __construct() {
        // assets class Initialize
        new \Wpcommerz\Variation\Assets();

        // admin Initialize
        $this->admin();

        // frontend Initialize
        $this->frontend();
    }

    /**
     * Initialize the actions
     *
     * @return void
     */
    public static function init() {
        if ( self::$instance === null ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Admin class load
     *
     * @since 1.0.0
     * @return void
     */
    public function admin() {
        new \Wpcommerz\Variation\Admin\Wpcvs_Admin();
    }

    /**
     * Frontend class load
     *
     * @since 1.0.0
     * @return void
     */
    public function frontend() {
        new \Wpcommerz\Variation\Frontend\Frontend();
    }

}
