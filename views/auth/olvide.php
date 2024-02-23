<div class="contenedor olvide">
    <?php
    include_once __DIR__ . '/../templates/nombreSitio.php';
    ?>
    <div class="contenedor-sm">
        <?php
         include_once __DIR__ . '/../templates/alertas.php';
        ?>
       
        <?php if($mostrar) { ?>
            <p class="descripcion-pagina">Cambiar password</p>
        <form action="/olvide" class="formulario" method="POST" novalidate>
             

            <div class="campo">
                <label for="email">Email</label>
                <input type="email"
                id="email"
                name="email"
                placeholder="Tu email"
                >
            </div>
            
            <input type="submit" class="boton" value="Enviar instrucciones">
        </form>
        <?php } ?>

        <div class="acciones">
            <a href="/">¿Ya tienes cuenta?Inicia sesion</a>
            <a href="/olvide">¿No tienes una cuenta?Crea una</a>
        </div>
    </div><!-- Contenedor sm !-->

</div><!-- Contenedor  !-->