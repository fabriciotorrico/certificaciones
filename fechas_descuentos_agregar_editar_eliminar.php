<?php
  include("verificar_login.php");
  include("inc.config.php");

  $link=Conectarse();

  //Tomamos las variables
  $accion = $_POST['accion'];

  //Creamos la funcion modificar_ultimo_descuento(), la cual actualzará el ultimo descuento de la gestiòn modificada a un valor de 15%
  function modificar_ultimo_descuento($gestion){
    $sql = " UPDATE fechas
             SET porcentaje = '15.00', id_usuario = '$_SESSION[idusuario_ss]'
             WHERE id_fecha IN(
               SELECT id_fecha
   						 FROM fechas
   						 WHERE gestion = '$gestion'
   						  AND activo=1
   						 ORDER BY fecha DESC
               LIMIT 1
               FOR UPDATE
             )";
    $result = pg_query($sql);
  }

  //Realizamos las operaciones correspondientes en funciòn a la accion seleccionada
  if ($accion == "agregar") {
    //Realizamos el registro
    $sql = "INSERT INTO fechas
                      (gestion, fecha, porcentaje, id_usuario, activo)
            VALUES ('$_POST[gestion]', '$_POST[fecha]', '$_POST[porcentaje]', '$_SESSION[idusuario_ss]', '1')";
    $result = pg_query($sql);

    //Establecemos el mensaje de exito a mostrar
    $_SESSION['msj_exito'] = "El registro de fecha y porcentaje de descuento fue agregado correctamente";
  }
  else {
    if ($accion == "editar") {
      //Actualzamos el registro
      $sql = " UPDATE fechas
               SET fecha = '$_POST[fecha]', porcentaje = '$_POST[porcentaje]', id_usuario = '$_SESSION[idusuario_ss]'
               WHERE id_fecha = '$_POST[id_fecha]'";
      $result = pg_query($sql);

      //Establecemos el mensaje de exito a mostrar
      $_SESSION['msj_exito'] = "El registro de fecha y porcentaje de descuento fue actualizado correctamente";
    }
    else {
      if ($accion == "eliminar") {
        $sql = " UPDATE fechas
                 SET id_usuario = '$_SESSION[idusuario_ss]', activo = 0
                 WHERE id_fecha = '$_POST[id_fecha]'";
        $result = pg_query($sql);

        //Establecemos el mensaje de exito a mostrar
        $_SESSION['msj_exito'] = "El registro de fecha y porcentaje de descuento fue eliminado correctamente";
      }
    }
  }
  //Modificamos el ultimo descuento de la gestion introducida
  modificar_ultimo_descuento($_POST['gestion']);

  //Establecemos la variable de sesion fechas_descuentos_gestion_seleccionada para ir al año agregado
  $_SESSION['fechas_descuentos_gestion_seleccionada'] = $_POST['gestion'];

  //Redireccionamos a la pagina previa
  header("Location:fechas_descuentos_form.php");
?>
