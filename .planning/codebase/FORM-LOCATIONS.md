# Form Deployment Locations

## CloudFix (cloudfix.com)

Forms are embedded as **HTML code blocks** in WordPress pages. CSS must remain inline or scoped to avoid WordPress theme conflicts.

| Form | URL | Implementation |
|------|-----|----------------|
| Newsletter | Footer (all pages) | Global footer widget/code block |
| Contact | `/contact-us/` | Page content code block |
| Referral Partner | `/partnerships-become-a-referral-partner/` | Page content code block |
| Partner Opportunity | `/partner-opportunity-submission/` | Page content code block |
| Lead Magnet | `/aws-cost-optimization-checklist/` | Page content code block |

## RightSpend (rightspend.ai)

| Form | URL | Implementation |
|------|-----|----------------|
| RightSpend | Homepage | Direct embed (TBD) |

## Implementation Constraints

### CloudFix WordPress Integration
- **Method**: Custom HTML blocks in page editor
- **CSS**: Must remain inline to avoid theme conflicts
- **JavaScript**: Must be self-contained, no external dependencies except reCAPTCHA
- **Updates**: Direct WordPress page edits required

### DO NOT
- Extract CSS to external files (will conflict with WordPress themes)
- Assume WordPress theme styles will apply
- Use JavaScript that depends on global WordPress scripts

### Security Notes
- Webhook is receiving hits from non-form sources
- Need to add origin/secret validation
- Consider CORS restrictions
