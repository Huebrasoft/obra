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

function verifyPasswordFlexible($plainPassword, $storedHash) {
    $storedHash = (string)$storedHash;

    // Formato recomendado
    if ($storedHash !== '' && password_verify($plainPassword, $storedHash)) {
        return true;
    }

    // Compatibilidad legacy: contraseña en texto plano
    if (hash_equals($storedHash, (string)$plainPassword)) {
        return true;
    }

    // Compatibilidad legacy: MD5 / SHA1
    if (hash_equals($storedHash, md5($plainPassword)) || hash_equals($storedHash, sha1($plainPassword))) {
        return true;
    }

    return false;
}

function loginUser($username, $password) {
    $username = trim((string)$username);
    $password = (string)$password;

    $userModel = new User();
    $user = $userModel->findByUsername($username);

    if (!$user || (int)$user['activo'] !== 1) {
        return false;
    }

    if (!verifyPasswordFlexible($password, $user['password_hash'])) {
        return false;
    }

    // Rehash automático a formato seguro actual
    $needsUpgrade = true;
    $info = password_get_info((string)$user['password_hash']);
    if (!empty($info['algo']) && (string)$info['algoName'] !== 'unknown') {
        $needsUpgrade = password_needs_rehash((string)$user['password_hash'], PASSWORD_DEFAULT);
    }

    if ($needsUpgrade) {
        $newHash = password_hash($password, PASSWORD_DEFAULT);
        $userModel->updatePasswordHash((int)$user['id'], $newHash);
    }

    $_SESSION['user'] = array(
        'id' => $user['id'],
        'username' => $user['username'],
        'nombre' => $user['nombre'],
        'rol' => $user['rol'],
    );
    return true;
}

function logoutUser() {
    unset($_SESSION['user']);
    session_regenerate_id(true);
}
