// Configuration file for CloudFix Forms
// Copy this to config-local.js and update with your values
// NEVER commit config-local.js to version control

const CONFIG = {
  // Webhook URL for form submissions
  WEBHOOK_URL: process.env.WEBHOOK_URL || 'https://automate.billgleeson.com/webhook/cloudfix-website-forms',

  // reCAPTCHA v3 Site Key (public)
  RECAPTCHA_SITE_KEY: process.env.RECAPTCHA_SITE_KEY || '6LctAb8rAAAAAG900ftMg2zJq13aLpJa5joqZ9yb',

  // Webhook secret for securing form submissions (must match n8n WEBHOOK_SECRET env var)
  WEBHOOK_SECRET: process.env.WEBHOOK_SECRET || '',

  // Email for notifications (used in WordPress/PHP versions)
  NOTIFICATION_EMAIL: process.env.NOTIFICATION_EMAIL || 'bill@billgleeson.com',

  // Environment settings
  ENVIRONMENT: process.env.NODE_ENV || 'development',

  // Feature flags
  DEBUG_MODE: process.env.DEBUG_MODE === 'true' || false,
  ENABLE_RECAPTCHA: process.env.ENABLE_RECAPTCHA !== 'false',

  // Form-specific settings
  FORM_SETTINGS: {
    contact: {
      title: 'CloudFix Contact Form',
      redirectUrl: process.env.CONTACT_REDIRECT_URL || null
    },
    rightspend: {
      title: 'CloudFix RightSpend Form',
      redirectUrl: process.env.RIGHTSPEND_REDIRECT_URL || null,
      minSpendAmount: process.env.MIN_SPEND_AMOUNT || 20000000 // $20M default
    },
    newsletter: {
      title: 'CloudFix Newsletter Signup',
      redirectUrl: process.env.NEWSLETTER_REDIRECT_URL || null
    },
    partner_opportunity: {
      title: 'CloudFix Partner Opportunity Form',
      redirectUrl: process.env.PARTNER_OPPORTUNITY_REDIRECT_URL || null
    },
    referral_partner: {
      title: 'CloudFix Referral Partner Form',
      redirectUrl: process.env.REFERRAL_PARTNER_REDIRECT_URL || null
    }
  }
};

// Export for use in browser or Node.js environments
if (typeof module !== 'undefined' && module.exports) {
  module.exports = CONFIG;
} else {
  window.CloudFixConfig = CONFIG;
}