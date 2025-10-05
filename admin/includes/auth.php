<?php
/**
 * Authentication Functions
 */

require_once dirname(__DIR__) . '/config.php';

/**
 * Start admin session
 */
function startAdminSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_name(SESSION_NAME);
        session_start();

        // Regenerate session ID periodically
        if (!isset($_SESSION['last_regenerate'])) {
            $_SESSION['last_regenerate'] = time();
        } elseif (time() - $_SESSION['last_regenerate'] > 300) { // Every 5 minutes
            session_regenerate_id(true);
            $_SESSION['last_regenerate'] = time();
        }
    }
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    startAdminSession();

    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        return false;
    }

    // Check session timeout
    if (isset($_SESSION['last_activity'])) {
        if (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT) {
            logout();
            return false;
        }
    }

    $_SESSION['last_activity'] = time();
    return true;
}

/**
 * Require authentication (redirect if not logged in)
 */
function requireAuth() {
    if (!isLoggedIn()) {
        header('Location: /admin/index.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
        exit;
    }
}

/**
 * Login user
 */
function login($username, $password) {
    if ($username === ADMIN_USERNAME && password_verify($password, ADMIN_PASSWORD_HASH)) {
        startAdminSession();

        // Regenerate session ID on login
        session_regenerate_id(true);

        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        $_SESSION['login_time'] = time();
        $_SESSION['last_activity'] = time();
        $_SESSION['last_regenerate'] = time();

        return true;
    }

    return false;
}

/**
 * Logout user
 */
function logout() {
    startAdminSession();

    $_SESSION = [];

    if (isset($_COOKIE[SESSION_NAME])) {
        setcookie(SESSION_NAME, '', time() - 3600, '/');
    }

    session_destroy();
}

/**
 * Generate CSRF token
 */
function generateCsrfToken() {
    startAdminSession();

    if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
        $_SESSION[CSRF_TOKEN_NAME . '_time'] = time();
    }

    return $_SESSION[CSRF_TOKEN_NAME];
}

/**
 * Validate CSRF token
 */
function validateCsrfToken($token) {
    startAdminSession();

    if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
        return false;
    }

    // Check token expiry
    if (isset($_SESSION[CSRF_TOKEN_NAME . '_time'])) {
        if (time() - $_SESSION[CSRF_TOKEN_NAME . '_time'] > CSRF_TOKEN_EXPIRE) {
            unset($_SESSION[CSRF_TOKEN_NAME]);
            unset($_SESSION[CSRF_TOKEN_NAME . '_time']);
            return false;
        }
    }

    return hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

/**
 * Get CSRF input field
 */
function getCsrfField() {
    $token = generateCsrfToken();
    return '<input type="hidden" name="' . CSRF_TOKEN_NAME . '" value="' . htmlspecialchars($token) . '">';
}
?>
