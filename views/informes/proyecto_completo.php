<?php include __DIR__ . '/../layout/header.php'; ?>
<section class="card">
    <h2>Informe completo por proyecto</h2>
    <form method="get" class="inline-form">
        <input type="hidden" name="page" value="informe_proyecto">
        <select name="proyecto_id" required>
            <option value="">Proyecto...</option>
            <?php foreach ($proyectos as $p): ?>
                <option value="<?= (int)$p['id'] ?>" <?= $proyectoId === (int)$p['id'] ? 'selected' : '' ?>><?= e($p['nombre']) ?></option>
            <?php endforeach; ?>
        </select>
        <button class="btn">Ver</button>
        <?php if ($proyectoId > 0): ?>
            <a class="btn btn-light" href="index.php?page=informe_proyecto&proyecto_id=<?= $proyectoId ?>&export=csv">CSV</a>
        <?php endif; ?>
    </form>

    <?php if ($proyecto && $informe): ?>
        <h3><?= e($proyecto['nombre']) ?></h3>
        <p>Total horas: <strong><?= number_format((float)$informe['mano_obra']['horas'], 2, ',', '.') ?></strong></p>
        <p>Coste total proyecto: <strong><?= number_format((float)$informe['totales']['total'], 2, ',', '.') ?> €</strong></p>

        <h4>Detalle por trabajador</h4>
        <table><tr><th>Trabajador</th><th>Horas</th><th>Coste</th></tr>
        <?php foreach ($informe['detalle_trabajadores'] as $d): ?>
            <tr><td><?= e($d['nombre']) ?></td><td><?= e($d['horas']) ?></td><td><?= number_format((float)$d['coste'],2,',','.') ?></td></tr>
        <?php endforeach; ?>
        </table>

        <h4>Materiales</h4>
        <table><tr><th>Material</th><th>Cantidad</th><th>Coste</th></tr>
        <?php foreach ($informe['materiales'] as $m): ?>
            <tr><td><?= e($m['nombre']) ?></td><td><?= e($m['cantidad']) ?> <?= e($m['unidad_medida']) ?></td><td><?= number_format((float)$m['coste'],2,',','.') ?></td></tr>
        <?php endforeach; ?>
        </table>

        <h4>Maquinaria</h4>
        <table><tr><th>Máquina</th><th>Horas</th><th>Coste</th></tr>
        <?php foreach ($informe['maquinaria'] as $q): ?>
            <tr><td><?= e($q['nombre']) ?></td><td><?= e($q['horas']) ?></td><td><?= number_format((float)$q['coste'],2,',','.') ?></td></tr>
        <?php endforeach; ?>
        </table>
    <?php endif; ?>
</section>
<?php include __DIR__ . '/../layout/footer.php'; ?>
