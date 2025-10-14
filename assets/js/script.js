// Sample product data - in a real application, this would come from a database
let products = [];
let categories = [];

// Function to format price in IDR
function formatPrice(price) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
    }).format(price);
}

// Function to generate WhatsApp message
function generateWhatsAppMessage(product, quantity = 1) {
    const totalPrice = product.price * quantity;
    return encodeURIComponent(`Halo, saya ingin memesan ${product.name} sebanyak ${quantity} pcs dengan total harga ${formatPrice(totalPrice)}. Apakah masih tersedia?`);
}

// Function to create product card HTML
function createProductCard(product) {
    // Use default image if no image is specified or if image is empty
    const productImage = product.image && product.image.trim() !== '' 
        ? product.image 
        : 'img/product-default.jpg';
        
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
        
    return `
        <div class="product-card">
            <div class="product-image">
                <img src="${productImage}" alt="${product.name}" onerror="this.src='img/product-default.jpg'">
            </div>
            <div class="product-info">
                <h3>${product.name}</h3>
                <div class="product-price">${formatPrice(product.price)}</div>
                <div class="product-meta">
                    <span class="${stockClass}">
                        ${stockStatus}
                    </span>
                </div>
                <div class="product-actions">
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

// Function to show product detail in modal
function showProductDetail(productId) {
    // Fetch products from API to get the latest data
    fetchProducts().then(fetchedProducts => {
        const product = fetchedProducts.find(p => p.id === productId);
        if (!product) {
            console.error('Product not found');
            return;
        }
        
        // Create modal HTML
        const modal = document.createElement('div');
        modal.className = 'modal';
        modal.id = 'productModal';
        
        // Use default image if no image is specified or if image is empty
        const productImage = product.image && product.image.trim() !== '' 
            ? product.image 
            : 'img/product-default.jpg';
        
        // Determine stock status display for modal
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
                stockClass = 'in-stock';
            }
        } else {
            stockStatus = 'In Stock';
            stockClass = 'in-stock';
        }
        
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Product Details</h2>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <div class="modal-product-image">
                        <img src="${productImage}" alt="${product.name}" onerror="this.src='img/product-default.jpg'">
                    </div>
                    <div class="modal-product-category">${product.category}</div>
                    <h2 class="modal-product-name">${product.name}</h2>
                    <div class="modal-product-price">${formatPrice(product.price)}</div>
                    <p class="modal-product-description">${product.description}</p>
                    <div class="modal-product-stock ${stockClass}">
                        Stock Status: ${stockStatus}
                    </div>
                    <div class="modal-actions">
                        <a href="https://wa.me/${product.whatsappNumber}?text=${generateWhatsAppMessage(product)}" 
                           target="_blank" class="whatsapp-btn">
                            <i class="fab fa-whatsapp"></i> Pesan via WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        `;
        
        // Remove any existing modal
        const existingModal = document.getElementById('productModal');
        if (existingModal) {
            document.body.removeChild(existingModal);
        }
        
        // Add modal to body
        document.body.appendChild(modal);
        
        // Add event listeners
        const closeBtn = modal.querySelector('.close');
        closeBtn.onclick = function() {
            document.body.removeChild(modal);
        }
        
        window.onclick = function(event) {
            if (event.target === modal) {
                document.body.removeChild(modal);
            }
        }
    }).catch(error => {
        console.error('Error fetching product details:', error);
    });
}

// Function to order via WhatsApp
function orderViaWhatsApp(productId) {
    // Fetch products from API to get the latest data
    fetchProducts().then(fetchedProducts => {
        const product = fetchedProducts.find(p => p.id === productId);
        if (product) {
            const message = generateWhatsAppMessage(product);
            const whatsappUrl = `https://wa.me/${product.whatsappNumber}?text=${message}`;
            window.open(whatsappUrl, '_blank');
        }
    }).catch(error => {
        console.error('Error fetching product details:', error);
    });
}

// Function to render products
function renderProducts(productsToShow) {
    const productGrid = document.getElementById('productGrid');
    if (productsToShow.length === 0) {
        productGrid.innerHTML = '<p>No products found.</p>';
        return;
    }
    
    productGrid.innerHTML = productsToShow.map(product => createProductCard(product)).join('');
}

// Function to populate category filter
function populateCategoryFilter() {
    const categoryFilter = document.getElementById('categoryFilter');
    // Clear existing options
    categoryFilter.innerHTML = '';
    
    // Add "all" option
    const allOption = document.createElement('option');
    allOption.value = 'all';
    allOption.textContent = 'All Categories';
    categoryFilter.appendChild(allOption);
    
    // Add unique categories
    const uniqueCategories = [...new Set(products.map(product => product.category))];
    uniqueCategories.forEach(category => {
        const option = document.createElement('option');
        option.value = category;
        option.textContent = category;
        categoryFilter.appendChild(option);
    });
}

// Function to filter products
function filterProducts() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const selectedCategory = document.getElementById('categoryFilter').value;
    
    let filteredProducts = products;
    
    // Filter by search term
    if (searchTerm) {
        filteredProducts = filteredProducts.filter(product => 
            product.name.toLowerCase().includes(searchTerm) || 
            product.description.toLowerCase().includes(searchTerm)
        );
    }
    
    // Filter by category
    if (selectedCategory !== 'all') {
        filteredProducts = filteredProducts.filter(product => 
            product.category === selectedCategory
        );
    }
    
    renderProducts(filteredProducts);
}

// Function to fetch products from API
async function fetchProducts() {
    try {
        const response = await fetch('api/products.php');
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        return Array.isArray(data) ? data : [];
    } catch (error) {
        console.error('Error fetching products:', error);
        return [];
    }
}

// Function to load products from API
async function loadProducts() {
    // Fetch products from API
    products = await fetchProducts();
    
    // Extract unique categories
    categories = [...new Set(products.map(product => product.category))];
    
    // Render products and populate filters
    renderProducts(products);
    populateCategoryFilter();
}

// Function to toggle mobile menu
function toggleMobileMenu() {
    // Handle old navigation structure
    const oldMenuToggle = document.querySelector('.menu-toggle');
    const oldNavMenu = document.querySelector('nav ul');
    
    if (oldMenuToggle && oldNavMenu) {
        oldMenuToggle.classList.toggle('active');
        oldNavMenu.classList.toggle('active');
    }
    
    // Handle new Bootstrap navigation structure
    const newNavbarToggle = document.querySelector('.navbar-toggler');
    const newNavbarCollapse = document.querySelector('.navbar-collapse');
    
    if (newNavbarToggle && newNavbarCollapse) {
        // Toggle the 'show' class on the collapse element
        newNavbarCollapse.classList.toggle('show');
    }
}

// Initialize the page
document.addEventListener('DOMContentLoaded', function() {
    loadProducts();
    
    // Add event listeners
    const searchBtn = document.getElementById('searchBtn');
    if (searchBtn) {
        searchBtn.addEventListener('click', filterProducts);
    }
    
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function(event) {
            if (event.key === 'Enter') {
                filterProducts();
            }
        });
    }
    
    const categoryFilter = document.getElementById('categoryFilter');
    if (categoryFilter) {
        categoryFilter.addEventListener('change', filterProducts);
    }
    
    // Add mobile menu toggle functionality for old navigation
    const oldMenuToggle = document.querySelector('.menu-toggle');
    if (oldMenuToggle) {
        oldMenuToggle.addEventListener('click', toggleMobileMenu);
    }
    
    // Add mobile menu toggle functionality for new Bootstrap navigation
    const newNavbarToggle = document.querySelector('.navbar-toggler');
    if (newNavbarToggle) {
        newNavbarToggle.addEventListener('click', toggleMobileMenu);
    }
    
    // Close mobile menu when clicking on a nav link (old navigation)
    const oldNavLinks = document.querySelectorAll('nav ul li a');
    oldNavLinks.forEach(link => {
        link.addEventListener('click', function() {
            const menuToggle = document.querySelector('.menu-toggle');
            const navMenu = document.querySelector('nav ul');
            
            if (menuToggle && navMenu) {
                menuToggle.classList.remove('active');
                navMenu.classList.remove('active');
            }
        });
    });
    
    // Close mobile menu when clicking on a nav link (new Bootstrap navigation)
    const newNavLinks = document.querySelectorAll('.navbar-nav .nav-link');
    newNavLinks.forEach(link => {
        link.addEventListener('click', function() {
            const navbarCollapse = document.querySelector('.navbar-collapse');
            if (navbarCollapse) {
                navbarCollapse.classList.remove('show');
            }
        });
    });
});