<?php include __DIR__ . '/../layout/header.php'; ?>

<section class="panel">
    <div class="panel-head">
        <div>
            <p class="eyebrow">Parte diario</p>
            <h1>Nuevo parte</h1>
        </div>
        <button type="button" class="btn-primary" id="btnGuardarParte">Guardar parte</button>
    </div>

    <div id="parteAlert" class="alert hidden"></div>

    <form id="parteForm" class="stack">
        <div class="grid-2">
            <div>
                <label>Proyecto *</label>
                <select name="proyecto_id" id="proyecto_id" required>
                    <option value="">Selecciona proyecto</option>
                    <?php foreach ($data['proyectos'] as $proyecto): ?>
                        <option value="<?= (int)$proyecto['id'] ?>"><?= htmlspecialchars($proyecto['nombre'], ENT_QUOTES, 'UTF-8') ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label>Fecha *</label>
                <input type="date" name="fecha" value="<?= date('Y-m-d') ?>" required>
            </div>
        </div>

        <div>
            <label>Tarea (opcional)</label>
            <select name="tarea_id" id="tarea_id">
                <option value="">Sin tarea</option>
                <?php foreach ($data['tareas'] as $tarea): ?>
                    <option value="<?= (int)$tarea['id'] ?>" data-proyecto="<?= (int)$tarea['proyecto_id'] ?>">
                        <?= htmlspecialchars($tarea['nombre'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="card-block">
            <div class="row-between"><h3>Trabajadores y horas</h3><button type="button" class="btn-soft" data-add-row="trabajadores">+ Añadir</button></div>
            <div id="rowsTrabajadores"></div>
        </div>

        <div class="card-block">
            <div class="row-between"><h3>Materiales y cantidades</h3><button type="button" class="btn-soft" data-add-row="materiales">+ Añadir</button></div>
            <div id="rowsMateriales"></div>
        </div>

        <div class="card-block">
            <div class="row-between"><h3>Maquinaria y horas</h3><button type="button" class="btn-soft" data-add-row="maquinaria">+ Añadir</button></div>
            <div id="rowsMaquinaria"></div>
        </div>

        <div>
            <label>Notas</label>
            <textarea name="notas" rows="4" placeholder="Anotaciones del día..."></textarea>
        </div>
    </form>
</section>

<script>
window.PARTE_DATA = <?= json_encode($data, JSON_UNESCAPED_UNICODE) ?>;
</script>
<script src="assets/js/parte_diario.js"></script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
