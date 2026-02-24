<?php $user = authUser(); ?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e(APP_NAME) ?></title>
    <link rel="stylesheet" href="<?= e(appUrl('assets/css/styles.css')) ?>">
</head>
<body>
<header class="topbar">
    <h1><?= e(APP_NAME) ?></h1>
    <?php if ($user): ?>
        <div class="user-info">
            <span><?= e($user['nombre']) ?> (<?= e($user['rol']) ?>)</span>
            <a class="btn btn-sm" href="<?= e(appUrl('index.php?page=logout')) ?>">Salir</a>
        </div>
    <?php endif; ?>
</header>
<?php if ($user): ?>
<nav class="nav">
    <a href="<?= e(appUrl('index.php')) ?>">Dashboard</a>
    <a href="<?= e(appUrl('index.php?page=parte_nuevo')) ?>">➕ Nuevo Parte Diario</a>
    <a href="<?= e(appUrl('index.php?page=informe_trabajador')) ?>">Informe trabajador</a>
    <a href="<?= e(appUrl('index.php?page=informe_proyecto')) ?>">Informe proyecto</a>
</nav>
<?php endif; ?>
<main class="container">
<?php foreach (getFlashes() as $f): ?>
    <div class="flash <?= e($f['type']) ?>"><?= e($f['message']) ?></div>
<?php endforeach; ?>
