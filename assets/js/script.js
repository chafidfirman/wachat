// Sample product data - in a real application, this would come from a database
let products = [];
let categories = [];

// Function to show product detail in modal
function showProductDetail(productId) {
    // Fetch products from API to get the latest data
    fetchProducts().then(fetchedProducts => {
        const product = fetchedProducts.find(p => p.id === productId);
        if (!product) {
            console.error('Product not found');
            return;
        }
        
        // Handle both data structures (JSON vs API)
        const inStock = product.inStock !== undefined ? product.inStock : product.in_stock;
        const stockQuantity = product.stockQuantity !== undefined ? product.stockQuantity : product.stock;
        const whatsappNumber = product.whatsappNumber !== undefined ? product.whatsappNumber : product.whatsapp_number;
        const productImage = product.image && product.image.trim() !== '' 
            ? product.image.startsWith('http') ? product.image : 'assets/' + product.image 
            : 'assets/img/product-default.jpg';
        
        // Determine stock status display for modal
        let stockStatus = '';
        let stockClass = '';
        if (!inStock) {
            stockStatus = 'Out of Stock';
            stockClass = 'out-of-stock';
        } else if (stockQuantity !== undefined && stockQuantity !== null) {
            if (stockQuantity <= 0) {
                stockStatus = 'Out of Stock';
                stockClass = 'out-of-stock';
            } else if (stockQuantity <= 5) {
                stockStatus = `Only ${stockQuantity} left!`;
                stockClass = 'low-stock';
            } else {
                stockStatus = `${stockQuantity} in stock`;
                stockClass = 'in-stock';
            }
        } else {
            stockStatus = 'In Stock';
            stockClass = 'in-stock';
        }
        
        // Create modal HTML
        const modal = document.createElement('div');
        modal.className = 'modal';
        modal.id = 'productModal';
        
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Product Details</h2>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <div class="modal-product-image">
                        <img src="${productImage}" alt="${product.name}" onerror="this.src='assets/img/product-default.jpg'">
                    </div>
                    <h2 class="modal-product-name">${product.name}</h2>
                    <div class="modal-product-price">${formatPrice(product.price)}</div>
                    <p class="modal-product-description">${product.description}</p>
                    <div class="modal-product-stock ${stockClass}">
                        Stock Status: ${stockStatus}
                    </div>
                    <div class="modal-actions">
                        <a href="https://wa.me/${whatsappNumber}?text=${encodeURIComponent(generateWhatsAppMessage(product, 1))}" 
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

// Function to generate WhatsApp message
function generateWhatsAppMessage(product, quantity = 1) {
    const totalPrice = product.price * quantity;
    return `Halo, saya ingin memesan ${product.name} sebanyak ${quantity} pcs dengan total harga ${formatPrice(totalPrice)}. Apakah masih tersedia?`;
}

// Function to render products
function renderProducts(productsToShow) {
    const productGrid = document.getElementById('productGrid');
    if (productGrid) {
        if (productsToShow.length === 0) {
            productGrid.innerHTML = '<p>No products found.</p>';
            return;
        }
        
        productGrid.innerHTML = productsToShow.map(product => createProductCard(product)).join('');
    }
}

// Function to populate category filter
function populateCategoryFilter() {
    const categoryFilter = document.getElementById('categoryFilter');
    if (categoryFilter) {
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
}

// Function to filter products
function filterProducts() {
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    
    if (!searchInput || !categoryFilter) return;
    
    const searchTerm = searchInput.value.toLowerCase();
    const selectedCategory = categoryFilter.value;
    
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
        const response = await fetch('api/v1/products');
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const result = await response.json();
        // Return the data array from the response
        return Array.isArray(result) ? result : (result.data || []);
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

// Function to toggle mobile menu (only for old navigation structure)
function toggleMobileMenu() {
    // Handle old navigation structure only
    const oldMenuToggle = document.querySelector('.menu-toggle');
    const oldNavMenu = document.querySelector('nav ul');
    
    if (oldMenuToggle && oldNavMenu) {
        oldMenuToggle.classList.toggle('active');
        oldNavMenu.classList.toggle('active');
    }
    
    // Note: For new Bootstrap navigation, we rely on Bootstrap's built-in collapse functionality
    // which is triggered by data-bs-toggle="collapse" and data-bs-target attributes
}

// Initialize the page
document.addEventListener('DOMContentLoaded', function() {
    try {
        // Only load products on pages that have the product grid
        const productGrid = document.getElementById('productGrid');
        if (productGrid) {
            loadProducts().catch(error => {
                console.error('Error loading products:', error);
            });
            
            // Add event listeners
            const searchBtn = document.getElementById('searchBtn');
            if (searchBtn) {
                searchBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    filterProducts();
                });
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
        }
        
        // Add mobile menu toggle functionality for old navigation only
        const oldMenuToggle = document.querySelector('.menu-toggle');
        if (oldMenuToggle) {
            oldMenuToggle.addEventListener('click', toggleMobileMenu);
        }
        
        // Note: For new Bootstrap navigation, we don't need to add event listeners
        // because Bootstrap's collapse functionality is triggered automatically
        // by the data-bs-toggle="collapse" and data-bs-target attributes
        
        // Close mobile menu when clicking on a nav link (old navigation)
        const oldNavLinks = document.querySelectorAll('nav ul li a');
        if (oldNavLinks.length > 0) {
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
        }
        
        // Close mobile menu when clicking on a nav link (new Bootstrap navigation)
        // Note: Bootstrap's collapse functionality handles this automatically
        // but we can add additional handling if needed
        const newNavLinks = document.querySelectorAll('.navbar-nav .nav-link');
        if (newNavLinks.length > 0) {
            newNavLinks.forEach(link => {
                link.addEventListener('click', function() {
                    // For Bootstrap navigation, the collapse is handled automatically
                    // but we can ensure the navbar is closed on smaller screens
                    const navbarCollapse = document.querySelector('.navbar-collapse');
                    if (navbarCollapse && navbarCollapse.classList.contains('show')) {
                        // Remove the 'show' class to close the navbar
                        navbarCollapse.classList.remove('show');
                        
                        // Also update the aria-expanded attribute on the toggle button
                        const navbarToggle = document.querySelector('.navbar-toggler');
                        if (navbarToggle) {
                            navbarToggle.setAttribute('aria-expanded', 'false');
                        }
                    }
                });
            });
        }
    } catch (error) {
        console.error('Error initializing page:', error);
    }
});