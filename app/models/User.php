<?php

class User
{
    private $pdo;

    // Constructor
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Insert a new user into the database.
     *
     * @param array $data The user data (name, email, password, role, status, created_at, updated_at).
     * @return bool True if the user was successfully inserted, false otherwise.
     */
    public function createUser(array $data): bool
    {
        try {
            $sql = "INSERT INTO Users (name, email, password, role, status, created_at, updated_at) 
                    VALUES (:name, :email, :password, :role, :status, :created_at, :updated_at)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => $data['role'],
                'status' => $data['status'] ?? 'Pending',
                'created_at' => $data['created_at'] ?? date('Y-m-d H:i:s'),
                'updated_at' => $data['updated_at'] ?? date('Y-m-d H:i:s')
            ]);
        } catch (PDOException $e) {
            error_log("Error creating user: " . $e->getMessage());
            throw new RuntimeException("Error while creating the user.", 0, $e);
        }
    }

    /**
     * Find a user by email.
     *
     * @param string $email The user's email.
     * @return array|null The user data or null if not found.
     */
    public function findByEmail(string $email): ?array
    {
        try {
            $sql = "SELECT * FROM Users WHERE email = :email";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['email' => $email]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            error_log("Error finding user by email: " . $e->getMessage());
            throw new RuntimeException("Error while searching for the user.", 0, $e);
        }
    }

    /**
     * Verify user credentials.
     *
     * @param string $email The user's email.
     * @param string $password The user's password.
     * @return array|null The user data if credentials are valid, otherwise null.
     */
    public function verifyCredentials(string $email, string $password): ?array
    {
        // Fetch the user by email
        $user = $this->findByEmail($email);
    
        // Verify the password and return the user data including status
        if ($user && password_verify($password, $user['password'])) {
            return [
                'id' => $user['id'], // Assuming 'id' is a field in the user table
                'name' => $user['name'], // Assuming 'name' is a field in the user table
                'email' => $user['email'], // Assuming 'email' is a field in the user table
                'role' => $user['role'], // Assuming 'role' is a field in the user table
                'status' => $user['status'] // Assuming 'status' is a field in the user table
            ];
        }
    
        return null; // Return null if credentials are invalid
    }

    /**
     * Update user status.
     *
     * @param int $userId The user's ID.
     * @param string $status The new status.
     * @return bool True if the status was successfully updated, false otherwise.
     */
    public function updateStatus(int $userId, string $status): bool
    {
        try {
            $sql = "UPDATE Users SET status = :status, updated_at = :updated_at WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                'status' => $status,
                'updated_at' => date('Y-m-d H:i:s'),
                'id' => $userId
            ]);
        } catch (PDOException $e) {
            error_log("Error updating user status: " . $e->getMessage());
            throw new RuntimeException("Error while updating the user's status.", 0, $e);
        }
    }

    public function logout(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_unset();
        session_destroy();
    }
}