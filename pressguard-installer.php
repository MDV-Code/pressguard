<?php
// /install.php
declare(strict_types=1);
error_reporting(E_ALL); ini_set('display_errors', '1');

session_start();
require __DIR__ . '/App/autoloader.php';

function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

$projectRoot        = __DIR__;
$configDir          = __DIR__ . '/App/Config';
$envPath            = $configDir . '/.env';

$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
$step = max(1, min($step, 4));

// ---------- Prechecks ----------
$okPhp           = version_compare(PHP_VERSION, '8.4.0', '>=');
$okMysqli        = extension_loaded('mysqli');
$projectWritable = is_writable($projectRoot);
$configWritable  = is_dir($configDir) && is_writable($configDir);

$ownerName = null;
if (function_exists('fileowner') && function_exists('posix_getpwuid')) {
    $oid = @fileowner($projectRoot);
    $pw  = $oid ? @posix_getpwuid($oid) : null;
    if (!empty($pw['name'])) $ownerName = $pw['name'];
}
$ownerLooksOk   = ($ownerName === 'www-data');

$htaccessPath   = $projectRoot.'/.htaccess';
$htaccessExists = is_file($htaccessPath);

$issues = [];
if (!$okPhp)            $issues[] = 'PHP 8.4 is missing';
if (!$okMysqli)         $issues[] = 'mysqli is missing';
if (!$projectWritable)  $issues[] = 'Project directory is not writable';
if (!$configWritable)   $issues[] = 'App/Config is not writable';
if (!$ownerLooksOk)     $issues[] = 'Owner is not www-data';

$allOk = empty($issues);

// ---------- Auto-create .htaccess (English version) ----------
if ($step === 1 && $allOk && !$htaccessExists) {
    $htaccessContent = <<<HTA
# Disable directory listing
Options -Indexes

# Default: allow all (we block selectively further below)
<IfModule mod_authz_core.c>
  Require all granted
</IfModule>

# Never serve /App publicly
<IfModule mod_rewrite.c>
  RewriteEngine On
  # Block direct access to /App (code, config, models, includes)
  RewriteRule ^App/ - [F,L]
</IfModule>

# Completely block .env, .ini, .log, .bak etc.
<FilesMatch "^\.(env|env\..*|git|hg|svn)|\.(ini|log|bak|backup)$">
  <IfModule mod_authz_core.c>
    Require all denied
  </IfModule>
</FilesMatch>

# Explicit file blocker (if FilesMatch does not catch)
<Files ".env">
  <IfModule mod_authz_core.c>
    Require all denied
  </IfModule>
</Files>

# Protect sensitive files as fallback if mod_rewrite is not available
<IfModule !mod_rewrite.c>
  <FilesMatch "^(autoloader\.php|bootstrap\.php|Database\.php)$">
    <IfModule mod_authz_core.c>
      Require all denied
    </IfModule>
  </FilesMatch>
</IfModule>
HTA;
    @file_put_contents($htaccessPath, $htaccessContent);
    $htaccessExists = is_file($htaccessPath);
}

// ---------- Install (Step 3 POST) ----------
$errors = [];
$ok = false;
$derivedEnvPath  = null;
$derivedRootPath = null;

if ($step === 3 && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['do_install'])) {
    if (!$allOk) {
        $errors[] = 'Prechecks failed.';
    } else {
        $dbHost     = trim($_POST['db_host'] ?? 'localhost');
        $dbUser     = trim($_POST['db_user'] ?? '');
        $dbPass     = (string)($_POST['db_pass'] ?? '');
        $dbName     = trim($_POST['db_name'] ?? '');
        $adminEmail = trim($_POST['admin_email'] ?? '');
        $adminPass  = (string)($_POST['admin_pass'] ?? '');

        if ($dbUser === '' || $dbName === '') $errors[] = 'DB user and DB name are required.';
        if (!filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) $errors[] = 'Admin email is invalid.';
        if (strlen($adminPass) < 8) $errors[] = 'Password must be at least 8 characters.';
        if (!is_dir($configDir) || !is_writable($configDir)) $errors[] = 'App/Config is not writable (cannot write .env).';

        if (!$errors) {
            try {
                $mysqli = @new mysqli($dbHost, $dbUser, $dbPass);
                if ($mysqli->connect_error) throw new RuntimeException('DB server connection failed: '.$mysqli->connect_error);

                $dbEsc = $mysqli->real_escape_string($dbName);
                $check = $mysqli->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '{$dbEsc}'");
                if ($check && $check->num_rows > 0) {
                    throw new RuntimeException("Database '{$dbName}' already exists. Choose a new name.");
                }

                if (!$mysqli->query("CREATE DATABASE `{$dbEsc}` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")) {
                    throw new RuntimeException('Database creation failed: '.$mysqli->error);
                }

                if (!$mysqli->select_db($dbName)) throw new RuntimeException('Cannot select the new database.');

                $sqlUsers = <<<SQL
CREATE TABLE `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(191) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `is_admin` TINYINT(1) NOT NULL DEFAULT 0,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;
                if (!$mysqli->query($sqlUsers)) {
                    throw new RuntimeException('Creating users table failed: '.$mysqli->error);
                }

                $hash = password_hash($adminPass, PASSWORD_BCRYPT);
                $stmt = $mysqli->prepare("INSERT INTO users (email, password_hash, is_admin, active) VALUES (?,?,1,1)");
                if (!$stmt) throw new RuntimeException('Prepare failed: '.$mysqli->error);
                $stmt->bind_param('ss', $adminEmail, $hash);
                if (!$stmt->execute()) throw new RuntimeException('Admin insert failed: '.$stmt->error);
                $stmt->close();

                $envContent = "DB_HOST={$dbHost}\nDB_USER={$dbUser}\nDB_PASSWORD={$dbPass}\nDB_NAME={$dbName}\n";
                if (file_exists($envPath)) @copy($envPath, $envPath.'.'.date('Ymd_His').'.bak');
                if (@file_put_contents($envPath, $envContent) === false) {
                    throw new RuntimeException("Writing .env failed: ".$envPath);
                }
                @chmod($envPath, 0640);

                $derivedEnvPath  = realpath($envPath) ?: $envPath;
                $derivedRootPath = realpath(dirname(dirname($derivedEnvPath))) ?: realpath($projectRoot) ?: $projectRoot;

                $ok = true;
                register_shutdown_function(fn()=>@unlink(__FILE__));
                $step = 4;

                $mysqli->close();
            } catch (\Throwable $t) {
                $errors[] = $t->getMessage();
            }
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Installer</title>
<link rel="stylesheet" href="/assets/css/install.css">
</head>
<body>
<div class="container">
  <div class="card">
    <h1>Installer</h1>

    <?php if ($step===1): ?>
      <h2>Step 1: System check</h2>
      <ul>
        <li>PHP ≥ 8.4: <span class="<?= $okPhp?'ok':'bad' ?>"><?= h(PHP_VERSION) ?></span></li>
        <li>mysqli: <span class="<?= $okMysqli?'ok':'bad' ?>"><?= $okMysqli?'OK':'Missing' ?></span></li>
        <li>Project writable: <span class="<?= $projectWritable?'ok':'bad' ?>"><?= $projectWritable?'OK':'No' ?></span></li>
        <li>App/Config writable: <span class="<?= $configWritable?'ok':'bad' ?>"><?= $configWritable?'OK':'No' ?></span></li>
        <li>Owner: <span class="<?= $ownerLooksOk?'ok':'bad' ?>"><?= h($ownerName??'unknown') ?></span></li>
      </ul>

      <?php if ($allOk): ?>
        <?php if (!$htaccessExists): ?>
          <div class="ok">.htaccess has been created automatically. Ensure your Apache vHost allows <code>AllowOverride All</code>.</div>
        <?php else: ?>
          <div class="ok">System OK. Existing .htaccess detected.</div>
        <?php endif; ?>
        <a class="btn" href="?step=2">Continue</a>
      <?php else: ?>
        <div class="bad"><b>Fix first:</b> <?= h(implode(', ', $issues)) ?></div>
        <?php if (!$okPhp || !$okMysqli): ?>
<pre>sudo apt update
sudo apt install -y php8.4 php8.4-mysqli</pre>
        <?php endif; ?>
        <?php if (!$ownerLooksOk || !$projectWritable || !$configWritable): ?>
<pre>sudo chown -R www-data:www-data <?= h($projectRoot) ?>

sudo chmod 755 <?= h($projectRoot) ?>

sudo chmod 750 <?= h($configDir) ?></pre>
        <?php endif; ?>
        <a class="btn" href="?step=1">Re-check</a>
      <?php endif; ?>

    <?php elseif ($step===2): ?>
      <h2>Step 2: vHost</h2>
      <?php if ($htaccessExists): ?>
        <div class="bad">.htaccess is present — your Apache vHost must set <code>AllowOverride All</code>.</div>
      <?php endif; ?>
      <a class="btn" href="?step=1">Back</a>
      <a class="btn" href="?step=3">Continue</a>

    <?php elseif ($step===3): ?>
      <h2>Step 3: Install</h2>
      <p class="sub"><b>Note:</b> The database name will be created new. If it already exists, installation stops.</p>
      <?php if ($errors): ?><div class="bad"><ul><?php foreach($errors as $e) echo "<li>".h($e)."</li>"; ?></ul></div><?php endif; ?>
      <form method="post" autocomplete="off">
        <label>DB host <input name="db_host" value="localhost" required></label>
        <label>DB user <input name="db_user" required></label>
        <label>DB password <input type="password" name="db_pass"></label>
        <label>DB name (new) <input name="db_name" required></label>
        <label>Admin email <input type="email" name="admin_email" required></label>
        <label>Admin password <input type="password" name="admin_pass" required minlength="8"></label>
        <button type="submit" name="do_install">Install</button>
      </form>
      <a class="btn" href="?step=2">Back</a>

    <?php elseif ($step===4): ?>
      <h2>Step 4: Success</h2>
      <div class="ok">Installation finished. <code>install.php</code> has been removed.</div>
      <?php
        $envShown  = $derivedEnvPath ?: (file_exists($envPath) ? realpath($envPath) : $envPath);
        $rootShown = $derivedRootPath ?: (file_exists($envPath) ? realpath(dirname(dirname($envPath))) : realpath($projectRoot));
      ?>
      <p>Harden permissions:</p>
<pre>chmod 640 <?= h($envShown) ?>

find <?= h($rootShown) ?> -type d -exec chmod 755 {} \;
find <?= h($rootShown) ?> -type f -exec chmod 644 {} \;</pre>
      <?php if ($htaccessExists): ?>
        <div class="ok">Reminder: vHost must allow <code>AllowOverride All</code> so <code>.htaccess</code> rules are enforced.</div>
      <?php endif; ?>
      <a class="btn" href="/index.php">Go to login</a>
    <?php endif; ?>
  </div>
</div>
</body>
</html>
