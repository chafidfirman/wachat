<?php
$title = 'Notifications & Activity Logs - ChatCart Web';
ob_start();
?>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('admin/dashboard') ?>">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('admin/products') ?>">
                            <i class="fas fa-boxes"></i> Products
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('admin/categories') ?>">
                            <i class="fas fa-tags"></i> Categories
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('admin/orders') ?>">
                            <i class="fas fa-shopping-cart"></i> Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('admin/users') ?>">
                            <i class="fas fa-users"></i> Users
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= site_url('admin/notifications') ?>">
                            <i class="fas fa-bell"></i> Notifications
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('admin/settings') ?>">
                            <i class="fas fa-cog"></i> Settings
                        </a>
                    </li>
                </ul>
                
                <hr>
                
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('admin/logout') ?>">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        
        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Notifications & Activity Logs</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <button type="button" class="btn btn-sm btn-outline-danger" id="clearAll">
                        <i class="fas fa-trash"></i> Clear All
                    </button>
                </div>
            </div>
            
            <div class="row">
                <!-- Notifications Panel -->
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Recent Notifications</h6>
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                <a href="#" class="list-group-item list-group-item-action">
                                    <div class="row g-0 align-items-center">
                                        <div class="col-1">
                                            <i class="fas fa-exclamation-circle text-warning"></i>
                                        </div>
                                        <div class="col-11">
                                            <div class="text-dark">Low stock alert for "Smartphone XYZ"</div>
                                            <div class="small text-muted">2 hours ago</div>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action">
                                    <div class="row g-0 align-items-center">
                                        <div class="col-1">
                                            <i class="fas fa-shopping-cart text-success"></i>
                                        </div>
                                        <div class="col-11">
                                            <div class="text-dark">New order #ORD-001 received</div>
                                            <div class="small text-muted">5 hours ago</div>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action">
                                    <div class="row g-0 align-items-center">
                                        <div class="col-1">
                                            <i class="fas fa-user-plus text-info"></i>
                                        </div>
                                        <div class="col-11">
                                            <div class="text-dark">New user "Jane Smith" registered</div>
                                            <div class="small text-muted">1 day ago</div>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action">
                                    <div class="row g-0 align-items-center">
                                        <div class="col-1">
                                            <i class="fas fa-edit text-primary"></i>
                                        </div>
                                        <div class="col-11">
                                            <div class="text-dark">Product "Laptop ABC" updated</div>
                                            <div class="small text-muted">2 days ago</div>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action">
                                    <div class="row g-0 align-items-center">
                                        <div class="col-1">
                                            <i class="fas fa-comment-dollar text-success"></i>
                                        </div>
                                        <div class="col-11">
                                            <div class="text-dark">Payment received for order #ORD-002</div>
                                            <div class="small text-muted">3 days ago</div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            
                            <!-- Pagination -->
                            <nav aria-label="Notifications pagination" class="mt-3">
                                <ul class="pagination justify-content-center">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">Next</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
                
                <!-- Activity Logs Panel -->
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Activity Logs</h6>
                            <button class="btn btn-sm btn-outline-primary" id="exportLogs">
                                <i class="fas fa-file-export"></i> Export
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Time</th>
                                            <th>User</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>2025-10-10 14:30:00</td>
                                            <td>admin</td>
                                            <td>Created new product "Smartphone XYZ"</td>
                                        </tr>
                                        <tr>
                                            <td>2025-10-10 13:45:00</td>
                                            <td>admin</td>
                                            <td>Updated order #ORD-001 status to "Processing"</td>
                                        </tr>
                                        <tr>
                                            <td>2025-10-10 12:15:00</td>
                                            <td>staff</td>
                                            <td>Added new category "Electronics"</td>
                                        </tr>
                                        <tr>
                                            <td>2025-10-10 11:30:00</td>
                                            <td>admin</td>
                                            <td>Updated store settings</td>
                                        </tr>
                                        <tr>
                                            <td>2025-10-10 10:45:00</td>
                                            <td>admin</td>
                                            <td>Deleted product "Old Product"</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination -->
                            <nav aria-label="Logs pagination" class="mt-3">
                                <ul class="pagination justify-content-center">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">Next</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
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

.list-group-item:first-child {
    border-top-left-radius: 0.375rem;
    border-top-right-radius: 0.375rem;
}

.list-group-item:last-child {
    border-bottom-right-radius: 0.375rem;
    border-bottom-left-radius: 0.375rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Clear all notifications
    document.getElementById('clearAll').addEventListener('click', function() {
        if (confirm('Are you sure you want to clear all notifications?')) {
            // In a real implementation, you would clear all notifications
            alert('All notifications cleared!');
        }
    });
    
    // Export logs
    document.getElementById('exportLogs').addEventListener('click', function() {
        alert('Exporting activity logs...');
        // In a real implementation, you would export the logs to a file
    });
});
</script>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../../core/shared/layouts/main.php';
?>