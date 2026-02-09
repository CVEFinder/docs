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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> - CVEFinder.io Docs</title>
    <meta name="description" content="Official CVEFinder.io API documentation. Learn how to integrate vulnerability scanning into your applications with our comprehensive API reference and guides.">
    <meta name="keywords" content="CVEFinder API, vulnerability scanner API, CVE API, security scanning API, website security">
    <meta name="author" content="CVEFinder.io">
    <meta name="robots" content="index, follow">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://docs.cvefinder.io/">
    <meta property="og:title" content="<?= htmlspecialchars($title) ?> - CVEFinder.io Documentation">
    <meta property="og:description" content="Official CVEFinder.io API documentation for developers">
    <meta property="og:image" content="https://cvefinder.io/og-image.png">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://docs.cvefinder.io/">
    <meta property="twitter:title" content="<?= htmlspecialchars($title) ?> - CVEFinder.io Documentation">
    <meta property="twitter:description" content="Official CVEFinder.io API documentation for developers">
    <meta property="twitter:image" content="https://cvefinder.io/og-image.png">

    <!-- Canonical URL -->
    <link rel="canonical" href="https://docs.cvefinder.io/?page=<?= htmlspecialchars($page) ?>">

    <!-- Sitemap -->
    <link rel="sitemap" type="application/xml" href="https://docs.cvefinder.io/sitemap.xml">

    <link rel="icon" type="image/svg+xml" href="https://cvefinder.io/assets/img/favicon.svg">

    <!-- Structured Data for SEO -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "TechArticle",
      "headline": "<?= htmlspecialchars($title) ?>",
      "description": "Official CVEFinder.io API documentation for developers",
      "author": {
        "@type": "Organization",
        "name": "CVEFinder.io"
      },
      "publisher": {
        "@type": "Organization",
        "name": "CVEFinder.io",
        "logo": {
          "@type": "ImageObject",
          "url": "https://cvefinder.io/assets/img/cvefinder_logo.png"
        }
      },
      "datePublished": "2026-01-31",
      "dateModified": "2026-01-31"
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
