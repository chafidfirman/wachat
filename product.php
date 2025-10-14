<?php
// Redirect to the proper product route
require_once __DIR__ . '/config.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    // Redirect to the public directory route
    header("Location: " . site_url("product/$id"));
    exit;
} else {
    // If no ID is provided, redirect to homepage
    header("Location: " . site_url());
    exit;
}
?>