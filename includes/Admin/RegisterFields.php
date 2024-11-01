<?php

namespace Wpcommerz\Variation\Admin;

use Wpcommerz\Variation\Admin\SettingsApi;

trait RegisterFields {

    /**
     * Class constructor.
     */
    public function __construct() {
        $this->register();
    }

    /**
     * Include register field
     *
     * @var array
     */
    public $register_field = array();

    /**
     * Defin class
     * Get class name
     *
     * @since 1.0.0
     * @return class_name
     */
    public static function get_defining_class() {
        $path = explode( '\\', __CLASS__ );
        return array_pop( $path );
    }

    /**
     * Add register html input checkbox attribute
     *
     * @param array $data
     * @return void
     */
    public function add_register_checkbox_field( array $data ) {
        $data['type'] = 'checkbox';
        return array_push( $this->register_field, $data );
    }

    /**
     * Add register html input radio attribute
     *
     * @param array $data
     * @return void
     */
    public function add_register_radio_field( array $data ) {
        $data['type'] = 'radio';
        return array_push( $this->register_field, $data );
    }

    /**
     * Add register html input text attribute
     *
     * @param array $data
     * @param bool $hidden
     * @return void
     */
    public function add_register_text_field( array $data, $hidden = true ) {
        $data['type']   = 'text';
        $data['hidden'] = $hidden;
        return array_push( $this->register_field, $data );
    }

    /**
     * Add register html input hidden attribute
     *
     * @param array $data
     * @param boole $hidden
     * @return void
     */
    public function add_register_color_field( array $data, $hidden = true ) {
        $data['type']   = 'color';
        $data['hidden'] = $hidden;
        return array_push( $this->register_field, $data );
    }

    /**
     * Add register html input select attribute
     *
     * @param array $data
     * @return void
     */
    public function add_register_option_field( array $data ) {
        $data['type'] = 'options';
        return array_push( $this->register_field, $data );
    }

    /**
     * Register setting api for swatches settings
     *
     * @since 1.0.0
     * @return class_obj
     */
    public function register() {
        new SettingsApi( $this );
    }
}
