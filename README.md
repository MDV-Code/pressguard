# 🛡️ PressGuard

A secure PHP-based web application starter with a guided installer. PressGuard automatically sets up your database, admin account, environment configuration, and removes itself after completion for enhanced security.

![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-4479A1?style=for-the-badge&logo=mysql)
![Apache](https://img.shields.io/badge/Apache-2.4%2B-D22128?style=for-the-badge&logo=apache)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

## ✨ Features

- 🔐 **Secure Admin Login** – bcrypt-hashed passwords with session management
- ⚡ **Step-by-Step Installer** – Comprehensive system checks and configuration
- 🗄️ **Automatic Database Setup** – Creates schema, tables, and initial admin user
- 🛠️ **Self-Destruction Mechanism** – Installer auto-removes after successful setup
- 🧱 **Security Hardened** – .htaccess protection and strict permission guidelines
- 📱 **Fully Responsive** – Works seamlessly on desktop and mobile devices

## 📦 Installation

### Prerequisites

- PHP 7.4 or higher
- MySQL 8.0 or higher
- Apache web server with mod_rewrite enabled
- Write permissions for web directory

### Quick Setup

1. **Clone or download PressGuard**
   ```bash
   git clone https://github.com/yourname/pressguard.git /var/www/html/pressguard
   ```

2. **Set appropriate permissions**
   ```bash
   sudo chown -R www-data:www-data /var/www/html/pressguard
   sudo chmod 755 /var/www/html/pressguard
   sudo chmod 750 /var/www/html/pressguard/App/Config
   ```

3. **Apache Configuration** (example vHost)
   ```apache
   <VirtualHost *:80>
       ServerName example.com
       DocumentRoot /var/www/html/pressguard
       
       <Directory /var/www/html/pressguard>
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```

4. **Enable rewrite module and reload Apache**
   ```bash
   sudo a2enmod rewrite
   sudo systemctl reload apache2
   ```

5. **Run the installer**
   Navigate to `http://your-domain.com/install.php` and follow the guided setup process.

## 📁 Project Structure

```
pressguard/
├── index.php                 # Main application entry point
├── install.php              # Installer (auto-removes after setup)
├── App/
│   ├── autoloader.php       # Class autoloader
│   ├── Config/
│   │   └── .env            # Environment configuration (generated)
│   ├── Controller/          # Application controllers
│   ├── Service/            # Business logic layer
│   ├── Models/             # Data models and database interaction
│   └── Includes/           # Helper functions and utilities
├── assets/
│   ├── css/
│   │   ├── install.css     # Installer styles
│   │   └── app.css         # Application styles
│   └── js/
│       └── app.js          # Application JavaScript
└── .htaccess               # Apache configuration and security rules
```

## 🔧 Installation Process

The guided installer will:

1. **System Check** - Verify PHP version, extensions, and directory permissions
2. **Database Setup** - Create database schema and initial tables
3. **Admin Creation** - Set up your first administrator account
4. **Environment Configuration** - Generate secure .env file with your settings
5. **Self-Cleanup** - Automatically remove the installer for security

## 🔒 Security Hardening

After installation, we recommend these additional security measures:

```bash
# Restrict .env file access
chmod 640 /var/www/html/pressguard/App/Config/.env

# Set secure directory permissions
find /var/www/html/pressguard -type d -exec chmod 755 {} \;

# Set secure file permissions
find /var/www/html/pressguard -type f -exec chmod 644 {} \;
```

## ⚠️ Security Notes

- The `.htaccess` file blocks direct access to the `App/` directory and `.env` files
- The installer automatically deletes itself after successful completion
- Always run the recommended hardening commands after installation
- Never leave the installer accessible on a production server

## 🐛 Troubleshooting

**Common issues:**

1. **Permission errors** - Ensure the web server has write access to the directory
2. **mod_rewrite not working** - Verify `AllowOverride All` is set in your Apache configuration
3. **PHP extensions missing** - Install required extensions (pdo_mysql, mbstring, etc.)

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 🤝 Contributing

We welcome contributions! Please feel free to submit pull requests, open issues, or suggest new features.

## 🆓 Free and Open Source

PressGuard is completely free and open source. If you find it useful, please consider giving it a star on GitHub!

---

**Disclaimer**: While PressGuard includes security best practices, always perform your own security audit before deploying to production environments.
