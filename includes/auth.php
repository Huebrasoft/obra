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
    return $user && isset($user['rol']) && $user['rol'] === $role;
}

function requireAdmin() {
    if (!hasRole('administrador')) {
        flash('error', 'No tienes permisos para acceder.');
        redirect('index.php');
    }
}

function verifyPasswordFlexible($plainPassword, $storedHash) {
    $plainPassword = (string)$plainPassword;
    $storedHash = (string)$storedHash;

    if ($storedHash === '') {
        return false;
    }

    if (password_verify($plainPassword, $storedHash)) {
        return true;
    }

    if (hash_equals($storedHash, $plainPassword)) {
        return true;
    }

    $md5 = md5($plainPassword);
    $sha1 = sha1($plainPassword);
    $sha256 = hash('sha256', $plainPassword);

    if (hash_equals($storedHash, $md5) || hash_equals($storedHash, $sha1) || hash_equals($storedHash, $sha256)) {
        return true;
    }

    // Algunos sistemas guardan hashes en mayúsculas
    if (strcasecmp($storedHash, $md5) === 0 || strcasecmp($storedHash, $sha1) === 0 || strcasecmp($storedHash, $sha256) === 0) {
        return true;
    }

    // Otros guardan hash en base64
    if (hash_equals($storedHash, base64_encode(pack('H*', $md5))) || hash_equals($storedHash, base64_encode(pack('H*', $sha1)))) {
        return true;
    }

    return false;
}

function loginUser($username, $password) {
    $username = trim((string)$username);
    $password = trim((string)$password);

    $userModel = new User();
    $user = $userModel->findByUsername($username);

    if (!$user) {
        return false;
    }

    $storedPassword = $userModel->getStoredPassword($user);
    if (!verifyPasswordFlexible($password, $storedPassword)) {
        return false;
    }

    $info = password_get_info($storedPassword);
    $needsUpgrade = true;
    if (!empty($info['algo']) && (string)$info['algoName'] !== 'unknown') {
        $needsUpgrade = password_needs_rehash($storedPassword, PASSWORD_DEFAULT);
    }

    if ($needsUpgrade) {
        $newHash = password_hash($password, PASSWORD_DEFAULT);
        $userModel->updatePasswordHash((int)$user['id'], $newHash);
    }

    $_SESSION['user'] = array(
        'id' => isset($user['id']) ? $user['id'] : 0,
        'username' => isset($user['username']) ? $user['username'] : (isset($user['usuario']) ? $user['usuario'] : $username),
        'nombre' => isset($user['nombre']) ? $user['nombre'] : (isset($user['username']) ? $user['username'] : $username),
        'rol' => isset($user['rol']) ? $user['rol'] : 'operario',
    );

    return true;
}

function logoutUser() {
    unset($_SESSION['user']);
    session_regenerate_id(true);
}
