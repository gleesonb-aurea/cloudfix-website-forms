<?php
// Add this to your WordPress theme's functions.php file

// CloudFix RightSpend Form Shortcode
function cloudfix_rightspend_form_shortcode() {
    ob_start();
    
    // Handle form submission
    if (isset($_POST['action']) && $_POST['action'] === 'cloudfix_rightspend_form') {
        // Verify nonce
        if (!wp_verify_nonce($_POST['rs_nonce'], 'cloudfix_rightspend_form')) {
            $error_message = 'Security check failed. Please try again.';
        } else {
            // Get form data
            $firstName = sanitize_text_field($_POST['rs_firstName']);
            $lastName = sanitize_text_field($_POST['rs_lastName']);
            $email = sanitize_email($_POST['rs_email']);
            $company = sanitize_text_field($_POST['rs_company']);
            
            // Prepare email
            $to = 'bill@billgleeson.com';
            $subject = 'New RightSpend Lead from ' . $firstName . ' ' . $lastName . ' - ' . $company;
            
            $message = "
            <html>
            <body>
                <h2>🚀 New RightSpend Enterprise Lead</h2>
                <p><strong>Submitted:</strong> " . current_time('Y-m-d H:i:s') . "</p>
                
                <h3>Contact Information</h3>
                <ul>
                    <li><strong>Name:</strong> {$firstName} {$lastName}</li>
                    <li><strong>Email:</strong> {$email}</li>
                    <li><strong>Company:</strong> {$company}</li>
                </ul>
                
                <hr>
                <p><small>Source: CloudFix RightSpend Form (WordPress) | Page: " . get_permalink() . "</small></p>
            </body>
            </html>";
            
            $headers = array(
                'Content-Type: text/html; charset=UTF-8',
                'From: ' . get_option('blogname') . ' <noreply@' . $_SERVER['HTTP_HOST'] . '>'
            );
            
            // Send email
            $sent = wp_mail($to, $subject, $message, $headers);
            
            if ($sent) {
                // Optional: Try to send to n8n webhook too
                $webhook_data = array(
                    'firstName' => $firstName,
                    'lastName' => $lastName,
                    'email' => $email,
                    'company' => $company,
                    'timestamp' => current_time('c'),
                    'source' => 'CloudFix RightSpend Form (WordPress)',
                    'page_url' => get_permalink(),
                    'form_type' => 'rightspend'
                );
                
                wp_remote_post('https://automate.billgleeson.com/webhook/cloudfix-website-forms', array(
                    'method' => 'POST',
                    'headers' => array('Content-Type' => 'application/json'),
                    'body' => json_encode($webhook_data),
                    'timeout' => 10,
                    'blocking' => false
                ));
                
                $success_message = 'Thank you! Your RightSpend estimate request has been submitted. Our team will contact you within 24 hours.';
            } else {
                $error_message = 'Sorry, there was an error processing your request. Please try again.';
            }
        }
    }
    ?>
    
    <div class="rightspend-form-wrapper">
        <style>
            .rightspend-form-wrapper {
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            }
            
            .rightspend-form-container {
                background: rgb(238, 238, 238);
                padding: 60px 20px;
                border-radius: 15px;
                max-width: 940px;
                margin: 40px auto;
            }
            
            .rightspend-form {
                background: #ffffff;
                padding: 40px 30px;
                border-radius: 15px;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
                max-width: 600px;
                margin: 0 auto;
            }
            
            .rightspend-form h2 {
                color: #1a365d;
                font-size: 36px;
                font-weight: 700;
                margin-bottom: 15px;
                text-align: center;
                line-height: 1.2;
            }
            
            .rightspend-form .form-subtitle {
                color: #4a5568;
                font-size: 18px;
                line-height: 1.6;
                text-align: center;
                margin-bottom: 35px;
            }
            
            .rightspend-form-row {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 20px;
                margin-bottom: 20px;
            }
            
            @media (max-width: 768px) {
                .rightspend-form-row {
                    grid-template-columns: 1fr;
                }
                .rightspend-form-container {
                    padding: 40px 15px;
                }
                .rightspend-form {
                    padding: 30px 20px;
                }
            }
            
            .rightspend-form-group {
                margin-bottom: 25px;
            }
            
            .rightspend-form-group label {
                display: block;
                color: #2d3748;
                font-weight: 600;
                font-size: 15px;
                margin-bottom: 8px;
            }
            
            .rightspend-form-group.required label::after {
                content: ' *';
                color: #e53e3e;
            }
            
            .rightspend-form-group input {
                width: 100%;
                padding: 16px 18px;
                border: 2px solid #e2e8f0;
                border-radius: 10px;
                font-size: 16px;
                background: #ffffff;
                transition: all 0.3s ease;
                box-sizing: border-box;
            }
            
            .rightspend-form-group input:focus {
                outline: none;
                border-color: #3182ce;
                box-shadow: 0 0 0 4px rgba(49, 130, 206, 0.15);
                transform: translateY(-1px);
            }
            
            .rightspend-submit-btn {
                background: linear-gradient(135deg, #f6d55c 0%, #ed8936 100%);
                color: #2d3748;
                border: none;
                padding: 18px 40px;
                border-radius: 10px;
                font-size: 18px;
                font-weight: 700;
                cursor: pointer;
                width: 100%;
                transition: all 0.3s ease;
                box-shadow: 0 6px 20px rgba(246, 213, 92, 0.4);
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
            
            .rightspend-submit-btn:hover {
                background: linear-gradient(135deg, #ecc94b 0%, #dd6b20 100%);
                transform: translateY(-3px);
                box-shadow: 0 8px 25px rgba(246, 213, 92, 0.5);
            }
            
            .rightspend-form-status {
                margin-top: 20px;
                padding: 16px;
                border-radius: 10px;
                font-size: 15px;
                text-align: center;
            }
            
            .rightspend-form-status.success {
                background: #f0fff4;
                color: #2f855a;
                border: 2px solid #9ae6b4;
            }
            
            .rightspend-form-status.error {
                background: #fed7d7;
                color: #c53030;
                border: 2px solid #feb2b2;
            }
        </style>
        
        <div class="rightspend-form-container">
            <div class="rightspend-form">
                <h2>Get Your RightSpend Estimate</h2>
                <p class="form-subtitle">Get a personalized assessment and 30-day free trial for your AWS environment.</p>
                
                <?php if (isset($success_message)): ?>
                    <div class="rightspend-form-status success"><?php echo $success_message; ?></div>
                <?php elseif (isset($error_message)): ?>
                    <div class="rightspend-form-status error"><?php echo $error_message; ?></div>
                <?php else: ?>
                    
                    <form method="POST" action="">
                        <?php wp_nonce_field('cloudfix_rightspend_form', 'rs_nonce'); ?>
                        
                        <div class="rightspend-form-row">
                            <div class="rightspend-form-group required">
                                <label for="rs_firstName">First Name</label>
                                <input type="text" id="rs_firstName" name="rs_firstName" required>
                            </div>
                            
                            <div class="rightspend-form-group required">
                                <label for="rs_lastName">Last Name</label>
                                <input type="text" id="rs_lastName" name="rs_lastName" required>
                            </div>
                        </div>
                        
                        <div class="rightspend-form-group required">
                            <label for="rs_email">Work Email</label>
                            <input type="email" id="rs_email" name="rs_email" required>
                        </div>
                        
                        <div class="rightspend-form-group required">
                            <label for="rs_company">Company Name</label>
                            <input type="text" id="rs_company" name="rs_company" required>
                        </div>
                        
                        <input type="hidden" name="action" value="cloudfix_rightspend_form">
                        
                        <button type="submit" class="rightspend-submit-btn">Get RightSpend Estimate</button>
                    </form>
                    
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php
    return ob_get_clean();
}
add_shortcode('cloudfix_rightspend', 'cloudfix_rightspend_form_shortcode');