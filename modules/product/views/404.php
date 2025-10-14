<?php
$title = 'Page Not Found - ChatCart Web';
ob_start();
?>
<div class="container not-found">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h1 class="display-1">404</h1>
            <h2>Page Not Found</h2>
            <p class="lead">Sorry, the page you are looking for could not be found.</p>
            <a href="<?= site_url() ?>" class="btn btn-success">Go to Homepage</a>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../../core/shared/layouts/main.php';
?>