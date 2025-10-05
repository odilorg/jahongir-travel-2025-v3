<?php
/**
 * Media Library
 */

require_once '../config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';
require_once '../includes/layout.php';

requireAuth();

// Handle file upload
if (isPost() && isset($_FILES['image'])) {
    $csrfToken = getPost(CSRF_TOKEN_NAME, '');

    if (!validateCsrfToken($csrfToken)) {
        setErrorMessage('Invalid security token');
    } else {
        $file = $_FILES['image'];
        $errors = validateImageUpload($file);

        if (empty($errors)) {
            $filename = generateSafeFilename($file['name']);
            $filepath = UPLOAD_DIR . '/' . $filename;

            // Check if file already exists
            if (file_exists($filepath)) {
                $filename = time() . '-' . $filename;
                $filepath = UPLOAD_DIR . '/' . $filename;
            }

            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                // Try to convert to WebP
                convertToWebP($filepath);

                setSuccessMessage('Image uploaded successfully!');
            } else {
                setErrorMessage('Failed to upload image. Check directory permissions.');
            }
        } else {
            setErrorMessage(implode('<br>', $errors));
        }
    }
}

// Function to convert image to WebP
function convertToWebP($filepath) {
    $info = getimagesize($filepath);
    $type = $info[2];

    // Create image resource
    $image = null;
    switch ($type) {
        case IMAGETYPE_JPEG:
            $image = imagecreatefromjpeg($filepath);
            break;
        case IMAGETYPE_PNG:
            $image = imagecreatefrompng($filepath);
            break;
        case IMAGETYPE_GIF:
            $image = imagecreatefromgif($filepath);
            break;
    }

    if ($image) {
        $webpPath = preg_replace('/\.(jpg|jpeg|png|gif)$/i', '.webp', $filepath);
        imagewebp($image, $webpPath, 80); // 80% quality
        imagedestroy($image);
        return true;
    }

    return false;
}

// Get all images
$images = [];
if (is_dir(UPLOAD_DIR)) {
    $files = glob(UPLOAD_DIR . '/*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);
    foreach ($files as $file) {
        $images[] = [
            'path' => $file,
            'url' => str_replace(ROOT_DIR, SITE_URL, str_replace('\\', '/', $file)),
            'filename' => basename($file),
            'size' => filesize($file),
            'modified' => filemtime($file),
            'extension' => strtolower(pathinfo($file, PATHINFO_EXTENSION))
        ];
    }

    // Sort by modified time (newest first)
    usort($images, function($a, $b) {
        return $b['modified'] - $a['modified'];
    });
}

renderAdminHeader('Media Library', 'media');
?>

<!-- Upload Section -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4">
        <i class="fas fa-upload text-blue-500 mr-2"></i>
        Upload New Image
    </h2>

    <form method="POST" action="" enctype="multipart/form-data" x-data="{ fileName: '' }">
        <?php echo getCsrfField(); ?>

        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-500 transition-colors">
            <input
                type="file"
                name="image"
                id="image"
                accept="image/jpeg,image/png,image/gif,image/webp"
                required
                class="hidden"
                @change="fileName = $event.target.files[0]?.name || ''"
            >

            <label for="image" class="cursor-pointer">
                <i class="fas fa-cloud-upload-alt text-5xl text-gray-400 mb-3"></i>
                <p class="text-gray-600 mb-2">
                    Click to select an image or drag and drop
                </p>
                <p class="text-sm text-gray-500">
                    JPG, PNG, GIF or WebP (Max <?php echo formatFileSize(MAX_UPLOAD_SIZE); ?>)
                </p>
            </label>

            <p x-show="fileName" class="mt-4 text-sm font-semibold text-gray-700">
                Selected: <span x-text="fileName"></span>
            </p>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn-primary">
                <i class="fas fa-upload mr-2"></i>
                Upload Image
            </button>
        </div>
    </form>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="text-sm text-gray-600 font-semibold mb-1">Total Images</div>
        <div class="text-3xl font-bold text-gray-800"><?php echo count($images); ?></div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="text-sm text-gray-600 font-semibold mb-1">Total Size</div>
        <div class="text-3xl font-bold text-gray-800">
            <?php
            $totalSize = array_sum(array_column($images, 'size'));
            echo formatFileSize($totalSize);
            ?>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="text-sm text-gray-600 font-semibold mb-1">WebP Images</div>
        <div class="text-3xl font-bold text-gray-800">
            <?php
            $webpCount = count(array_filter($images, fn($img) => $img['extension'] === 'webp'));
            echo $webpCount;
            ?>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="text-sm text-gray-600 font-semibold mb-1">Recent Upload</div>
        <div class="text-lg font-bold text-gray-800">
            <?php echo !empty($images) ? timeAgo($images[0]['modified']) : 'Never'; ?>
        </div>
    </div>
</div>

<!-- Image Grid -->
<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4">
        <i class="fas fa-images text-purple-500 mr-2"></i>
        All Images (<?php echo count($images); ?>)
    </h2>

    <?php if (empty($images)): ?>
    <div class="text-center py-12">
        <i class="fas fa-images text-6xl text-gray-300 mb-4"></i>
        <p class="text-gray-600">No images uploaded yet</p>
    </div>
    <?php else: ?>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
        <?php foreach ($images as $image): ?>
        <div class="group relative border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow" x-data="{ showModal: false }">
            <!-- Image -->
            <div class="aspect-square bg-gray-100 flex items-center justify-center">
                <img
                    src="<?php echo htmlspecialchars($image['url']); ?>"
                    alt="<?php echo htmlspecialchars($image['filename']); ?>"
                    class="w-full h-full object-cover cursor-pointer"
                    @click="showModal = true"
                >
            </div>

            <!-- Info -->
            <div class="p-2 bg-white">
                <p class="text-xs font-semibold text-gray-800 truncate" title="<?php echo htmlspecialchars($image['filename']); ?>">
                    <?php echo htmlspecialchars($image['filename']); ?>
                </p>
                <div class="flex items-center justify-between mt-1">
                    <span class="text-xs text-gray-600">
                        <?php echo formatFileSize($image['size']); ?>
                    </span>
                    <span class="badge badge-<?php echo $image['extension'] === 'webp' ? 'success' : 'primary'; ?> text-xs">
                        <?php echo strtoupper($image['extension']); ?>
                    </span>
                </div>
            </div>

            <!-- Modal -->
            <div
                x-show="showModal"
                x-cloak
                class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4"
                @click="showModal = false"
            >
                <div class="max-w-4xl w-full bg-white rounded-lg p-6" @click.stop>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-800">
                            <?php echo htmlspecialchars($image['filename']); ?>
                        </h3>
                        <button @click="showModal = false" class="text-gray-600 hover:text-gray-800">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <img
                        src="<?php echo htmlspecialchars($image['url']); ?>"
                        alt="<?php echo htmlspecialchars($image['filename']); ?>"
                        class="w-full rounded-lg mb-4"
                    >

                    <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                        <div>
                            <span class="text-gray-600">Size:</span>
                            <span class="ml-2 font-semibold"><?php echo formatFileSize($image['size']); ?></span>
                        </div>
                        <div>
                            <span class="text-gray-600">Modified:</span>
                            <span class="ml-2 font-semibold"><?php echo timeAgo($image['modified']); ?></span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Image URL:</label>
                        <input
                            type="text"
                            value="<?php echo htmlspecialchars($image['url']); ?>"
                            readonly
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50"
                            onclick="this.select()"
                        >
                    </div>

                    <div class="flex items-center space-x-3">
                        <a href="<?php echo htmlspecialchars($image['url']); ?>" target="_blank" class="btn-primary">
                            <i class="fas fa-external-link-alt mr-2"></i>
                            Open Original
                        </a>
                        <button
                            @click="navigator.clipboard.writeText('<?php echo htmlspecialchars($image['url']); ?>'); alert('URL copied to clipboard!')"
                            class="btn-secondary"
                        >
                            <i class="fas fa-copy mr-2"></i>
                            Copy URL
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<?php renderAdminFooter(); ?>
