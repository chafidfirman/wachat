<?php
$title = 'Search Results - ChatCart Web';
ob_start();
require_once __DIR__ . '/../../../core/shared/helpers/components_helper.php';
?>
<div class="container mt-4">
    <h1 class="mb-4">Search Results</h1>
    
    <form action="<?= site_url('search') ?>" method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-8">
                <input type="text" class="form-control" name="q" placeholder="Search products..." value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">Search</button>
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
                    <div class="col-md-4 mb-4">
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