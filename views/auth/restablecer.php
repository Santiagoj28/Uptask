<div class="contenedor restablecer">
    <?php
    include_once __DIR__ . '/../templates/nombreSitio.php';
    ?>
    <div class="contenedor-sm">
        <?php
        include_once __DIR__ . '/../templates/alertas.php';
        ?>
         <?php if($mostrar) {?>
        <p class="descripcion-pagina">Coloca tu nuevo password</p>
        <form  class="formulario" method="POST">
           
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
            <input type="submit" class="boton" value="Guardar password">
        </form>
        <?php } ?>

        <div class="acciones">
            <a href="/">¿Ya tienes cuenta?Inicia sesion</a>
            
        </div>
    </div><!-- Contenedor sm !-->

</div><!-- Contenedor  !-->