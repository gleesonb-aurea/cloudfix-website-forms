# Phase 1: Webhook Security - Context

**Gathered:** 2025-01-12
**Status:** Ready for planning

## Phase Boundary

Configure N8N webhook authentication to reject unauthorized submissions. The forms already send `X-Webhook-Secret` headers — this phase completes the server-side validation and testing.

**In scope:**
- N8N webhook node authentication configuration
- Secret environment variable setup
- Production testing (valid/invalid/missing secret)
- Documentation of rotation process

**Out of scope:**
- Form code changes (already complete)
- WordPress deployment (Phase 2)
- Enhanced monitoring/logging infrastructure
- Rate limiting (future enhancement)

## Implementation Decisions

### N8N Authentication Method
- **D-01:** Use N8N webhook node's built-in **Header Auth** option
  - Configure header name: `X-Webhook-Secret`
  - Configure header value: from `$env.WEBHOOK_SECRET`
  - N8N validates automatically before workflow executes

### Error Handling
- **D-02:** Return **HTTP 403 only** on validation failure
  - No error message body (minimize information leakage)
  - No response to form
  - No logging of failed attempts

### Testing Approach
- **D-03:** **Production testing only**
  - Test via curl commands against live webhook
  - Test cases: valid secret, invalid secret, missing secret
  - No staging environment required

### Monitoring
- **D-04:** **No monitoring needed** for this phase
  - Failed auth attempts handled silently (403)
  - N8N's built-in logging sufficient
  - No external alerts or IP logging

### Secret Management
- **D-05:** Rotate on **compromise only**
  - No scheduled rotation
  - Document rotation process for future use
  - Process: generate new secret, update env var, deploy forms

## Canonical References

**Downstream agents MUST read these before planning or implementing.**

### N8N Configuration
- `n8n-webhook-security.md` — Complete N8N setup guide with code node examples, curl testing commands, and rollback procedures
- `.planning/codebase/WEBHOOK-SECURITY.md` — Security plan with Phase 1/2/3 approach and threat model

### Form Implementation
- `.planning/codebase/INTEGRATIONS.md` — Current webhook endpoint, data structure, and authentication state
- `config.js` — Default configuration (contains fallback webhook URL and secret placeholder)
- `config-local.example.js` — Template for local config (shows WEBHOOK_SECRET pattern)

### Requirements
- `.planning/REQUIREMENTS.md` — FR-1 (Webhook Security), NFR-1 (Security), TR-1 (Configuration), TR-2 (N8N Workflow)

## Existing Code Insights

### Reusable Assets
- **N8N workflow:** Existing webhook at `https://automate.billgleeson.com/webhook/cloudfix-website-forms` — currently has reCAPTCHA validation, needs header auth added
- **All 6 forms:** Already send `X-Webhook-Secret` header (completed in previous commit)
- **Configuration system:** `config.js` + `config-local.js` pattern already supports `WEBHOOK_SECRET`

### Established Patterns
- **Environment variables:** N8N uses `$env.VAR_NAME` for secrets (e.g., `$env.RECAPTCHA_SECRET_KEY`)
- **Optional headers:** Forms use `...(WEBHOOK_SECRET ? { 'X-Webhook-Secret': WEBHOOK_SECRET } : {})` pattern — no header if secret not configured
- **Fallback behavior:** Missing secret results in empty header string, not crash

### Integration Points
- **Webhook entry point:** N8N webhook node (first node in workflow)
- **Secret location:** N8N environment variables (not in workflow)
- **Forms location:** cloudfix.com WordPress pages (Phase 2) and rightspend.ai (Phase 3)

## Specific Ideas

No specific requirements — standard N8N Header Auth configuration.

## Deferred Ideas

None — discussion stayed within phase scope.
