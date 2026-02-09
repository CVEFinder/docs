# API Keys Documentation

## Overview

CVEFinder.io provides API keys for **Pro tier users** to programmatically access the platform's features. API keys offer a secure, token-based authentication method for automated vulnerability scanning, CVE database queries, monitoring management, and exporting scan results.

## Key Features

- **Pro Tier Exclusive**: API keys are only available for users with active Pro subscriptions
- **Full Platform Access**: Access all Pro features programmatically including:
  - Website vulnerability scanning (10 scans/day)
  - CVE database queries with version-based filtering
  - Exploit database & PoC code access
  - Email monitoring management (up to 5 URLs)
  - Product/vendor CVE alerts (up to 10)
  - JSON export of scan results and CVE data
  - Manual rescan/refresh of existing scans
- **Secure Storage**: Keys are cryptographically hashed before storage
- **Rate Limiting**: Built-in tracking of API usage
- **Key Management**: Create, revoke, and rotate keys as needed
- **Usage Analytics**: Track request counts and last usage timestamps
- **Multiple Keys**: Up to 5 API keys per user account

## API Key Format

API keys follow this format:
```
cvf_a1b2c3d4e5f6789012345678901234567890abcdef123456
```

- **Prefix**: `cvf_`
- **Length**: 52 characters total (4 char prefix + 48 hex characters)
- **Display**: Keys are shown in full only once during creation. Afterward, they appear masked: `cvf_••••• last6`

## Getting Started

### Prerequisites

1. **Pro Tier Account**: Upgrade your account to Pro tier at [cvefinder.io/pricing](https://cvefinder.io/pricing)
2. **Active Subscription**: Your Pro subscription must be active

### Authentication Methods

CVEFinder.io supports two authentication methods:

1. **JWT Token**: For browser-based authentication (login via OTP)
2. **API Key**: For programmatic access (server-to-server)

## Creating an API Key

### Endpoint

```
POST /api/create-api-key
```

### Request Headers

```http
Authorization: Bearer <your_jwt_token>
Content-Type: application/json
```

### Request Body

```json
{
  "name": "CVEFinder CLI Tool"
}
```

**Parameters:**
- `name` (required): A friendly name for the key (max 100 characters)
  - Examples: "CVEFinder CLI Tool", "Scanner Bot", "Security Dashboard", "CI/CD Pipeline"

### Response (Success)

```json
{
  "success": true,
  "api_key": "cvf_a1b2c3d4e5f6789012345678901234567890abcdef123456",
  "api_key_id": 42,
  "name": "CVEFinder CLI Tool",
  "created_at": "2026-01-31 10:30:00"
}
```

**IMPORTANT**: Save the `api_key` value immediately. This is the only time the full key will be displayed. If you lose it, you'll need to rotate or create a new key.

### Response (Error - Not Pro User)

```json
{
  "success": false,
  "error": "API keys are only available for Pro users.",
  "upgrade_required": true,
  "upgrade_url": "/pricing"
}
```

### Response (Error - Key Limit Reached)

```json
{
  "success": false,
  "error": "Maximum of 5 API keys allowed per user"
}
```

### cURL Example

```bash
curl -X POST https://cvefinder.io/api/create-api-key \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"name": "CVEFinder CLI Tool"}'
```

### JavaScript Example

```javascript
const response = await fetch('https://cvefinder.io/api/create-api-key', {
  method: 'POST',
  headers: {
    'Authorization': `Bearer ${jwtToken}`,
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    name: 'CVEFinder CLI Tool'
  })
});

const data = await response.json();
if (data.success) {
  console.log('API Key:', data.api_key);
  // Store this key securely - it won't be shown again!
}
```

## Using Your API Key

### Authentication Header

Include your API key in the `Authorization` header of all API requests:

```http
Authorization: Bearer cvf_a1b2c3d4e5f6789012345678901234567890abcdef123456
```

### Example Requests

For complete API endpoint documentation, see the [API Reference](/?page=api-reference) guide.

**Scan a website:**
```bash
curl -X POST https://cvefinder.io/api/scan \
  -H "Authorization: Bearer cvf_a1b2c3d4e5f6789012345678901234567890abcdef123456" \
  -H "Content-Type: application/json" \
  -d '{"target": "https://example.com"}'
```

**Get CVEs for a product with version filtering (Pro feature):**
```bash
curl -X GET "https://cvefinder.io/api/product-cves?product_id=123&version=8.1.5&page=1&per_page=20&sort=epss" \
  -H "Authorization: Bearer cvf_a1b2c3d4e5f6789012345678901234567890abcdef123456"
```

**Get exploit information for a CVE (Pro feature):**
```bash
curl -X GET "https://cvefinder.io/api/get-exploits?cve_id=456" \
  -H "Authorization: Bearer cvf_a1b2c3d4e5f6789012345678901234567890abcdef123456"
```

See the [API Reference](/?page=api-reference) for all available endpoints, request/response formats, and detailed documentation.

### How It Works

1. The API extracts the token from the `Authorization: Bearer` header
2. If the token format matches an API key (`cvf_` prefix, 52 chars), it validates against stored API keys
3. The system securely verifies the key against stored hashes
4. On successful validation, the request proceeds with the user's permissions
5. Usage metrics are automatically updated

## Managing API Keys

### List Your API Keys

```
GET /api/list-api-keys
```

**Response:**
```json
{
  "success": true,
  "keys": [
    {
      "id": 42,
      "masked_key": "cvf_a1b2c3•••••123456",
      "name": "CVEFinder CLI Tool",
      "requests_count": 1523,
      "last_used_at": "2026-01-31 14:22:15",
      "created_at": "2026-01-15 10:30:00"
    }
  ]
}
```

### Revoke an API Key

Use this endpoint to permanently deactivate an API key.

**Endpoint:**
```
POST /api/revoke-api-key
```

**Request Headers:**
```http
Authorization: Bearer <your_jwt_token>
Content-Type: application/json
```

**Request Body:**
```json
{
  "api_key_id": 42
}
```

**Response (Success):**
```json
{
  "success": true,
  "message": "API key revoked successfully"
}
```

**cURL Example:**
```bash
curl -X POST https://cvefinder.io/api/revoke-api-key \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"api_key_id": 42}'
```

**Notes:**
- Revoked keys cannot be reactivated
- The key is permanently deactivated and will no longer authenticate
- Existing requests using the revoked key will immediately fail

### Rotate an API Key

Key rotation generates a new API key with the same name and automatically revokes the old one. This is useful for:
- Regular security maintenance
- Suspected key compromise
- Onboarding/offboarding team members

**Endpoint:**
```
POST /api/rotate-api-key
```

**Request Headers:**
```http
Authorization: Bearer <your_jwt_token>
Content-Type: application/json
```

**Request Body:**
```json
{
  "api_key_id": 42
}
```

**Response (Success):**
```json
{
  "success": true,
  "api_key": "cvf_new9876543210abcdefghijklmnopqrstuvwxyz012345",
  "api_key_id": 43,
  "name": "CVEFinder CLI Tool",
  "created_at": "2026-01-31 15:45:00"
}
```

**cURL Example:**
```bash
curl -X POST https://cvefinder.io/api/rotate-api-key \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"api_key_id": 42}'
```

**Important:**
- The new API key is shown in full (this is the only time)
- The old key is immediately revoked
- Update your applications with the new key before the old one stops working

## Security Best Practices

### Storage

- **Never commit API keys to version control** (Git, SVN, etc.)
- Store keys in environment variables or secure secret management systems
- Use `.env` files (excluded from version control) for local development
- For production, use platform-specific secret managers (AWS Secrets Manager, Azure Key Vault, etc.)

**Example `.env` file:**
```bash
CVEFINDER_API_KEY=cvf_a1b2c3d4e5f6789012345678901234567890abcdef123456
```

### Access Control

- **Use separate keys for different environments** (development, staging, production)
- **Rotate keys regularly** (every 90 days recommended)
- **Revoke immediately** if a key is compromised or exposed
- **Use descriptive names** to track which service uses which key
- **Limit key distribution** - only share with services that need access

### Monitoring

- Regularly review your API key usage in the dashboard
- Check last usage timestamps for unexpected activity
- Monitor request counts for anomalies
- Set up alerts for unusual API usage patterns

## Limitations

- **Maximum Keys**: 5 active API keys per user
- **Pro Tier Only**: API keys require an active Pro subscription
- **Scan Quota**: 10 scans per day (same limit as web interface)
- **Monitoring Limits**: Up to 5 URLs for email monitoring, up to 10 product/vendor alerts
- **JWT Required for Management**: API keys cannot create, revoke, or rotate other keys (prevents privilege escalation)
- **Account-Specific**: Each key is tied to a specific user account
- **Pro Features Only**: Some endpoints (version filtering, exploit database, JSON exports) require Pro tier

## Error Codes

| HTTP Code | Error | Description |
|-----------|-------|-------------|
| 401 | Unauthorized | No valid authentication provided |
| 403 | Forbidden | Not a Pro user or insufficient permissions |
| 400 | Bad Request | Invalid input (missing name, invalid ID, etc.) |
| 404 | Not Found | API key doesn't exist or doesn't belong to your account |
| 500 | Server Error | Internal server error |

## Usage Logging

Every API request is logged for analytics and security auditing:

- API Key usage tracking
- Endpoint access logs
- Request timestamps
- Response status codes
- Client information

## Frequently Asked Questions

### Can I use API keys for browser-based authentication?

No. API keys are designed for server-to-server communication. For browser-based apps, use JWT tokens obtained through the passwordless OTP login flow.

### What happens if my Pro subscription expires?

Your API keys will stop working immediately when your subscription becomes inactive. They will resume working if you reactivate your Pro subscription.

### Can I increase the 5-key limit?

The 5-key limit is currently fixed for all Pro users. If you need more keys, contact support@cvefinder.io to discuss enterprise options.

### What Pro features can I access via API?

With API keys, you can access all Pro tier features:
- **Scanning**: 10 website scans per day with full technology/version detection
- **CVE Filtering**: Version-based CVE filtering for precise vulnerability matching
- **Exploit Database**: Access PoC code and exploit information for CVEs
- **Monitoring**: Manage email monitoring (5 URLs) and product/vendor alerts (10 max)
- **Exports**: JSON export of scan results and CVE data
- **Manual Rescans**: Trigger manual rescan/refresh of existing scans
- **Sorting**: Sort CVEs by EPSS score, newest, or oldest date

### Do API calls count toward my daily scan quota?

Yes. API scans count toward your 10 scans per day limit (same as web interface scans).

### How are API keys stored?

API keys are cryptographically hashed before storage using industry-standard algorithms. The plaintext key is never stored in the database.

### Can I regenerate a lost API key?

No. If you lose an API key, you must either rotate the existing key (if you know its ID) or revoke it and create a new one.

### What's the difference between revoke and rotate?

- **Revoke**: Permanently deactivates a key. You'll need to create a new key separately.
- **Rotate**: Generates a new key with the same name and automatically revokes the old one in a single operation.

## Support

For issues or questions:
- **Email**: support@cvefinder.io
- **Documentation**: docs.cvefinder.io

## Changelog

- **2026-01-31**: Initial API key system release
  - Create, revoke, and rotate endpoints
  - Secure key hashing
  - Usage logging and analytics
  - Pro tier integration
