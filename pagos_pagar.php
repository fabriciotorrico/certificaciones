<?php
  include("verificar_login.php");
  include("inc.config.php");

  $link=Conectarse();

  //Tomamos la fecha actual
  date_default_timezone_set('America/La_Paz');
  $fecha_hoy = date("Y-m-d");
  $fecha_hora_hoy = date("Y-m-d H:i:s");

  //Tomamos las varialbes necesarias
	$id = $_POST['id'];
	$gestion = $_POST['gestion'];
	$pago = $_POST['pago'];

  //Establecemos las variables globales para volver a la vista previa
  $_SESSION['volviendo'] = 1;
  $_SESSION['id'] = $_POST['id'];
  $_SESSION['gestion'] = $_POST['gestion'];
  $_SESSION['pago'] = $_POST['pago'];

  //Verificamos si ya se realizó el pago para el id y gestion seleccionados
  $sql  = "SELECT P.id_pagoinm, P.fechapago, U.nombres, U.apellidos
           FROM pagoinm AS P
           LEFT JOIN usuario AS U
            ON P.id_usuario = U.id
           WHERE P.idinm = '$id'
            AND P.gestionpago = '$gestion'
            AND P.activo = 1";
  $result = pg_query($link, $sql);
  $pagoinm = pg_fetch_array($result, NULL, PGSQL_BOTH);

  //Si existe $pagoinm[idinm], ya se realizó el pago, por lo que muestra el mensaje
  if (isset($pagoinm['id_pagoinm'])) {
    //Establecemos el mensaje a mostrar
    echo $_SESSION['msj_error'] = "El pago ya fue efectuado, por el usuario ".$pagoinm['nombres']." ".$pagoinm['apellidos']." en fecha ".$pagoinm['fechapago'];
  }
  else {
    //En caso de que no exista el registro del pago, lo hacemos
    //Tomamos los datos de la liquidacion
    $sql  = "SELECT *
             FROM liquidacion
             WHERE id = '$id'
              AND gestion = '$gestion'
              AND activo = 1";
    $result = pg_query($link, $sql);
    $liquidacion = pg_fetch_array($result, NULL, PGSQL_BOTH);

    //Establecemos los valores a registrar
    $base_imponible = 0;

    //Realizamos el registro en la tabla pagoinm
    $sql = "INSERT INTO pagoinm
                      (idinm, id_liquidacion, gestionpago,
                       impuestoneto, montopagar,
                       fechapago, fecha, id_usuario, activo
                      )
            VALUES ('$id', '$liquidacion[idliqui]', '$gestion',
                    '$liquidacion[impuestoneto]', '$liquidacion[total]',
                    '$fecha_hora_hoy', '$fecha_hoy','$_SESSION[idusuario_ss]', '1'
                   )";
    $result = pg_query($sql);
    //Establecemos el mensaje a mostrar
    echo $_SESSION['msj_exito'] = "El pago fue efectuado con éxito";
  }

  //Redirigimos a la vista anterior
  header("Location:pagos_datos_tecnicos_form.php");
?>
