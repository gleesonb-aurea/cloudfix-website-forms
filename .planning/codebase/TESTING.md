# Testing Patterns

**Analysis Date:** 2026-05-12

## Current Testing State

**Test Framework:**
- None (no automated testing framework)

**Test Coverage:**
- No automated test suite
- No unit tests
- No integration tests
- No E2E tests

**Manual Testing:**
- Open HTML files directly in browser
- Test form submissions by filling forms
- Check browser console for errors
- Verify webhook receives data (via n8n logs)

## Client-Side Validation

**HTML5 Validation:**
- Used sparingly in favor of JavaScript validation
- `type="email"` for email fields
- `required` attribute in some forms (not consistently applied)

**JavaScript Validation:**
- Custom validation functions in each form
- Pattern: `validateForm()` returns boolean, shows status message on failure
- Focus management: `element.focus()` on first invalid field
- Email validation regex: `/^[^\s@]+@[^\s@]+\.[^\s@]+$/`

**Validation Function Pattern:**
```javascript
function validateForm() {
    if (!firstNameInput.value.trim()) {
        showStatus('Please enter your first name.', 'error');
        firstNameInput.focus();
        return false;
    }
    // ... more validations
    return true;
}
```

**Required Field Indication:**
- CSS class `.required` on form groups
- Visual indicator: `label::after { content: ' *'; color: #e53e3e; }`
- Consistent across all forms

## Server-Side Validation

**Location:**
- Handled by n8n webhook workflow
- reCAPTCHA token verification: `{{ $env.RECAPTCHA_SECRET_KEY }}`
- Form type detection via `form_type` field
- See `n8n-gmail-integration.md` for webhook configuration

**Validation Handled by Webhook:**
- reCAPTCHA v3 token verification
- Form data sanitization
- Spam filtering

## reCAPTCHA Verification

**Implementation:**
- Google reCAPTCHA v3 (invisible verification)
- Dynamically loaded per form: `https://www.google.com/recaptcha/api.js?render={SITE_KEY}`
- Executed before form submission: `grecaptcha.execute(SITE_KEY, { action: 'form_name' })`

**Action Names:**
- `contact_form`
- `rightspend_form`
- `newsletter_signup`
- `partner_opportunity_form`
- `referral_partner_form`
- `lead_magnet_form`

**Testing Without reCAPTCHA:**
- Use `*-no-recaptcha.html` variants for local testing
- Set `ENABLE_RECAPTCHA: false` in config

## Manual Testing Checklist

**Form Submission:**
- [ ] Fill all required fields, submit successfully
- [ ] Submit with empty required fields (show error)
- [ ] Submit with invalid email (show error)
- [ ] Submit without consent checkbox (show error for forms that require it)
- [ ] Verify data appears in n8n webhook logs
- [ ] Verify email notification received (if configured)

**reCAPTCHA:**
- [ ] Verify reCAPTCHA script loads
- [ ] Submit form and check token is generated
- [ ] Test with invalid/missing token (via dev tools)

**Responsive Design:**
- [ ] Test at 768px breakpoint (mobile layout)
- [ ] Test on actual mobile device
- [ ] Verify touch targets are adequate (44px minimum)
- [ ] Check keyboard navigation on desktop

**Cross-Browser:**
- [ ] Chrome/Edge (Chromium)
- [ ] Firefox
- [ ] Safari (if available)
- [ ] Mobile browsers (iOS Safari, Chrome Mobile)

**WordPress Integration:**
- [ ] Embed via custom HTML block
- [ ] Embed via shortcode (if using PHP version)
- [ ] Verify no CSS conflicts with theme
- [ ] Test on WordPress admin page (should not initialize)

## Status Messaging

**Status States:**
- `.loading` - Processing submission
- `.success` - Submission successful
- `.error` - Submission or validation failed

**Status Function:**
```javascript
function showStatus(message, type) {
    statusDiv.textContent = message;
    statusDiv.className = `{prefix}-form-status ${type}`;
    statusDiv.style.display = 'block';
}
```

**Button State During Submission:**
```javascript
submitBtn.disabled = true;
submitBtn.textContent = 'Sending...';
```

## Debug Variants

**Available Debug Files:**
- `contact-form-debug.html` - Enhanced logging for troubleshooting
- `contact-form-no-recaptcha.html` - Bypasses reCAPTCHA for local testing

**Debug Mode:**
- Set `DEBUG_MODE: true` in `config-local.js`
- Adds console logging for form submission steps

## WordPress-Specific Testing

**Shortcode Testing:**
- Use `functions-php-addition.php` patterns
- Test shortcode output: `[cloudfix_contact]`, `[cloudfix_rightspend]`
- Verify nonce verification works
- Test form submission via POST

**CORS Testing:**
- `rightspend-form-nocors.html` - Variant for CORS issues
- `wordpress-proxy.php` - PHP proxy for webhook requests

## Configuration Testing

**Environment Switching:**
1. Create `config-local.js` from `config-local.example.js`
2. Set `ENVIRONMENT: 'development'`
3. Test with local webhook endpoint
4. Remove `config-local.js` before commit

**Testing Without Config File:**
- Forms fall back to hardcoded defaults
- `config.js` loads if `config-local.js` fails

## Common Issues

**Form Not Submitting:**
- Check browser console for errors
- Verify webhook URL is accessible
- Check CORS configuration (test `*-nocors.html` variant)

**reCAPTCHA Issues:**
- Verify site key matches domain
- Check that reCAPTCHA script loads
- Use `*-no-recaptcha.html` variant to isolate issue

**WordPress Conflicts:**
- Check browser console for CSS conflicts
- Verify form doesn't initialize in admin (`wp-admin` class check)
- Test with different WordPress themes

## Future Testing Recommendations

**Consider Adding:**
- Jest or Vitest for JavaScript unit tests
- Playwright or Cypress for E2E testing
- Visual regression testing for responsive layouts
- Automated webhook testing (mock n8n endpoint)
- CI/CD integration for automated tests

**Test Files to Create:**
- `tests/` directory for test files
- `tests/` fixtures for form data samples
- Mock webhook endpoint for local testing

---

*Testing analysis: 2026-05-12*
