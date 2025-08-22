<?php
namespace App\Controller;

use App\Service\AdminAuthService;

class AdminAuthController
{
    private AdminAuthService $service;

    public function __construct()
    {
        $this->service = new AdminAuthService();
    }

    public function login(): void
    {
        session_start();
        if (
            empty($_POST['csrf_token']) ||
            empty($_SESSION['csrf_token']) ||
            !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
        ) {
            header('Location: /index.php?error=' . urlencode('Sicherheitsfehler. Bitte erneut.'));
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $origin = trim($_POST['_origin'] ?? 'index.php');

        if ($email === '' || $password === '') {
            header('Location: /index.php?error=' . urlencode('E-Mail und Passwort erforderlich.'));
            exit;
        }

        $admin = $this->service->authenticate($email, $password);
        if (!$admin) {
            header('Location: /index.php?error=' . urlencode('Ungültige Zugangsdaten.'));
            exit;
        }

        // Session festlegen
        session_regenerate_id(true);
        $_SESSION['admin_id']    = (int)$admin['id'];
        $_SESSION['admin_email'] = $admin['email'];
        $_SESSION['is_admin']    = true;
        $_SESSION['login_at']    = date('c');
        $_SESSION['login_origin']= $origin;

        unset($_SESSION['csrf_token']);

        // Ab hier nur noch Admins → Secret
        header('Location: /secret.php');
        exit;
    }
}
