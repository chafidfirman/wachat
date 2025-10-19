<?php
/**
 * 404 Error Page
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found - <?php echo htmlspecialchars(APP_NAME ?? 'ChatCart Web'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .error-container {
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-card {
            max-width: 600px;
            width: 100%;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .error-icon {
            font-size: 4rem;
            color: #ffc107;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="card error-card">
            <div class="card-body text-center">
                <div class="error-icon mb-4">🔍</div>
                <h1 class="card-title">Page Not Found</h1>
                <p class="card-text">
                    <?php if (isset($error) && !empty($error)): ?>
                        <?php echo htmlspecialchars($error); ?>
                    <?php else: ?>
                        The page you're looking for doesn't exist or has been moved.
                    <?php endif; ?>
                </p>
                <div class="mt-4">
                    <a href="<?php echo base_url(); ?>" class="btn btn-primary">Go to Homepage</a>
                    <button onclick="history.back()" class="btn btn-secondary">Go Back</button>
                </div>
                <?php if (defined('DEBUG_MODE') && DEBUG_MODE && isset($_GET['debug']) && $_GET['debug'] === 'true'): ?>
                    <div class="mt-4 p-3 bg-light rounded">
                        <h5>Debug Information:</h5>
                        <pre><?php echo isset($debugInfo) ? htmlspecialchars(print_r($debugInfo, true)) : 'No debug information available'; ?></pre>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>