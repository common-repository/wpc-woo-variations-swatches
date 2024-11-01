<?php

namespace Wpcommerz\Variation\Admin;

class AttributeSettings {
    /**
     * AttributeSetting Class handler.
     */
    public function __construct() {
        new \Wpcommerz\Variation\Admin\AddAttributesSetting();
        add_action( 'admin_init', [ $this, 'init_attribute_hooks' ] );
        add_action( 'wpcvs_wc_product_attribute_field', [ $this, 'attribute_fields' ], 10, 3 );
    }

    /**
     * Init attribute hooks for attribute, manage
     * @return hook
     */
    public function init_attribute_hooks() {
        $attribute_taxonomies = wc_get_attribute_taxonomies();

        if ( empty( $attribute_taxonomies ) ) {
            return;
        }

        foreach ( $attribute_taxonomies as $tax ) {
            add_action( 'pa_' . $tax->attribute_name . '_add_form_fields', [ $this, 'add_attribute_fields' ] );
            add_action( 'pa_' . $tax->attribute_name . '_edit_form_fields', array( $this, 'edit_attribute_fields' ), 10, 2 );

            add_filter( 'manage_edit-pa_' . $tax->attribute_name . '_columns', [ $this, 'add_attribute_columns' ] );
            add_filter( 'manage_pa_' . $tax->attribute_name . '_custom_column', [ $this, 'add_attribute_column_content' ], 10, 3 );
        }

        add_action( 'created_term', [ $this, 'save_term_meta' ], 10, 2 );

        add_action( 'edit_term', [ $this, 'save_term_meta' ], 12, 2 );
    }

    /**
     * Add term meta for wpc-wc coustome type attribute
     *
     * @since 1.0.0
     * @param  int $term_id
     * @param  int $tt_id
     * @return term
     */
    public function save_term_meta( $term_id, $tt_id ) {
        foreach ( wpcvs_attribute_types() as $type => $label ) {
            if ( isset( $_POST[$type] ) ) { //phpcs:ignore
                update_term_meta( $term_id, $type, sanitize_text_field( wp_unslash( $_POST[ $type ] ) ) ); //phpcs:ignore
            }
        }
    }

    /**
     * Add action product attribute field visibility
     *
     * @since 1.0.0
     * @param string $taxonomy
     * @return hook
     */
    public function add_attribute_fields( $taxonomy ) {
        $attr = wpcvs_get_tax_attribute( $taxonomy );
        do_action( 'wpcvs_wc_product_attribute_field', $attr->attribute_type, '', 'add' );
    }

    /**
     * Edit product attribute fields
     *
     * @since 1.0.0
     * @param object $term
     * @param string $taxonomy
     * @return void
     */
    public function edit_attribute_fields( $term, $taxonomy ) {
        $attr      = wpcvs_get_tax_attribute( $taxonomy );
        $term_data = get_term_meta( $term->term_id, $attr->attribute_type, true );

        do_action( 'wpcvs_wc_product_attribute_field', $attr->attribute_type, $term_data, 'edit' );
    }

    /**
     * Thumbnail column to column list
     *
     * @since 1.0.0
     * @param $columns
     * @return array
     */
    public function add_attribute_columns( $columns ) {
        $new_columns          = array();
        $new_columns['cb']    = $columns['cb'];
        $new_columns['thumb'] = '';

        unset( $columns['cb'] );

        return array_merge( $new_columns, $columns );
    }

    /**
     * Html depend on attribute type
     *
     * @since 1.0.0
     * @param $columns
     * @param $column
     * @param $term_id
     */
    public function add_attribute_column_content( $columns, $column, $term_id ) {
        if ( 'thumb' !== $column ) {
            return $columns;
        }

        $attr  = wpcvs_get_tax_attribute( isset( $_REQUEST['taxonomy'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['taxonomy'] ) ) : '' ); //phpcs:ignore
        $value = get_term_meta( $term_id, $attr->attribute_type, true );

        switch ( $attr->attribute_type ) {
            case 'color':
                printf( '<div class="wpc-wc-preview wpc-wc-color h-10 rounded-lg shadow-lg w-10 wpc-wc-color wpc-wc-preview" style="background-color:%s;"></div>', esc_attr( $value ) );
                break;

            case 'image':
                $image = $value ? wp_get_attachment_image_src( $value ) : '';
                $image = $image ? $image[0] : WC()->plugin_url() . '/assets/images/placeholder.png';
                printf( '<img class="h-10 rounded-lg shadow-lg w-10 border-0" src="%s" width="44px" height="44px">', esc_url( $image ) );
                break;

            default:
                printf( '<div class="wpc-wc-preview wpc-wc-color h-10 rounded-lg shadow-lg w-10 wpc-wc-color wpc-wc-preview relative" style="background-color:%s;"><span class="absolute font-bold left-3 top-3">%s</span></div>', '#ddd', esc_attr( $value ) );
                break;
        }
    }

    /**
     * Type attribute fields
     *
     * @since 1.0.0
     * @param array $type
     * @param string $value
     * @param string $form
     * @return void
     */
    public function attribute_fields( $type, $value, $form ) {
        $color_type = wpcvs_attribute_types();

        // Return a default attribute type
        if ( in_array( $type, array( 'select', 'text' ), true ) ) {
            return;
        }

        // Print the open tag of field container
        printf(
            '<%s class          = "form-field">%s<label for="term-%s">%s</label>%s',
            'edit' === $form ? 'tr' : 'div',
            'edit' === $form ? '<th>' : '',
            esc_attr( $type ),
            $color_type[ $type ],
            'edit' === $form ? '</th><td>' : ''
        );

        switch ( $type ) {
            case 'image':
                $image = $value ? wp_get_attachment_image_src( $value ) : '';
                $image = $image ? $image[0] : WC()->plugin_url() . '/assets/images/placeholder.png';

                // include file from view
                require_once WPCVS_DIR . '/includes/Admin/views/attribute-image.php';
                break;

            case 'color':
                ?>
                    <input type="text" value="<?php echo $value; ?>" name="<?php echo esc_attr( $type ); ?>" class="term-color" id="color-pc" data-default-color="#effeff" />
                <?php
                break;

            case 'label':
                ?>
                    <input type="text" value="<?php echo $value; ?>" name="<?php echo esc_attr( $type ); ?>" class="term-label" />
                <?php
                break;
        }
        echo 'edit' === $form ? '</td></tr>' : '</div>';
    }
}
