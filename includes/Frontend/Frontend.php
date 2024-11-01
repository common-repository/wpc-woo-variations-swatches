<?php
namespace Wpcommerz\Variation\Frontend;

class Frontend {
    /**
     * Frontend handler.
     */
    public function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        $this->init_class();
    }

    /**
     * Enqueue scripts only product page
     *
     * @since 1.0.0
     * @return void
     */
    public function enqueue_scripts() {
        if ( is_singular( 'product' ) || is_shop() || is_product() ) {
            wp_enqueue_style( 'wpc-variation-style' );
            wp_enqueue_script( 'wpc-variation-scripts-frontend' );
            ?>
            <style>
                .wpc-variation-swatch [wpcvs-tooltip] {
                    position: relative;
                }
                <?php if ( wpcvs_get_setting_option( 'enable_tooltip', true ) ) { ?>
                .wpc-variation-swatch [wpcvs-tooltip]:before, .wpc-variation-swatch [wpcvs-tooltip]:after{
                    position: absolute;
                    -webkit-transform: translateX(-50%);
                    transform: translateX(-50%);
                    left:50%;
                    bottom:110%;
                    visibility: hidden;
                    opacity: 0;
                    pointer-events: none;
                    box-sizing: inherit;
                    transition: 1s;
                }

                .wpc-variation-swatch [wpcvs-tooltip]:before{
                    content: attr(wpcvs-tooltip);
                    margin-bottom: 5px;
                    padding: 7px;
                    border-radius: 3px;
                    background-color: <?php echo wpcvs_get_setting_option( 'tooltip_bg_color', '#444' ); ?>;
                    color: <?php echo wpcvs_get_setting_option( 'tooltip_text_color', '#fff' ); ?>;
                    text-align: center;
                    font-size: <?php echo wpcvs_get_setting_option( 'tooltip_font', '14' ); ?>px;
                    line-height: 1.2;
                    min-width:100px;
                }

                .wpc-variation-swatch [wpcvs-tooltip]:after{
                    width: 0;
                    border-top: 5px solid <?php echo wpcvs_get_setting_option( 'tooltip_bg_color', '#444' ); ?>;
                    border-right: 5px solid transparent;
                    border-left: 5px solid transparent;
                    content: " ";
                    line-height: 0;
                }
                <?php } ?>
                .wpc-variation-swatch [wpcvs-tooltip]:hover:before, .wpc-variation-swatch [wpcvs-tooltip]:hover:after{
                    visibility: visible;
                    opacity: 1;
                }
            </style>

            <style>

                .variation_check.disabled { cursor: no-drop; }
                .variation_check.disabled::before {content: '';width: 2px;height: 100%;background: red;position: absolute;transform: rotate(45deg);text-align: center;top: 0;left: 50%;z-index: 999;}
                .variation_check.disabled::after {content: '';width: 2px;height: 100%;background: red;position: absolute;transform: rotate(-45deg);text-align: center;top: 0;left: 50%;z-index: 999;}
            </style>
            <?php
        }
    }

    /**
     * Load frontend all class object
     *
     * @since 1.0.0
     * @return class_obj
     */
    public function init_class() {
        new \Wpcommerz\Variation\Frontend\SingleProduct();
    }
}
