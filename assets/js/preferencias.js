const btnOscuro = document.getElementById('toggle-dark'); 


function actualizarTextoBoton() {
    if (btnOscuro) {
        const esOscuro = document.body.classList.contains('dark-mode');
        
        
        if (esOscuro) {
            btnOscuro.innerHTML = "Modo Claro";
        } else {
            btnOscuro.innerHTML = "Modo Oscuro";
        }
    }
}

actualizarTextoBoton();

if(btnOscuro) {
    btnOscuro.addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');
        
        let modo = document.body.classList.contains('dark-mode') ? 'oscuro' : 'claro';
        document.cookie = "modo=" + modo + "; max-age=" + (60*60*24*30) + "; path=/";
        
        actualizarTextoBoton();
    });
}