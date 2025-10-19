<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'ChatCart Web'; ?></title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Component CSS -->
    <?php echo link_css('components.css'); ?>
    <!-- Additional CSS -->
    <?php if (isset($additional_css)) echo $additional_css; ?>
</head>
<body>
    <!-- Header -->
    <?php include __DIR__ . '/../components/header.php'; ?>

    <!-- Main Content -->
    <main>
        <?php echo $content; ?>
    </main>

    <!-- Footer -->
    <?php include __DIR__ . '/../components/footer.php'; ?>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <?php echo script_js('script.js'); ?>
    <?php echo script_js('public-script.js'); ?>
    
    <!-- Debug Overlay -->
    <?php 
    // Include debug helper if not already included
    if (!function_exists('displayDebugOverlay')) {
        require_once __DIR__ . '/../helpers/debug_helper.php';
    }
    displayDebugOverlay(); ?>
</body>
</html>