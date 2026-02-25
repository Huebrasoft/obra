<?php include __DIR__ . '/../layout/header.php'; ?>
<section class="grid-2">
    <article class="card">
        <h2>Acciones rápidas</h2>
        <a class="btn btn-big" href="<?= e(appUrl('index.php?page=parte_nuevo')) ?>">➕ Nuevo Parte Diario</a>
    </article>
    <article class="card">
        <h2>Proyectos activos</h2>
        <?php if (!$proyectos): ?><p>Sin proyectos activos.</p><?php endif; ?>
        <ul>
            <?php foreach ($proyectos as $p): ?>
                <li><strong><?= e($p['nombre']) ?></strong> · <?= e($p['estado']) ?> · <?= e($p['cliente_nombre']) ?></li>
            <?php endforeach; ?>
        </ul>
    </article>
</section>
<?php include __DIR__ . '/../layout/footer.php'; ?>
