# API Reference

## Overview

CVEFinder.io provides a RESTful API for programmatic access to vulnerability scanning, CVE database queries, monitoring management, and more. All API endpoints require authentication via JWT token (obtained through OTP login) or API key (Pro users only).

## Authentication

All API requests must include an `Authorization` header with either:

1. **JWT Token** (for web-based authentication):
```http
Authorization: Bearer <your_jwt_token>
```

2. **API Key** (Pro users only):
```http
Authorization: Bearer cvf_a1b2c3d4e5f6789012345678901234567890abcdef123456
```

For API key management, see [API Keys](/?page=api-keys) documentation.

## Base URL

```
https://cvefinder.io/api
```

---

## Scanning Endpoints

### Scan a Website

Scans a website to detect technologies and match them against the CVE database.

**Endpoint:**
```
POST /api/scan
```

**Authentication:** Required (JWT or API Key)

**Request Headers:**
```http
Authorization: Bearer <token>
Content-Type: application/json
```

**Request Body:**
```json
{
  "target": "https://example.com"
}
```

**Parameters:**
- `target` (required): The URL to scan (with or without protocol)

**Response (Success):**
```json
{
  "success": true,
  "scan_id": 12345,
  "url": "https://example.com",
  "technologies": [
    {
      "name": "WordPress",
      "version": "6.4.2",
      "cve_count": 15
    }
  ]
}
```

**Response (Error - Rate Limit):**
```json
{
  "success": false,
  "error": "Daily scan limit reached",
  "limit": 20,
  "upgrade_action": "upgrade"
}
```

**Rate Limits:**
- Guest: 1 scan per day
- Free: 3 scans per day
- Pro: 20 scans per day

**cURL Example:**
```bash
curl -X POST https://cvefinder.io/api/scan \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"target": "https://example.com"}'
```

---

### Get Scan Results

Retrieve results for a specific scan.

**Endpoint:**
```
GET /api/get-scan?id=<scan_id>
```

**Authentication:** Required (JWT or API Key)

**Request Headers:**
```http
Authorization: Bearer <token>
```

**Query Parameters:**
- `id` (required): The scan ID to retrieve

**Response (Success):**
```json
{
  "success": true,
  "scan": {
    "id": 12345,
    "domain": "example.com",
    "url": "https://example.com",
    "status": "completed",
    "public": true,
    "tech_count": 8,
    "cve_count": 15,
    "created_at": "2026-02-09 10:30:00",
    "updated_at": "2026-02-09 10:30:15",
    "is_public": true,
    "is_owner": true
  },
  "technologies": [
    {
      "technology": "WordPress",
      "version": "6.4.2",
      "product_id": 456,
      "product_name": "WordPress",
      "product_slug": "wordpress",
      "detection_method": "generator_meta",
      "confidence": 95
    }
  ],
  "cves": [
    {
      "cve_id": "CVE-2024-1234",
      "summary": "SQL injection vulnerability...",
      "severity": "high",
      "cvss_score": 7.5,
      "epss_score": 4.2,
      "published_date": "2024-01-15"
    }
  ],
  "cve_count": 15,
  "cves_hidden": false,
  "can_view_cves": true,
  "can_view_exploits": true,
  "domain_details": {
    "ip_addresses": ["192.0.2.1"],
    "ns_records": ["ns1.example.com", "ns2.example.com"],
    "mx_records": ["mail.example.com"],
    "cname_record": "",
    "ssl_subject": "CN=example.com",
    "ssl_valid_from": "2024-01-01",
    "ssl_valid_to": "2025-01-01",
    "asn_organization": "Example Hosting Inc.",
    "ip_geolocation": "San Francisco, CA, United States",
    "tier_restricted": false
  }
}
```

**cURL Example:**
```bash
curl -X GET "https://cvefinder.io/api/get-scan?id=12345" \
  -H "Authorization: Bearer YOUR_API_KEY"
```

---

### Get Recent Scans

Retrieve the user's recent scan history.

**Endpoint:**
```
GET /api/recent-scans
```

**Authentication:** Required (JWT or API Key)

**Request Headers:**
```http
Authorization: Bearer <token>
```

**Response (Success):**
```json
{
  "success": true,
  "scans": [
    {
      "id": 12345,
      "domain": "example.com",
      "url": "https://example.com",
      "scanned_at": "2026-02-09 10:30:00",
      "tech_count": 8,
      "cve_count": 15,
      "scan_url": "/scan/12345"
    }
  ],
  "count": 10
}
```

**cURL Example:**
```bash
curl -X GET https://cvefinder.io/api/recent-scans \
  -H "Authorization: Bearer YOUR_API_KEY"
```

---

### Bulk Scan (Pro)

Scan multiple URLs simultaneously (up to 20 URLs).

**Endpoint:**
```
POST /api/bulk-scan
```

**Authentication:** Required (Pro users only)

**Request Headers:**
```http
Authorization: Bearer <token>
Content-Type: application/json
X-Recaptcha-Token: <recaptcha_token>  // Required for web requests, not for API keys
```

**Request Body:**
```json
{
  "urls": "https://example.com\nhttps://example.org\nhttps://example.net"
}
```

**Parameters:**
- `urls` (required): Newline-separated list of URLs (2-20 URLs, max 10KB)

**Response (Success):**
```json
{
  "success": true,
  "bulk_scan_id": 456,
  "total_urls": 3,
  "scans_created": 3,
  "failed": 0,
  "url": "/bulk-scan/456"
}
```

**Response (Error - Pro Required):**
```json
{
  "success": false,
  "error": "Bulk scan is only available to Pro users",
  "upgrade_url": "/pricing",
  "tier": "free"
}
```

**Response (Error - Limit Exceeded):**
```json
{
  "success": false,
  "error": "Maximum 20 URLs allowed per bulk scan",
  "provided": 25,
  "limit": 20
}
```

**cURL Example:**
```bash
curl -X POST https://cvefinder.io/api/bulk-scan \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"urls": "https://example.com\nhttps://example.org\nhttps://example.net"}'
```

---

### Get Bulk Scan Status (Pro)

Retrieve status and progress of a bulk scan.

**Endpoint:**
```
GET /api/get-bulk-scan?id=<bulk_scan_id>
```

**Authentication:** Required (Pro users only, must own the bulk scan)

**Request Headers:**
```http
Authorization: Bearer <token>
```

**Query Parameters:**
- `id` (required): The bulk scan ID

**Response (Success):**
```json
{
  "success": true,
  "bulk_scan": {
    "id": 456,
    "total_urls": 3,
    "completed_scans": 2,
    "pending_scans": 1,
    "failed_scans": 0,
    "status": "processing",
    "progress_percentage": 67,
    "created_at": "2026-02-14 10:00:00",
    "updated_at": "2026-02-14 10:01:00"
  },
  "scans": [
    {
      "id": 12345,
      "domain": "example.com",
      "url": "https://example.com",
      "status": "completed",
      "tech_count": 8,
      "cve_count": 15,
      "created_at": "2026-02-14 10:00:05",
      "updated_at": "2026-02-14 10:00:20"
    },
    {
      "id": 12346,
      "domain": "example.org",
      "url": "https://example.org",
      "status": "completed",
      "tech_count": 5,
      "cve_count": 8,
      "created_at": "2026-02-14 10:00:10",
      "updated_at": "2026-02-14 10:00:30"
    },
    {
      "id": 12347,
      "domain": "example.net",
      "url": "https://example.net",
      "status": "pending",
      "tech_count": 0,
      "cve_count": 0,
      "created_at": "2026-02-14 10:00:15",
      "updated_at": "2026-02-14 10:00:15"
    }
  ]
}
```

**Status Values:**
- `processing`: Scans are still running
- `completed`: All scans finished successfully
- `failed`: All scans failed

**cURL Example:**
```bash
curl -X GET "https://cvefinder.io/api/get-bulk-scan?id=456" \
  -H "Authorization: Bearer YOUR_API_KEY"
```

---

### Get Recent Bulk Scans (Pro)

Retrieve the user's recent bulk scan history with pagination.

**Endpoint:**
```
GET /api/account-recent-bulk-scans
```

**Authentication:** Required (Pro users only)

**Request Headers:**
```http
Authorization: Bearer <token>
```

**Query Parameters:**
- `page` (optional): Page number (default: 1)
- `per_page` (optional): Results per page, 5-10 (default: 10)

**Response (Success):**
```json
{
  "success": true,
  "bulk_scans": [
    {
      "id": 456,
      "total_urls": 3,
      "completed_scans": 3,
      "failed_scans": 0,
      "status": "completed",
      "created_at": "2026-02-14 10:00:00",
      "updated_at": "2026-02-14 10:01:30"
    }
  ],
  "pagination": {
    "current_page": 1,
    "per_page": 10,
    "total_bulk_scans": 1,
    "total_pages": 1,
    "has_next": false,
    "has_prev": false
  }
}
```

**cURL Example:**
```bash
curl -X GET "https://cvefinder.io/api/account-recent-bulk-scans?page=1&per_page=10" \
  -H "Authorization: Bearer YOUR_API_KEY"
```

---

### Export Scan Results (Pro)

Export scan results in JSON format.

**Endpoint:**
```
GET /api/export-scan-json?scan_id=<scan_id>
```

**Authentication:** Required (Pro users only)

**Query Parameters:**
- `scan_id` (required): The scan ID to export

**Response:** JSON file download with complete scan data including all detected technologies and CVEs.

**cURL Example:**
```bash
curl -X GET "https://cvefinder.io/api/export-scan-json?scan_id=12345" \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -o scan-results.json
```

---

## CVE Database Endpoints

### Get Product CVEs

Retrieve CVEs for a specific product with pagination and filtering.

**Endpoint:**
```
GET /api/product-cves
```

**Authentication:** Optional (filtering and sorting require Pro)

**Query Parameters:**
- `product_id` (required): The product ID
- `page` (optional): Page number (default: 1)
- `per_page` (optional): Results per page, 10-100 (default: 20)
- `version` (optional, Pro): Filter CVEs by specific version
- `sort` (optional, Pro): Sort order - `newest`, `oldest`, `epss` (default: newest)

**Response (Success):**
```json
{
  "success": true,
  "cves": [
    {
      "id": 789,
      "cve_id": "CVE-2024-1234",
      "description": "SQL injection vulnerability...",
      "cvss_score": 7.5,
      "severity": "high",
      "epss_score": 4.2,
      "published_date": "2024-01-15",
      "has_exploit": true
    }
  ],
  "pagination": {
    "current_page": 1,
    "total_pages": 5,
    "total_results": 98,
    "per_page": 20
  }
}
```

**Response (Error - Pro Required for Filtering):**
```json
{
  "success": false,
  "error": "Version filtering is only available for Pro users.",
  "requires_pro": true
}
```

**cURL Example:**
```bash
# Basic query (all users)
curl -X GET "https://cvefinder.io/api/product-cves?product_id=456&page=1&per_page=20" \
  -H "Authorization: Bearer YOUR_API_KEY"

# With version filtering and EPSS sorting (Pro only)
curl -X GET "https://cvefinder.io/api/product-cves?product_id=456&version=6.4.2&sort=epss" \
  -H "Authorization: Bearer YOUR_API_KEY"
```

---

### Get Product Versions (Pro)

Retrieve available versions for a product.

**Endpoint:**
```
GET /api/product-versions?product_id=<product_id>
```

**Authentication:** Required (Pro users only)

**Query Parameters:**
- `product_id` (required): The product ID

**Response (Success):**
```json
{
  "success": true,
  "versions": [
    "6.4.2",
    "6.4.1",
    "6.4.0",
    "6.3.2"
  ]
}
```

**cURL Example:**
```bash
curl -X GET "https://cvefinder.io/api/product-versions?product_id=456" \
  -H "Authorization: Bearer YOUR_API_KEY"
```

---

### Get Exploit Information (Pro)

Retrieve exploit database information and PoC code for a CVE.

**Endpoint:**
```
GET /api/get-exploits?cve_id=<cve_id>
```

**Authentication:** Required (Pro users only)

**Query Parameters:**
- `cve_id` (required): The CVE internal ID

**Response (Success):**
```json
{
  "success": true,
  "exploits": [
    {
      "id": 123,
      "title": "WordPress 6.4.2 SQL Injection PoC",
      "description": "Proof of concept code...",
      "url": "https://github.com/example/exploit",
      "verified": true,
      "published_date": "2024-01-20"
    }
  ]
}
```

**Response (Error - Not Pro):**
```json
{
  "success": false,
  "error": "Exploit database access is only available for Pro users.",
  "requires_pro": true
}
```

**cURL Example:**
```bash
curl -X GET "https://cvefinder.io/api/get-exploits?cve_id=789" \
  -H "Authorization: Bearer YOUR_API_KEY"
```

---

## Search Endpoints

### Search CVE Database

Search for products, vendors, or CVEs.

**Endpoint:**
```
GET /api/search?q=<query>
```

**Authentication:** Optional

**Query Parameters:**
- `q` (required): Search query (min 2 characters)

**Response (Success):**
```json
{
  "success": true,
  "query": "wordpress",
  "filters_applied": {
    "text_search": "wordpress"
  },
  "results": {
    "cves": [
      {
        "cve_id": "CVE-2024-1234",
        "summary": "SQL injection vulnerability...",
        "severity": "high",
        "cvss_score": 7.5,
        "epss_score": 4.2,
        "published_date": "2024-01-15",
        "last_modified": "2024-01-20",
        "url": "/cve/CVE-2024-1234"
      }
    ],
    "products": [
      {
        "id": 456,
        "name": "WordPress",
        "vendor_name": "WordPress.org",
        "vendor_id": 123,
        "url": "/product/456/wordpress",
        "vendor_url": "/vendor/123/wordpress-org"
      }
    ],
    "vendors": [
      {
        "id": 123,
        "name": "WordPress.org",
        "url": "/vendor/123/wordpress-org"
      }
    ]
  },
  "counts": {
    "cves": 10,
    "products": 5,
    "vendors": 2
  },
  "pagination": {
    "per_page": 10,
    "cves": {
      "page": 1,
      "total": 150,
      "total_pages": 15
    },
    "products": {
      "page": 1,
      "total": 25,
      "total_pages": 3
    },
    "vendors": {
      "page": 1,
      "total": 8,
      "total_pages": 1
    }
  },
  "user_tier": "pro"
}
```

**cURL Example:**
```bash
curl -X GET "https://cvefinder.io/api/search?q=wordpress" \
  -H "Authorization: Bearer YOUR_API_KEY"
```

---

### Export Search Results (Pro)

Export search results in JSON format.

**Endpoint:**
```
GET /api/export-search?q=<query>
```

**Authentication:** Required (Pro users only)

**Query Parameters:**
- `q` (required): Search query

**Response:** JSON file download with complete search results.

**cURL Example:**
```bash
curl -X GET "https://cvefinder.io/api/export-search?q=apache" \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -o search-results.json
```

---

## Monitoring Endpoints

### Enable Monitoring (Pro)

Enable email monitoring for a URL.

**Endpoint:**
```
POST /api/enable-monitoring
```

**Authentication:** Required (Pro users only)

**Request Headers:**
```http
Authorization: Bearer <token>
Content-Type: application/json
```

**Request Body:**
```json
{
  "scan_id": 12345
}
```

**Response (Success):**
```json
{
  "success": true,
  "message": "Monitoring enabled for https://example.com"
}
```

**Response (Error - Limit Reached):**
```json
{
  "success": false,
  "error": "Maximum of 5 monitored URLs allowed for Pro users"
}
```

**cURL Example:**
```bash
curl -X POST https://cvefinder.io/api/enable-monitoring \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"scan_id": 12345}'
```

---

### Disable Monitoring (Pro)

Disable email monitoring for a URL.

**Endpoint:**
```
POST /api/disable-monitoring
```

**Authentication:** Required (Pro users only)

**Request Body:**
```json
{
  "scan_id": 12345
}
```

**Response (Success):**
```json
{
  "success": true,
  "message": "Monitoring disabled for https://example.com"
}
```

**cURL Example:**
```bash
curl -X POST https://cvefinder.io/api/disable-monitoring \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"scan_id": 12345}'
```

---

### Check Monitoring Status (Pro)

Check if monitoring is enabled for a URL.

**Endpoint:**
```
GET /api/check-monitoring?scan_id=<scan_id>
```

**Authentication:** Required (Pro users only)

**Query Parameters:**
- `scan_id` (required): The scan ID

**Response (Success):**
```json
{
  "success": true,
  "monitoring_enabled": true
}
```

**cURL Example:**
```bash
curl -X GET "https://cvefinder.io/api/check-monitoring?scan_id=12345" \
  -H "Authorization: Bearer YOUR_API_KEY"
```

---

### Toggle Monitoring (Pro)

Toggle monitoring on/off for a URL.

**Endpoint:**
```
POST /api/toggle-monitoring
```

**Authentication:** Required (Pro users only)

**Request Body:**
```json
{
  "scan_id": 12345
}
```

**Response (Success):**
```json
{
  "success": true,
  "monitoring_enabled": true,
  "message": "Monitoring enabled for https://example.com"
}
```

**cURL Example:**
```bash
curl -X POST https://cvefinder.io/api/toggle-monitoring \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"scan_id": 12345}'
```

---

## CVE Alert Endpoints

### Create CVE Alert (Pro)

Create a product/vendor CVE alert.

**Endpoint:**
```
POST /api/create-cve-alert
```

**Authentication:** Required (Pro users only)

**Request Body:**
```json
{
  "product_id": 456,
  "alert_type": "product"
}
```

**Parameters:**
- `product_id` (required): The product ID to monitor
- `alert_type` (required): `product` or `vendor`

**Response (Success):**
```json
{
  "success": true,
  "alert_id": 789,
  "message": "CVE alert created for WordPress"
}
```

**Response (Error - Limit Reached):**
```json
{
  "success": false,
  "error": "Maximum of 10 CVE alerts allowed for Pro users"
}
```

**cURL Example:**
```bash
curl -X POST https://cvefinder.io/api/create-cve-alert \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"product_id": 456, "alert_type": "product"}'
```

---

### Check CVE Alert Status (Pro)

Check if a CVE alert exists for a product/vendor.

**Endpoint:**
```
GET /api/check-cve-alert?product_id=<product_id>&alert_type=<type>
```

**Authentication:** Required (Pro users only)

**Query Parameters:**
- `product_id` (required): The product ID
- `alert_type` (required): `product` or `vendor`

**Response (Success):**
```json
{
  "success": true,
  "alert_exists": true,
  "alert_id": 789
}
```

**cURL Example:**
```bash
curl -X GET "https://cvefinder.io/api/check-cve-alert?product_id=456&alert_type=product" \
  -H "Authorization: Bearer YOUR_API_KEY"
```

---

## Vendor/Product Endpoints

### Get Vendor Products

Retrieve all products for a specific vendor.

**Endpoint:**
```
GET /api/vendor-products?vendor=<vendor_name>
```

**Authentication:** Optional

**Query Parameters:**
- `vendor` (required): The vendor name

**Response (Success):**
```json
{
  "success": true,
  "products": [
    {
      "id": 456,
      "name": "WordPress",
      "cve_count": 342
    },
    {
      "id": 457,
      "name": "WooCommerce",
      "cve_count": 89
    }
  ]
}
```

**cURL Example:**
```bash
curl -X GET "https://cvefinder.io/api/vendor-products?vendor=WordPress.org" \
  -H "Authorization: Bearer YOUR_API_KEY"
```

---

## Account Endpoints

### Get Account Data

Retrieve current user account information.

**Endpoint:**
```
GET /api/account-data
```

**Authentication:** Required (JWT or API Key)

**Response (Success):**
```json
{
  "success": true,
  "user": {
    "id": 123,
    "email": "user@example.com",
    "plan_tier": "pro",
    "plan_status": "active",
    "created_at": "2025-12-01 10:00:00",
    "subscription_ends_at": "2026-03-09"
  },
  "limit_info": {
    "used": 3,
    "limit": 20,
    "remaining": 17,
    "tier": "pro"
  },
  "api_keys_count": 2,
  "scheduled_scans_count": 0,
  "monitored_scans": [
    {
      "id": 456,
      "url": "https://example.com",
      "domain": "example.com",
      "frequency": "daily",
      "is_active": true,
      "alert_on_new_cves": true,
      "alert_on_severity": "high",
      "last_checked_at": "2026-02-09 08:00:00",
      "next_check_at": "2026-02-10 08:00:00",
      "last_cve_count": 15,
      "last_critical_count": 2,
      "last_high_count": 5,
      "last_alert_sent_at": "2026-02-08 09:15:00",
      "total_alerts_sent": 3,
      "created_at": "2026-01-15 10:00:00"
    }
  ],
  "cve_alerts": [
    {
      "id": 789,
      "alert_type": "product",
      "target_id": 456,
      "target_name": "WordPress",
      "version": null,
      "last_cve_count": 342,
      "last_checked_at": "2026-02-09 08:00:00",
      "last_alert_sent_at": "2026-02-08 10:30:00",
      "created_at": "2026-01-20 14:00:00"
    }
  ]
}
```

**cURL Example:**
```bash
curl -X GET https://cvefinder.io/api/account-data \
  -H "Authorization: Bearer YOUR_API_KEY"
```

---

## Platform Stats

### Get Platform Statistics

Retrieve platform-wide statistics (no authentication required).

**Endpoint:**
```
GET /api/stats
```

**Authentication:** None

**Response (Success):**
```json
{
  "success": true,
  "stats": {
    "total_cves": 245789,
    "total_products": 52341,
    "total_scans": 1234567,
    "last_updated": "2026-02-09 08:00:00"
  }
}
```

**cURL Example:**
```bash
curl -X GET https://cvefinder.io/api/stats
```

---

## Error Handling

All API endpoints use standard HTTP status codes and return consistent error responses:

### HTTP Status Codes

| Code | Meaning |
|------|---------|
| 200 | Success |
| 400 | Bad Request - Invalid parameters |
| 401 | Unauthorized - Missing or invalid authentication |
| 403 | Forbidden - Pro feature or insufficient permissions |
| 404 | Not Found - Resource doesn't exist |
| 429 | Too Many Requests - Rate limit exceeded |
| 500 | Internal Server Error |

### Error Response Format

```json
{
  "success": false,
  "error": "Error message describing what went wrong",
  "requires_pro": true  // Optional: indicates Pro tier required
}
```

---

## Rate Limits

### Scan Limits (per day)
- Guest: 1 scan
- Free: 3 scans
- Pro: 20 scans (including bulk scan mode for up to 20 URLs at once)

### API Request Limits
- General API calls: No hard limit (fair use policy)
- Scan endpoint: Subject to daily scan quota
- Bulk scan endpoint: Pro only, max 20 URLs per request
- Pro features: Require active Pro subscription

### Best Practices
- Cache responses when possible
- Use pagination for large result sets
- Handle rate limit errors gracefully
- Implement exponential backoff for retries

---

## Support

For API issues or questions:
- **Email**: support@cvefinder.io
- **Documentation**: docs.cvefinder.io
- **API Keys Guide**: [API Keys](/?page=api-keys)
- **FAQ**: [FAQ](/?page=faq)
