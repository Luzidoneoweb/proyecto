 <!-- Contenido de la aplicación - visible cuando está logueado -->
 <section class="contenido-aplicacion oculto" id="contenidoAplicacion">
            <!-- Pestaña: Mis Textos -->
            <div class="panel-pestana activo" id="panelTextos">
                <div class="encabezado-panel">
                    <h2>Mis Textos</h2>
                    <button class="boton-agregar">Agregar Texto</button>
                </div>
                <div class="contenido-panel">
                    <p>Aquí aparecerán tus textos guardados para practicar.</p>
                    <!-- Contenido de textos se agregará dinámicamente -->
                </div>
            </div>

            <!-- Pestaña: Vocabulario -->
            <div class="panel-pestana" id="panelVocabulario">
                <div class="encabezado-panel">
                    <h2>Mi Vocabulario</h2>
                    <button class="boton-practicar">Practicar</button>
                </div>
                <div class="contenido-panel">
                    <p>Aquí aparecerán las palabras que has traducido.</p>
                    <!-- Contenido de vocabulario se agregará dinámicamente -->
                </div>
            </div>

            <!-- Pestaña: Progreso -->
            <div class="panel-pestana" id="panelProgreso">
                <div class="encabezado-panel">
                    <h2>Mi Progreso</h2>
                </div>
                <div class="contenido-panel">
                    <p>Aquí verás estadísticas de tu aprendizaje.</p>
                    <!-- Contenido de progreso se agregará dinámicamente -->
                </div>
            </div>

            <!-- Pestaña: Biblioteca -->
            <div class="panel-pestana" id="panelBiblioteca">
                <div class="encabezado-panel">
                    <h2>Biblioteca</h2>
                    <input type="search" placeholder="Buscar textos..." class="buscador-biblioteca">
                </div>
                <div class="contenido-panel">
                    <p>Explora textos disponibles para leer y aprender.</p>
                    <!-- Contenido de biblioteca se agregará dinámicamente -->
                </div>
            </div>

            <!-- Pestaña: Configuración -->
            <div class="panel-pestana" id="panelConfiguracion">
                <div class="encabezado-panel">
                    <h2>Configuración</h2>
                </div>
                <div class="contenido-panel">
                    <div class="grupo-configuracion">
                        <h3>Idioma de aprendizaje</h3>
                        <label for="idioma-select" class="visually-hidden">Selecciona idioma</label>
                        <select id="idioma-select" class="selector-idioma" aria-label="Selecciona idioma">
                            <option value="en">Inglés</option>
                            <option value="fr">Francés</option>
                            <option value="de">Alemán</option>
                            <option value="it">Italiano</option>
                        </select>
                    </div>
                    <div class="grupo-configuracion">
                        <h3>Notificaciones</h3>
                        <label class="interruptor">
                            <input type="checkbox" checked>
                            <span class="deslizador"></span>
                            Recordatorios diarios
                        </label>
                    </div>
                </div>
            </div>
        </section>
    </main>
