# External Integrations

**Analysis Date:** 2026-05-12

## APIs & External Services

**Form Submission:**
- n8n Webhook - Form data collection and processing
  - Endpoint: `https://automate.billgleeson.com/webhook/cloudfix-website-forms`
  - Method: POST
  - Content-Type: application/json
  - Auth: None (public webhook)
  - Configurable via: `WEBHOOK_URL` in `config-local.js`

**Bot Protection:**
- Google reCAPTCHA v3 - Invisible form validation
  - SDK URL: `https://www.google.com/recaptcha/api.js`
  - Site Key Config: `RECAPTCHA_SITE_KEY` in `config-local.js`
  - Default Site Key: `6LctAb8rAAAAAG900ftMg2zJq13aLpJa5joqZ9yb`
  - Secret Key Location: n8n environment variables (never in repo)
  - Actions used: `contact_form`, `rightspend_form`, `newsletter_signup`, `partner_opportunity`, `referral_partner`

## Data Storage

**Databases:**
- None directly (forms submit to webhook for processing)

**Form Data Storage:**
- Optional: Google Sheets or Airtable via n8n workflow
- Configured in n8n, not in forms

**File Storage:**
- None (form data only)

**Caching:**
- None (no client-side caching)

## Authentication & Identity

**Auth Provider:**
- None (forms are public)

**Form Security:**
- reCAPTCHA v3 token validation on server side
- WordPress nonce verification for PHP versions
- HTML5 validation for client-side checks

## Monitoring & Observability

**Error Tracking:**
- Console logging for JavaScript errors
- Server-side error handling in n8n workflow

**Logs:**
- Browser console for client-side errors
- n8n workflow logs for webhook processing

**Analytics:**
- Optional: Can be added via Google Analytics or similar
- Form submissions include `page_url` and `user_agent` for tracking

## CI/CD & Deployment

**Hosting:**
- Static file hosting (any web server)
- WordPress integration via custom HTML blocks or shortcodes

**CI Pipeline:**
- None (manual deployment)

**Deployment Methods:**
1. Direct file upload to web server
2. WordPress Custom HTML blocks
3. WordPress shortcodes (via `functions.php` or Code Snippets plugin)

## Environment Configuration

**Required env vars:**
- `WEBHOOK_URL` - n8n webhook endpoint
- `RECAPTCHA_SITE_KEY` - Google reCAPTCHA v3 site key
- `RECAPTCHA_SECRET_KEY` - Google reCAPTCHA v3 secret (n8n side only)

**Optional env vars:**
- `NOTIFICATION_EMAIL` - Email notification recipient
- `NODE_ENV` - Environment (development/production)
- `DEBUG_MODE` - Enable debug logging
- `ENABLE_RECAPTCHA` - Toggle reCAPTCHA on/off
- `CONTACT_REDIRECT_URL` - Post-submit redirect for contact form
- `RIGHTSPEND_REDIRECT_URL` - Post-submit redirect for RightSpend form
- `MIN_SPEND_AMOUNT` - Minimum spend validation for RightSpend ($20M default)
- `NEWSLETTER_REDIRECT_URL` - Post-submit redirect for newsletter
- `PARTNER_OPPORTUNITY_REDIRECT_URL` - Post-submit redirect for partner opportunity
- `REFERRAL_PARTNER_REDIRECT_URL` - Post-submit redirect for referral partner

**Secrets location:**
- `config-local.js` (gitignored) - Contains site key and webhook URL
- n8n environment variables - Contains reCAPTCHA secret key
- Never commit secrets to repository

## Webhooks & Callbacks

**Incoming:**
- None (forms are standalone, no callbacks)

**Outgoing:**
- All forms submit to: `https://automate.billgleeson.com/webhook/cloudfix-website-forms`

**Standard submission data structure:**
```json
{
  "timestamp": "ISO-8601 timestamp",
  "source": "CloudFix [Form Name]",
  "page_url": "Current page URL",
  "recaptcha_token": "reCAPTCHA v3 token",
  "user_agent": "Browser user agent string",
  "form_type": "contact|rightspend|newsletter|partner_opportunity|referral_partner",
  // ... form-specific fields
}
```

**Form types:**
- `contact` - Contact form (`contact-form.html`)
- `rightspend` - RightSpend enterprise form (`rightspend-form.html`)
- `newsletter` - Newsletter signup (`newsletter-form.html`)
- `partner_opportunity` - Partner referral (`partner-opportunity-form.html`)
- `referral_partner` - Partner application (`referral-partner-form.html`)

## WordPress Integration

**Shortcode Support:**
- `[cloudfix_contact]` - Contact form
- `[cloudfix_rightspend]` - RightSpend form
- `[cloudfix_newsletter]` - Newsletter form
- `[cloudfix_partner_opportunity]` - Partner opportunity form
- `[cloudfix_referral_partner]` - Referral partner form
- `[cloudfix_lead_magnet_capture]` - Lead magnet form

**Proxy Files:**
- `wordpress-proxy.php` - CORS proxy for webhook requests (optional)
- `functions-php-addition.php` - Example shortcode implementations

**CORS Handling:**
- Forms use direct AJAX POST to webhook (no CORS issues with same-origin)
- WordPress proxy available for cross-origin scenarios

**Implementation Methods:**
1. Custom HTML blocks (paste form directly)
2. Shortcodes via `functions.php` (recommended)
3. Shortcodes via Code Snippets plugin (non-developer friendly)
4. Child theme template files (developer approach)

---

*Integration audit: 2026-05-12*
