<?php
/**
 * Admin Profile / Settings
 */

require_once '../config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';
require_once '../includes/layout.php';

requireAuth();

// Handle password change
if (isPost() && getPost('action') === 'change_password') {
    $csrfToken = getPost(CSRF_TOKEN_NAME, '');

    if (!validateCsrfToken($csrfToken)) {
        setErrorMessage('Invalid security token');
    } else {
        $currentPassword = getPost('current_password', '');
        $newPassword = getPost('new_password', '');
        $confirmPassword = getPost('confirm_password', '');

        $errors = [];

        // Validate current password
        if (!password_verify($currentPassword, ADMIN_PASSWORD_HASH)) {
            $errors[] = 'Current password is incorrect';
        }

        // Validate new password
        if (strlen($newPassword) < 8) {
            $errors[] = 'New password must be at least 8 characters';
        }

        if ($newPassword !== $confirmPassword) {
            $errors[] = 'New passwords do not match';
        }

        if (empty($errors)) {
            // Generate new password hash
            $newHash = password_hash($newPassword, PASSWORD_BCRYPT);

            // Update config file
            $configPath = dirname(__DIR__) . '/config.php';
            $configContent = file_get_contents($configPath);

            $pattern = "/define\('ADMIN_PASSWORD_HASH', '.*?'\);/";
            $replacement = "define('ADMIN_PASSWORD_HASH', '{$newHash}');";

            $newConfigContent = preg_replace($pattern, $replacement, $configContent);

            if (file_put_contents($configPath, $newConfigContent)) {
                setSuccessMessage('Password changed successfully! Please login again.');
                logout();
                header('Location: ../index.php');
                exit;
            } else {
                setErrorMessage('Failed to update password. Check file permissions.');
            }
        } else {
            setErrorMessage(implode('<br>', $errors));
        }
    }
}

renderAdminHeader('Settings', 'settings');
?>

<!-- Profile Info -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex items-start justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">Admin Profile</h2>
            <p class="text-gray-600">Manage your admin account settings</p>
        </div>
        <div class="bg-blue-100 p-4 rounded-full">
            <i class="fas fa-user-cog text-2xl text-blue-500"></i>
        </div>
    </div>
</div>

<!-- Account Info -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Account Information</h3>

        <div class="space-y-3">
            <div>
                <span class="text-sm text-gray-600">Username:</span>
                <p class="font-semibold text-gray-800"><?php echo htmlspecialchars(ADMIN_USERNAME); ?></p>
            </div>

            <div>
                <span class="text-sm text-gray-600">Login Time:</span>
                <p class="font-semibold text-gray-800">
                    <?php echo isset($_SESSION['login_time']) ? date('M j, Y g:i A', $_SESSION['login_time']) : 'Unknown'; ?>
                </p>
            </div>

            <div>
                <span class="text-sm text-gray-600">Session Timeout:</span>
                <p class="font-semibold text-gray-800">
                    <?php echo SESSION_TIMEOUT / 60; ?> minutes
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Site Information</h3>

        <div class="space-y-3">
            <div>
                <span class="text-sm text-gray-600">Site Name:</span>
                <p class="font-semibold text-gray-800"><?php echo htmlspecialchars(SITE_NAME); ?></p>
            </div>

            <div>
                <span class="text-sm text-gray-600">Site URL:</span>
                <p class="font-semibold text-gray-800">
                    <a href="<?php echo SITE_URL; ?>" target="_blank" class="text-blue-600 hover:text-blue-700">
                        <?php echo htmlspecialchars(SITE_URL); ?>
                        <i class="fas fa-external-link-alt text-xs ml-1"></i>
                    </a>
                </p>
            </div>

            <div>
                <span class="text-sm text-gray-600">PHP Version:</span>
                <p class="font-semibold text-gray-800"><?php echo phpversion(); ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Change Password -->
<div class="bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4">
        <i class="fas fa-key text-yellow-500 mr-2"></i>
        Change Password
    </h3>

    <p class="text-gray-600 mb-6">
        For security reasons, please use a strong password with at least 8 characters.
    </p>

    <form method="POST" action="" class="max-w-md">
        <?php echo getCsrfField(); ?>
        <input type="hidden" name="action" value="change_password">

        <!-- Current Password -->
        <div class="mb-4">
            <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-2">
                Current Password <span class="text-red-500">*</span>
            </label>
            <input
                type="password"
                id="current_password"
                name="current_password"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
        </div>

        <!-- New Password -->
        <div class="mb-4">
            <label for="new_password" class="block text-sm font-semibold text-gray-700 mb-2">
                New Password <span class="text-red-500">*</span>
            </label>
            <input
                type="password"
                id="new_password"
                name="new_password"
                required
                minlength="8"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
            <p class="text-xs text-gray-600 mt-1">Minimum 8 characters</p>
        </div>

        <!-- Confirm Password -->
        <div class="mb-6">
            <label for="confirm_password" class="block text-sm font-semibold text-gray-700 mb-2">
                Confirm New Password <span class="text-red-500">*</span>
            </label>
            <input
                type="password"
                id="confirm_password"
                name="confirm_password"
                required
                minlength="8"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
        </div>

        <button type="submit" class="btn-primary">
            <i class="fas fa-save mr-2"></i>
            Change Password
        </button>
    </form>
</div>

<!-- Security Notice -->
<div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mt-6">
    <div class="flex items-start">
        <i class="fas fa-exclamation-triangle text-yellow-500 text-2xl mr-4 mt-1"></i>
        <div>
            <h4 class="font-bold text-gray-800 mb-2">Security Recommendations</h4>
            <ul class="text-sm text-gray-700 space-y-1">
                <li>• Use a unique, strong password that you don't use elsewhere</li>
                <li>• Change your password regularly (every 3-6 months)</li>
                <li>• Never share your admin credentials</li>
                <li>• Always logout when finished</li>
                <li>• Access admin panel only from secure networks</li>
            </ul>
        </div>
    </div>
</div>

<?php renderAdminFooter(); ?>
