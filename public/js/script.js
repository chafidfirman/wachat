document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Handle search form submission - Use a more generic selector
    const searchForms = document.querySelectorAll('form[action*="search"]');
    searchForms.forEach(function(searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const searchInput = this.querySelector('input[name="q"]');
            if (searchInput && searchInput.value.trim() === '') {
                e.preventDefault();
                alert('Please enter a search term');
            }
        });
    });
});

// Product detail page functionality
if (document.querySelector('.product-detail-page')) {
    // Handle quantity input
    const quantityInput = document.getElementById('quantity');
    if (quantityInput) {
        quantityInput.addEventListener('change', function() {
            if (this.value < 1) this.value = 1;
            if (this.max && this.value > this.max) this.value = this.max;
        });
    }
}