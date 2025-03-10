<?php
// Enqueue parent theme styles
function my_child_theme_enqueue_styles() {
    // Load parent theme styles
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    // Load child theme styles
    wp_enqueue_style('child-style', get_stylesheet_uri(), array('parent-style'));

    // Enqueue custom JavaScript file
    wp_enqueue_script('script-js', get_stylesheet_directory_uri() . '/script.js', array(), false, true);
}
add_action('wp_enqueue_scripts', 'my_child_theme_enqueue_styles');



/*Test n Debug*/
add_action('init', function() {
    if (class_exists('WP_Query')) {
        error_log('WP_Query is available!');
    } else {
        error_log('WP_Query is missing!');
    }
});

/*Shortcode to display sorted events */
function display_sorted_events() {
    $current_date = current_time('Y-m-d');
    ob_start(); // Start output buffering

    // Query for upcoming events
    $upcoming_events = new WP_Query(array(
        'category_name'  => 'events',
        'posts_per_page' => 6,
        'meta_key'       => 'event_date',
        'orderby'        => 'meta_value',
        'order'          => 'ASC',
        'meta_query'     => array(
            array(
                'key'     => 'event_date',
                'value'   => $current_date,
                'compare' => '>='
            )
        )
    ));

    echo '<div class="events-section">';
    echo '<h2 class="events-title">Upcoming Events</h2>';
    echo '<div class="events-grid">';
    
    if ($upcoming_events->have_posts()) {
        while ($upcoming_events->have_posts()) {
            $upcoming_events->the_post();
            $event_date = get_field('event_date');
            $event_description = get_field('event_description');
            $event_image = get_the_post_thumbnail_url(get_the_ID(), 'large'); // Get featured image

            echo '<div class="event-item">';
            if ($event_image) {
                echo '<img class="event-image" src="' . esc_url($event_image) . '" alt="' . esc_attr(get_the_title()) . '">';
            }
            echo '<div class="event-content">'; // White box inside
            echo '<div class="event-title">' . get_the_title() . '</div>';
            echo '<div class="event-date">Date: ' . esc_html($event_date) . '</div>';
            echo '<p class="event-description">' . esc_html($event_description) . '</p>';
            echo '<p>' . get_the_excerpt() . '</p>';
            echo '<a href="' . get_permalink() . '" class="event-link">View Details</a>';
            echo '</div>';
            echo '</div>'; // End .event-item
        }
    } else {
        echo '<p style="text-align:center;">No upcoming events.</p>';
    }

    echo '</div>';
    echo '</div>'; // End Upcoming Events section

    wp_reset_postdata();

    // Query for past events
    $past_events = new WP_Query(array(
        'category_name'  => 'events',
        'posts_per_page' => 6,
        'meta_key'       => 'event_date',
        'orderby'        => 'meta_value',
        'order'          => 'DESC',
        'meta_query'     => array(
            array(
                'key'     => 'event_date',
                'value'   => $current_date,
                'compare' => '<'
            )
        )
    ));

    echo '<div class="events-section">';
    echo '<h2 class="events-title">Past Events</h2>';
    echo '<div class="events-grid">';
    
    if ($past_events->have_posts()) {
        while ($past_events->have_posts()) {
            $past_events->the_post();
            $event_date = get_field('event_date');
            $event_description = get_field('event_description');
            $event_image = get_the_post_thumbnail_url(get_the_ID(), 'large'); // Get featured image

            echo '<div class="event-item">';
            if ($event_image) {
                echo '<img class="event-image" src="' . esc_url($event_image) . '" alt="' . esc_attr(get_the_title()) . '">';
            }
            echo '<div class="event-content">'; // White box inside
            echo '<div class="event-title">' . get_the_title() . '</div>';
            echo '<div class="event-date">Date: ' . esc_html($event_date) . '</div>';
            echo '<p class="event-description">' . esc_html($event_description) . '</p>';
            echo '<p>' . get_the_excerpt() . '</p>';
            echo '<a href="' . get_permalink() . '" class="event-link">View Details</a>';
            echo '</div>';
            echo '</div>'; // End .event-item
        }
    } else {
        echo '<p style="text-align:center;">No past events.</p>';
    }

    echo '</div>';
    echo '</div>'; // End Past Events section

    wp_reset_postdata();

    return ob_get_clean(); // Return buffered output
}
add_shortcode('sorted_events', 'display_sorted_events');


//Contributor KW
// Handle form submission
add_action('init', 'handle_student_registration_form_submission');

function handle_student_registration_form_submission() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_registration_form']) && $_POST['student_registration_form'] == '1') {
        global $wpdb;
        $table_name = $wpdb->prefix . 'student_registrations';

        $fullName = sanitize_text_field($_POST['fullName']);
        $studentID = sanitize_text_field($_POST['studentID']);
        $studentMail = sanitize_email($_POST['studentMail']);
        $studentPhone = sanitize_text_field($_POST['studentPhone']);
        $studentDegree = sanitize_text_field($_POST['studentDegree']);
        $studentTrimester = sanitize_text_field($_POST['studentTrimester']);
        $consent = isset($_POST['consent']) ? 1 : 0;

        $inserted = $wpdb->insert($table_name, [
            'full_name' => $fullName,
            'student_id' => $studentID,
            'student_mail' => $studentMail,
            'student_phone' => $studentPhone,
            'student_degree' => $studentDegree,
            'student_trimester' => $studentTrimester,
            'consent' => $consent,
            'created_at' => current_time('mysql')
        ]);

        if ($inserted !== false) {
            set_transient('student_form_message', '✅ Form submitted successfully!', 30);
        } else {
            set_transient('student_form_message', '❌ Failed to submit the form. Please try again.', 30);
        }

        wp_redirect(add_query_arg('form_submitted', '1', home_url($_SERVER['REQUEST_URI'])));
        exit;
    }
}

// Shortcode to display form button and modal
function display_student_registration_form() {
    $message = get_transient('student_form_message');
    $form_submitted = isset($_GET['form_submitted']) && $_GET['form_submitted'] === '1' && !empty($message);
    delete_transient('student_form_message');

    ob_start();
    ?>
    <!-- Bootstrap CSS & JS (only load if not already loaded by theme) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <div id="formWrapper" style="<?php echo $form_submitted ? 'display:none;' : ''; ?>">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#studentFormModal">
            REGISTER NOW
        </button>
    </div>

    <div class="modal fade" id="studentFormModal" tabindex="-1" aria-labelledby="studentFormModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Student Registration Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="<?php echo esc_url(home_url($_SERVER['REQUEST_URI'])); ?>">
                        <input type="hidden" name="student_registration_form" value="1">

                        <div class="mb-3">
                            <label>Full Name *</label>
                            <input type="text" name="fullName" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Student ID *</label>
                            <input type="text" name="studentID" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Email *</label>
                            <input type="email" name="studentMail" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Phone Number *</label>
                            <input type="text" name="studentPhone" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Degree</label>
                            <input type="text" name="studentDegree" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Trimester</label>
                            <input type="text" name="studentTrimester" class="form-control">
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="consent" required>
                            <label class="form-check-label">I agree to event photos/videos being used for publicity.</label>
                        </div>

                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-success">SUBMIT</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php if ($form_submitted): ?>
        <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Success</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <?php echo esc_html($message); ?>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();

                document.getElementById('successModal').addEventListener('hidden.bs.modal', function () {
                    document.getElementById('formWrapper').style.display = 'block';
                    // Clean the URL (remove ?form_submitted=1)
                    if (history.replaceState) {
                        const url = new URL(window.location.href);
                        url.searchParams.delete('form_submitted');
                        history.replaceState(null, '', url.toString());
                    }
                });
            });
        </script>
    <?php endif; ?>

    <?php
    return ob_get_clean();
}
add_shortcode('student_registration_form', 'display_student_registration_form');