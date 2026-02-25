<?php include __DIR__ . '/layout/header.php'; ?>
<section class="panel">
    <div class="panel-head">
        <div>
            <p class="eyebrow" id="currentDate">Hoy</p>
            <h1>Hola, <?= htmlspecialchars(currentUser()['nombre'] ?? 'equipo', ENT_QUOTES, 'UTF-8') ?> 👋</h1>
            <p>Resumen rápido de la obra.</p>
        </div>
        <a class="btn-primary" href="index.php?page=nuevo-parte">Nuevo Parte Diario</a>
    </div>

    <div class="metrics">
        <article><small>Obras activas</small><strong>4</strong></article>
        <article><small>Personal hoy</small><strong>12</strong></article>
        <article><small>Horas semana</small><strong>148h</strong></article>
        <article><small>Notas pendientes</small><strong>2</strong></article>
    </div>
</section>

<section class="panel">
    <div class="row-between">
        <h2>Obras en curso</h2>
    </div>
    <div class="project-grid">
        <article class="project-card"><span class="tag">En curso</span><h3>Reforma nave industrial</h3><p>Polígono Los Villares</p></article>
        <article class="project-card"><span class="tag">En curso</span><h3>Cimentación vivienda</h3><p>Carbajosa de la Sagrada</p></article>
        <article class="project-card"><span class="tag tag-warn">Pausado</span><h3>Adecuación local</h3><p>Centro ciudad</p></article>
    </div>
</section>
<?php include __DIR__ . '/layout/footer.php'; ?>
