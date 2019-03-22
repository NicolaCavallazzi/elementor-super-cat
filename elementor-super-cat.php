<?php
/**
* Plugin Name: Elementor SuperCat
* Description: Elementor add-ons
* Plugin URI:  https://cosmo.cat/
* Version:     0.2
* Author:      Nicola Cavallazzi
* Author URI:  https://cosmo.cat/
* Text Domain: elementor-super-cat
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
* Main Elementor Super Cat Class
*
* The init class that runs the Super Cat plugin.
* Intended To make sure that the plugin's minimum requirements are met.
*
* You should only modify the constants to match your plugin's needs.
*
* Any custom code should go inside Plugin Class in the plugin.php file.
* @since 0.1
*/
final class Elementor_Super_Cat {

    /**
    * Plugin Version
    *
    * @since 0.1
    * @var string The plugin version.
    */
    const VERSION = '1.2.0';

    /**
    * Minimum Elementor Version
    *
    * @since 0.1
    * @var string Minimum Elementor version required to run the plugin.
    */
    const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

    /**
    * Minimum PHP Version
    *
    * @since 0.1
    * @var string Minimum PHP version required to run the plugin.
    */
    const MINIMUM_PHP_VERSION = '7.0';

    /**
    * Constructor
    *
    * @since 1.0.0
    * @access public
    */
    public function __construct() {

        // Load translation
        add_action( 'init', array( $this, 'i18n' ) );

        // Init Plugin
        add_action( 'plugins_loaded', array( $this, 'init' ) );

        // Create custom category
        add_action( 'elementor/elements/categories_registered', array( $this, 'create_category' ) );
    }

    /**
    * Create widget category
    *
    * Creates the custom widget category.
    * Fired by `elementor/elements/categories_registered` action hook.
    *
    * @since 0.1
    * @access public
    */
    public function create_category($elements_manager) {
        $elements_manager->add_category(
            'super-cat',
            [
                'title' => __( 'Super Cat', 'super-cat' ),
                'icon' => 'fa fa-plug',
            ]
        );
    }

    /**
    * Load Textdomain
    *
    * Load plugin localization files.
    * Fired by `init` action hook.
    *
    * @since 0.1
    * @access public
    */
    public function i18n() {
        load_plugin_textdomain( 'elementor-super-cat' );
    }

    /**
    * Initialize the plugin
    *
    * Validates that Elementor is already loaded.
    * Checks for basic plugin requirements, if one check fail don't continue,
    * if all check have passed include the plugin class.
    *
    * Fired by `plugins_loaded` action hook.
    *
    * @since 0.1
    * @access public
    */
    public function init() {

        // Check if Elementor installed and activated
        if ( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );
            return;
        }

        // Check for required Elementor version
        if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
            add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );
            return;
        }

        // Check for required PHP version
        if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
            add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
            return;
        }

        // Once we get here, We have passed all validation checks so we can safely include our plugin
        require_once( 'plugin.php' );
    }

    /**
    * Admin notice
    *
    * Warning when the site doesn't have Elementor installed or activated.
    *
    * @since 1.0.0
    * @access public
    */
    public function admin_notice_missing_main_plugin() {
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor */
            esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'elementor-super-cat' ),
            '<strong>' . esc_html__( 'Elementor Super Cat', 'elementor-super-cat' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'elementor-super-cat' ) . '</strong>'
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    /**
    * Admin notice
    *
    * Warning when the site doesn't have a minimum required Elementor version.
    *
    * @since 1.0.0
    * @access public
    */
    public function admin_notice_minimum_elementor_version() {
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-super-cat' ),
            '<strong>' . esc_html__( 'Elementor Super Cat', 'elementor-super-cat' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'elementor-super-cat' ) . '</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    /**
    * Admin notice
    *
    * Warning when the site doesn't have a minimum required PHP version.
    *
    * @since 1.0.0
    * @access public
    */
    public function admin_notice_minimum_php_version() {
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-super-cat' ),
            '<strong>' . esc_html__( 'Elementor Super Cat', 'elementor-super-cat' ) . '</strong>',
            '<strong>' . esc_html__( 'PHP', 'elementor-super-cat' ) . '</strong>',
            self::MINIMUM_PHP_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }
}

// Instantiate Elementor_Super_Cat.
new Elementor_Super_Cat();