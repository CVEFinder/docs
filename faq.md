# Frequently Asked Questions

## General Questions

### What is CVEFinder.io?

CVEFinder.io is a vulnerability scanner that detects technologies, frameworks, and libraries used on websites and matches them against the CVE (Common Vulnerabilities and Exposures) database to identify potential security risks.

### How does CVEFinder.io work?

CVEFinder.io scans websites by analyzing HTTP headers, HTML content, JavaScript files, and other resources to detect technologies and their versions. It then cross-references this information with the CVE database to find known vulnerabilities.

### Is CVEFinder.io free to use?

Yes! We offer three tiers:
- **Guest**: 1 scan per day (no account required)
- **Free**: 3 scans per day with basic CVE information
- **Pro**: 10 scans per day with advanced features ($9/month)

### What's the difference between Free and Pro plans?

Pro users get:
- 10 scans per day (vs 3 for Free)
- Full CVE database access
- Exploit database & PoC code
- API keys for integration
- JSON exports
- Version-based CVE filtering
- Email monitoring (5 URLs)
- Product/vendor alerts (10 max)
- Manual rescan/refresh
- CVE sorting by EPSS score

## Scanning

### How many scans do I get?

- **Guest**: 1 scan per day
- **Free**: 3 scans per day
- **Pro**: 10 scans per day

Scans reset daily at midnight UTC.

### Can I scan the same website multiple times?

Yes, but it will count toward your daily scan limit unless you're a Pro user. Pro users can manually rescan/refresh existing scans without using a scan credit.

### What technologies can CVEFinder.io detect?

CVEFinder.io can detect hundreds of technologies including:
- Web servers (Apache, Nginx, IIS)
- Programming languages (PHP, Python, Ruby, Node.js)
- CMS platforms (WordPress, Drupal, Joomla)
- JavaScript frameworks (React, Vue, Angular)
- Databases (MySQL, PostgreSQL, MongoDB)
- CDNs and web services
- And many more...

### Why are some version numbers not detected?

Not all websites expose version information. Some applications intentionally hide version numbers for security reasons, or use obfuscation techniques. CVEFinder.io does its best to detect versions, but cannot guarantee 100% accuracy.

### How accurate is the vulnerability detection?

CVEFinder.io uses the official CVE database and matches detected technologies/versions against known vulnerabilities. However:
- Version detection may not always be accurate
- Some vulnerabilities may not apply to your specific configuration
- Vulnerabilities require manual review to confirm exploitability
- Always verify findings before taking action

## CVE Information

### What is a CVE?

CVE (Common Vulnerabilities and Exposures) is a publicly disclosed cybersecurity vulnerability. Each CVE has a unique ID (e.g., CVE-2024-1234) and contains information about the vulnerability, affected software, and severity.

### What is CVSS score?

CVSS (Common Vulnerability Scoring System) is a standardized method for rating the severity of vulnerabilities on a scale of 0-10:
- **0.0**: None
- **0.1-3.9**: Low
- **4.0-6.9**: Medium
- **7.0-8.9**: High
- **9.0-10.0**: Critical

### What is EPSS score?

EPSS (Exploit Prediction Scoring System) estimates the probability that a vulnerability will be exploited in the wild within the next 30 days. It's expressed as a percentage (0-100%).

### What does "Filtered by version" mean?

Pro users can filter CVEs to show only vulnerabilities that affect a specific version of a product. This helps eliminate false positives and focus on relevant security issues.

### Where does CVE data come from?

CVEFinder.io sources CVE data from:
- National Vulnerability Database (NVD)
- Official CVE feeds
- EPSS scoring data
- Exploit databases

## Pro Features

### How do API keys work?

Pro users can generate up to 5 API keys for programmatic access to CVEFinder.io. API keys allow you to:
- Automate vulnerability scanning
- Integrate CVEFinder into your CI/CD pipeline
- Query the CVE database
- Manage monitoring and alerts
- Export scan results

For detailed API documentation, see [API Keys](/?page=api-keys).

### What is email monitoring?

Email monitoring sends you alerts when new CVEs are published for technologies detected on your monitored URLs. Pro users can monitor up to 5 URLs.

### What are product/vendor alerts?

Product/vendor alerts notify you when new CVEs are published for specific products or vendors (e.g., "WordPress", "Apache HTTP Server"). Pro users can set up to 10 alerts.

### Can I export scan results?

Yes! Pro users can export scan results in JSON format for further analysis, reporting, or integration with other tools.

### How does version-based CVE filtering work?

When viewing CVEs for a product, Pro users can select a specific version to see only vulnerabilities that affect that version. This uses the CVE's version criteria (exact match, less than, greater than) to filter results.

## Account & Billing

### Do I need an account to use CVEFinder.io?

No account is required for guest scans (1 per day). To get more scans or access advanced features, you'll need to create a free account.

### How do I create an account?

CVEFinder.io uses passwordless authentication. Simply enter your email address, and we'll send you a one-time password (OTP) to log in. No password to remember!

### How do I upgrade to Pro?

1. Log in to your account
2. Go to [Pricing](/pricing)
3. Click "Upgrade to Pro"
4. Complete payment via Stripe

### Can I cancel my Pro subscription?

Yes, you can cancel anytime from your account settings. Your Pro features will remain active until the end of your current billing period.

### What payment methods do you accept?

We accept all major credit cards via Stripe (Visa, Mastercard, American Express, Discover, etc.).

### Do you offer refunds?

We offer a 7-day money-back guarantee. If you're not satisfied with Pro features, contact support@cvefinder.io within 7 days of purchase for a full refund.

## Security & Privacy

### Is my scan data private?

Yes. Scan results are private and only visible to your account. We do not share scan results with third parties.

### Do you store my website's source code?

No. CVEFinder.io only stores metadata about detected technologies and versions. We do not store or retain the actual source code or content of scanned websites.

### Is CVEFinder.io safe to use on production websites?

Yes. CVEFinder.io performs passive scanning by analyzing HTTP responses and publicly accessible resources. It does not perform active exploitation or intrusive testing.

### How do you secure API keys?

API keys are cryptographically hashed before storage using industry-standard algorithms. The plaintext key is never stored in our database.

## Troubleshooting

### Why can't I scan a website?

Common reasons:
- The URL is invalid or unreachable
- The website blocks automated scanners
- Firewall or security settings prevent access
- The website requires authentication
- You've reached your daily scan limit

### Why are no technologies detected?

This can happen if:
- The website uses obfuscation or hiding techniques
- The website is primarily client-side rendered (SPA)
- Technologies don't expose identifying headers or patterns
- The scan timed out before detection completed

### Why do I see "Rate limit exceeded"?

You've reached your daily scan limit. Wait until the next day (midnight UTC) for your scans to reset, or upgrade to Pro for more scans.

### My scan is stuck on "Scanning..."

If a scan doesn't complete within 30 seconds, it may have timed out. Try refreshing the page or rescanning the URL.

## Contact & Support

### How do I report a bug?

Email us at support@cvefinder.io with:
- Description of the issue
- Steps to reproduce
- Browser and device information
- Screenshots (if applicable)

### How do I request a feature?

We'd love to hear your ideas! Email feature requests to support@cvefinder.io.

### Where can I get help?

- **Email**: support@cvefinder.io
- **Documentation**: docs.cvefinder.io

## Legal

### What is your privacy policy?

View our privacy policy at [cvefinder.io/privacy-policy](/privacy-policy).

### What are your terms of service?

View our terms and conditions at [cvefinder.io/terms-and-conditions](/terms-and-conditions).

### Can I use CVEFinder.io for commercial purposes?

Yes! Both Free and Pro plans can be used for commercial purposes, subject to our terms of service.
