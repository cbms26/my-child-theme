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

//Shortcode to Display sorted events in home-page
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
            $event_image = get_the_post_thumbnail_url(get_the_ID(), 'large'); // Get featured image

            echo '<div class="event-item" style="background-image: url(' . esc_url($event_image) . ');">';
            echo '<div class="event-content">'; // White box inside
            echo '<div class="event-title">' . get_the_title() . '</div>';
            echo '<div class="event-date">Date: ' . esc_html($event_date) . '</div>';
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
            $event_image = get_the_post_thumbnail_url(get_the_ID(), 'large'); // Get featured image

            echo '<div class="event-item" style="background-image: url(' . esc_url($event_image) . ');">';
            echo '<div class="event-content">'; // White box inside
            echo '<div class="event-title">' . get_the_title() . '</div>';
            echo '<div class="event-date">Date: ' . esc_html($event_date) . '</div>';
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


