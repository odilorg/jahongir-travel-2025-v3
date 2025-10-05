<?php
/**
 * Edit Tour Page
 */

require_once '../config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';
require_once '../includes/file-parser.php';
require_once '../includes/layout.php';

requireAuth();

$filename = getQuery('file', '');
$directory = getQuery('dir', '');

if (empty($filename) || empty($directory)) {
    redirectWithMessage('list.php', 'Invalid tour file', 'error');
}

$filepath = $directory . DIRECTORY_SEPARATOR . $filename;

if (!file_exists($filepath)) {
    redirectWithMessage('list.php', 'Tour file not found', 'error');
}

// Parse tour data
$tourData = parsePhpFile($filepath);

if (!$tourData) {
    redirectWithMessage('list.php', 'Error parsing tour file', 'error');
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
            'og_type' => getPost('og_type', 'product'),
            'og_image' => getPost('og_image', ''),
            'heading' => getPost('heading', ''),
            'price' => getPost('price', ''),
            'duration' => getPost('duration', ''),
            'tour_code' => getPost('tour_code', ''),
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
                redirectWithMessage('list.php', 'Tour updated successfully!', 'success');
            } else {
                setErrorMessage('Failed to update tour. Please check file permissions.');
            }
        } else {
            setErrorMessage(implode('<br>', $errors));
        }
    }
}

renderAdminHeader('Edit Tour: ' . truncate($tourData['title'], 50), 'tours');
?>

<!-- Breadcrumb -->
<div class="mb-6">
    <nav class="text-sm text-gray-600">
        <a href="../dashboard.php" class="hover:text-blue-600">Dashboard</a>
        <i class="fas fa-chevron-right mx-2 text-xs"></i>
        <a href="list.php" class="hover:text-blue-600">Tours</a>
        <i class="fas fa-chevron-right mx-2 text-xs"></i>
        <span class="text-gray-800">Edit Tour</span>
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
                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
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
                        value="<?php echo htmlspecialchars($tourData['title']); ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Best of Uzbekistan in 10 Days Tour | Jahongir Travel"
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
                        value="<?php echo htmlspecialchars($tourData['heading']); ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Best of Uzbekistan in 10 Days"
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
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Brief description for search engines (max 160 characters)"
                        x-data="{ count: <?php echo strlen($tourData['meta_description']); ?> }"
                        x-on:input="count = $el.value.length"
                    ><?php echo htmlspecialchars($tourData['meta_description']); ?></textarea>
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
                        value="<?php echo htmlspecialchars($tourData['meta_keywords']); ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="uzbekistan tour, samarkand, bukhara, silk road"
                    >
                    <p class="text-xs text-gray-600 mt-1">
                        Comma-separated keywords
                    </p>
                </div>
            </div>

            <!-- Tour Details -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-clipboard-list text-green-500 mr-2"></i>
                    Tour Details
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">
                            Price (USD)
                        </label>
                        <input
                            type="text"
                            id="price"
                            name="price"
                            value="<?php echo htmlspecialchars($tourData['price']); ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="950.00"
                        >
                    </div>

                    <!-- Duration -->
                    <div>
                        <label for="duration" class="block text-sm font-semibold text-gray-700 mb-2">
                            Duration
                        </label>
                        <input
                            type="text"
                            id="duration"
                            name="duration"
                            value="<?php echo htmlspecialchars($tourData['duration']); ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="10 DAYS 9 NIGHTS"
                        >
                    </div>

                    <!-- Tour Code -->
                    <div>
                        <label for="tour_code" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tour Code
                        </label>
                        <input
                            type="text"
                            id="tour_code"
                            name="tour_code"
                            value="<?php echo htmlspecialchars($tourData['tour_code']); ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="BEST10D"
                        >
                    </div>
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
                    href="<?php
                        // Get relative path from document root
                        $relativePath = str_replace(ROOT_DIR, '', str_replace('\\', '/', $filepath));
                        // Construct URL based on current request
                        $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
                        $baseUrl .= str_replace('/admin/tours/edit.php', '', $_SERVER['PHP_SELF']);
                        echo $baseUrl . $relativePath;
                    ?>"
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
                            <?php echo htmlspecialchars($tourData['filename']); ?>
                        </p>
                    </div>

                    <div>
                        <span class="text-gray-600">Category:</span>
                        <p class="font-semibold text-gray-800 mt-1">
                            <?php echo htmlspecialchars(getTourCategory($filepath)); ?>
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
                        value="<?php echo htmlspecialchars($tourData['canonical_url']); ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="https://jahongir-travel.uz/..."
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
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="product" <?php echo $tourData['og_type'] === 'product' ? 'selected' : ''; ?>>Product</option>
                        <option value="website" <?php echo $tourData['og_type'] === 'website' ? 'selected' : ''; ?>>Website</option>
                        <option value="article" <?php echo $tourData['og_type'] === 'article' ? 'selected' : ''; ?>>Article</option>
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
                        value="<?php echo htmlspecialchars($tourData['og_image']); ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="https://jahongir-travel.uz/images/..."
                    >
                </div>
            </div>
        </div>
    </div>
</form>

<?php renderAdminFooter(); ?>
