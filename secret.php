<?php
// /secret.php
declare(strict_types=1);
session_start();
require __DIR__ . '/App/autoloader.php';

// Access protection: only logged in admins are allowed here
if (empty($_SESSION['is_admin'])) {
    header('Location: /index.php?error=' . urlencode('No access. Please log in.'));
    exit;
}

// Collect current session information
$sessionInfo = [
    'admin_email'  => $_SESSION['admin_email'] ?? null,
    'admin_id'     => $_SESSION['admin_id'] ?? null,
    'is_admin'     => $_SESSION['is_admin'] ?? null,
    'login_at'     => $_SESSION['login_at'] ?? null,
    'login_origin' => $_SESSION['login_origin'] ?? null,
    'session_id'   => session_id(),
];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin Area • PressGuard</title>
  <link rel="stylesheet" href="./assets/css/app.css">
  <link rel="stylesheet" href="./assets/css/secret.css">
</head>
<body>
  <div class="container">
    <div class="card">
      <div class="header-row">
        <h1 style="margin:0">Admin Area</h1>
        <span class="badge">Administrators only</span>
      </div>
      <p class="sub">From here on, content is shown only to administrators.</p>

      <table class="tbl">
        <tr><td>Email</td><td><?= htmlspecialchars((string)$sessionInfo['admin_email'], ENT_QUOTES) ?></td></tr>
        <tr><td>Admin ID</td><td><?= htmlspecialchars((string)$sessionInfo['admin_id'], ENT_QUOTES) ?></td></tr>
        <tr><td>Login time</td><td><?= htmlspecialchars((string)$sessionInfo['login_at'], ENT_QUOTES) ?></td></tr>
        <tr><td>Login origin</td><td><?= htmlspecialchars((string)$sessionInfo['login_origin'], ENT_QUOTES) ?></td></tr>
        <tr><td>Session ID</td><td><?= htmlspecialchars((string)$sessionInfo['session_id'], ENT_QUOTES) ?></td></tr>
      </table>

      <div class="actions">
        <a class="btn" href="/index.php">Home</a>
        <a class="btn" href="/index.php?action=logout">Logout</a>
      </div>

      <div class="footer">© <?= date('Y') ?> PressGuard</div>
    </div>
  </div>
  <script src="/assets/js/app.js"></script>
</body>
</html>
