<?php
/**
 * CVEFinder.io Documentation Site
 * Simple markdown-based documentation using Parsedown
 */

// Load Parsedown library (install via composer: composer require erusev/parsedown)
require_once __DIR__ . '/vendor/autoload.php';

$parsedown = new Parsedown();

// Get requested doc page (default: api-keys)
$page = $_GET['page'] ?? 'api-keys';

// Sanitize page name (prevent directory traversal)
$page = preg_replace('/[^a-z0-9\-]/', '', strtolower($page));

// Build file path
$mdFile = __DIR__ . '/' . $page . '.md';

// Check if file exists
if (!file_exists($mdFile)) {
    $mdFile = __DIR__ . '/api-keys.md'; // Default fallback
    $page = 'api-keys';
}

// Read and parse markdown
$markdown = file_get_contents($mdFile);
$html = $parsedown->text($markdown);

// Extract title from first H1
preg_match('/<h1>(.*?)<\/h1>/', $html, $matches);
$title = $matches[1] ?? 'CVEFinder.io Documentation';

// Extract first paragraph for meta description
preg_match('/<p>(.*?)<\/p>/', $html, $descMatches);
$rawDescription = $descMatches[1] ?? 'Official CVEFinder.io API documentation. Learn how to integrate vulnerability scanning into your applications.';
$description = strip_tags($rawDescription);
$description = substr($description, 0, 160); // Limit to 160 characters for SEO

// Page-specific metadata
$pageMetadata = [
    'api-reference' => [
        'title' => 'API Reference - Complete REST API Documentation',
        'description' => 'Complete CVEFinder.io REST API reference. Scan websites, query CVE database, manage monitoring, bulk scans, and more with our comprehensive API.',
        'keywords' => 'CVEFinder API, REST API, vulnerability scanner API, CVE API, bulk scan API, security API documentation'
    ],
    'api-keys' => [
        'title' => 'API Keys Guide - Authentication & Integration',
        'description' => 'Learn how to generate and manage CVEFinder.io API keys for programmatic access. Integrate vulnerability scanning into your CI/CD pipeline.',
        'keywords' => 'API keys, API authentication, CVEFinder integration, API access, programmatic scanning'
    ],
    'faq' => [
        'title' => 'FAQ - Frequently Asked Questions',
        'description' => 'Find answers to common questions about CVEFinder.io vulnerability scanning, pricing, features, bulk scans, and API integration.',
        'keywords' => 'CVEFinder FAQ, vulnerability scanner questions, scanning limits, bulk scan, API questions'
    ]
];

// Use page-specific metadata if available
$pageTitle = $pageMetadata[$page]['title'] ?? $title;
$pageDescription = $pageMetadata[$page]['description'] ?? $description;
$pageKeywords = $pageMetadata[$page]['keywords'] ?? 'CVEFinder API, vulnerability scanner API, CVE API, security scanning API';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> | CVEFinder.io Documentation</title>
    <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
    <meta name="keywords" content="<?= htmlspecialchars($pageKeywords) ?>">
    <meta name="author" content="CVEFinder.io">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">

    <!-- Additional SEO Meta Tags -->
    <meta name="language" content="English">
    <meta name="revisit-after" content="7 days">
    <meta name="distribution" content="global">
    <meta name="rating" content="general">
    <meta http-equiv="content-language" content="en-US">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="article">
    <meta property="og:url" content="https://docs.cvefinder.io/?page=<?= htmlspecialchars($page) ?>">
    <meta property="og:title" content="<?= htmlspecialchars($pageTitle) ?> | CVEFinder.io">
    <meta property="og:description" content="<?= htmlspecialchars($pageDescription) ?>">
    <meta property="og:image" content="https://cvefinder.io/og-image.png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="CVEFinder.io Documentation">
    <meta property="og:locale" content="en_US">
    <meta property="article:publisher" content="https://cvefinder.io">
    <meta property="article:modified_time" content="<?= date('c') ?>">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@cvefinder">
    <meta name="twitter:creator" content="@cvefinder">
    <meta name="twitter:url" content="https://docs.cvefinder.io/?page=<?= htmlspecialchars($page) ?>">
    <meta name="twitter:title" content="<?= htmlspecialchars($pageTitle) ?> | CVEFinder.io">
    <meta name="twitter:description" content="<?= htmlspecialchars($pageDescription) ?>">
    <meta name="twitter:image" content="https://cvefinder.io/og-image.png">
    <meta name="twitter:image:alt" content="CVEFinder.io Documentation">

    <!-- Canonical URL -->
    <link rel="canonical" href="https://docs.cvefinder.io/?page=<?= htmlspecialchars($page) ?>">

    <!-- Sitemap -->
    <link rel="sitemap" type="application/xml" href="https://docs.cvefinder.io/sitemap.xml">

    <link rel="icon" type="image/svg+xml" href="https://cvefinder.io/assets/img/favicon.svg">

    <!-- Structured Data for SEO - Documentation/TechArticle -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "TechArticle",
      "headline": "<?= htmlspecialchars($pageTitle) ?>",
      "description": "<?= htmlspecialchars($pageDescription) ?>",
      "keywords": "<?= htmlspecialchars($pageKeywords) ?>",
      "url": "https://docs.cvefinder.io/?page=<?= htmlspecialchars($page) ?>",
      "image": "https://cvefinder.io/og-image.png",
      "author": {
        "@type": "Organization",
        "name": "CVEFinder.io",
        "url": "https://cvefinder.io"
      },
      "publisher": {
        "@type": "Organization",
        "name": "CVEFinder.io",
        "url": "https://cvefinder.io",
        "logo": {
          "@type": "ImageObject",
          "url": "https://cvefinder.io/assets/img/cvefinder_logo.png",
          "width": 200,
          "height": 60
        }
      },
      "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "https://docs.cvefinder.io/?page=<?= htmlspecialchars($page) ?>"
      },
      "datePublished": "2026-01-31T00:00:00Z",
      "dateModified": "<?= date('c') ?>",
      "inLanguage": "en-US",
      "isPartOf": {
        "@type": "WebSite",
        "name": "CVEFinder.io Documentation",
        "url": "https://docs.cvefinder.io"
      }
    }
    </script>

    <!-- Breadcrumb Structured Data -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [
        {
          "@type": "ListItem",
          "position": 1,
          "name": "Home",
          "item": "https://cvefinder.io"
        },
        {
          "@type": "ListItem",
          "position": 2,
          "name": "Documentation",
          "item": "https://docs.cvefinder.io"
        },
        {
          "@type": "ListItem",
          "position": 3,
          "name": "<?= htmlspecialchars($title) ?>",
          "item": "https://docs.cvefinder.io/?page=<?= htmlspecialchars($page) ?>"
        }
      ]
    }
    </script>

    <!-- SoftwareApplication Structured Data for API -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "SoftwareApplication",
      "name": "CVEFinder.io API",
      "applicationCategory": "DeveloperApplication",
      "operatingSystem": "Any",
      "description": "RESTful API for vulnerability scanning and CVE database queries. Scan websites, detect technologies, manage bulk scans, and access comprehensive security intelligence.",
      "url": "https://cvefinder.io",
      "documentation": "https://docs.cvefinder.io",
      "offers": {
        "@type": "Offer",
        "price": "0",
        "priceCurrency": "USD",
        "description": "Free tier available with 3 scans per day. Pro tier offers 20 scans per day with bulk scanning for $9/month."
      },
      "featureList": [
        "Website vulnerability scanning",
        "Bulk scan mode (20 URLs simultaneously)",
        "CVE database access",
        "Technology detection",
        "REST API integration",
        "JSON exports",
        "Email monitoring",
        "Exploit database access"
      ],
      "author": {
        "@type": "Organization",
        "name": "CVEFinder.io"
      }
    }
    </script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #dc3545;
            --secondary-color: #6c757d;
            --bg-color: #ffffff;
            --text-color: #212529;
            --code-bg: #f8f9fa;
            --border-color: #dee2e6;
            --sidebar-width: 280px;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background: var(--bg-color);
        }

        /* Header */
        header {
            background: #fff;
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        header .container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary-color);
            text-decoration: none;
        }

        .logo span {
            color: var(--text-color);
            font-weight: normal;
        }

        nav a {
            color: var(--text-color);
            text-decoration: none;
            margin-left: 2rem;
            font-size: 0.95rem;
        }

        nav a:hover {
            color: var(--primary-color);
        }

        /* Main Layout */
        .main-container {
            display: flex;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Sidebar */
        aside {
            width: var(--sidebar-width);
            padding: 2rem 1.5rem;
            border-right: 1px solid var(--border-color);
            position: sticky;
            top: 70px;
            height: calc(100vh - 70px);
            overflow-y: auto;
        }

        aside h3 {
            font-size: 0.85rem;
            text-transform: uppercase;
            color: var(--secondary-color);
            margin-bottom: 1rem;
            letter-spacing: 0.5px;
        }

        aside ul {
            list-style: none;
        }

        aside li {
            margin-bottom: 0.5rem;
        }

        aside a {
            color: var(--text-color);
            text-decoration: none;
            display: block;
            padding: 0.5rem 0.75rem;
            border-radius: 4px;
            transition: all 0.2s;
        }

        aside a:hover,
        aside a.active {
            background: var(--code-bg);
            color: var(--primary-color);
        }

        /* Content */
        main {
            flex: 1;
            padding: 2rem 3rem;
            max-width: 900px;
        }

        /* Typography */
        h1 {
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            color: var(--text-color);
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 0.5rem;
        }

        h2 {
            font-size: 1.8rem;
            margin-top: 2.5rem;
            margin-bottom: 1rem;
            color: var(--text-color);
        }

        h3 {
            font-size: 1.4rem;
            margin-top: 2rem;
            margin-bottom: 0.75rem;
            color: var(--text-color);
        }

        h4 {
            font-size: 1.1rem;
            margin-top: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--secondary-color);
        }

        p {
            margin-bottom: 1rem;
            line-height: 1.7;
        }

        /* Code Blocks */
        code {
            background: var(--code-bg);
            padding: 0.2rem 0.4rem;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
            color: #e83e8c;
        }

        pre {
            background: var(--code-bg);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 1.25rem;
            overflow-x: auto;
            margin: 1.5rem 0;
            line-height: 1.5;
        }

        pre code {
            background: none;
            padding: 0;
            color: var(--text-color);
            font-size: 0.875rem;
        }

        /* Lists */
        ul, ol {
            margin-bottom: 1rem;
            padding-left: 2rem;
        }

        li {
            margin-bottom: 0.5rem;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 1.5rem 0;
            font-size: 0.95rem;
        }

        th {
            background: var(--code-bg);
            padding: 0.75rem;
            text-align: left;
            border: 1px solid var(--border-color);
            font-weight: 600;
        }

        td {
            padding: 0.75rem;
            border: 1px solid var(--border-color);
        }

        tr:nth-child(even) {
            background: #fafbfc;
        }

        /* Blockquotes */
        blockquote {
            border-left: 4px solid var(--primary-color);
            padding-left: 1rem;
            margin: 1.5rem 0;
            color: var(--secondary-color);
            font-style: italic;
        }

        /* Links */
        a {
            color: var(--primary-color);
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .main-container {
                flex-direction: column;
            }

            aside {
                width: 100%;
                position: static;
                height: auto;
                border-right: none;
                border-bottom: 1px solid var(--border-color);
            }

            main {
                padding: 1.5rem;
            }

            header .container {
                flex-direction: column;
                align-items: flex-start;
            }

            nav {
                margin-top: 1rem;
            }

            nav a {
                margin-left: 0;
                margin-right: 1.5rem;
            }
        }

        /* Utility Classes */
        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 3px;
            background: var(--code-bg);
            color: var(--secondary-color);
            margin-left: 0.5rem;
        }

        .badge.pro {
            background: var(--primary-color);
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <a href="https://cvefinder.io" class="logo">CVEFinder<span>.io</span></a>
            <nav>
                <a href="https://cvefinder.io">Home</a>
                <a href="https://cvefinder.io/pricing">Pricing</a>
                <a href="/">Documentation</a>
            </nav>
        </div>
    </header>

    <div class="main-container">
        <aside>
            <h3>Documentation</h3>
            <ul>
                <li><a href="?page=faq" class="<?= $page === 'faq' ? 'active' : '' ?>">FAQ</a></li>
                <li><a href="?page=api-reference" class="<?= $page === 'api-reference' ? 'active' : '' ?>">API Reference</a></li>
                <li><a href="?page=api-keys" class="<?= $page === 'api-keys' ? 'active' : '' ?>">API Keys <span class="badge pro">PRO</span></a></li>
            </ul>
        </aside>

        <main>
            <?= $html ?>
        </main>
    </div>
</body>
</html>
