# Webhook Security Plan

## Current State
- **Webhook URL**: `https://automate.billgleeson.com/webhook/cloudfix-website-forms`
- **Security**: Only reCAPTCHA v3 token (verified server-side in n8n)
- **Issue**: Receiving hits from non-form sources

## Current Vulnerabilities
1. ❌ No secret/key authentication
2. ❌ No origin validation (anyone can POST)
3. ❌ No Referer header check
4. ❌ No rate limiting
5. ❌ Public webhook URL discoverable

## Proposed Security Layers

### Option 1: Shared Secret Header (Recommended)
Add a secret header to all form submissions:

**Forms (JavaScript):**
```javascript
const response = await fetch(WEBHOOK_URL, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-Webhook-Secret': WEBHOOK_SECRET  // New
    },
    body: JSON.stringify(data)
});
```

**Config files:**
```javascript
const WEBHOOK_SECRET = config.WEBHOOK_SECRET || 'default-secret-key';
```

**N8N:**
- Verify `X-Webhook-Secret` header matches env var
- Reject if missing or invalid (before reCAPTCHA check)

**Pros:** Simple, effective, stops random hits immediately
**Cons:** Secret visible in browser source (but still stops casual scraping)

---

### Option 2: HMAC Signature (More Secure)
Sign payload with secret:

**Forms (JavaScript):**
```javascript
async function generateSignature(payload, secret) {
    const encoder = new TextEncoder();
    const key = await crypto.subtle.importKey(
        'raw', encoder.encode(secret), { name: 'HMAC', hash: 'SHA-256' }, false, ['sign']
    );
    const signature = await crypto.subtle.sign('HMAC', key, encoder.encode(payload));
    return btoa(String.fromCharCode(...new Uint8Array(signature)));
}

const payload = JSON.stringify(data);
const signature = await generateSignature(payload, WEBHOOK_SECRET);

const response = await fetch(WEBHOOK_URL, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-Signature': signature
    },
    body: payload
});
```

**N8N:**
- Recompute HMAC from body + secret
- Compare with `X-Signature` header

**Pros:** Tamper-proof, secret not directly exposed
**Cons:** More complex, requires async crypto

---

### Option 3: Origin/Referer Validation (Server-side)
Validate requests come from allowed domains:

**N8N:**
- Check `Origin` or `Referer` header
- Whitelist: `cloudfix.com`, `rightspend.ai`
- Reject if missing or not in whitelist

**Pros:** No client changes needed
**Cons:** Headers can be spoofed, but stops casual scraping

---

### Option 4: CORS + Preflight (WordPress Compatible)
Add CORS validation:

**N8N:**
- Return 403 for OPTIONS without correct Origin
- Only respond to preflight from allowed domains

**Pros:** Browser-enforced
**Cons:** Can be bypassed, requires careful CORS config

---

## Recommended Implementation

**Phase 1: Quick Fix (Deploy Today)**
1. Add shared secret header to all forms
2. Configure n8n to validate secret
3. Enable origin/referer validation in n8n

**Phase 2: Enhanced Security**
1. Add HMAC signatures
2. Implement rate limiting in n8n
3. Add request logging for monitoring

**Phase 3: Monitoring**
1. Alert on failed auth attempts
2. Log suspicious patterns
3. Consider IP blocking for repeat offenders

## Configuration Updates Required

### `config-local.example.js`
```javascript
const CONFIG = {
    WEBHOOK_URL: 'your-webhook-endpoint',
    WEBHOOK_SECRET: 'your-webhook-secret-key',  // NEW
    RECAPTCHA_SITE_KEY: 'your-recaptcha-site-key',
    // ...
};
```

### N8N Environment Variables
```
WEBHOOK_SECRET=your-production-secret-key
ALLOWED_ORIGINS=cloudfix.com,rightspend.ai
```

## Files to Update
- `config.js` - Add WEBHOOK_SECRET default
- `config-local.example.js` - Add WEBHOOK_SECRET placeholder
- `*-form.html` (all 5 forms) - Add secret header to fetch
- `n8n workflow` - Add secret validation node

## WordPress Compatibility Note
All forms are embedded as HTML code blocks. Adding headers requires no WordPress changes - pure JavaScript fetch modification.
