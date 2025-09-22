<?php
// Simple test file to check if PHP is working
echo "PHP is working!<br>";
echo "Current time: " . date('Y-m-d H:i:s') . "<br>";
echo "PHP Version: " . phpversion() . "<br>";

// Test if we can include the header
echo "<br>Testing header include...<br>";
try {
    $title = "Test Page";
    include 'includes/header.php';
    echo "Header included successfully!<br>";
} catch (Exception $e) {
    echo "Error including header: " . $e->getMessage() . "<br>";
}

echo "<br>Test completed!";
?>

