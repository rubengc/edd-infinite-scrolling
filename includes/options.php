<?php
/**
 * Options
 *
 * @package     EDD\Infinite_Scrolling\Options
 * @since       1.0.0
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'EDD_Infinite_Scrolling_Options' ) ) {

    class EDD_Infinite_Scrolling_Options extends uFramework_Options {

        public function __construct() {
            $this->options_key = 'edd-infinite-scrolling';

            add_filter( 'tsunoa_' . $this->options_key . '_settings', array( $this, 'register_settings_url' ) );

            parent::__construct();
        }

        public function register_settings_url( $url ) {
            return 'admin.php?page=' . $this->options_key;
        }

        /**
         * Add the options metabox to the array of metaboxes
         * @since  0.1.0
         */
        public function register_form() {
            // Options page configuration
            $args = array(
                'key'      => $this->options_key,
                'title'    => __( 'EDD Infinite Scrolling', 'edd-infinite-scrolling' ),
                'topmenu'  => 'tsunoa',
                'cols'     => 2,
                'boxes'    => $this->boxes(),
                'tabs'     => $this->tabs(),
                'menuargs' => array(
                    'menu_title' => __( 'EDD Infinite Scrolling', 'edd-infinite-scrolling' ),
                ),
                'savetxt'  => __( 'Save changes' ),
                'admincss' => '.' . $this->options_key . ' #side-sortables{padding-top: 0 !important;}' .
                    '.' . $this->options_key . '.cmo-options-page .columns-2 #postbox-container-1{margin-top: 0 !important;}' .
                    '.' . $this->options_key . '.cmo-options-page .nav-tab-wrapper{display: none;}'
            );

            // Create the options page
            new Cmb2_Metatabs_Options( $args );
        }

        /**
         * Setup form in settings page
         *
         * @return array
         */
        public function boxes() {
            // Holds all CMB2 box objects
            $boxes = array();

            // Default options to all boxes
            $show_on = array(
                'key'   => 'options-page',
                'value' => array( $this->options_key ),
            );

            // General options box
            $cmb = new_cmb2_box( array(
                'id'      => $this->options_key . '_general',
                'title'   => __( 'General options', 'edd-infinite-scrolling' ),
                'show_on' => $show_on,
            ) );

            $cmb->add_field( array(
                'name' => __( 'Enable by default', 'edd-infinite-scrolling' ),
                'desc' => __( 'Set by default infinite_scrolling="yes" to all [downloads] and [edd_downloads] shortcodes (you can override this option setting it to infinite_scrolling="no")', 'edd-infinite-scrolling' ),
                'id'   => 'enabled_by_default',
                'type' => 'checkbox',
            ) );

            $cmb->object_type( 'options-page' );

            $boxes[] = $cmb;

            // Infinite scrolling animations options box
            $cmb = new_cmb2_box( array(
                'id'      => $this->options_key . '_infinite_scrolling_animations',
                'title'   => __( 'Animations', 'edd-infinite-scrolling' ),
                'show_on' => $show_on,
            ) );

            $cmb->add_field( array(
                'name' => __( 'Entrance animation', 'edd-infinite-scrolling' ),
                'desc' => '',
                'id'   => 'infinite_scrolling_in_animation',
                'type' => 'animation',
                'preview' => true,
                'groups' => array( 'entrances' ),
            ) );

            $cmb->object_type( 'options-page' );

            $boxes[] = $cmb;

            // Submit box
            $cmb = new_cmb2_box( array(
                'id'      => $this->options_key . '-submit',
                'title'   => __( 'Save changes', 'edd-ajax-search' ),
                'show_on' => $show_on,
                'context' => 'side',
            ) );

            $cmb->add_field( array(
                'name' => '',
                'desc' => '',
                'id'   => 'submit_box',
                'type' => 'title',
                'render_row_cb' => array( $this, 'submit_box' )
            ) );

            $cmb->object_type( 'options-page' );

            $boxes[] = $cmb;

            // Shortcode box
            $cmb = new_cmb2_box( array(
                'id'      => $this->options_key . '-shortcode',
                'title'   => __( 'Shortcode generator', 'edd-infinite-scrolling' ),
                'show_on' => $show_on,
                'context' => 'side',
            ) );

            $cmb->add_field( array(
                'name' => '',
                'desc' => __( 'From this options page you can configure default parameters for EDD Infinite Scrolling. Also using form bellow you can generate a shortcode to place it in any page.', 'edd-infinite-scrolling' ),
                'id'   => 'shortcode_generator',
                'type' => 'title',
                'after' => array( $this, 'shortcode_generator' ),
            ) );

            $cmb->object_type( 'options-page' );

            $boxes[] = $cmb;

            return $boxes;
        }

        public function tabs() {
            $tabs = array();

            $tabs[] = array(
                'id'    => 'general',
                'title' => 'General',
                'desc'  => '',
                'boxes' => array(
                    $this->options_key . '_general',
                    $this->options_key . '_style',
                    $this->options_key . '_infinite_scrolling_animations',
                ),
            );

            return $tabs;
        }

        /**
         * Submit box
         *
         * @param array      $field_args
         * @param CMB2_Field $field
         */
        public function submit_box( $field_args, $field ) {
            ?>
            <p>
                <a href="<?php echo tsunoa_product_docs_url( $this->options_key ); ?>" target="_blank" class="uframework-icon-link"><i class="dashicons dashicons-media-text"></i> <?php _e( 'Documentation' ); ?></a>
                <a href="<?php echo tsunoa_product_url( $this->options_key ); ?>" target="_blank" class="uframework-icon-link"><i class="dashicons dashicons-cart"></i> <?php _e( 'Get support and pro features', 'edd-ajax-search' ); ?></a>
            </p>
            <div class="cmb2-actions">
                <input type="submit" name="submit-cmb" value="<?php _e( 'Save changes' ); ?>" class="button-primary">
            </div>
            <?php
        }

        /**
         * Shortcode generator
         *
         * @param array      $field_args
         * @param CMB2_Field $field
         */
        public function shortcode_generator( $field_args, $field ) {
            ?>
            <div id="edd-infinite-scrolling-shortcode-form" class="uframework-shortcode-generator">
                <p>
                    <textarea type="text" id="edd-infinite-scrolling-shortcode-input" data-shortcode="downloads" readonly="readonly">[downloads infinite_scrolling="yes"]</textarea>
                </p>

                <input type="hidden" id="shortcode_form_infinite_scrolling" data-shortcode-attr="infinite_scrolling" value="yes">
            </div>
            <?php
        }

    }

}