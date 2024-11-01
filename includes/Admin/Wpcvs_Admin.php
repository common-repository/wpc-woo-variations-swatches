<?php

namespace Wpcommerz\Variation\Admin;

class Wpcvs_Admin {
    /**
     * Admin Class handler.
     */
    public function __construct() {
        // admin header load scripts
        add_action( 'admin_head', [ $this, 'enqueue_admin' ] );
        $this->init_class();
    }

    /**
     * Enqueue scripts only product post_type page
     *
     * @since 1.0.0
     * @return void
     */
    public function enqueue_admin() {
        global $post_type;

        if ( 'product' === $post_type ) {
            wp_enqueue_media();
            wp_enqueue_style( 'wpc-variation-style' );
            wp_enqueue_script( 'wpc-variation-scripts-admin' );
        }
    }

    /**
     * Load admin all class object
     *
     * @since 1.0.0
     * @return class_obj
     */
    public function init_class() {
        new \Wpcommerz\Variation\Admin\SwatchesMenu();
        new \Wpcommerz\Variation\Admin\AttributeSettings();
        new \Wpcommerz\Variation\Admin\ManagesAttribute();
    }

}
