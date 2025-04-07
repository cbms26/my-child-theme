<?php
use PHPUnit\Framework\TestCase;

if (!defined('WP_USE_THEMES')) {
    define('WP_USE_THEMES', false);
}

// Load WordPress core
require_once dirname(__DIR__, 4) . '/wp-load.php';
require_once dirname(__DIR__, 4) . '/wp-config.php';
require_once dirname(__DIR__, 1) . '/functions.php';


add_shortcode('sorted_events', 'display_sorted_events');
add_shortcode('student_registration_form', 'display_student_registration_form');
add_shortcode('dynamic_event_details', 'dynamic_event_details');
add_shortcode('upcoming_events', 'upcoming_events_shortcode');

class ThemeFunctionsTest extends TestCase {

    public function test_enqueue_styles() {
        do_action('wp_enqueue_scripts');
        $this->assertTrue(wp_style_is('parent-style', 'enqueued'), 'Parent style should be enqueued');
        $this->assertTrue(wp_style_is('bootstrap-css', 'enqueued'), 'Bootstrap CSS should be enqueued');
        $this->assertTrue(wp_script_is('bootstrap-js', 'enqueued'), 'Bootstrap JS should be enqueued');
    }

    public function test_menus_registered() {
        do_action('after_setup_theme');
        $menus = get_registered_nav_menus();

        $this->assertArrayHasKey('primary', $menus, 'Primary menu should be registered');
        $this->assertArrayHasKey('footer_quick_links', $menus, 'Footer Quick Links menu should be registered');
        $this->assertArrayHasKey('footer_student_board', $menus, 'Footer Student Board menu should be registered');
    }

    public function test_wp_query_exists() {
        $this->assertTrue(class_exists('WP_Query'), 'WP_Query class should exist');
    }

    public function test_sorted_events_shortcode() {
        $this->assertTrue(function_exists('do_shortcode'), 'do_shortcode function should exist');
        $this->assertTrue(function_exists('display_sorted_events'), 'display_sorted_events function should be registered');

        $output = display_sorted_events();

        $this->assertNotEmpty($output, 'Sorted events shortcode should not return empty output');
        $this->assertStringContainsString('<div class="events-split-layout">', $output, 'Sorted events shortcode should include main layout container');
        $this->assertStringContainsString('<div class="events-section left-events">', $output, 'Sorted events shortcode should include upcoming events section');
        $this->assertStringContainsString('<div class="events-section fullwidth-past-events">', $output, 'Sorted events shortcode should include past events section');
    }

    public function test_inject_logo_and_search_script() {
        do_action('wp_enqueue_scripts');
        $this->assertTrue(wp_script_is('menu-custom-js', 'registered'), 'menu-custom-js should be registered');
        $this->assertTrue(wp_script_is('menu-custom-js', 'enqueued'), 'menu-custom-js should be enqueued');
        global $wp_scripts;
        $script = $wp_scripts->registered['menu-custom-js'] ?? null;
        $this->assertNotNull($script, 'menu-custom-js should be registered');
        $this->assertContains('jquery', $script->deps, 'menu-custom-js should depend on jQuery');
        $expected_path = get_stylesheet_directory_uri() . '/assets/js/menu-custom.js';
        $this->assertStringContainsString($expected_path, $script->src, 'menu-custom-js should have the correct file path');
    }

    public function test_student_registration_shortcode_output() {
        $this->assertTrue(function_exists('display_student_registration_form'), 'display_student_registration_form function should exist');
        $output = display_student_registration_form();
        $this->assertNotEmpty($output, 'Student registration form shortcode should return output');
        $this->assertStringContainsString('<form', $output, 'Form HTML should be present in output');
    }

    public function test_dynamic_event_details_shortcode() {
        $this->assertTrue(function_exists('dynamic_event_details'), 'dynamic_event_details function should exist');

        // Simulate not being on a single tribe_event post to trigger the fallback message
        $GLOBALS['post'] = (object) ['post_type' => 'post'];

        $output = dynamic_event_details();
        $this->assertStringContainsString('only works on event pages', $output, 'Fallback message should be shown when not on event page');
    }

    public function test_upcoming_events_shortcode() {
        $this->assertTrue(function_exists('upcoming_events_shortcode'), 'upcoming_events_shortcode function should exist');

        $output = upcoming_events_shortcode();

        $this->assertNotEmpty($output, 'Upcoming events shortcode should return output');
        $this->assertStringContainsString('upcoming_events_section', $output, 'Output should contain the container class');
    }
}