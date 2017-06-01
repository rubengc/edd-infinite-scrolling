<?php
/**
 * Plugin Name:     EDD Infinite Scrolling
 * Plugin URI:      https://wordpress.org/plugins/edd-infinite-scrolling
 * Description:     Product lists infinite scrolling for Easy Digital Downloads.
 * Version:         1.0.0
 * Author:          Tsunoa
 * Author URI:      https://tsunoa.com
 * Text Domain:     edd-infinite-scrolling
 *
 * @package         EDD\Infinite_Scrolling
 * @author          Tsunoa
 * @copyright       Copyright (c) Tsunoa
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'EDD_Infinite_Scrolling' ) ) {

    /**
     * Main EDD_Infinite_Scrolling class
     *
     * @since       1.0.0
     */
    class EDD_Infinite_Scrolling {

        /**
         * @var         EDD_Infinite_Scrolling $instance The one true EDD_Infinite_Scrolling
         * @since       1.0.0
         */
        private static $instance;

        /**
         * @var         EDD_Infinite_Scrolling_Functions EDD Infinite Scrolling functions
         * @since       1.0.0
         */
        public $functions;

        /**
         * @var         EDD_Infinite_Scrolling_Options EDD Infinite Scrolling options
         * @since       1.0.0
         */
        public $options;

        /**
         * @var         EDD_Infinite_Scrolling_Scripts EDD Infinite Scrolling scripts
         * @since       1.0.0
         */
        public $scripts;


        /**
         * Get active instance
         *
         * @access      public
         * @since       1.0.0
         * @return      object self::$instance The one true EDD_Infinite_Scrolling
         */
        public static function instance() {
            if( !self::$instance ) {
                self::$instance = new EDD_Infinite_Scrolling();
                self::$instance->setup_constants();
                self::$instance->includes();
                self::$instance->load_textdomain();
                self::$instance->hooks();
            }

            return self::$instance;
        }


        /**
         * Setup plugin constants
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function setup_constants() {
            // Plugin version
            define( 'EDD_INFINITE_SCROLLING_VER', '1.0.0' );

            // Plugin path
            define( 'EDD_INFINITE_SCROLLING_DIR', plugin_dir_path( __FILE__ ) );

            // Plugin URL
            define( 'EDD_INFINITE_SCROLLING_URL', plugin_dir_url( __FILE__ ) );
        }

        /**
         * Include necessary files
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function includes() {
            require_once EDD_INFINITE_SCROLLING_DIR . 'uFramework/uFramework.php';

            // Include scripts
            require_once EDD_INFINITE_SCROLLING_DIR . 'includes/functions.php';
            require_once EDD_INFINITE_SCROLLING_DIR . 'includes/options.php';
            require_once EDD_INFINITE_SCROLLING_DIR . 'includes/scripts.php';


            $this->functions = new EDD_Infinite_Scrolling_Functions();
            $this->options = new EDD_Infinite_Scrolling_Options();
            $this->scripts = new EDD_Infinite_Scrolling_Scripts();
        }

        /**
         * Run action and filter hooks
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function hooks() {

        }

        /**
         * Internationalization
         *
         * @access      public
         * @since       1.0.0
         * @return      void
         */
        public function load_textdomain() {
            // Set filter for language directory
            $lang_dir = EDD_INFINITE_SCROLLING_DIR . '/languages/';
            $lang_dir = apply_filters( 'edd_infinite_scrolling_languages_directory', $lang_dir );

            // Traditional WordPress plugin locale filter
            $locale = apply_filters( 'plugin_locale', get_locale(), 'edd-infinite-scrolling' );
            $mofile = sprintf( '%1$s-%2$s.mo', 'edd-infinite-scrolling', $locale );

            // Setup paths to current locale file
            $mofile_local   = $lang_dir . $mofile;
            $mofile_global  = WP_LANG_DIR . '/edd-infinite-scrolling/' . $mofile;

            if( file_exists( $mofile_global ) ) {
                // Look in global /wp-content/languages/edd-infinite-scrolling/ folder
                load_textdomain( 'edd-infinite-scrolling', $mofile_global );
            } elseif( file_exists( $mofile_local ) ) {
                // Look in local /wp-content/plugins/edd-infinite-scrolling/languages/ folder
                load_textdomain( 'edd-infinite-scrolling', $mofile_local );
            } else {
                // Load the default language files
                load_plugin_textdomain( 'edd-infinite-scrolling', false, $lang_dir );
            }
        }
    }
}


/**
 * The main function responsible for returning the one true EDD_Infinite_Scrolling
 * instance to functions everywhere
 *
 * @since       1.0.0
 * @return      \EDD_Infinite_Scrolling The one true EDD_Infinite_Scrolling
 */
function edd_infinite_scrolling() {
    return EDD_Infinite_Scrolling::instance();
}
add_action( 'plugins_loaded', 'edd_infinite_scrolling' );


/**
 * EDD_Infinite_Scrolling activation
 *
 * @since       1.0.0
 * @return      void
 */
function edd_infinite_scrolling_activation() {
    // Default option => value
    $options = array(
        'enabled_by_default' => 'on',
    );

    $opts = array();

    foreach($options as $option => $value) {
        $opts[$option] = $value;
    }

    add_option( 'edd-infinite-scrolling', $options );
}
register_activation_hook( __FILE__, 'edd_infinite_scrolling_activation' );