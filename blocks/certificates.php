<?php
/**
 * Handles the registration and rendering of the 'Certificates' block for LearnDash LMS - Student Dashboard.
 *
 * This file contains functions to register and render the 'Certificates' block. The block displays the certificates
 * earned by the current user in a grid layout.
 *
 * @package LearnDashLMS\StudentDashboard\Blocks\Certificates
 */

namespace LearnDashLMS\StudentDashboard\Blocks\Certificates;

/**
 * Fetches the certificates for the current user.
 *
 * @return array The list of certificates.
 */
function get_certificates() {
    // Ensure the LearnDash function exists
    if ( ! function_exists( 'learndash_get_course_certificate_link' ) ) {
        error_log('LearnDash function learndash_get_course_certificate_link does not exist.');
        return [];
    }

    $user_id = get_current_user_id();
    if ( ! $user_id ) {
        error_log('No user ID found.');
        return [];
    }

    $certificates = array();
    $courses = learndash_user_get_enrolled_courses( $user_id );
    if ( empty( $courses ) ) {
        error_log('No enrolled courses found for user ID: ' . $user_id);
    } else {
        foreach ( $courses as $course_id ) {
            $certificate_url = learndash_get_course_certificate_link( $course_id, $user_id );
            error_log('Course ID: ' . $course_id . ' | Certificate URL: ' . $certificate_url);
            if ( $certificate_url ) {
                $certificates[] = array(
                    'course_id' => $course_id,
                    'course_title' => get_the_title( $course_id ),
                    'certificate_url' => $certificate_url
                );
            }
        }
    }

    return $certificates;
}

/**
 * Registers the 'Certificates' block.
 */
function register_block() {
    register_block_type( 'ldsd/certificates', array(
        'attributes'      => array(
            'blockTitle' => array(
                'type'    => 'string',
                'default' => "Certificates you've earned",
            ),
        ),
        'editor_script'   => 'ldsd-certificates-block',
        'render_callback' => __NAMESPACE__ . '\block_render',
    ));
}
add_action( 'init', __NAMESPACE__ . '\\register_block' );

/**
 * Renders the 'Certificates' block.
 *
 * Outputs the HTML for the 'Certificates' block.
 *
 * @param array $attributes The attributes of the block.
 * @return string The HTML content to display.
 */
function block_render( $attributes ) {
    $certificates = get_certificates();
    $block_title = isset( $attributes['blockTitle'] ) ? $attributes['blockTitle'] : "Certificates you've earned";

    ob_start();
    ?>
    <div class="certificates-list-block">
        <h2><?php echo esc_html( $block_title ); ?></h2>
        <div class="certificates-grid">
            <?php if ( ! empty( $certificates ) ) : ?>
                <?php foreach ( $certificates as $certificate ) : ?>
                    <div class="certificate-card">
                        <div class="certificate-card__content">
                            <h3 class="certificate-card__title">
                                <a href="<?php echo esc_url( $certificate['certificate_url'] ); ?>" target="_blank">
                                    <?php echo esc_html( $certificate['course_title'] ); ?>
                                </a>
                            </h3>
                            <a class="certificate-card__link" href="<?php echo esc_url( $certificate['certificate_url'] ); ?>" target="_blank">
                                <?php esc_html_e( 'View Certificate', 'ldsd' ); ?>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p><?php esc_html_e( 'No certificates earned yet.', 'ldsd' ); ?></p>
            <?php endif; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
