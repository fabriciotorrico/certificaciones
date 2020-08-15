<?php
  $link=Conectarse();

  //Tomamos las variables necesarias
  $id_usuario = $_SESSION['id_usuario'];
  $fecha = $_SESSION['fecha'];

  //Seleccionamos los parametros configurados
  $sql  = "SELECT D.departamento, M.municipio, M.codigo_gobierno_municipal
           FROM parametros_siim AS P
           LEFT JOIN municipio AS M
            ON P.id_municipio = M.id_municipio
           LEFT JOIN departamento AS D
            ON M.id_departamento = D.id_departamento
           WHERE P.activo = '1'";
  $result = pg_query($link, $sql);
  $parametro = pg_fetch_array($result, NULL, PGSQL_BOTH);

  //Seleccionamos los datos del usuario
  $sql  = "SELECT nombres, apellidos, username
           FROM usuario
           WHERE id = '$id_usuario'";
  $usuarios = pg_query($link, $sql);
  $usuario = pg_fetch_array($usuarios, NULL, PGSQL_BOTH);

  //Creamos la funcion para dar formato con 0s al nro de liquidacion
  function formato_nro_liquidacion($liquidacion){
    $digitos_liquidacion = strlen($liquidacion);
    switch ($digitos_liquidacion) {
      case '1':
        $nro_liquidacion = "0000000".$liquidacion;
        break;

      case '2':
        $nro_liquidacion = "000000".$liquidacion;
        break;

      case '3':
        $nro_liquidacion = "00000".$liquidacion;
        break;

      case '4':
        $nro_liquidacion = "0000".$liquidacion;
        break;

      case '5':
        $nro_liquidacion = "000".$liquidacion;
        break;

      case '6':
        $nro_liquidacion = "00".$liquidacion;
        break;

      case '7':
        $nro_liquidacion = "0".$liquidacion;
        break;

      default:
        $nro_liquidacion = $liquidacion;
        break;
    }
    return $nro_liquidacion;
  }

/*  $meses = array('Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre', 'Enero');

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
  $barrios = pg_query($link, $sql);*/
?>
<html>
<?php include_once ("header_diseños.php"); ?>
 <body>
   <table border="0" width="100%">

     <tr>
       <td>
         <div class="encabezado">
          Inmuebles
         </div>
       </td>
       <td colspan="2">
         <div class="encabezado">
          Gobierno Aut&oacute;nomo Municipal de <?php echo $parametro['municipio']." (".$parametro['departamento'].")"; ?>
         </div>
       </td>
       <td>
         <div class="encabezado">
           Resumen General
         </div>
       </td>
     </tr>

     <tr>
       <td>
         <table class="tabla_con_borde" width="100%">
           <tr>
             <td>
               <div class="subtitulo_centrado">
                 <b>Lugar de Recaudaci&oacute;n</b>
               </div>
             </td>
           </tr>
           <tr>
             <td>
               <div class="subtitulo_centrado">
                 Gobierno Municipal
               </div>
             </td>
           </tr>
         </table>
       </td>

       <td colspan="2">
         <table class="tabla_con_borde" width="100%">
           <tr>
             <td>
               <div class="subtitulo_centrado">
                 <b>C&oacute;digo Gobierno Municipal</b>
               </div>
             </td>
           </tr>
           <tr>
             <td>
               <div class="subtitulo_centrado">
                 <?php echo $parametro['codigo_gobierno_municipal']; ?>
               </div>
             </td>
           </tr>
         </table>
       </td>

       <td>
         <table class="tabla_con_borde" width="100%">
           <tr>
             <td>
               <div class="subtitulo_centrado">
                 <b>No Detalles</b>
               </div>
             </td>
           </tr>
           <tr>
             <td>
               <div class="subtitulo_centrado">

               </div>
             </td>
           </tr>
         </table>
       </td>
     </tr>

     <tr>
       <td>
         <table class="tabla_con_borde" width="100%">
           <tr>
             <td>
               <div class="subtitulo_centrado">
                 <b>Recauador</b>
               </div>
             </td>
           </tr>
           <tr>
             <td>
               <div class="subtitulo_centrado">
                 <?php echo "Caja: ".$usuario['nombres']." ".$usuario['apellidos']; ?>
               </div>
             </td>
           </tr>
         </table>
       </td>

       <td>
         <table class="tabla_con_borde" width="100%">
           <tr>
             <td>
               <div class="subtitulo_centrado">
                 <b>Identificador</b>
               </div>
             </td>
           </tr>
           <tr>
             <td>
               <div class="subtitulo_centrado">
                 <?php echo $usuario['username']; ?>
               </div>
             </td>
           </tr>
         </table>
       </td>

       <td>
         <table class="tabla_con_borde" width="100%">
           <tr>
             <td>
               <div class="subtitulo_centrado">
                 <b>Sucursal</b>
               </div>
             </td>
           </tr>
           <tr>
             <td>
               <div class="subtitulo_centrado">

               </div>
             </td>
           </tr>
         </table>
       </td>

       <td>
         <table class="tabla_con_borde" width="100%">
           <tr>
             <td>
               <div class="subtitulo_centrado">
                 <b>Fecha</b>
               </div>
             </td>
           </tr>
           <tr>
             <td>
               <div class="subtitulo_centrado">
                 <?php echo $fecha; ?>
               </div>
             </td>
           </tr>
         </table>
       </td>
     </tr>
   </table>

   <br>
   <table width="100%" style="border-collapse: collapse">
     <tr>
       <th class="td_linea_arriba_abajo"><div class="subtitulo_centrado">Nro. de Comprobante (Fecha de pago: <?php echo $fecha; ?>)</div></th>
       <th class="td_linea_arriba_abajo"><div class="subtitulo_centrado">Monto</div></th>
     </tr>
     <?php
       $sql  = "SELECT P.montopagar, P.fecha, L.numliqui, L.formulario, L.parcial
                FROM pagoinm AS P
                LEFT JOIN liquidacion AS L
                  ON P.id_liquidacion = L.idliqui
                WHERE P.id_usuario = '$id_usuario'
                  AND P.fecha = '$fecha'
                  AND P.activo = 1";
       $result = pg_query($link, $sql);
       while($registro = pg_fetch_array($result, NULL, PGSQL_BOTH)) {
        ?>
        <tr>
          <td><div class="valores_centrado"><?php echo formato_nro_liquidacion($registro['numliqui']); ?></div></td>
          <td><div class="valores_centrado"><?php echo number_format($registro['montopagar'], 2, '.', ','); ?></div></td>
        </tr>
        <?php
       }

       //Calculamos el total
       $sql  = "SELECT sum(P.montopagar) as suma_total
                FROM pagoinm AS P
                LEFT JOIN liquidacion AS L
                  ON P.id_liquidacion = L.idliqui
                WHERE P.id_usuario = '$id_usuario'
                  AND P.fecha = '$fecha'";
       $resultado = pg_query($link, $sql);
       $total = pg_fetch_array($resultado, NULL, PGSQL_BOTH)
     ?>
     <tr>
       <td class="td_linea_arriba_abajo"><div class="subtitulo_centrado"><b>TOTAL</b></div></td>
       <td class="td_linea_arriba_abajo"><div class="subtitulo_centrado"><b><?php echo number_format($total['suma_total'], 2, '.', ','); ?></b></div></td>
     </tr>
   </table>

   <?php /*Comentamos por que de momento no debe mostrarse esta parte
   <br><br><br><br><br><br>
   <table width="100%" style="border-collapse: collapse">
     <tr>
       <th class="td_linea_arriba_abajo"><div class="subtitulo_centrado">Rubro</div></th>
       <th class="td_linea_arriba_abajo" colspan="4"><div class="subtitulo_centrado">Descripci&oacute;n</div></th>
       <th class="td_linea_arriba_abajo"><div class="subtitulo_centrado">Monto</div></th>
     </tr>
     <?php
       $suma_total = 0;
       $suma_pagaron = 0;
       $suma_evasores = 0;
       $suma_porcentaje = 0;
       $suma_percibido = 0;
         //Contamos el total de inmuebles en ese barrio
         /*$sql  = "SELECT count(id) as total
                  FROM inmueble
                  WHERE idbarrioin = '$barrio[idbarrio]'";
         $result = pg_query($link, $sql);
         $inmuebles_totales = pg_fetch_array($result, NULL, PGSQL_BOTH);
         $total = $inmuebles_totales['total'];

       ?>
     <tr>
       <td><div class="valores_centrado">12000</div></td>
       <td colspan="4"><div class="valores">VENTA DE BIENES Y SERVICIOS A LAS ADMINISTRACIONES</div></td>
       <td><div class="valores_centrado"><?php echo "A"; ?></div></td>
     </tr>

     <tr>
       <td><div class="valores_centrado">12200</div></td>
       <td><div class="valores"> &nbsp; &nbsp; Venta de Servicios a las Administraciones P&uacute;blicas</div></td>
       <td><div class="valores_centrado"><?php echo ""; ?></div></td>
       <td><div class="valores">Terreno</div></td>
       <td><div class="valores_centrado"><?php echo ""; ?></div></td>
     </tr>

     <tr>
       <td><div class="valores_centrado">13000</div></td>
       <td><div class="valores">INGRESOS TRIBUTARIOS</div></td>
       <td><div class="valores_centrado"><?php echo ""; ?></div></td>
       <td><div class="valores">Casa</div></td>
       <td><div class="valores_centrado"><?php echo ""; ?></div></td>
     </tr>

     <tr>
       <td><div class="valores_centrado">13300</div></td>
       <td colspan="4"><div class="valores"> &nbsp; &nbsp; Impuestos Directos Municipales</div></td>
       <td><div class="valores_centrado"><?php echo ""; ?></div></td>
     </tr>

     <tr>
       <td><div class="valores_centrado">13310</div></td>
       <td colspan="4"><div class="valores"> &nbsp; &nbsp;  &nbsp; &nbsp; Impuestos a la Propiedad de Bienes Inmuebles</div></td>
       <td><div class="valores_centrado"><?php echo ""; ?></div></td>
     </tr>
   </table>

   <br>
   <table width="100%" style="border-collapse: collapse">
     <tr>
       <th class="td_linea_arriba_abajo"><div class="subtitulo_centrado">Rubro</div></th>
       <th class="td_linea_arriba_abajo"><div class="subtitulo_centrado">IPBI</div></th>
       <th class="td_linea_arriba_abajo"><div class="subtitulo_centrado">RECTIF <br>IPB </div></th>
       <th class="td_linea_arriba_abajo"><div class="subtitulo_centrado">TRANSF <br>IPB </div></th>
       <th class="td_linea_arriba_abajo"><div class="subtitulo_centrado">ACTIV <br>IPB </div></th>
       <th class="td_linea_arriba_abajo"><div class="subtitulo_centrado">RECTIF ACTIV <br>ECO </div></th>
       <th class="td_linea_arriba_abajo"><div class="subtitulo_centrado">OTROS <br>INGRESOS </div></th>
       <th class="td_linea_arriba_abajo"><div class="subtitulo_centrado">TOTAL </div></th>
     </tr>

     <?php
       //Tomamos el valor de 12200 que es el costo del formulario
       $sql  = "SELECT precio_formulario as valor_12200
                FROM parametros_siim
                WHERE activo = 1";
       $result = pg_query($link, $sql);
       $resultado = pg_fetch_array($result, NULL, PGSQL_BOTH);
       $valor_12200 = $resultado['valor_12200'];
     ?>

     <tr>
       <td><div class="valores_centrado">12200</div></td>
       <td><div class="valores_centrado"><?php echo number_format($valor_12200, 0, '.', ','); ?></div></td>
       <td><div class="valores_centrado">0.00</div></td>
       <td><div class="valores_centrado">0.00</div></td>
       <td><div class="valores_centrado">0.00</div></td>
       <td><div class="valores_centrado">0.00</div></td>
       <td><div class="valores_centrado">0.00</div></td>
       <td><div class="valores_centrado"><?php echo number_format($valor_12200, 0, '.', ','); ?></div></td>
     </tr>

     <tr>
       <td><div class="valores_centrado">13310</div></td>
       <td><div class="valores_centrado"><?php echo number_format($valor_12200, 0, '.', ','); ?></div></td>
       <td><div class="valores_centrado">0.00</div></td>
       <td><div class="valores_centrado">0.00</div></td>
       <td><div class="valores_centrado">0.00</div></td>
       <td><div class="valores_centrado">0.00</div></td>
       <td><div class="valores_centrado">0.00</div></td>
       <td><div class="valores_centrado"><?php echo number_format($valor_12200, 0, '.', ','); ?></div></td>
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
       <td class="td_linea_arriba_abajo"><div class="valores_centrado">TOTAL GENERAL: </div></td>
       <td class="td_linea_arriba_abajo"><div class="valores_centrado"><?php echo number_format(10, 0, '.', ','); ?></div></td>
       <td class="td_linea_arriba_abajo"><div class="valores_centrado">0.00</div></td>
       <td class="td_linea_arriba_abajo"><div class="valores_centrado">0.00</div></td>
       <td class="td_linea_arriba_abajo"><div class="valores_centrado">0.00</div></td>
       <td class="td_linea_arriba_abajo"><div class="valores_centrado">0.00</div></td>
       <td class="td_linea_arriba_abajo"><div class="valores_centrado">0.00</div></td>
       <td class="td_linea_arriba_abajo"><div class="valores_centrado"><?php echo number_format(10, 0, '.', ','); ?></div></td>
     </tr>
   </table>
   */ ?>
 </body>
<?php
  //Borramos las variables de sesion
  /*unset($_SESSION['id_usuario']);
  unset($_SESSION['fecha']);*/
?>
