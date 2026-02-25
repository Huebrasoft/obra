<?php include __DIR__ . '/layout/header.php'; ?>

<section class="panel">
    <div class="panel-head">
        <div>
            <p class="eyebrow" id="currentDate">Hoy</p>
            <h1>Hola, <?= htmlspecialchars(currentUser()['nombre'] ?? 'equipo', ENT_QUOTES, 'UTF-8') ?> 👋</h1>
            <p>Aquí tienes el resumen de tus obras hoy.</p>
        </div>
        <a class="btn-primary" href="index.php?page=nuevo-parte"><i data-lucide="plus-circle"></i>Nuevo Parte Diario</a>
    </div>

    <div class="metrics">
        <article class="metric-card">
            <div class="metric-top"><i data-lucide="hard-hat"></i><span>Obras Activas</span></div>
            <strong>4</strong>
        </article>
        <article class="metric-card">
            <div class="metric-top"><i data-lucide="users"></i><span>Personal Hoy</span></div>
            <strong>12</strong>
        </article>
        <article class="metric-card">
            <div class="metric-top"><i data-lucide="clock"></i><span>Horas Semana</span></div>
            <strong>148h</strong>
        </article>
        <article class="metric-card">
            <div class="metric-top"><i data-lucide="package"></i><span>Notas Pendientes</span></div>
            <strong>2</strong>
        </article>
    </div>
</section>

<section class="panel">
    <div class="section-head">
        <h2>Obras en Curso</h2>
        <a href="#">Ver todas</a>
    </div>
    <div class="project-grid">
        <article class="project-card">
            <span class="tag">● En curso</span>
            <h3>Reforma Integral Nave Industrial</h3>
            <p>Polígono Los Villares, Salamanca</p>
            <div class="project-meta"><span>Cliente: Acero S.A.</span><i data-lucide="more-vertical"></i></div>
        </article>
        <article class="project-card">
            <span class="tag">● En curso</span>
            <h3>Cimentación Vivienda Unifamiliar</h3>
            <p>Carbajosa de la Sagrada</p>
            <div class="project-meta"><span>Cliente: Familia Pérez</span><i data-lucide="more-vertical"></i></div>
        </article>
        <article class="project-card">
            <span class="tag pause">● Pausado</span>
            <h3>Adecuación Local Comercial</h3>
            <p>Centro Ciudad</p>
            <div class="project-meta"><span>Falta material</span><i data-lucide="more-vertical"></i></div>
        </article>
    </div>
</section>

<?php include __DIR__ . '/layout/footer.php'; ?>
