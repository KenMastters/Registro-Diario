window.addEventListener('load', function() {
    const successMessage = document.querySelector('.success-message');
    
    if (successMessage) {
        // Muestra el mensaje de éxito
        successMessage.style.display = 'block';

        // Después de 3 segundos, oculta el mensaje
        setTimeout(function() {
            successMessage.style.display = 'none';
        }, 2000); // 
    }
});
