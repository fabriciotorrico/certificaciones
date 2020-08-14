<?php
	include("verificar_login.php");
	include("inc.config.php");
	//include("header.php");
	$link=Conectarse();

	$id_inmueble = $_GET['id_inmueble'];
	$ci_vendedor = $_GET['ci_vendedor'];
	$ci_comprador = $_GET['ci_comprador'];
	$bolivianos = $_GET['bolivianos'];
	$dolares = $_GET['dolares'];
	$ddrr = $_GET['ddrr'];
	$minuta = $_GET['minuta'];
	$monto_transferencia = $_GET['monto_transferencia'];

	//Tomamos los datos de la transferencia para determinar si habilitar o no el boton parte 2
  $sql  = "SELECT idtransf
           FROM transferencia
           WHERE idinm = '$id_inmueble'
             AND ci_vendedor = '$ci_vendedor'
             AND ci_comprador = '$ci_comprador'
             AND montobs = '$bolivianos'
             AND montosus = '$dolares'
             AND ddrr = '$ddrr'
             AND fechminuta = '$minuta'
             AND montotranfe = '$monto_transferencia'
             AND activo = '1'
           ORDER BY idtransf DESC
           LIMIT 1";
  $result = pg_query($link, $sql);
  $existe_transferencia = pg_fetch_array($result, NULL, PGSQL_BOTH);

	//Retornamos si o no segun corresponda
	if (isset($existe_transferencia['idtransf'])) {
		echo "si";
	}
	else {
		echo "no";
	}
?>
