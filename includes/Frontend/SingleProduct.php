<?php

namespace Wpcommerz\Variation\Frontend;

class SingleProduct {
    /**
     * Default shape width
     *
     * @var string
     */
    public $width = 'w-9';

    /**
     * Default shape height
     *
     * @var string
     */
    public $height = 'h-9';

    /**
     * Default tooltip font size
     *
     * @var string
     */
    public $text_size = '12';

    /**
     * Default tooltip text color
     *
     * @var string
     */
    public $tooltip_text = '#fff';

    /**
     * Default tooltip background color
     *
     * @var string
     */
    public $tooltip_bg = '#444';

    /**
     * Default tooltip active mode
     *
     * @var boolean
     */
    public $tooltip_active = true;

    /**
     * Default attribute item shape
     *
     * @var string
     */
    public $shape = 'rounded';

    /**
     * Single page product variation filter handler
     * @since 1.0.0
     */
    public function __construct() {
        $this->init_filter();
    }

    /**
     * Add filter for single page variation
     *
     * @hooked woocommerce_dropdown_variation_attribute_options_html -100
     * @hooked wpcvs_custom_attribute_html                             -5
     * @since 1.0.0
     * @return void
     */
    public function init_filter() {
        if ( wpcvs_get_setting_option( 'enable_style', 'yes' ) ) {
            add_filter( 'woocommerce_dropdown_variation_attribute_options_html', [ $this, 'get_wpc_variation_html' ], 100, 2 );
        }

        add_filter( 'wpcvs_custom_attribute_html', [ $this, 'wpcvs_variation_html' ], 5, 4 );
    }

    /**
     * Wc variation attribute options
     *
     * @since 1.0.0
     * @param  void $html
     * @param  array $args
     * @return $html
     */
    public function get_wpc_variation_html( $html, $args ) {
        $attr = wpcvs_get_tax_attribute( $args['attribute'] );
        if ( empty( $attr ) ) {
            return $html;
        }

        if ( ! array_key_exists( $attr->attribute_type, wpcvs_attribute_types() ) ) {
            return $html;
        }

        $options   = $args['options'];
        $product   = $args['product'];
        $attribute = $args['attribute'];
        $class     = "variation-selector variation-select-{$attr->attribute_type}";
        $wpc_html  = '';

        if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
            $attributes = $product->get_variation_attributes();
            $options    = $attributes[ $attribute ];
        }

        if ( array_key_exists( $attr->attribute_type, wpcvs_attribute_types() ) ) {
            if ( ! empty( $options ) && $product && taxonomy_exists( $attribute ) ) {
                $terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );
                foreach ( $terms as $term ) {
                    if ( in_array( $term->slug, $options, true ) ) {
                        $wpc_html .= apply_filters( 'wpcvs_custom_attribute_html', '', $term, $attr->attribute_type, $args );
                    }
                }
            }

            if ( ! empty( $wpc_html ) ) {
                $wpc_html = '<ul class="wpc-variation-swatch flex -m-1" data-attribute_name="attribute_' . esc_attr( $attribute ) . '"> ' . $wpc_html . ' </ul>';
                $html     = '<div class="' . $class . ' hidden" style="">' . $html . '</div>' . $wpc_html;
            }
        }

        return $html;
    }

    /**
     * Html for frontend
     *
     * @since 1.0.0
     * @param  $html
     * @param  $term
     * @param  $type
     * @param  $args
     * @return $html
     */
    public function wpcvs_variation_html( $html, $term, $attribute_type, $args ) {
        $tooltip     = '';
        $selected    = sanitize_title( $args['selected'] ) === $term->slug ? 'selected' : '';
        $name        = esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) );
        $li_class   = $this->get_li_class();
        $screen_reader_html_attr = $selected ? ' aria-checked="true"' : ' aria-checked="false"';
        $tooltip = sprintf( 'wpcvs-tooltip="%s"', $name );

        $html .= sprintf( ' <li data-value="%s" %s class="wpc-swatch %s cursor-pointer bg-center bg-cover border-2 duration-500 hover:border-4 hover:border-blue-500 m-1 transition %s"> ', $term->slug, $tooltip . $screen_reader_html_attr, $selected, $li_class );

        switch ( $attribute_type ) {
            case 'color':
                $color = get_term_meta( $term->term_id, 'color', true );

                $html .= sprintf( '<span style="background-color:%s;" class="wpc-content variation_check absolute border-2 border-white h-full w-full %s"></span>', $color, $this->content_shape() );
                break;

            case 'image':
                $image = get_term_meta( $term->term_id, 'image', true );
                $image = $image ? wp_get_attachment_image_src( $image ) : '';
                $image = $image ? $image[0] : WC()->plugin_url() . '/assets/images/placeholder.png';
                $html  .= sprintf( '<span style="background-image: url(%s);" class="wpc-content variation_check absolute border-2 border-white bg-center bg-cover h-full w-full %s"></span>', $image, $this->content_shape() );
                break;

            case 'label':
                $label = get_term_meta( $term->term_id, 'label', true );
                $label = $label ? $label : $name;
                $html  .= sprintf( ' <span class="bg-gray-100 border-2 border-white flex h-full items-center justify-center variation_check w-full %s">%s</span>', $this->content_shape(), $label );
                break;
        }
        $html .= '</li>';
        return $html;
    }

    /**
     * Get attribute main div style class
     *
     * Get attribute shape
     *
     * Get attribute shape width and height
     *
     * Use setting from swatche  $li_class   = '';s setting
     * @return void
     */
    public function get_li_class() {
        $class   = ' ' . $this->content_shape();
        $class  .= ' ' . wpcvs_get_setting_option( 'shape_width', $this->width ) . ' ';
        $class  .= ' ' . wpcvs_get_setting_option( 'shape_height', $this->height ) . ' ';

        return $class;
    }


    /**
     * Set content shape class
     *
     * Use setting from swatches setting
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function content_shape() {
        $shape = wpcvs_get_setting_option( 'shape_style', $this->shape );

        return $shape === 'rounded' ? 'rounded-full' : 'rounded';
    }

    /**
     * Get tooltip style class
     *
     * Get tooltip background color and text color
     *
     * Use setting from swatches setting
     * @since 1.0.0
     * @return void
     */
    public function get_tooltip_style() {
        $bg_color   = wpcvs_get_setting_option( '2', '#444' );
        $text_color = wpcvs_get_setting_option( 'tooltip_text_color', '#fff' );
        $font_size  = wpcvs_get_setting_option( 'tooltip_font', '16' );

        return 'style="background-color:' . $bg_color . ';color:' . $text_color . ';font-size:' . $font_size . 'px"';
    }

    /**
     * Get tooltip activator
     *
     * Use setting from swatches setting
     * @since 1.0.0
     * @return bool
     */
    public function get_tooltip_active() {
        $active = wpcvs_get_setting_option( 'enable_tooltip', true );
        return $active;
    }
}
