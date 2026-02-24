<?php
require_once __DIR__ . '/../models/User.php';

function authUser() {
    return isset($_SESSION['user']) ? $_SESSION['user'] : null;
}

function isLoggedIn() {
    return authUser() !== null;
}

function requireLogin() {
    if (!isLoggedIn()) {
        redirect('index.php?page=login');
    }
}

function hasRole($role) {
    $user = authUser();
    return $user && $user['rol'] === $role;
}

function requireAdmin() {
    if (!hasRole('administrador')) {
        flash('error', 'No tienes permisos para acceder.');
        redirect('index.php');
    }
}

function loginUser($username, $password) {
    $userModel = new User();
    $user = $userModel->findByUsername($username);

    if ($user && password_verify($password, $user['password_hash']) && (int)$user['activo'] === 1) {
        $_SESSION['user'] = array(
            'id' => $user['id'],
            'username' => $user['username'],
            'nombre' => $user['nombre'],
            'rol' => $user['rol'],
        );
        return true;
    }
    return false;
}

function logoutUser() {
    unset($_SESSION['user']);
    session_regenerate_id(true);
}
