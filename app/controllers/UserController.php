<?php

// Inclure le modèle User
require_once __DIR__ . '/../models/User.php';

class UserController
{
    private $userModel;

    // Constructor
    public function __construct($pdo)
    {
        $this->userModel = new User($pdo);
    }

    // Method to create an account
    public function createAccount()
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // var_dump($_POST); // Affiche les données du formulaire
            // exit; // Arrête l'exécution pour vérifier les données
            $fullName = $_POST['fullName'] ?? '';
            $email = $_POST['email'] ?? '';
            $passWord = $_POST['passWord'] ?? '';
            $confirmpassWord = $_POST['confirmpassWord'] ?? '';
            $role = $_POST['role'] ?? '';

            // Validation
            if (empty($fullName)) {
                $errors['fullName'] = "Full Name is required.";
            }
            if (empty($email)) {
                $errors['email'] = "Email is required.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Please enter a valid email address.";
            }
            if (empty($passWord)) {
                $errors['passWord'] = "passWord is required.";
            } elseif (strlen($passWord) < 8) {
                $errors['passWord'] = "passWord must be at least 8 characters long.";
            }
            if (empty($confirmpassWord)) {
                $errors['confirmpassWord'] = "Please confirm your passWord.";
            } elseif ($passWord !== $confirmpassWord) {
                $errors['confirmpassWord'] = "passWords do not match.";
            }
            if (empty($role)) {
                $errors['role'] = "Role is required.";
            }

            // If no errors, proceed
            if (empty($errors)) {
                // Hash the passWord
                $hashedpassWord = passWord_hash($passWord, passWord_BCRYPT);

                // Create user data array
                $userData = [
                    'fullName' => $fullName,
                    'email' => $email,
                    'passWord' => $hashedpassWord,
                    'role' => $role
                ];

                // Create the user
                if ($this->userModel->createAccount($userData)) {
                    echo "Account created successfully!";
                    header('Location: /login');
                    exit();
                } else {
                    $errors[] = "Error while creating the account.";
                }
            }
        }

        // Pass errors to the view
        // include __DIR__ . '/../views/users/register.php';
    }
}