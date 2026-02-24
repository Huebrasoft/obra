(function () {
  const app = document.getElementById('parteApp');
  if (!app || !window.PARTE_DATA) return;

  const wrapT = document.getElementById('trabajadoresWrap');
  const wrapM = document.getElementById('materialesWrap');
  const wrapQ = document.getElementById('maquinariaWrap');

  const options = (arr, valueKey = 'id', labelFn = (x) => x.nombre) =>
    ['<option value="">Selecciona...</option>']
      .concat(arr.map((i) => `<option value="${i[valueKey]}">${labelFn(i)}</option>`))
      .join('');

  function addRow(type) {
    const row = document.createElement('div');
    row.className = 'row';

    if (type === 'trabajador') {
      row.innerHTML = `<select class="id">${options(window.PARTE_DATA.trabajadores)}</select>
                       <input class="qty" type="number" step="0.25" min="0" placeholder="Horas">
                       <button type="button" class="btn btn-danger">X</button>`;
      wrapT.appendChild(row);
    }
    if (type === 'material') {
      row.innerHTML = `<select class="id">${options(window.PARTE_DATA.materiales, 'id', (x) => `${x.nombre} (${x.unidad_medida})`)}</select>
                       <input class="qty" type="number" step="0.01" min="0" placeholder="Cantidad">
                       <button type="button" class="btn btn-danger">X</button>`;
      wrapM.appendChild(row);
    }
    if (type === 'maquinaria') {
      row.innerHTML = `<select class="id">${options(window.PARTE_DATA.maquinaria, 'id', (x) => `${x.nombre} ${x.modelo || ''}`)}</select>
                       <input class="qty" type="number" step="0.25" min="0" placeholder="Horas">
                       <button type="button" class="btn btn-danger">X</button>`;
      wrapQ.appendChild(row);
    }

    row.querySelector('.btn-danger').addEventListener('click', () => row.remove());
  }

  document.querySelectorAll('[data-add]').forEach((btn) => {
    btn.addEventListener('click', () => addRow(btn.dataset.add));
  });

  document.getElementById('parteForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.currentTarget;
    const payload = {
      proyecto_id: form.proyecto_id.value,
      fecha: form.fecha.value,
      tarea: form.tarea.value,
      notas: form.notas.value,
      trabajadores: [...wrapT.querySelectorAll('.row')].map((r) => ({ trabajador_id: r.querySelector('.id').value, horas: r.querySelector('.qty').value })),
      materiales: [...wrapM.querySelectorAll('.row')].map((r) => ({ material_id: r.querySelector('.id').value, cantidad: r.querySelector('.qty').value })),
      maquinaria: [...wrapQ.querySelectorAll('.row')].map((r) => ({ maquinaria_id: r.querySelector('.id').value, horas: r.querySelector('.qty').value }))
    };

    if (!payload.proyecto_id || !payload.fecha) {
      alert('Proyecto y fecha son obligatorios.');
      return;
    }

    const res = await fetch('index.php?page=parte_guardar_ajax', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
    const json = await res.json();
    if (json.ok) {
      alert('Parte guardado correctamente #' + json.parte_id);
      window.location.reload();
    } else {
      alert(json.error || 'Error inesperado');
    }
  });
})();
