<?php
/**
 * Tours List Page
 */

require_once '../config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';
require_once '../includes/file-parser.php';
require_once '../includes/layout.php';

requireAuth();

// Get all tours
$tours = getAllTours();

// Search functionality
$searchQuery = getQuery('search', '');
if ($searchQuery) {
    $tours = array_filter($tours, function($tour) use ($searchQuery) {
        return stripos($tour['title'], $searchQuery) !== false ||
               stripos($tour['category'], $searchQuery) !== false;
    });
}

// Category filter
$categoryFilter = getQuery('category', '');
if ($categoryFilter) {
    $tours = array_filter($tours, function($tour) use ($categoryFilter) {
        return $tour['category'] === $categoryFilter;
    });
}

// Get unique categories
$categories = array_unique(array_column($tours, 'category'));
sort($categories);

renderAdminHeader('Tours', 'tours');
?>

<!-- Header Actions -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-800">All Tours</h2>
        <p class="text-sm text-gray-600 mt-1"><?php echo count($tours); ?> tours found</p>
    </div>
    <div class="flex items-center space-x-3">
        <a href="create.php" class="btn-primary">
            <i class="fas fa-plus-circle mr-2"></i>
            Create New Tour
        </a>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <form method="GET" action="" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Search -->
        <div>
            <label for="search" class="block text-sm font-semibold text-gray-700 mb-2">Search</label>
            <input
                type="text"
                id="search"
                name="search"
                value="<?php echo htmlspecialchars($searchQuery); ?>"
                placeholder="Search by title..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
        </div>

        <!-- Category -->
        <div>
            <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">Category</label>
            <select
                id="category"
                name="category"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo $categoryFilter === $cat ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($cat); ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Submit -->
        <div class="flex items-end">
            <button type="submit" class="btn-primary w-full">
                <i class="fas fa-search mr-2"></i>
                Filter
            </button>
            <?php if ($searchQuery || $categoryFilter): ?>
            <a href="list.php" class="btn-secondary ml-2">
                <i class="fas fa-times"></i>
            </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Tours Table -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <?php if (empty($tours)): ?>
    <div class="p-12 text-center">
        <i class="fas fa-map-marked-alt text-6xl text-gray-300 mb-4"></i>
        <p class="text-gray-600 mb-4">No tours found</p>
        <a href="create.php" class="btn-primary inline-block">
            <i class="fas fa-plus-circle mr-2"></i>
            Create Your First Tour
        </a>
    </div>
    <?php else: ?>
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                    Tour Title
                </th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                    Category
                </th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                    Price
                </th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                    Duration
                </th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                    Last Modified
                </th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                    Actions
                </th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            <?php foreach ($tours as $tour): ?>
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4">
                    <div class="font-semibold text-gray-800">
                        <?php echo htmlspecialchars(truncate($tour['title'], 70)); ?>
                    </div>
                    <div class="text-sm text-gray-600 mt-1">
                        <?php echo htmlspecialchars($tour['filename']); ?>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="badge badge-primary">
                        <?php echo htmlspecialchars($tour['category']); ?>
                    </span>
                </td>
                <td class="px-6 py-4 text-gray-700">
                    <?php if ($tour['price']): ?>
                        $<?php echo htmlspecialchars($tour['price']); ?>
                    <?php else: ?>
                        <span class="text-gray-400">-</span>
                    <?php endif; ?>
                </td>
                <td class="px-6 py-4 text-gray-700">
                    <?php if ($tour['duration']): ?>
                        <?php echo htmlspecialchars($tour['duration']); ?>
                    <?php else: ?>
                        <span class="text-gray-400">-</span>
                    <?php endif; ?>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                    <?php echo timeAgo($tour['modified']); ?>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end space-x-2">
                        <a
                            href="edit.php?file=<?php echo urlencode($tour['filename']); ?>&dir=<?php echo urlencode(dirname($tour['path'])); ?>"
                            class="text-blue-600 hover:text-blue-700"
                            title="Edit"
                        >
                            <i class="fas fa-edit"></i>
                        </a>
                        <a
                            href="<?php echo str_replace(ROOT_DIR, SITE_URL, str_replace('\\', '/', $tour['path'])); ?>"
                            target="_blank"
                            class="text-green-600 hover:text-green-700"
                            title="Preview"
                        >
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<?php renderAdminFooter(); ?>
