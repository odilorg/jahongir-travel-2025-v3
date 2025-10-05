<?php
/**
 * Admin Layout Template
 */

function renderAdminHeader($title = 'Dashboard', $currentPage = 'dashboard') {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?> - <?php echo SITE_NAME; ?> Admin</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom Admin Styles -->
    <style>
        [x-cloak] { display: none !important; }

        .sidebar-link {
            @apply flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors rounded-lg;
        }

        .sidebar-link.active {
            @apply bg-blue-500 text-white hover:bg-blue-600;
        }

        .btn-primary {
            @apply bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors;
        }

        .btn-secondary {
            @apply bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors;
        }

        .btn-danger {
            @apply bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors;
        }

        .badge {
            @apply px-2 py-1 text-xs font-semibold rounded-full;
        }

        .badge-primary {
            @apply bg-blue-100 text-blue-800;
        }

        .badge-success {
            @apply bg-green-100 text-green-800;
        }

        .badge-warning {
            @apply bg-yellow-100 text-yellow-800;
        }
    </style>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#236373',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50" x-data="{ sidebarOpen: true, mobileMenuOpen: false }">

    <!-- Mobile Menu Button -->
    <div class="lg:hidden fixed top-4 left-4 z-50">
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="bg-white p-2 rounded-lg shadow-lg">
            <i class="fas fa-bars text-gray-700"></i>
        </button>
    </div>

    <!-- Sidebar -->
    <aside
        class="fixed top-0 left-0 h-full bg-white shadow-lg transition-all duration-300 z-40"
        :class="{ 'w-64': sidebarOpen, 'w-20': !sidebarOpen, 'hidden': !mobileMenuOpen }"
        x-show="mobileMenuOpen || window.innerWidth >= 1024"
        x-cloak
    >
        <!-- Logo -->
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3" x-show="sidebarOpen">
                    <i class="fas fa-plane-departure text-2xl text-blue-500"></i>
                    <span class="font-bold text-xl text-gray-800">JT Admin</span>
                </div>
                <i class="fas fa-plane-departure text-2xl text-blue-500" x-show="!sidebarOpen"></i>
                <button
                    @click="sidebarOpen = !sidebarOpen"
                    class="hidden lg:block text-gray-500 hover:text-gray-700"
                >
                    <i class="fas" :class="sidebarOpen ? 'fa-chevron-left' : 'fa-chevron-right'"></i>
                </button>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="p-4 space-y-2">
            <a href="/admin/dashboard.php" class="sidebar-link <?php echo $currentPage === 'dashboard' ? 'active' : ''; ?>">
                <i class="fas fa-home w-6"></i>
                <span x-show="sidebarOpen" class="ml-3">Dashboard</span>
            </a>

            <a href="/admin/tours/list.php" class="sidebar-link <?php echo $currentPage === 'tours' ? 'active' : ''; ?>">
                <i class="fas fa-map-marked-alt w-6"></i>
                <span x-show="sidebarOpen" class="ml-3">Tours</span>
            </a>

            <a href="/admin/blog/list.php" class="sidebar-link <?php echo $currentPage === 'blog' ? 'active' : ''; ?>">
                <i class="fas fa-blog w-6"></i>
                <span x-show="sidebarOpen" class="ml-3">Blog Posts</span>
            </a>

            <a href="/admin/media/library.php" class="sidebar-link <?php echo $currentPage === 'media' ? 'active' : ''; ?>">
                <i class="fas fa-images w-6"></i>
                <span x-show="sidebarOpen" class="ml-3">Media Library</span>
            </a>

            <div class="border-t border-gray-200 my-4"></div>

            <a href="/admin/settings/sitemap.php" class="sidebar-link <?php echo $currentPage === 'sitemap' ? 'active' : ''; ?>">
                <i class="fas fa-sitemap w-6"></i>
                <span x-show="sidebarOpen" class="ml-3">Sitemap</span>
            </a>

            <a href="/admin/settings/profile.php" class="sidebar-link <?php echo $currentPage === 'settings' ? 'active' : ''; ?>">
                <i class="fas fa-cog w-6"></i>
                <span x-show="sidebarOpen" class="ml-3">Settings</span>
            </a>

            <a href="/admin/logout.php" class="sidebar-link text-red-600 hover:bg-red-50 hover:text-red-700">
                <i class="fas fa-sign-out-alt w-6"></i>
                <span x-show="sidebarOpen" class="ml-3">Logout</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main
        class="transition-all duration-300"
        :class="{ 'lg:ml-64': sidebarOpen, 'lg:ml-20': !sidebarOpen }"
    >
        <!-- Top Bar -->
        <header class="bg-white shadow-sm">
            <div class="px-6 py-4 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800"><?php echo htmlspecialchars($title); ?></h1>
                    <p class="text-sm text-gray-600 mt-1">
                        <i class="fas fa-user-circle"></i>
                        Welcome, <?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?>
                    </p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="<?php echo SITE_URL; ?>" target="_blank" class="text-sm text-gray-600 hover:text-blue-600">
                        <i class="fas fa-external-link-alt mr-1"></i> View Site
                    </a>
                    <span class="text-sm text-gray-500">
                        <i class="far fa-clock"></i> <?php echo date('M j, Y g:i A'); ?>
                    </span>
                </div>
            </div>
        </header>

        <!-- Messages -->
        <div class="px-6 py-4">
            <?php
            $successMsg = getSuccessMessage();
            $errorMsg = getErrorMessage();

            if ($successMsg):
            ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center justify-between" x-data="{ show: true }" x-show="show">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span><?php echo htmlspecialchars($successMsg); ?></span>
                </div>
                <button @click="show = false" class="text-green-700 hover:text-green-900">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <?php endif; ?>

            <?php if ($errorMsg): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center justify-between" x-data="{ show: true }" x-show="show">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span><?php echo htmlspecialchars($errorMsg); ?></span>
                </div>
                <button @click="show = false" class="text-red-700 hover:text-red-900">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <?php endif; ?>
        </div>

        <!-- Page Content -->
        <div class="px-6 py-4">
<?php
}

function renderAdminFooter() {
?>
        </div>

        <!-- Footer -->
        <footer class="px-6 py-4 text-center text-sm text-gray-600 border-t border-gray-200 mt-8">
            &copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?> - Admin Panel
        </footer>
    </main>

</body>
</html>
<?php
}
?>
