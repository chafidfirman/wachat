// Public Script for ChatCart Web
// This file contains public-facing JavaScript functionality

console.log('Public script loaded');

// Function to handle mobile menu toggle for Bootstrap navigation
function handleMobileMenuToggle() {
    // This is handled automatically by Bootstrap's collapse functionality
    // but we can add custom behavior if needed
    console.log('Mobile menu toggle clicked');
}

// Function to handle search functionality
function handleSearch() {
    const searchForm = document.getElementById('searchForm');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const searchTerm = document.getElementById('searchInput').value;
            if (searchTerm.trim() !== '') {
                // Redirect to search results page
                window.location.href = 'search?q=' + encodeURIComponent(searchTerm);
            }
        });
    }
}

// Function to handle category filter
function handleCategoryFilter() {
    const categoryLinks = document.querySelectorAll('.category-link');
    categoryLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const categoryId = this.getAttribute('data-category-id');
            if (categoryId) {
                window.location.href = 'search?category=' + categoryId;
            }
        });
    });
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Public script initialized');
    
    // Handle search functionality
    handleSearch();
    
    // Handle category filter
    handleCategoryFilter();
    
    // Handle mobile menu toggle if needed
    const mobileToggle = document.querySelector('.navbar-toggler');
    if (mobileToggle) {
        mobileToggle.addEventListener('click', handleMobileMenuToggle);
    }
});