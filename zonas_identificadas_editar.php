<?php
  include("verificar_login.php");
  include("inc.config.php");

  $link=Conectarse();

  //Actualizamos el registro
  $sql = " UPDATE zonavalor
           SET nombre = '$_POST[nombre]', descripcion = '$_POST[descripcion]', valorm2 = '$_POST[valorm2]'
           WHERE idzona = '$_POST[idzona]'";
  $result = pg_query($sql);

  //Establecemos el mensaje de exito a mostrar
  $_SESSION['msj_exito'] = "La zona fue editada exitosamente";

  //Redireccionamos a la pagina previa
  header("Location:zonas_identificadas_form.php");
?>
