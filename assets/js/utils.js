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
    return `Halo, saya ingin memesan ${product.name} sebanyak ${quantity} pcs dengan total harga ${formatPrice(totalPrice)}. Apakah masih tersedia?`;
}

// Function to order via WhatsApp
function orderViaWhatsApp(productId) {
    // Fetch product details from the API
    fetch(`api/v1/products?id=${productId}`)
        .then(response => response.json())
        .then(result => {
            if (result.success && result.data) {
                const product = result.data;
                // Handle both data structures (JSON vs API)
                const whatsappNumber = product.whatsappNumber !== undefined ? product.whatsappNumber : product.whatsapp_number;
                const message = generateWhatsAppMessage(product, 1);
                const whatsappUrl = `https://wa.me/${whatsappNumber}?text=${encodeURIComponent(message)}`;
                window.open(whatsappUrl, '_blank');
            } else {
                alert('Product not found. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error fetching product details:', error);
            alert('Error fetching product details. Please try again.');
        });
}
