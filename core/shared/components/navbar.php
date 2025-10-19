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
                    <a class="nav-link<?= ($_SERVER['REQUEST_URI'] == '/' || $_SERVER['REQUEST_URI'] == '/index.php' || $_SERVER['REQUEST_URI'] == '/public/' || $_SERVER['REQUEST_URI'] == '/public/index.php' || strpos($_SERVER['REQUEST_URI'], '/public/index.php?path=') === 0 && !isset($_GET['path'])) ? ' active' : ''; ?>" href="<?= base_url() ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link<?= ($_SERVER['REQUEST_URI'] == '/about.html' || $_SERVER['REQUEST_URI'] == '/about' || (isset($_GET['path']) && $_GET['path'] == 'about')) ? ' active' : ''; ?>" href="<?= base_url('about.html') ?>">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link<?= ($_SERVER['REQUEST_URI'] == '/contact.html' || $_SERVER['REQUEST_URI'] == '/contact' || (isset($_GET['path']) && $_GET['path'] == 'contact')) ? ' active' : ''; ?>" href="<?= base_url('contact.html') ?>">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link<?= (strpos($_SERVER['REQUEST_URI'], '/admin') !== false || (isset($_GET['path']) && strpos($_GET['path'], 'admin') === 0)) ? ' active' : ''; ?>" href="<?= site_url('admin/login') ?>">Admin</a>
                </li>
            </ul>
        </div>
    </div>
</nav>