<?php
/**
 * Components Helper Functions
 * Provides reusable UI components for consistent rendering across the application
 */

/**
 * Render a product card component
 * @param array $product The product data
 */
function renderProductCard($product) {
    // Extract product data with defaults
    $id = $product['id'] ?? 0;
    $name = $product['name'] ?? 'Untitled Product';
    $description = $product['description'] ?? '';
    $price = $product['price'] ?? 0;
    $image = !empty($product['images']) ? json_decode($product['images'], true)['image'] : 
           (!empty($product['image']) ? $product['image'] : base_url('assets/img/product-default.jpg'));
    $stock = $product['stock'] ?? null;
    
    // Determine stock status
    $stockStatus = '';
    $stockBadgeClass = '';
    
    if ($stock !== null && $stock <= 5 && $stock > 0) {
        $stockStatus = "Only {$stock} left!";
        $stockBadgeClass = 'bg-warning';
    } elseif ($stock === null || $stock > 0) {
        $stockStatus = 'In Stock';
        $stockBadgeClass = 'bg-success';
    } else {
        $stockStatus = 'Out of Stock';
        $stockBadgeClass = 'bg-danger';
    }
    
    // Render the component
    echo "
    <div class=\"card h-100 product-card\">
        <img src=\"" . htmlspecialchars($image) . "\" 
             class=\"card-img-top product-image\" alt=\"" . htmlspecialchars($name) . "\">
        <div class=\"card-body d-flex flex-column\">
            <h5 class=\"card-title product-title\">" . htmlspecialchars($name) . "</h5>
            <p class=\"card-text product-description\">
                " . htmlspecialchars(substr($description, 0, 60)) . "...
            </p>
            <div class=\"mt-auto\">
                <div class=\"d-flex justify-content-between align-items-center mb-2\">
                    <span class=\"text-success fw-bold product-price\">
                        Rp " . number_format($price, 0, ',', '.') . "
                    </span>
                    <span class=\"badge {$stockBadgeClass}\">{$stockStatus}</span>
                </div>
                <div class=\"mt-2 d-flex justify-content-between\">
                    <a href=\"" . site_url("product/{$id}") . "\" class=\"btn btn-sm btn-outline-primary detail-btn\">
                        <i class=\"fas fa-eye\"></i> Detail
                    </a>";
                    
    if ($stock === null || $stock > 0) {
        echo "<a href=\"" . site_url("whatsapp/{$id}") . "\" class=\"btn btn-sm btn-success whatsapp-btn\" target=\"_blank\">
                        <i class=\"fab fa-whatsapp\"></i> Order
                    </a>";
    } else {
        echo "<button class=\"btn btn-sm btn-secondary\" disabled>
                        <i class=\"fab fa-whatsapp\"></i> Out of Stock
                    </button>";
    }
    
    echo "
                </div>
            </div>
        </div>
    </div>";
}

/**
 * Render a related product card component
 * @param array $relatedProduct The related product data
 */
function renderRelatedProductCard($relatedProduct) {
    // Extract product data with defaults
    $id = $relatedProduct['id'] ?? 0;
    $name = $relatedProduct['name'] ?? 'Untitled Product';
    $price = $relatedProduct['price'] ?? 0;
    $image = !empty($relatedProduct['image']) ? $relatedProduct['image'] : base_url('assets/img/product-default.jpg');
    
    // Render the component
    echo "
    <div class=\"card h-100 related-product-card\">
        <img src=\"" . htmlspecialchars($image) . "\" 
             class=\"card-img-top related-product-image\" alt=\"" . htmlspecialchars($name) . "\">
        <div class=\"card-body d-flex flex-column\">
            <h6 class=\"card-title related-product-title\">" . htmlspecialchars($name) . "</h6>
            <div class=\"mt-auto\">
                <div class=\"d-flex justify-content-between align-items-center\">
                    <span class=\"text-success fw-bold related-product-price\">
                        Rp " . number_format($price, 0, ',', '.') . "
                    </span>
                </div>
                <a href=\"" . site_url("product/{$id}") . "\" class=\"btn btn-sm btn-outline-primary mt-2 detail-btn\">
                    <i class=\"fas fa-eye\"></i> Detail
                </a>
            </div>
        </div>
    </div>";
}

/**
 * Render a hero section component
 * @param string $title The hero title
 * @param string $subtitle The hero subtitle
 * @param string $ctaText The CTA button text
 * @param string $ctaLink The CTA button link
 */
function renderHero($title, $subtitle, $ctaText, $ctaLink) {
    echo "
    <section class=\"hero bg-primary text-white py-5\">
        <div class=\"container\">
            <div class=\"row\">
                <div class=\"col-lg-8 mx-auto text-center\">
                    <h1 class=\"display-4 fw-bold mb-3\">{$title}</h1>
                    <p class=\"lead mb-4\">{$subtitle}</p>
                    <a href=\"" . htmlspecialchars($ctaLink) . "\" class=\"btn btn-light btn-lg px-4 py-2\">
                        {$ctaText}
                    </a>
                </div>
            </div>
        </div>
    </section>";
}

/**
 * Render a product grid component
 * @param array $products The products to display
 * @param string $title The section title
 * @param string $id The section ID
 */
function renderProductGrid($products, $title, $id) {
    echo "
    <section class=\"product-section mb-5\">
        <div class=\"container\">
            <h2 class=\"text-center mb-4\">{$title}</h2>
            <div class=\"row\" id=\"{$id}\">";
                
    if (empty($products)) {
        echo "<div class=\"col-12\"><p class=\"text-center\">No products available.</p></div>";
    } else {
        foreach ($products as $product) {
            echo "<div class=\"col-md-4 mb-4\">";
            renderProductCard($product);
            echo "</div>";
        }
    }
    
    echo "
            </div>
        </div>
    </section>";
}

/**
 * Render an alert component
 * @param string $type The alert type (primary, secondary, success, danger, warning, info, light, dark)
 * @param string $message The alert message
 * @param bool $dismissible Whether the alert is dismissible
 */
function renderAlert($type, $message, $dismissible = false) {
    $dismissibleAttr = $dismissible ? 'alert-dismissible fade show' : '';
    $closeButton = $dismissible ? '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' : '';
    
    echo "
    <div class=\"alert alert-{$type} {$dismissibleAttr}\" role=\"alert\">
        {$message}
        {$closeButton}
    </div>";
}

/**
 * Render a form input component
 * @param string $type The input type
 * @param string $name The input name
 * @param string $label The input label
 * @param string $value The input value
 * @param array $attributes Additional attributes
 */
function renderInput($type, $name, $label, $value = '', $attributes = []) {
    $attrString = '';
    foreach ($attributes as $key => $val) {
        $attrString .= " {$key}=\"" . htmlspecialchars($val) . "\"";
    }
    
    echo "
    <div class=\"mb-3\">
        <label for=\"{$name}\" class=\"form-label\">{$label}</label>
        <input type=\"{$type}\" class=\"form-control\" id=\"{$name}\" name=\"{$name}\" value=\"" . htmlspecialchars($value) . "\"{$attrString}>
    </div>";
}

/**
 * Render a form select component
 * @param string $name The select name
 * @param string $label The select label
 * @param array $options The select options
 * @param string $selected The selected value
 * @param array $attributes Additional attributes
 */
function renderSelect($name, $label, $options, $selected = '', $attributes = []) {
    $attrString = '';
    foreach ($attributes as $key => $val) {
        $attrString .= " {$key}=\"" . htmlspecialchars($val) . "\"";
    }
    
    echo "
    <div class=\"mb-3\">
        <label for=\"{$name}\" class=\"form-label\">{$label}</label>
        <select class=\"form-select\" id=\"{$name}\" name=\"{$name}\"{$attrString}>";
            
    foreach ($options as $value => $text) {
        $selectedAttr = ($value == $selected) ? ' selected' : '';
        echo "<option value=\"" . htmlspecialchars($value) . "\"{$selectedAttr}>" . htmlspecialchars($text) . "</option>";
    }
    
    echo "
        </select>
    </div>";
}

/**
 * Render a button component
 * @param string $type The button type (primary, secondary, success, danger, warning, info, light, dark)
 * @param string $text The button text
 * @param string $url The button URL (optional)
 * @param array $attributes Additional attributes
 */
function renderButton($type, $text, $url = '', $attributes = []) {
    $attrString = '';
    foreach ($attributes as $key => $val) {
        $attrString .= " {$key}=\"" . htmlspecialchars($val) . "\"";
    }
    
    if (!empty($url)) {
        echo "<a href=\"" . htmlspecialchars($url) . "\" class=\"btn btn-{$type}\"{$attrString}>{$text}</a>";
    } else {
        echo "<button type=\"button\" class=\"btn btn-{$type}\"{$attrString}>{$text}</button>";
    }
}
?>