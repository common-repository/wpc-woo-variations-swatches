<?php
namespace Wpcommerz\Variation\Admin\Contracts;

interface SettingsRegister {
    /**
     * Set register fields for setting
     * @since 1.0.0
     * @return void
     */
    public function fields();

    /**
     * Set setting callback
     *
     * @since 1.0.0
     * @param $value
     * @return string
     */
    public function settings_section_call_back( $value );

    /**
     * Set setting title class callback
     *
     * @since 1.0.0
     * @return string
     */
    public function setting_string_title();

    /**
     * Defin class
     * Get class name
     *
     * @since 1.0.0
     * @return class_name
     */
    public static function get_defining_class();

    /**
     * Register setting api for swatches settings
     *
     * @since 1.0.0
     * @return class_obj
     */
    public function register();

}
