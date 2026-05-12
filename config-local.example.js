// Example configuration file for CloudFix Forms
// Copy this to config-local.js and update with your actual values
// NEVER commit the actual config-local.js to version control

const CONFIG = {
  // Webhook URL for form submissions
  WEBHOOK_URL: 'https://your-automation-server.com/webhook/cloudfix-forms',

  // reCAPTCHA v3 Site Key (get from https://www.google.com/recaptcha/admin/create)
  RECAPTCHA_SITE_KEY: 'your-recaptcha-site-key-here',

  // Email for notifications (used in WordPress/PHP versions)
  NOTIFICATION_EMAIL: 'your-email@yourcompany.com',

  // Environment settings
  ENVIRONMENT: 'development',

  // Feature flags
  DEBUG_MODE: true, // Set to false for production
  ENABLE_RECAPTCHA: true,

  // Form-specific settings
  FORM_SETTINGS: {
    contact: {
      title: 'CloudFix Contact Form',
      redirectUrl: null // Set to URL for post-submission redirect
    },
    rightspend: {
      title: 'CloudFix RightSpend Form',
      redirectUrl: null,
      minSpendAmount: 20000000 // $20M minimum
    },
    newsletter: {
      title: 'CloudFix Newsletter Signup',
      redirectUrl: null
    },
    partner_opportunity: {
      title: 'CloudFix Partner Opportunity Form',
      redirectUrl: null
    },
    referral_partner: {
      title: 'CloudFix Referral Partner Form',
      redirectUrl: null
    }
  }
};

// Export for use in browser or Node.js environments
if (typeof module !== 'undefined' && module.exports) {
  module.exports = CONFIG;
} else {
  window.CloudFixConfig = CONFIG;
}