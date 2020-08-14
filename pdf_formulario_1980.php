<?php
  include("verificar_login.php");
  include("inc.config.php");

  $link=Conectarse();

  //Tomamos la fecha actual
  date_default_timezone_set('America/La_Paz');
  $fecha_hoy = date("Y-m-d");

  //Tomamos las varialbes necesarias
	$existe_liquidacion_previa = $_POST['existe_liquidacion_previa'];

  //Siguiendo la misma lógica que en el archivo liquidacion_inmebles_datos_tecnicos_form.php, verificamos si prviamente se hizo la liquidacion
  //Si no se hizo la liquidacion o se selecciono "Rectificatoria" ($existe_liquidacion_previa = 0), realizamos las oeraciones correspondientes
  //Por el contrario, si existe liquidacion previa, solo mostramos el pdf con los valores guardados
  if ($existe_liquidacion_previa == 0) {
      //Seleccionamos los datos necesarios de la base de datos para llenar la tabla liquidacion
      //Tomamos los datos del inmueble de liquidacion 1
      $sql  = "SELECT *
               FROM liquidacion1
               WHERE id = '$_POST[id]'";
      $result = pg_query($link, $sql);
      $liquidacion1 = pg_fetch_array($result, NULL, PGSQL_BOTH);

      //Tomamos los datos del inmueble de liquidacion 2
      $sql  = "SELECT *
               FROM liquidacion2
               WHERE id = '$_POST[id]'";
      $result = pg_query($link, $sql);
      $liquidacion2 = pg_fetch_array($result, NULL, PGSQL_BOTH);

      //Tomamos los datos de cotizaciones
      $sql  = "SELECT *
               FROM dolarufv
               WHERE fecha = '$fecha_hoy'";
      $result = pg_query($link, $sql);
      $cotizaciones = pg_fetch_array($result, NULL, PGSQL_BOTH);

      //Tomamos los datos para los bloques
      $sql  = "SELECT *
               FROM edificacion
               WHERE idinm = '$_POST[id]'";
      $bloques = pg_query($link, $sql);

      //Seleccionamos los parametros configurados
      $sql  = "SELECT precio_formulario
               FROM parametros_siim
               WHERE activo = '1'";
      $parametros = pg_query($link, $sql);
      $parametro = pg_fetch_array($parametros, NULL, PGSQL_BOTH);

      //Seleccionamos los datos del propietario
      $sql  = "SELECT *
               FROM liquidacion0
               WHERE id = '$_POST[id]'";
      $propietarios = pg_query($link, $sql);
      $propietario = pg_fetch_array($propietarios, NULL, PGSQL_BOTH);

      //Ponemos en activo 0, todos los registros que se hayan hecho previamente con el mismo inmueble y año
      $sql = " UPDATE liquidacion
               SET activo = 0
               WHERE id = '$_POST[id]'
                AND gestion = '$_POST[gestion]'";
      $result = pg_query($sql);



      //Calculamos los valores necesarios para el registro
      //Caclulamos el valor de k5
      if (isset($liquidacion2['k5_1']) && isset($liquidacion2['k5_2']) && isset($liquidacion2['k5_3']) && isset($liquidacion2['k5_4']) && isset($liquidacion2['k5_5'])){
        $k5 = 1-$liquidacion2['k5_1']-$liquidacion2['k5_2']-$liquidacion2['k5_3']-$liquidacion2['k5_4']-$liquidacion2['k5_5'];
      }
      else {
        $k5 = 0;
      }

      //Caclulamos el valor del terreno
      if (isset($liquidacion2['superficie']) && isset($liquidacion2['valunitario']) && isset($liquidacion2['k1']) && isset($liquidacion2['k2']) && isset($liquidacion2['k3']) && isset($liquidacion2['k4'])){
            $valor_terreno = $liquidacion2['superficie'] * $liquidacion2['valunitario'] * $liquidacion2['k1'] * $liquidacion2['k2'] * $liquidacion2['k3'] * $liquidacion2['k4'] * $k5;
            //Redondeamos el valor del terreno a 2 decimales
            $valor_terreno_round = round($valor_terreno,2);
      }
      else {
        $valor_terreno_round = 0.00;
      }

      //Calculamos el total del monto
      $total = $_POST['parcial'] + $parametro['precio_formulario'];

      //Evitamos valores vacios que producen errores al realizar el insert en la base de datos
      if (isset($liquidacion1['superficie'])) {$superficie = $liquidacion1['superficie'];}else {$superficie = 0;}
      if (isset($liquidacion2['valunitario'])) {$valunitario = $liquidacion2['valunitario'];}else {$valunitario = 0;}
      if (isset($liquidacion2['k1'])) {$k1 = $liquidacion2['k1'];}else {$k1 = 0;}
      if (isset($liquidacion2['k2'])) {$k2 = $liquidacion2['k2'];}else {$k2 = 0;}
      if (isset($liquidacion2['k3'])) {$k3 = $liquidacion2['k3'];}else {$k3 = 0;}
      if (isset($liquidacion2['k4'])) {$k4 = $liquidacion2['k4'];}else {$k4 = 0;}
      if (isset($liquidacion2['k5_1'])) {$k5_1 = $liquidacion2['k5_1'];}else {$k5_1 = 0;}
      if (isset($liquidacion2['k5_2'])) {$k5_2 = $liquidacion2['k5_2'];}else {$k5_2 = 0;}
      if (isset($liquidacion2['k5_3'])) {$k5_3 = $liquidacion2['k5_3'];}else {$k5_3 = 0;}
      if (isset($liquidacion2['k5_4'])) {$k5_4 = $liquidacion2['k5_4'];}else {$k5_4 = 0;}
      if (isset($liquidacion2['k5_5'])) {$k5_5 = $liquidacion2['k5_5'];}else {$k5_5 = 0;}
      if (isset($liquidacion2['k6'])) {$k6 = $liquidacion2['k6'];}else {$k6 = 0;}
      if (isset($parametro['precio_formulario'])) {$precio_formulario = $parametro['precio_formulario'];}else {$precio_formulario = 0;}
      if (isset($liquidacion2['exencion'])) {$exencion = $liquidacion2['exencion'];}else {$exencion = 0;}
      if (isset($propietario['numeroprop'])) {$numeroprop = $propietario['numeroprop'];}else {$numeroprop = "S/N";}
      if (isset($propietario['idtipodoc'])) {$idtipodoc = $propietario['idtipodoc'];}else {$idtipodoc = "";}
      if (isset($propietario['centrourbanprop'])) {$centrourbanprop = $propietario['centrourbanprop'];}else {$centrourbanprop = "";}
      if (isset($propietario['barioprop'])) {$barioprop = $propietario['barioprop'];}else {$barioprop = "";}
      if (isset($propietario['cenurbaninm'])) {$cenurbaninm = $propietario['cenurbaninm'];}else {$cenurbaninm = "";}
      if (isset($propietario['direccioninm'])) {$direccioninm = $propietario['direccioninm'];}else {$direccioninm = "";}

      if (isset($liquidacion2['alicuota'])) {$alicuota = $liquidacion2['alicuota'];}else {$alicuota = 0;}
      if (isset($liquidacion2['fechliqui'])) {$fechliqui = $liquidacion2['fechliqui'];}else {$fechliqui = 0000-00-00;}

      //Realizamos el registro con los datos introducidos
      $sql = "INSERT INTO liquidacion
                      (superficie, zona, valunitario,
                      k1, k1_val, k2, k2_val,
                      k3, k3_val, k4, k4_val,
                      k5_1, k5_2, k5_3, k5_4, k5_5, k5_val,
                      k6, k6_val,
                      valterr, impuestoneto, alicuota, mantevalor,
                      interes, multamora, deberesfor, sancionadm,
                      pagotermino, pagoanterior, totalcredito, credito, otrosdoc, saldofavor,
                      parcial, formulario, total, fechvenci, fechliqui, dolar, ufv,
                      exencion, gestion, liquidacion, formapago,
                      pmc, tipo_documento, ci, tipopersona, nombre,
                      centrourbaprop, barrioprop, direccionprop,
                      numeroprop, telefono, codigo, tipoinm, idbarrioinm, nombreviainm,
                      numeroinm, cenurbaninm, direccioninm,
                      id, id_usuario, activo)
              VALUES ('$superficie', '$liquidacion1[zona]', '$valunitario',
                      '$liquidacion1[tipovia]', '$k1', '$liquidacion1[topografia]', '$k2',
                      '$liquidacion1[forma]', '$k3', '$liquidacion1[ubicacion]', '$k4',
                      '$k5_1','$k5_2','$k5_3','$k5_4','$k5_5', '$k5',
                      '$liquidacion1[frentefondo]', '$k6',
                      '$valor_terreno_round', '$_POST[impuesto_neto]', '$alicuota', '0',
                      '0', '0', '0', '0',
                      '$_POST[pago_termino]', '0', '0', '0', '0', '0',
                      '$_POST[parcial]', '$precio_formulario', '$total', '$_POST[fecha_vencimiento]', '$fechliqui','$cotizaciones[dolar]', '$cotizaciones[ufv]',
                      '$exencion', '$_POST[gestion]', '$_POST[liquidacion]', '$_POST[forma_pago]',
                      '0', '$idtipodoc', '$propietario[numero]', '$propietario[persona]', '$propietario[nombre_completo]',
                      '$centrourbanprop', '$barioprop', '$propietario[direccionprop]',
                      '$numeroprop', '$propietario[telefono]', '$liquidacion1[codigo]', '$liquidacion1[idtipoinm]', '$liquidacion1[barrio]', '$liquidacion1[nombrevia]',
                      '$liquidacion1[numeroinm]', '$cenurbaninm', '$direccioninm',
                      '$_POST[id]', '$_SESSION[idusuario_ss]', '1'
                      ) RETURNING idliqui";
      $result = pg_query($sql);
      $reg_insertado = pg_fetch_array($result, NULL, PGSQL_BOTH);
      //Tomamos el id del registro insertado
      $id_liquidacion = $reg_insertado['idliqui'];

      //Seleccionamos los datos necesarios de la base de datos para llenar la tabla liquidacion_construccion
      //Tomamos los datos de la vista edificacion, que corresponden a los valores de los bloques
      $sql  = "SELECT *
               FROM edificacion
               WHERE idinm = '$_POST[id]'";
      $edificaciones = pg_query($link, $sql);


      //Ponemos en activo 0, todos los registros que se hayan hecho previamente con el mismo inmueble y año
      $sql = " UPDATE liquidacion_construccion
               SET activo = 0
               WHERE idinm = '$_POST[id]'
                AND gestion = '$_POST[gestion]'";
      $result = pg_query($sql);

      //Para cada registro en la vista edificaciones (cada bloque), ralizamos un registro en la tabla liquidacion_construccion
      while ($edificacion = pg_fetch_array($edificaciones, NULL, PGSQL_BOTH)) {

        //Caclulamos el valor del terreno
        if (isset($edificacion['superficie']) && isset($edificacion['valor']) && isset($edificacion['kk1_val']) && isset($edificacion['kk2_val']) && isset($edificacion['kk3'])){
              $valor_edificacion = $edificacion['superficie'] * $edificacion['valor'] * $edificacion['kk1_val'] * $edificacion['kk2_val'] * $edificacion['kk3'];
              //Redondeamos el valor de la edificacion a 2 decimales
              $valor_edificacion_round = round($valor_edificacion,2);
        }
        else {
          $valor_edificacion_round = 0;
        }

        //Evitamos valores vacios que producen errores al realizar el insert en la base de datos
        if (isset($edificacion['superficie'])) {$superficie = $edificacion['superficie'];}else {$superficie = 0;}
        if (isset($edificacion['bloque'])) {$bloque = $edificacion['bloque'];}else {$bloque = 0;}
        if (isset($edificacion['plantas'])) {$plantas = $edificacion['plantas'];}else {$plantas = 0;}
        if (isset($edificacion['valor'])) {$valor = $edificacion['valor'];}else {$valor = 0;}
        if (isset($edificacion['kk1_val'])) {$kk1_val = $edificacion['kk1_val'];}else {$kk1_val = 0;}
        if (isset($edificacion['kk2_val'])) {$kk2_val = $edificacion['kk2_val'];}else {$kk2_val = 0;}
        if (isset($edificacion['cimientos_val'])) {$cimientos_val = $edificacion['cimientos_val'];}else {$cimientos_val = 0;}
        if (isset($edificacion['estructuras_val'])) {$estructuras_val = $edificacion['estructuras_val'];}else {$estructuras_val = 0;}
        if (isset($edificacion['muros_val'])) {$muros_val = $edificacion['muros_val'];}else {$muros_val = 0;}
        if (isset($edificacion['cubierta_val'])) {$cubierta_val = $edificacion['cubierta_val'];}else {$cubierta_val = 0;}
        if (isset($edificacion['cielos_val'])) {$cielos_val = $edificacion['cielos_val'];}else {$cielos_val = 0;}
        if (isset($edificacion['pisos_val'])) {$pisos_val = $edificacion['pisos_val'];}else {$pisos_val = 0;}
        if (isset($edificacion['ventanas_val'])) {$ventanas_val = $edificacion['ventanas_val'];}else {$ventanas_val = 0;}
        if (isset($edificacion['puertas_val'])) {$puertas_val = $edificacion['puertas_val'];}else {$puertas_val = 0;}
        if (isset($edificacion['interior_val'])) {$interior_val = $edificacion['interior_val'];}else {$interior_val = 0;}
        if (isset($edificacion['fachada_val'])) {$fachada_val = $edificacion['fachada_val'];}else {$fachada_val = 0;}
        if (isset($edificacion['kk3'])) {$kk3 = $edificacion['kk3'];}else {$kk3 = 0;}
        if (isset($edificacion['tipologia'])) {$tipologia = $edificacion['tipologia'];}else {$tipologia = 0;}

        $sql = "INSERT INTO liquidacion_construccion
                          (idliqui, gestion, idinm, codigo,
                           superficie, bloque, plantas, valunitario,
                           kk1, kk1_val, kk2, kk2_val,
                           cimientos, cimientos_val, estructuras, estructuras_val,
                           muros, muros_val, cubierta, cubierta_val,
                           cielos, cielos_val, pisos, pisos_val,
                           ventanas, ventanas_val, puertas, puertas_val,
                           interior, interior_val, fachada, fachada_val,
                           kk3, tipologia, valedif, activo
                          )
                VALUES ('$id_liquidacion', '$_POST[gestion]', '$edificacion[idinm]', '$edificacion[codigo]',
                        '$superficie', '$bloque', '$plantas', '$valor',
                        '$edificacion[kk1]', '$kk1_val', '$edificacion[kk2]', '$kk2_val',
                        '$edificacion[cimientos]', '$cimientos_val', '$edificacion[estructuras]', '$estructuras_val',
                        '$edificacion[muros]', '$muros_val', '$edificacion[cubierta]', '$cubierta_val',
                        '$edificacion[cielos]', '$cielos_val', '$edificacion[pisos]', '$pisos_val',
                        '$edificacion[ventanas]', '$ventanas_val', '$edificacion[puertas]', '$puertas_val',
                        '$edificacion[interior]', '$interior_val', '$edificacion[fachada]', '$fachada_val',
                        '$kk3', '$tipologia', '$valor_edificacion_round', '1'
                       )";
        $result = pg_query($sql);
      }
  }

  //Incluimos el pdf.php para usar la función
  include ('pdf.php');

  //Creamos variables de sesion para poder ser leidas desde los diseños
  $_SESSION['id_codigo_catastro'] = $_POST['id'];
  $_SESSION['gestion'] = $_POST['gestion'];
  $_SESSION['liquidacion'] = $_POST['liquidacion'];
  $_SESSION['forma_pago'] = $_POST['forma_pago'];

  //Utilizamos la función para generar el pdf, le pasamos la ruta del diseño y el nombre del pdf
  generar_pdf("disenos_pdf/formulario_1980.php", "formulario 1980", "leter", "portrait");
?>



<?php
//generar_pdf($html, "formul 1980", "leter", "portrait");

/*$html = <<<'ENDHTML'
<html>
 <body>
  <h1>Hello Dompdf
  ENDHTML;

  $html = $html + $id + <<<'ENDHTML'
  </h1>
 </body>
</html>
ENDHTML;*/
?>
