<?php
	include("verificar_login.php");
	include("inc.config.php");
	//include("header.php");
	$link=Conectarse();

	$ci = $_GET['ci'];
	//Tomamos los registros que coincidan con la busqueda
	$sql  = "SELECT ci, propietario
					 FROM buscar
					 WHERE ci LIKE UPPER('%$ci%')";
	$result = pg_query($link, $sql);
	$resultado = pg_fetch_array($result, NULL, PGSQL_BOTH);

	//Retornamos los valores
	echo json_encode(array("ci"=>$resultado['ci'], "nombre"=>$resultado['propietario']));
?>
