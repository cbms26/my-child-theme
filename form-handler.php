<?php
add_action('init', 'handle_student_registration_form');

function handle_student_registration_form() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_registration_form'])) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'student_registrations';

        $fullName = sanitize_text_field($_POST['fullName']);
        $studentID = sanitize_text_field($_POST['studentID']);
        $studentMail = sanitize_email($_POST['studentMail']);
        $studentPhone = sanitize_text_field($_POST['studentPhone']);
        $studentDegree = sanitize_text_field($_POST['studentDegree']);
        $studentTrimester = sanitize_text_field($_POST['studentTrimester']);
        $consent = isset($_POST['consent']) ? 1 : 0;

        $inserted = $wpdb->insert(
            $table_name,
            [
                'full_name' => $fullName,
                'student_id' => $studentID,
                'student_mail' => $studentMail,
                'student_phone' => $studentPhone,
                'student_degree' => $studentDegree,
                'student_trimester' => $studentTrimester,
                'consent' => $consent,
                'created_at' => current_time('mysql')
            ]
        );

        if ($inserted === false) {
            set_transient('form_message', '<div class="alert alert-danger">Failed to submit the form. Please try again.</div>', 60);
        } else {
            set_transient('form_message', '<div class="alert alert-success">Form submitted successfully!</div>', 60);
        }

        wp_safe_redirect(home_url($_SERVER['REQUEST_URI']));
        exit;
    }
}
