<?php $user = currentUser(); $currentPage = $_GET['page'] ?? 'dashboard'; ?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Obra App', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="app-shell">
<aside class="sidebar-desktop">
    <div class="brand-row">
        <div class="logo-box">H</div>
        <span class="brand-title">Huebra<span>Soft</span></span>
    </div>

    <nav class="side-nav">
        <a class="<?= $currentPage === 'dashboard' ? 'active' : '' ?>" href="index.php?page=dashboard"><i data-lucide="layout-dashboard"></i>Dashboard</a>
        <a class="<?= $currentPage === 'nuevo-parte' ? 'active' : '' ?>" href="index.php?page=nuevo-parte"><i data-lucide="file-plus-2"></i>Partes diarios</a>
        <a href="#"><i data-lucide="hard-hat"></i>Obras / Proyectos</a>
        <a href="#"><i data-lucide="users"></i>Trabajadores</a>
        <a href="#"><i data-lucide="package"></i>Materiales</a>
        <a href="#"><i data-lucide="bar-chart-3"></i>Informes</a>
        <a href="#"><i data-lucide="users-round"></i>Clientes</a>
        <?php if (($user['rol'] ?? '') === 'admin'): ?>
            <a class="<?= $currentPage === 'usuarios' ? 'active' : '' ?>" href="index.php?page=usuarios"><i data-lucide="shield"></i>Usuarios</a>
        <?php endif; ?>
    </nav>

    <div class="sidebar-user">
        <div class="avatar"><?= strtoupper(substr($user['nombre'] ?? 'U', 0, 1)) ?></div>
        <div>
            <p><?= htmlspecialchars($user['nombre'] ?? 'Usuario', ENT_QUOTES, 'UTF-8') ?></p>
            <small><?= htmlspecialchars($user['rol'] ?? '', ENT_QUOTES, 'UTF-8') ?></small>
        </div>
    </div>
</aside>

<main class="main-zone">
    <header class="mobile-header">
        <div class="brand-row">
            <div class="logo-box">H</div>
            <span class="brand-title">Huebra<span>Soft</span></span>
        </div>
        <a class="avatar small" href="index.php?page=mi-perfil"><?= strtoupper(substr($user['nombre'] ?? 'U', 0, 1)) ?></a>
    </header>

    <div class="page-wrap">
