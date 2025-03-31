<?php
use PHPUnit\Framework\TestCase;

if (!defined('WP_USE_THEMES')) {
    define('WP_USE_THEMES', false);
}

// Load WordPress core
require_once dirname(__DIR__, 4) . '/wp-load.php';
require_once dirname(__DIR__, 4) . '/wp-config.php';

// 🔥 Ensure theme functions are loaded
require_once dirname(__DIR__, 1) . '/functions.php';

// 🔥 Manually register the shortcode (if PHPUnit isn’t loading it)
add_shortcode('sorted_events', 'display_sorted_events');


class ThemeFunctionsTest extends TestCase {

    //   Test if styles and scripts are enqueued
    public function test_enqueue_styles() {
        do_action('wp_enqueue_scripts');
        $this->assertTrue(wp_style_is('parent-style', 'enqueued'), 'Parent style should be enqueued');
        $this->assertTrue(wp_style_is('bootstrap-css', 'enqueued'), 'Bootstrap CSS should be enqueued');
        $this->assertTrue(wp_script_is('bootstrap-js', 'enqueued'), 'Bootstrap JS should be enqueued');
    }

    //   Test if menus are registered properly
    public function test_menus_registered() {
        do_action('after_setup_theme');
        $menus = get_registered_nav_menus();

        $this->assertArrayHasKey('primary', $menus, 'Primary menu should be registered');
        $this->assertArrayHasKey('footer_quick_links', $menus, 'Footer Quick Links menu should be registered');
        $this->assertArrayHasKey('footer_student_board', $menus, 'Footer Student Board menu should be registered');
    }

    //   Test if WP_Query class exists (ensures WordPress is properly loaded)
    public function test_wp_query_exists() {
        $this->assertTrue(class_exists('WP_Query'), 'WP_Query class should exist');
    }

    public function test_sorted_events_shortcode() {
        // Ensure WordPress is loading the shortcode function
        $this->assertTrue(function_exists('do_shortcode'), 'do_shortcode function should exist');
        $this->assertTrue(function_exists('display_sorted_events'), 'display_sorted_events function should be registered');
    
        // Manually register the shortcode (as WordPress may not have initialized it)
        add_shortcode('sorted_events', 'display_sorted_events');
    
        // Run the shortcode
        $output = do_shortcode('[sorted_events]');
    
        // Log output to debug
        error_log('Shortcode Output: ' . $output);
    
        // Check if output contains expected HTML
        $this->assertNotEmpty($output, 'Sorted events shortcode should not return empty output');
        $this->assertStringContainsString('<div class="events-section">', $output, 'Sorted events shortcode should output events section');
    }

    public function test_inject_logo_and_search_script() {
        // Trigger the function
        do_action('wp_enqueue_scripts');
    
        // Check if the script is registered
        $this->assertTrue(wp_script_is('menu-custom-js', 'registered'), 'menu-custom-js should be registered');
    
        // Check if the script is enqueued
        $this->assertTrue(wp_script_is('menu-custom-js', 'enqueued'), 'menu-custom-js should be enqueued');
    
        // Check if the script has the correct dependency (jQuery)
        global $wp_scripts;
        $script = $wp_scripts->registered['menu-custom-js'] ?? null;
    
        $this->assertNotNull($script, 'menu-custom-js should be registered');
        $this->assertContains('jquery', $script->deps, 'menu-custom-js should depend on jQuery');
        
        // Ensure the path is correct
        $expected_path = get_stylesheet_directory_uri() . '/assets/js/menu-custom.js';
        $this->assertStringContainsString($expected_path, $script->src, 'menu-custom-js should have the correct file path');
    }
    
    
    // // 🟢 Test if form submission inserts student registration data
    // public function test_student_registration_inserts_data() {
    //     global $wpdb;
    //     $table_name = $wpdb->prefix . 'student_registrations';

    //     // Simulate $_POST data
    //     $_SERVER['REQUEST_METHOD'] = 'POST';
    //     $_POST = [
    //         'student_registration_form' => '1',
    //         'fullName' => 'Test Student',
    //         'studentID' => '123456',
    //         'studentMail' => 'test@student.com',
    //         'studentPhone' => '123456789',
    //         'studentDegree' => 'MIT',
    //         'studentTrimester' => 'T1',
    //         'consent' => '1'
    //     ];

    //     // Call the form submission function
    //     handle_student_registration_form_submission();

    //     // Fetch inserted data
    //     $result = $wpdb->get_row("SELECT * FROM $table_name WHERE student_id = '123456'");

    //     // Assertions
    //     $this->assertNotNull($result, 'Student registration data should be inserted into the database');
    //     $this->assertEquals('Test Student', $result->full_name, 'Full name should match');
    //     $this->assertEquals('123456', $result->student_id, 'Student ID should match');
    //     $this->assertEquals('test@student.com', $result->student_mail, 'Student email should match');
    //     $this->assertEquals('123456789', $result->student_phone, 'Student phone should match');
    //     $this->assertEquals('MIT', $result->student_degree, 'Student degree should match');
    //     $this->assertEquals('T1', $result->student_trimester, 'Student trimester should match');
    //     $this->assertEquals(1, $result->consent, 'Consent should be recorded as 1 (true)');

    //     // Clean up after test
    //     $wpdb->query("DELETE FROM $table_name WHERE student_id = '123456'");
    // }
}
