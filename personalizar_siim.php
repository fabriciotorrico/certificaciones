<?php
	include("verificar_login.php");
	include("inc.config.php");

	$link=Conectarse();

  //Tomamos los datos necesarios
	$id_usuario = $_SESSION['idusuario_ss'];

	//Tomamos la fecha y hora actual
	date_default_timezone_set('America/La_Paz');
	$fecha_hora = date("Y-m-d H:i:s");

	//Verificamos si pusiron check a los parámetros dados
	if (isset($_POST['imprimir_qr'])) {$imprimir_qr = 1;} else {$imprimir_qr = 0;}
	if (isset($_POST['fuc_preimpreso'])) {$fuc_preimpreso = 1;} else {$fuc_preimpreso = 0;}
	if (isset($_POST['fuc_tam_legal'])) {$fuc_tam_legal = 1;} else {$fuc_tam_legal = 0;}
	if (isset($_POST['sancion_administrativa'])) {$sancion_administrativa = 1;} else {$sancion_administrativa = 0;}

	//Actualizamos todos los registros de la tabla parametros_siim en activo = 0
	$sql = " UPDATE parametros_siim
					 SET activo=0";
  $result = pg_query($sql);

	//Insertamos un nuevo registro con los campos introducidos
	$sql = "INSERT INTO parametros_siim
											(id_municipio, tasa_interes, gestion_inicio, gestion_fin, precio_formulario, descuento,
											 imprimir_qr, fuc_preimpreso, fuc_tam_legal, sancion_administrativa, recaudacion,
											 comision_f1980, comision_f2160, comision_f0501, mil, nombre_logo, id_usuario, creacion, activo)
						VALUES ('$_POST[id_municipio]', '$_POST[tasa_interes]', '$_POST[gestion_inicio]', '$_POST[gestion_fin]', '$_POST[precio_formulario]', '$_POST[descuento]',
										'$imprimir_qr', '$fuc_preimpreso', '$fuc_tam_legal', '$sancion_administrativa', '$_POST[recaudacion]',
										'$_POST[comision_f1980]', '$_POST[comision_f2160]', '$_POST[comision_f0501]', '$_POST[mil]', '$_POST[id_municipio]', '$id_usuario', '$fecha_hora', '1')";

	$result = pg_query($sql);

	$_SESSION['msj_exito'] = "Cambio de parámetros guardado correctamente";

	header("Location: personalizar_siim_form.php");
?>
