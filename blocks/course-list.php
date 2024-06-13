<?php
/**
 * This file contains functions related to the registration and rendering of the 'Course List' block.
 * It ensures that the block is registered correctly in WordPress and rendered using the corresponding callback function.
 *
 * @package LearnDashLMS\StudentDashboard\Blocks\CourseList
 */

namespace LearnDashLMS\StudentDashboard\Blocks\CourseList;

/**
 * Initializes the 'Course List' block.
 *
 * Manually registers the block instead of using metadata loaded from the `block.json` file.
 */
function register_block() {
    register_block_type('ldsd/course-list', array(
        'attributes'      => array(
            'blockTitle' => array(
                'type'    => 'string',
                'default' => 'Course List',
            ),
        ),
        'editor_script'   => 'ldsd-course-list-editor-script',
        'render_callback' => __NAMESPACE__ . '\block_render',
    ));

    // Enqueue block editor assets
    wp_register_script(
        'ldsd-course-list-editor-script',
        plugins_url( 'build/course-list/index.js', dirname(__FILE__) ),
        array( 'wp-blocks', 'wp-element', 'wp-editor' ),
        filemtime( plugin_dir_path( dirname(__FILE__) ) . 'build/course-list/index.js' )
    );

    // Enqueue block editor styles
    wp_register_style(
        'ldsd-course-list-editor-style',
        plugins_url( 'build/course-list/index.css', dirname(__FILE__) ),
        array(),
        filemtime( plugin_dir_path( dirname(__FILE__) ) . 'build/course-list/index.css' )
    );

    // Enqueue block frontend styles
    wp_register_style(
        'ldsd-course-list-style',
        plugins_url( 'build/course-list/style-index.css', dirname(__FILE__) ),
        array(),
        filemtime( plugin_dir_path( dirname(__FILE__) ) . 'build/course-list/style-index.css' )
    );
}
add_action( 'init', __NAMESPACE__ . '\\register_block' );

/**
 * Fetches the courses the current user is enrolled in.
 *
 * @return array The list of enrolled courses.
 */
function get_enrolled_courses() {
    $user_id = get_current_user_id();
    if ( ! $user_id ) {
        return [];
    }

    $courses = learndash_user_get_enrolled_courses( $user_id );

    if ( empty( $courses ) ) {
        return [];
    }

    $course_list = [];
    foreach ( $courses as $course_id ) {
        $course_list[] = get_the_title( $course_id );
    }

    return $course_list;
}

/**
 * Renders the 'Course List' block.
 *
 * Outputs the HTML for the 'Course List' block.
 *
 * @param array $attributes The attributes of the block.
 * @return string The HTML content to display.
 */
function block_render( $attributes ) {
    $blockTitle = isset($attributes['blockTitle']) ? $attributes['blockTitle'] : 'Course List';
    $courses = get_enrolled_courses();

    // Log the courses data
    error_log('Courses data: ' . print_r($courses, true));

    ob_start();
    ?>
    <div class="course-list-block">
        <h2><?php echo esc_html( $blockTitle ); ?></h2>
        <?php if ( ! empty( $courses ) ) : ?>
            <ul>
                <?php foreach ( $courses as $course ) : ?>
                    <li><?php echo esc_html( $course ); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <p><?php esc_html_e( 'User is not enrolled in any courses', 'ldsd' ); ?></p>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}
