<?php
	include("verificar_login.php");
	include("inc.config.php");

	$link=Conectarse();

  //Tomamos los datos necesarios
  $fecha = $_POST['fecha'];
  $dolar = $_POST['dolar'];
  $ufv 	 = $_POST['ufv'];
	$gestion = substr($fecha, 0, 4);
	$id_usuario = $_SESSION['idusuario_ss'];
	//Verificamos si se ha creado un registro de cotizacion dolar y ufv para la fecha dada
	$sql  = "SELECT iddolarufv
					 FROM dolarufv
					 WHERE fecha = '$fecha'";
	$result = pg_query($link, $sql);

	//Si ya existe el registro, actualizamos los valores
	if ($row = pg_fetch_array($result, NULL, PGSQL_BOTH)){
		$sql = " UPDATE dolarufv
						 SET ufv=$ufv, dolar=$dolar, id_usuario=$id_usuario
						 WHERE iddolarufv = '$row[iddolarufv]'";
	}
	//Caso contrario, creamos el registro
	else {
		$sql = "INSERT INTO dolarufv
											(ufv, dolar, fecha, gestion, id_usuario)
						VALUES ('$ufv', '$dolar', '$fecha', '$gestion', '$id_usuario')";
	}

	//Ejecutamos la consulta y redirigimos
	$result = pg_query($sql);

	//Establecemos el mensaje de exito a mostrar
	$_SESSION['msj_exito'] = "Cotización de Dólar / UFV guardada correctamente";
	header("Location: home.php");
?>
