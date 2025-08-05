document.addEventListener('DOMContentLoaded', () => {
  const inputBusqueda = document.getElementById('search-product');
  const tabla = document.querySelector('#productos');
  const btnAgregar = document.getElementById('btn-add-product');
  const inputNombre = document.getElementById('input-nombre');  // ejemplo para agregar un nombre de producto
  const btnEliminar = document.querySelector('button[type="submit"]'); // Asegúrate de que el botón de eliminar esté correctamente referenciado

  // Filtrado existente
  inputBusqueda.addEventListener('input', () => {
    const texto = inputBusqueda.value.toLowerCase();

    Array.from(tabla.rows).forEach(row => {
      const celdasBusqueda = Array.from(row.cells).slice(1);
      const coincide = celdasBusqueda.some(celda => {
        return celda.textContent.toLowerCase().includes(texto);
      });
      row.style.display = coincide ? '' : 'none';
    });
  });

  // Evitar agregar productos automáticamente, solo tras click en el botón
  btnAgregar.addEventListener('click', () => {
    const nombre = inputNombre.value.trim();
    // Validaciones
    if (nombre === '') {
      alert('Por favor, ingresa el nombre del producto');
      return;
    }
    // Crear una nueva fila y agregarla a la tabla
    const nuevaFila = tabla.insertRow();
    const celdaID = nuevaFila.insertCell();
    const celdaNombre = nuevaFila.insertCell();
    // ... otras celdas

    celdaID.textContent = tabla.rows.length; // por ejemplo, ID incremental
    celdaNombre.textContent = nombre;
    // llenar otras celdas con datos

    // Limpiar inputs
    inputNombre.value = '';
  });

  // Redefinir acción para el botón de eliminar
  btnEliminar.addEventListener('click', (e) => {
    // Si el botón "Eliminar" tiene alguna acción asociada
    // aquí se podría resetear la búsqueda o recargar la página
    if (inputBusqueda.value === '') {
      // Esto recarga la página para restablecer el índice
      window.location.reload();  // O también podrías hacer un "reset" manual
    }
  });
});

// Función para renumerar los IDs al cargar la página
function renumerarIds() {
    const filas = document.querySelectorAll('#productos tr');
    filas.forEach((fila, index) => {
        const celdaId = fila.querySelector('td:first-child');
        if (celdaId) {
            celdaId.textContent = index + 1;  // Empezamos desde 1
        }
    });
}

// Renumerar al cargar la página
window.addEventListener('load', renumerarIds);
