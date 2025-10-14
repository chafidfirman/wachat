document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Handle search form submission
    const searchForm = document.querySelector('form[action="public/index.php?path=search"]');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const searchInput = this.querySelector('input[name="q"]');
            if (searchInput && searchInput.value.trim() === '') {
                e.preventDefault();
                alert('Please enter a search term');
            }
        });
    }
    
    // Alternative selector for search form using the site_url pattern
    const searchFormAlt = document.querySelector('form[action="' + window.location.origin + '/public/index.php?path=search"]');
    if (searchFormAlt && !searchForm) {
        searchFormAlt.addEventListener('submit', function(e) {
            const searchInput = this.querySelector('input[name="q"]');
            if (searchInput && searchInput.value.trim() === '') {
                e.preventDefault();
                alert('Please enter a search term');
            }
        });
    }
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