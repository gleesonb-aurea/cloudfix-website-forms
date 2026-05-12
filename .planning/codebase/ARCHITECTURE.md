<!-- refreshed: 2026-05-12 -->
# Architecture

**Analysis Date:** 2026-05-12

## System Overview

```text
┌─────────────────────────────────────────────────────────────────┐
│                    WordPress / Static HTML Host                  │
│                   cloudfix.com or direct HTML                    │
├──────────────────────┬──────────────────────────────────────────┤
│   contact-form.html  │  newsletter-form.html                    │
│  `[contact-form.html]`│  `[newsletter-form.html]`                │
│                      │                                          │
│ rightspend-form.html │  partner-opportunity-form.html           │
│ `[rightspend-form.html]`│ `[partner-opportunity-form.html]`       │
│                      │                                          │
│ referral-partner.html│  lead-magnet-capture-form.html           │
│ `[referral-partner-form.html]`│ `[lead-magnet-capture-form.html]`   │
└──────────┬───────────┴──────────────────┬───────────────────────┘
           │                              │
           ▼                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                    Configuration Layer                          │
│           config-local.js → config.js (fallback)                │
│              `[config-local.js]` / `[config.js]`                │
└─────────────────────────────────────────────────────────────────┘
           │
           ▼
┌─────────────────────────────────────────────────────────────────┐
│              External Service Integration                        │
│  1. Google reCAPTCHA v3 (bot verification)                      │
│  2. N8N Webhook (form processing, routing, notifications)       │
│     URL: `[config.WEBHOOK_URL]`                                 │
└─────────────────────────────────────────────────────────────────┘
```

## Component Responsibilities

| Component | Responsibility | File |
|-----------|----------------|------|
| Contact Form | General inquiries, support requests | `contact-form.html` |
| RightSpend Form | Enterprise lead capture with spend validation | `rightspend-form.html` |
| Newsletter Form | Email subscription capture | `newsletter-form.html` |
| Partner Opportunity Form | Partner referral submissions with client details | `partner-opportunity-form.html` |
| Referral Partner Form | Partner program applications | `referral-partner-form.html` |
| Lead Magnet Form | Content download/gated resource access | `lead-magnet-capture-form.html` |
| Configuration | Environment-specific settings (webhook URL, reCAPTCHA key) | `config-local.js`, `config.js` |
| WordPress Proxy | CORS proxy for server-side form submission | `wordpress-proxy.php` |
| WordPress Shortcodes | Reusable WordPress embed codes | `functions-php-addition.php` |

## Pattern Overview

**Overall:** Self-contained form components with inline CSS/JavaScript

**Key Characteristics:**
- Each form is a complete, standalone HTML fragment
- Inline scoped CSS prevents WordPress theme conflicts
- Vanilla JavaScript with no framework dependencies
- Configuration injection via external script tags
- reCAPTCHA v3 for invisible bot verification
- AJAX form submission with user feedback states

## Layers

**Presentation Layer:**
- Purpose: Form UI and user interaction
- Location: Individual `*.html` files
- Contains: HTML structure, inline CSS, event handlers
- Depends on: Configuration layer, Google reCAPTCHA
- Used by: WordPress pages, static HTML pages

**Configuration Layer:**
- Purpose: Environment-specific settings and feature flags
- Location: `config-local.js` (local), `config.js` (fallback)
- Contains: Webhook URLs, reCAPTCHA keys, feature flags, form-specific settings
- Depends on: Environment variables (optional)
- Used by: All forms

**Integration Layer:**
- Purpose: External service communication
- Contains: Google reCAPTCHA, N8N webhook
- Depends on: Presentation layer for data
- Used by: All forms

## Data Flow

### Primary Request Path

1. **User interaction** (`{form-name}.html:button click`) - User submits form
2. **Client validation** (`{form-name}.html:validateForm()`) - JavaScript validates required fields, email formats
3. **reCAPTCHA execution** (`{form-name}.html:grecaptcha.execute()`) - Invisible verification token generation
4. **Data collection** (`{form-name}.html:data object`) - FormData assembled with metadata
5. **AJAX submission** (`{form-name}.html:fetch()`) - POST to webhook URL
6. **User feedback** (`{form-name}.html:showStatus()`) - Success/error/loading states

### Configuration Loading

1. **Script injection** (`{form-name}.html:222`) - Attempts to load `config-local.js`
2. **Fallback handler** (`{form-name}.html:222`) - Falls back to `config.js` on error
3. **Global assignment** (`config.js:52`) - Sets `window.CloudFixConfig` for form access

**State Management:**
- Forms are stateless (no client-side persistence)
- Status messages displayed via DOM manipulation
- Form reset on successful submission

## Key Abstractions

**Form Wrapper Pattern:**
- Purpose: Isolate form CSS from host page styles
- Examples: `.cloudfix-contact-form-wrapper`, `.rightspend-form-wrapper`, `.partner-opportunity-wrapper`
- Pattern: Scoped CSS classes with form-specific prefixes

**Configuration Abstraction:**
- Purpose: Centralize environment-specific values
- Examples: `config.WEBHOOK_URL`, `config.RECAPTCHA_SITE_KEY`, `config.FORM_SETTINGS`
- Pattern: Global object with fallback values

**Status Display:**
- Purpose: Consistent user feedback across all forms
- Examples: `showStatus(message, type)` where type is `success`, `error`, or `loading`
- Pattern: CSS class-based styling with dynamic content

## Entry Points

**Direct HTML Access:**
- Location: Individual `{form-name}.html` files
- Triggers: Direct file opening in browser
- Responsibilities: Complete form rendering and functionality

**WordPress Integration:**
- Location: WordPress page editor (Custom HTML block) or theme files
- Triggers: Page load on WordPress site
- Responsibilities: Form embedded within WordPress content

**Shortcode Integration:**
- Location: `functions-php-addition.php`
- Triggers: Shortcode rendering (e.g., `[cloudfix_rightspend]`)
- Responsibilities: Server-side form rendering with PHP handling

## Architectural Constraints

- **Threading:** Single-threaded JavaScript event loop
- **Global state:** `window.CloudFixConfig` for configuration sharing
- **Circular imports:** Not applicable (no module system)
- **CSS isolation:** Form-specific prefixes required to avoid WordPress theme conflicts
- **No build process:** All code must be browser-ready as-is

## Anti-Patterns

### Direct ID Collisions

**What happens:** Using generic IDs like `#email` or `#submit` that conflict with host page elements
**Why it's wrong:** Multiple forms on one page or existing page elements cause selector conflicts
**Do this instead:** Use prefixed IDs like `#cf_email`, `#nl_email`, `#rs_email` (contact, newsletter, rightspend)

### Missing Configuration Fallback

**What happens:** Relying solely on `config-local.js` without fallback
**Why it's wrong:** Forms break if `config-local.js` is missing in production
**Do this instead:** Use `<script src="config-local.js" onerror="this.src='config.js'"></script>` pattern from `contact-form.html:222`

### CSS Scope Bleeding

**What happens:** Using generic class names like `.form-group` or `.submit-btn`
**Why it's wrong:** WordPress themes may override or be overridden by form styles
**Do this instead:** Always prefix with form identifier: `.cloudfix-form-group`, `.rightspend-submit-btn`

## Error Handling

**Strategy:** Client-side validation with user-friendly messages, server-side errors caught in fetch

**Patterns:**
- Field-level validation with focus management
- try-catch around async operations
- Generic fallback messages for network failures
- Status div with CSS-based styling (success/error/loading)

## Cross-Cutting Concerns

**Logging:** Console.error for development only, no production logging
**Validation:** HTML5 attributes + JavaScript regex patterns
**Authentication:** reCAPTCHA v3 token verification (server-side in n8n)
**Internationalization:** Not implemented (English only)

---

*Architecture analysis: 2026-05-12*
