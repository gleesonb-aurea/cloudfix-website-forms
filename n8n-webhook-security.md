# N8N Webhook Security Setup

## Overview
All CloudFix forms now send an `X-Webhook-Secret` header with submissions. This header must be validated in N8N to reject unauthorized requests.

## Environment Variables

Add to your N8N environment variables:

```bash
WEBHOOK_SECRET=your-secure-random-string-here
```

Generate a secure secret:
```bash
openssl rand -hex 32
# or
node -e "console.log(require('crypto').randomBytes(32).toString('hex'))"
```

## N8N Workflow Changes

### Option 1: Code Node (Recommended)

Add a **Code** node immediately after the Webhook trigger:

```javascript
// Validate webhook secret
const secret = $env.WEBHOOK_SECRET;
const headerSecret = $header['x-webhook-secret'];

if (!secret) {
  throw new Error('WEBHOOK_SECRET not configured in environment');
}

if (!headerSecret || headerSecret !== secret) {
  throw new Error('Invalid webhook secret');
}

// Secret validated - continue
return { json: $json };
```

**Settings:**
- Mode: **Run Once for All Items**
- On Error: **Continue** (with error handling node) or **Stop Workflow**

### Option 2: IF Node

Add an **IF** node after the Webhook trigger:

**Condition:**
```
{{ $header['x-webhook-secret'] === $env.WEBHOOK_SECRET }}
```

**True branch:** Continue to reCAPTCHA verification
**False branch:** Return HTTP 403 response

### Option 3: Switch Node (Multiple Secrets)

If you need to support multiple secrets (e.g., per form):

```javascript
// Switch node conditions
{{ $header['x-webhook-secret'] === $env.WEBHOOK_SECRET }}
{{ $header['x-webhook-secret'] === $env.WEBHOOK_SECRET_LEGACY }}
```

## WordPress Configuration

For WordPress-embedded forms, create a `config-local.js` in the same directory as your forms:

```javascript
const CONFIG = {
  WEBHOOK_URL: 'https://automate.billgleeson.com/webhook/cloudfix-website-forms',
  WEBHOOK_SECRET: 'your-secure-random-string-here', // Must match N8N env var
  RECAPTCHA_SITE_KEY: '6LctAb8rAAAAAG900ftMg2zJq13aLpJa5joqZ9yb'
};

window.CloudFixConfig = CONFIG;
```

**Security Note:** The secret is visible in browser dev tools, but this still prevents:
- Random scraping/hits to your webhook
- Automated bots that don't inspect your JavaScript
- Cross-origin form submissions from other sites

## Testing

### Test Valid Secret
```bash
curl -X POST https://automate.billgleeson.com/webhook/cloudfix-website-forms \
  -H "Content-Type: application/json" \
  -H "X-Webhook-Secret: your-actual-secret" \
  -d '{"test": true}'
```

### Test Invalid Secret (Should Reject)
```bash
curl -X POST https://automate.billgleeson.com/webhook/cloudfix-website-forms \
  -H "Content-Type: application/json" \
  -H "X-Webhook-Secret: wrong-secret" \
  -d '{"test": true}'
```

### Test Missing Secret (Should Reject)
```bash
curl -X POST https://automate.billgleeson.com/webhook/cloudfix-website-forms \
  -H "Content-Type: application/json" \
  -d '{"test": true}'
```

## Rollback Plan

If forms break after deploying:
1. Forms will still work without the secret (returns empty header)
2. The validation uses optional chaining: `...(WEBHOOK_SECRET ? { 'X-Webhook-Secret': WEBHOOK_SECRET } : {})`
3. Remove validation in N8N and keep for later deployment

## Enhanced Security (Future)

Consider adding:
1. **HMAC signatures** for tamper-proof payloads
2. **Rate limiting** per IP address in N8N
3. **Origin validation** (check Referer/Origin headers)
4. **Request logging** for security monitoring
5. **IP whitelisting** if forms only come from known sources
