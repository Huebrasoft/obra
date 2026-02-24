<?php
require_once __DIR__ . '/../models/User.php';

function authUser(): ?array {
    return $_SESSION['user'] ?? null;
}

function isLoggedIn(): bool {
    return authUser() !== null;
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        redirect('index.php?page=login');
    }
}

function hasRole(string $role): bool {
    $user = authUser();
    return $user && $user['rol'] === $role;
}

function requireAdmin(): void {
    if (!hasRole('administrador')) {
        flash('error', 'No tienes permisos para acceder.');
        redirect('index.php');
    }
}

function loginUser(string $username, string $password): bool {
    $userModel = new User();
    $user = $userModel->findByUsername($username);

    if ($user && password_verify($password, $user['password_hash']) && (int)$user['activo'] === 1) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'nombre' => $user['nombre'],
            'rol' => $user['rol'],
        ];
        return true;
    }
    return false;
}

function logoutUser(): void {
    unset($_SESSION['user']);
    session_regenerate_id(true);
}
