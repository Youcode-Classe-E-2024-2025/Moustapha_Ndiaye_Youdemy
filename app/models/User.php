<?php

class User
{
    private $pdo;

    // Constructor
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Method to create a user
    public function createAccount(array $data): bool
    {
        try {
            $sql = "INSERT INTO users (fullName, email, password, role) VALUES (:fullName, :email, :password, :role)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            // Log the error or handle it as needed
            error_log("Error creating user: " . $e->getMessage());
            return false;
        }
    }

    // Method to find a user by email
    public function findByEmail(string $email): ?array
    {
        try {
            $sql = "SELECT * FROM users WHERE email = :email";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['email' => $email]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            // Log the error or handle it as needed
            error_log("Error finding user by email: " . $e->getMessage());
            return null;
        }
    }
}