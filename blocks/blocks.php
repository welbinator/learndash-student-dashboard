<?php
/**
 * Registers and enqueues block editor assets for the LearnDash LMS - Student Dashboard plugin.
 *
 * This file contains functions to register and enqueue block editor assets for the plugin.
 * It includes the registration of custom blocks and enqueuing of necessary scripts for the block editor.
 *
 * @package LearnDashLMS\StudentDashboard\Blocks
 */

namespace LearnDashLMS\StudentDashboard\Blocks;

/**
 * Enqueues block editor assets for the plugin.
 */
function enqueue_block_editor_assets() {
    if ( function_exists( 'get_current_screen' ) ) {
        $screen = get_current_screen();
        if ( $screen && $screen->is_block_editor() ) {
            wp_enqueue_script(
                'ldsd-course-list-block',
                plugin_dir_url( __FILE__ ) . '../build/course-list/index.js',
                array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),
                filemtime( plugin_dir_path( __FILE__ ) . '../build/course-list/index.js' ),
                true
            );
        }
    }
}
add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\enqueue_block_editor_assets' );

/**
 * Adds a custom block category for the plugin blocks.
 *
 * @param array $categories Existing block categories.
 * @return array Updated list of block categories.
 */
function add_block_category( $categories ) {
    return array_merge(
        $categories,
        array(
            array(
                'slug'  => 'learndash-lms',
                'title' => __( 'LearnDash LMS', 'ldsd' ),
            ),
        )
    );
}
add_filter( 'block_categories_all', __NAMESPACE__ . '\\add_block_category', 10, 2 );

