// Función para gestionar el Modo Oscuro
const btnOscuro = document.getElementById('toggle-dark'); 

// Ya no añadimos la clase aquí al cargar, lo hacemos desde PHP para evitar el "parpadeo"
// Pero sí necesitamos actualizar el texto del botón si existe
function actualizarTextoBoton() {
    if (btnOscuro) {
        const esOscuro = document.body.classList.contains('dark-mode');
        // Si tienes las traducciones disponibles podrías usarlas, pero como JS no tiene $texts directamente:
        // Vamos a leer un atributo data o simplemente cambiar el texto basándonos en el estado.
        // Asumimos que el botón inicialmente tiene el texto correcto traducido.
        // Pero para simplificar, usaremos un icono visual que todos entienden o cambiamos el color.
        
        if (esOscuro) {
            btnOscuro.innerHTML = "☀️ Modo Claro";
        } else {
            btnOscuro.innerHTML = "🌙 Modo Oscuro";
        }
    }
}

// Ejecutar al inicio
actualizarTextoBoton();

if(btnOscuro) {
    btnOscuro.addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');
        
        // Guardamos la preferencia por 30 días
        let modo = document.body.classList.contains('dark-mode') ? 'oscuro' : 'claro';
        document.cookie = "modo=" + modo + "; max-age=" + (60*60*24*30) + "; path=/";
        
        actualizarTextoBoton();
    });
}