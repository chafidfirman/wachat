<?php
$title = 'Order Management - ChatCart Web';
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
                        <a class="nav-link active" href="<?= site_url('admin/orders') ?>">
                            <i class="fas fa-shopping-cart"></i> Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('admin/users') ?>">
                            <i class="fas fa-users"></i> Users
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
                <h1 class="h2">Order Management</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <button type="button" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
            </div>
            
            <!-- Search and Filter Section -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <input type="text" class="form-control" id="searchInput" placeholder="Search orders...">
                        </div>
                        <div class="col-md-2 mb-3">
                            <select class="form-select" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="processing">Processing</option>
                                <option value="shipped">Shipped</option>
                                <option value="delivered">Delivered</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <input type="date" class="form-control" id="startDate" placeholder="Start Date">
                        </div>
                        <div class="col-md-2 mb-3">
                            <input type="date" class="form-control" id="endDate" placeholder="End Date">
                        </div>
                        <div class="col-md-2 mb-3">
                            <button class="btn btn-primary w-100" id="filterBtn">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                        </div>
                        <div class="col-md-1 mb-3">
                            <button class="btn btn-outline-secondary w-100" id="resetBtn">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Orders Table -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Order List</h5>
                    <div>
                        <span class="badge bg-primary">Total: <?php echo count($orders ?? []); ?></span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($orders) && is_array($orders)): ?>
                                    <?php foreach ($orders as $order): ?>
                                        <tr>
                                            <td>
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#orderModal" data-id="<?php echo $order['id']; ?>">
                                                    #<?php echo htmlspecialchars($order['id']); ?>
                                                </a>
                                            </td>
                                            <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                            <td><?php echo htmlspecialchars($order['date']); ?></td>
                                            <td><?php echo htmlspecialchars($order['items_count']); ?></td>
                                            <td>Rp <?php echo number_format($order['total'], 0, ',', '.'); ?></td>
                                            <td>
                                                <?php
                                                $statusClass = '';
                                                switch ($order['status']) {
                                                    case 'pending':
                                                        $statusClass = 'bg-warning';
                                                        break;
                                                    case 'processing':
                                                        $statusClass = 'bg-info';
                                                        break;
                                                    case 'shipped':
                                                        $statusClass = 'bg-primary';
                                                        break;
                                                    case 'delivered':
                                                        $statusClass = 'bg-success';
                                                        break;
                                                    case 'cancelled':
                                                        $statusClass = 'bg-danger';
                                                        break;
                                                    default:
                                                        $statusClass = 'bg-secondary';
                                                }
                                                ?>
                                                <span class="badge <?php echo $statusClass; ?>">
                                                    <?php echo ucfirst(htmlspecialchars($order['status'])); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-sm btn-outline-primary view-order" data-bs-toggle="modal" data-bs-target="#orderModal" data-id="<?php echo $order['id']; ?>">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-success update-status" data-id="<?php echo $order['id']; ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No orders found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <nav aria-label="Order pagination">
                        <ul class="pagination justify-content-center">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Order Modal -->
<div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderModalLabel">Order Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Order Information</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Order ID:</strong></td>
                                <td>#ORD-001</td>
                            </tr>
                            <tr>
                                <td><strong>Date:</strong></td>
                                <td>2025-10-10 14:30:00</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td><span class="badge bg-warning">Pending</span></td>
                            </tr>
                            <tr>
                                <td><strong>Total:</strong></td>
                                <td>Rp 5.000.000</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Customer Information</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Name:</strong></td>
                                <td>John Doe</td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>john.doe@example.com</td>
                            </tr>
                            <tr>
                                <td><strong>Phone:</strong></td>
                                <td>+62 812 3456 7890</td>
                            </tr>
                            <tr>
                                <td><strong>Address:</strong></td>
                                <td>Jl. Merdeka No. 123, Jakarta</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <h6>Order Items</h6>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Smartphone XYZ</td>
                                <td>Rp 5.000.000</td>
                                <td>1</td>
                                <td>Rp 5.000.000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <h6>Order Status History</h6>
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-badge bg-warning"></div>
                        <div class="timeline-panel">
                            <div class="timeline-heading">
                                <h6 class="timeline-title">Order Placed</h6>
                                <p class="text-muted mb-0"><small>2025-10-10 14:30:00</small></p>
                            </div>
                            <div class="timeline-body">
                                <p>Order has been placed by customer.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary update-order-status">Update Status</button>
            </div>
        </div>
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

.table th {
    background-color: #f8f9fa;
    border-top: none;
}

.timeline {
    position: relative;
    padding: 20px 0;
    list-style: none;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-badge {
    position: absolute;
    top: 0;
    left: 15px;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    z-index: 100;
}

.timeline-panel {
    margin-left: 60px;
    padding: 10px;
    border: 1px solid #e9ecef;
    border-radius: 5px;
    background-color: #f8f9fa;
}
</style>

<script>
// Handle order modal
document.addEventListener('DOMContentLoaded', function() {
    const orderModal = document.getElementById('orderModal');
    
    // When the modal is shown
    orderModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget; // Button that triggered the modal
        const orderId = button.getAttribute('data-id');
        
        // In a real implementation, you would fetch the order data here
        // and populate the modal fields
        document.getElementById('orderModalLabel').textContent = 'Order Details #' + orderId;
    });
    
    // View order details
    document.querySelectorAll('.view-order').forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.getAttribute('data-id');
            // In a real implementation, you would fetch the order details
            console.log('Viewing order:', orderId);
        });
    });
    
    // Update order status
    document.querySelectorAll('.update-status').forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.getAttribute('data-id');
            // In a real implementation, you would show a status update form
            alert('Update status for order #' + orderId);
        });
    });
    
    // Filter functionality
    document.getElementById('filterBtn').addEventListener('click', function() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        
        // In a real implementation, you would filter the orders based on these criteria
        alert(`Filtering orders with:
Search: ${searchTerm}
Status: ${statusFilter}
Start Date: ${startDate}
End Date: ${endDate}`);
    });
    
    // Reset filters
    document.getElementById('resetBtn').addEventListener('click', function() {
        document.getElementById('searchInput').value = '';
        document.getElementById('statusFilter').value = '';
        document.getElementById('startDate').value = '';
        document.getElementById('endDate').value = '';
    });
    
    // Enter key in search
    document.getElementById('searchInput').addEventListener('keyup', function(event) {
        if (event.key === 'Enter') {
            document.getElementById('filterBtn').click();
        }
    });
});
</script>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../../core/shared/layouts/main.php';
?>