<?php include __DIR__ . '/layout/header.php'; ?>
<section class="panel">
    <h1>Mi perfil</h1>
    <p>Usuario: <?= htmlspecialchars(currentUser()['usuario'] ?? '-', ENT_QUOTES, 'UTF-8') ?></p>
    <p>Email: <?= htmlspecialchars(currentUser()['email'] ?? '-', ENT_QUOTES, 'UTF-8') ?></p>
    <p>Rol: <?= htmlspecialchars(currentUser()['rol'] ?? '-', ENT_QUOTES, 'UTF-8') ?></p>
</section>
<?php include __DIR__ . '/layout/footer.php'; ?>
