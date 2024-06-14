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
                'default' => 'Courses you are enrolled in',
            ),
        ),
        'editor_script'   => 'ldsd-course-list-editor-script',
        'editor_style'    => 'ldsd-course-list-editor-style',
        'style'           => 'ldsd-course-list-style',
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

    return $courses;
}

/**
 * Fetches the enrollment date for a specific course.
 *
 * @param int $user_id The user ID.
 * @param int $course_id The course ID.
 * @return string The enrollment date.
 */
function get_enrollment_date( $user_id, $course_id ) {
    $enrollment_date = get_user_meta( $user_id, 'course_' . $course_id . '_access_from', true );
    if ( $enrollment_date ) {
        return date( 'F j, Y', $enrollment_date );
    }
    return '';
}

/**
 * Extracts the progress percentage from the HTML returned by learndash_course_progress.
 *
 * @param string $progress_html The HTML string returned by learndash_course_progress.
 * @return int The progress percentage.
 */
function extract_progress_percentage( $progress_html ) {
    preg_match( '/(\d+)% Complete/', $progress_html, $matches );
    return isset( $matches[1] ) ? intval( $matches[1] ) : 0;
}

/**
 * Fetches the number of lessons for a specific course.
 *
 * @param int $course_id The course ID.
 * @return int The number of lessons.
 */
function get_number_of_lessons( $course_id ) {
    $lessons_query = new \WP_Query( array(
        'post_type'      => 'sfwd-lessons',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'meta_query'     => array(
            array(
                'key'   => 'course_id',
                'value' => $course_id,
            ),
        ),
    ));

    return $lessons_query->found_posts;
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
    $user_id = get_current_user_id();
    $courses = get_enrolled_courses();

    ob_start();
    ?>
    <div class="course-list-block">
        <h2><?php echo esc_html( $blockTitle ); ?></h2>
        <div class="course-grid">
            <?php if ( ! empty( $courses ) ) : ?>
                <?php foreach ( $courses as $course_id ) : ?>
                    <?php
                     $course_title = get_the_title( $course_id );
                     $course_url = get_permalink( $course_id );
                     $course_image = get_the_post_thumbnail_url( $course_id, 'full' );
                     $progress_html = learndash_course_progress( array( 'user_id' => $user_id, 'course_id' => $course_id ) );
                     $progress_percentage = extract_progress_percentage( $progress_html );
                     $enrollment_date = get_enrollment_date( $user_id, $course_id );
                     $number_of_lessons = get_number_of_lessons( $course_id );
                    ?>
                    <div class="course-card">
                        <div class="course-card__header">
                            <?php if ( $course_image ) : ?>
                                <a href="<?php echo esc_url( $course_url ); ?>"><img src="<?php echo esc_url( $course_image ); ?>" class="course-card__image" alt="<?php echo esc_attr( $course_title ); ?>"></a>
                            <?php endif; ?>
                            
                        </div>
                        <div class="course-card__content">
                            
                            <div class="progress-bar-container">
                                <div class="progress-bar">
                                    <div class="progress-bar__inner" style="width: <?php echo esc_html( $progress_percentage ); ?>%;"></div>
                                </div>
                                <p class="progress-bar__percentage"><?php echo esc_html( $progress_percentage ); ?>%</p>
                            </div>
                            <h3 class="course-card__title">
                                <a href="<?php echo esc_url( $course_url ); ?>">
                                    <?php echo esc_html( $course_title ); ?>
                                </a>
                            </h3>
                            
                        </div>
                        <div class="course-card__footer">
                            <div><p class="course-card__enrollment-date"><span class="dashicons dashicons-calendar-alt"></span> <?php echo esc_html( $enrollment_date ); ?></p></div>
                            <div><p class="course-card__lessons"><span class="dashicons dashicons-text-page"></span><?php echo esc_html( $number_of_lessons ); ?> Lessons</p></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p><?php esc_html_e( 'User is not enrolled in any courses', 'ldsd' ); ?></p>
            <?php endif; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
