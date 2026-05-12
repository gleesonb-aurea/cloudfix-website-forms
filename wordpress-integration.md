# WordPress Integration Guide

## Best Methods to Add Forms to WordPress

### Method 1: Custom HTML Blocks (Recommended - Easiest)
This is the simplest method for most WordPress users.

1. **Edit the Page/Post**:
   - Go to WordPress Admin → Pages/Posts
   - Edit the page where you want the form
   - Add a new block

2. **Add Custom HTML Block**:
   - Click the "+" to add a block
   - Search for "Custom HTML" or "HTML"
   - Select the "Custom HTML" block

3. **Insert Form Code**:
   - Copy the entire contents of the form HTML file
   - Paste it into the Custom HTML block
   - Click "Update" or "Publish"

**Pros**: No coding, immediate deployment, easy to modify
**Cons**: Code is tied to specific pages

### Method 2: Theme's functions.php (Recommended - Reusable)
This method creates reusable shortcodes for your forms.

1. **Access functions.php**:
   - WordPress Admin → Appearance → Theme Editor
   - Select `functions.php`
   - Or via FTP/cPanel File Manager

2. **Add Shortcode Functions**:
```php
// CloudFix Contact Form
function cloudfix_contact_form_shortcode() {
    ob_start();
    ?>
    <!-- Paste the entire contact-form.html content here -->
    <?php
    return ob_get_clean();
}
add_shortcode('cloudfix_contact', 'cloudfix_contact_form_shortcode');

// CloudFix RightSpend Form
function cloudfix_rightspend_form_shortcode() {
    ob_start();
    ?>
    <!-- Paste the entire rightspend-form.html content here -->
    <?php
    return ob_get_clean();
}
add_shortcode('cloudfix_rightspend', 'cloudfix_rightspend_form_shortcode');

// CloudFix Newsletter Form
function cloudfix_newsletter_form_shortcode() {
    ob_start();
    ?>
    <!-- Paste the entire newsletter-form.html content here -->
    <?php
    return ob_get_clean();
}
add_shortcode('cloudfix_newsletter', 'cloudfix_newsletter_form_shortcode');

// CloudFix Partner Opportunity Form
function cloudfix_partner_opportunity_form_shortcode() {
    ob_start();
    ?>
    <!-- Paste the entire partner-opportunity-form.html content here -->
    <?php
    return ob_get_clean();
}
add_shortcode('cloudfix_partner_opportunity', 'cloudfix_partner_opportunity_form_shortcode');

// CloudFix Referral Partner Form
function cloudfix_referral_partner_form_shortcode() {
    ob_start();
    ?>
    <!-- Paste the entire referral-partner-form.html content here -->
    <?php
    return ob_get_clean();
}
add_shortcode('cloudfix_referral_partner', 'cloudfix_referral_partner_form_shortcode');

// CloudFix Lead Magnet Capture Form
function cloudfix_lead_magnet_capture_form_shortcode() {
    ob_start();
    ?>
    <!-- Paste the entire lead-magnet-capture-form.html content here -->
    <?php
    return ob_get_clean();
}
add_shortcode('cloudfix_lead_magnet_capture', 'cloudfix_lead_magnet_capture_form_shortcode');
```

3. **Use Shortcodes**:
   - In any page/post: `[cloudfix_contact]`
   - In any page/post: `[cloudfix_rightspend]`
   - In any page/post: `[cloudfix_newsletter]`
   - In any page/post: `[cloudfix_partner_opportunity]`
   - In any page/post: `[cloudfix_referral_partner]`
   - In any page/post: `[cloudfix_lead_magnet_capture]`

**Pros**: Reusable, theme-integrated, clean shortcodes
**Cons**: Requires functions.php editing, lost if theme changes

### Method 3: Child Theme Files (Best for Developers)
Create dedicated form template files in your child theme.

1. **Create Form Files**:
   - `/wp-content/themes/your-child-theme/forms/contact-form.php`
   - Copy the HTML content into PHP files

2. **Include in Functions.php**:
```php
function cloudfix_contact_form_shortcode() {
    ob_start();
    include(get_stylesheet_directory() . '/forms/contact-form.php');
    return ob_get_clean();
}
add_shortcode('cloudfix_contact', 'cloudfix_contact_form_shortcode');
```

**Pros**: Clean file organization, version control friendly, theme-safe
**Cons**: Requires child theme setup

### Method 4: Code Snippets Plugin (Recommended for Non-Developers)
Use the "Code Snippets" plugin to manage form code without editing theme files.

1. **Install Plugin**:
   - WordPress Admin → Plugins → Add New
   - Search for "Code Snippets" by Code Snippets Pro
   - Install and Activate

2. **Add Snippets**:
   - Go to Snippets → Add New
   - Title: "CloudFix Contact Form"
   - Code:
```php
function cloudfix_contact_form_shortcode() {
    ob_start();
    ?>
    <!-- Paste the entire contact-form.html content here -->
    <?php
    return ob_get_clean();
}
add_shortcode('cloudfix_contact', 'cloudfix_contact_form_shortcode');
```
   - Save Changes and Activate

**Pros**: Plugin-managed, survives theme changes, easy to disable
**Cons**: Requires plugin dependency

## Form Placement Guide

### Contact Form
- **Pages**: `/contact-us/`, `/contact/`
- **Shortcode**: `[cloudfix_contact]`
- **Purpose**: Main contact/lead capture

### RightSpend Form
- **Pages**: `/rightspend/`
- **Shortcode**: `[cloudfix_rightspend]`
- **Purpose**: Enterprise leads ($20M+ spend)

### Newsletter Form
- **Pages**: Homepage, footer, sidebar
- **Shortcode**: `[cloudfix_newsletter]`
- **Purpose**: Email list building

### Partner Opportunity Form
- **Pages**: `/partner-opportunity-submission/`
- **Shortcode**: `[cloudfix_partner_opportunity]`
- **Purpose**: Partner referral submissions

### Referral Partner Form
- **Pages**: `/partnerships-become-a-referral-partner/`
- **Shortcode**: `[cloudfix_referral_partner]`
- **Purpose**: Partner program applications

### Lead Magnet Capture Form
- **Pages**: Any landing page offering the checklist
- **Shortcode**: `[cloudfix_lead_magnet_capture]`
- **Purpose**: Checklist download capture

## Replacing Existing HubSpot Forms

### Step-by-Step Replacement Process:

1. **Identify Current HubSpot Forms**:
   - Look for `hbspt.forms.create()` JavaScript calls
   - Search for HubSpot form IDs in your pages
   - Check for embedded HubSpot iframes

2. **Replace HubSpot Code**:
   - Remove HubSpot JavaScript and div containers
   - Replace with appropriate shortcode or HTML block
   - Test form submission

3. **Update Page Content**:
   - `/contact-us/` → Replace with `[cloudfix_contact]`
   - `/rightspend/` → Replace with `[cloudfix_rightspend]`
   - Homepage newsletter → Replace with `[cloudfix_newsletter]`
   - Partner pages → Replace with appropriate partner form
   - Checklist landing pages → Replace with `[cloudfix_lead_magnet_capture]`

### Example Replacement:

**Before (HubSpot)**:
```html
<div id="hubspot-form-container"></div>
<script>
hbspt.forms.create({
    portalId: "20049174",
    formId: "0e26c8d0-d2b2-490f-ac89-5fb944bef96b",
    target: "#hubspot-form-container"
});
</script>
```

**After (Custom Form)**:
```
[cloudfix_contact]
```

## Testing Checklist

- [ ] Forms display correctly on desktop and mobile
- [ ] All form fields work and validate properly
- [ ] Form submissions reach the n8n webhook
- [ ] reCAPTCHA verification functions
- [ ] Success/error messages display correctly
- [ ] Email notifications are sent to bill@billgleeson.com
- [ ] Forms match CloudFix branding
- [ ] No JavaScript console errors
- [ ] Page load speed is not significantly impacted

## Troubleshooting

### Common Issues:

1. **Form Not Displaying**:
   - Check for PHP syntax errors
   - Verify shortcode spelling
   - Clear caching plugins

2. **Form Submissions Not Working**:
   - Check webhook URL is correct
   - Verify n8n workflow is active
   - Test reCAPTCHA keys

3. **Styling Issues**:
   - Check for CSS conflicts with theme
   - Inspect element to debug styles
   - Add `!important` declarations if needed

4. **Mobile Display Issues**:
   - Test responsive design
   - Check viewport meta tag
   - Adjust CSS media queries

### Performance Optimization:

- **Lazy Load reCAPTCHA**: Load script only when needed
- **Inline Critical CSS**: Move essential CSS inline
- **Minimize HTTP Requests**: Combine resources where possible
- **Cache Static Resources**: Use CDN for external libraries

## Maintenance

- **Regular Testing**: Test forms monthly to ensure functionality
- **Update Monitoring**: Check for WordPress/plugin conflicts after updates
- **Performance Monitoring**: Monitor page load times
- **Security Updates**: Keep reCAPTCHA keys secure and rotate as needed
