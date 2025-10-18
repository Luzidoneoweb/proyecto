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
        
        // Función para alternar el estado de login (simulado)
        function alternarLogin() {
            usuarioLogueado = !usuarioLogueado;
            
            if (usuarioLogueado) {
                mostrarInterfazLogueada();
            } else {
                mostrarInterfazNoLogueada();
            }
        }
        
        // Función para mostrar interfaz de usuario logueado
        function mostrarInterfazLogueada() {
            navegacionPrincipal.classList.add('oculto');
            navegacionUsuario.classList.remove('oculto');
            paginaInicio.classList.add('oculto');
            contenidoAplicacion.classList.remove('oculto');
        }
        
        // Función para mostrar interfaz de usuario no logueado
        function mostrarInterfazNoLogueada() {
            navegacionPrincipal.classList.remove('oculto');
            navegacionUsuario.classList.add('oculto');
            paginaInicio.classList.remove('oculto');
            contenidoAplicacion.classList.add('oculto');
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

            // Cerrar el menú móvil después de seleccionar una pestaña si está abierto
            if (navegacionUsuario && navegacionUsuario.classList.contains('menu-abierto')) {
                navegacionUsuario.classList.remove('menu-abierto');
            }
        }
        
        // Event listeners
        if (botonLogin) {
            botonLogin.addEventListener('click', alternarLogin);
        }
        if (botonCerrarSesion) {
            botonCerrarSesion.addEventListener('click', alternarLogin); // Simular cierre de sesión
        }
        
        // Event listeners para las pestañas
        document.querySelectorAll('.pestana').forEach(pestana => {
            pestana.addEventListener('click', (e) => {
                const nombrePestana = e.target.getAttribute('data-pestana');
                cambiarPestana(nombrePestana);
            });
        });
        
        // Menú móvil
        if (botonMenuMovil) {
            botonMenuMovil.addEventListener('click', () => {
                if (usuarioLogueado) {
                    navegacionUsuario.classList.toggle('menu-abierto');
                } else {
                    navegacionPrincipal.classList.toggle('menu-abierto');
                }
            });
        }
        
        // Navegación suave
        document.querySelectorAll('.enlace-menu').forEach(enlace => {
            enlace.addEventListener('click', (e) => {
                e.preventDefault();
                const destino = e.target.getAttribute('href').substring(1);
                const elemento = document.getElementById(destino);
                if (elemento) {
                    elemento.scrollIntoView({ behavior: 'smooth' });
                }
                // Cerrar el menú principal después de seleccionar un enlace si está abierto
                if (navegacionPrincipal.classList.contains('menu-abierto')) {
                    navegacionPrincipal.classList.remove('menu-abierto');
                }
            });
        });

        // Inicializar estado de la interfaz al cargar la página (simulado)
        document.addEventListener('DOMContentLoaded', function() {
            // Determinar el estado inicial de usuarioLogueado basado en la visibilidad de navegacionUsuario
            if (!navegacionUsuario.classList.contains('oculto')) {
                usuarioLogueado = true;
            } else {
                usuarioLogueado = false;
            }
        });
