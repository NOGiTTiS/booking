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
            $stmt           = $this->pdo->prepare("INSERT INTO users (username, password, first_name, last_name, email, phone, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
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

    public function getUserByEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUser($id, $username, $firstName, $lastName, $email, $phone, $role)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE users SET username = ?, first_name = ?, last_name = ?, email = ?, phone = ?, role = ? WHERE id = ?");
            $stmt->execute([$username, $firstName, $lastName, $email, $phone, $role, $id]);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Error updating user: " . $e->getMessage());
            return false;
        }
    }

    public function deleteUser($id)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Error deleting user: " . $e->getMessage());
            return false;
        }
    }
    public function changePassword($id, $newPassword)
    {
        try {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt           = $this->pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$hashedPassword, $id]);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Error changing password: " . $e->getMessage());
            return false;
        }
    }

    public function resetPassword($id)
    {
        try {
            $defaultPassword = "password123";
            $hashedPassword  = password_hash($defaultPassword, PASSWORD_DEFAULT);
            $stmt            = $this->pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$hashedPassword, $id]);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Error resetting password: " . $e->getMessage());
            return false;
        }
    }

    public function saveResetToken($userId, $token)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE users SET reset_token = ? WHERE id = ?");
            $stmt->execute([$token, $userId]);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Error saving reset token: " . $e->getMessage());
            return false;
        }
    }

    public function getUserByToken($token)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE reset_token = ?");
        $stmt->execute([$token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function changePasswordWithToken($token, $newPassword)
    {
        try {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt           = $this->pdo->prepare("UPDATE users SET password = ?, reset_token = NULL WHERE reset_token = ?");
            $stmt->execute([$hashedPassword, $token]);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Error changing password with token: " . $e->getMessage());
            return false;
        }
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
