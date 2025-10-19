<?php
/**
 * Error Monitoring Dashboard
 */
$title = 'Error Monitoring - ' . (defined('APP_NAME') ? APP_NAME . ' Admin' : 'ChatCart Web Admin');
ob_start();
?>
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Error Monitoring</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="refreshLogs()">
                    <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearLogs()">
                    <i class="bi bi-trash me-1"></i>Clear Logs
                </button>
            </div>
        </div>
    </div>

    <!-- Error Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-danger mb-3">
                <div class="card-header">Critical Errors</div>
                <div class="card-body">
                    <h5 class="card-title" id="critical-errors-count">0</h5>
                    <p class="card-text">Today</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Errors</div>
                <div class="card-body">
                    <h5 class="card-title" id="errors-count">0</h5>
                    <p class="card-text">Today</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-header">Warnings</div>
                <div class="card-body">
                    <h5 class="card-title" id="warnings-count">0</h5>
                    <p class="card-text">Today</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-secondary mb-3">
                <div class="card-header">Navigation Errors</div>
                <div class="card-body">
                    <h5 class="card-title" id="navigation-errors-count">0</h5>
                    <p class="card-text">Today</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Log Files -->
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-exclamation-circle me-2"></i>Error Log</span>
                    <button class="btn btn-sm btn-outline-primary" onclick="loadLog('error')">Refresh</button>
                </div>
                <div class="card-body">
                    <pre id="error-log" class="bg-dark text-light p-3" style="height: 300px; overflow-y: auto; font-size: 0.8rem;"><?php
                        $errorLog = file_exists(__DIR__ . '/../../../../logs/error.log') ? file_get_contents(__DIR__ . '/../../../../logs/error.log') : 'No errors logged.';
                        echo htmlspecialchars($errorLog);
                    ?></pre>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-signpost me-2"></i>Navigation Log</span>
                    <button class="btn btn-sm btn-outline-primary" onclick="loadLog('navigation')">Refresh</button>
                </div>
                <div class="card-body">
                    <pre id="navigation-log" class="bg-dark text-light p-3" style="height: 300px; overflow-y: auto; font-size: 0.8rem;"><?php
                        $navLog = file_exists(__DIR__ . '/../../../../logs/navigation.log') ? file_get_contents(__DIR__ . '/../../../../logs/navigation.log') : 'No navigation errors logged.';
                        echo htmlspecialchars($navLog);
                    ?></pre>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-database me-2"></i>Query Log</span>
                    <button class="btn btn-sm btn-outline-primary" onclick="loadLog('query')">Refresh</button>
                </div>
                <div class="card-body">
                    <pre id="query-log" class="bg-dark text-light p-3" style="height: 300px; overflow-y: auto; font-size: 0.8rem;"><?php
                        $queryLog = file_exists(__DIR__ . '/../../../../logs/query.log') ? file_get_contents(__DIR__ . '/../../../../logs/query.log') : 'No queries logged.';
                        echo htmlspecialchars($queryLog);
                    ?></pre>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-person me-2"></i>Admin Log</span>
                    <button class="btn btn-sm btn-outline-primary" onclick="loadLog('admin')">Refresh</button>
                </div>
                <div class="card-body">
                    <pre id="admin-log" class="bg-dark text-light p-3" style="height: 300px; overflow-y: auto; font-size: 0.8rem;"><?php
                        $adminLog = file_exists(__DIR__ . '/../../../../logs/admin.log') ? file_get_contents(__DIR__ . '/../../../../logs/admin.log') : 'No admin access logged.';
                        echo htmlspecialchars($adminLog);
                    ?></pre>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Function to refresh logs
    function refreshLogs() {
        loadLog('error');
        loadLog('navigation');
        loadLog('query');
        loadLog('admin');
        updateStatistics();
    }

    // Function to load a specific log
    function loadLog(logType) {
        fetch(`<?= site_url('admin/error-monitoring/log/') ?>${logType}`)
            .then(response => response.text())
            .then(data => {
                document.getElementById(`${logType}-log`).textContent = data;
            })
            .catch(error => {
                console.error('Error loading log:', error);
            });
    }

    // Function to update statistics
    function updateStatistics() {
        // In a real implementation, this would fetch actual statistics from the server
        // For now, we'll just show placeholder values
        document.getElementById('critical-errors-count').textContent = '0';
        document.getElementById('errors-count').textContent = '0';
        document.getElementById('warnings-count').textContent = '0';
        document.getElementById('navigation-errors-count').textContent = '0';
    }

    // Function to clear logs
    function clearLogs() {
        if (confirm('Are you sure you want to clear all logs? This action cannot be undone.')) {
            fetch('<?= site_url('admin/error-monitoring/clear') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Logs cleared successfully');
                    refreshLogs();
                } else {
                    alert('Error clearing logs: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error clearing logs:', error);
                alert('Error clearing logs');
            });
        }
    }

    // Auto-refresh logs every 30 seconds
    setInterval(refreshLogs, 30000);
</script>
<?php
$content = ob_get_clean();

// Add Bootstrap Icons CSS
$additional_css = '<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">';

include __DIR__ . '/../../../core/shared/layouts/main.php';
?>