<?php
// Enqueue necessary files
function my_child_theme_enqueue_styles()
{
    // Parent Theme Style
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style', get_stylesheet_uri(), array('parent-style'));

    // Google Fonts: Open Sans
    wp_enqueue_style('open-sans-font', 'https://fonts.googleapis.com/css2?family=Open+Sans&display=swap', false);
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css?family=Leckerli+One', false);

    // Bootstrap CSS & JS
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css');
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', array(), false, true);

    // Custom JavaScript
    wp_enqueue_script('script-js', get_stylesheet_directory_uri() . '/script.js', array(), false, true);
}
add_action('wp_enqueue_scripts', 'my_child_theme_enqueue_styles');



// Enqueue /assets/js/menu-custom.js JavaScript to Inject Logo & Search in Modal (Display: Navigation Page)
function inject_logo_and_search_script()
{
    wp_enqueue_script(
        'menu-custom-js',
        get_stylesheet_directory_uri() . '/assets/js/menu-custom.js', // Fixes incorrect path
        array('jquery'),
        null,
        true
    );
}
add_action('wp_enqueue_scripts', 'inject_logo_and_search_script');


// Register Menus (Display: Navigation Menu)
function my_child_theme_menus()
{
    register_nav_menus([
        'primary' => __('Primary Menu', 'twentytwentyfive-child')
    ]);
}
add_action('after_setup_theme', 'my_child_theme_menus');


// Register Footer Menus (Display: Footer section)
function my_child_theme_footer_menus()
{
    register_nav_menus([
        'footer_quick_links' => __('Footer Quick Links', 'twentytwentyfive-child'),
        'footer_student_board' => __('Footer Student Board', 'twentytwentyfive-child'),
    ]);
}
add_action('after_setup_theme', 'my_child_theme_footer_menus'); //   FIXED: Now this runs!

// // Debug: WP_Query availability (for testing WP_Query is missing ot not!)
// add_action('init', function () {
//     if (class_exists('WP_Query')) {
//         error_log('WP_Query is available!');
//     } else {
//         error_log('WP_Query is missing!');
//     }
// });


// Sorting events based on its event date 
function display_sorted_events()
{
    $current_date = current_time('Y-m-d H:i:s');
    $two_weeks_later = date('Y-m-d H:i:s', strtotime('+14 days', strtotime($current_date)));
    ob_start();

    echo '<div class="events-split-layout">';

    // !!!!!! Left Column: Upcoming Events (next 2 weeks) !!!!!!
    echo '<div class="events-section left-events">';
    echo '<h2 class="events-title">Upcoming Events</h2>';

    $upcoming_2weeks = new WP_Query(array(
        'post_type'      => 'tribe_events',
        'posts_per_page' => -1,
        'meta_key'       => '_EventStartDate',
        'orderby'        => 'meta_value',
        'order'          => 'ASC',
        'meta_query'     => array(
            array(
                'key'     => '_EventStartDate',
                'value'   => $current_date,
                'compare' => '>=',
                'type'    => 'DATETIME'
            ),
            array(
                'key'     => '_EventStartDate',
                'value'   => $two_weeks_later,
                'compare' => '<=',
                'type'    => 'DATETIME'
            )
        )
    ));

    echo '<div class="events-grid">';

    if ($upcoming_2weeks->have_posts()) {
        while ($upcoming_2weeks->have_posts()) {
            $upcoming_2weeks->the_post();
            $event_date = get_post_meta(get_the_ID(), '_EventStartDate', true);
            $event_description = get_the_excerpt();
            $event_image = get_the_post_thumbnail_url(get_the_ID(), 'large');

            echo '<div class="event-item">';
            if ($event_image) {
                echo '<img class="event-image" src="' . esc_url($event_image) . '" alt="' . esc_attr(get_the_title()) . '">';
            }
            echo '<div class="event-content">';
            echo '<div class="event-title">' . get_the_title() . '</div>';
            echo '<div class="event-date">Date: ' . esc_html(date('F j, Y g:i a', strtotime($event_date))) . '</div>';
            echo '<p class="event-description">' . esc_html($event_description) . '</p>';
            echo '<a href="' . get_permalink() . '" class="event-link">View Details</a>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo '<p style="text-align:center;">No events in the next 2 weeks.</p>';
    }
    echo '</div>'; // end events-grid
    wp_reset_postdata();
    echo '</div>'; // end left-events

    // !!!!!! Right Column: Future Events (after 2 weeks) !!!!!!
    echo '<div class="events-section right-events">';
    echo '<h2 class="events-title">Future Events</h2>';

    $future_events = new WP_Query(array(
        'post_type'      => 'tribe_events',
        'posts_per_page' => -1,
        'meta_key'       => '_EventStartDate',
        'orderby'        => 'meta_value',
        'order'          => 'ASC',
        'meta_query'     => array(
            array(
                'key'     => '_EventStartDate',
                'value'   => $two_weeks_later,
                'compare' => '>',
                'type'    => 'DATETIME'
            )
        )
    ));

    echo '<ul class="events-list">';
    if ($future_events->have_posts()) {
        while ($future_events->have_posts()) {
            $future_events->the_post();
            $event_date = get_post_meta(get_the_ID(), '_EventStartDate', true);
            echo '<li class="event-list-item">';
            echo '<strong>' . get_the_title() . '</strong> — ';
            echo '<span>' . esc_html(date('F j, Y g:i a', strtotime($event_date))) . '</span>';
            echo ' — <a href="' . get_permalink() . '" class="event-link">View Details</a>';
            echo '</li>';
        }
    } else {
        echo '<p style="text-align:center;">No future events found.</p>';
    }
    echo '</ul>';
    echo '</div>'; // end right-events

    echo '</div>'; // end events-split-layout

    // !!!!!! Full-Width Past Events Section (below split layout of upcoming & future events) !!!!!!
    $past_events = new WP_Query(array(
        'post_type'      => 'tribe_events',
        'posts_per_page' => -1,
        'meta_key'       => '_EventStartDate',
        'orderby'        => 'meta_value',
        'order'          => 'DESC',
        'meta_query'     => array(
            array(
                'key'     => '_EventStartDate',
                'value'   => $current_date,
                'compare' => '<',
                'type'    => 'DATETIME'
            )
        )
    ));

    echo '<div class="events-section fullwidth-past-events">';
    echo '<h2 class="events-title">Past Events</h2>';
    echo '<ul class="events-list">';
    if ($past_events->have_posts()) {
        while ($past_events->have_posts()) {
            $past_events->the_post();
            $event_date = get_post_meta(get_the_ID(), '_EventStartDate', true);
            echo '<li class="event-list-item">';
            echo '<strong>' . get_the_title() . '</strong> — ';
            echo '<span>' . esc_html(date('F j, Y g:i a', strtotime($event_date))) . '</span>';
            echo ' — <a href="' . get_permalink() . '" class="event-link">View Details</a>';
            echo '</li>';
        }
    } else {
        echo '<p style="text-align:center;">No past events found.</p>';
    }
    echo '</ul>';
    echo '</div>'; // end fullwidth-past-events
    wp_reset_postdata();

    return ob_get_clean();
}
add_shortcode('sorted_events', 'display_sorted_events'); // Use shortcode: [sorted_events] for displaying

//Disbale auto dropdown option on main menu
function disable_wp_block_navigation_dropdown()
{
?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let menuItems = document.querySelectorAll(".wp-block-navigation-item.has-child > a");

            menuItems.forEach(function(menuItem) {
                menuItem.removeAttribute("aria-haspopup"); 
                menuItem.removeAttribute("aria-expanded"); 
                menuItem.parentElement.classList.remove("has-child"); 
            });

            console.log("WordPress block navigation dropdown disabled.");
        });
    </script>
<?php
}
add_action('wp_footer', 'disable_wp_block_navigation_dropdown');

//KW Contributor
// Handle form submission
function remove_old_fontawesome() {
    wp_dequeue_style('font-awesome'); 
    wp_deregister_style('font-awesome');
}
add_action('wp_enqueue_scripts', 'remove_old_fontawesome', 20);

function load_fontawesome6() {
    wp_enqueue_style('font-awesome-6', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css', array(), '6.4.2');
}
add_action('wp_enqueue_scripts', 'load_fontawesome6');

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
    <button type="button" class="btn register-btn" data-bs-toggle="modal" data-bs-target="#studentFormModal">REGISTER NOW <span class="arrow">&#8594;</span>
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
                    <form class="event-registration-form" method="POST" action="<?php echo esc_url(home_url($_SERVER['REQUEST_URI'])); ?>">
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

/* Shortcode to display event details with social media icons */
function dynamic_event_details() {
    // Check if we are on a single event page
    if (!is_singular('tribe_events')) {
        return '<p>This shortcode only works on event pages.</p>';
    }

    // Get the event ID
    $event_id = get_the_ID();

    // Fetch event details using The Events Calendar plugin functions
    $event_date = tribe_get_start_date($event_id, false, 'F j, Y'); // Format: "November 19, 2024"
    $event_time = tribe_get_start_time($event_id) . ' - ' . tribe_get_end_time($event_id); // Start & end time
    $event_location = tribe_get_venue($event_id); // Get venue name
    $event_image = get_the_post_thumbnail_url($event_id, 'large'); // Fetch featured image

    // Start output buffering to capture HTML content
    ob_start();
    ?>

  <!-- Single Page Event Container -->
        <div class="single_event_container">
            <h2 class="single_event_detail">Event Details</h2> <!-- Event Details -->

            <div class="single_event_content">
                <!-- Right Section: Event Details & Registration -->
                <div class="single_event_right">
                    
                    <!-- Date & Time Section -->
                    <div class="event_info">
                        <h3>Date and Time</h3>
                        <p><i class="fa-solid fa-calendar-days"></i> <?php echo esc_html($event_date); ?></p>
                        <p><i class="fa-solid fa-clock"></i> <?php echo esc_html($event_time); ?></p>
                    </div>

                    <!-- Location Section -->
                    <div class="event_info">
                        <h3>Location</h3>
                        <p><i class="fa-solid fa-location-dot"></i> <?php echo esc_html($event_location); ?></p>
                    </div>

                    <!-- Social Media Links with Icons -->
                    <div class="event_info">
                        <div class="event_social">
                            <h3>Follow us</h3>
                                <p class="social-icons">
                                    <a href="#" target="_blank"><i class="fa-brands fa-facebook"></i></a>
                                    <a href="#" target="_blank"><i class="fa-brands fa-instagram"></i></a>
                                    <a href="#" target="_blank"><i class="fa-brands fa-youtube"></i></a>
                                </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    // Return the buffered content as shortcode output
    return ob_get_clean();
}

// Register the shortcode [dynamic_event_details]
add_shortcode('dynamic_event_details', 'dynamic_event_details');

// Function to display upcoming events (fetching 2 nearest events)
function upcoming_events_shortcode() {
    // Get today's date in UTC format
    $today = gmdate('Y-m-d H:i:s');

    // Check if a single event post is displayed
    $excluded_event_id = is_singular('tribe_events') ? get_the_ID() : null;

    // Query arguments for fetching 2 upcoming events
    $args = array(
        'post_type'      => 'tribe_events', // Fetch events from The Events Calendar plugin
        'posts_per_page' => 2, // Limit to only 2 events
        'meta_key'       => '_EventStartDate', // Use event start date for sorting
        'orderby'        => 'meta_value',
        'order'          => 'ASC', // Earliest events first
        'meta_query'     => array(
            array(
                'key'     => '_EventStartDate', // Compare event start date
                'value'   => $today,
                'compare' => '>=', // Events must be today or later
                'type'    => 'DATETIME' // Ensure correct type
            ),
        ),
    );

    // If viewing a single event, exclude it from the query
    if ($excluded_event_id) {
        $args['post__not_in'] = array($excluded_event_id);
    }

    $events = new WP_Query($args); // Execute the query

    // If no upcoming events are found, return a message
    if (!$events->have_posts()) {
        return '<div class="no-events">No upcoming events found.</div>';
    }

    // Start output buffering
    ob_start();
    ?>
    <div class="upcoming_events_section">
        <h2 class="upcoming_events_header">Upcoming Events</h2>

        <div class="upcoming_events_container">
            <?php 
            while ($events->have_posts()) : $events->the_post(); 
                $event_id = get_the_ID();
                $event_title = get_the_title();
                $event_date = tribe_get_start_date($event_id, false, 'M j'); // Example: "Mar 22"
                $event_year = tribe_get_start_date($event_id, false, 'Y'); // Example: "2025"
                $event_permalink = get_permalink();
                $event_thumbnail = get_the_post_thumbnail_url($event_id, 'large');

                // Use a placeholder image if no thumbnail exists
                if (!$event_thumbnail) {
                    $event_thumbnail = 'https://via.placeholder.com/400x250?text=Event+Image';
                }
            ?>
                <div class="event_card">
                    <a href="<?php echo esc_url($event_permalink); ?>" class="event_thumbnail_link">
                        <div class="event_thumbnail" style="background-image: url('<?php echo esc_url($event_thumbnail); ?>');">
                            <div class="event_date">
                                <span class="event_month"><?php echo esc_html($event_date); ?></span>
                                <span class="event_year"><?php echo esc_html($event_year); ?></span>
                            </div>
                        </div>
                    </a>

                    <div class="event_details">
                        <h3><a href="<?php echo esc_url($event_permalink); ?>"><?php echo esc_html($event_title); ?></a></h3>
                    </div>
                </div>

            <?php endwhile; ?>
        </div>

        <div class="more_events_button_container">
            <a href="<?php echo esc_url(get_post_type_archive_link('tribe_events')); ?>" class="more_events_button">MORE EVENTS <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>

    <?php
    wp_reset_postdata(); // Reset WordPress query

    // Clean output and remove auto <p> tags
    return force_clean_shortcode_output(ob_get_clean());
}

// Function to remove unwanted <p> and <br> tags in shortcodes
function force_clean_shortcode_output($content) {
    $content = preg_replace('/<p>|<\/p>|<br\s*\/?>/', '', $content); // Remove <p> and <br> tags
    return trim($content);
}

// Register the shortcode [upcoming_events]
add_shortcode('upcoming_events', 'upcoming_events_shortcode');
