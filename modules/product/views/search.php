<?php
$title = 'Search Results - ChatCart Web';
ob_start();
require_once __DIR__ . '/../../../core/shared/helpers/components_helper.php';
?>
<div class="container mt-4">
    <h1 class="mb-4">Search Products</h1>
    
    <form action="<?= site_url('search') ?>" method="GET" class="mb-4">
        <div class="row g-3">
            <div class="col-md-6">
                <input type="text" class="form-control" name="q" placeholder="Search products..." value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
            </div>
            <div class="col-md-4">
                <select class="form-select" name="category">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>" <?php echo (isset($_GET['category']) && $_GET['category'] == $category['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Search</button>
            </div>
        </div>
        
        <!-- Advanced Filters -->
        <div class="row g-3 mt-2">
            <div class="col-md-3">
                <label for="min_price" class="form-label">Min Price</label>
                <input type="number" class="form-control" name="min_price" id="min_price" placeholder="Min Price" value="<?php echo isset($_GET['min_price']) ? htmlspecialchars($_GET['min_price']) : ''; ?>" min="0" step="1000">
            </div>
            <div class="col-md-3">
                <label for="max_price" class="form-label">Max Price</label>
                <input type="number" class="form-control" name="max_price" id="max_price" placeholder="Max Price" value="<?php echo isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : ''; ?>" min="0" step="1000">
            </div>
            <div class="col-md-3">
                <label for="in_stock" class="form-label">Stock Status</label>
                <select class="form-select" name="in_stock" id="in_stock">
                    <option value="">All</option>
                    <option value="1" <?php echo (isset($_GET['in_stock']) && $_GET['in_stock'] == '1') ? 'selected' : ''; ?>>In Stock</option>
                    <option value="0" <?php echo (isset($_GET['in_stock']) && $_GET['in_stock'] == '0') ? 'selected' : ''; ?>>Out of Stock</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="sort" class="form-label">Sort By</label>
                <select class="form-select" name="sort" id="sort">
                    <option value="id" <?php echo (!isset($_GET['sort']) || $_GET['sort'] == 'id') ? 'selected' : ''; ?>>Default</option>
                    <option value="name" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'name') ? 'selected' : ''; ?>>Name</option>
                    <option value="price" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price') ? 'selected' : ''; ?>>Price</option>
                </select>
            </div>
        </div>
        
        <div class="row g-3 mt-2">
            <div class="col-md-3">
                <select class="form-select" name="order">
                    <option value="asc" <?php echo (!isset($_GET['order']) || $_GET['order'] == 'asc') ? 'selected' : ''; ?>>Ascending</option>
                    <option value="desc" <?php echo (isset($_GET['order']) && $_GET['order'] == 'desc') ? 'selected' : ''; ?>>Descending</option>
                </select>
            </div>
        </div>
    </form>
    
    <?php if (isset($_GET['q']) && !empty($_GET['q'])): ?>
        <div class="mb-4">
            <h3>Search results for: "<?php echo htmlspecialchars($_GET['q']); ?>"</h3>
            <p>Found <?php echo count($products); ?> products</p>
        </div>
        
        <?php if (!empty($categories)): ?>
            <div class="mb-4">
                <h5>Filter by Category:</h5>
                <div class="btn-group flex-wrap" role="group">
                    <a href="<?= site_url('search') ?>?q=<?php echo urlencode($_GET['q']); ?>" class="btn btn-outline-secondary m-1 <?php echo !isset($_GET['category']) ? 'active' : ''; ?>">All</a>
                    <?php foreach ($categories as $category): ?>
                        <a href="<?= site_url('search') ?>?q=<?php echo urlencode($_GET['q']); ?>&category=<?php echo $category['id']; ?>" class="btn btn-outline-secondary m-1 <?php echo (isset($_GET['category']) && $_GET['category'] == $category['id']) ? 'active' : ''; ?>">
                            <?php echo htmlspecialchars($category['name']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if (empty($products)): ?>
            <div class="alert alert-info">
                <h4>No products found</h4>
                <p>Try different search terms or browse our categories.</p>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($products as $product): ?>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-4">
                        <?php renderProductCard($product); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="alert alert-info">
            <h4>Search our products</h4>
            <p>Enter a search term above to find products in our catalog.</p>
        </div>
        
        <?php if (!empty($categories)): ?>
            <div class="mb-4">
                <h5>Browse by Category:</h5>
                <div class="btn-group flex-wrap" role="group">
                    <?php foreach ($categories as $category): ?>
                        <a href="<?= site_url('search') ?>?category=<?php echo $category['id']; ?>" class="btn btn-outline-secondary m-1">
                            <?php echo htmlspecialchars($category['name']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../../core/shared/layouts/main.php';
?>