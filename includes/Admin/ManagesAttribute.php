<?php
namespace Wpcommerz\Variation\Admin;

class ManagesAttribute {
    /**
     * ManagesAttribute handler
     */
    public function __construct() {
        add_action( 'woocommerce_product_option_terms', [ $this, 'product_option_terms' ], 10, 2 );
    }

    /**
     * WC product option variation terms
     *
     * @since 1.0.0
     * @param  $tax taxonomy
     * @param  $i index
     * @return void
     */
    public function product_option_terms( $tax, $i ) {
        global $thepostid;

        if ( ! array_key_exists( $tax->attribute_type, wpcvs_attribute_types() ) ) {
            return;
        }

        $tax_name   = wc_attribute_taxonomy_name( $tax->attribute_name );
        $product_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : $thepostid; //phpcs:ignore

        ?>
        <select multiple="multiple" data-placeholder="<?php esc_attr_e( 'Select terms', 'wpcvs' ); ?>" class="multiselect attribute_values wc-enhanced-select" name="attribute_values[<?php echo esc_attr( $i ); ?>][]">
            <?php
            $args = array(
                'orderby'    => 'name',
                'hide_empty' => 0,
            );
            $all_terms = get_terms( $tax_name, apply_filters( 'woocommerce_product_attribute_terms', $args ) );

            if ( $all_terms ) {
                foreach ( $all_terms as $term ) {
                    echo '<option value="' . esc_attr( $term->term_id ) . '" ' . selected( has_term( absint( $term->term_id ), $tax_name, $product_id ), true, false ) . '>' . esc_attr( apply_filters( 'woocommerce_product_attribute_term_name', $term->name, $term ) ) . '</option>';
                }
            }
            ?>
        </select>
        <button class="button plus select_all_attributes"><?php esc_html_e( 'Select all', 'wpcvs' ); ?></button>
        <button class="button minus select_no_attributes"><?php esc_html_e( 'Select none', 'wpcvs' ); ?></button>
        <?php
    }
}
