document.addEventListener("DOMContentLoaded", () => {
    // Navegación entre secciones del perfil
    document.querySelectorAll(".menu-item").forEach(item => {
        item.addEventListener("click", function() {
            document.querySelectorAll(".menu-item").forEach(i => i.classList.remove("active"));
            this.classList.add("active");

            const section = this.dataset.section;
            if (section) {
                console.log("Navegando a:", section);
                // Aquí puedes agregar la lógica para mostrar/ocultar secciones
            }
        });
    });

    // Manejo del formulario (puedes cambiarlo por una petición AJAX si lo deseas)
    const form = document.querySelector("form[action*='profile.update']");
    if (form) {
        form.addEventListener("submit", function(e) {
            e.preventDefault();
            console.log("Formulario enviado");
            // Aquí puedes enviar los datos via fetch/AJAX si no quieres recargar
        });
    }
});
