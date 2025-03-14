<?php
// ✅ Enqueue Parent Theme Styles & Bootstrap
function my_child_theme_enqueue_styles() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style', get_stylesheet_uri(), array('parent-style'));

    // ✅ Bootstrap CSS & JS
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css');
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', array(), false, true);

    // ✅ Custom JavaScript
    wp_enqueue_script('script-js', get_stylesheet_directory_uri() . '/script.js', array(), false, true);
}
add_action('wp_enqueue_scripts', 'my_child_theme_enqueue_styles');

// ✅ Register Menus
function my_child_theme_menus() {
    register_nav_menus([
        'primary' => __('Primary Menu', 'twentytwentyfive-child')
    ]);
}
add_action('after_setup_theme', 'my_child_theme_menus');

// ✅ Register Footer Menus (Now properly added)
function my_child_theme_footer_menus() {
    register_nav_menus([
        'footer_quick_links' => __('Footer Quick Links', 'twentytwentyfive-child'),
        'footer_student_board' => __('Footer Student Board', 'twentytwentyfive-child'),
    ]);
}
add_action('after_setup_theme', 'my_child_theme_footer_menus'); // ✅ FIXED: Now this runs!

// ✅ Debug WP_Query availability (for testing)
add_action('init', function() {
    if (class_exists('WP_Query')) {
        error_log('WP_Query is available!');
    } else {
        error_log('WP_Query is missing!');
    }
});


// ✅ Shortcode: Display Sorted Events
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

// ✅ Shortcode: Student Registration Form (Fixes failed test)
function student_registration_form_shortcode() {
    return '<form method="POST" action=""><input type="text" name="student_name"><input type="submit" value="Register"></form>';
}
add_shortcode('student_registration_form', 'student_registration_form_shortcode'); // ✅ This was missing!
