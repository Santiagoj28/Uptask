<?php include_once __DIR__ . '/header-dashboard.php';?>

<div class="contenedor-sm">
    <?php include_once __DIR__ .'/../templates/alertas.php';?>

    <a href="/perfil" class="enlace">Volver a el perfil</a>

    <form class="formulario" method="POST" action="/cambiar_password">
        <div class="campo">
            <label for="nombre">Password Actual:</label>
            <input 
              type="password"
              name="password_actual"
              placeholder="Password actual"
              >
        </div>

        <div class="campo">
            <label for="email">New password:</label>
            <input 
            type="password"
             name="password_nuevo"      
              placeholder="Tu nuevo password"
              >
        </div>

        <input type="submit" value="Guardar cambios">

    </form>

</div>