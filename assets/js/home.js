// Function to create product card HTML with badges
function createProductCardWithBadge(product, badgeType) {
    // Use default image if no image is specified or if image is empty
    const productImage = product.image && product.image.trim() !== ''
        ? product.image.startsWith('http') ? product.image : 'assets/' + product.image
        : 'assets/img/product-default.jpg';

    // Determine stock status display
    let stockStatus = '';
    let stockClass = '';
    if (!product.inStock) {
        stockStatus = 'Out of Stock';
        stockClass = 'out-of-stock';
    } else if (product.stockQuantity !== undefined && product.stockQuantity !== null) {
        if (product.stockQuantity <= 0) {
            stockStatus = 'Out of Stock';
            stockClass = 'out-of-stock';
        } else if (product.stockQuantity <= 5) {
            stockStatus = `Only ${product.stockQuantity} left!`;
            stockClass = 'low-stock';
        } else {
            stockStatus = `${product.stockQuantity} in stock`;
        }
    } else {
        stockStatus = 'In Stock';
    }

    // Determine badge text
    let badgeText = '';
    if (badgeType === 'limited') {
        badgeText = product.stockQuantity <= 3 ? `SISA ${product.stockQuantity} PCS!` : 'LIMITED STOCK!';
    } else if (badgeType === 'best-seller') {
        badgeText = 'Best Seller';
    }

    return `
        <div class="product-card">
            ${badgeText ? `<div class="product-badge ${badgeType}">${badgeText}</div>` : ''}
            <div class="product-image">
                <img src="${productImage}" alt="${product.name}" onerror="this.src='assets/img/product-default.jpg'">
            </div>
            <div class="product-info">
                <h3>${product.name}</h3>
                <div class="product-price">${formatPrice(product.price)}</div>
                <div class="product-meta">
                    <span class="${stockClass}">
                        ${stockStatus}
                    </span>
                </div>
                <div class.product-actions">
                    <a href="public/index.php?path=product/${product.id}" class="action-btn detail-btn">
                        <i class="fas fa-info-circle"></i> Detail
                    </a>
                    <button class="action-btn whatsapp-btn ${!product.inStock || (product.stockQuantity !== null && product.stockQuantity <= 0) ? 'out-of-stock-btn' : ''}"
                            onclick="orderViaWhatsApp(${product.id})"
                            ${!product.inStock || (product.stockQuantity !== null && product.stockQuantity <= 0) ? 'disabled' : ''}>
                        <i class="fab fa-whatsapp"></i> Chat & Beli
                    </button>
                </div>
            </div>
        </div>
    `;
}

// Function to render limited stock products
function renderLimitedProducts(productsToShow) {
    const productGrid = document.getElementById('limitedProducts');
    if (productsToShow.length === 0) {
        productGrid.innerHTML = '<p>Tidak ada produk limited saat ini.</p>';
        return;
    }

    productGrid.innerHTML = productsToShow.map(product => createProductCardWithBadge(product, 'limited')).join('');
}

// Function to render best seller products
function renderBestSellerProducts(productsToShow) {
    const productGrid = document.getElementById('bestSellerProducts');
    if (productsToShow.length === 0) {
        productGrid.innerHTML = '<p>Tidak ada produk terlaris saat ini.</p>';
        return;
    }

    productGrid.innerHTML = productsToShow.map(product => createProductCardWithBadge(product, 'best-seller')).join('');
}

// Function to render all products
function renderAllProducts(productsToShow) {
    const productGrid = document.getElementById('allProducts');
    if (productsToShow.length === 0) {
        productGrid.innerHTML = '<p>Tidak ada produk tersedia.</p>';
        return;
    }

    productGrid.innerHTML = productsToShow.map(product => createProductCardWithBadge(product, '')).join('');
}

// Function to fetch and display products
async function loadProducts() {
    try {
        const response = await fetch('api/v1/products');
        const result = await response.json();

        if (result.success) {
            const allProducts = result.data;

            // Filter limited stock products (stock <= 5)
            const limitedProducts = allProducts.filter(product =>
                product.stockQuantity !== null &&
                product.stockQuantity <= 5 &&
                product.stockQuantity > 0
            ).slice(0, 4); // Limit to 4 products

            // Filter best seller products (you can define your own logic here)
            const bestSellerProducts = allProducts.filter(product =>
                product.category_id == 1 // Example: products from category 1
            ).slice(0, 4); // Limit to 4 products

            // Render products
            renderLimitedProducts(limitedProducts);
            renderBestSellerProducts(bestSellerProducts);
            renderAllProducts(allProducts);
        } else {
            document.getElementById('limitedProducts').innerHTML = '<p>Error loading products.</p>';
            document.getElementById('bestSellerProducts').innerHTML = '<p>Error loading products.</p>';
            document.getElementById('allProducts').innerHTML = '<p>Error loading products.</p>';
        }
    } catch (error) {
        console.error('Error fetching products:', error);
        document.getElementById('limitedProducts').innerHTML = '<p>Error loading products.</p>';
        document.getElementById('bestSellerProducts').innerHTML = '<p>Error loading products.</p>';
        document.getElementById('allProducts').innerHTML = '<p>Error loading products.</p>';
    }
}

// Load products when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadProducts();
});
