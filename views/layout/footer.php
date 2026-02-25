    </div>

    <nav class="mobile-nav">
        <a class="<?= (($_GET['page'] ?? 'dashboard') === 'dashboard') ? 'active' : '' ?>" href="index.php?page=dashboard"><i data-lucide="layout-dashboard"></i><span>Inicio</span></a>
        <a class="<?= (($_GET['page'] ?? '') === 'nuevo-parte') ? 'active' : '' ?>" href="index.php?page=nuevo-parte"><i data-lucide="file-plus-2"></i><span>Parte</span></a>
        <a href="index.php?page=mi-perfil"><i data-lucide="user"></i><span>Perfil</span></a>
        <a href="index.php?page=logout"><i data-lucide="log-out"></i><span>Salir</span></a>
    </nav>
</main>
<script src="assets/js/app.js"></script>
</body>
</html>
