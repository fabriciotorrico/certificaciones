<?php
  $link=Conectarse();

  //Tomamos las variables necesarias
  $tipo = $_SESSION['tipo'];
  $soi = $_SESSION['soi'];

  //Tomamos la fecha y hopra actual
  date_default_timezone_set('America/La_Paz');
  $fecha_hoy = date("d/m/Y");
  $fecha_hoy_comparacion = date("Y-m-d");
  $fecha_comparacion_desde = date("Y-m-01");
  $fecha_comparacion_hasta = date("Y-m-d");
  $hora = date("H:i");
  $dia = date("d");
  $mes = date("m");
  $meses = array('Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre', 'Enero');
  $mes_literal = $meses[(date('m', strtotime('0-'.$mes.'-0'))*1)-1];
  $ano = date("Y");

  //Establecemos segun el tipo
  if ($tipo == "al_dia") {
    $fecha_seleccionada = $dia." de ".$mes_literal." de ".$ano;

    //Tomamos los montos
    $sql  = "SELECT sum(L.formulario) as formulario, sum(P.montopagar) as impuestos
             FROM pagoinm AS P
             LEFT JOIN liquidacion AS L
              ON P.id_liquidacion = L.idliqui
             WHERE P.fecha = '$fecha_hoy_comparacion'
              AND P.activo = '1'";
    $result = pg_query($link, $sql);
    $montos = pg_fetch_array($result, NULL, PGSQL_BOTH);
  }
  else {
    $fecha_seleccionada = "Del 01 de ".$mes_literal." de ".$ano." al ".$dia." de ".$mes_literal." de ".$ano;

    //Tomamos los montos
    $sql  = "SELECT sum(L.formulario) as formulario, sum(P.montopagar) as impuestos
             FROM pagoinm AS P
             LEFT JOIN liquidacion AS L
              ON P.id_liquidacion = L.idliqui
             WHERE P.fecha BETWEEN '$fecha_comparacion_desde' AND '$fecha_comparacion_hasta'
              AND P.activo = '1'";
    $result = pg_query($link, $sql);
    $montos = pg_fetch_array($result, NULL, PGSQL_BOTH);
  }

  //Establecemos los valores a mostrar
  $ipbi_formulario = $montos['formulario'];
  $ipbi_impuestos = $montos['impuestos'];
  $suma_ipbi = $ipbi_impuestos + $ipbi_formulario;

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
?>
<html>
<?php include_once ("header_diseños.php"); ?>
 <body>
   <table border="0" width="98%">
     <tr>
       <td>
         <div class="subtitulo_izquierda">
          Sistema QMap M&oacute;dulo Impositivo
         </div>
       </td>
       <td>
         <div class="subtitulo">
          Impreso: <?php echo $fecha_hoy;?>
         </div>
       </td>
     </tr>

     <tr>
       <td>
         <div class="subtitulo_izquierda">
          Gobierno Aut&oacute;nomo Municipal de <?php echo $parametro['municipio']." (".$parametro['departamento'].")"; ?>
         </div>
       </td>
       <td>
         <div class="subtitulo">
          Hora: <?php echo $hora;?>
         </div>
       </td>
     </tr>

     <tr>
       <td colspan="2">
         <div class="encabezado">
           RECACUDACI&Oacute;N POR RUBROS Y TIPO DE INGRESO
         </div>
       </td>
     </tr>

     <tr>
       <td colspan="2">
         <div class="encabezado">
           <?php echo $fecha_seleccionada;?>
         </div>
       </td>
     </tr>
   </table>

   <br>
   <?php $monto=0; ?>
   <table width="98%" style="border-collapse: collapse">
    <tr>
      <th class="td_linea_arriba_abajo"><div class="subtitulo_centrado">Rubro</div></th>
      <th class="td_linea_arriba_abajo"><div class="subtitulo_centrado">Descripci&oacute;n</div></th>
      <th class="td_linea_arriba_abajo"><div class="subtitulo_centrado">Monto</div></th>
    </tr>

    <tr>
      <td><div class="valores_centrado">12000</div></td>
      <td><div class="valores">VENTA DE BIENES Y SERVICIOS DE LAS ADMINISTRACIONES</div></td>
      <td><div class="valores_centrado"><?php echo number_format($monto, 2, '.', ','); ?></div></td>
    </tr>

    <tr>
      <td><div class="valores_centrado">12200</div></td>
      <td><div class="valores"> Venta de Servicios de las Administraciones P&uacute;blicas</div></td>
      <td><div class="valores_centrado"><?php echo number_format($monto, 2, '.', ','); ?></div></td>
    </tr>

    <tr>
      <td><div class="valores_centrado">13000</div></td>
      <td><div class="valores">INGRESOS TRIBUTARIOS</div></td>
      <td><div class="valores_centrado"><?php echo number_format($monto, 2, '.', ','); ?></div></td>
    </tr>

    <tr>
      <td><div class="valores_centrado">13300</div></td>
      <td><div class="valores"> Impuestos Directos Municipales</div></td>
      <td><div class="valores_centrado"><?php echo number_format($monto, 2, '.', ','); ?></div></td>
    </tr>

    <tr>
      <td><div class="valores_centrado">13310</div></td>
      <td><div class="valores"> Impuesto a la Propiedad de Bienes Inmuebles</div></td>
      <td><div class="valores_centrado"><?php echo number_format($monto, 2, '.', ','); ?></div></td>
    </tr>

    <tr>
      <td class="td_linea_arriba_abajo" colspan="2"><div class="valores" align="right">TOTAL</div></td>
      <td class="td_linea_arriba_abajo"><div class="valores_centrado"><?php echo number_format($monto, 2, '.', ','); ?></div></td>
    </tr>
   </table>

   <br>

   <table width="98%" style="border-collapse: collapse">
    <tr>
      <th class="td_linea_punteada_arriba_abajo"><div class="subtitulo_centrado">RUBRO</div></th>
      <th class="td_linea_punteada_arriba_abajo"><div class="subtitulo_centrado">IPBI</div></th>
      <th class="td_linea_punteada_arriba_abajo"><div class="subtitulo_centrado">RECTIF <br>IPBI</div></th>
      <th class="td_linea_punteada_arriba_abajo"><div class="subtitulo_centrado">TRANSF <br>IPBI</div></th>
      <th class="td_linea_punteada_arriba_abajo"><div class="subtitulo_centrado">ACTIVID <br>ECON</div></th>
      <th class="td_linea_punteada_arriba_abajo"><div class="subtitulo_centrado">RECTIF <br>ACT ECO</div></th>
      <th class="td_linea_punteada_arriba_abajo"><div class="subtitulo_centrado">OTORS <br>INGRESOS</div></th>
      <th class="td_linea_punteada_arriba_abajo"><div class="subtitulo_centrado">TOTAL <br>GRAL</div></th>
    </tr>

    <tr>
      <td><div class="valores_centrado">12200</div></td>
      <td><div class="valores_centrado"><?php echo number_format($ipbi_formulario, 2, '.', ','); ?></div></td>
      <td><div class="valores_centrado">0.00</div></td>
      <td><div class="valores_centrado">0.00</div></td>
      <td><div class="valores_centrado">0.00</div></td>
      <td><div class="valores_centrado">0.00</div></td>
      <td><div class="valores_centrado">0.00</div></td>
      <td><div class="valores_centrado"><?php echo number_format($ipbi_formulario, 2, '.', ','); ?></div></td>
    </tr>

    <tr>
      <td><div class="valores_centrado">13310</div></td>
      <td><div class="valores_centrado"><?php echo number_format($ipbi_impuestos, 2, '.', ','); ?></div></td>
      <td><div class="valores_centrado">0.00</div></td>
      <td><div class="valores_centrado">0.00</div></td>
      <td><div class="valores_centrado">0.00</div></td>
      <td><div class="valores_centrado">0.00</div></td>
      <td><div class="valores_centrado">0.00</div></td>
      <td><div class="valores_centrado"><?php echo number_format($ipbi_impuestos, 2, '.', ','); ?></div></td>
    </tr>

    <tr>
      <td><div class="valores_centrado">15910</div></td>
      <td><div class="valores_centrado">0.00</div></td>
      <td><div class="valores_centrado">0.00</div></td>
      <td><div class="valores_centrado">0.00</div></td>
      <td><div class="valores_centrado">0.00</div></td>
      <td><div class="valores_centrado">0.00</div></td>
      <td><div class="valores_centrado">0.00</div></td>
      <td><div class="valores_centrado">0.00</div></td>
    </tr>

    <tr>
      <td><div class="valores_centrado">16190</div></td>
      <td><div class="valores_centrado">0.00</div></td>
      <td><div class="valores_centrado">0.00</div></td>
      <td><div class="valores_centrado">0.00</div></td>
      <td><div class="valores_centrado">0.00</div></td>
      <td><div class="valores_centrado">0.00</div></td>
      <td><div class="valores_centrado">0.00</div></td>
      <td><div class="valores_centrado">0.00</div></td>
    </tr>

    <tr>
      <td class="td_linea_punteada_arriba_abajo"><div class="valores" align="right">TOTAL</div></td>
      <td class="td_linea_punteada_arriba_abajo"><div class="valores_centrado"><?php echo number_format($suma_ipbi, 2, '.', ','); ?></div></td>
      <td class="td_linea_punteada_arriba_abajo"><div class="valores_centrado">0.00</div></td>
      <td class="td_linea_punteada_arriba_abajo"><div class="valores_centrado">0.00</div></td>
      <td class="td_linea_punteada_arriba_abajo"><div class="valores_centrado">0.00</div></td>
      <td class="td_linea_punteada_arriba_abajo"><div class="valores_centrado">0.00</div></td>
      <td class="td_linea_punteada_arriba_abajo"><div class="valores_centrado">0.00</div></td>
      <td class="td_linea_punteada_arriba_abajo"><div class="valores_centrado"><?php echo number_format($suma_ipbi, 2, '.', ','); ?></div></td>
    </tr>
   </table>
 </body>
<?php
  //Borramos las variables de sesion
  unset($_SESSION['tipo']);
  unset($_SESSION['soi']);
?>
