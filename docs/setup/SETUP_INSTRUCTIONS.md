# ChatCart Web v2.0 Setup Instructions

## System Requirements

- PHP 8.0 or higher
- MySQL 8.0 or higher
- Apache or Nginx web server
- Composer (optional, for future enhancements)

## Installation Steps

### 1. Download and Extract
Download the ChatCart Web package and extract it to your web server's document root.

### 2. Database Setup
1. Create a new MySQL database:
   ```sql
   CREATE DATABASE chatcart;
   ```

2. Import the database schema:
   ```bash
   mysql -u your_username -p chatcart < database/chatcart.sql
   ```

### 3. Configure Database Connection
Edit `core/shared/config/database.php` and update the database credentials:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_database_username');
define('DB_PASS', 'your_database_password');
define('DB_NAME', 'chatcart');
```

### 4. Web Server Configuration

#### Apache
If you're using Apache, make sure `mod_rewrite` is enabled and add the following to your `.htaccess` file in the `public` directory:

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?path=$1 [QSA,L]
```

Configure your virtual host to point to the `public` directory:
```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    DocumentRoot /path/to/wachat/public
    <Directory /path/to/wachat/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

#### Nginx
If you're using Nginx, add the following to your server configuration:

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/wachat/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?path=$request_uri;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
    }
}
```

### 5. File Permissions
Ensure the web server has read access to all files and write access to any directories that need to store uploaded files (when implemented).

### 6. Admin Access
Access the admin panel at `http://yourdomain.com/admin/login`

Default admin credentials:
- Username: admin
- Password: password

**Important**: Change the default password immediately after logging in for security reasons.

## Environment Variables
For production environments, it's recommended to use environment variables for sensitive configuration:

1. Create a `.env` file in the project root:
   ```
   DB_HOST=localhost
   DB_USER=your_database_username
   DB_PASS=your_database_password
   DB_NAME=chatcart
   ```

2. Update `core/shared/config/database.php` to read from environment variables:
   ```php
   define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
   define('DB_USER', $_ENV['DB_USER'] ?? 'root');
   define('DB_PASS', $_ENV['DB_PASS'] ?? '');
   define('DB_NAME', $_ENV['DB_NAME'] ?? 'chatcart');
   ```

## Troubleshooting

### Common Issues

1. **404 Errors**: 
   - Ensure your web server is configured to use the `public` directory as the document root
   - Check that URL rewriting is properly configured

2. **Database Connection Errors**:
   - Verify database credentials in `core/shared/config/database.php`
   - Ensure the database server is running
   - Check that the `chatcart` database exists and has been imported

3. **Permission Errors**:
   - Ensure the web server has read access to all files
   - Check that the `storage` directory (when implemented) has write permissions

### Debugging
Enable error reporting by setting the following in your `php.ini`:
```ini
display_errors = On
error_reporting = E_ALL
```

For development purposes, you can also add this to the top of `public/index.php`:
```php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
```

## Security Recommendations

1. **Change Default Credentials**: Immediately change the default admin password
2. **Use HTTPS**: Configure SSL/TLS for secure communication
3. **Regular Updates**: Keep PHP, MySQL, and server software up to date
4. **File Permissions**: Restrict file permissions to the minimum necessary
5. **Input Validation**: Always validate and sanitize user input
6. **SQL Injection Prevention**: Use prepared statements for all database queries
7. **XSS Prevention**: Escape output when displaying user-generated content

## Backup and Maintenance

### Database Backup
Regularly backup your database:
```bash
mysqldump -u your_username -p chatcart > backup/chatcart_backup_$(date +%Y%m%d).sql
```

### File Backup
Backup your application files regularly, especially after making changes.

### Updates
When updating the application:
1. Backup your database and files
2. Review changelogs for breaking changes
3. Update files
4. Run any necessary database migrations
5. Test thoroughly

## Support
For support, please refer to the documentation or contact the development team.