<?php $user = currentUser(); ?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Obra App', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="app-shell">
<aside class="sidebar">
    <div class="brand"><span class="logo">H</span> HuebraSoft</div>
    <nav>
        <a href="index.php?page=dashboard">Dashboard</a>
        <a href="index.php?page=nuevo-parte">Nuevo parte</a>
        <a href="index.php?page=mi-perfil">Mi perfil</a>
        <?php if (($user['rol'] ?? '') === 'admin'): ?>
            <a href="index.php?page=usuarios">Usuarios</a>
        <?php endif; ?>
        <a href="index.php?page=logout">Salir</a>
    </nav>
    <div class="user-mini">
        <strong><?= htmlspecialchars($user['nombre'] ?? 'Usuario', ENT_QUOTES, 'UTF-8') ?></strong>
        <small><?= htmlspecialchars($user['rol'] ?? '', ENT_QUOTES, 'UTF-8') ?></small>
    </div>
</aside>
<main class="content-area">
<header class="mobile-head">
    <div class="brand"><span class="logo">H</span> HuebraSoft</div>
</header>
<div class="container">
