<?php
$title = 'Page Management - ChatCart Web';
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
                        <a class="nav-link active" href="<?= site_url('admin/pages') ?>">
                            <i class="fas fa-file-alt"></i> Pages
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
                <h1 class="h2">Page Management</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#pageModal">
                        <i class="fas fa-plus"></i> Add Page
                    </button>
                </div>
            </div>
            
            <!-- Search Section -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" id="searchInput" placeholder="Search pages...">
                        </div>
                        <div class="col-md-2 mb-3">
                            <button class="btn btn-primary w-100" id="searchBtn">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Pages Table -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Page List</h5>
                    <div>
                        <span class="badge bg-primary">Total: <?php echo count($pages ?? []); ?></span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Slug</th>
                                    <th>Status</th>
                                    <th>Last Modified</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($pages) && is_array($pages)): ?>
                                    <?php foreach ($pages as $page): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($page['title']); ?></td>
                                            <td><?php echo htmlspecialchars($page['slug']); ?></td>
                                            <td>
                                                <?php if ($page['status'] === 'published'): ?>
                                                    <span class="badge bg-success">Published</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Draft</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($page['last_modified']); ?></td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#pageModal" data-id="<?php echo $page['id']; ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-info view-page" data-id="<?php echo $page['id']; ?>">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger delete-page" data-id="<?php echo $page['id']; ?>">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No pages found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <nav aria-label="Page pagination">
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
        </main>
    </div>
</div>

<!-- Page Modal -->
<div class="modal fade" id="pageModal" tabindex="-1" aria-labelledby="pageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pageModalLabel">Add Page</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="pageForm">
                    <input type="hidden" id="pageId">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="pageTitle" class="form-label">Page Title</label>
                            <input type="text" class="form-control" id="pageTitle" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="pageSlug" class="form-label">Slug</label>
                            <input type="text" class="form-control" id="pageSlug" required>
                            <div class="form-text">The "slug" is the URL-friendly version of the title.</div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="pageContent" class="form-label">Content</label>
                        <div id="editor" style="height: 300px;"></div>
                        <textarea id="pageContent" class="d-none"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="pageStatus" class="form-label">Status</label>
                            <select class="form-select" id="pageStatus">
                                <option value="published">Published</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="pageTemplate" class="form-label">Template</label>
                            <select class="form-select" id="pageTemplate">
                                <option value="default">Default</option>
                                <option value="full-width">Full Width</option>
                                <option value="with-sidebar">With Sidebar</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="savePage">Save Page</button>
            </div>
        </div>
    </div>
</div>

<!-- Quill.js CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

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

.ql-container {
    font-size: 1rem;
}
</style>

<!-- Quill.js -->
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
// Initialize Quill editor
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Quill editor
    var quill = new Quill('#editor', {
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'indent': '-1'}, { 'indent': '+1' }],
                [{ 'align': [] }],
                ['link', 'image'],
                ['clean']
            ]
        },
        placeholder: 'Write your page content here...',
        theme: 'snow'
    });
    
    // Update hidden textarea with editor content before form submission
    document.getElementById('savePage').addEventListener('click', function() {
        document.getElementById('pageContent').value = quill.root.innerHTML;
    });
    
    const pageModal = document.getElementById('pageModal');
    const modalTitle = pageModal.querySelector('.modal-title');
    const savePageBtn = document.getElementById('savePage');
    
    // When the modal is shown
    pageModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget; // Button that triggered the modal
        const pageId = button.getAttribute('data-id');
        
        // If editing an existing page
        if (pageId) {
            modalTitle.textContent = 'Edit Page';
            // In a real implementation, you would fetch the page data here
            // and populate the form fields
        } else {
            modalTitle.textContent = 'Add Page';
            // Clear the form
            document.getElementById('pageId').value = '';
            document.getElementById('pageTitle').value = '';
            document.getElementById('pageSlug').value = '';
            document.getElementById('pageStatus').value = 'published';
            document.getElementById('pageTemplate').value = 'default';
            quill.setContents([{ insert: '\n' }]);
        }
    });
    
    // Save page
    savePageBtn.addEventListener('click', function() {
        // In a real implementation, you would send the form data to the server
        // and handle the response
        alert('Page saved successfully!');
        // Close the modal
        const modal = bootstrap.Modal.getInstance(pageModal);
        modal.hide();
    });
    
    // Delete page
    document.querySelectorAll('.delete-page').forEach(button => {
        button.addEventListener('click', function() {
            const pageId = this.getAttribute('data-id');
            if (confirm('Are you sure you want to delete this page?')) {
                // In a real implementation, you would send a request to delete the page
                alert('Page deleted successfully!');
                // Refresh the page or remove the row from the table
            }
        });
    });
    
    // View page
    document.querySelectorAll('.view-page').forEach(button => {
        button.addEventListener('click', function() {
            const pageId = this.getAttribute('data-id');
            // In a real implementation, you would redirect to the page preview
            alert('Viewing page #' + pageId);
        });
    });
    
    // Search functionality
    document.getElementById('searchBtn').addEventListener('click', function() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        // In a real implementation, you would filter the pages based on the search term
        alert(`Searching for pages with: ${searchTerm}`);
    });
    
    // Enter key in search
    document.getElementById('searchInput').addEventListener('keyup', function(event) {
        if (event.key === 'Enter') {
            document.getElementById('searchBtn').click();
        }
    });
    
    // Auto-generate slug from title
    document.getElementById('pageTitle').addEventListener('input', function() {
        const title = this.value;
        const slug = title.toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
        document.getElementById('pageSlug').value = slug;
    });
});
</script>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../../core/shared/layouts/main.php';
?>