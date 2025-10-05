<?php
/**
 * Blog Posts List Page
 */

require_once '../config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';
require_once '../includes/file-parser.php';
require_once '../includes/layout.php';

requireAuth();

// Get all blog posts
$posts = getAllBlogPosts();

// Search functionality
$searchQuery = getQuery('search', '');
if ($searchQuery) {
    $posts = array_filter($posts, function($post) use ($searchQuery) {
        return stripos($post['title'], $searchQuery) !== false ||
               stripos($post['meta_description'], $searchQuery) !== false;
    });
}

renderAdminHeader('Blog Posts', 'blog');
?>

<!-- Header Actions -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-800">All Blog Posts</h2>
        <p class="text-sm text-gray-600 mt-1"><?php echo count($posts); ?> posts found</p>
    </div>
    <div class="flex items-center space-x-3">
        <a href="create.php" class="btn-primary">
            <i class="fas fa-plus-circle mr-2"></i>
            Create New Post
        </a>
    </div>
</div>

<!-- Search -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <form method="GET" action="" class="flex items-center space-x-4">
        <div class="flex-1">
            <input
                type="text"
                name="search"
                value="<?php echo htmlspecialchars($searchQuery); ?>"
                placeholder="Search blog posts..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
            >
        </div>
        <button type="submit" class="btn-primary">
            <i class="fas fa-search mr-2"></i>
            Search
        </button>
        <?php if ($searchQuery): ?>
        <a href="list.php" class="btn-secondary">
            <i class="fas fa-times"></i>
        </a>
        <?php endif; ?>
    </form>
</div>

<!-- Posts Grid -->
<?php if (empty($posts)): ?>
<div class="bg-white rounded-lg shadow-md p-12 text-center">
    <i class="fas fa-blog text-6xl text-gray-300 mb-4"></i>
    <p class="text-gray-600 mb-4">No blog posts found</p>
    <a href="create.php" class="btn-primary inline-block">
        <i class="fas fa-plus-circle mr-2"></i>
        Create Your First Post
    </a>
</div>
<?php else: ?>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach ($posts as $post): ?>
    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
        <div class="p-6">
            <div class="flex items-center justify-between mb-3">
                <span class="badge badge-success">Blog Post</span>
                <span class="text-xs text-gray-500">
                    <?php echo timeAgo($post['modified']); ?>
                </span>
            </div>

            <h3 class="text-lg font-bold text-gray-800 mb-3">
                <?php echo htmlspecialchars(truncate($post['title'], 70)); ?>
            </h3>

            <p class="text-sm text-gray-600 mb-4">
                <?php echo htmlspecialchars(truncate($post['meta_description'], 100)); ?>
            </p>

            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                <a
                    href="edit.php?file=<?php echo urlencode($post['filename']); ?>"
                    class="text-green-600 hover:text-green-700 font-semibold"
                >
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
                <a
                    href="<?php
                        // Construct URL based on current request
                        $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
                        $baseUrl .= str_replace('/admin/blog/list.php', '', $_SERVER['PHP_SELF']);
                        echo $baseUrl . '/blog/' . $post['filename'];
                    ?>"
                    target="_blank"
                    class="text-blue-600 hover:text-blue-700 font-semibold"
                >
                    <i class="fas fa-external-link-alt mr-1"></i> View
                </a>
            </div>
        </div>

        <div class="bg-gray-50 px-6 py-3 text-xs text-gray-600">
            <i class="fas fa-file mr-1"></i>
            <?php echo htmlspecialchars($post['filename']); ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php renderAdminFooter(); ?>
