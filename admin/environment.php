<?php
/**
 * Environment Management Interface
 * Allows administrators to switch between environments
 */

// Include configuration
require_once __DIR__ . '/../config.php';

// Check if user is admin (in a real implementation, you would check proper authentication)
$isAdmin = true; // For demonstration purposes

if (!$isAdmin) {
    header('HTTP/1.0 403 Forbidden');
    die('Access denied');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['environment'])) {
    $newEnvironment = $_POST['environment'];
    
    // Validate environment
    if (in_array($newEnvironment, ['development', 'staging', 'production'])) {
        // Read the current config file
        $configFile = __DIR__ . '/../config.php';
        $configContent = file_get_contents($configFile);
        
        // Replace the environment setting
        $pattern = "/define\('ENVIRONMENT', '.*?'\);/";
        $replacement = "define('ENVIRONMENT', '{$newEnvironment}');";
        $newConfigContent = preg_replace($pattern, $replacement, $configContent);
        
        // Write the updated config file
        if (file_put_contents($configFile, $newConfigContent) !== false) {
            $message = "Environment successfully set to: {$newEnvironment}";
            $success = true;
            
            // Reload the configuration
            require_once $configFile;
        } else {
            $message = "Error: Failed to update config.php";
            $success = false;
        }
    } else {
        $message = "Error: Invalid environment";
        $success = false;
    }
}

// Get current environment
$currentEnvironment = defined('ENVIRONMENT') ? ENVIRONMENT : 'not set';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Environment Management - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Environment Management</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($message)): ?>
                            <div class="alert alert-<?php echo $success ? 'success' : 'danger'; ?>">
                                <?php echo htmlspecialchars($message); ?>
                            </div>
                        <?php endif; ?>
                        
                        <p><strong>Current Environment:</strong> <?php echo htmlspecialchars($currentEnvironment); ?></p>
                        <p><strong>Debug Mode:</strong> <?php echo defined('DEBUG_MODE') && DEBUG_MODE ? 'ENABLED' : 'DISABLED'; ?></p>
                        <p><strong>Display Errors:</strong> <?php echo defined('DISPLAY_ERRORS') && DISPLAY_ERRORS ? 'ENABLED' : 'DISABLED'; ?></p>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Select Environment:</label>
                                <select name="environment" class="form-select">
                                    <option value="development" <?php echo $currentEnvironment === 'development' ? 'selected' : ''; ?>>Development</option>
                                    <option value="staging" <?php echo $currentEnvironment === 'staging' ? 'selected' : ''; ?>>Staging</option>
                                    <option value="production" <?php echo $currentEnvironment === 'production' ? 'selected' : ''; ?>>Production</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Set Environment</button>
                        </form>
                        
                        <div class="mt-4">
                            <h5>Environment Descriptions:</h5>
                            <ul>
                                <li><strong>Development:</strong> Debug mode enabled, errors displayed, detailed logging</li>
                                <li><strong>Staging:</strong> Debug mode disabled, errors logged but not displayed</li>
                                <li><strong>Production:</strong> Debug mode disabled, errors logged but not displayed, optimized for performance</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>