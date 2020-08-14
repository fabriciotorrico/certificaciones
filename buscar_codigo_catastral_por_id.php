<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>
<?php
	include("verificar_login.php");
	include("inc.config.php");
	include("header.php");
	$link=Conectarse();

	$id = $_GET['id'];
	//Tomamos los registros que coincidan con la busqueda
	$sql  = "SELECT B.id, B.propietario, B.ci, B.codigo, B.direccion
					 FROM buscar AS B
					 WHERE B.id = '$id'";
	$result = pg_query($link, $sql);
	$resultado = pg_fetch_array($result, NULL, PGSQL_BOTH);

	//Retornamos los valores
	echo "CI: ".$resultado['ci']
			."<br> Propietario: ".$resultado['propietario']
			."<br> Código: ".$resultado['codigo']
			."<br> Dirección: ".$resultado['direccion'];
	//echo json_encode(array("ci"=>$resultado['ci'], "nombre"=>$resultado['propietario']));
?>
