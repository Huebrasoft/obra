<?php
class AuthController {
    public function login(): void {
        if (isPost()) {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            if ($username === '' || $password === '') {
                flash('error', 'Usuario y contraseña son obligatorios.');
            } elseif (loginUser($username, $password)) {
                redirect('index.php');
            } else {
                flash('error', 'Credenciales inválidas.');
            }
        }
        include __DIR__ . '/../views/auth/login.php';
    }

    public function logout(): void {
        logoutUser();
        redirect('index.php?page=login');
    }
}
