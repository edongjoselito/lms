<?php
/**
 * Notification Helper
 * Helper functions for displaying toast notifications
 * Inspired by Sileo - Physics-based toast notifications
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Set a flash notification that will be displayed as a toast
 *
 * @param string $message The message to display
 * @param string $type Type of notification: success, error, warning, info
 * @param string $title Optional title for the notification
 */
function set_notification($message, $type = 'info', $title = null) {
    $CI =& get_instance();

    $notifications = $CI->session->flashdata('notifications') ?: [];
    $notifications[] = [
        'message' => $message,
        'type' => $type,
        'title' => $title ?: get_default_title($type)
    ];

    $CI->session->set_flashdata('notifications', $notifications);
}

/**
 * Set a success notification
 */
function notify_success($message, $title = null) {
    set_notification($message, 'success', $title);
}

/**
 * Set an error notification
 */
function notify_error($message, $title = null) {
    set_notification($message, 'error', $title);
}

/**
 * Set a warning notification
 */
function notify_warning($message, $title = null) {
    set_notification($message, 'warning', $title);
}

/**
 * Set an info notification
 */
function notify_info($message, $title = null) {
    set_notification($message, 'info', $title);
}

/**
 * Get default title based on notification type
 */
function get_default_title($type) {
    $titles = [
        'success' => 'Success',
        'error' => 'Error',
        'warning' => 'Warning',
        'info' => 'Information'
    ];
    return $titles[$type] ?? 'Notification';
}

/**
 * Render notifications as JSON for JavaScript consumption
 * Call this in your view to output notifications for the JS toast system
 */
function render_notifications() {
    $CI =& get_instance();
    $notifications = $CI->session->flashdata('notifications') ?: [];

    foreach (array('success', 'error', 'warning', 'info') as $type) {
        $message = $CI->session->flashdata($type);

        if ($message) {
            $notifications[] = array(
                'message' => $message,
                'type' => $type,
                'title' => get_default_title($type)
            );
        }
    }

    $errors = $CI->session->flashdata('errors');
    if (!empty($errors)) {
        if (is_array($errors)) {
            foreach ($errors as $error) {
                $notifications[] = array(
                    'message' => $error,
                    'type' => 'error',
                    'title' => 'Error'
                );
            }
        } else {
            $notifications[] = array(
                'message' => $errors,
                'type' => 'error',
                'title' => 'Error'
            );
        }
    }

    if (empty($notifications)) {
        return '';
    }

    $json = json_encode($notifications, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
    return '<div id="flash-data" data-messages=\'' . htmlspecialchars($json, ENT_QUOTES, 'UTF-8') . '\'></div>';
}
