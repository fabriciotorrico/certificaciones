<?php
  $link=Conectarse();

  //Tomamos las variables necesarias
  $pago = $_SESSION['pago'];
  $gestion = $_SESSION['gestion'];
  $id_barrio = $_SESSION['id_barrio'];

  //Tomamos la fecha y hopra actual
  date_default_timezone_set('America/La_Paz');
  $mes = date("m");
  $meses = array('Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre', 'Enero');
  $mes_literal = $meses[(date('m', strtotime('0-'.$mes.'-0'))*1)-1];
  $dia = date("l");
  $dias_ES = array("Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado", "Domingo");
  $dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
  $dia_literal = str_replace($dias_EN, $dias_ES, $dia);
  $fecha_hoy = $dia_literal.", ".date("d")." de ".$mes_literal." de ".date("Y");

  //Seleccionamos los parametros configurados
  $sql  = "SELECT D.departamento, M.municipio
           FROM parametros_siim AS P
           LEFT JOIN municipio AS M
            ON P.id_municipio = M.id_municipio
           LEFT JOIN departamento AS D
            ON M.id_departamento = D.id_departamento
           WHERE P.activo = '1'";
  $result = pg_query($link, $sql);
  $parametro = pg_fetch_array($result, NULL, PGSQL_BOTH);

  //Seleccionamos los barrios segun corresponda
  if ($id_barrio == 0) {
    $sql  = "SELECT idbarrio, nombre
             FROM barrio
             ORDER BY nombre";
    $barrios = pg_query($link, $sql);
  }
  else {
    $sql  = "SELECT idbarrio, nombre
             FROM barrio
             WHERE idbarrio = $id_barrio";
    $barrios = pg_query($link, $sql);
  }


/*
  //Seleccionamos los barrios
  $sql  = "SELECT idbarrio, nombre
           FROM barrio
           ORDER BY nombre";
  $barrios = pg_query($link, $sql);
/*
  //Tomamos reporte anual o el mes seleccionado segun corresponda
  if (isset($_SESSION['reporte_anual'])) {
    $reporte_anual = $_SESSION['reporte_anual'];

    //Seleccionamos los pagos
     $sql  = "SELECT substring(P.fecha, 1, 7) as dia, sum(L.impuestoneto) as impuestoneto, sum(L.mantevalor) as mantevalor, sum(L.interes) as interes,
                     sum(L.multamora) as multamora, sum(L.deberesfor) as deberesfor, sum(L.sancionadm) as sancionadm,
                     sum(L.formulario) as formulario, sum(L.total) as total
              FROM pagoinm AS P
              LEFT JOIN liquidacion AS L
               ON P.id_liquidacion = L.idliqui
              WHERE P.fecha LIKE '$gestion%'
               AND P.activo = '1'
              GROUP BY substring(P.fecha, 1, 7)
              ORDER BY substring(P.fecha, 1, 7)";
    $pagos = pg_query($link, $sql);
  }
  else {
    $mes = $_SESSION['mes'];
    $mes_literal = $meses[(date('m', strtotime('0-'.$mes.'-0'))*1)-1];

    //Seleccionamos los pagos
     $sql  = "SELECT P.fecha as dia, sum(L.impuestoneto) as impuestoneto, sum(L.mantevalor) as mantevalor, sum(L.interes) as interes,
                     sum(L.multamora) as multamora, sum(L.deberesfor) as deberesfor, sum(L.sancionadm) as sancionadm,
                     sum(L.formulario) as formulario, sum(L.total) as total
              FROM pagoinm AS P
              LEFT JOIN liquidacion AS L
               ON P.id_liquidacion = L.idliqui
              WHERE P.fecha LIKE '$gestion%-%$mes-%'
               AND P.activo = '1'
              GROUP BY P.fecha
              ORDER BY P.fecha";
    $pagos = pg_query($link, $sql);
  }


  */
?>
<html>
<?php include_once ("header_diseños.php"); ?>
 <body>
   <table border="0" width="100%">
     <tr>
       <td>
         <div class="subtitulo_izquierda">
          Sistema QMap M&oacute;dulo Impositivo
         </div>
       </td>
     </tr>

     <tr>
       <td>
         <div class="subtitulo_izquierda">
          Gobierno Aut&oacute;nomo Municipal de <?php echo $parametro['municipio']." (".$parametro['departamento'].")"; ?>
         </div>
       </td>
     </tr>

     <tr>
       <td>
         <div class="encabezado">
           LISTA BIENES INMUEBBLES POR BARRIO
         </div>
       </td>
     </tr>

     <tr>
       <td>
         <div class="encabezado">
           IMPUESTOS GESTI&Oacute;N <?php echo $gestion; ?>
         </div>
       </td>
     </tr>

     <tr>
       <td>
         <div class="subtitulo" style="text-align: center;">
           <?php echo $fecha_hoy; ?>
         </div>
       </td>
     </tr>

     <tr>
       <td>
         <div class="subtitulo_izquierda">
          Ordenado por Barrio y Contribuyente
         </div>
       </td>
     </tr>
   </table>

   <?php
   while ($barrio = pg_fetch_array($barrios, NULL, PGSQL_BOTH)) {
   ?>
   <table width="100%" style="border-collapse: collapse">
     <tr>
       <td colspan="5">
         <div class="subtitulo_centrado">
           <b>Barrio: <?php echo $barrio['nombre']; ?></b>
         </div>
       </td>
     </tr>
     <tr>
       <th class="td_linea_arriba_abajo"><div class="subtitulo_centrado">Nombre</div></th>
       <th class="td_linea_arriba_abajo"><div class="subtitulo_centrado">CI</div></th>
       <th class="td_linea_arriba_abajo"><div class="subtitulo_centrado">Tipo</div></th>
       <th class="td_linea_arriba_abajo"><div class="subtitulo_centrado">C&oacute;digo Catastral</div></th>
       <th class="td_linea_arriba_abajo"><div class="subtitulo_centrado">Monto Cancelado</div></th>
     </tr>
     <?php
       //Seleccionamos los inmuebles de ese barrio, unido a sus propietarios y monto pagado
       /*$sql  = "SELECT L.paterno, L.materno, L.nombres, L.numero, L.tipoinm, L.codigo, P.montopagar
                FROM liquidacion0 AS L
                LEFT JOIN pagoinm AS P
                  ON L.id = P.idinm
                WHERE L.idbarrioin = '$barrio[idbarrio]'
                 AND (P.activo IS NULL OR P.activo = 1)
                 AND (P.fecha IS NULL OR P.fecha LIKE '$gestion%')
                ORDER BY L.paterno, L.materno, L.nombres";*/
      $sql  = "SELECT L.id, L.paterno, L.materno, L.nombres, L.numero, L.tipoinm, L.codigo
               FROM liquidacion0 AS L
               WHERE L.idbarrioin = '$barrio[idbarrio]'
               ORDER BY L.paterno, L.materno, L.nombres";
      $inmuebles = pg_query($link, $sql);
      while ($inmueble = pg_fetch_array($inmuebles, NULL, PGSQL_BOTH)) {
        //Seleccionamos el pago del inmueble para la gestion dada
        $sql  = "SELECT montopagar
                 FROM pagoinm
                 WHERE idinm = '$inmueble[id]'
                 AND fecha LIKE '$gestion%'
                 AND activo = 1";
        $pagos_inm = pg_query($link, $sql);
        $pago_inm = pg_fetch_array($pagos_inm, NULL, PGSQL_BOTH);
        if (isset($pago_inm)) {$monto_cancelado = $pago_inm['montopagar'];}
        else {$monto_cancelado = 0;}
      ?>
        <tr>
          <td><div class="valores_centrado"><?php echo $inmueble['paterno']." ".$inmueble['materno']." ".$inmueble['nombres']; ?></div></td>
          <td><div class="valores_centrado"><?php echo $inmueble['numero']; ?></div></td>
          <td><div class="valores_centrado"><?php echo $inmueble['tipoinm']; ?></div></td>
          <td><div class="valores_centrado"><?php echo $inmueble['codigo']; ?></div></td>
          <td><div class="valores_centrado"><?php echo number_format($monto_cancelado, 2, '.', ','); ?></div></td>
         </tr>
      <?php
      }
    ?>
   </table>
   <?php
   }
   ?>
 </body>
<?php
  //Borramos las variables de sesion
  unset($_SESSION['pago']);
  unset($_SESSION['gestion']);
  unset($_SESSION['id_barrio']);
?>
