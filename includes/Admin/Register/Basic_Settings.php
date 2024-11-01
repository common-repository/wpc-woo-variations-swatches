<?php

namespace Wpcommerz\Variation\Admin\Register;

use Wpcommerz\Variation\Admin\Contracts\SettingsRegister;
use Wpcommerz\Variation\Admin\RegisterFields;

class Basic_Settings implements SettingsRegister {

    use RegisterFields;

    /**
     * Set register fields for setting
     * @since 1.0.0
     * @return void
     */
    public function fields() {
        $enable_tooltip = [
            'id'       => 'enable_tooltip',
            'title'    => 'Enable tooltip',
            'subtitle' => 'Enable or disabled tooltip on each product attribute',
        ];
        $this->add_register_checkbox_field( $enable_tooltip );

        $enable_style = [
            'id'    => 'enable_style',
            'title' => 'Enable Style',
            'subtitle' => 'Default stylesheet enable or disabled',
        ];
        $this->add_register_checkbox_field( $enable_style );

        $shape_style = [
            'id'    => 'shape_style',
            'title' => 'Shape Style',
            'value' => [
                'rounded' => 'Rounded Shape',
                'squared' => 'Squared Shape',
            ],
        ];
        $this->add_register_radio_field( $shape_style );

        $shape_width = [
            'id'      => 'shape_width',
            'title'   => 'Shape width',
            'default' => 'w-9',
            'value'  => [
                'w-4'  => 'width: 16px',
                'w-5'  => 'width: 20px',
                'w-6'  => 'width: 24px',
                'w-7'  => 'width: 28px',
                'w-8'  => 'width: 32px',
                'w-9'  => 'width: 36px',
                'w-10' => 'width: 40px',
                'w-11' => 'width: 44px',
                'w-12' => 'width: 48px',
                'w-14' => 'width: 56px',
                'w-16' => 'width: 64px',
                'w-20' => 'width: 80px',
                'w-24' => 'width: 96px',
            ],
        ];
        $this->add_register_option_field( $shape_width );

        $shape_height = [
            'id'      => 'shape_height',
            'title'   => 'Shape height',
            'default' => 'h-9',
            'value' => [
                'h-4'  => 'height: 16px',
                'h-5'  => 'height: 20px',
                'h-6'  => 'height: 24px',
                'h-7'  => 'height: 28px',
                'h-8'  => 'height: 32px',
                'h-9'  => 'height: 36px',
                'h-10' => 'height: 40px',
                'h-11' => 'height: 44px',
                'h-12' => 'height: 48px',
                'h-14' => 'height: 56px',
                'h-16' => 'height: 64px',
                'h-20' => 'height: 80px',
                'h-24' => 'height: 96px',
            ],
        ];
        $this->add_register_option_field( $shape_height );

        $font_size = [
            'id'       => 'tooltip_font',
            'title'    => 'Tooltip Font Size',
            'subtitle' => 'tooltip font size (: px)',
            'default'  => '14',
        ];
        $this->add_register_text_field( $font_size, true );

        $bg_color = [
            'id'      => 'tooltip_bg_color',
            'title'   => 'Tooltip background color',
            'default' => '#444',
        ];
        $this->add_register_color_field( $bg_color, true );

        $text_color = [
            'id'      => 'tooltip_text_color',
            'title'   => 'Tooltip text color',
            'default' => '#fff',
        ];
        $this->add_register_color_field( $text_color, true );
    }

    /**
     * Set setting callback
     *
     * @since 1.0.0
     * @param $value
     * @return string
     */
    public function setting_string_title(): string {
        return 'Genarel Setting';
    }

    /**
     * Set setting title class callback
     *
     * @since 1.0.0
     * @return string
     */
    public function settings_section_call_back( $value ) {
    }
}
