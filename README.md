# CloudFix Lead Forms

Custom HTML forms to replace HubSpot forms on cloudfix.com WordPress site.

## Forms Included

1. **Contact Form** (`contact-form.html`) - Main contact form
2. **RightSpend Form** (`rightspend-form.html`) - Enterprise form with spend validation
3. **Newsletter Signup** (`newsletter-form.html`) - Email subscription form
4. **Partner Opportunity Form** (`partner-opportunity-form.html`) - Partner referral submissions
5. **Referral Partner Form** (`referral-partner-form.html`) - Partner program applications
6. **Lead Magnet Capture Form** (`lead-magnet-capture-form.html`) - Checklist download capture form

## Setup Instructions

### 1. Webhook URL Configuration

All forms are pre-configured to submit to:
```javascript
const WEBHOOK_URL = 'https://automate.billgleeson.com/webhook/cloudfix-website-forms';
```

No changes needed unless you want to use a different webhook endpoint.

### 2. WordPress Installation

#### Option A: Custom HTML Block
1. Edit the page in WordPress
2. Add a "Custom HTML" block
3. Copy and paste the entire contents of the desired form HTML file

#### Option B: Code Snippets Plugin (Recommended)
1. Install the "Code Snippets" plugin
2. Create a new snippet with the form code
3. Use shortcodes to embed: `[cloudfix_contact]`, `[cloudfix_rightspend]`, `[cloudfix_newsletter]`, `[cloudfix_partner_opportunity]`, `[cloudfix_referral_partner]`

#### Option C: Theme Functions
Add to your theme's `functions.php`:

```php
function cloudfix_contact_form_shortcode() {
    ob_start();
    ?>
    <!-- Paste entire contact-form.html content here -->
    <?php
    return ob_get_clean();
}
add_shortcode('cloudfix_contact', 'cloudfix_contact_form_shortcode');
```

**📋 See `wordpress-integration.md` for detailed WordPress setup instructions.**

### 3. reCAPTCHA Configuration

Forms use reCAPTCHA v3. You need to configure your own keys:

**For Development:**
1. Get reCAPTCHA v3 keys from: https://www.google.com/recaptcha/admin/create
2. Replace the site key in each HTML file
3. Configure the secret key in your n8n webhook

**Current Site Key** (for reference): `6LctAb8rAAAAAG900ftMg2zJq13aLpJa5joqZ9yb`
**⚠️ Secret Key**: Configure in your n8n environment - never commit to version control

## Form Data Structure

### Contact Form
```json
{
  "firstName": "John",
  "lastName": "Doe", 
  "email": "john@company.com",
  "company": "Company Name",
  "jobTitle": "CTO",
  "phone": "+1234567890",
  "message": "How can we help you?",
  "newsletter": true/false,
  "timestamp": "2024-01-01T12:00:00.000Z",
  "source": "CloudFix Contact Form",
  "page_url": "https://cloudfix.com/contact-us/",
  "recaptcha_token": "...",
  "user_agent": "..."
}
```

### RightSpend Form
```json
{
  "firstName": "Jane",
  "lastName": "Smith",
  "email": "jane@enterprise.com", 
  "company": "Enterprise Corp",
  "jobTitle": "VP Engineering",
  "phone": "+1234567890",
  "annualSpend": "$50M - $100M",
  "newsletter": true/false,
  "timestamp": "2024-01-01T12:00:00.000Z",
  "source": "CloudFix RightSpend Form",
  "form_type": "rightspend",
  "recaptcha_token": "...",
  "user_agent": "..."
}
```

### Newsletter Form
```json
{
  "email": "subscriber@company.com",
  "timestamp": "2024-01-01T12:00:00.000Z",
  "source": "CloudFix Newsletter Signup",
  "form_type": "newsletter",
  "subscription_type": "general_updates",
  "recaptcha_token": "...",
  "user_agent": "..."
}
```

### Lead Magnet Capture Form
```json
{
  "email": "subscriber@company.com",
  "company": "Company Name",
  "monthly_aws_spend": "50k_200k",
  "timestamp": "2024-01-01T12:00:00.000Z",
  "source": "CloudFix Lead Magnet Capture Form",
  "form_type": "lead_magnet_capture",
  "recaptcha_token": "...",
  "user_agent": "..."
}
```

## Styling Notes

- Forms match CloudFix branding (blues, grays, gradients)
- Fully responsive design
- Accessible form labels and focus states
- Custom error and success states
- reCAPTCHA v3 integration (invisible)

## Features

- ✅ reCAPTCHA v3 spam protection
- ✅ Client-side form validation
- ✅ Responsive design
- ✅ Loading states and feedback
- ✅ Error handling
- ✅ Matches CloudFix visual design
- ✅ Clean data structure for n8n processing

## Testing

Test forms by:
1. Filling out with valid data
2. Checking browser console for errors
3. Verifying webhook receives proper JSON
4. Testing validation (required fields, email format, etc.)

## Support

Forms are self-contained with inline CSS and JavaScript. No external dependencies except Google reCAPTCHA.
