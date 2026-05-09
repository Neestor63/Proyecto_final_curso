document.addEventListener('DOMContentLoaded', function() {
    const formReserva = document.querySelector('form[action="index.php?action=reservar"]');
    
    if (formReserva) {
        formReserva.addEventListener('submit', function(e) {
            const inputFecha = document.querySelector('input[name="fecha"]').value;
            const hoy = new Date().toISOString().split('T')[0];

            if (inputFecha < hoy) {
                e.preventDefault(); // Detiene el envío del formulario
                alert("¡Cuidado! No puedes viajar al pasado. Elige una fecha de hoy en adelante.");
            }
        });
    }
});