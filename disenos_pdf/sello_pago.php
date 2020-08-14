<?php
/****************************************************************
  DADO QUE EL ESPACIO PARA EL SELLO VARIA EN FUNCIÓN AL ESPACIO UTILIZADO POR
  CADA LIQUIDACIÓN, IMPRIMIMOS LO MISMO QUE SE IMPRIMIÓ EN EL FORMULARIO 1980
  (formulario_1980.php) PERO CON EL TEXTO EN COLOR BLANCO.
******************************************************************/
?>

<?php
  //include("verificar_login.php");
  //include("inc.config.php");

  $link=Conectarse();

  //Tomamos las variables necesarias
  $id = $_SESSION['id_codigo_catastro'];
  $gestion = $_SESSION['gestion'];

  //Tomamos los datos del inmueble
  $sql  = "SELECT *
           FROM liquidacion
           WHERE id = '$id'
           AND gestion = '$gestion'
           AND activo = '1'";
  $result = pg_query($link, $sql);
  $liquidacion = pg_fetch_array($result, NULL, PGSQL_BOTH);


  //Caclulamos el total
  $total = $liquidacion['impuestoneto'] - $liquidacion['pagotermino'] + $liquidacion['formulario'];
  include 'conversor_numeral_literal.php';
  $total_literal = convertir($total);

  //Tomamos los datos para los bloques
  $sql  = "SELECT *
           FROM liquidacion_construccion
           WHERE idinm = '$id'
           AND gestion = '$gestion'
           AND idliqui = '$liquidacion[idliqui]'
           AND activo = 1";
  $bloques = pg_query($link, $sql);

  //Tomamos los datos del pago
  $sql  = "SELECT P.id_pagoinm, P.fechapago, P.montopagar, U.nombres, U.apellidos
           FROM pagoinm AS P
           LEFT JOIN usuario AS U
            ON P.id_usuario = U.id
           WHERE P.idinm = '$id'
            AND P.gestionpago = '$gestion'
            AND P.activo = 1";
  $result = pg_query($link, $sql);
  $pagoinm = pg_fetch_array($result, NULL, PGSQL_BOTH);

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
?>
<html>
<?php include_once ("header_diseños.php"); ?>
 <body>
   <?php
   //Repetimos 3 veces el mismo formato, para recortar
   for ($i=1; $i <= 1; $i++) {
   ?>
     <table width="90%" border="0">
       <tr>
         <td rowspan="2">
           <!--img class="foto" src="imagenes/logos_municipios/logo_id_municipio_1.jpg" width="130px" height="130px"alt="SIIM WEB"-->
         </td>
         <td width="60%">
           <div class="encabezado_texto_blanco">
             IMPUESTO A LA PROPIEDAD DE BIENES INMUEBLES</p>
             <p>Form. 1981 - IPBI</p>
           </div>
         </td>
         <td width="20%">
           <div class="encabezado_texto_blanco">
             <?php echo formato_nro_liquidacion($liquidacion['numliqui']); ?>
           </div>
         </td>
       </tr>

       <tr>
         <td>
           <div class="titulo_texto_blanco">
             DATOS DEL CONTRIBUYENTE
           </div>
           <table class="tabla_con_borde_texto_blanco" width=100%>
             <tr>
               <td>
                 <div class="subtitulo_texto_blanco">
                   PMC:
                 </div>
               </td>
               <td>
                 <div class="valores_texto_blanco">
                   <?php echo $liquidacion['pmc'];?>
                 </div>
               </td>

               <td>
                 <div class="subtitulo_texto_blanco">
                   <?php echo $liquidacion['tipo_documento'].":";?>
                 </div>
               </td>
               <td>
                 <div class="valores_texto_blanco">
                   <?php echo $liquidacion['ci'];?>
                 </div>
               </td>

               <td>
                 <div class="subtitulo_texto_blanco">
                   Tipo Persona:
                 </div>
               </td>
               <td>
                 <div class="valores_texto_blanco">
                   <?php echo $liquidacion['tipopersona'];?>
                 </div>
               </td>
             </tr>

             <tr>
               <td>
                 <div class="subtitulo_texto_blanco">
                   NOMBRE:
                 </div>
               </td>
               <td colspan="5">
                 <div class="valores_texto_blanco">
                   <?php echo $liquidacion['nombre'];?>
                 </div>
               </td>
             </tr>

             <tr>
               <td>
                 <div class="subtitulo_texto_blanco">
                   DIRECCI&Oacute;N:
                 </div>
               </td>
               <td colspan="5">
                 <div class="valores_texto_blanco">
                   <?php echo "ZONA: ".$liquidacion['barrioprop']." V&Iacute;A: ".$liquidacion['direccionprop']." Nro: ".$liquidacion['numeroprop'];?>
                 </div>
               </td>
             </tr>

             <tr>
               <td>
                 <div class="subtitulo_texto_blanco">
                   TEL&Eacute;FONO:
                 </div>
               </td>
               <td colspan="5">
                 <div class="valores_texto_blanco">
                   <?php echo $liquidacion['telefono'];?>
                 </div>
               </td>
             </tr>
           </table>
         </td>

         <td>
           <div class="titulo_texto_blanco">
             Usuario: <?php echo $_SESSION['nombres_ss']." ".$_SESSION['apellidos_ss']; ?>
           </div>
           <table class="tabla_con_borde_texto_blanco" width="50%">
             <tr>
               <td>
                 <div class="subtitulo_texto_blanco">
                   GESTI&Oacute;N:
                 </div>
               </td>
               <td>
                 <div class="valores_texto_blanco">
                   <?php echo $liquidacion['gestion'];?>
                 </div>
               </td>
             </tr>

             <tr>
               <td>
                 <div class="subtitulo_texto_blanco">
                   EMITIDO:
                 </div>
               </td>
               <td>
                 <div class="valores_texto_blanco">
                    <?php echo $liquidacion['fechliqui'];?>
                 </div>
               </td>
             </tr>

             <tr>
               <td>
                 <div class="subtitulo_texto_blanco">
                   VENCE:
                 </div>
               </td>
               <td>
                 <div class="valores_texto_blanco">
                    <?php echo $liquidacion['fechvenci'];?>
                 </div>
               </td>
             </tr>
           </table>
         </td>
       </tr>

       <tr>
         <td colspan="3">
           <div class="titulo_texto_blanco">
             DATOS DEL INMUEBLE
           </div>
           <table class="tabla_con_borde_texto_blanco">
             <tr>
                <td colspan="2">
                 <div class="valores_texto_blanco">
                   &nbsp;&nbsp;&nbsp;&nbsp; C&Oacute;DIGO CATASTRAL:  <?php echo $liquidacion['codigo'];?>
                 </div>
               </td>

               <td>
                 <div class="subtitulo_texto_blanco">
                   TIPO:
                 </div>
               </td>
               <td colspan="5">
                 <div class="valores_texto_blanco">
                    <?php echo $liquidacion['tipoinm'];?>
                 </div>
               </td>
             </tr>

             <tr>
               <td>
                 <div class="subtitulo_texto_blanco">
                   DIRECCI&Oacute;N:
                 </div>
               </td>
               <td>
                 <div class="valores_texto_blanco">
                   <?php echo "ZONA: ".$liquidacion['idbarrioinm']." V&Iacute;A: ".$liquidacion['nombreviainm']." Nro: ".$liquidacion['numeroinm'];?>
                 </div>
               </td>

               <td>
                 <div class="subtitulo_texto_blanco">
                   EDIFICIO:
                 </div>
               </td>
               <td>
                 <div class="valores_texto_blanco">
                   ---
                 </div>
               </td>

               <td>
                 <div class="subtitulo_texto_blanco">
                   PISO:
                 </div>
               </td>
               <td>
                 <div class="valores_texto_blanco">
                   ---
                 </div>
               </td>

               <td>
                 <div class="subtitulo_texto_blanco">
                   DEPTO:
                 </div>
               </td>
               <td>
                 <div class="valores_texto_blanco">
                   ---
                 </div>
               </td>
             </tr>
           </table>
         </td>
       </tr>
       <tr>
         <td colspan="3">
           <div class="titulo_texto_blanco">
             VALUACI&Oacute;N DEL TERRENO
           </div>
           <table class="tabla_con_borde_y_divisiones_texto_blanco" width="100%">
             <tr>
               <td class="td_linea_punteada_abajo_derecha_texto_blanco">
                 <div class="subtitulo_centrado_texto_blanco">
                   SUPERFICIE <br>(M2)
                 </div>
               </td>
               <td class="td_linea_punteada_abajo_derecha_texto_blanco">
                 <div class="subtitulo_centrado_texto_blanco">
                   VALOR <br>BS/M2
                 </div>
               </td>
               <td class="td_linea_punteada_abajo_derecha_texto_blanco">
                 <div class="subtitulo_centrado_texto_blanco">
                   K1 (TIPO <br>DE V&Iacute;A)
                 </div>
               </td>
               <td class="td_linea_punteada_abajo_derecha_texto_blanco">
                 <div class="subtitulo_centrado_texto_blanco">
                   K2 <br>(TOPOGRAF&Iacute;A)
                 </div>
               </td>
               <td class="td_linea_punteada_abajo_derecha_texto_blanco">
                 <div class="subtitulo_centrado_texto_blanco">
                   K3 <br>(FORMA)
                 </div>
               </td>
               <td class="td_linea_punteada_abajo_derecha_texto_blanco">
                 <div class="subtitulo_centrado_texto_blanco">
                   K4 <br>(UBICACI&Oacute;N)
                 </div>
               </td>
               <td class="td_linea_punteada_abajo_derecha_texto_blanco">
                 <div class="subtitulo_centrado_texto_blanco">
                   K5 <br>(SERVICIOS)
                 </div>
               </td>
               <td class="td_linea_punteada_abajo_derecha_texto_blanco">
                 <div class="subtitulo_centrado_texto_blanco">
                   K6 <br>(FREN./FON.)
                 </div>
               </td>
               <td class="td_linea_punteada_abajo_texto_blanco">
                 <div class="subtitulo_centrado_texto_blanco">
                   VALOR DEL <br>TERRENO (Bs.)
                 </div>
               </td>
             </tr>

             <tr>
               <td class="td_linea_punteada_derecha_texto_blanco">
                 <div class="valores_centrado_texto_blanco">
                    <?php echo number_format($liquidacion['superficie'], 2, '.', ',');?>
                 </div>
               </td>
               <td class="td_linea_punteada_derecha_texto_blanco">
                 <div class="valores_centrado_texto_blanco">
                   <?php echo number_format($liquidacion['valunitario'], 2, '.', ',');?>
                 </div>
               </td>
               <td class="td_linea_punteada_derecha_texto_blanco">
                 <div class="valores_centrado_texto_blanco">
                   <?php echo number_format($liquidacion['k1_val'], 2, '.', ',');?>
                 </div>
               </td>
               <td class="td_linea_punteada_derecha_texto_blanco">
                 <div class="valores_centrado_texto_blanco">
                   <?php echo number_format($liquidacion['k2_val'], 2, '.', ',');?>
                 </div>
               </td>
               <td class="td_linea_punteada_derecha_texto_blanco">
                 <div class="valores_centrado_texto_blanco">
                   <?php echo number_format($liquidacion['k3_val'], 2, '.', ',');?>
                 </div>
               </td>
               <td class="td_linea_punteada_derecha_texto_blanco">
                 <div class="valores_centrado_texto_blanco">
                   <?php echo number_format($liquidacion['k4_val'], 2, '.', ',');?>
                 </div>
               </td>
               <td class="td_linea_punteada_derecha_texto_blanco">
                 <div class="valores_centrado_texto_blanco">
                   <?php echo number_format($liquidacion['k5_val'], 2, '.', ',');?>
                 </div>
               </td>
               <td class="td_linea_punteada_derecha_texto_blanco">
                 <div class="valores_centrado_texto_blanco">
                   <?php echo number_format($liquidacion['k6_val'], 2, '.', ',');?>
                 </div>
               </td>
               <td>
                 <div class="valores_centrado_texto_blanco">
                   <?php echo number_format($liquidacion['valterr'], 2, '.', ',');?>
                 </div>
               </td>
             </tr>
           </table>
         </td>
       </tr>
     </table>
     <table width="100%" border="0">
       <tr>
         <td>
           <div class="titulo_texto_blanco">
             VALUACI&Oacute;N DE LA CONSTRUCCI&Oacute;N
           </div>
           <table class="tabla_con_borde_y_divisiones_texto_blanco" width="100%" >
             <tr>
               <td class="td_linea_punteada_abajo_derecha_texto_blanco">
                 <div class="subtitulo_centrado_texto_blanco">
                   SUPERFICIE
                 </div>
               </td>
               <td class="td_linea_punteada_abajo_derecha_texto_blanco">
                 <div class="subtitulo_centrado_texto_blanco">
                   VALOR <br>BS/M2
                 </div>
               </td>
               <td class="td_linea_punteada_abajo_derecha_texto_blanco">
                 <div class="subtitulo_centrado_texto_blanco">
                   KK1 <br>DEPRESIA.
                 </div>
               </td>
               <td class="td_linea_punteada_abajo_derecha_texto_blanco">
                 <div class="subtitulo_centrado_texto_blanco">
                   KK2 <br>CONSERVA
                 </div>
               </td>
               <td class="td_linea_punteada_abajo_derecha_texto_blanco">
                 <div class="subtitulo_centrado_texto_blanco">
                   KK3 <br>DESTINO / USO
                 </div>
               </td>
               <td class="td_linea_punteada_abajo_texto_blanco">
                 <div class="subtitulo_centrado_texto_blanco">
                   VALOR DE <br>CONSTRUCCI&Oacute;N
                 </div>
               </td>
             </tr>

           <?php
           $suma_valor_construccion = 0;
           while ($bloque = pg_fetch_array($bloques, NULL, PGSQL_BOTH)) {
           ?>
             <tr>
               <td class="td_linea_punteada_arriba_derecha_texto_blanco">
                 <div class="valores_centrado_texto_blanco">
                   <?php echo number_format($bloque['superficie'], 2, '.', ',');?>
                 </div>
               </td>
                 <td class="td_linea_punteada_arriba_derecha_texto_blanco">
                 <div class="valores_centrado_texto_blanco">
                   <?php echo number_format($bloque['valunitario'], 2, '.', ',');?>
                 </div>
               </td>
                 <td class="td_linea_punteada_arriba_derecha_texto_blanco">
                 <div class="valores_centrado_texto_blanco">
                   <?php echo number_format($bloque['kk1_val'], 2, '.', ',');?>
                 </div>
               </td>
                 <td class="td_linea_punteada_arriba_derecha_texto_blanco">
                 <div class="valores_centrado_texto_blanco">
                   <?php echo number_format($bloque['kk2_val'], 2, '.', ',');?>
                 </div>
               </td>
                 <td class="td_linea_punteada_arriba_derecha_texto_blanco">
                 <div class="valores_centrado_texto_blanco">
                   <?php echo number_format($bloque['kk3'], 2, '.', ',');?>
                 </div>
               </td>
               <td class="td_linea_punteada_arriba_texto_blanco">
                 <div class="valores_centrado_texto_blanco">
                   <?php echo number_format($bloque['valedif'], 2, '.', ',');?>
                 </div>
               </td>
             </tr>
           <?php
           $suma_valor_construccion = $suma_valor_construccion + $bloque['valedif'];
           }
           ?>
           </table>
         </td>

         <td rowspan="4" width="45%">
           <div class="encabezado">
             <?php
             //CODIGO QR
                 //set it to writable location, a place for temp generated PNG files
                 $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'codigos_qr'.DIRECTORY_SEPARATOR;
                 //html PNG location prefix
                 $PNG_WEB_DIR = 'disenos_pdf/codigos_qr/';
                 include "phpqrcode.php";

                 $texto_qr = 'Nro liquidacion: '.formato_nro_liquidacion($liquidacion['numliqui']).', id: '.$id.', gestion: '.$gestion.', monto a pagar: '.$pagoinm['montopagar'].', fecha: '.$pagoinm['fechapago'];

                 $_REQUEST['data'] = $texto_qr;
                 $_REQUEST['size'] = 2;
                 $_REQUEST['level'] = "M";

                 //ofcourse we need rights to create temp dir
                 if (!file_exists($PNG_TEMP_DIR))
                     mkdir($PNG_TEMP_DIR);
                 $filename = $PNG_TEMP_DIR.'qr_pago_.png';
                 //processing form input
                 //remember to sanitize user input in real-life solution !!!
                 $errorCorrectionLevel = 'L';
                 if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L','M','Q','H')))
                     $errorCorrectionLevel = $_REQUEST['level'];

                 $matrixPointSize = 4;
                 if (isset($_REQUEST['size']))
                     $matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);


                 if (isset($_REQUEST['data'])) {
                     //it's very important!
                     if (trim($_REQUEST['data']) == '')
                         die('data cannot be empty! <a href="?">back</a>');

                     // user data
                     $filename = $PNG_TEMP_DIR.'qr_pago_'.md5($_REQUEST['data'].'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
                     QRcode::png($_REQUEST['data'], $filename, $errorCorrectionLevel, $matrixPointSize, 2);
                 } else {
                     //default data
                     echo 'You can provide data in GET parameter: <a href="?data=like_that">like that</a><hr/>
                     <div align="right">';
                     QRcode::png('PHP QR Code :)', $filename, $errorCorrectionLevel, $matrixPointSize, 2);
                 }
                 //display generated file
                 $PNG_WEB_DIR.basename($filename);
                 echo '<img src="'.$PNG_WEB_DIR.basename($filename).'" />';
             ?>
             <table class="tabla_con_borde" width=100%>
                 <tr>
                   <td>
                     <div class="subtitulo_centrado"> SIIM - GOBIERNO MUNICIPAL </div>
                   </td>
                 </tr>
                 <tr>
                   <td>
                     <div class="subtitulo_centrado"> <?php echo $parametro['departamento']." - ".$parametro['municipio']; ?> </div>
                   </td>
                 </tr>
                 <tr>
                   <td>
                     <div class="subtitulo_centrado"> Usuario: <?php echo $pagoinm['nombres']." <br>".$pagoinm['apellidos']; ?></div>
                   </td>
                 </tr>
                 <tr>
                   <td>
                     <div class="subtitulo_centrado"> Monto: Bs. <?php echo number_format($pagoinm['montopagar'], 2, '.', ','); ?></div>
                   </td>
                 </tr>
                 <tr>
                   <td>
                     <div class="subtitulo_centrado"> <?php echo "Fecha: ".substr($pagoinm['fechapago'], 0, 10)." - Hora: ".substr($pagoinm['fechapago'], 11, 8); ?></div>
                   </td>
                 </tr>
             </table>
           </div>
         </td>
       </tr>

       <tr>
         <td>
           <div class="titulo_texto_blanco">
             VALUACI&Oacute;N DEL INMUEBLE
           </div>
           <table class="tabla_con_borde_y_divisiones_texto_blanco" width="100%">
             <tr>
               <td class="td_linea_punteada_abajo_derecha_texto_blanco">
                 <div class="subtitulo_centrado_texto_blanco">
                   VAL. DEL <br>TERRENO
                 </div>
               </td>
               <td class="td_linea_punteada_abajo_derecha_texto_blanco">
                 <div class="subtitulo_centrado_texto_blanco">
                   VAL. DE LA<br>CONSTRUCCI&Oacute;N
                 </div>
               </td>
               <td class="td_linea_punteada_abajo_derecha_texto_blanco">
                 <div class="subtitulo_centrado_texto_blanco">
                   VAL. <br>LIBROS
                 </div>
               </td>
               <td class="td_linea_punteada_abajo_derecha_texto_blanco">
                 <div class="subtitulo_centrado_texto_blanco">
                   EXCENCI&Oacute;N
                 </div>
               </td>
               <td class="td_linea_punteada_abajo_derecha_texto_blanco">
                 <div class="subtitulo_centrado_texto_blanco">
                   UFV
                 </div>
               </td>
               <td class="td_linea_punteada_abajo_texto_blanco">
                 <div class="subtitulo_centrado_texto_blanco">
                   BASE <br>IMPONIBLE
                 </div>
               </td>
             </tr>

             <tr>
               <td class="td_linea_punteada_derecha_texto_blanco">
                 <div class="valores_centrado_texto_blanco">
                   <?php echo number_format($liquidacion['valterr'], 2, '.', ',');?>
                 </div>
               </td>
               <td class="td_linea_punteada_derecha_texto_blanco">
                 <div class="valores_centrado_texto_blanco">
                   <?php echo number_format($suma_valor_construccion, 2, '.', ',');?>
                 </div>
               </td>
               <td class="td_linea_punteada_derecha_texto_blanco">
                 <div class="valores_centrado_texto_blanco">
                   0
                 </div>
               </td>
               <td class="td_linea_punteada_derecha_texto_blanco">
                 <div class="valores_centrado_texto_blanco">
                  <?php echo number_format($liquidacion['exencion'], 2, '.', ',');?>
                 </div>
               </td>
               <td class="td_linea_punteada_derecha_texto_blanco">
                 <div class="valores_centrado_texto_blanco">
                   <?php echo $liquidacion['ufv'];?>
                 </div>
               </td>
               <td>
                 <div class="valores_centrado_texto_blanco">
                   <?php echo number_format($liquidacion['valterr']+$suma_valor_construccion, 2, '.', ',');?>
                 </div>
               </td>
             </tr>
           </table>
         </td>
       </tr>

        <tr>
          <td>
            <div class="titulo_texto_blanco">
              C&Aacute;LCULO DE ACCESORIOS
            </div>
            <table class="tabla_con_borde_y_divisiones_texto_blanco">
              <tr>
                <td>
                  <div class="subtitulo_texto_blanco">
                    MONTO DETERMINADO:
                  </div>
                </td>
                <td>
                  <div class="valores">
                    <?php echo number_format($liquidacion['impuestoneto'], 2, '.', ',');?>
                  </div>
                </td>

                <td>
                  <div class="subtitulo_texto_blanco">
                    MULTA INCUMPLIMIENTO:
                  </div>
                </td>
                <td>
                  <div class="valores_texto_blanco">
                    <?php echo number_format($liquidacion['multamora'], 2, '.', ',');?>
                  </div>
                </td>
              </tr>

              <tr>
                <td>
                  <div class="subtitulo_texto_blanco">
                    DESCUENTO PAGO T&Eacute;RMINO:
                  </div>
                </td>
                <td>
                  <div class="valores_texto_blanco">
                    <?php echo number_format($liquidacion['pagotermino'], 2, '.', ',');?>
                  </div>
                </td>

                <td>
                  <div class="subtitulo_texto_blanco">
                    REPOSICI&Oacute;N FORMULARIO:
                  </div>
                </td>
                <td>
                  <div class="valores_texto_blanco">
                    <?php echo number_format($liquidacion['formulario'], 2, '.', ',');?>
                  </div>
                </td>
              </tr>

              <tr>
                <td>
                  <div class="subtitulo_texto_blanco">
                    PAGOS ANTERIORES:
                  </div>
                </td>
                <td>
                  <div class="valores_texto_blanco">
                    <?php echo number_format($liquidacion['pagoanterior'], 2, '.', ',');?>
                  </div>
                </td>

                <td>
                  <div class="subtitulo_texto_blanco">
                    OTROS CR&Eacute;DITOS:
                  </div>
                </td>
                <td>
                  <div class="valores_texto_blanco">
                    <?php echo number_format($liquidacion['totalcredito'], 2, '.', ',');?>
                  </div>
                </td>
              </tr>

              <tr>
                <td>
                  <div class="subtitulo_texto_blanco">
                    MANTENIMIENTO DE VALOR:
                  </div>
                </td>
                <td>
                  <div class="valores_texto_blanco">
                    <?php echo number_format($liquidacion['mantevalor'], 2, '.', ',');?>
                  </div>
                </td>

                <td>
                  <div class="subtitulo_texto_blanco">
                    SALDO A FAVOR
                  </div>
                </td>
                <td>
                  <div class="valores_texto_blanco">
                    <?php echo number_format($liquidacion['saldofavor'], 2, '.', ',');?>
                  </div>
                </td>
              </tr>

              <tr>
                <td>
                  <div class="subtitulo_texto_blanco">
                    INTER&Eacute;S:
                  </div>
                </td>
                <td>
                  <div class="valores_texto_blanco">
                    <?php echo number_format($liquidacion['interes'], 2, '.', ',');?>
                  </div>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <tr>
          <td>
            <div class="titulo_texto_blanco">
              TOTAL: <?php echo number_format($total, 2, '.', ',');?> SON: <?php echo $total_literal;?>
              <br>
              INCLUYE <?php echo number_format($liquidacion['formulario'], 2, '.', ',');?> Bs. POR REPOSICI&Oacute;N DE FORMULARIO
            </div>
          </td>
        </tr>
     </table>
     <!--i class="fa fa-scissors"></i>-----------------------------------------------------------------------------------------------------------------------------------------<-->
   <?php
   }
   ?>
  </body>
</html>

<?php
  //Borramos las variables de sesion
  $_SESSION['id_codigo_catastro']="";
  $_SESSION['gestion']="";
  $_SESSION['liquidacion']="";
  $_SESSION['forma_pago']="";
?>
