 // Variables globales
        let usuarioLogueado = false;
        
        // Elementos del DOM
        const botonLogin = document.getElementById('botonLogin');
        const botonCerrarSesion = document.getElementById('botonCerrarSesion');
        const navegacionPrincipal = document.getElementById('navegacionPrincipal');
        const navegacionUsuario = document.getElementById('navegacionUsuario');
        const paginaInicio = document.getElementById('paginaInicio');
        const contenidoAplicacion = document.getElementById('contenidoAplicacion');
        const botonMenuMovil = document.getElementById('botonMenuMovil');
        
        // Función para alternar el estado de login
        function alternarLogin() {
            usuarioLogueado = !usuarioLogueado;
            
            if (usuarioLogueado) {
                // Mostrar interfaz de usuario logueado
                navegacionPrincipal.classList.add('oculto');
                navegacionUsuario.classList.remove('oculto');
                paginaInicio.classList.add('oculto');
                contenidoAplicacion.classList.remove('oculto');
            } else {
                // Mostrar interfaz de usuario no logueado
                navegacionPrincipal.classList.remove('oculto');
                navegacionUsuario.classList.add('oculto');
                paginaInicio.classList.remove('oculto');
                contenidoAplicacion.classList.add('oculto');
            }
        }
        
        // Función para cambiar entre pestañas
        function cambiarPestana(nombrePestana) {
            // Remover clase activa de todas las pestañas
            document.querySelectorAll('.pestana').forEach(pestana => {
                pestana.classList.remove('activa');
            });
            
            // Ocultar todos los paneles
            document.querySelectorAll('.panel-pestana').forEach(panel => {
                panel.classList.remove('activo');
            });
            
            // Activar la pestaña seleccionada
            document.querySelector(`[data-pestana="${nombrePestana}"]`).classList.add('activa');
            
            // Mostrar el panel correspondiente
            document.getElementById(`panel${nombrePestana.charAt(0).toUpperCase() + nombrePestana.slice(1)}`).classList.add('activo');
        }
        
        // Event listeners
        botonLogin.addEventListener('click', alternarLogin);
        botonCerrarSesion.addEventListener('click', alternarLogin);
        
        // Event listeners para las pestañas
        document.querySelectorAll('.pestana').forEach(pestana => {
            pestana.addEventListener('click', (e) => {
                const nombrePestana = e.target.getAttribute('data-pestana');
                cambiarPestana(nombrePestana);
            });
        });
        
        // Menú móvil
        botonMenuMovil.addEventListener('click', () => {
            navegacionPrincipal.classList.toggle('menu-abierto');
        });
        
        // Navegación suave
        document.querySelectorAll('.enlace-menu').forEach(enlace => {
            enlace.addEventListener('click', (e) => {
                e.preventDefault();
                const destino = e.target.getAttribute('href').substring(1);
                const elemento = document.getElementById(destino);
                if (elemento) {
                    elemento.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });