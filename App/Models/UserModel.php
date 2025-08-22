<?php
namespace App\Models;

use App\Includes\Database;

class UserModel
{
    public function __construct(private Database $db) {}

    public function createTable(): bool
    {
        $sql = <<<SQL
CREATE TABLE IF NOT EXISTS `users` (
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
        $this->db->query($sql);
        return true;
    }

    public function findByEmailActiveAdmin(string $email): ?array
    {
        $stmt = $this->db->query(
            "SELECT id,email,password_hash FROM users WHERE email = ? AND is_admin = 1 AND active = 1 LIMIT 1",
            [$email]
        );
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        return $row ?: null;
    }

    public function existsByEmail(string $email): bool
    {
        $stmt = $this->db->query("SELECT id FROM users WHERE email = ? LIMIT 1", [$email]);
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    public function createAdmin(string $email, string $plainPassword): int
    {
        $hash = password_hash($plainPassword, PASSWORD_BCRYPT);
        $stmt = $this->db->query(
            "INSERT INTO users (email, password_hash, is_admin, active) VALUES (?,?,1,1)",
            [$email, $hash]
        );
        return $this->db->getInsertId();
    }
}
