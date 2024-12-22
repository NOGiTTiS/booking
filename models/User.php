<?php

class User
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }
    public function createUser($username, $password, $firstName, $lastName, $email, $phone, $role = 'user')
    {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->pdo->prepare("INSERT INTO users (username, password, first_name, last_name, email, phone, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$username, $hashedPassword, $firstName, $lastName, $email, $phone, $role]);
            return true;
        } catch (PDOException $e) {
            // Log the error or display a user-friendly message
            throw new Exception("Error creating user: " . $e->getMessage());
            return false;
        }
    }
    public function getUserByUsername($username)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function verifyPassword($password, $hashedPassword)
    {
        return password_verify($password, $hashedPassword);
    }

    public function getAllUsers()
    {
        $stmt = $this->pdo->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
