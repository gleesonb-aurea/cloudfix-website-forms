# Coding Conventions

**Analysis Date:** 2026-05-12

## File Naming

**HTML Forms:**
- Pattern: `{form-name}-form.html` (kebab-case)
- Examples: `contact-form.html`, `rightspend-form.html`, `newsletter-form.html`
- Variants: `{form-name}-form-{variant}.html` for special versions (e.g., `contact-form-debug.html`, `contact-form-no-recaptcha.html`)

**JavaScript Config Files:**
- Pattern: `config{variant}.js` (kebab-case)
- `config.js` - Default/fallback configuration
- `config-local.js` - Local development overrides (gitignored)
- `config-local.example.js` - Template for local config

**PHP Files:**
- Pattern: `{purpose}.php` or `{purpose}-{variant}.php` (kebab-case)
- Examples: `functions-php-addition.php`, `wordpress-proxy.php`

**Documentation:**
- Pattern: `{topic}-{type}.md` or `{topic}.md` (kebab-case)
- Examples: `wordpress-integration.md`, `n8n-gmail-integration.md`, `README.md`

## Code Style

**Indentation:**
- 4 spaces for HTML/CSS/JavaScript
- Consistent across all files

**Quotes:**
- Single quotes for JavaScript strings and object properties
- Double quotes for HTML attributes
- Template literals for dynamic strings

**Line Endings:**
- LF (Unix-style)

**HTML Formatting:**
- Self-contained: Each form is a complete HTML fragment (not a full document)
- Inline CSS in `<style>` blocks within wrapper div
- Inline JavaScript in `<script>` blocks at end of wrapper
- No external dependencies except Google reCAPTCHA

## CSS Conventions

**Class Naming:**
- Pattern: `{prefix}-{form-type}-{element}` (BEM-like but simplified)
- Prefixes: `cloudfix-` for general CloudFix forms, form-specific for others
- Examples:
  - `.cloudfix-contact-form`, `.cloudfix-form-group`, `.cloudfix-submit-btn`
  - `.rightspend-form`, `.rightspend-form-group`, `.rightspend-submit-btn`
  - `.newsletter-form-status`, `.newsletter-email-input`

**Scoping:**
- All CSS scoped within form-specific wrapper classes
- Wrapper div uses `{form-name}-wrapper` class
- Prevents conflicts when embedded in WordPress

**Organization:**
- Inline styles in `<style>` blocks within each form file
- Base styles first (typography, layout)
- Component styles (form groups, inputs, buttons)
- State modifiers (`.success`, `.error`, `.loading`)
- Media queries at end of style block
- Responsive breakpoint: `@media (max-width: 768px)`

**Color Pattern:**
- Blues for primary actions: `#3182ce`, `#2c5aa0`
- Grays for text: `#2d3748`, `#4a5568`, `#718096`
- Green for success: `#f0fff4`, `#2f855a`
- Red for errors: `#fed7d7`, `#c53030`
- Gradients for buttons: `linear-gradient(135deg, ...)`

## JavaScript Conventions

**Variable Naming:**
- camelCase for variables and functions
- UPPER_SNAKE_CASE for constants (`WEBHOOK_URL`, `RECAPTCHA_SITE_KEY`)
- Descriptive names: `firstNameInput`, `submitBtn`, `statusDiv`

**ID Naming:**
- Pattern: `{prefix}_{fieldName}` for form elements
- Prefixes: `cf_` (contact), `rs_` (rightspend), `nl_` (newsletter), `lm_` (lead magnet), `partner_` (partner forms), `referral_` (referral partner)
- Status elements: `{prefix}-form-status`
- Submit buttons: `{prefix}_submit_btn`

**Function Naming:**
- Verb-noun pattern: `showStatus()`, `hideStatus()`, `validateForm()`, `resetForm()`, `handleSubmit()`
- Async functions use `async/await` pattern

**Event Handlers:**
- Named functions passed to `addEventListener`
- Prevent default on button clicks: `e.preventDefault()`
- Enter key support via container keypress listener

**Constants:**
- Define at top of IIFE: `WEBHOOK_URL`, `RECAPTCHA_SITE_KEY`
- Fallback pattern: `const config = window.CloudFixConfig || {};`
- Dynamic reCAPTCHA loading: `document.createElement('script')`

**Error Handling:**
- Try/catch around async operations
- Console.error for debugging: `console.error('Form submission error:', error)`
- User-friendly status messages

## Module Pattern

**IIFE Wrapper:**
All JavaScript wrapped in immediately-invoked function expression:
```javascript
(function() {
    // Skip initialization in WordPress admin
    if (document.body.classList.contains('wp-admin')) {
        return;
    }
    // Form logic here
})();
```

**Configuration Loading:**
```html
<script src="config-local.js" onerror="this.src='config.js'"></script>
```

**DOM Access:**
- Cache DOM elements at top of IIFE
- Use `getElementById()` for form elements
- Store references: `const emailInput = document.getElementById('cf_email');`

## Comments

**Style:**
- Single-line `//` comments for section descriptions
- Comments above code blocks, not inline

**When to Comment:**
- Section separators (e.g., `// Form field elements`)
- Configuration fallbacks
- Non-obvious logic (e.g., WordPress admin check)
- User-facing messages (for translation/reference)

**Comment Examples:**
```javascript
// Skip initialization in WordPress admin
if (document.body.classList.contains('wp-admin')) {
    return;
}

// Form field elements
const firstNameInput = document.getElementById('cf_firstName');

// Execute reCAPTCHA
const token = await grecaptcha.execute(RECAPTCHA_SITE_KEY, { action: 'contact_form' });
```

**No JSDoc:**
- Functions not documented with JSDoc
- Function names are descriptive enough

## Form Data Structure

**Standard Fields:**
All forms submit these metadata fields:
- `timestamp`: ISO string from `new Date().toISOString()`
- `source`: `'CloudFix {Form Name}'`
- `page_url`: `window.location.href`
- `recaptcha_token`: from reCAPTCHA v3
- `user_agent`: `navigator.userAgent`
- `form_type`: lowercase identifier (`contact`, `rightspend`, `newsletter`, `partner_opportunity`, `referral_partner`, `lead_magnet`)

**Field Naming:**
- camelCase for all JSON fields
- Trim all values: `.value.trim()`

## Configuration Pattern

**Config Object:**
```javascript
const CONFIG = {
  WEBHOOK_URL: 'url',
  RECAPTCHA_SITE_KEY: 'key',
  NOTIFICATION_EMAIL: 'email',
  ENVIRONMENT: 'development',
  DEBUG_MODE: false,
  ENABLE_RECAPTCHA: true,
  FORM_SETTINGS: {
    contact: { title: '...', redirectUrl: null }
  }
};
```

**Export Pattern:**
```javascript
if (typeof module !== 'undefined' && module.exports) {
  module.exports = CONFIG;
} else {
  window.CloudFixConfig = CONFIG;
}
```

---

*Convention analysis: 2026-05-12*
