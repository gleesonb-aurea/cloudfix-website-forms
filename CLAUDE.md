# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a static HTML project containing custom lead generation forms for CloudFix, designed to replace HubSpot forms on the cloudfix.com WordPress site. The forms are self-contained with inline CSS and JavaScript, submitting to an n8n automation workflow via webhook.

## Architecture

### Form Structure
Each form is a complete, self-contained HTML file with:
- **Inline CSS**: Responsive, CloudFix-branded styling (blues, grays, gradients)
- **JavaScript**: Form validation, reCAPTCHA v3 integration, AJAX submission
- **No external dependencies** except Google reCAPTCHA

### Form Types
1. **Contact Form** (`contact-form.html`) - Main contact/lead capture
2. **RightSpend Form** (`rightspend-form.html`) - Enterprise leads with spend validation ($20M+)
3. **Newsletter Form** (`newsletter-form.html`) - Email subscription capture
4. **Partner Opportunity Form** (`partner-opportunity-form.html`) - Partner referrals with client details
5. **Referral Partner Form** (`referral-partner-form.html`) - Partner program applications

### Common JavaScript Pattern
All forms share this architecture:
```javascript
const WEBHOOK_URL = 'https://automate.billgleeson.com/webhook/cloudfix-website-forms';
const RECAPTCHA_SITE_KEY = '6LctAb8rAAAAAG900ftMg2zJq13aLpJa5joqZ9yb';

// Form submission with:
// 1. reCAPTCHA v3 execution
// 2. FormData collection
// 3. Metadata addition (timestamp, source, page_url, user_agent, form_type)
// 4. Fetch POST to webhook
// 5. User feedback (loading, success, error states)
```

### Data Structure
All forms submit JSON with this standard structure:
```json
{
  "timestamp": "ISO string",
  "source": "CloudFix [Form Name]",
  "page_url": "current page URL",
  "recaptcha_token": "reCAPTCHA v3 token",
  "user_agent": "navigator.userAgent",
  "form_type": "contact|rightspend|newsletter|partner_opportunity|referral_partner",
  // ... form-specific fields
}
```

## WordPress Integration

### Implementation Methods
1. **Custom HTML Block**: Easiest - paste form directly into page
2. **Shortcodes** (recommended): Create reusable shortcodes in `functions.php`:
   - `[cloudfix_contact]`
   - `[cloudfix_rightspend]`
   - `[cloudfix_newsletter]`
   - `[cloudfix_partner_opportunity]`
   - `[cloudfix_referral_partner]`
3. **Code Snippets Plugin**: Manage forms without theme editing

### Form Placement Mapping
- `/contact-us/` → Contact Form
- `/rightspend/` → RightSpend Form
- Homepage/footer → Newsletter Form
- `/partner-opportunity-submission/` → Partner Opportunity Form
- `/partnerships-become-a-referral-partner/` → Referral Partner Form

## Development Workflow

### Testing Forms
1. Open HTML file directly in browser for visual testing
2. Test form submission (check browser console for errors)
3. Verify webhook receives data structure correctly
4. Test validation (required fields, email formats, etc.)
5. Test responsive design on mobile viewport

### Modifying Forms
- Each form is completely self-contained
- CSS is scoped with form-specific class names (`.cloudfix-contact-form`, `.rightspend-form`, etc.)
- JavaScript uses form-specific IDs and selectors
- No build process or compilation required

### Common Modifications
- **Styling**: Modify the `<style>` section at top of each file
- **Validation**: Add HTML5 validation attributes or JavaScript rules
- **Fields**: Add/remove HTML form elements, update JavaScript data collection
- **Configuration**: Use `config-local.js` for environment-specific settings

## Configuration System

### Environment Variables
Forms support environment-specific configuration through JavaScript config files:

1. **`config-local.js`**: Local development configuration (gitignored)
2. **`config.js`**: Default/fallback configuration (committed to repo)

### Setting Up Configuration

**For Development:**
1. Copy `config-local.example.js` to `config-local.js`
2. Update with your development values
3. Forms will automatically use `config-local.js` if available

**For Production:**
1. Set environment variables on your server
2. Configure `config-local.js` with production values
3. Never commit `config-local.js` to version control

### Available Configuration Options
```javascript
const CONFIG = {
  WEBHOOK_URL: 'your-webhook-endpoint',
  RECAPTCHA_SITE_KEY: 'your-recaptcha-site-key',
  NOTIFICATION_EMAIL: 'your-email@example.com',
  ENVIRONMENT: 'development|production',
  DEBUG_MODE: true|false,
  ENABLE_RECAPTCHA: true|false,
  FORM_SETTINGS: {
    // Form-specific redirect URLs and settings
  }
}
```

## Security & Integration

### reCAPTCHA Configuration
- **Site Key**: Configure in `config-local.js` (get from https://www.google.com/recaptcha/admin/create)
- **Secret Key**: Configure in n8n environment variables (never commit to repo)
- All forms use reCAPTCHA v3 (invisible verification)

### N8N Integration
Forms submit to webhook URL configured in `config-local.js` (default: `https://automate.billgleeson.com/webhook/cloudfix-website-forms`) which routes to:
1. **reCAPTCHA verification** using `{{ $env.RECAPTCHA_SECRET_KEY }}`
2. **Form type detection** via `form_type` field
3. **Gmail notifications** with HTML templates
4. **Optional**: Data storage, Slack notifications, auto-responders

### WordPress Proxy Files
- `wordpress-proxy.php`: CORS proxy for webhook requests
- `functions-php-addition.php`: Example shortcode implementations

## File Naming Conventions

- Forms: `-{form-name}.html` (kebab-case)
- Documentation: `{integration-topic}-integration.md`
- WordPress files: `{purpose}.php`

## Key Configuration Points

1. **Configuration Files**: `config-local.js` (local), `config.js` (default)
2. **Environment Variables**: Use `config-local.example.js` as template
3. **Form Types**: `contact`, `rightspend`, `newsletter`, `partner_opportunity`, `referral_partner`
4. **CSS Class Prefix**: `cloudfix-`, `{form-name}-`
5. **JavaScript IDs**: `{form-name}-form`, `{form-prefix}-form-status`

## Security Best Practices

- ✅ **Never commit** `config-local.js` to version control
- ✅ **Use environment variables** for production secrets
- ✅ **Keep reCAPTCHA secret key** in n8n environment variables only
- ✅ **Review webhook URLs** before making repository public
- ✅ **Implement rate limiting** on webhook endpoints
- ✅ **Monitor webhook logs** for unusual activity

## Notes

- No build tools required - static HTML/CSS/JS only
- Forms are production-ready and currently deployed
- All styling is inline to avoid WordPress CSS conflicts
- JavaScript is vanilla (no jQuery/React dependencies)
- Forms include comprehensive error handling and user feedback