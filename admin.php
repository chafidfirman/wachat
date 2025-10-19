<?php
// Redirect to the proper admin login route
require_once __DIR__ . '/config.php';
header('Location: ' . base_url('admin/login'));
exit;
?>