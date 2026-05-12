# CloudFix Lead Forms Project

## Project Overview

Static HTML lead generation forms for CloudFix, designed to replace HubSpot forms on the cloudfix.com WordPress site. Forms submit to an N8N automation workflow via webhook.

## Project Goal

**Primary Focus:** WordPress Integration

Integrate self-contained HTML forms into WordPress pages on cloudfix.com and rightspend.ai, ensuring proper styling, functionality, and security.

## Problem Statement

- Existing HubSpot forms need replacement
- Forms are embedded as HTML code blocks in WordPress
- Webhook receiving unauthorized/random hits
- Need proper WordPress integration without theme conflicts

## Timeline

**1-2 weeks** - Short sprint, focused scope

## Primary Deliverables

1. **Security Hardened**
   - Webhook secured with X-Webhook-Secret header validation
   - No unauthorized submissions
   - N8N validation configured

## Stakeholders

- **CloudFix team** - Internal team using the forms
- **External partners** - Partner referral programs
- **Marketing/Revenue** - Teams depending on lead capture

## Current State

### Forms Implemented
- Contact Form (`contact-form.html`)
- RightSpend Form (`rightspend-form.html`)
- Newsletter Form (`newsletter-form.html`)
- Partner Opportunity Form (`partner-opportunity-form.html`)
- Referral Partner Form (`referral-partner-form.html`)
- Lead Magnet Form (`lead-magnet-capture-form.html`)

### Live Deployments
| Form | URL | Status |
|------|-----|--------|
| Newsletter | Footer (all pages) | To be deployed |
| Contact | /contact-us/ | To be deployed |
| Referral Partner | /partnerships-become-a-referral-partner/ | To be deployed |
| Partner Opportunity | /partner-opportunity-submission/ | To be deployed |
| Lead Magnet | /aws-cost-optimization-checklist/ | To be deployed |

### Technical Stack
- **Frontend:** HTML5, vanilla JavaScript, inline CSS
- **Form Handling:** reCAPTCHA v3, AJAX submission
- **Backend:** N8N automation workflow
- **Integration:** WordPress HTML code blocks

### Security Implementation
- X-Webhook-Secret header validation (pending N8N config)
- reCAPTCHA v3 verification
- Server-side validation in N8N

## Constraints

### WordPress Integration
- Forms embedded as HTML code blocks (no plugin)
- CSS must remain inline to avoid theme conflicts
- JavaScript must be self-contained
- No external dependencies except reCAPTCHA

### Deployment
- Direct WordPress page edits required
- Forms on two domains: cloudfix.com, rightspend.ai
- Config via config-local.js for environment-specific settings

## Success Criteria

1. All forms deployed to WordPress pages
2. Webhook security active in N8N
3. No unauthorized webhook submissions
4. Forms function correctly across all pages
5. Mobile-responsive design maintained

## Risks

- **WordPress theme conflicts** - Inline CSS helps mitigate
- **Browser compatibility** - Testing required
- **N8N workflow changes** - Coordination needed
- **Partner downtime** - Careful deployment required

## Related Documents

- `.planning/codebase/` - Codebase mapping documentation
- `.planning/codebase/FORM-LOCATIONS.md` - Live form locations
- `.planning/codebase/WEBHOOK-SECURITY.md` - Security plan
- `n8n-webhook-security.md` - N8N setup guide
- `CLAUDE.md` - Project guidance for Claude Code
