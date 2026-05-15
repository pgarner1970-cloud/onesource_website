One Source Air & Energy Ltd - Static Website Build

Upload the contents of this ZIP to your GitHub repository root.

Required live structure:
index.html
assets/css/styles.css
assets/js/site.js
assets/images/
assets/logos/

Notes:
- Accreditation/logo images are taken from the current One Source website assets and included locally.
- The enquiry form is visual only at this stage.
- Gallery filters work using JavaScript.
- Replace SVG project placeholders with real installation photos later while keeping the same filenames or editing index.html.


Google reCAPTCHA setup:
1. Go to https://www.google.com/recaptcha/admin/create
2. Create reCAPTCHA v2 Checkbox keys for the live domain.
3. Edit site-config.php:
   - $GOOGLE_RECAPTCHA_SITE_KEY
   - $GOOGLE_RECAPTCHA_SECRET_KEY
4. Enquiries are sent to luke@onesourceairandenergyltd.co.uk via send-enquiry.php.
