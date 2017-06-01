<?php
/**
 * Scripts
 *
 * @package     EDD\Infinite_Scrolling\Scripts
 * @since       1.0.0
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'EDD_Infinite_Scrolling_Scripts' ) ) {

    class EDD_Infinite_Scrolling_Scripts {

        public function __construct() {
            // Register scripts
            add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );

            // Enqueue frontend scripts
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 100 );
        }

        /**
         * Register scripts
         *
         * @since       1.0.0
         * @return      void
         */
        public function register_scripts() {
            // Use minified libraries if SCRIPT_DEBUG is turned off
            $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

            // Stylesheets
            wp_register_style( 'edd-infinite-scrolling-animate-css', EDD_INFINITE_SCROLLING_URL . 'assets/css/animate' . $suffix . '.css', array( ), EDD_INFINITE_SCROLLING_VER, 'all' );
            wp_register_style( 'edd-infinite-scrolling-css', EDD_INFINITE_SCROLLING_URL . 'assets/css/edd-infinite-scrolling' . $suffix . '.css', array( ), EDD_INFINITE_SCROLLING_VER, 'all' );

            // Scripts
            wp_register_script( 'edd-infinite-scrolling-js', EDD_INFINITE_SCROLLING_URL . 'assets/js/edd-infinite-scrolling' . $suffix . '.js', array( 'jquery' ), EDD_INFINITE_SCROLLING_VER, true );
        }

        /**
         * Enqueue frontend scripts
         *
         * @since       1.0.0
         * @return      void
         */
        public function enqueue_scripts( $hook ) {
            // Localize scripts
            $script_parameters = array(
                'ajax_url'              => admin_url( 'admin-ajax.php' ),
                'nonce'	                => wp_create_nonce( 'edd_infinite_scrolling_nonce' ),
                'in_animation'          => edd_infinite_scrolling()->options->get( 'infinite_scrolling_in_animation', '' ),
            );

            wp_localize_script( 'edd-infinite-scrolling-js', 'edd_infinite_scrolling', $script_parameters );

            // Stylesheets
            wp_enqueue_style('edd-infinite-scrolling-animate-css');
            wp_enqueue_style('edd-infinite-scrolling-css');

            // Scripts
            wp_enqueue_script( 'edd-infinite-scrolling-js' );
        }

    }

}