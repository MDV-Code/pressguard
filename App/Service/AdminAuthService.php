<?php
namespace App\Service;

use App\Includes\Database;
use App\Models\UserModel;

class AdminAuthService
{
    private UserModel $users;

    public function __construct()
    {
        $db = new Database();
        $this->users = new UserModel($db);
    }

    public function authenticate(string $email, string $password): ?array
    {
        $row = $this->users->findByEmailActiveAdmin($email);
        if (!$row) return null;
        if (!password_verify($password, $row['password_hash'])) return null;
        return $row;
    }
}
