<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HuebraSoft - Obras y Proyectos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: {
                            50: '#eff6ff', 100: '#dbeafe', 500: '#3b82f6',
                            600: '#2563eb', 900: '#1e3a8a', 950: '#172554',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .glass-nav { background: rgba(255,255,255,.9); backdrop-filter: blur(10px); border-top: 1px solid #f1f5f9; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased flex h-screen overflow-hidden">
<?php
$userName = $_SESSION['user']['nombre'] ?? 'Usuario';
$userRole = $_SESSION['user']['rol'] ?? 'operario';
$metrics = $data['metrics'] ?? [];
$projects = $data['projects'] ?? [];
$clientes = $data['clientes'] ?? [];

function projectStatusBadge(string $estado): array {
    if ($estado === 'En curso') return ['bg-emerald-50 text-emerald-700 border-emerald-200', 'bg-emerald-500'];
    if ($estado === 'Proximo') return ['bg-blue-50 text-blue-700 border-blue-200', 'bg-blue-500'];
    if ($estado === 'Pausado') return ['bg-amber-50 text-amber-700 border-amber-200', 'bg-amber-500'];
    return ['bg-slate-50 text-slate-700 border-slate-200', 'bg-slate-500'];
}
?>

    <aside class="hidden md:flex flex-col w-64 bg-white border-r border-slate-200 h-full">
        <div class="p-6 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-brand-600 flex items-center justify-center text-white font-bold text-xl">H</div>
            <span class="font-bold text-xl tracking-tight text-slate-900">Huebra<span class="text-brand-600 font-normal">Soft</span></span>
        </div>

        <nav class="flex-1 px-4 space-y-1 mt-4">
            <a href="index.php?page=dashboard" class="flex items-center gap-3 px-3 py-2.5 text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-xl font-medium transition-colors">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i> Dashboard
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 bg-brand-50 text-brand-600 rounded-xl font-medium">
                <i data-lucide="hard-hat" class="w-5 h-5"></i> Obras / Proyectos
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-xl font-medium transition-colors">
                <i data-lucide="users" class="w-5 h-5"></i> Trabajadores
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-xl font-medium transition-colors">
                <i data-lucide="package" class="w-5 h-5"></i> Materiales
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-xl font-medium transition-colors">
                <i data-lucide="bar-chart-3" class="w-5 h-5"></i> Informes
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-xl font-medium transition-colors">
                <i data-lucide="users-round" class="w-5 h-5"></i> Clientes
            </a>
            <a href="index.php?page=logout" class="flex items-center gap-3 px-3 py-2.5 text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-xl font-medium transition-colors">
                <i data-lucide="log-out" class="w-5 h-5"></i> Salir
            </a>
        </nav>

        <div class="p-4 border-t border-slate-200">
            <div class="flex items-center gap-3">
                <img src="https://i.pravatar.cc/150?img=11" alt="Usuario" class="w-10 h-10 rounded-full border border-slate-200">
                <div>
                    <p class="text-sm font-semibold text-slate-900"><?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?></p>
                    <p class="text-xs text-slate-500"><?= htmlspecialchars(ucfirst($userRole), ENT_QUOTES, 'UTF-8') ?></p>
                </div>
            </div>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-full overflow-y-auto relative pb-20 md:pb-0">
        <header class="md:hidden flex items-center justify-between p-5 bg-white border-b border-slate-200 sticky top-0 z-10">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-md bg-brand-600 flex items-center justify-center text-white font-bold text-lg">H</div>
                <span class="font-bold text-lg text-slate-900">Huebra<span class="text-brand-600 font-normal">Soft</span></span>
            </div>
            <img src="https://i.pravatar.cc/150?img=11" alt="Usuario" class="w-8 h-8 rounded-full border border-slate-200">
        </header>

        <div class="p-5 md:p-8 max-w-6xl mx-auto w-full">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-slate-900">Obras y Proyectos</h1>
                    <p class="text-slate-600 mt-1">Gestiona el estado y progreso de todas tus obras en un solo lugar.</p>
                </div>

                <button onclick="toggleModal('modalNuevaObra')" class="bg-brand-600 hover:bg-brand-700 text-white px-5 py-2.5 rounded-xl font-semibold shadow-md shadow-brand-500/20 transition-all flex items-center justify-center gap-2 transform hover:scale-[1.02] active:scale-95 whitespace-nowrap">
                    <i data-lucide="plus" class="w-5 h-5"></i>
                    Nueva Obra
                </button>
            </div>

            <div class="flex flex-col md:flex-row gap-4 mb-8">
                <div class="relative flex-1">
                    <i data-lucide="search" class="absolute left-3.5 top-1/2 transform -translate-y-1/2 text-slate-400 w-5 h-5"></i>
                    <input type="text" placeholder="Buscar por nombre, cliente o ubicación..." class="w-full pl-11 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 text-sm md:text-base shadow-sm">
                </div>

                <div class="flex gap-2 overflow-x-auto hide-scrollbar pb-1 md:pb-0">
                    <button class="px-4 py-2 bg-slate-800 text-white rounded-lg text-sm font-medium whitespace-nowrap shadow-sm">Todas</button>
                    <button class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-lg text-sm font-medium whitespace-nowrap shadow-sm">En curso <span class="ml-1 bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded-md text-xs"><?= (int)($metrics['en_curso'] ?? 0) ?></span></button>
                    <button class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-lg text-sm font-medium whitespace-nowrap shadow-sm">Próximas <span class="ml-1 bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded-md text-xs"><?= (int)($metrics['proximas'] ?? 0) ?></span></button>
                    <button class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-lg text-sm font-medium whitespace-nowrap shadow-sm">Pausadas <span class="ml-1 bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded-md text-xs"><?= (int)($metrics['pausadas'] ?? 0) ?></span></button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <?php if (!$projects): ?>
                    <div class="col-span-full bg-white rounded-2xl border border-slate-200 p-8 text-center text-slate-500">No hay obras todavía.</div>
                <?php endif; ?>

                <?php foreach ($projects as $project): ?>
                    <?php [$badgeClass, $dotClass] = projectStatusBadge($project['estado']); ?>
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow flex flex-col h-full <?= $project['estado'] === 'Pausado' ? 'opacity-80' : '' ?>">
                        <div class="p-5 flex-1">
                            <div class="flex justify-between items-start mb-3">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium border <?= $badgeClass ?>">
                                    <span class="w-1.5 h-1.5 rounded-full <?= $dotClass ?>"></span> <?= htmlspecialchars($project['estado'], ENT_QUOTES, 'UTF-8') ?>
                                </span>
                                <button class="text-slate-400 hover:text-brand-600 transition-colors"><i data-lucide="more-horizontal" class="w-5 h-5"></i></button>
                            </div>

                            <h3 class="font-bold text-lg text-slate-900 leading-tight mb-2"><?= htmlspecialchars($project['nombre'], ENT_QUOTES, 'UTF-8') ?></h3>

                            <div class="space-y-2 mt-4">
                                <div class="flex items-center gap-2 text-sm text-slate-600"><i data-lucide="user" class="w-4 h-4 text-slate-400"></i><span><?= htmlspecialchars($project['cliente_nombre'] ?? 'Sin cliente', ENT_QUOTES, 'UTF-8') ?></span></div>
                                <div class="flex items-center gap-2 text-sm text-slate-600"><i data-lucide="map-pin" class="w-4 h-4 text-slate-400"></i><span class="truncate">Ubicación no definida</span></div>
                            </div>
                        </div>

                        <div class="px-5 py-4 border-t border-slate-100 bg-slate-50 rounded-b-2xl flex items-center justify-between">
                            <?php if ($project['estado'] === 'Proximo' && !empty($project['fecha_inicio'])): ?>
                                <div class="flex items-center gap-2 text-sm text-slate-500"><i data-lucide="calendar" class="w-4 h-4"></i> Inicio: <?= htmlspecialchars(date('d M', strtotime($project['fecha_inicio'])), ENT_QUOTES, 'UTF-8') ?></div>
                            <?php elseif ($project['estado'] === 'Pausado'): ?>
                                <div class="flex flex-col"><span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Motivo</span><span class="text-sm font-medium text-slate-600">Esperando Materiales</span></div>
                            <?php else: ?>
                                <div class="flex flex-col"><span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Horas Totales</span><span class="text-sm font-semibold text-slate-700"><?= (float)$project['horas_totales'] ?>h</span></div>
                            <?php endif; ?>

                            <button class="text-sm font-medium text-brand-600 hover:text-brand-700 flex items-center gap-1">Ver detalles <i data-lucide="chevron-right" class="w-4 h-4"></i></button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="mt-8 text-center">
                <button class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl transition-colors shadow-sm">Cargar proyectos finalizados</button>
            </div>
        </div>
    </main>

    <nav class="md:hidden glass-nav fixed bottom-0 left-0 right-0 z-40 px-6 py-3 pb-safe">
        <div class="flex justify-between items-center max-w-md mx-auto">
            <a href="index.php?page=dashboard" class="flex flex-col items-center gap-1 text-slate-400 hover:text-slate-600 transition-colors"><i data-lucide="layout-dashboard" class="w-6 h-6"></i><span class="text-[10px] font-medium">Inicio</span></a>
            <a href="#" class="flex flex-col items-center gap-1 text-brand-600"><i data-lucide="hard-hat" class="w-6 h-6"></i><span class="text-[10px] font-medium">Obras</span></a>
            <a href="#" class="flex flex-col items-center gap-1 text-slate-400 hover:text-slate-600 transition-colors"><i data-lucide="users" class="w-6 h-6"></i><span class="text-[10px] font-medium">Equipo</span></a>
            <a href="#" class="flex flex-col items-center gap-1 text-slate-400 hover:text-slate-600 transition-colors"><i data-lucide="bar-chart-3" class="w-6 h-6"></i><span class="text-[10px] font-medium">Informes</span></a>
        </div>
    </nav>

    <div id="modalNuevaObra" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden opacity-0 transition-opacity flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden transform scale-95 transition-transform flex flex-col max-h-[90vh]" id="modalContent">
            <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50 sticky top-0">
                <h3 class="font-bold text-lg text-slate-900 flex items-center gap-2"><i data-lucide="hard-hat" class="w-5 h-5 text-brand-600"></i> Nueva Obra</h3>
                <button onclick="toggleModal('modalNuevaObra')" class="text-slate-400 hover:text-slate-700 bg-white rounded-full p-1 border border-slate-200 shadow-sm"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>

            <div class="p-6 space-y-4 overflow-y-auto">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nombre de la Obra *</label>
                    <input type="text" placeholder="Ej. Reforma Baños Planta 2" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-slate-700 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Cliente Asociado *</label>
                    <div class="relative">
                        <select class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-slate-700 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 appearance-none bg-white">
                            <option value="" disabled selected>Selecciona un cliente</option>
                            <?php foreach ($clientes as $cliente): ?>
                                <option value="<?= (int)$cliente['id'] ?>"><?= htmlspecialchars($cliente['nombre'], ENT_QUOTES, 'UTF-8') ?></option>
                            <?php endforeach; ?>
                        </select>
                        <i data-lucide="chevron-down" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-slate-400 w-4 h-4 pointer-events-none"></i>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Localización</label>
                    <div class="relative">
                        <i data-lucide="map-pin" class="absolute left-3.5 top-1/2 transform -translate-y-1/2 text-slate-400 w-4 h-4"></i>
                        <input type="text" placeholder="Dirección o población" class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-xl text-slate-700 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Estado</label>
                        <div class="relative">
                            <select class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-slate-700 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 appearance-none bg-white">
                                <option>Proximo</option>
                                <option>En curso</option>
                                <option>Pausado</option>
                            </select>
                            <i data-lucide="chevron-down" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-400 w-4 h-4 pointer-events-none"></i>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Fecha de Inicio</label>
                        <input type="date" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-slate-700 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Descripción corta (Opcional)</label>
                    <textarea rows="2" placeholder="Detalles o alcance del proyecto..." class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-slate-700 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 resize-none"></textarea>
                </div>
            </div>

            <div class="p-5 border-t border-slate-100 bg-slate-50 flex justify-end gap-3 sticky bottom-0">
                <button onclick="toggleModal('modalNuevaObra')" class="px-5 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-200 rounded-xl transition-colors">Cancelar</button>
                <button class="px-5 py-2.5 text-sm font-medium text-white bg-brand-600 hover:bg-brand-700 rounded-xl transition-colors shadow-sm flex items-center gap-2">Crear Obra</button>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
        let isModalOpen = false;

        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            const modalContent = modal.querySelector('#modalContent');
            isModalOpen = !isModalOpen;

            if (isModalOpen) {
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.remove('opacity-0');
                    modalContent.classList.remove('scale-95');
                }, 10);
            } else {
                modal.classList.add('opacity-0');
                modalContent.classList.add('scale-95');
                setTimeout(() => modal.classList.add('hidden'), 300);
            }
        }
    </script>
</body>
</html>
