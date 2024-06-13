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
 * Registers the block using metadata loaded from the `block.json` file.
 * Sets the render callback to the `block_render` function.
 */
function register_block() {

    $course_list_block_path = plugin_dir_path( dirname( __DIR__ ) ) . 'build/course-list';
    register_block_type_from_metadata(
        $course_list_block_path,
        array(
            'render_callback' => __NAMESPACE__ . '\block_render',
        )
    );
}
add_action( 'init', __NAMESPACE__ . '\\register_block' );

/**
 * Renders the 'Course List' block.
 *
 * Outputs the HTML for the 'Course List' block.
 *
 * @param array $attributes The attributes of the block.
 * @return string The HTML content to display.
 */
function block_render( $attributes ) {
    ob_start();
    ?>
    <div class="course-list-block">
        <h2>Course List</h2>
        <ul>
            <li>Course 1</li>
            <li>Course 2</li>
            <li>Course 3</li>
        </ul>
    </div>
    <?php
    return ob_get_clean();
}

