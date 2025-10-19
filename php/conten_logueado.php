 <!-- Contenido de la aplicación - visible cuando está logueado -->
        <section class="contenido-aplicacion oculto" id="contenidoAplicacion">
            <!-- Pestaña: Progreso -->
            <div class="panel-pestana activo" id="panelProgreso">
                <div class="encabezado-panel">
                    <h2>Mi Progreso</h2>
                </div>
                <div class="contenido-panel">
                    <?php include $_SERVER['DOCUMENT_ROOT'] . '/proyecto/php/pestanas/progreso.php'; ?>
                </div>
            </div>

            <!-- Pestaña: Mis Textos -->
            <div class="panel-pestana" id="panelTextos">
                <div class="encabezado-panel">
                    <h2>Mis Textos</h2>
                    <button class="boton-agregar">Agregar Texto</button>
                </div>
                <div class="contenido-panel">
                    <?php include $_SERVER['DOCUMENT_ROOT'] . '/proyecto/php/pestanas/textos.php'; ?>
                </div>
            </div>

            <!-- Pestaña: Vocabulario -->
            <div class="panel-pestana" id="panelVocabulario">
                <div class="encabezado-panel">
                    <h2>Mi Vocabulario</h2>
                    <button class="boton-practicar">Practicar</button>
                </div>
                <div class="contenido-panel">
                    <?php include $_SERVER['DOCUMENT_ROOT'] . '/proyecto/php/pestanas/vocabulario.php'; ?>
                </div>
            </div>

            <!-- Pestaña: Prácticas -->
            <div class="panel-pestana" id="panelPracticas">
                <div class="encabezado-panel">
                    <h2>Prácticas</h2>
                    <button class="boton-practicar">Iniciar Práctica</button>
                </div>
                <div class="contenido-panel">
                    <?php include $_SERVER['DOCUMENT_ROOT'] . '/proyecto/php/pestanas/practicas.php'; ?>
                </div>
            </div>

            <!-- Pestaña: Biblioteca -->
            <div class="panel-pestana" id="panelBiblioteca">
                <div class="encabezado-panel">
                    <h2>Biblioteca</h2>
                    <input type="search" placeholder="Buscar textos..." class="buscador-biblioteca">
                </div>
                <div class="contenido-panel">
                    <?php include $_SERVER['DOCUMENT_ROOT'] . '/proyecto/php/pestanas/biblioteca.php'; ?>
                </div>
            </div>

                    <?php include $_SERVER['DOCUMENT_ROOT'] . '/proyecto/php/pestanas/configuracion.php'; ?>
                </div>
            </div>
            </div>
        </section>
    </main>
