<?php
  include("verificar_login.php");
  include("inc.config.php");
  $link=Conectarse();

  //Tomamos las varialbes necesarias
	$id_inmueble = $_POST['id_inmueble'];
	$ci_vendedor = $_POST['ci_vendedor'];
	$ci_comprador = $_POST['ci_comprador'];
	$bolivianos = $_POST['bolivianos'];
	$dolares = $_POST['dolares'];
	$ddrr = $_POST['ddrr'];
	$minuta = $_POST['minuta'];
	$valor_terreno = $_POST['valor_terreno'];
	$base_imponible = $_POST['base_imponible'];
	$impuesto_neto = $_POST['impuesto_neto'];
	$monto_transferencia = $_POST['monto_transferencia'];
	$fecha_vencimiento = $_POST['fecha_vencimiento'];
	$cotizacion_dolar = $_POST['cotizacion_dolar'];
	$cotizacion_ufv = $_POST['cotizacion_ufv'];

  //Tomamos los datos de transferencia1
	$sql  = "SELECT *
					 FROM transferencia1
					 WHERE id = '$id_inmueble'";
	$result = pg_query($link, $sql);
	$inm1 = pg_fetch_array($result, NULL, PGSQL_BOTH);
  //Calculamos k5 (servicios)
  $servicios = 1 - $inm1['k5_1'] - $inm1['k5_2'] - $inm1['k5_3'] - $inm1['k5_4'] - $inm1['k5_5'];

  //Tomamos los datos del inmueble
  $sql  = "SELECT *
           FROM transferencia2
           WHERE id = '$id_inmueble'";
  $result = pg_query($link, $sql);
  $inm = pg_fetch_array($result, NULL, PGSQL_BOTH);

  //Tomamos los datos de las edificaciones para calcular la superficie construida
  $sql  = "SELECT *
           FROM edificacion
           WHERE idinm = '$id_inmueble'";
  $edificaciones = pg_query($link, $sql);

  $supedif = 0;
  while ($edificacion = pg_fetch_array($edificaciones, NULL, PGSQL_BOTH)) {
    $supedif = $supedif + $edificacion['superficie'];
  }

  //Evitamos valores vacios que producen errores al realizar el insert en la base de datos
  if (isset($inm['superficie'])) {$superficie = $inm['superficie'];}else {$superficie = 0;}
  if (isset($inm['valunitario'])) {$valunitario = $inm['valunitario'];}else {$valunitario = 0;}
  if (isset($inm['mantevalor'])) {$mantevalor = $inm['mantevalor'];}else {$mantevalor = 0;}
  if (isset($inm['interes'])) {$interes = $inm['interes'];}else {$interes = 0;}
  if (isset($inm['multamora'])) {$multamora = $inm['multamora'];}else {$multamora = 0;}
  if (isset($inm['debformales'])) {$debformales = $inm['debformales'];}else {$debformales = 0;}
  if (isset($inm['sancionadm'])) {$sancionadm = $inm['sancionadm'];}else {$sancionadm = 0;}
  if (isset($inm['credito'])) {$credito = $inm['credito'];}else {$credito = 0;}
  if (isset($inm['otros'])) {$otros = $inm['otros'];}else {$otros = 0;}
  if (isset($inm['saldofavor'])) {$saldofavor = $inm['saldofavor'];}else {$saldofavor = 0;}
  if (!isset($valor_terreno)) {$valor_terreno = 0;}
  if (!isset($base_imponible)) {$base_imponible = 0;}
  if (!isset($impuesto_neto)) {$impuesto_neto = 0;}
  if (!isset($servicios)) {$servicios = 0;}

  //Realizamos el registro con los datos introducidos
  $sql = "INSERT INTO transferencia
                  (idinm, ci_vendedor, ci_comprador, montobs, montosus,
                   ddrr, fechminuta, supterreno, valuniterr, valterreno,
                   supedif,
                   codigo, k1, k2, k3, k4, k5, k6,
                   baseimponible, montotranfe, fechvenci, fechliqui, dolar,
                   ufv, impuestoneto, alicuota, mantevalor, interes,
                   multamora, deberesfor, sancionadm, credito, otrosdoc,
                   saldofavor, id_usuario, activo)
          VALUES ('$id_inmueble', '$ci_vendedor', '$ci_comprador', '$bolivianos', '$dolares',
                  '$ddrr', '$minuta', '$superficie', '$valunitario', '$valor_terreno',
                  '$supedif',
                  '$inm1[codigo]', '$inm1[tipovia]', '$inm1[topografia]', '$inm1[forma]', '$inm1[ubicacion]', '$servicios', '$inm1[frentefondo]',
                  '$base_imponible', '$monto_transferencia', '$fecha_vencimiento', '$inm[fechliqui]', '$cotizacion_dolar',
                  '$cotizacion_ufv', '$impuesto_neto', '$inm[alicuota]', '$mantevalor', '$interes',
                  '$multamora', '$debformales', '$sancionadm', '$credito', '$otros',
                  '$saldofavor', '$_SESSION[idusuario_ss]', '1'
                ) RETURNING idtransf";
  $result = pg_query($sql);
  $reg_insertado = pg_fetch_array($result, NULL, PGSQL_BOTH);
  //Tomamos el id del registro insertado
  $id_transferencia = $reg_insertado['idtransf'];

  //Tomamos los datos de los bloques
  $sql  = "SELECT *
           FROM edificacion
           WHERE idinm = '$id_inmueble'";
  $bloques = pg_query($link, $sql);

  //Realizamos los registros en la tabla transferencia_construccion
  while ($bloque = pg_fetch_array($bloques, NULL, PGSQL_BOTH)) {
    //Evitamos valores vacios que producen errores al realizar el insert en la base de datos
    if (isset($bloque['superficie'])) {$superficie = $bloque['superficie'];}else {$superficie = 0;}
    if (isset($bloque['valor'])) {$valor_unitario = $bloque['valor'];}else {$valor_unitario = 0;}
    if (isset($bloque['kk1_val'])) {$kk1_val = $bloque['kk1_val'];}else {$kk1_val = 0;}
    if (isset($bloque['kk2_val'])) {$kk2_val = $bloque['kk2_val'];}else {$kk2_val = 0;}
    if (isset($bloque['kk3_val'])) {$kk3_val = $bloque['kk3_val'];}else {$kk3_val = 0;}

    $sql = "INSERT INTO transferencia_construccion
                    (id_transferencia, id_inmueble, superficie, valor_unitario,
                     kk1, kk1_val, kk2, kk2_val, kk3_val,
                     id_usuario, activo)
            VALUES ('$id_transferencia', '$id_inmueble', '$superficie', '$valor_unitario',
                    '$bloque[kk1]', '$kk1_val', '$bloque[kk2]', '$kk2_val', '$kk3_val',
                    '$_SESSION[idusuario_ss]', '1'
                   )";
    $result = pg_query($sql);
  }

  //Incluimos el pdf.php para usar la función
  include ('pdf.php');

  //Creamos variables de sesion para poder ser leidas desde los diseños
  $_SESSION['id_inmueble'] = $_POST['id_inmueble'];
  $_SESSION['ci_vendedor'] = $_POST['ci_vendedor'];
  $_SESSION['ci_comprador'] = $_POST['ci_comprador'];
  $_SESSION['bolivianos'] = $_POST['bolivianos'];
  $_SESSION['dolares'] = $_POST['dolares'];
  $_SESSION['ddrr'] = $_POST['ddrr'];
  $_SESSION['minuta'] = $_POST['minuta'];
  $_SESSION['monto_transferencia'] = $_POST['monto_transferencia'];

  //Utilizamos la función para generar el pdf, le pasamos la ruta del diseño y el nombre del pdf
  generar_pdf("disenos_pdf/transf_inm_parte_1.php", "Transf Inm - Parte 1", "leter", "portrait");
?>
