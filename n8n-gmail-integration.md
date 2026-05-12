# N8N Gmail Integration Guide

## Webhook URL
All forms submit to: `https://automate.billgleeson.com/webhook/cloudfix-website-forms`

## N8N Workflow Setup

### 1. Webhook Node Configuration
- **Method**: POST
- **Path**: `/webhook/cloudfix-website-forms`
- **Response Mode**: Respond Immediately
- **Response Code**: 200
- **Response Data**: `{"status": "success", "message": "Form submitted successfully"}`

### 2. reCAPTCHA Verification Node (Optional but Recommended)
Add an HTTP Request node to verify reCAPTCHA:
- **Method**: POST
- **URL**: `https://www.google.com/recaptcha/api/siteverify`
- **Body**:
  ```json
  {
    "secret": "{{ $env.RECAPTCHA_SECRET_KEY }}",
    "response": "{{ $json.recaptcha_token }}"
  }
  ```

### 3. Form Type Detection
Add a Switch node to route based on form type:
- **Mode**: Rules
- **Rules**:
  - `{{ $json.body.form_type }} === 'contact'` → Contact Form Branch
  - `{{ $json.body.form_type }} === 'rightspend'` → RightSpend Branch  
  - `{{ $json.body.form_type }} === 'newsletter'` → Newsletter Branch
  - `{{ $json.body.form_type }} === 'partner_opportunity'` → Partner Opportunity Branch
  - `{{ $json.body.form_type }} === 'referral_partner'` → Referral Partner Branch
  - Default → General Form Branch

### 4. Gmail Integration Node
For each branch, add a Gmail node:

#### Gmail Node Configuration:
- **Resource**: Message
- **Operation**: Send
- **To**: `bill@billgleeson.com` (or your preferred email)
- **Subject**: Dynamic based on form type (see examples below)
- **Message Type**: HTML
- **Message**: Dynamic HTML template (see examples below)

## Email Templates by Form Type

### Contact Form Email Template
**Subject**: `New Contact Form Submission from {{ $json.body.firstName }} {{ $json.body.lastName }}`

**HTML Body**:
```html
<h2>New Contact Form Submission</h2>
<p><strong>Submitted:</strong> {{ $json.body.timestamp }}</p>
<p><strong>Source:</strong> {{ $json.body.source }}</p>
<p><strong>Page URL:</strong> {{ $json.body.page_url }}</p>

<h3>Contact Information</h3>
<ul>
  <li><strong>Name:</strong> {{ $json.body.firstName }} {{ $json.body.lastName }}</li>
  <li><strong>Email:</strong> {{ $json.body.email }}</li>
  <li><strong>Company:</strong> {{ $json.body.company }}</li>
  <li><strong>Job Title:</strong> {{ $json.body.jobTitle || 'Not provided' }}</li>
  <li><strong>Phone:</strong> {{ $json.body.phone || 'Not provided' }}</li>
</ul>

<h3>Message</h3>
<p>{{ $json.body.message || 'No message provided' }}</p>

<h3>Marketing Preferences</h3>
<p><strong>Newsletter Signup:</strong> {{ $json.body.newsletter ? 'Yes' : 'No' }}</p>

<hr>
<p><small>User Agent: {{ $json.body.user_agent }}</small></p>
```

### RightSpend Form Email Template
**Subject**: `New RightSpend Lead from {{ $json.body.firstName }} {{ $json.body.lastName }} - {{ $json.body.company }}`

**HTML Body**:
```html
<h2>🚀 New RightSpend Enterprise Lead</h2>
<p><strong>Submitted:</strong> {{ $json.body.timestamp }}</p>
<p><strong>Annual EC2 Spend:</strong> <span style="color: #e53e3e; font-weight: bold;">{{ $json.body.annualSpend }}</span></p>

<h3>Contact Information</h3>
<ul>
  <li><strong>Name:</strong> {{ $json.body.firstName }} {{ $json.body.lastName }}</li>
  <li><strong>Email:</strong> {{ $json.body.email }}</li>
  <li><strong>Company:</strong> {{ $json.body.company }}</li>
  <li><strong>Job Title:</strong> {{ $json.body.jobTitle || 'Not provided' }}</li>
  <li><strong>Phone:</strong> {{ $json.body.phone || 'Not provided' }}</li>
</ul>

<h3>Marketing Preferences</h3>
<p><strong>Newsletter Signup:</strong> {{ $json.body.newsletter ? 'Yes' : 'No' }}</p>

<hr>
<p><small>Source: {{ $json.body.source }} | Page: {{ $json.body.page_url }}</small></p>
```

### Newsletter Form Email Template
**Subject**: `New Newsletter Subscription: {{ $json.body.email }}`

**HTML Body**:
```html
<h2>📧 New Newsletter Subscription</h2>
<p><strong>Submitted:</strong> {{ $json.body.timestamp }}</p>
<p><strong>Email:</strong> {{ $json.body.email }}</p>
<p><strong>Source:</strong> {{ $json.body.source }}</p>
<p><strong>Page URL:</strong> {{ $json.body.page_url }}</p>

<hr>
<p><small>User Agent: {{ $json.body.user_agent }}</small></p>
```

### Partner Opportunity Form Email Template
**Subject**: `Partner Opportunity: {{ $json.body.clientCompany }} ({{ $json.body.estimatedSpend }}) - {{ $json.body.partnerFirstName }} {{ $json.body.partnerLastName }}`

**HTML Body**:
```html
<h2>🤝 New Partner Opportunity Submission</h2>
<p><strong>Submitted:</strong> {{ $json.body.timestamp }}</p>

<h3>Partner Information</h3>
<ul>
  <li><strong>Partner Name:</strong> {{ $json.body.partnerFirstName }} {{ $json.body.partnerLastName }}</li>
  <li><strong>Partner Email:</strong> {{ $json.body.partnerEmail }}</li>
  <li><strong>Partner Company:</strong> {{ $json.body.partnerCompany }}</li>
</ul>

<h3>Client/Opportunity Details</h3>
<ul>
  <li><strong>Client Name:</strong> {{ $json.body.clientFirstName }} {{ $json.body.clientLastName }}</li>
  <li><strong>Client Email:</strong> {{ $json.body.clientEmail }}</li>
  <li><strong>Client Company:</strong> {{ $json.body.clientCompany }}</li>
  <li><strong>Client Phone:</strong> {{ $json.body.clientPhone || 'Not provided' }}</li>
  <li><strong>Estimated Annual AWS Spend:</strong> <span style="color: #e53e3e; font-weight: bold;">{{ $json.body.estimatedSpend }}</span></li>
</ul>

<h3>Opportunity Details</h3>
<p>{{ $json.body.opportunityDetails || 'No details provided' }}</p>

<h3>Consent & Preferences</h3>
<ul>
  <li><strong>Client Consent:</strong> {{ $json.body.clientConsent ? 'Yes - Client has consented to contact' : 'No' }}</li>
  <li><strong>Partner Updates:</strong> {{ $json.body.partnerUpdates ? 'Yes' : 'No' }}</li>
</ul>

<hr>
<p><small>Source: {{ $json.body.source }} | Page: {{ $json.body.page_url }}</small></p>
```

### Referral Partner Form Email Template
**Subject**: `New Referral Partner Application: {{ $json.body.firstName }} {{ $json.body.lastName }} - {{ $json.body.company }}`

**HTML Body**:
```html
<h2>🎯 New Referral Partner Application</h2>
<p><strong>Submitted:</strong> {{ $json.body.timestamp }}</p>

<h3>Applicant Information</h3>
<ul>
  <li><strong>Name:</strong> {{ $json.body.firstName }} {{ $json.body.lastName }}</li>
  <li><strong>Email:</strong> {{ $json.body.email }}</li>
  <li><strong>Phone:</strong> {{ $json.body.phone || 'Not provided' }}</li>
  <li><strong>Company:</strong> {{ $json.body.company }}</li>
  <li><strong>Job Title:</strong> {{ $json.body.jobTitle || 'Not provided' }}</li>
  <li><strong>Business Type:</strong> {{ $json.body.businessType }}</li>
</ul>

<h3>Client Base Description</h3>
<p>{{ $json.body.clientBase || 'Not provided' }}</p>

<h3>AWS/Cloud Experience</h3>
<p>{{ $json.body.experience || 'Not provided' }}</p>

<h3>Questions/Additional Information</h3>
<p>{{ $json.body.questions || 'None provided' }}</p>

<h3>Agreements & Preferences</h3>
<ul>
  <li><strong>Partnership Agreement:</strong> {{ $json.body.partnerAgreement ? 'Agreed to be contacted about program' : 'No' }}</li>
  <li><strong>Partner Updates:</strong> {{ $json.body.partnerUpdates ? 'Yes' : 'No' }}</li>
</ul>

<hr>
<p><small>Source: {{ $json.body.source }} | Page: {{ $json.body.page_url }}</small></p>
```

## Additional N8N Nodes (Optional)

### 1. Data Storage
Add a Google Sheets or Airtable node to store form submissions for tracking.

### 2. Slack Notifications
Add a Slack node for real-time notifications on high-priority forms (RightSpend, Partner Opportunities).

### 3. Auto-Responders
Add additional Gmail nodes to send confirmation emails to form submitters.

### 4. Lead Scoring
Add Function nodes to calculate lead scores based on form data (company size, spend level, etc.).

## Testing
1. Submit test data through each form
2. Verify emails are received with correct formatting
3. Check that reCAPTCHA tokens are being verified
4. Ensure all form fields are captured properly

## Security Notes
- Keep the reCAPTCHA secret key secure in N8N
- Consider adding rate limiting to prevent spam
- Monitor webhook logs for unusual activity