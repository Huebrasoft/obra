(function () {
  const data = window.PARTE_DATA || {};
  const form = document.getElementById('parteForm');
  const btnGuardar = document.getElementById('btnGuardarParte');
  const alertBox = document.getElementById('parteAlert');

  if (!form || !btnGuardar) return;

  const rowsTrab = document.getElementById('rowsTrabajadores');
  const rowsMat = document.getElementById('rowsMateriales');
  const rowsMaq = document.getElementById('rowsMaquinaria');

  function options(items, valueKey, textKey, extraTextKey) {
    return (items || []).map((x) => {
      const extra = extraTextKey ? ` (${x[extraTextKey] ?? 0})` : '';
      return `<option value="${x[valueKey]}">${x[textKey]}${extra}</option>`;
    }).join('');
  }

  function addRow(type) {
    let html = '';
    if (type === 'trabajadores') {
      html = `<div class="line-row">
        <select class="tr-id"><option value="">Trabajador</option>${options(data.trabajadores, 'id', 'nombre')}</select>
        <input type="number" class="tr-horas" min="0" step="0.25" placeholder="Horas">
        <input type="number" class="tr-coste" min="0" step="0.01" placeholder="Coste/h">
        <button type="button" class="btn-remove">×</button>
      </div>`;
      rowsTrab.insertAdjacentHTML('beforeend', html);
    }
    if (type === 'materiales') {
      html = `<div class="line-row">
        <select class="mt-id"><option value="">Material</option>${options(data.materiales, 'id', 'nombre', 'unidad')}</select>
        <input type="number" class="mt-cantidad" min="0" step="0.01" placeholder="Cantidad">
        <input type="number" class="mt-precio" min="0" step="0.01" placeholder="Precio ud.">
        <button type="button" class="btn-remove">×</button>
      </div>`;
      rowsMat.insertAdjacentHTML('beforeend', html);
    }
    if (type === 'maquinaria') {
      html = `<div class="line-row">
        <select class="mq-id"><option value="">Máquina</option>${options(data.maquinaria, 'id', 'nombre')}</select>
        <input type="number" class="mq-horas" min="0" step="0.25" placeholder="Horas">
        <input type="number" class="mq-coste" min="0" step="0.01" placeholder="Coste/h">
        <button type="button" class="btn-remove">×</button>
      </div>`;
      rowsMaq.insertAdjacentHTML('beforeend', html);
    }
  }

  document.querySelectorAll('[data-add-row]').forEach((btn) => {
    btn.addEventListener('click', () => addRow(btn.dataset.addRow));
  });

  [rowsTrab, rowsMat, rowsMaq].forEach((box) => {
    box.addEventListener('click', (e) => {
      if (e.target.classList.contains('btn-remove')) {
        e.target.closest('.line-row').remove();
      }
    });
  });

  const proyecto = document.getElementById('proyecto_id');
  const tarea = document.getElementById('tarea_id');
  proyecto.addEventListener('change', () => {
    const pid = proyecto.value;
    Array.from(tarea.options).forEach((o, idx) => {
      if (idx === 0) return;
      o.hidden = o.dataset.proyecto !== pid;
    });
    tarea.value = '';
  });

  function showAlert(msg, ok) {
    alertBox.textContent = msg;
    alertBox.classList.remove('hidden', 'alert-ok', 'alert-error');
    alertBox.classList.add(ok ? 'alert-ok' : 'alert-error');
  }

  function collectRows() {
    const trabajadores = Array.from(rowsTrab.querySelectorAll('.line-row')).map((r) => ({
      trabajador_id: Number(r.querySelector('.tr-id').value || 0),
      horas: Number(r.querySelector('.tr-horas').value || 0),
      coste_hora_snapshot: Number(r.querySelector('.tr-coste').value || 0)
    }));

    const materiales = Array.from(rowsMat.querySelectorAll('.line-row')).map((r) => ({
      material_id: Number(r.querySelector('.mt-id').value || 0),
      cantidad: Number(r.querySelector('.mt-cantidad').value || 0),
      precio_unitario_snapshot: Number(r.querySelector('.mt-precio').value || 0)
    }));

    const maquinaria = Array.from(rowsMaq.querySelectorAll('.line-row')).map((r) => ({
      maquinaria_id: Number(r.querySelector('.mq-id').value || 0),
      horas: Number(r.querySelector('.mq-horas').value || 0),
      coste_hora_snapshot: Number(r.querySelector('.mq-coste').value || 0)
    }));

    return { trabajadores, materiales, maquinaria };
  }

  btnGuardar.addEventListener('click', async () => {
    const fd = new FormData(form);
    const rows = collectRows();

    if (!fd.get('proyecto_id') || !fd.get('fecha')) {
      showAlert('Proyecto y fecha son obligatorios.', false);
      return;
    }

    if (rows.trabajadores.length + rows.materiales.length + rows.maquinaria.length === 0) {
      showAlert('Añade al menos un registro.', false);
      return;
    }

    const payload = {
      proyecto_id: fd.get('proyecto_id'),
      fecha: fd.get('fecha'),
      tarea_id: fd.get('tarea_id') || '',
      notas: fd.get('notas') || '',
      trabajadores: rows.trabajadores,
      materiales: rows.materiales,
      maquinaria: rows.maquinaria,
    };

    btnGuardar.disabled = true;
    btnGuardar.textContent = 'Guardando...';

    try {
      const res = await fetch('ajax/guardar_parte.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });

      const json = await res.json();
      if (!res.ok || !json.ok) {
        showAlert(json.message || 'Error al guardar.', false);
      } else {
        showAlert(json.message, true);
        form.reset();
        rowsTrab.innerHTML = '';
        rowsMat.innerHTML = '';
        rowsMaq.innerHTML = '';
      }
    } catch (e) {
      showAlert('Error de red o servidor.', false);
    } finally {
      btnGuardar.disabled = false;
      btnGuardar.textContent = 'Guardar parte';
    }
  });

  addRow('trabajadores');
})();
