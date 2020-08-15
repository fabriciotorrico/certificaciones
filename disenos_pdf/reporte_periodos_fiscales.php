<?php
  $link=Conectarse();

  //Tomamos las variables necesarias
  $pago = $_SESSION['pago'];
  $gestion = $_SESSION['gestion'];
  $meses = array('Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre', 'Enero');

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

  //Seleccionamos los barrios
  $sql  = "SELECT idbarrio, nombre
           FROM barrio
           ORDER BY nombre";
  $barrios = pg_query($link, $sql);
?>
<html>
<?php include_once ("header_diseños.php"); ?>
 <body>
   <table border="0" width="100%">
     <tr>
       <td colspan="2">
         <div class="encabezado">
           Reporte de Recaudaciones por Periodos Fiscales
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
           Fecha: <?php echo $fecha; ?>
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
           Hora: <?php echo $hora; ?>
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
       <th class="td_linea_arriba_abajo"><div class="subtitulo_centrado">Ubicaci&oacute;n Bienes Inmuebles</div></th>
       <th class="td_linea_arriba_abajo"><div class="subtitulo_centrado">Total</div></th>
       <th class="td_linea_arriba_abajo"><div class="subtitulo_centrado">Pagaron</div></th>
       <th class="td_linea_arriba_abajo"><div class="subtitulo_centrado">Evasores</div></th>
       <th class="td_linea_arriba_abajo"><div class="subtitulo_centrado">Porcentaje (%)</div></th>
       <th class="td_linea_arriba_abajo"><div class="subtitulo_centrado">Percibido</div></th>
     </tr>
     <?php
       $suma_total = 0;
       $suma_pagaron = 0;
       $suma_evasores = 0;
       $suma_porcentaje = 0;
       $suma_percibido = 0;
       while ($barrio = pg_fetch_array($barrios, NULL, PGSQL_BOTH)) {
         //Contamos el total de inmuebles en ese barrio
         $sql  = "SELECT count(id) as total
                  FROM inmueble
                  WHERE idbarrioin = '$barrio[idbarrio]'";
         $result = pg_query($link, $sql);
         $inmuebles_totales = pg_fetch_array($result, NULL, PGSQL_BOTH);
         $total = $inmuebles_totales['total'];

         //Seleccionamos los inmuebles de ese barrio que pagaron
         $sql  = "SELECT count(P.id_pagoinm) as pagaron, sum(L.total) as percibido
                  FROM pagoinm AS P
                  LEFT JOIN liquidacion AS L
                   ON P.id_liquidacion = L.idliqui
                  WHERE upper(L.idbarrioinm) = upper('$barrio[nombre]')
                   AND P.fecha LIKE '$gestion%'
                   AND P.activo = '1'";
        $result = pg_query($link, $sql);
        $inmuebles_pagaron = pg_fetch_array($result, NULL, PGSQL_BOTH);
        $pagaron = $inmuebles_pagaron['pagaron'];
        $percibido = $inmuebles_pagaron['percibido'];

        //Calculamos evasores y el porcentaje
        $evasores = $total - $pagaron;
        if ($total != 0) {
          $porcentaje = ($evasores / $total)*100;
        }
        else {
          $porcentaje = 0;
        }
       ?>
         <tr>
           <td><div class="valores_centrado"><?php echo $barrio['nombre']; ?></div></td>
           <td><div class="valores_centrado"><?php echo number_format($total, 0, '.', ','); ?></div></td>
           <td><div class="valores_centrado"><?php echo number_format($pagaron, 0, '.', ','); ?></div></td>
           <td><div class="valores_centrado"><?php echo number_format($evasores, 0, '.', ','); ?></div></td>
           <td><div class="valores_centrado"><?php echo number_format($porcentaje, 2, '.', ',')." %"; ?></div></td>
           <td><div class="valores_centrado"><?php echo number_format($percibido, 2, '.', ','); ?></div></td>
         </tr>
       <?php
        //Sumamos los valores
        $suma_total = $suma_total + $total;
        $suma_pagaron = $suma_pagaron + $pagaron;
        $suma_evasores = $suma_evasores + $evasores;
        $suma_percibido = $suma_percibido + $percibido;
      }
       ?>
     <tr>
       <td class="td_linea_arriba_abajo"><div class="valores_centrado">TOTAL GENERAL: </div></td>
       <td class="td_linea_arriba_abajo"><div class="valores_centrado"><?php echo number_format($suma_total, 0, '.', ','); ?></div></td>
       <td class="td_linea_arriba_abajo"><div class="valores_centrado"><?php echo number_format($suma_pagaron, 0, '.', ','); ?></div></td>
       <td class="td_linea_arriba_abajo"><div class="valores_centrado"><?php echo number_format($suma_evasores, 0, '.', ','); ?></div></td>
       <td class="td_linea_arriba_abajo"><div class="valores_centrado"><?php if ($suma_total != 0) {echo number_format(($suma_evasores / $suma_total)*100, 2, '.', ',')." %";}else {echo "0.00 %";} ?></div></td>
       <td class="td_linea_arriba_abajo"><div class="valores_centrado"><?php echo number_format($suma_percibido, 2, '.', ','); ?></div></td>
     </tr>
   </table>
 </body>
<?php
  //Borramos las variables de sesion
  unset($_SESSION['pago']);
  unset($_SESSION['gestion']);
?>
