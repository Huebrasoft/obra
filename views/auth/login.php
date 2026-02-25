<?php include __DIR__ . '/../layout/header.php'; ?>
<section class="card login-card">
    <h2>Acceso</h2>
    <form method="post" class="stack">
        <label>Usuario<input name="username" required></label>
        <label>Contraseña<input type="password" name="password" required></label>
        <button class="btn" type="submit">Entrar</button>
    </form>
    <small>Demo admin/admin123 · operario/operario123</small>
</section>
<?php include __DIR__ . '/../layout/footer.php'; ?>
