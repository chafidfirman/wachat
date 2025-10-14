<?php
$title = 'Store Settings - ChatCart Web';
ob_start();
?>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('admin/dashboard') ?>">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('admin/products') ?>">
                            <i class="fas fa-boxes"></i> Products
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('admin/categories') ?>">
                            <i class="fas fa-tags"></i> Categories
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('admin/orders') ?>">
                            <i class="fas fa-shopping-cart"></i> Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('admin/users') ?>">
                            <i class="fas fa-users"></i> Users
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= site_url('admin/settings') ?>">
                            <i class="fas fa-cog"></i> Settings
                        </a>
                    </li>
                </ul>
                
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('admin/logout') ?>">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        
        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Store Settings</h1>
            </div>
            
            <!-- Settings Tabs -->
            <ul class="nav nav-tabs" id="settingsTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="identity-tab" data-bs-toggle="tab" data-bs-target="#identity" type="button" role="tab" aria-controls="identity" aria-selected="true">
                        <i class="fas fa-store"></i> Store Identity
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-controls="general" aria-selected="false">
                        <i class="fas fa-cogs"></i> General Settings
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pages-tab" data-bs-toggle="tab" data-bs-target="#pages" type="button" role="tab" aria-controls="pages" aria-selected="false">
                        <i class="fas fa-file-alt"></i> Page Settings
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="theme-tab" data-bs-toggle="tab" data-bs-target="#theme" type="button" role="tab" aria-controls="theme" aria-selected="false">
                        <i class="fas fa-palette"></i> Theme & Layout
                    </button>
                </li>
            </ul>
            
            <div class="tab-content" id="settingsTabContent">
                <!-- Store Identity Tab -->
                <div class="tab-pane fade show active" id="identity" role="tabpanel" aria-labelledby="identity-tab">
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Store Identity</h5>
                        </div>
                        <div class="card-body">
                            <form id="storeIdentityForm">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="storeName" class="form-label">Store Name</label>
                                            <input type="text" class="form-control" id="storeName" name="storeName" value="<?php echo htmlspecialchars($settings['store_name'] ?? ''); ?>" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="storeSlogan" class="form-label">Slogan</label>
                                            <input type="text" class="form-control" id="storeSlogan" name="storeSlogan" value="<?php echo htmlspecialchars($settings['store_slogan'] ?? ''); ?>">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="storeDescription" class="form-label">Description</label>
                                            <textarea class="form-control" id="storeDescription" name="storeDescription" rows="4"><?php echo htmlspecialchars($settings['store_description'] ?? ''); ?></textarea>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="storeLogo" class="form-label">Logo URL</label>
                                            <input type="text" class="form-control" id="storeLogo" name="storeLogo" value="<?php echo htmlspecialchars($settings['store_logo'] ?? ''); ?>">
                                            <div class="form-text">Enter the full URL to your store logo</div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="storeAddress" class="form-label">Address</label>
                                            <textarea class="form-control" id="storeAddress" name="storeAddress" rows="3"><?php echo htmlspecialchars($settings['store_address'] ?? ''); ?></textarea>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="storePhone" class="form-label">Phone Number</label>
                                            <input type="text" class="form-control" id="storePhone" name="storePhone" value="<?php echo htmlspecialchars($settings['store_phone'] ?? ''); ?>">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="storeEmail" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="storeEmail" name="storeEmail" value="<?php echo htmlspecialchars($settings['store_email'] ?? ''); ?>">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Social Media Links</label>
                                            <div class="input-group mb-2">
                                                <span class="input-group-text"><i class="fab fa-facebook"></i></span>
                                                <input type="text" class="form-control" id="facebookUrl" name="facebookUrl" placeholder="Facebook URL" value="<?php echo htmlspecialchars($settings['facebook_url'] ?? ''); ?>">
                                            </div>
                                            <div class="input-group mb-2">
                                                <span class="input-group-text"><i class="fab fa-instagram"></i></span>
                                                <input type="text" class="form-control" id="instagramUrl" name="instagramUrl" placeholder="Instagram URL" value="<?php echo htmlspecialchars($settings['instagram_url'] ?? ''); ?>">
                                            </div>
                                            <div class="input-group mb-2">
                                                <span class="input-group-text"><i class="fab fa-twitter"></i></span>
                                                <input type="text" class="form-control" id="twitterUrl" name="twitterUrl" placeholder="Twitter URL" value="<?php echo htmlspecialchars($settings['twitter_url'] ?? ''); ?>">
                                            </div>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fab fa-whatsapp"></i></span>
                                                <input type="text" class="form-control" id="whatsappUrl" name="whatsappUrl" placeholder="WhatsApp URL" value="<?php echo htmlspecialchars($settings['whatsapp_url'] ?? ''); ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> Save Settings
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- General Settings Tab -->
                <div class="tab-pane fade" id="general" role="tabpanel" aria-labelledby="general-tab">
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">General Settings</h5>
                        </div>
                        <div class="card-body">
                            <form id="generalSettingsForm">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="currency" class="form-label">Currency</label>
                                            <select class="form-select" id="currency" name="currency">
                                                <option value="IDR" <?php echo (isset($settings['currency']) && $settings['currency'] === 'IDR') ? 'selected' : ''; ?>>IDR (Indonesian Rupiah)</option>
                                                <option value="USD" <?php echo (isset($settings['currency']) && $settings['currency'] === 'USD') ? 'selected' : ''; ?>>USD (US Dollar)</option>
                                                <option value="EUR" <?php echo (isset($settings['currency']) && $settings['currency'] === 'EUR') ? 'selected' : ''; ?>>EUR (Euro)</option>
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="language" class="form-label">Language</label>
                                            <select class="form-select" id="language" name="language">
                                                <option value="en" <?php echo (isset($settings['language']) && $settings['language'] === 'en') ? 'selected' : ''; ?>>English</option>
                                                <option value="id" <?php echo (isset($settings['language']) && $settings['language'] === 'id') ? 'selected' : ''; ?>>Indonesian</option>
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="timezone" class="form-label">Timezone</label>
                                            <select class="form-select" id="timezone" name="timezone">
                                                <option value="Asia/Jakarta" <?php echo (isset($settings['timezone']) && $settings['timezone'] === 'Asia/Jakarta') ? 'selected' : ''; ?>>Asia/Jakarta</option>
                                                <option value="Asia/Makassar" <?php echo (isset($settings['timezone']) && $settings['timezone'] === 'Asia/Makassar') ? 'selected' : ''; ?>>Asia/Makassar</option>
                                                <option value="Asia/Jayapura" <?php echo (isset($settings['timezone']) && $settings['timezone'] === 'Asia/Jayapura') ? 'selected' : ''; ?>>Asia/Jayapura</option>
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="operatingHours" class="form-label">Operating Hours</label>
                                            <textarea class="form-control" id="operatingHours" name="operatingHours" rows="3"><?php echo htmlspecialchars($settings['operating_hours'] ?? ''); ?></textarea>
                                            <div class="form-text">Example: Monday-Friday: 9:00 AM - 6:00 PM</div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="shippingOptions" class="form-label">Shipping Options</label>
                                            <textarea class="form-control" id="shippingOptions" name="shippingOptions" rows="3"><?php echo htmlspecialchars($settings['shipping_options'] ?? ''); ?></textarea>
                                            <div class="form-text">Enter available shipping methods</div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="paymentMethods" class="form-label">Payment Methods</label>
                                            <textarea class="form-control" id="paymentMethods" name="paymentMethods" rows="3"><?php echo htmlspecialchars($settings['payment_methods'] ?? ''); ?></textarea>
                                            <div class="form-text">Enter available payment methods</div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="taxRate" class="form-label">Tax Rate (%)</label>
                                            <input type="number" class="form-control" id="taxRate" name="taxRate" step="0.01" min="0" max="100" value="<?php echo htmlspecialchars($settings['tax_rate'] ?? '0'); ?>">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Store Status</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="storeStatus" id="storeOpen" value="open" <?php echo (!isset($settings['store_status']) || $settings['store_status'] === 'open') ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="storeOpen">
                                                    Open
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="storeStatus" id="storeClosed" value="closed" <?php echo (isset($settings['store_status']) && $settings['store_status'] === 'closed') ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="storeClosed">
                                                    Closed
                                                </label>
                                            </div>
                                            <div class="form-text">When closed, customers will see a maintenance message</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> Save Settings
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Page Settings Tab -->
                <div class="tab-pane fade" id="pages" role="tabpanel" aria-labelledby="pages-tab">
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Page Settings</h5>
                        </div>
                        <div class="card-body">
                            <form id="pageSettingsForm">
                                <div class="mb-3">
                                    <label for="navbarItems" class="form-label">Navbar Items</label>
                                    <div id="navbarItemsContainer">
                                        <?php if (isset($navbarItems) && is_array($navbarItems)): ?>
                                            <?php foreach ($navbarItems as $index => $item): ?>
                                                <div class="row mb-2 navbar-item-row">
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" name="navbarItems[<?php echo $index; ?>][label]" placeholder="Label" value="<?php echo htmlspecialchars($item['label']); ?>">
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" name="navbarItems[<?php echo $index; ?>][url]" placeholder="URL" value="<?php echo htmlspecialchars($item['url']); ?>">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="button" class="btn btn-danger remove-navbar-item"><i class="fas fa-trash"></i></button>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <div class="row mb-2 navbar-item-row">
                                                <div class="col-md-5">
                                                    <input type="text" class="form-control" name="navbarItems[0][label]" placeholder="Label">
                                                </div>
                                                <div class="col-md-5">
                                                    <input type="text" class="form-control" name="navbarItems[0][url]" placeholder="URL">
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-danger remove-navbar-item"><i class="fas fa-trash"></i></button>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary mt-2" id="addNavbarItem">
                                        <i class="fas fa-plus"></i> Add Navbar Item
                                    </button>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="footerText" class="form-label">Footer Text</label>
                                    <textarea class="form-control" id="footerText" name="footerText" rows="3"><?php echo htmlspecialchars($settings['footer_text'] ?? ''); ?></textarea>
                                </div>
                                
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> Save Settings
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Theme & Layout Tab -->
                <div class="tab-pane fade" id="theme" role="tabpanel" aria-labelledby="theme-tab">
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Theme & Layout</h5>
                        </div>
                        <div class="card-body">
                            <form id="themeSettingsForm">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Dashboard Theme</label>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="radio" name="theme" id="themeLight" value="light" checked>
                                                <label class="form-check-label" for="themeLight">
                                                    Light Theme
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="radio" name="theme" id="themeDark" value="dark">
                                                <label class="form-check-label" for="themeDark">
                                                    Dark Theme
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="theme" id="themeAuto" value="auto">
                                                <label class="form-check-label" for="themeAuto">
                                                    Auto (System Preference)
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Sidebar Position</label>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="radio" name="sidebarPosition" id="sidebarLeft" value="left" checked>
                                                <label class="form-check-label" for="sidebarLeft">
                                                    Left
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="sidebarPosition" id="sidebarRight" value="right">
                                                <label class="form-check-label" for="sidebarRight">
                                                    Right
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Layout Options</label>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" id="compactSidebar" name="compactSidebar">
                                                <label class="form-check-label" for="compactSidebar">
                                                    Compact Sidebar
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" id="fixedHeader" name="fixedHeader" checked>
                                                <label class="form-check-label" for="fixedHeader">
                                                    Fixed Header
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="boxedLayout" name="boxedLayout">
                                                <label class="form-check-label" for="boxedLayout">
                                                    Boxed Layout
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="primaryColor" class="form-label">Primary Color</label>
                                            <input type="color" class="form-control form-control-color" id="primaryColor" name="primaryColor" value="#28a745" title="Choose your primary color">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-end">
                                    <button type="reset" class="btn btn-secondary me-2">
                                        <i class="fas fa-undo"></i> Reset
                                    </button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> Save Settings
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<style>
.sidebar {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    z-index: 100;
    padding: 48px 0 0;
    box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
}

.sidebar-sticky {
    position: relative;
    top: 0;
    height: calc(100vh - 48px);
    padding-top: .5rem;
    overflow-x: hidden;
    overflow-y: auto;
}

.nav-link {
    font-weight: 500;
    color: #333;
}

.nav-link:hover,
.nav-link.active {
    color: #28a745;
}

.nav-link i {
    margin-right: 5px;
}

.navbar-item-row .form-control {
    margin-bottom: 5px;
}

.form-control-color {
    height: calc(1.5em + 0.75rem + 2px);
    padding: 0.375rem 0.75rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add navbar item functionality
    document.getElementById('addNavbarItem').addEventListener('click', function() {
        const container = document.getElementById('navbarItemsContainer');
        const newIndex = container.querySelectorAll('.navbar-item-row').length;
        
        const newRow = document.createElement('div');
        newRow.className = 'row mb-2 navbar-item-row';
        newRow.innerHTML = `
            <div class="col-md-5">
                <input type="text" class="form-control" name="navbarItems[${newIndex}][label]" placeholder="Label">
            </div>
            <div class="col-md-5">
                <input type="text" class="form-control" name="navbarItems[${newIndex}][url]" placeholder="URL">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger remove-navbar-item"><i class="fas fa-trash"></i></button>
            </div>
        `;
        
        container.appendChild(newRow);
    });
    
    // Remove navbar item functionality
    document.getElementById('navbarItemsContainer').addEventListener('click', function(e) {
        if (e.target.closest('.remove-navbar-item')) {
            e.target.closest('.navbar-item-row').remove();
        }
    });
    
    // Form submission handlers
    document.getElementById('storeIdentityForm').addEventListener('submit', function(e) {
        e.preventDefault();
        // In a real implementation, you would send the form data to the server
        alert('Store identity settings saved successfully!');
    });
    
    document.getElementById('generalSettingsForm').addEventListener('submit', function(e) {
        e.preventDefault();
        // In a real implementation, you would send the form data to the server
        alert('General settings saved successfully!');
    });
    
    document.getElementById('pageSettingsForm').addEventListener('submit', function(e) {
        e.preventDefault();
        // In a real implementation, you would send the form data to the server
        alert('Page settings saved successfully!');
    });
    
    document.getElementById('themeSettingsForm').addEventListener('submit', function(e) {
        e.preventDefault();
        // In a real implementation, you would send the form data to the server
        alert('Theme settings saved successfully!');
    });
});
</script>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../../core/shared/layouts/main.php';
?>