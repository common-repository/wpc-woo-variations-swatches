<?php
namespace Wpcommerz\Variation\Admin;

class AddAttributesSetting {
    /**
     * Product attributes type Class handler.
     */
    public function __construct() {
        // add filter product attributes type
        add_filter( 'product_attributes_type_selector', [ $this, 'wpcvs_product_attributes_type_select' ] );
    }

    /**
     * Merge attributes type
     *
     * Get attributes type
     *
     * @since 1.0.0
     * @param WC $type
     * @return array
     */
    public function wpcvs_product_attributes_type_select( $type ) {
        $html = array_merge( $type, wpcvs_attribute_types() );

        return $html;
    }

}
