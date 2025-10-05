<?php
/**
 * Sitemap Generator
 */

require_once '../config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';
require_once '../includes/file-parser.php';
require_once '../includes/layout.php';

requireAuth();

// Handle sitemap regeneration
if (isPost() && getPost('action') === 'regenerate') {
    $csrfToken = getPost(CSRF_TOKEN_NAME, '');

    if (!validateCsrfToken($csrfToken)) {
        setErrorMessage('Invalid security token');
    } else {
        if (regenerateSitemap()) {
            setSuccessMessage('Sitemap regenerated successfully!');
        } else {
            setErrorMessage('Failed to regenerate sitemap');
        }
    }
}

// Function to regenerate sitemap
function regenerateSitemap() {
    $sitemapPath = ROOT_DIR . '/sitemap.xml';

    // Start XML
    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
    $xml .= '        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"' . "\n";
    $xml .= '        xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">' . "\n\n";

    $baseUrl = rtrim(SITE_URL, '/');
    $today = date('Y-m-d');

    // Homepage
    $xml .= "  <!-- Homepage -->\n";
    $xml .= "  <url>\n";
    $xml .= "    <loc>{$baseUrl}/</loc>\n";
    $xml .= "    <lastmod>{$today}</lastmod>\n";
    $xml .= "    <changefreq>weekly</changefreq>\n";
    $xml .= "    <priority>1.0</priority>\n";
    $xml .= "  </url>\n\n";

    // Main pages
    $mainPages = [
        'index.php' => ['priority' => '0.9', 'changefreq' => 'weekly'],
        'aboutus.php' => ['priority' => '0.8', 'changefreq' => 'monthly'],
        'contact.php' => ['priority' => '0.8', 'changefreq' => 'monthly'],
    ];

    $xml .= "  <!-- Main Pages -->\n";
    foreach ($mainPages as $page => $settings) {
        if (file_exists(ROOT_DIR . '/' . $page)) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>{$baseUrl}/{$page}</loc>\n";
            $xml .= "    <lastmod>{$today}</lastmod>\n";
            $xml .= "    <changefreq>{$settings['changefreq']}</changefreq>\n";
            $xml .= "    <priority>{$settings['priority']}</priority>\n";
            $xml .= "  </url>\n\n";
        }
    }

    // Tours
    $xml .= "  <!-- Tours -->\n";
    $tours = getAllTours();
    foreach ($tours as $tour) {
        $relativePath = str_replace(ROOT_DIR, '', str_replace('\\', '/', $tour['path']));
        $url = $baseUrl . $relativePath;
        $modified = date('Y-m-d', $tour['modified']);

        $xml .= "  <url>\n";
        $xml .= "    <loc>{$url}</loc>\n";
        $xml .= "    <lastmod>{$modified}</lastmod>\n";
        $xml .= "    <changefreq>monthly</changefreq>\n";
        $xml .= "    <priority>0.8</priority>\n";
        $xml .= "  </url>\n\n";
    }

    // Blog Posts
    $xml .= "  <!-- Blog Posts -->\n";
    $posts = getAllBlogPosts();
    foreach ($posts as $post) {
        $url = $baseUrl . '/blog/' . $post['filename'];
        $modified = date('Y-m-d', $post['modified']);

        $xml .= "  <url>\n";
        $xml .= "    <loc>{$url}</loc>\n";
        $xml .= "    <lastmod>{$modified}</lastmod>\n";
        $xml .= "    <changefreq>monthly</changefreq>\n";
        $xml .= "    <priority>0.7</priority>\n";
        $xml .= "  </url>\n\n";
    }

    $xml .= "</urlset>\n";

    // Write to file
    return file_put_contents($sitemapPath, $xml) !== false;
}

// Get sitemap info
$sitemapPath = ROOT_DIR . '/sitemap.xml';
$sitemapExists = file_exists($sitemapPath);
$sitemapModified = $sitemapExists ? filemtime($sitemapPath) : null;
$sitemapSize = $sitemapExists ? filesize($sitemapPath) : 0;

// Count URLs in sitemap
$urlCount = 0;
if ($sitemapExists) {
    $content = file_get_contents($sitemapPath);
    preg_match_all('/<url>/', $content, $matches);
    $urlCount = count($matches[0]);
}

renderAdminHeader('Sitemap Manager', 'sitemap');
?>

<!-- Info Card -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex items-start justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">Sitemap Information</h2>
            <p class="text-gray-600">
                The sitemap helps search engines discover and index all pages on your website.
            </p>
        </div>
        <div class="bg-blue-100 p-4 rounded-full">
            <i class="fas fa-sitemap text-2xl text-blue-500"></i>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="text-sm text-gray-600 font-semibold mb-1">Total URLs</div>
        <div class="text-3xl font-bold text-gray-800"><?php echo $urlCount; ?></div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="text-sm text-gray-600 font-semibold mb-1">File Size</div>
        <div class="text-3xl font-bold text-gray-800">
            <?php echo $sitemapExists ? formatFileSize($sitemapSize) : '0 B'; ?>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="text-sm text-gray-600 font-semibold mb-1">Last Updated</div>
        <div class="text-lg font-bold text-gray-800">
            <?php echo $sitemapExists ? timeAgo($sitemapModified) : 'Never'; ?>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="text-sm text-gray-600 font-semibold mb-1">Status</div>
        <div class="text-lg font-bold">
            <?php if ($sitemapExists): ?>
                <span class="text-green-600"><i class="fas fa-check-circle"></i> Exists</span>
            <?php else: ?>
                <span class="text-red-600"><i class="fas fa-times-circle"></i> Missing</span>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Actions -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Regenerate Sitemap -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-sync text-blue-500 mr-2"></i>
            Regenerate Sitemap
        </h3>
        <p class="text-gray-600 mb-4">
            Automatically regenerate sitemap.xml with all current tours and blog posts.
        </p>
        <form method="POST" action="" class="space-y-4">
            <?php echo getCsrfField(); ?>
            <input type="hidden" name="action" value="regenerate">

            <button type="submit" class="btn-primary w-full">
                <i class="fas fa-sync mr-2"></i>
                Regenerate Now
            </button>
        </form>
    </div>

    <!-- View Sitemap -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-external-link-alt text-green-500 mr-2"></i>
            View & Submit
        </h3>
        <p class="text-gray-600 mb-4">
            View your sitemap or submit it to search engines.
        </p>
        <div class="space-y-3">
            <a
                href="<?php echo SITE_URL; ?>/sitemap.xml"
                target="_blank"
                class="btn-secondary w-full block text-center"
            >
                <i class="fas fa-eye mr-2"></i>
                View Sitemap
            </a>

            <a
                href="https://search.google.com/search-console"
                target="_blank"
                class="btn-secondary w-full block text-center"
            >
                <i class="fab fa-google mr-2"></i>
                Google Search Console
            </a>
        </div>
    </div>
</div>

<!-- Sitemap Preview -->
<?php if ($sitemapExists): ?>
<div class="bg-white rounded-lg shadow-md p-6 mt-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4">
        <i class="fas fa-code text-purple-500 mr-2"></i>
        Sitemap Preview
    </h3>
    <div class="bg-gray-900 rounded-lg p-4 overflow-x-auto">
        <pre class="text-green-400 text-sm font-mono"><?php
            $preview = file_get_contents($sitemapPath);
            // Show first 50 lines
            $lines = explode("\n", $preview);
            $previewLines = array_slice($lines, 0, 50);
            echo htmlspecialchars(implode("\n", $previewLines));
            if (count($lines) > 50) {
                echo "\n\n... (" . (count($lines) - 50) . " more lines)";
            }
        ?></pre>
    </div>
</div>
<?php endif; ?>

<?php renderAdminFooter(); ?>
