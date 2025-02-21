<?php
// Enqueue parent theme styles
function my_child_theme_enqueue_styles()
{
    //Load parent theme styles
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    //Load child theme styles
    wp_enqueue_style('child-style', get_stylesheet_uri(), array('parent-style'));
}
add_action('wp_enqueue_scripts', 'my_child_theme_enqueue_styles');