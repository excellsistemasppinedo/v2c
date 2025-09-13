<?php
  require_once 'pages/screen.php';
  require_once 'pages/constantes.php';
  $loScreen = new screen(__SERVER__,__BASEDATOS__,__PUERTO__,__USUARIO__,__CLAVE__);
?>
<!DOCTYPE html>
<html lang="es">
  <?php  
    echo $loScreen->head();
  ?>
  <body>
    <?php
      echo $loScreen->navbar();
      echo $loScreen->contenido_principal();
      echo $loScreen->footer();
      echo $loScreen->js();
    ?>
  </body>
</html>
