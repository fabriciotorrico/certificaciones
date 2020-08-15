<?php
	include("verificar_login.php");
	include("inc.config.php");
	$link=Conectarse();

	$id = $_GET['id_inmueble'];
	//Tomamos los datos del inmueble
	$sql  = "SELECT *
					 FROM transferencia1
					 WHERE id = '$id'";
	$result = pg_query($link, $sql);
	$inm = pg_fetch_array($result, NULL, PGSQL_BOTH);

	//Calculamos k5 (servicios)
	$servicios = 1 - $inm['k5_1'] - $inm['k5_2'] - $inm['k5_3'] - $inm['k5_4'] - $inm['k5_5'];


	//Devolvemos un json con los valores requeridos
	echo json_encode(array("id"=>$inm['id'], "idtipoinm"=>$inm['idtipoinm'], "barrio"=>$inm['barrio'],
												 "nombrevia"=>$inm['nombrevia'], "numeroinm"=>$inm['numeroinm'],
												 "codigo"=>$inm['codigo'], "superficie"=>$inm['superficie'], "zona"=>$inm['zona'],
												 "tipovia"=>$inm['tipovia'], "topografia"=>$inm['topografia'], "forma"=>$inm['forma'],
												 "ubicacion"=>$inm['ubicacion'], "servicios"=>$servicios, "frentefondo"=>$inm['frentefondo'],
												 "revalorizacion_tecnica"=>$inm['revaloracion'], "superficie_hectareas"=>$inm['suphectarea'],
												 "valor_hectareas"=>$inm['valhectarea'], "exento"=>$inm['exento'],
												 "agua_potable"=>$inm['aguapotabl'], "alcantarillado"=>$inm['alcantaril'],
												 "energia_electrica"=>$inm['electricid'], "telefono"=>$inm['telefonoin'], "transporte"=>$inm['transporte']
											 ));

?>
