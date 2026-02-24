<?php $user = currentUser(); ?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Obra App', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="topbar">
    <div>
        <strong>Obra App</strong>
        <small><?= htmlspecialchars($user['rol'] ?? '', ENT_QUOTES, 'UTF-8') ?></small>
    </div>
    <nav>
        <a href="index.php?page=dashboard">Inicio</a>
        <a href="index.php?page=mi-perfil">Mi perfil</a>
        <?php if (($user['rol'] ?? '') === 'admin'): ?>
            <a href="index.php?page=usuarios">Usuarios</a>
        <?php endif; ?>
        <a href="index.php?page=logout">Salir</a>
    </nav>
</header>
<main class="container">
