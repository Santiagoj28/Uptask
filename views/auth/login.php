

<div class="contenedor login">
    <?php
    include_once __DIR__ . '/../templates/nombreSitio.php';
    ?>
    <div class="contenedor-sm">
        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>
        <p class="descripcion-pagina">Iniciar Sesion</p>
        <form action="/" class="formulario" method="POST" novalidate>
            <div class="campo">
                <label for="email">Email</label>
                <input type="email"
                id="email"
                name="email"
                placeholder="Tu email"
                >
            </div>
            <div class="campo">
                <label for="password">Password</label>
                <input type="password"
                id="password"
                name="password"
                placeholder="Tu password"
                >
            </div>
            <input type="submit" class="boton" value="Iniciar sesion">

        </form>

        <div class="acciones">
            <a href="/crear_cuenta">¿Aun no tienes una cuenta?obtener una</a>
            <a href="/olvide">¿Olvidaste tu password?</a>
        </div>
    </div><!-- Contenedor sm !-->

</div><!-- Contenedor  !-->