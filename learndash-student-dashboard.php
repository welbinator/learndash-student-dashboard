<?php
/*
Plugin Name: LearnDash LMS - Student Dashboard
Plugin URI:  https://example.com/learndash-lms-student-dashboard
Description: Adds a student dashboard to LearnDash LMS.
Version:     1.0.0
Author:      Your Name
Author URI:  https://example.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: ldsd
Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Define plugin constants
define( 'LDSD_VERSION', '1.0.0' );
define( 'LDSD_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'LDSD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include necessary files
require_once LDSD_PLUGIN_DIR . 'includes/functions.php';
require_once LDSD_PLUGIN_DIR . 'includes/class-ldsd-dashboard.php';
require_once LDSD_PLUGIN_DIR . 'includes/class-ldsd-shortcodes.php';
require_once LDSD_PLUGIN_DIR . 'includes/class-ldsd-ajax.php';
require_once plugin_dir_path( __FILE__ ) . 'blocks/blocks.php';
require_once plugin_dir_path( __FILE__ ) . 'blocks/course-list.php';

// Load plugin text domain
function ldsd_load_textdomain() {
    load_plugin_textdomain( 'ldsd', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'ldsd_load_textdomain' );

// Enqueue scripts and styles
function ldsd_enqueue_scripts() {
    wp_enqueue_style( 'ldsd-style', LDSD_PLUGIN_URL . 'assets/css/style.css', array(), LDSD_VERSION );
    wp_enqueue_script( 'ldsd-script', LDSD_PLUGIN_URL . 'assets/js/script.js', array( 'jquery' ), LDSD_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'ldsd_enqueue_scripts' );

// Activation and deactivation hooks
function ldsd_activate() {
    // Activation code here
}
register_activation_hook( __FILE__, 'ldsd_activate' );

function ldsd_deactivate() {
    // Deactivation code here
}
register_deactivation_hook( __FILE__, 'ldsd_deactivate' );



