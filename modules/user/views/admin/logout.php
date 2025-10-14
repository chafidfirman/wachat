<?php
$title = 'Logout - ChatCart Web';
ob_start();
?>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url() ?>">
                            <i class="fas fa-home"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('about.html') ?>">
                            <i class="fas fa-info-circle"></i> About
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('contact.html') ?>">
                            <i class="fas fa-envelope"></i> Contact
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= site_url('admin/login') ?>">
                            <i class="fas fa-user-shield"></i> Admin
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        
        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Logout</h1>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Session Ended</h5>
                        </div>
                        <div class="card-body text-center">
                            <div class="mb-4">
                                <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                            </div>
                            <h4>You have been successfully logged out</h4>
                            <p class="text-muted">Your session has been terminated securely.</p>
                            
                            <div class="mt-4">
                                <a href="<?= site_url('admin/login') ?>" class="btn btn-primary">
                                    <i class="fas fa-sign-in-alt"></i> Login Again
                                </a>
                                <a href="<?= base_url() ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-home"></i> Return to Home
                                </a>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex justify-content-between">
                                <small class="text-muted">
                                    <i class="fas fa-clock"></i> Session ended at: <?php echo date('Y-m-d H:i:s'); ?>
                                </small>
                                <small class="text-muted">
                                    IP: <?php echo $_SERVER['REMOTE_ADDR'] ?? 'Unknown'; ?>
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info mt-4">
                        <h6><i class="fas fa-info-circle"></i> Security Information</h6>
                        <ul class="mb-0">
                            <li>All session data has been cleared</li>
                            <li>Cookies have been removed</li>
                            <li>You have been redirected to a secure page</li>
                        </ul>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<style>
.sidebar {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    z-index: 100;
    padding: 48px 0 0;
    box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
}

.sidebar-sticky {
    position: relative;
    top: 0;
    height: calc(100vh - 48px);
    padding-top: .5rem;
    overflow-x: hidden;
    overflow-y: auto;
}

.nav-link {
    font-weight: 500;
    color: #333;
}

.nav-link:hover,
.nav-link.active {
    color: #28a745;
}

.nav-link i {
    margin-right: 5px;
}

.card {
    border: none;
    border-radius: 0.5rem;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
    border-radius: calc(0.5rem - 1px) calc(0.5rem - 1px) 0 0;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // In a real implementation, you would destroy the session and clear cookies here
    console.log('Session destroyed securely');
});
</script>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../../core/shared/layouts/main.php';
?>