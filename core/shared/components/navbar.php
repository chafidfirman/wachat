<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="<?= base_url() ?>">
            <i class="fas fa-shopping-cart"></i> ChatCart Web
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link<?= ($_SERVER['REQUEST_URI'] == '/' || $_SERVER['REQUEST_URI'] == '/index.php' || $_SERVER['REQUEST_URI'] == '/public/' || $_SERVER['REQUEST_URI'] == '/public/index.php') ? ' active' : ''; ?>" href="<?= base_url() ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link<?= ($_SERVER['REQUEST_URI'] == '/about.html' || $_SERVER['REQUEST_URI'] == '/about') ? ' active' : ''; ?>" href="<?= base_url('about.html') ?>">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link<?= ($_SERVER['REQUEST_URI'] == '/contact.html' || $_SERVER['REQUEST_URI'] == '/contact') ? ' active' : ''; ?>" href="<?= base_url('contact.html') ?>">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link<?= (strpos($_SERVER['REQUEST_URI'], '/admin') !== false) ? ' active' : ''; ?>" href="<?= site_url('admin/login') ?>">Admin</a>
                </li>
            </ul>
        </div>
    </div>
</nav>