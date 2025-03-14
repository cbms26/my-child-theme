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

    // ✅ Test if styles and scripts are enqueued
    public function test_enqueue_styles() {
        do_action('wp_enqueue_scripts');
        $this->assertTrue(wp_style_is('parent-style', 'enqueued'), 'Parent style should be enqueued');
        $this->assertTrue(wp_style_is('bootstrap-css', 'enqueued'), 'Bootstrap CSS should be enqueued');
        $this->assertTrue(wp_script_is('bootstrap-js', 'enqueued'), 'Bootstrap JS should be enqueued');
    }

    // ✅ Test if menus are registered properly
    public function test_menus_registered() {
        do_action('after_setup_theme');
        $menus = get_registered_nav_menus();

        $this->assertArrayHasKey('primary', $menus, 'Primary menu should be registered');
        $this->assertArrayHasKey('footer_quick_links', $menus, 'Footer Quick Links menu should be registered');
        $this->assertArrayHasKey('footer_student_board', $menus, 'Footer Student Board menu should be registered');
    }

    // ✅ Test if WP_Query class exists (ensures WordPress is properly loaded)
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
    
}
