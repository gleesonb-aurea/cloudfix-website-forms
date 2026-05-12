# Requirements - CloudFix Lead Forms

## Project Requirements

**Goal:** WordPress integration with security-hardened webhook
**Timeline:** 1-2 weeks
**Stakeholders:** CloudFix team, External partners, Marketing/Revenue

---

## Functional Requirements

### FR-1: Webhook Security
The webhook must reject unauthorized submissions to prevent random hits.

- [ ] N8N validates `X-Webhook-Secret` header
- [ ] Requests without valid secret return HTTP 403
- [ ] Secret configured via environment variable
- [ ] All forms include secret header in submissions

**Acceptance Criteria:**
- curl request without secret is rejected
- curl request with invalid secret is rejected
- Valid form submissions succeed

### FR-2: WordPress Integration
Forms must be embedded in WordPress pages without breaking site functionality.

- [ ] Contact form on `/contact-us/`
- [ ] Newsletter form in footer (all pages)
- [ ] Referral Partner form on `/partnerships-become-a-referral-partner/`
- [ ] Partner Opportunity form on `/partner-opportunity-submission/`
- [ ] Lead Magnet form on `/aws-cost-optimization-checklist/`
- [ ] RightSpend form on `rightspend.ai`

**Acceptance Criteria:**
- Forms render correctly on all pages
- No WordPress theme CSS conflicts
- Mobile responsive design works
- Form submission works without page reload

### FR-3: Form Validation
Forms must validate user input before submission.

- [ ] Required fields enforced client-side
- [ ] Email format validation
- [ ] Phone number format validation (where applicable)
- [ ] Consent checkbox required
- [ ] User-friendly error messages

**Acceptance Criteria:**
- Invalid input shows inline error
- Form cannot submit with invalid data
- Error messages are clear and actionable

### FR-4: reCAPTCHA Integration
All forms must include reCAPTCHA v3 verification.

- [ ] reCAPTCHA token generated on form submission
- [ ] Token included in webhook payload
- [ ] Server-side verification in N8N
- [ ] Graceful handling if reCAPTCHA fails

**Acceptance Criteria:**
- Forms submit successfully with valid reCAPTCHA
- Failed reCAPTCHA shows user error
- reCAPTCHA execution doesn't block UI

### FR-5: User Feedback
Forms must provide clear feedback during submission.

- [ ] Loading state during submission
- [ ] Success message after successful submission
- [ ] Error message on failure
- [ ] Form reset after successful submission

**Acceptance Criteria:**
- Button disabled during submission
- User knows submission is in progress
- Success/error messages are visually distinct

---

## Non-Functional Requirements

### NFR-1: Security
- [ ] No sensitive data in browser console
- [ ] HTTPS only for webhook endpoints
- [ ] Secret not logged in N8N workflow
- [ ] Rate limiting considered (future)

### NFR-2: Performance
- [ ] Forms load in < 2 seconds
- [ ] Submission completes in < 5 seconds
- [ ] No blocking JavaScript
- [ ] reCAPTCHA loads asynchronously

### NFR-3: Compatibility
- [ ] Works on Chrome, Firefox, Safari, Edge
- [ ] Mobile responsive (iOS, Android)
- [ ] WordPress 6.x compatible
- [ ] No jQuery dependencies

### NFR-4: Maintainability
- [ ] Configuration externalized (config-local.js)
- [ ] Consistent code patterns across forms
- [ ] Inline documentation for complex logic
- [ ] Version controlled

### NFR-5: Accessibility
- [ ] Semantic HTML
- [ ] ARIA labels where needed
- [ ] Keyboard navigation works
- [ ] Screen reader compatible

---

## Technical Requirements

### TR-1: Configuration
- [ ] `WEBHOOK_SECRET` in config-local.js
- [ ] `WEBHOOK_URL` configurable
- [ ] `RECAPTCHA_SITE_KEY` configurable
- [ ] Environment variable support

### TR-2: N8N Workflow
- [ ] Secret validation node added
- [ ] Error handling for invalid secrets
- [ ] Logging for security events
- [ ] reCAPTCHA verification maintained

### TR-3: Data Structure
All form submissions include:
```json
{
  "timestamp": "ISO string",
  "source": "CloudFix [Form Name]",
  "page_url": "current page URL",
  "recaptcha_token": "reCAPTCHA v3 token",
  "user_agent": "navigator.userAgent",
  "form_type": "contact|rightspend|newsletter|partner_opportunity|referral_partner|lead_magnet_capture"
}
```

---

## Out of Scope

- Multiple language support
- Advanced analytics tracking
- A/B testing framework
- Database storage (N8N handles this)
- Email notification templates (N8N handles this)
- Slack notifications (N8N handles this)

---

## Dependencies

| Dependency | Owner | Status |
|------------|-------|--------|
| N8N webhook validation | DevOps | Pending |
| WordPress page updates | Content team | Pending |
| reCAPTCHA configuration | CloudFix | Complete |
| Production secret value | Security | Pending |

---

## Risk Mitigation

| Risk | Mitigation |
|------|------------|
| Forms break WordPress layout | Inline CSS, scoped selectors |
| Webhook secret exposed in browser | Acceptable risk; prevents casual scraping |
| N8N downtime affects all forms | Error handling, user messaging |
| Partner forms unavailable | Staged deployment, testing |
| reCAPTCHA blocks legitimate users | Graceful degradation, support fallback |
