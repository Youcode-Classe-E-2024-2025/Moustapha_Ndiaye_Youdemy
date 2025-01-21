<?php
require_once __DIR__ . '/../../app/models/User.php';

class UserController
{
    private $userModel;

    public function __construct($pdo)
    {
        $this->userModel = new User($pdo);
    }

    /**
     * Create a new user account.
     *
     * @param array $data The form data (name, email, password, role).
     * @return array An array containing a success status and any errors.
     */
    public function createAccount(array $data): array
    {
        // Validate the data
        $errors = $this->validateRegistrationData($data);

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        try {
            // Hash the password
            $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

            // Prepare the data for the model
            $userData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $hashedPassword,
                'role' => $data['role']
            ];

            // Use the model to create the user
            if ($this->userModel->createUser($userData)) {
                return ['success' => true, 'errors' => []];
            } else {
                return ['success' => false, 'errors' => ['database' => "An error occurred while creating the account."]];
            }
        } catch (PDOException $e) {
            error_log("Error while creating the account: " . $e->getMessage());
            return ['success' => false, 'errors' => ['database' => "An error occurred while creating the account. Please try again later."]];
        }
    }

    /**
     * Validate registration data.
     *
     * @param array $data The form data (name, email, password, confirmPassword, role).
     * @return array An array of validation errors.
     */
    private function validateRegistrationData(array $data): array
    {
        $errors = [];

        // Validate name
        if (empty($data['name'])) {
            $errors['name'] = "Full Name is required.";
        }

        // Validate email
        if (empty($data['email'])) {
            $errors['email'] = "Email is required.";
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Please enter a valid email address.";
        }

        // Validate password
        if (empty($data['password'])) {
            $errors['password'] = "Password is required.";
        } elseif (strlen($data['password']) < 8) {
            $errors['password'] = "Password must be at least 8 characters long.";
        }

        // Validate confirmPassword
        if (empty($data['confirmPassword'])) {
            $errors['confirmPassword'] = "Please confirm your password.";
        } elseif ($data['password'] !== $data['confirmPassword']) {
            $errors['confirmPassword'] = "Passwords do not match.";
        }

        // Validate role
        if (empty($data['role'])) {
            $errors['role'] = "Role is required.";
        }

        return $errors;
    }
}