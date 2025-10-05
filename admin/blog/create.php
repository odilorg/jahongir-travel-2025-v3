<?php
/**
 * Create New Blog Post Page
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
            'og_type' => getPost('og_type', 'article'),
            'og_image' => getPost('og_image', ''),
            'heading' => getPost('heading', ''),
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

        // Validate filename
        if (!empty($data['filename'])) {
            $filename = $data['filename'];
            if (!preg_match('/^[a-z0-9-]+\.php$/', $filename)) {
                $errors[] = 'Filename must be lowercase with hyphens only (e.g., my-blog-post.php)';
            }

            $filepath = BLOG_DIR . DIRECTORY_SEPARATOR . $filename;

            if (file_exists($filepath)) {
                $errors[] = 'A blog post with this filename already exists';
            }
        }

        if (empty($errors)) {
            // Create new blog post file from template
            if (createBlogFile($filepath, $data)) {
                redirectWithMessage('list.php', 'Blog post created successfully!', 'success');
            } else {
                setErrorMessage('Failed to create blog post file. Please check directory permissions.');
            }
        } else {
            setErrorMessage(implode('<br>', $errors));
        }
    }
}

// Function to create new blog file
function createBlogFile($filepath, $data) {
    // Get template
    $template = getBlogTemplate($data);

    // Write file
    return file_put_contents($filepath, $template) !== false;
}

// Function to generate blog template
function getBlogTemplate($data) {
    $title = addslashes($data['title']);
    $metaDesc = addslashes($data['meta_description']);
    $metaKeys = addslashes($data['meta_keywords']);
    $canonical = addslashes($data['canonical_url']);
    $ogType = addslashes($data['og_type']);
    $ogImage = addslashes($data['og_image']);
    $heading = htmlspecialchars($data['heading']);

    $template = <<<PHP
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
                    <li><a href="../index.php" class="home">Home</a></li>
                    <li><a href="index.php">Travel Blog</a></li>
                    <li>{$heading}</li>
                </ul>
            </div>
            <h1 class="heading_primary">{$heading}</h1>
        </div>
    </div>

    <section class="content-area single-woo-tour">
        <div class="container">
            <div class="row">
                <div class="site-main col-sm-8">
                    <div class="tb_single_tour product">
                        <div class="top_content_single row">
                            <div class="images images_single_left">
                                <div class="title-single">
                                    <div class="title">
                                        <h1>{$heading}</h1>
                                    </div>
                                    <div class="tour_code">
                                        <strong>Published: </strong><?php echo date('F Y'); ?>
                                    </div>
                                </div>

                                <div class="post_images">
                                    <img width="800" height="400" src="../images/blog/placeholder.jpg" alt="{$heading}" style="display: block; width: 100%; height: auto; max-width: 100%;">
                                </div>
                            </div>
                        </div>

                        <div class="tour_content">
                            <div class="tour_content_wrapper">
                                <div class="tour_content_description">
                                    <div class="description">
                                        <p class="lead">Add your blog post content here.</p>

                                        <h2>Section Title</h2>
                                        <p>Add your content in paragraphs and sections.</p>

                                        <h3>Subsection</h3>
                                        <p>More detailed information...</p>

                                        <ul>
                                            <li>Bullet point 1</li>
                                            <li>Bullet point 2</li>
                                            <li>Bullet point 3</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-sm-4">
                    <aside class="widget">
                        <h3 class="widget-title">Related Posts</h3>
                        <ul>
                            <li><a href="best-time-to-visit-uzbekistan.php">Best Time to Visit Uzbekistan</a></li>
                            <li><a href="uzbekistan-visa-requirements.php">Uzbekistan Visa Requirements</a></li>
                            <li><a href="what-to-pack-uzbekistan-travel.php">What to Pack for Uzbekistan</a></li>
                        </ul>
                    </aside>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'includes/footer.php';?>
PHP;

    return $template;
}

renderAdminHeader('Create New Blog Post', 'blog');
?>

<!-- Breadcrumb -->
<div class="mb-6">
    <nav class="text-sm text-gray-600">
        <a href="../dashboard.php" class="hover:text-blue-600">Dashboard</a>
        <i class="fas fa-chevron-right mx-2 text-xs"></i>
        <a href="list.php" class="hover:text-blue-600">Blog Posts</a>
        <i class="fas fa-chevron-right mx-2 text-xs"></i>
        <span class="text-gray-800">Create New Post</span>
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
                    <i class="fas fa-info-circle text-green-500 mr-2"></i>
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
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                        placeholder="my-blog-post.php"
                    >
                    <p class="text-xs text-gray-600 mt-1">
                        <i class="fas fa-info-circle"></i> Lowercase letters, numbers, hyphens only. Must end with .php
                    </p>
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
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                        placeholder="My Blog Post Title | Jahongir Travel"
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
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                        placeholder="My Blog Post Title"
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
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                        placeholder="uzbekistan travel, tips, guides"
                    >
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
                    Create Blog Post
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
                        <option value="article" selected>Article</option>
                        <option value="website">Website</option>
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
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                        placeholder="https://jahongir-travel.uz/images/blog/..."
                    >
                </div>
            </div>

            <!-- Info -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-green-500 text-xl mr-3 mt-1"></i>
                    <div class="text-sm text-gray-700">
                        <p class="font-semibold mb-2">After Creating:</p>
                        <ul class="space-y-1">
                            <li>• File will be created in /blog/ folder</li>
                            <li>• Edit the file to add full post content</li>
                            <li>• Add images and formatting</li>
                            <li>• Update sitemap after creation</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<?php renderAdminFooter(); ?>
