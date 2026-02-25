<?php
require_once __DIR__ . '/../config/db.php';

class AuthController
{
    public function login(string $identifier, string $password): bool
    {
        $pdo = getPDO();

        $sql = 'SELECT id, nombre, usuario, email, password_hash, rol, activo
                FROM usuarios
                WHERE email = :identifier OR usuario = :identifier
                LIMIT 1';

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['identifier' => $identifier]);
        $user = $stmt->fetch();

        if (!$user || (int)$user['activo'] !== 1) {
            return false;
        }

        if (!password_verify($password, $user['password_hash'])) {
            return false;
        }

        $_SESSION['user'] = [
            'id' => (int)$user['id'],
            'nombre' => $user['nombre'],
            'usuario' => $user['usuario'],
            'email' => $user['email'],
            'rol' => $user['rol'],
        ];

        return true;
    }

    public function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
    }
}
