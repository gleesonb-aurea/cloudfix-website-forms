# Codebase Structure

**Analysis Date:** 2026-05-12

## Directory Layout

```
lead-forms/
├── contact-form.html              # Main contact/lead capture form
├── rightspend-form.html           # Enterprise lead form with spend validation
├── newsletter-form.html           # Email subscription form
├── partner-opportunity-form.html  # Partner referral submission form
├── referral-partner-form.html     # Partner program application form
├── lead-magnet-capture-form.html  # Gated content/lead magnet form
├── contact-form-debug.html        # Debug version with additional logging
├── contact-form-no-recaptcha.html # Version without reCAPTCHA for testing
├── rightspend-form-nocors.html    # CORS workaround variant
├── rightspend-form-wordpress.html # WordPress-specific version
├── config.js                      # Default/fallback configuration
├── config-local.example.js        # Configuration template
├── config-local.js                # Local development configuration (gitignored)
├── wordpress-proxy.php            # CORS proxy for WordPress installations
├── functions-php-addition.php     # WordPress shortcode implementations
├── wordpress-integration.md       # WordPress integration documentation
├── n8n-gmail-integration.md       # N8N webhook setup documentation
├── README.md                      # Project overview and usage
├── CLAUDE.md                      # Claude Code project instructions
├── .gitignore                     # Git ignore rules
└── .planning/                     # Planning and documentation directory
```

## Directory Purposes

**Root directory:**
- Purpose: Contains all form files, configuration, and integration code
- Contains: HTML forms, JavaScript config, PHP integration files, documentation
- Key files: `contact-form.html`, `config.js`, `functions-php-addition.php`

**`.planning/`:**
- Purpose: Generated planning documents and analysis
- Contains: Codebase architecture and structure documentation
- Key files: `ARCHITECTURE.md`, `STRUCTURE.md`

**`.claude/`:**
- Purpose: Claude Code agent configuration
- Contains: Settings for AI assistant
- Key files: `settings.local.json`

## Key File Locations

**Entry Points:**
- `contact-form.html`: Main contact form for general inquiries
- `rightspend-form.html`: Enterprise lead capture with spend qualification
- `newsletter-form.html`: Email subscription form
- `partner-opportunity-form.html`: Partner referral submissions
- `referral-partner-form.html`: Partner program applications
- `lead-magnet-capture-form.html`: Content download/gated resource access

**Configuration:**
- `config-local.js`: Local development settings (never committed)
- `config.js`: Default production fallback values
- `config-local.example.js`: Template for creating local config

**WordPress Integration:**
- `wordpress-proxy.php`: CORS proxy for server-side form submission
- `functions-php-addition.php`: Shortcode implementations for easy WordPress embedding

**Documentation:**
- `README.md`: Project overview and setup instructions
- `CLAUDE.md`: Claude Code project-specific instructions
- `wordpress-integration.md`: WordPress implementation guide
- `n8n-gmail-integration.md`: Webhook and automation setup

**Debug/Variants:**
- `contact-form-debug.html`: Development version with verbose logging
- `contact-form-no-recaptcha.html`: Testing version without bot protection
- `rightspend-form-nocors.html`: CORS workaround for specific hosting scenarios
- `rightspend-form-wordpress.html`: WordPress-specific implementation variant

## Naming Conventions

**Files:**
- Forms: `{form-purpose}-form.html` (kebab-case)
- Examples: `contact-form.html`, `rightspend-form.html`, `partner-opportunity-form.html`
- Special variants: `{form-name}-{variant}.html` (e.g., `contact-form-debug.html`)

**Directories:**
- No subdirectories used (flat structure for simple deployment)

**CSS Classes:**
- Form wrapper: `{form-prefix}-form-wrapper` or `{form-prefix}-wrapper`
- Form container: `{form-prefix}-form`
- Form groups: `{form-prefix}-form-group`
- Buttons: `{form-prefix}-submit-btn`
- Status messages: `{form-prefix}-form-status`

**CSS Prefixes by Form:**
- Contact: `cloudfix-` or `cloudfix-contact-`
- RightSpend: `rightspend-`
- Newsletter: `newsletter-` or `cloudfix-newsletter-`
- Partner Opportunity: `partner-`
- Referral Partner: `referral-`
- Lead Magnet: `lead-magnet-`

**JavaScript IDs:**
- Input fields: `{prefix}_{fieldName}` (e.g., `cf_firstName`, `nl_email`)
- Buttons: `{prefix}_submit_btn`
- Status div: `{prefix}-form-status`
- Container: `{prefix}-form-container`

**JavaScript ID Prefixes by Form:**
- Contact: `cf_`
- RightSpend: `rs_`
- Newsletter: `nl_`
- Partner Opportunity: `po_`
- Referral Partner: `rp_`
- Lead Magnet: `lm_`

## Where to Add New Code

**New Form:**
- Primary code: Create `{form-name}-form.html` in root directory
- Tests: No test framework (manual testing required)
- Configuration: Add settings to `config.js` and `config-local.example.js` under `FORM_SETTINGS`

**New Form Element:**
- HTML: Add to form container in appropriate `{form-name}-form.html`
- CSS: Add scoped styles within the `<style>` tag
- JavaScript: Add field reference in form's IIFE, update validation and data collection

**Configuration Changes:**
- Development: Update `config-local.js` (gitignored)
- Production defaults: Update `config.js`
- New settings template: Add to `config-local.example.js`

**WordPress Integration:**
- Shortcode: Add to `functions-php-addition.php`
- Proxy logic: Modify `wordpress-proxy.php`
- Documentation: Update `wordpress-integration.md`

**Utility Functions:**
- No shared utilities directory
- Add form-specific functions within each form's IIFE
- Consider creating `shared.js` if reuse emerges across 3+ forms

## Special Directories

**Root Directory:**
- Purpose: All source files for immediate deployment
- Generated: No
- Committed: Yes (except `config-local.js`)

**`.gitignore`:**
- Excludes: `config-local.js` (contains secrets)
- All other files are tracked

**No Build Process:**
- All files are deployment-ready as-is
- No compilation, bundling, or transpilation required
- Direct browser execution

---

*Structure analysis: 2026-05-12*
