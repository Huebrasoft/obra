<?php include __DIR__ . '/../layout/header.php'; ?>
<section class="card" id="parteApp">
    <h2>Nuevo Parte Diario</h2>
    <form id="parteForm" class="stack">
        <label>Proyecto
            <select name="proyecto_id" required>
                <option value="">Selecciona...</option>
                <?php foreach ($proyectos as $p): ?>
                    <option value="<?= (int)$p['id'] ?>"><?= e($p['nombre']) ?> (<?= e($p['cliente_nombre']) ?>)</option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Fecha<input type="date" name="fecha" value="<?= date('Y-m-d') ?>" required></label>
        <label>Tarea (opcional)<input name="tarea" placeholder="Ej: encofrado zona norte"></label>

        <h3>Trabajadores</h3>
        <div id="trabajadoresWrap"></div>
        <button type="button" class="btn btn-light" data-add="trabajador">+ Añadir trabajador</button>

        <h3>Materiales</h3>
        <div id="materialesWrap"></div>
        <button type="button" class="btn btn-light" data-add="material">+ Añadir material</button>

        <h3>Maquinaria</h3>
        <div id="maquinariaWrap"></div>
        <button type="button" class="btn btn-light" data-add="maquinaria">+ Añadir maquinaria</button>

        <label>Notas del día<textarea name="notas" rows="3"></textarea></label>
        <button class="btn btn-big" type="submit">Guardar Parte</button>
    </form>
</section>
<script>
window.PARTE_DATA = {
  trabajadores: <?= json_encode($trabajadores, JSON_UNESCAPED_UNICODE) ?>,
  materiales: <?= json_encode($materiales, JSON_UNESCAPED_UNICODE) ?>,
  maquinaria: <?= json_encode($maquinaria, JSON_UNESCAPED_UNICODE) ?>
};
</script>
<?php include __DIR__ . '/../layout/footer.php'; ?>
