# Technology Stack

**Analysis Date:** 2026-05-12

## Languages

**Primary:**
- HTML5 - Form structure and markup (all form files)

**Secondary:**
- JavaScript ES6+ - Client-side validation, AJAX submission, reCAPTCHA integration
- CSS3 - Inline styling within HTML files

**Supporting:**
- PHP - WordPress integration files (`wordpress-proxy.php`, `functions-php-addition.php`)

## Runtime

**Environment:**
- Browser-based (client-side JavaScript)
- WordPress/PHP server environment (optional integration)

**Package Manager:**
- None (static HTML/CSS/JS - no build process)

## Frameworks

**Core:**
- None (vanilla HTML/CSS/JavaScript only)

**Testing:**
- None (manual browser testing)

**Build/Dev:**
- None (no build tools or bundlers required)

## Key Dependencies

**Critical:**
- Google reCAPTCHA v3 - Bot protection and form validation
  - Loaded dynamically: `https://www.google.com/recaptcha/api.js`
  - Site key: `6LctAb8rAAAAAG900ftMg2zJq13aLpJa5joqZ9yb` (default)

**Infrastructure:**
- n8n automation platform - Form submission webhook endpoint
  - Default webhook: `https://automate.billgleeson.com/webhook/cloudfix-website-forms`

## Configuration

**Environment:**
- JavaScript config files with fallback pattern:
  - `config-local.js` - Local/environment-specific config (gitignored)
  - `config.js` - Default fallback configuration (committed)
  - `config-local.example.js` - Template for new setups

**Config loading pattern:**
```html
<script src="config-local.js" onerror="this.src='config.js'"></script>
```

**Available config options:**
- `WEBHOOK_URL` - Form submission endpoint
- `RECAPTCHA_SITE_KEY` - Google reCAPTCHA v3 site key
- `NOTIFICATION_EMAIL` - Email for WordPress PHP versions
- `ENVIRONMENT` - development/production flag
- `DEBUG_MODE` - Enable debug logging
- `ENABLE_RECAPTCHA` - Toggle reCAPTCHA on/off
- `FORM_SETTINGS` - Per-form redirect URLs and settings

**Build:**
- No build config (static files)

## Platform Requirements

**Development:**
- Modern web browser for testing
- Text editor for HTML/CSS/JS editing
- Optional: Local WordPress installation for PHP integration testing

**Production:**
- Web server for static file hosting (Apache, Nginx, etc.)
- WordPress site (for PHP integration)
- Access to n8n instance or webhook endpoint
- Valid Google reCAPTCHA v3 site key

**Browser Support:**
- Modern browsers with ES6+ support
- Fetch API support
- CSS Grid and Flexbox support

---

*Stack analysis: 2026-05-12*
