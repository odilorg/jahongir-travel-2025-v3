<?php
/**
 * Create New Tour Page
 */

require_once '../config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';
require_once '../includes/file-parser.php';
require_once '../includes/layout.php';

requireAuth();

// Handle form submission
if (isPost()) {
    $csrfToken = getPost(CSRF_TOKEN_NAME, '');

    if (!validateCsrfToken($csrfToken)) {
        setErrorMessage('Invalid security token');
    } else {
        // Get form data
        $data = [
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
            'category' => getPost('category', ''),
            'filename' => getPost('filename', ''),
        ];

        // Validate
        $errors = [];
        if (empty($data['title'])) {
            $errors[] = 'Title is required';
        }
        if (empty($data['meta_description'])) {
            $errors[] = 'Meta description is required';
        }
        if (strlen($data['meta_description']) > 160) {
            $errors[] = 'Meta description should be 160 characters or less';
        }
        if (empty($data['filename'])) {
            $errors[] = 'Filename is required';
        }
        if (empty($data['category'])) {
            $errors[] = 'Category is required';
        }

        // Validate filename
        if (!empty($data['filename'])) {
            $filename = $data['filename'];
            if (!preg_match('/^[a-z0-9-]+\.php$/', $filename)) {
                $errors[] = 'Filename must be lowercase with hyphens only (e.g., my-tour-name.php)';
            }

            // Determine directory based on category
            $directory = TOURS_DIR;
            if ($data['category'] === 'Tours from Samarkand') {
                $directory = SAMARKAND_TOURS_DIR;
            } elseif ($data['category'] === 'Tours from Bukhara') {
                $directory = BUKHARA_TOURS_DIR;
            } elseif ($data['category'] === 'Tours from Khiva') {
                $directory = KHIVA_TOURS_DIR;
            }

            $filepath = $directory . DIRECTORY_SEPARATOR . $filename;

            if (file_exists($filepath)) {
                $errors[] = 'A tour with this filename already exists';
            }
        }

        if (empty($errors)) {
            // Create new tour file from template
            if (createTourFile($filepath, $data)) {
                redirectWithMessage('list.php', 'Tour created successfully!', 'success');
            } else {
                setErrorMessage('Failed to create tour file. Please check directory permissions.');
            }
        } else {
            setErrorMessage(implode('<br>', $errors));
        }
    }
}

// Function to create new tour file
function createTourFile($filepath, $data) {
    // Ensure directory exists
    $directory = dirname($filepath);
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
    }

    // Get template
    $template = getTourTemplate($data);

    // Write file
    return file_put_contents($filepath, $template) !== false;
}

// Function to generate tour template
function getTourTemplate($data) {
    $title = addslashes($data['title']);
    $metaDesc = addslashes($data['meta_description']);
    $metaKeys = addslashes($data['meta_keywords']);
    $canonical = addslashes($data['canonical_url']);
    $ogType = addslashes($data['og_type']);
    $ogImage = addslashes($data['og_image']);
    $heading = htmlspecialchars($data['heading']);
    $price = htmlspecialchars($data['price']);
    $duration = htmlspecialchars($data['duration']);
    $tourCode = htmlspecialchars($data['tour_code']);

    $template = <<<PHP
<?php include '../mailer-tours.php'; ?>
<?php
\$title = "{$title}";
\$meta_description = "{$metaDesc}";
\$meta_keywords = "{$metaKeys}";
\$canonical_url = "{$canonical}";
\$og_type = "{$ogType}";
\$og_image = "{$ogImage}";
?>
<?php include 'includes/header.php';?>

<div class="site wrapper-content">
    <div class="top_site_main" style="background-image:url(../images/banner/top-heading.jpg);">
        <div class="banner-wrapper container article_heading">
            <div class="breadcrumbs-wrapper">
                <ul class="phys-breadcrumb">
                    <li><a href="../index.php" class="home">Tours</a></li>
                    <li>Uzbekistan tours</li>
                </ul>
            </div>
            <?php echo \$alert; ?>
            <h1 class="heading_primary">{$heading}</h1>
        </div>
    </div>

    <section class="content-area single-woo-tour">
        <div class="container">
            <div class="tb_single_tour product">
                <div class="top_content_single row">
                    <div class="images images_single_left">
                        <div class="title-single">
                            <div class="title">
                                <h2>Tour Details</h2>
                            </div>
                            <div class="tour_code">
                                <strong>Code: </strong>{$tourCode}
                            </div>
                        </div>

                        <div class="tour_after_title">
                            <div class="meta_date">
                                <span>{$duration}</span>
                            </div>
                            <div class="meta_values">
                                <span>Category:</span>
                                <div class="value">
                                    <a href="#" rel="tag">Culture</a>,
                                    <a href="#" rel="tag">Architecture Tour</a>
                                </div>
                            </div>
                        </div>

                        <!-- Add your tour content here -->
                        <div class="tour_content">
                            <div class="tour_content_wrapper">
                                <div class="tour_content_description">
                                    <p>This is a new tour. Add your content here.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'includes/footer.php';?>
PHP;

    return $template;
}

renderAdminHeader('Create New Tour', 'tours');
?>

<!-- Breadcrumb -->
<div class="mb-6">
    <nav class="text-sm text-gray-600">
        <a href="../dashboard.php" class="hover:text-blue-600">Dashboard</a>
        <i class="fas fa-chevron-right mx-2 text-xs"></i>
        <a href="list.php" class="hover:text-blue-600">Tours</a>
        <i class="fas fa-chevron-right mx-2 text-xs"></i>
        <span class="text-gray-800">Create New Tour</span>
    </nav>
</div>

<!-- Create Form -->
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

                <!-- Filename -->
                <div class="mb-4">
                    <label for="filename" class="block text-sm font-semibold text-gray-700 mb-2">
                        Filename <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="filename"
                        name="filename"
                        required
                        pattern="[a-z0-9-]+\.php"
                        value="<?php echo htmlspecialchars(getPost('filename', '')); ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="my-tour-name.php"
                    >
                    <p class="text-xs text-gray-600 mt-1">
                        <i class="fas fa-info-circle"></i> Lowercase letters, numbers, hyphens only. Must end with .php
                    </p>
                </div>

                <!-- Category -->
                <div class="mb-4">
                    <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">
                        Category <span class="text-red-500">*</span>
                    </label>
                    <select
                        id="category"
                        name="category"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="">Select Category</option>
                        <option value="Uzbekistan Tours" <?php echo getPost('category') === 'Uzbekistan Tours' ? 'selected' : ''; ?>>Uzbekistan Tours</option>
                        <option value="Tours from Samarkand" <?php echo getPost('category') === 'Tours from Samarkand' ? 'selected' : ''; ?>>Tours from Samarkand</option>
                        <option value="Tours from Bukhara" <?php echo getPost('category') === 'Tours from Bukhara' ? 'selected' : ''; ?>>Tours from Bukhara</option>
                        <option value="Tours from Khiva" <?php echo getPost('category') === 'Tours from Khiva' ? 'selected' : ''; ?>>Tours from Khiva</option>
                    </select>
                </div>

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
                        value="<?php echo htmlspecialchars(getPost('title', '')); ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="My Amazing Tour | Jahongir Travel"
                    >
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
                        value="<?php echo htmlspecialchars(getPost('heading', '')); ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="My Amazing Tour"
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
                        x-data="{ count: 0 }"
                        x-on:input="count = $el.value.length"
                    ><?php echo htmlspecialchars(getPost('meta_description', '')); ?></textarea>
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
                        value="<?php echo htmlspecialchars(getPost('meta_keywords', '')); ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="uzbekistan tour, samarkand, bukhara"
                    >
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
                            value="<?php echo htmlspecialchars(getPost('price', '')); ?>"
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
                            value="<?php echo htmlspecialchars(getPost('duration', '')); ?>"
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
                            value="<?php echo htmlspecialchars(getPost('tour_code', '')); ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="MYTOUR1"
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
                    Create Tour
                </button>

                <a href="list.php" class="btn-secondary w-full block text-center">
                    <i class="fas fa-times mr-2"></i>
                    Cancel
                </a>
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
                        value="<?php echo htmlspecialchars(getPost('canonical_url', '')); ?>"
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
                        <option value="product" selected>Product</option>
                        <option value="website">Website</option>
                        <option value="article">Article</option>
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
                        value="<?php echo htmlspecialchars(getPost('og_image', '')); ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="https://jahongir-travel.uz/images/..."
                    >
                </div>
            </div>

            <!-- Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 text-xl mr-3 mt-1"></i>
                    <div class="text-sm text-gray-700">
                        <p class="font-semibold mb-2">After Creating:</p>
                        <ul class="space-y-1">
                            <li>• File will be created in selected category folder</li>
                            <li>• Edit the file to add full tour content</li>
                            <li>• Add images and detailed itinerary</li>
                            <li>• Update sitemap after creation</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<?php renderAdminFooter(); ?>
