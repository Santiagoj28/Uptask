<div class="contenedor crear">
    <?php
    include_once __DIR__ . '/../templates/nombreSitio.php';
   
    ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Crear cuenta</p>
        <?php
         include_once __DIR__ . '/../templates/alertas.php';
        ?>
        <form action="/crear_cuenta" class="formulario" method="POST">
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input type="text"
                id="nombre"
                name="nombre"
                placeholder="Tu nombre"
                value="<?php echo $usuario->nombre ?>"
                >
            </div>       

            <div class="campo">
                <label for="email">Email</label>
                <input type="email"
                id="email"
                name="email"
                placeholder="Tu email"
                value="<?php echo $usuario->email ?>"
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
            <div class="campo">
                <label for="password2">Confirmar password</label>
                <input type="password"
                id="password2"
                name="password2"
                placeholder="Confirmar password"
                >
            </div>
            <input type="submit" class="boton" value="Iniciar sesion">

        </form>

        <div class="acciones">
            <a href="/">¿Ya tienes cuenta?Inicia sesion</a>
            <a href="/olvide">¿Olvidaste tu password?</a>
        </div>
    </div><!-- Contenedor sm !-->

</div><!-- Contenedor  !-->