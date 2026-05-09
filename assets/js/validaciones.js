document.addEventListener("DOMContentLoaded", function() {
    const passwordInput = document.getElementById('pass-input');
    const mensajeDiv = document.getElementById('pass-mensaje');
    
    if (passwordInput && mensajeDiv) {
        passwordInput.addEventListener("keyup", function() {
            const valor = passwordInput.value;
            
            if (valor.length === 0) {
                mensajeDiv.innerHTML = "";
                passwordInput.style.borderColor = "#444";
            } 
            else if (valor.length < 6) {
                mensajeDiv.innerHTML = "Demasiado corta (mínimo 6 caracteres)";
                mensajeDiv.style.color = "var(--accent-red)";
                passwordInput.style.borderColor = "var(--accent-red)";
            } 
            else {
                mensajeDiv.innerHTML = "¡Contraseña segura!";
                mensajeDiv.style.color = "var(--primary-color)";
                passwordInput.style.borderColor = "var(--primary-color)";
            }
        });
    }
});