# 🛡️ PressGuard

Ein sicheres PHP-basiertes Webanwendungs-Starterkit mit einem geführten Installationsprogramm. PressGuard richtet automatisch Ihre Datenbank, Admin-Konten, Umgebungskonfiguration ein und entfernt sich selbst nach Abschluss für erweiterte Sicherheit.

![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-4479A1?style=for-the-badge&logo=mysql)
![Apache](https://img.shields.io/badge/Apache-2.4%2B-D22128?style=for-the-badge&logo=apache)
![Lizenz](https://img.shields.io/badge/Lizenz-MIT-green?style=for-the-badge)

## ✨ Funktionen

- 🔐 **Sicherer Admin-Login** – Bcrypt-gehashte Passwörter mit Session-Management
- ⚡ **Schritt-für-Schritt Installer** – Umfassende Systemchecks und Konfiguration
- 🗄️ **Automatische Datenbankeinrichtung** – Erstellt Schema, Tabellen und initialen Admin-Benutzer
- 🛠️ **Selbstzerstörungsmechanismus** – Installer entfernt sich nach erfolgreicher Einrichtung automatisch
- 🧱 **Sicherheitsgehärtet** – .htaccess-Schutz und strenge Berechtigungsrichtlinien
- 📱 **Vollständig responsiv** – Funktioniert nahtlos auf Desktop- und Mobilgeräten

## 📦 Installation

### Voraussetzungen

- PHP 7.4 oder höher
- MySQL 8.0 oder höher
- Apache Webserver mit aktiviertem mod_rewrite
- Schreibrechte für Webverzeichnis

### Schnelleinrichtung

1. **PressGuard klonen oder herunterladen**
   ```bash
   git clone https://github.com/yourname/pressguard.git /var/www/html/pressguard
   ```

2. **Angemessene Berechtigungen setzen**
   ```bash
   sudo chown -R www-data:www-data /var/www/html/pressguard
   sudo chmod 755 /var/www/html/pressguard
   sudo chmod 750 /var/www/html/pressguard/App/Config
   ```

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
   Navigieren Sie zu `http://ihre-domain.com/install.php` und folgen Sie dem geführten Setup-Prozess.

## 📁 Projektstruktur

```
pressguard/
├── index.php                 # Haupt-Einstiegspunkt der Anwendung
├── install.php              # Installer (entfernt sich nach Setup automatisch)
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
└── .htaccess               # Apache-Konfiguration und Sicherheitsregeln
```

## 🔧 Installationsprozess

Der geführte Installer wird:

1. **Systemcheck** - PHP-Version, Erweiterungen und Verzeichnisberechtigungen überprüfen
2. **Datenbank-Setup** - Datenbankschema und Initialtabellen erstellen
3. **Admin-Erstellung** - Ihr erstes Administrator-Konto einrichten
4. **Umgebungskonfiguration** - Sichere .env-Datei mit Ihren Einstellungen generieren
5. **Selbstbereinigung** - Installer automatisch aus Sicherheitsgründen entfernen

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

- Die `.htaccess`-Date blockiert direkten Zugriff auf das `App/`-Verzeichnis und `.env`-Dateien
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
