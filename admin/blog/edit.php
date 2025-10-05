<?php
/**
 * Edit Blog Post Page
 */

require_once '../config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';
require_once '../includes/file-parser.php';
require_once '../includes/layout.php';

requireAuth();

$filename = getQuery('file', '');

if (empty($filename)) {
    redirectWithMessage('list.php', 'Invalid blog post file', 'error');
}

$filepath = BLOG_DIR . DIRECTORY_SEPARATOR . $filename;

if (!file_exists($filepath)) {
    redirectWithMessage('list.php', 'Blog post file not found', 'error');
}

// Parse blog post data
$postData = parsePhpFile($filepath);

if (!$postData) {
    redirectWithMessage('list.php', 'Error parsing blog post file', 'error');
}

// Handle form submission
if (isPost()) {
    $csrfToken = getPost(CSRF_TOKEN_NAME, '');

    if (!validateCsrfToken($csrfToken)) {
        setErrorMessage('Invalid security token');
    } else {
        // Get form data
        $updateData = [
            'title' => getPost('title', ''),
            'meta_description' => getPost('meta_description', ''),
            'meta_keywords' => getPost('meta_keywords', ''),
            'canonical_url' => getPost('canonical_url', ''),
            'og_type' => getPost('og_type', 'article'),
            'og_image' => getPost('og_image', ''),
            'heading' => getPost('heading', ''),
        ];

        // Validate
        $errors = [];
        if (empty($updateData['title'])) {
            $errors[] = 'Title is required';
        }
        if (empty($updateData['meta_description'])) {
            $errors[] = 'Meta description is required';
        }
        if (strlen($updateData['meta_description']) > 160) {
            $errors[] = 'Meta description should be 160 characters or less';
        }

        if (empty($errors)) {
            if (updatePhpFile($filepath, $updateData)) {
                redirectWithMessage('list.php', 'Blog post updated successfully!', 'success');
            } else {
                setErrorMessage('Failed to update blog post. Please check file permissions.');
            }
        } else {
            setErrorMessage(implode('<br>', $errors));
        }
    }
}

renderAdminHeader('Edit Blog Post: ' . truncate($postData['title'], 50), 'blog');
?>

<!-- Breadcrumb -->
<div class="mb-6">
    <nav class="text-sm text-gray-600">
        <a href="../dashboard.php" class="hover:text-blue-600">Dashboard</a>
        <i class="fas fa-chevron-right mx-2 text-xs"></i>
        <a href="list.php" class="hover:text-blue-600">Blog Posts</a>
        <i class="fas fa-chevron-right mx-2 text-xs"></i>
        <span class="text-gray-800">Edit Post</span>
    </nav>
</div>

<!-- Edit Form -->
<form method="POST" action="">
    <?php echo getCsrfField(); ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-info-circle text-green-500 mr-2"></i>
                    Basic Information
                </h2>

                <!-- Page Title (SEO) -->
                <div class="mb-4">
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                        Page Title (SEO) <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        required
                        maxlength="200"
                        value="<?php echo htmlspecialchars($postData['title']); ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                        placeholder="Best Time to Visit Uzbekistan | Travel Guide"
                    >
                    <p class="text-xs text-gray-600 mt-1">
                        <i class="fas fa-info-circle"></i> This appears in browser tabs and search results
                    </p>
                </div>

                <!-- Heading (H1) -->
                <div class="mb-4">
                    <label for="heading" class="block text-sm font-semibold text-gray-700 mb-2">
                        Main Heading (H1)
                    </label>
                    <input
                        type="text"
                        id="heading"
                        name="heading"
                        maxlength="150"
                        value="<?php echo htmlspecialchars($postData['heading']); ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                        placeholder="Best Time to Visit Uzbekistan: Complete Seasonal Guide"
                    >
                </div>

                <!-- Meta Description -->
                <div class="mb-4">
                    <label for="meta_description" class="block text-sm font-semibold text-gray-700 mb-2">
                        Meta Description <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        id="meta_description"
                        name="meta_description"
                        required
                        maxlength="160"
                        rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                        placeholder="Brief description for search engines (max 160 characters)"
                        x-data="{ count: <?php echo strlen($postData['meta_description']); ?> }"
                        x-on:input="count = $el.value.length"
                    ><?php echo htmlspecialchars($postData['meta_description']); ?></textarea>
                    <p class="text-xs text-gray-600 mt-1" x-text="`${count}/160 characters`"></p>
                </div>

                <!-- Meta Keywords -->
                <div class="mb-4">
                    <label for="meta_keywords" class="block text-sm font-semibold text-gray-700 mb-2">
                        Meta Keywords
                    </label>
                    <input
                        type="text"
                        id="meta_keywords"
                        name="meta_keywords"
                        value="<?php echo htmlspecialchars($postData['meta_keywords']); ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                        placeholder="uzbekistan travel, best time to visit, weather"
                    >
                    <p class="text-xs text-gray-600 mt-1">
                        Comma-separated keywords
                    </p>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Actions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Actions</h3>

                <button type="submit" class="btn-primary w-full mb-3">
                    <i class="fas fa-save mr-2"></i>
                    Save Changes
                </button>

                <a href="list.php" class="btn-secondary w-full block text-center mb-3">
                    <i class="fas fa-times mr-2"></i>
                    Cancel
                </a>

                <a
                    href="<?php echo SITE_URL . '/blog/' . $postData['filename']; ?>"
                    target="_blank"
                    class="btn-secondary w-full block text-center"
                >
                    <i class="fas fa-external-link-alt mr-2"></i>
                    Preview
                </a>
            </div>

            <!-- File Info -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">File Information</h3>

                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-gray-600">Filename:</span>
                        <p class="font-semibold text-gray-800 mt-1 break-all">
                            <?php echo htmlspecialchars($postData['filename']); ?>
                        </p>
                    </div>

                    <div>
                        <span class="text-gray-600">Last Modified:</span>
                        <p class="font-semibold text-gray-800 mt-1">
                            <?php echo date('M j, Y g:i A', filemtime($filepath)); ?>
                        </p>
                    </div>

                    <div>
                        <span class="text-gray-600">File Size:</span>
                        <p class="font-semibold text-gray-800 mt-1">
                            <?php echo formatFileSize(filesize($filepath)); ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- SEO Settings -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">SEO Settings</h3>

                <!-- Canonical URL -->
                <div class="mb-4">
                    <label for="canonical_url" class="block text-sm font-semibold text-gray-700 mb-2">
                        Canonical URL
                    </label>
                    <input
                        type="url"
                        id="canonical_url"
                        name="canonical_url"
                        value="<?php echo htmlspecialchars($postData['canonical_url']); ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                        placeholder="https://jahongir-travel.uz/blog/..."
                    >
                </div>

                <!-- OG Type -->
                <div class="mb-4">
                    <label for="og_type" class="block text-sm font-semibold text-gray-700 mb-2">
                        Open Graph Type
                    </label>
                    <select
                        id="og_type"
                        name="og_type"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                    >
                        <option value="article" <?php echo $postData['og_type'] === 'article' ? 'selected' : ''; ?>>Article</option>
                        <option value="website" <?php echo $postData['og_type'] === 'website' ? 'selected' : ''; ?>>Website</option>
                    </select>
                </div>

                <!-- OG Image -->
                <div class="mb-4">
                    <label for="og_image" class="block text-sm font-semibold text-gray-700 mb-2">
                        Social Share Image
                    </label>
                    <input
                        type="url"
                        id="og_image"
                        name="og_image"
                        value="<?php echo htmlspecialchars($postData['og_image']); ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                        placeholder="https://jahongir-travel.uz/images/blog/..."
                    >
                </div>
            </div>
        </div>
    </div>
</form>

<?php renderAdminFooter(); ?>
