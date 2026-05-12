<?php
// WordPress form proxy - Save this as wp-content/themes/your-theme/form-proxy.php
// Then create a custom page template that includes this file

if ($_POST) {
    // Verify WordPress nonce for security
    if (!wp_verify_nonce($_POST['wp_nonce'], 'cloudfix_form')) {
        wp_die('Security check failed');
    }
    
    $webhook_url = 'https://automate.billgleeson.com/webhook/cloudfix-website-forms';
    
    // Forward the POST data to n8n webhook
    $response = wp_remote_post($webhook_url, array(
        'method' => 'POST',
        'headers' => array(
            'Content-Type' => 'application/json',
        ),
        'body' => json_encode($_POST),
        'timeout' => 30,
    ));
    
    if (is_wp_error($response)) {
        wp_die('Form submission failed: ' . $response->get_error_message());
    } else {
        echo json_encode(array('status' => 'success'));
    }
    exit;
}
?>