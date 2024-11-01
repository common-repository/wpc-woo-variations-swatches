<?php
/**
 * Get attribute by taxonomy
 *
 * @since 1.0.0
 * @param  $taxonomy
 * @return Object
 */
function wpcvs_get_tax_attribute( $taxonomy ) {
    global $wpdb;
    $attr = substr( $taxonomy, 3 );
    $attr = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'woocommerce_attribute_taxonomies WHERE attribute_name = %s', $attr ) );

    return $attr;
}

/**
* Set attributes type
* @since 1.0.0
* @return array
*/
function wpcvs_attribute_types() {
    return [
        'color' => esc_html__( 'Color', 'wpcvs' ),
        'image' => esc_html__( 'Image', 'wpcvs' ),
        'label' => esc_html__( 'Label', 'wpcvs' ),
    ];
}

/**
* Get single settings with default
*
* @since 1.0.0
* @param string $name
* @param void $default
* @return void
*/
function wpcvs_get_setting_option( $name, $default ) {
    $group   = get_option( 'basic_settings_option_name' );
    $setting = isset( $group[ $name ] ) ? $group[ $name ] ?? $default : $default;
    return apply_filters( 'wpcvs_basic_variation_setting_' . $name, $setting );
}
