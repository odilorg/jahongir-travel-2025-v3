<?php
/**
 * Logout Handler
 */

require_once 'config.php';
require_once 'includes/auth.php';

logout();

header('Location: index.php');
exit;
?>
