<?php
// /index.php
declare(strict_types=1);
session_start();
require __DIR__ . '/App/autoloader.php';

use App\Controller\AdminAuthController;
use App\Controller\LogoutController;

$action = $_GET['action'] ?? '';
if ($action === 'admin_auth' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    (new AdminAuthController())->login(); exit;
}
if ($action === 'logout') {
    (new LogoutController())->handle(); exit;
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$error = $_GET['error'] ?? '';
$isLogged = !empty($_SESSION['is_admin']);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>PressGuard Login</title>
  <link rel="stylesheet" href="./assets/css/app.css">
</head>
<body>
  <div class="container"><div class="card">
    <?php if ($isLogged): ?>
      <h1>Welcome</h1>
      <p class="sub">Logged in as <?= htmlspecialchars($_SESSION['admin_email'] ?? '', ENT_QUOTES) ?></p>
      <div class="field"><a class="btn" href="/secret.php">Go to Admin Area</a></div>
      <div class="field"><a class="btn" href="/index.php?action=logout">Logout</a></div>
    <?php else: ?>
      <h1>Admin Login</h1>
      <p class="sub">Access to the administration area</p>
      <?php if ($error): ?><div class="err"><?= htmlspecialchars($error, ENT_QUOTES) ?></div><?php endif; ?>
      <form method="POST" action="/index.php?action=admin_auth" autocomplete="off">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES) ?>">
        <input type="hidden" name="_origin" value="/index.php">

        <div class="field">
          <label for="email">Email</label>
          <input id="email" name="email" type="email" class="input" required inputmode="email" autocomplete="username">
        </div>
        <div class="field">
          <label for="password">Password</label>
          <div class="pw-wrap">
            <input id="password" name="password" type="password" class="input" required minlength="8" autocomplete="current-password">
            <button class="toggle" type="button" onclick="togglePassword('password', this)">show</button>
          </div>
        </div>
        <div class="field" style="margin-top:16px">
          <button class="btn" type="submit">Login</button>
        </div>
      </form>
    <?php endif; ?>
    <div class="footer">© <?= date('Y') ?> PressGuard</div>
  </div></div>
  <script src="/assets/js/app.js"></script>
</body>
</html>
