# Phase 1 Discussion Log

**Date:** 2025-01-12
**Phase:** 01 - Webhook Security

## Questions Asked

### 1. N8N Auth Method
**Question:** Which N8N node type should handle secret validation?

**Options presented:**
- Code node (JavaScript) — Full JavaScript control, centralized validation logic
- IF node (visual) — Visual workflow builder, simpler for non-developers
- Switch node (multiple) — Handle multiple secrets (per-form) or legacy keys

**User input:** "the n8n webhook node can authenticate - its built in"

**Follow-up question:** How should we configure N8N webhook authentication?

**Options presented:**
- Header Auth (built-in) — Configure webhook node's Header Auth option
- API Key Auth (built-in) — Use API Key auth option
- Manual validation node — Add Code/IF node after webhook

**Decision:** **Header Auth (built-in)**

---

### 2. Error Handling
**Question:** What should happen when validation fails?

**Options presented:**
- Return HTTP 403 only — Simple 403 with minimal info
- Log failed attempts — Log IP, timestamp, headers for security monitoring
- Error response to form — Return 403 + generic error message

**Decision:** **Return HTTP 403 only**

---

### 3. Testing Approach
**Question:** How should we approach testing?

**Options presented:**
- Production testing only — Test in production with curl commands
- Staging then production — Create staging webhook, test fully, then migrate
- Monitor mode first — Deploy with monitor mode (log but don't block)

**Decision:** **Production testing only**

---

### 4. Monitoring
**Question:** What monitoring/security logging do we need?

**Options presented:**
- Failed attempt counter — Count and log failed auth attempts
- IP logging on failures — Log source IPs of repeated failures
- Alert on threshold — Send Slack/email alert on threshold
- No monitoring needed — Keep it simple

**Decision:** **No monitoring needed**

---

### 5. Secret Rotation
**Question:** How often should we rotate the webhook secret?

**Options presented:**
- Rotate on compromise only — No scheduled rotation
- Quarterly (90 days) — Quarterly rotation schedule
- Document process, no schedule — Set up rotation process but execute only when needed

**Decision:** **Rotate on compromise only**

---

## Decisions Summary

| ID | Category | Decision |
|----|----------|----------|
| D-01 | N8N Auth Method | Header Auth (built-in) |
| D-02 | Error Handling | HTTP 403 only |
| D-03 | Testing | Production testing only |
| D-04 | Monitoring | No monitoring needed |
| D-05 | Secret Rotation | Rotate on compromise only |

---

## Deferred Ideas

None — discussion stayed within phase scope.
