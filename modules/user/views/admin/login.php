<?php
$title = 'Admin Login - ChatCart Web';
ob_start();
?>
<div class="admin-login-container">
    <div class="admin-login-card">
        <div class="admin-login-header">
            <h3>Admin Login</h3>
        </div>
        <div class="admin-login-body">
            <div id="login-alert" class="admin-login-alert alert alert-danger d-none" role="alert"></div>
            <form id="loginForm" class="admin-login-form">
                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="admin-login-btn">Login</button>
            </form>
        </div>
        <div class="admin-login-footer">
            <p><a href="<?= base_url() ?>">← Back to Home</a></p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const loginAlert = document.getElementById('login-alert');
    
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            
            // Hide any previous alerts
            loginAlert.classList.add('d-none');
            
            // Send login request
            fetch('<?= site_url('admin/login') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Redirect to dashboard
                    window.location.href = '<?= site_url('admin/dashboard') ?>';
                } else {
                    // Show error message
                    loginAlert.textContent = data.message;
                    loginAlert.classList.remove('d-none');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                loginAlert.textContent = 'An error occurred. Please try again.';
                loginAlert.classList.remove('d-none');
            });
        });
    }
});
</script>
<?php
$content = ob_get_clean();
// Add the admin login CSS
$additional_css = '<link href="' . base_url('assets/css/admin-login.css') . '" rel="stylesheet">';
include __DIR__ . '/../../../core/shared/layouts/main.php';
?>