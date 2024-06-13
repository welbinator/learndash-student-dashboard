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

 function enqueue_block_editor_assets() {
     if ( function_exists( 'get_current_screen' ) ) {
         $screen = get_current_screen();
         if ( $screen && $screen->is_block_editor() ) {
             // Enqueue script and style for the Course List block
             wp_enqueue_script(
                 'ldsd-course-list-block',
                 plugin_dir_url( __FILE__ ) . '../build/course-list/index.js',
                 array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),
                 filemtime( plugin_dir_path( __FILE__ ) . '../build/course-list/index.js' ),
                 true
             );
 
             $courses = \LearnDashLMS\StudentDashboard\Blocks\CourseList\get_enrolled_courses();
             wp_localize_script( 'ldsd-course-list-block', 'ldsdCourseListData', array(
                 'courses' => $courses
             ));
 
             // Enqueue script and style for the Certificates block
             wp_enqueue_script(
                 'ldsd-certificates-block',
                 plugin_dir_url( __FILE__ ) . '../build/certificates/index.js',
                 array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),
                 filemtime( plugin_dir_path( __FILE__ ) . '../build/certificates/index.js' ),
                 true
             );
 
             $certificates = \LearnDashLMS\StudentDashboard\Blocks\Certificates\get_certificates();
             wp_localize_script( 'ldsd-certificates-block', 'ldsdCertificatesData', array(
                 'certificates' => $certificates
             ));
         }
     }
 }
 
 add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\\enqueue_block_editor_assets' );
 
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
 