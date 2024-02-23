<?php 
foreach($alertas as $key => $alertas){ //obtiene el key del error o exito
    foreach($alertas as $mensaje ){   //obtiene los mensajes de alerta
?>
<div class="alertas <?php echo $key ?>">
    <?php echo $mensaje ?>
</div>
<?php 
    }
 }
  ?>