<?php
require_once __DIR__ . '/../config.php';

class User
{
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function register($username, $email, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $role = 'user';

        $stmt = $this->pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");

        return $stmt->execute([$username, $email, $hashedPassword, $role]);
    }

    public function getUserByEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
