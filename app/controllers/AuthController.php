<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/session.php';

class AuthController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function register($username, $email, $password)
    {
        if ($this->userModel->register($username, $email, $password)) {
            setFlashMessage('success', 'Registration successful. Please login.');
            header("Location: login.php");
            exit();
        } else {
            setFlashMessage('error', 'Registration failed. Try again.');
            header("Location: register.php");
            exit();
        }
    }
    public function login($email, $password)
    {
        $user = $this->userModel->getUserByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            return true;
        } else {
            return false;  // Ensure failure response
        }
    }
}
