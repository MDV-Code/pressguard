<h1 align="center">🛡️ PressGuard</h1>
<p align="center">A secure PHP-based web application starter with a guided installer</p>
<p align="center">Ein sicheres PHP-basiertes Webanwendungs-Starterkit mit Installationsprogramm</p>

<div align="center">
  <!--- Tabs Navigation -->
  <a href="#english">English</a> | 
  <a href="#deutsch">Deutsch</a>
</div>

<!--- English Content -->
<h2 id="english">🇺🇸 English Version</h2>

A secure PHP-based web application starter with a guided installer. PressGuard automatically sets up your database, admin account, environment configuration, and removes itself after completion for enhanced security.

![Debian](https://img.shields.io/badge/Debian-12%2B-A81D33?style=for-the-badge&logo=debian)
![PHP](https://img.shields.io/badge/PHP-8.4%2B-777BB4?style=for-the-badge&logo=php)
![MariaDB](https://img.shields.io/badge/MariaDB-10.5%2B-003545?style=for-the-badge&logo=mariadb)
![Apache](https://img.shields.io/badge/Apache-2.4%2B-D22128?style=for-the-badge&logo=apache)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

## ⚠️ GitHub Restriction Notice

GitHub automatically blocks `install.php` files for security reasons. Therefore, the installer is provided as:

**📁 Filename:** `pressguard-installer.php`

**After download, rename it:**
```bash
mv pressguard-installer.php install.php
```

**Or access it directly at:**
```
http://your-domain.com/pressguard-installer.php
```

## ✨ Features

- 🔐 **Secure Admin Login** – bcrypt-hashed passwords with session management
- ⚡ **Step-by-Step Installer** – Comprehensive system checks and configuration
- 🗄️ **Automatic Database Setup** – Creates schema, tables, and initial admin user
- 🛠️ **Self-Destruction Mechanism** – Installer auto-removes after successful setup
- 🧱 **Security Hardened** – .htaccess protection and strict permission guidelines
- 📱 **Fully Responsive** – Works seamlessly on desktop and mobile devices

## 📦 Installation

### Prerequisites

- PHP 8.4 or higher
- MySQL 8.0 or higher
- Apache web server with mod_rewrite enabled
- Write permissions for web directory

### Quick Setup

1. **Clone or download PressGuard**
   ```bash
   git clone https://github.com/MDV-Code/pressguard.git /var/www/html/pressguard
   ```

2. **Set appropriate permissions**
   ```bash
   sudo chown -R www-data:www-data /var/www/html/pressguard
   sudo chmod 755 /var/www/html/pressguard
   sudo chmod 750 /var/www/html/pressguard/App/Config
   ```

<details>
<summary>👉 Click to expand full installation instructions</summary>

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
   Navigate to `http://your-domain.com/pressguard-installer.php` and follow the guided setup process.

</details>

## 📁 Project Structure

```
pressguard/
├── index.php                 # Main application entry point
├── pressguard-installer.php # Installer (auto-removes after setup)
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
└── .htaccess               # Apache configuration and security rules (auto-generated)
```

## 🔧 Installation Process

The guided installer will:

1. **System Check** - Verify PHP version, extensions, and directory permissions
2. **Database Setup** - Create database schema and initial tables
3. **Admin Creation** - Set up your first administrator account
4. **Environment Configuration** - Generate secure .env file with your settings
5. **Security Configuration** - Automatically create .htaccess file with proper security rules
6. **Self-Cleanup** - Automatically remove the installer for security

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

- **Auto-generated .htaccess** - The installer automatically creates a secure .htaccess file after permission verification
- The .htaccess file blocks direct access to the `App/` directory and `.env` files
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

---

<!--- German Content -->
<h2 id="deutsch">🇩🇪 Deutsche Version</h2>

Ein sicheres PHP-basiertes Webanwendungs-Starterkit mit einem geführten Installationsprogramm. PressGuard richtet automatisch Ihre Datenbank, Admin-Konten, Umgebungskonfiguration ein und entfernt sich selbst nach Abschluss für erweiterte Sicherheit.

![Debian](https://img.shields.io/badge/Debian-12%2B-A81D33?style=for-the-badge&logo=debian)
![PHP](https://img.shields.io/badge/PHP-8.4%2B-777BB4?style=for-the-badge&logo=php)
![MariaDB](https://img.shields.io/badge/MariaDB-10.5%2B-003545?style=for-the-badge&logo=mariadb)
![Apache](https://img.shields.io/badge/Apache-2.4%2B-D22128?style=for-the-badge&logo=apache)
![Lizenz](https://img.shields.io/badge/Lizenz-MIT-green?style=for-the-badge)

## ⚠️ GitHub Einschränkungshinweis

GitHub blockiert `install.php`-Dateien automatisch aus Sicherheitsgründen. Daher finden Sie den Installer als:

**📁 Dateiname:** `pressguard-installer.php`

**Nach dem Download umbennen:**
```bash
mv pressguard-installer.php install.php
```

**Oder direkt aufrufen unter:**
```
http://ihre-domain.com/pressguard-installer.php
```

## ✨ Funktionen

- 🔐 **Sicherer Admin-Login** – Bcrypt-gehashte Passwörter mit Session-Management
- ⚡ **Schritt-für-Schritt Installer** – Umfassende Systemchecks und Konfiguration
- 🗄️ **Automatische Datenbankeinrichtung** – Erstellt Schema, Tabellen und initialen Admin-Benutzer
- 🛠️ **Selbstzerstörungsmechanismus** – Installer entfernt sich nach erfolgreicher Einrichtung automatisch
- 🧱 **Sicherheitsgehärtet** – .htaccess-Schutz und strenge Berechtigungsrichtlinien
- 📱 **Vollständig responsiv** – Funktioniert nahtlos auf Desktop- und Mobilgeräten

## 📦 Installation

### Voraussetzungen

- PHP 8.4 oder höher
- MySQL 8.0 oder höher
- Apache Webserver mit aktiviertem mod_rewrite
- Schreibrechte für Webverzeichnis

### Schnelleinrichtung

1. **PressGuard klonen oder herunterladen**
   ```bash
   git clone https://github.com/MDV-Code/pressguard.git /var/www/html/pressguard
   ```

2. **Angemessene Berechtigungen setzen**
   ```bash
   sudo chown -R www-data:www-data /var/www/html/pressguard
   sudo chmod 755 /var/www/html/pressguard
   sudo chmod 750 /var/www/html/pressguard/App/Config
   ```

<details>
<summary>👉 Klicken Sie hier für vollständige Installationsanweisungen</summary>

3. **Apache-Konfiguration** (Beispiel vHost)
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

4. **Rewrite-Modul aktivieren und Apache neuladen**
   ```bash
   sudo a2enmod rewrite
   sudo systemctl reload apache2
   ```

5. **Installer ausführen**
   Navigieren Sie zu `http://ihre-domain.com/pressguard-installer.php` und folgen Sie dem geführten Setup-Prozess.

</details>

## 📁 Projektstruktur

```
pressguard/
├── index.php                 # Haupt-Einstiegspunkt der Anwendung
├── pressguard-installer.php # Installer (entfernt sich nach Setup automatisch)
├── App/
│   ├── autoloader.php       # Klassen-Autoloader
│   ├── Config/
│   │   └── .env            # Umgebungskonfiguration (wird generiert)
│   ├── Controller/          # Anwendungscontroller
│   ├── Service/            # Geschäftslogik-Schicht
│   ├── Models/             # Datenmodelle und Datenbankinteraktion
│   └── Includes/           # Hilfsfunktionen und Utilities
├── assets/
│   ├── css/
│   │   ├── install.css     # Installer-Styles
│   │   └── app.css         # Anwendungs-Styles
│   └── js/
│       └── app.js          # Anwendungs-JavaScript
└── .htaccess               # Apache-Konfiguration und Sicherheitsregeln (automatisch generiert)
```

## 🔧 Installationsprozess

Der geführte Installer wird:

1. **Systemcheck** - PHP-Version, Erweiterungen und Verzeichnisberechtigungen überprüfen
2. **Datenbank-Setup** - Datenbankschema und Initialtabellen erstellen
3. **Admin-Erstellung** - Ihr erstes Administrator-Konto einrichten
4. **Umgebungskonfiguration** - Sichere .env-Datei mit Ihren Einstellungen generieren
5. **Sicherheitskonfiguration** - Automatische Erstellung der .htaccess-Datei mit entsprechenden Sicherheitsregeln
6. **Selbstbereinigung** - Installer automatisch aus Sicherheitsgründen entfernen

## 🔒 Sicherheitshärtung

Nach der Installation empfehlen wir diese zusätzlichen Sicherheitsmaßnahmen:

```bash
# .env Dateizugriff einschränken
chmod 640 /var/www/html/pressguard/App/Config/.env

# Sichere Verzeichnisberechtigungen setzen
find /var/www/html/pressguard -type d -exec chmod 755 {} \;

# Sichere Dateiberechtigungen setzen
find /var/www/html/pressguard -type f -exec chmod 644 {} \;
```

## ⚠️ Sicherheitshinweise

- **Automatisch generierte .htaccess** - Der Installer erstellt nach der Berechtigungsprüfung automatisch eine sichere .htaccess-Datei
- Die .htaccess-Datei blockiert direkten Zugriff auf das `App/`-Verzeichnis und `.env`-Dateien
- Der Installer löscht sich nach erfolgreichem Abschluss automatisch
- Führen Sie immer die empfohlenen Härtungsbefehle nach der Installation aus
- Lassen Sie den Installer niemals auf einem Produktionsserver zugänglich

## 🐛 Problembehandlung

**Häufige Probleme:**

1. **Berechtigungsfehler** - Stellen Sie sicher, dass der Webserver Schreibzugriff auf das Verzeichnis hat
2. **mod_rewrite funktioniert nicht** - Überprüfen Sie, ob `AllowOverride All` in Ihrer Apache-Konfiguration gesetzt ist
3. **Fehlende PHP-Erweiterungen** - Installieren Sie benötigte Erweiterungen (pdo_mysql, mbstring, etc.)

## 📄 Lizenz

Dieses Projekt ist unter der MIT-Lizenz lizenziert - siehe LICENSE-Datei für Details.

## 🤝 Mitwirken

Wir freuen uns über Beiträge! Bitte zögern Sie nicht, Pull Requests einzureichen, Issues zu öffnen oder neue Funktionen vorzuschlagen.

## 🆓 Kostenlos und Open Source

PressGuard ist komplett kostenlos und Open Source. Wenn Sie es nützlich finden, erwägen Sie bitte, es auf GitHub mit einem Stern zu bewerten!

---

**Haftungsausschluss**: Während PressGuard Sicherheits-Best-Practices enthält, führen Sie bitte immer Ihr eigenes Security-Audit durch, bevor Sie es in Produktionsumgebungen einsetzen.

---

## 📄 License / Lizenz

Dieses Projekt ist unter der MIT-Lizenz lizenziert - siehe [LICENSE](LICENSE)-Datei für Details.
