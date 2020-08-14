<?php
  //include("verificar_login.php");
  //include("inc.config.php");

  $link=Conectarse();

  //Tomamos las variables necesarias
  $pago = $_SESSION['pago'];
  $gestion = $_SESSION['gestion'];
  $meses = array('Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre', 'Enero');

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

  //Tomamos la fecha y hopra actual
  date_default_timezone_set('America/La_Paz');
  $fecha = date("d/m/Y");
  $hora = date("H:i");

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
   <table border="0" width="100%">
     <tr>
       <td colspan="2">
         <div class="encabezado">
           Reporte de Recaudaci&oacute;n
         </div>
       </td>
     </tr>

     <tr>
       <td>
         <div class="subtitulo_izquierda">
           Sistema QMap M&oacute;dulo Tributario
         </div>
       </td>
       <td>
         <div class="subtitulo">
           Fecha: <?php echo $fecha; ?>
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
           Hora: <?php echo $hora; ?>
         </div>
       </td>
     </tr>

     <tr>
       <td>
         <div class="subtitulo_izquierda">
           Reporte: Recaudaciones por Periodos Fiscales <?php echo "(".$gestion.")"; ?>
         </div>
       </td>
       <td>
         <div class="subtitulo">
           <!-->P&aacute;gina:<-->
         </div>
       </td>
     </tr>

     <tr>
       <td colspan="2">
         <div class="subtitulo_izquierda">
           Formato: Detalle Mensual / Gesti&oacute;n: <?php echo $gestion;?> / Mes: <?php if (isset($reporte_anual)) {echo "Anual";} else {echo $mes_literal;}?>
         </div>
       </td>
     </tr>

     <tr>
       <td colspan="2">
         <div class="subtitulo_izquierda">
           <?php if ($pago == "inmuebles") {
             echo "Rubro: Bienes Inmuebles";
           } ?>
         </div>
       </td>
     </tr>
   </table>

   <table width="100%" style="border-collapse: collapse">
     <tr>
       <th class="td_linea_arriba_abajo"><div class="subtitulo_centrado">Mes</div></th>
       <th class="td_linea_arriba_abajo"><div class="subtitulo_centrado">Fecha</div></th>
       <th class="td_linea_arriba_abajo"><div class="subtitulo_centrado">Neto</div></th>
       <th class="td_linea_arriba_abajo"><div class="subtitulo_centrado">(-10%)</div></th>
       <th class="td_linea_arriba_abajo"><div class="subtitulo_centrado">M. Valor</div></th>
       <th class="td_linea_arriba_abajo"><div class="subtitulo_centrado">Interes</div></th>
       <th class="td_linea_arriba_abajo"><div class="subtitulo_centrado">Mora</div></th>
       <th class="td_linea_arriba_abajo"><div class="subtitulo_centrado">D. Form</div></th>
       <th class="td_linea_arriba_abajo"><div class="subtitulo_centrado">S. Adm</div></th>
       <th class="td_linea_arriba_abajo"><div class="subtitulo_centrado">P. Form</div></th>
       <th class="td_linea_arriba_abajo"><div class="subtitulo_centrado">Importe</div></th>
     </tr>
     <tr>
       <td colspan="11">
         <div class="subtitulo_izquierda">
           Tipo Liquidaci&oacute;n: Original
         </div>
       </td>
     </tr>
     <?php
       $parcial_impuestoneto = 0;
       $parcial_mantevalor = 0;
       $parcial_interes = 0;
       $parcial_multamora = 0;
       $parcial_deberesfor = 0;
       $parcial_sancionadm = 0;
       $parcial_formulario = 0;
       $parcial_total = 0;
       $mes_mostrar_anterior = 0;
       while ($pago = pg_fetch_array($pagos, NULL, PGSQL_BOTH)) {
       ?>
         <tr>
           <td>
             <div class="valores_centrado">
               <?php
                //Tomamos el mes a mostrar
                $mes_mostrar = substr($pago['dia'], 5, 2);
                if ($mes_mostrar != $mes_mostrar_anterior) {
                  echo $meses[(date('m', strtotime('0-'.$mes_mostrar.'-0'))*1)-1];
                }
                $mes_mostrar_anterior = $mes_mostrar
               ?>
             </div>
           </td>
           <td><div class="valores_centrado"><?php if (isset($mes)) {echo $pago['dia'];} ?></div></td>
           <td><div class="valores_centrado"><?php echo number_format($pago['impuestoneto'], 2, '.', ','); ?></div></td>
           <td><div class="valores_centrado">0.00</div></td>
           <td><div class="valores_centrado"><?php echo number_format($pago['mantevalor'], 2, '.', ','); ?></div></td>
           <td><div class="valores_centrado"><?php echo number_format($pago['interes'], 2, '.', ','); ?></div></td>
           <td><div class="valores_centrado"><?php echo number_format($pago['multamora'], 2, '.', ','); ?></div></td>
           <td><div class="valores_centrado"><?php echo number_format($pago['deberesfor'], 2, '.', ','); ?></div></td>
           <td><div class="valores_centrado"><?php echo number_format($pago['sancionadm'], 2, '.', ','); ?></div></td>
           <td><div class="valores_centrado"><?php echo number_format($pago['formulario'], 2, '.', ','); ?></div></td>
           <td><div class="valores_centrado"><?php echo number_format($pago['total'], 2, '.', ','); ?></div></td>
         </tr>
       <?php
        //Sumamos los valores
        $parcial_impuestoneto = $parcial_impuestoneto + $pago['impuestoneto'];
        $parcial_mantevalor = $parcial_mantevalor + $pago['mantevalor'];
        $parcial_interes = $parcial_interes + $pago['interes'];
        $parcial_multamora = $parcial_multamora + $pago['multamora'];
        $parcial_deberesfor = $parcial_deberesfor + $pago['deberesfor'];
        $parcial_sancionadm = $parcial_sancionadm + $pago['sancionadm'];
        $parcial_formulario = $parcial_formulario + $pago['formulario'];
        $parcial_total = $parcial_total + $pago['total'];
       }
       ?>
     <tr>
       <td></td>
       <td><div class="valores_centrado">Parcial: </div></td>
       <td class="td_linea_arriba_abajo"><div class="valores_centrado"><?php echo number_format($parcial_impuestoneto, 2, '.', ','); ?></div></td>
       <td class="td_linea_arriba_abajo"><div class="valores_centrado"><?php echo "0.00"; ?></div></td>
       <td class="td_linea_arriba_abajo"><div class="valores_centrado"><?php echo number_format($parcial_mantevalor, 2, '.', ','); ?></div></td>
       <td class="td_linea_arriba_abajo"><div class="valores_centrado"><?php echo number_format($parcial_interes, 2, '.', ','); ?></div></td>
       <td class="td_linea_arriba_abajo"><div class="valores_centrado"><?php echo number_format($parcial_multamora, 2, '.', ','); ?></div></td>
       <td class="td_linea_arriba_abajo"><div class="valores_centrado"><?php echo number_format($parcial_deberesfor, 2, '.', ','); ?></div></td>
       <td class="td_linea_arriba_abajo"><div class="valores_centrado"><?php echo number_format($parcial_sancionadm, 2, '.', ','); ?></div></td>
       <td class="td_linea_arriba_abajo"><div class="valores_centrado"><?php echo number_format($parcial_formulario, 2, '.', ','); ?></div></td>
       <td class="td_linea_arriba_abajo"><div class="valores_centrado"><?php echo number_format($parcial_total, 2, '.', ','); ?></div></td>
     </tr>

     <tr>
       <td class="td_linea_arriba_abajo"></td>
       <td class="td_linea_arriba_abajo"><div class="valores_centrado">Total: </div></td>
       <td class="td_linea_arriba_abajo"><div class="valores_centrado"><?php echo number_format($parcial_impuestoneto, 2, '.', ','); ?></div></td>
       <td class="td_linea_arriba_abajo"><div class="valores_centrado"><?php echo "0.00"; ?></div></td>
       <td class="td_linea_arriba_abajo"><div class="valores_centrado"><?php echo number_format($parcial_mantevalor, 2, '.', ','); ?></div></td>
       <td class="td_linea_arriba_abajo"><div class="valores_centrado"><?php echo number_format($parcial_interes, 2, '.', ','); ?></div></td>
       <td class="td_linea_arriba_abajo"><div class="valores_centrado"><?php echo number_format($parcial_multamora, 2, '.', ','); ?></div></td>
       <td class="td_linea_arriba_abajo"><div class="valores_centrado"><?php echo number_format($parcial_deberesfor, 2, '.', ','); ?></div></td>
       <td class="td_linea_arriba_abajo"><div class="valores_centrado"><?php echo number_format($parcial_sancionadm, 2, '.', ','); ?></div></td>
       <td class="td_linea_arriba_abajo"><div class="valores_centrado"><?php echo number_format($parcial_formulario, 2, '.', ','); ?></div></td>
       <td class="td_linea_arriba_abajo"><div class="valores_centrado"><?php echo number_format($parcial_total, 2, '.', ','); ?></div></td>
     </tr>
   </table>
 </body>
<?php
  //Borramos las variables de sesion
  unset($_SESSION['pago']);
  unset($_SESSION['gestion']);
  unset($_SESSION['reporte_anual']);
  unset($_SESSION['mes']);
?>
