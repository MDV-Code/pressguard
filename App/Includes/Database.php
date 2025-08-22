<?php
namespace App\Includes;

require_once __DIR__ . '/../Config/bootstrap.php'; // lädt .env

class Database
{
    private \mysqli $connection;

    public function __construct()
    {
        $this->connection = new \mysqli(
            getenv('DB_HOST') ?: 'localhost',
            getenv('DB_USER') ?: '',
            getenv('DB_PASSWORD') ?: '',
            getenv('DB_NAME') ?: ''
        );
        if ($this->connection->connect_error) {
            die("Verbindung zur Datenbank fehlgeschlagen: " . $this->connection->connect_error);
        }
        $this->connection->set_charset('utf8mb4');
    }

    public function query(string $sql, array $params = []): \mysqli_stmt
    {
        $stmt = $this->connection->prepare($sql);
        if ($stmt === false) {
            die("Fehler bei der Vorbereitung der Abfrage: " . $this->connection->error);
        }
        if ($params) {
            $types = '';
            foreach ($params as $p) {
                $types .= match (gettype($p)) {
                    'integer' => 'i',
                    'double'  => 'd',
                    'string'  => 's',
                    default   => 'b',
                };
            }
            $refs = [];
            foreach ($params as $k => $v) { $refs[$k] = &$params[$k]; }
            array_unshift($refs, $types);
            call_user_func_array([$stmt, 'bind_param'], $refs);
        }
        $stmt->execute();
        return $stmt;
    }

    public function getInsertId(): int { return $this->connection->insert_id; }

    public function getMysqli(): \mysqli { return $this->connection; }

    public function __destruct(){ if ($this->connection) $this->connection->close(); }
}
