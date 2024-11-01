<?php
namespace Wpcommerz\Variation\Admin;

use Wpcommerz\Variation\Admin\Contracts\SettingsRegister;

class SettingsApi {

    /**
     * Get setting object
     *
     * @var object
     */
    public $settings;

    /**
     * Set setting option group
     *
     * @var string
     */
    public $option_group;

    /**
     * Set setting option group name
     *
     * @var string
     */
    public $option_name;

    /**
     * Set setting section id
     *
     * @var string
     */
    public $section_id;

    /**
     * Set setting section title
     *
     * @var string
     */
    public $section_title;

    /**
     * Set setting for page
     *
     * @var string
     */
    public $page;

    /**
     * All setting name prefix
     *
     * @var string
     */
    public $prefix;

    /**
     * Class constructor.
     */
    public function __construct( SettingsRegister $settings_register ) {
        add_action( 'admin_init', [ $this, 'register_setting' ] );

        $this->settings = $settings_register;
        $this->prefix   = strtolower( $settings_register::get_defining_class() );

        $this->load_setting();
        $this->settings->fields();
    }

    /**
     * Set setting option group,name,section
     *
     * @since 1.0.0
     * @return void
     */
    public function load_setting() {
        $this->option_group  = $this->prefix . '_option_group';
        $this->option_name   = $this->prefix . '_option_name';
        $this->section_id    = $this->prefix . '_section_id';
        $this->section_title = $this->settings->setting_string_title();
        $this->page          = $this->prefix;
    }

    /**
     * Register setting for swatches setting panel
     *
     * @return void
     */
    public function register_setting() {
        register_setting( $this->option_group, $this->option_name, [ 'sanitize_callback' => [ $this, 'sanitize_basic_setting_option' ] ] );
        add_settings_section( $this->section_id, $this->section_title, [ $this->settings, 'settings_section_call_back' ], $this->page );

        foreach ( $this->settings->register_field as $value ) {
            $custome_value = isset( $value['value'] ) ? $value['value'] : [];
            $hidden        = isset( $value['hidden'] ) ? $value['hidden'] : false;

            $args = [
                'label_for'  => $value['id'],
                'label_text' => $value['title'],
                'subtitle'   => isset( $value['subtitle'] ) ? $value['subtitle'] : '',
                'value'      => $custome_value,
                'hidden'     => $hidden,
                'default'    => isset( $value['default'] ) ? $value['default'] : false,
            ];

            add_settings_field( $value['id'], $value['title'], [ $this, "wpcvs_setting_$value[type]" ], $this->page, $this->section_id, $args );
        }
    }

    /**
     * Setting field for text
     *
     * @param array $args
     * @return void
     */
    public function wpcvs_setting_text( $args ) {
        $setting_value = get_option( $this->option_name );
        $value         = $setting_value[$args['label_for']] ?? $args['default']; //phpcs:ignore
        $name          = $this->option_name . "[$args[label_for]]";
        $value         = isset( $value ) ? $value : '';
        $type          = $args['hidden'] ? 'text' : 'hidden';

        echo '<div class="flex flex-col leading-7">';
        printf( '<input type="text" name="%s" class="text w-48" id="%s" value="%s"> <label for="%s" class="italic">%s</label>', $name, $args['label_for'], $value, $args['label_for'], $args['subtitle'] );
        echo '</div>';
    }

    /**
     * Setting field for color
     *
     * @param array $args
     * @return void
     */
    public function wpcvs_setting_color( $args ) {
        $setting_value = get_option( $this->option_name );
        $value         = $setting_value[$args['label_for']] ?? $args['default']; //phpcs:ignore
        $name          = $this->option_name . "[$args[label_for]]";
        $value         = isset( $value ) ? $value : '';

        printf( '<input type="text" name="%s" class="term-color" id="color-pc" value="%s" data-default-color="%s">', $name, $value, $value );
    }

    /**
     * Setting field for checkbox
     *
     * @param array $args
     * @return void
     */
    public function wpcvs_setting_checkbox( $args ) {
        $setting_value = get_option( $this->option_name );
        $value         = $setting_value[$args['label_for']] ?? 'yes'; //phpcs:ignore
        $name          = $this->option_name . "[$args[label_for]]";
        $value         = isset( $value ) ? checked( $value, 'yes', false ) : '';

        echo '<div class="flex flex-col leading-7">';
        printf( '<input type="checkbox" name="%s" id="%s" value="yes" %s> <label for="%s">%s</label>', $name, $args['label_for'], $value, $args['label_for'], $args['subtitle'] );
        echo '</div>';
    }

    /**
     * Setting field for radio
     *
     * @param array $args
     * @return void
     */
    public function wpcvs_setting_radio( $args ) {
        $setting_value = get_option( $this->option_name );
        $value         = $setting_value[$args['label_for']] ?? 'rounded'; //phpcs:ignore
        $name          = $this->option_name . "[$args[label_for]]";

        echo '<div class="flex flex-col leading-7">';
        foreach ( $args['value'] as $handle => $va ) {
            $checked = isset( $value ) ? checked( $value, $handle, false ) : '';
            printf( '<div> <input type="radio" name="%s" value="%s" %s> <span>%s</span></div>', $name, $handle, $checked, $va );
        }
        echo '</div>';
    }

    /**
     * Setting field for option
     *
     * @param  array $args
     * @return void
     */
    public function wpcvs_setting_options( $args ) {
        $setting_value = get_option( $this->option_name );
        $value         = $setting_value[$args['label_for']] ?? $args['default']; //phpcs:ignore
        $name          = $this->option_name . "[$args[label_for]]";

        echo '<div class="flex flex-col leading-7">';
        ?>
        <select id="<?php echo $args['label_for']; ?>" class="w-48" name="<?php echo $name; ?>">
            <?php foreach ( $args['value'] as $handle => $va ) { ?>
                <?php $checked = isset( $value ) ? selected( $value, $handle, false ) : ''; ?>
            <option value="<?php echo $handle; ?>" <?php echo $checked; ?>> <?php echo $va; ?> </option>
            <?php } ?>
        </select>
        <?php
        echo '</div>';
    }

    /**
     * All input sanitize settings optimize for database
     *
     * @param $input
     * @return void
     */
    public function sanitize_basic_setting_option( $input ) {
        $output = [];

        foreach ( $this->settings->register_field as $value ) {
            $output[$value['id']] = isset( $input[$value['id']] ) ? $input[$value['id']] : false; //phpcs:ignore
        }

        return $output;
    }

}
