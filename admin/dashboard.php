<?php
/**
 * Admin Dashboard
 */

require_once 'config.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';
require_once 'includes/file-parser.php';
require_once 'includes/layout.php';

requireAuth();

// Get statistics
$tours = getAllTours();
$blogPosts = getAllBlogPosts();

$totalTours = count($tours);
$totalBlogPosts = count($blogPosts);

// Recent tours (last 5)
$recentTours = array_slice($tours, 0, 5);

// Recent blog posts (last 5)
$recentBlogPosts = array_slice($blogPosts, 0, 5);

// Render header
renderAdminHeader('Dashboard', 'dashboard');
?>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Tours -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 font-semibold">Total Tours</p>
                <p class="text-3xl font-bold text-gray-800 mt-2"><?php echo $totalTours; ?></p>
            </div>
            <div class="bg-blue-100 p-4 rounded-full">
                <i class="fas fa-map-marked-alt text-2xl text-blue-500"></i>
            </div>
        </div>
        <a href="tours/list.php" class="text-sm text-blue-600 hover:text-blue-700 mt-4 inline-block">
            View all tours <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>

    <!-- Total Blog Posts -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 font-semibold">Blog Posts</p>
                <p class="text-3xl font-bold text-gray-800 mt-2"><?php echo $totalBlogPosts; ?></p>
            </div>
            <div class="bg-green-100 p-4 rounded-full">
                <i class="fas fa-blog text-2xl text-green-500"></i>
            </div>
        </div>
        <a href="blog/list.php" class="text-sm text-green-600 hover:text-green-700 mt-4 inline-block">
            View all posts <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>

    <!-- Media Files -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 font-semibold">Media Library</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">
                    <?php
                    $imageCount = 0;
                    if (is_dir(IMAGES_DIR)) {
                        $imageFiles = glob(IMAGES_DIR . '/*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);
                        $imageCount = count($imageFiles);
                    }
                    echo $imageCount;
                    ?>
                </p>
            </div>
            <div class="bg-purple-100 p-4 rounded-full">
                <i class="fas fa-images text-2xl text-purple-500"></i>
            </div>
        </div>
        <a href="media/library.php" class="text-sm text-purple-600 hover:text-purple-700 mt-4 inline-block">
            Browse media <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <p class="text-sm text-gray-600 font-semibold">Quick Actions</p>
            <div class="bg-yellow-100 p-4 rounded-full">
                <i class="fas fa-bolt text-2xl text-yellow-500"></i>
            </div>
        </div>
        <div class="space-y-2">
            <a href="tours/create.php" class="block text-sm text-blue-600 hover:text-blue-700">
                <i class="fas fa-plus-circle mr-1"></i> New Tour
            </a>
            <a href="blog/create.php" class="block text-sm text-green-600 hover:text-green-700">
                <i class="fas fa-plus-circle mr-1"></i> New Blog Post
            </a>
            <a href="settings/sitemap.php" class="block text-sm text-gray-600 hover:text-gray-700">
                <i class="fas fa-sync mr-1"></i> Update Sitemap
            </a>
        </div>
    </div>
</div>

<!-- Recent Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Tours -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-map-marked-alt text-blue-500 mr-2"></i>
                    Recent Tours
                </h2>
                <a href="tours/list.php" class="text-sm text-blue-600 hover:text-blue-700">
                    View all
                </a>
            </div>
        </div>
        <div class="p-6">
            <?php if (empty($recentTours)): ?>
                <p class="text-gray-500 text-center py-8">No tours found</p>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($recentTours as $tour): ?>
                    <div class="flex items-start justify-between p-4 hover:bg-gray-50 rounded-lg transition-colors">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-800 mb-1">
                                <?php echo htmlspecialchars(truncate($tour['title'], 60)); ?>
                            </h3>
                            <div class="flex items-center space-x-3 text-sm text-gray-600">
                                <span class="badge badge-primary">
                                    <?php echo htmlspecialchars($tour['category']); ?>
                                </span>
                                <?php if ($tour['price']): ?>
                                <span>
                                    <i class="fas fa-dollar-sign"></i> <?php echo htmlspecialchars($tour['price']); ?>
                                </span>
                                <?php endif; ?>
                                <span>
                                    <i class="far fa-clock"></i> <?php echo timeAgo($tour['modified']); ?>
                                </span>
                            </div>
                        </div>
                        <a href="tours/edit.php?file=<?php echo urlencode($tour['filename']); ?>&dir=<?php echo urlencode(dirname($tour['path'])); ?>"
                           class="ml-4 text-blue-600 hover:text-blue-700">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Recent Blog Posts -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-blog text-green-500 mr-2"></i>
                    Recent Blog Posts
                </h2>
                <a href="blog/list.php" class="text-sm text-green-600 hover:text-green-700">
                    View all
                </a>
            </div>
        </div>
        <div class="p-6">
            <?php if (empty($recentBlogPosts)): ?>
                <p class="text-gray-500 text-center py-8">No blog posts found</p>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($recentBlogPosts as $post): ?>
                    <div class="flex items-start justify-between p-4 hover:bg-gray-50 rounded-lg transition-colors">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-800 mb-1">
                                <?php echo htmlspecialchars(truncate($post['title'], 60)); ?>
                            </h3>
                            <div class="flex items-center space-x-3 text-sm text-gray-600">
                                <span class="badge badge-success">Blog</span>
                                <span>
                                    <i class="far fa-clock"></i> <?php echo timeAgo($post['modified']); ?>
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-2">
                                <?php echo htmlspecialchars(truncate($post['meta_description'], 80)); ?>
                            </p>
                        </div>
                        <a href="blog/edit.php?file=<?php echo urlencode($post['filename']); ?>"
                           class="ml-4 text-green-600 hover:text-green-700">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- System Info -->
<div class="bg-white rounded-lg shadow-md p-6 mt-6">
    <h2 class="text-lg font-bold text-gray-800 mb-4">
        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
        System Information
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
        <div>
            <span class="text-gray-600">PHP Version:</span>
            <span class="ml-2 font-semibold"><?php echo phpversion(); ?></span>
        </div>
        <div>
            <span class="text-gray-600">Server Time:</span>
            <span class="ml-2 font-semibold"><?php echo date('Y-m-d H:i:s'); ?></span>
        </div>
        <div>
            <span class="text-gray-600">Disk Space:</span>
            <span class="ml-2 font-semibold">
                <?php
                $freeSpace = disk_free_space(ROOT_DIR);
                $totalSpace = disk_total_space(ROOT_DIR);
                echo formatFileSize($freeSpace) . ' free of ' . formatFileSize($totalSpace);
                ?>
            </span>
        </div>
    </div>
</div>

<?php renderAdminFooter(); ?>
