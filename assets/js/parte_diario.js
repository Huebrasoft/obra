(function () {
  const data = window.PARTE_DATA || {};
  const form = document.getElementById('parteForm');
  const btnGuardar = document.getElementById('btnGuardarParte');
  const alertBox = document.getElementById('parteAlert');

  if (!form || !btnGuardar) return;

  const rowsTrab = document.getElementById('rowsTrabajadores');
  const rowsMat = document.getElementById('rowsMateriales');
  const rowsMaq = document.getElementById('rowsMaquinaria');

  function options(items, valueKey, textKey, suffixKey) {
    return (items || []).map((x) => {
      const suffix = suffixKey ? ` (${x[suffixKey] ?? ''})` : '';
      return `<option value="${x[valueKey]}">${x[textKey]}${suffix}</option>`;
    }).join('');
  }

  function addRow(type) {
    let html = '';

    if (type === 'trabajadores') {
      html = `<div class="line-row line-row-3">
        <select class="tr-id"><option value="">Trabajador</option>${options(data.trabajadores, 'id', 'nombre')}</select>
        <input type="number" class="tr-horas" min="0" step="0.25" placeholder="Horas">
        <button type="button" class="btn-remove">×</button>
      </div>`;
      rowsTrab.insertAdjacentHTML('beforeend', html);
      return;
    }

    if (type === 'materiales') {
      html = `<div class="line-row line-row-3">
        <select class="mt-id"><option value="">Material</option>${options(data.materiales, 'id', 'nombre', 'unidad')}</select>
        <input type="number" class="mt-cantidad" min="0" step="0.01" placeholder="Cantidad">
        <button type="button" class="btn-remove">×</button>
      </div>`;
      rowsMat.insertAdjacentHTML('beforeend', html);
      return;
    }

    html = `<div class="line-row line-row-3">
      <select class="mq-id"><option value="">Máquina</option>${options(data.maquinaria, 'id', 'nombre')}</select>
      <input type="number" class="mq-horas" min="0" step="0.25" placeholder="Horas">
      <button type="button" class="btn-remove">×</button>
    </div>`;
    rowsMaq.insertAdjacentHTML('beforeend', html);
  }

  function bindAddButtons() {
    document.querySelectorAll('[data-add-row]').forEach((btn) => {
      btn.addEventListener('click', () => addRow(btn.dataset.addRow));
    });
  }

  function bindRemoveButtons() {
    [rowsTrab, rowsMat, rowsMaq].forEach((box) => {
      box.addEventListener('click', (e) => {
        if (e.target.classList.contains('btn-remove')) {
          const row = e.target.closest('.line-row');
          if (row) row.remove();
        }
      });
    });
  }

  function bindTaskFilter() {
    const proyecto = document.getElementById('proyecto_id');
    const tarea = document.getElementById('tarea_id');
    if (!proyecto || !tarea) return;

    proyecto.addEventListener('change', () => {
      const pid = proyecto.value;
      Array.from(tarea.options).forEach((option, idx) => {
        if (idx === 0) return;
        option.hidden = option.dataset.proyecto !== pid;
      });
      tarea.value = '';
    });
  }

  function showAlert(msg, ok) {
    alertBox.textContent = msg;
    alertBox.classList.remove('hidden', 'alert-ok', 'alert-error');
    alertBox.classList.add(ok ? 'alert-ok' : 'alert-error');
  }

  function readRows() {
    const trabajadores = Array.from(rowsTrab.querySelectorAll('.line-row')).map((row) => ({
      trabajador_id: Number(row.querySelector('.tr-id').value || 0),
      horas: Number(row.querySelector('.tr-horas').value || 0),
    })).filter((row) => row.trabajador_id > 0 && row.horas > 0);

    const materiales = Array.from(rowsMat.querySelectorAll('.line-row')).map((row) => ({
      material_id: Number(row.querySelector('.mt-id').value || 0),
      cantidad: Number(row.querySelector('.mt-cantidad').value || 0),
    })).filter((row) => row.material_id > 0 && row.cantidad > 0);

    const maquinaria = Array.from(rowsMaq.querySelectorAll('.line-row')).map((row) => ({
      maquinaria_id: Number(row.querySelector('.mq-id').value || 0),
      horas: Number(row.querySelector('.mq-horas').value || 0),
    })).filter((row) => row.maquinaria_id > 0 && row.horas > 0);

    return { trabajadores, materiales, maquinaria };
  }

  async function submitForm() {
    const formData = new FormData(form);
    const rows = readRows();

    if (!formData.get('proyecto_id')) {
      showAlert('Selecciona un proyecto.', false);
      return;
    }

    if (!formData.get('fecha')) {
      showAlert('La fecha es obligatoria.', false);
      return;
    }

    if (rows.trabajadores.length + rows.materiales.length + rows.maquinaria.length === 0) {
      showAlert('Añade al menos un registro con valores válidos.', false);
      return;
    }

    const payload = {
      proyecto_id: formData.get('proyecto_id'),
      fecha: formData.get('fecha'),
      tarea_id: formData.get('tarea_id') || '',
      notas: formData.get('notas') || '',
      trabajadores: rows.trabajadores,
      materiales: rows.materiales,
      maquinaria: rows.maquinaria,
    };

    btnGuardar.disabled = true;
    btnGuardar.textContent = 'Guardando...';

    try {
      const response = await fetch('ajax/guardar_parte.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
      });

      const result = await response.json();
      if (!response.ok || !result.ok) {
        showAlert(result.message || 'No se pudo guardar el parte.', false);
        return;
      }

      showAlert(result.message || 'Parte guardado correctamente.', true);
      form.reset();
      rowsTrab.innerHTML = '';
      rowsMat.innerHTML = '';
      rowsMaq.innerHTML = '';
      addRow('trabajadores');
    } catch (error) {
      showAlert('Error de red o servidor.', false);
    } finally {
      btnGuardar.disabled = false;
      btnGuardar.textContent = 'Guardar parte';
    }
  }

  btnGuardar.addEventListener('click', submitForm);
  bindAddButtons();
  bindRemoveButtons();
  bindTaskFilter();
  addRow('trabajadores');
})();
