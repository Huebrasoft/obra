<?php include __DIR__ . '/../layout/header.php'; ?>
<section class="card">
    <h2>Informe mensual por trabajador</h2>
    <form method="get" class="inline-form">
        <input type="hidden" name="page" value="informe_trabajador">
        <select name="trabajador_id" required>
            <option value="">Trabajador...</option>
            <?php foreach ($trabajadores as $t): ?>
                <option value="<?= (int)$t['id'] ?>" <?= $trabajadorId === (int)$t['id'] ? 'selected' : '' ?>><?= e($t['nombre']) ?></option>
            <?php endforeach; ?>
        </select>
        <input type="month" name="mes" value="<?= e($mes) ?>" required>
        <button class="btn">Ver</button>
        <?php if ($trabajadorId > 0): ?>
            <a class="btn btn-light" href="index.php?page=informe_trabajador&trabajador_id=<?= $trabajadorId ?>&mes=<?= e($mes) ?>&export=csv">CSV</a>
        <?php endif; ?>
    </form>

    <?php if ($trabajadorId > 0): ?>
        <table>
            <tr><th>Día</th><th>Horas</th><th>Coste (€)</th></tr>
            <?php foreach ($resultado['filas'] as $f): ?>
            <tr><td><?= e($f['dia']) ?></td><td><?= e($f['horas']) ?></td><td><?= number_format((float)$f['coste'],2,',','.') ?></td></tr>
            <?php endforeach; ?>
            <tr><th>Total</th><th><?= number_format($resultado['total_horas'],2,',','.') ?></th><th><?= number_format($resultado['total_coste'],2,',','.') ?></th></tr>
        </table>
    <?php endif; ?>
</section>
<?php include __DIR__ . '/../layout/footer.php'; ?>
