<?php
  //include("verificar_login.php");
  //include("inc.config.php");

  $link=Conectarse();

  //Tomamos las variables necesarias
  $id = $_SESSION['id_codigo_catastro'];
  $gestion = $_SESSION['gestion'];
  //$liquidacion = $_SESSION['liquidacion'];
  //$forma_pago = $_SESSION['forma_pago'];

  //Tomamos los datos del inmueble
  $sql  = "SELECT *
           FROM liquidacion
           WHERE id = '$id'
           AND gestion = '$gestion'
           AND activo = '1'";
  $result = pg_query($link, $sql);
  $liquidacion = pg_fetch_array($result, NULL, PGSQL_BOTH);

  //Tomamos los datos para los bloques
  $sql  = "SELECT *
           FROM liquidacion_construccion
           WHERE idinm = '$id'
           AND gestion = '$gestion'
           AND idliqui = '$liquidacion[idliqui]'
           AND activo = 1";
  $bloques = pg_query($link, $sql);

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
<?php include 'conversor_numeral_literal.php';?>
 <body>
   <?php
   //Repetimos n veces el mismo formato, para recortar
   for ($i=1; $i <= 1; $i++) {
   ?>
     <table width="90%" border="0">
       <tr>
         <td rowspan="2">
           <img class="foto" src="imagenes/logos_municipios/logo_id_municipio_1.jpg" width="130px" height="130px"alt="SIIM WEB">
         </td>
         <td width="60%">
           <div class="encabezado">
             IMPUESTO A LA PROPIEDAD DE BIENES INMUEBLES</p>
             <p>Form. 1981 - IPBI</p>
           </div>
         </td>
         <td width="20%">
           <div class="encabezado" style="color: #ff6666">
             <?php echo formato_nro_liquidacion($liquidacion['numliqui']); ?>
           </div>
         </td>
       </tr>

       <tr>
         <td>
           <div class="titulo">
             DATOS DEL CONTRIBUYENTE
           </div>
           <table class="tabla_con_borde" width=100%>
             <tr>
               <td>
                 <div class="subtitulo">
                   PMC:
                 </div>
               </td>
               <td>
                 <div class="valores">
                   <?php echo $liquidacion['pmc'];?>
                 </div>
               </td>

               <td>
                 <div class="subtitulo">
                   <?php echo $liquidacion['tipo_documento'].":";?>
                 </div>
               </td>
               <td>
                 <div class="valores">
                   <?php echo $liquidacion['ci'];?>
                 </div>
               </td>

               <td>
                 <div class="subtitulo">
                   Tipo Persona:
                 </div>
               </td>
               <td>
                 <div class="valores">
                   <?php echo $liquidacion['tipopersona'];?>
                 </div>
               </td>
             </tr>

             <tr>
               <td>
                 <div class="subtitulo">
                   NOMBRE:
                 </div>
               </td>
               <td colspan="5">
                 <div class="valores">
                   <?php echo $liquidacion['nombre'];?>
                 </div>
               </td>
             </tr>

             <tr>
               <td>
                 <div class="subtitulo">
                   DIRECCI&Oacute;N:
                 </div>
               </td>
               <td colspan="5">
                 <div class="valores">
                   <!-- Tomar en cuenta que no permite caracteres como º-->
                   <?php //$direccionprop = $texto = preg_replace('([^A-Za-z0-9 ])', '', $liquidacion['direccionprop']); ?>
                   <?php echo "ZONA: ".$liquidacion['barrioprop']." V&Iacute;A: ".$liquidacion['direccionprop']." Nro: ".$liquidacion['numeroprop'];?>
                 </div>
               </td>
             </tr>

             <tr>
               <td>
                 <div class="subtitulo">
                   TEL&Eacute;FONO:
                 </div>
               </td>
               <td colspan="5">
                 <div class="valores">
                   <?php echo $liquidacion['telefono'];?>
                 </div>
               </td>
             </tr>
           </table>
         </td>

         <td>
           <div class="titulo">
             Usuario: <?php echo $_SESSION['nombres_ss']." ".$_SESSION['apellidos_ss']; ?>
           </div>
           <table class="tabla_con_borde" width="50%">
             <tr>
               <td>
                 <div class="subtitulo">
                   GESTI&Oacute;N:
                 </div>
               </td>
               <td>
                 <div class="valores">
                   <?php echo $liquidacion['gestion'];?>
                 </div>
               </td>
             </tr>

             <tr>
               <td>
                 <div class="subtitulo">
                   EMITIDO:
                 </div>
               </td>
               <td>
                 <div class="valores">
                    <?php echo $liquidacion['fechliqui'];?>
                 </div>
               </td>
             </tr>

             <tr>
               <td>
                 <div class="subtitulo">
                   VENCE:
                 </div>
               </td>
               <td>
                 <div class="valores">
                    <?php echo $liquidacion['fechvenci'];?>
                 </div>
               </td>
             </tr>
           </table>
         </td>
       </tr>

       <tr>
         <td colspan="3">
           <div class="titulo">
             DATOS DEL INMUEBLE
           </div>
           <table class="tabla_con_borde">
             <tr>
                <td colspan="2">
                 <div class="valores">
                   &nbsp;&nbsp;&nbsp;&nbsp; C&Oacute;DIGO CATASTRAL:  <?php echo $liquidacion['codigo'];?>
                 </div>
               </td>

               <td>
                 <div class="subtitulo">
                   TIPO:
                 </div>
               </td>
               <td colspan="5">
                 <div class="valores">
                    <?php echo $liquidacion['tipoinm'];?>
                 </div>
               </td>
             </tr>

             <tr>
               <td>
                 <div class="subtitulo">
                   DIRECCI&Oacute;N:
                 </div>
               </td>
               <td>
                 <div class="valores">
                   <?php echo "ZONA: ".$liquidacion['idbarrioinm']." V&Iacute;A: ".$liquidacion['nombreviainm']." Nro: ".$liquidacion['numeroinm'];?>
                 </div>
               </td>

               <td>
                 <div class="subtitulo">
                   EDIFICIO:
                 </div>
               </td>
               <td>
                 <div class="valores">
                   ---
                 </div>
               </td>

               <td>
                 <div class="subtitulo">
                   PISO:
                 </div>
               </td>
               <td>
                 <div class="valores">
                   ---
                 </div>
               </td>

               <td>
                 <div class="subtitulo">
                   DEPTO:
                 </div>
               </td>
               <td>
                 <div class="valores">
                   ---
                 </div>
               </td>
             </tr>
           </table>
         </td>
       </tr>
       <tr>
         <td colspan="3">
           <div class="titulo">
             VALUACI&Oacute;N DEL TERRENO
           </div>
           <table class="tabla_con_borde_y_divisiones" width="100%">
             <tr>
               <td class="td_linea_punteada_abajo_derecha">
                 <div class="subtitulo_centrado">
                   SUPERFICIE <br>(M2)
                 </div>
               </td>
               <td class="td_linea_punteada_abajo_derecha">
                 <div class="subtitulo_centrado">
                   VALOR <br>BS/M2
                 </div>
               </td>
               <td class="td_linea_punteada_abajo_derecha">
                 <div class="subtitulo_centrado">
                   K1 (TIPO <br>DE V&Iacute;A)
                 </div>
               </td>
               <td class="td_linea_punteada_abajo_derecha">
                 <div class="subtitulo_centrado">
                   K2 <br>(TOPOGRAF&Iacute;A)
                 </div>
               </td>
               <td class="td_linea_punteada_abajo_derecha">
                 <div class="subtitulo_centrado">
                   K3 <br>(FORMA)
                 </div>
               </td>
               <td class="td_linea_punteada_abajo_derecha">
                 <div class="subtitulo_centrado">
                   K4 <br>(UBICACI&Oacute;N)
                 </div>
               </td>
               <td class="td_linea_punteada_abajo_derecha">
                 <div class="subtitulo_centrado">
                   K5 <br>(SERVICIOS)
                 </div>
               </td>
               <td class="td_linea_punteada_abajo_derecha">
                 <div class="subtitulo_centrado">
                   K6 <br>(FREN./FON.)
                 </div>
               </td>
               <td class="td_linea_punteada_abajo">
                 <div class="subtitulo_centrado">
                   VALOR DEL <br>TERRENO (Bs.)
                 </div>
               </td>
             </tr>

             <tr>
               <td class="td_linea_punteada_derecha">
                 <div class="valores_centrado">
                    <?php echo number_format($liquidacion['superficie'], 2, '.', ',');?>
                 </div>
               </td>
               <td class="td_linea_punteada_derecha">
                 <div class="valores_centrado">
                   <?php echo number_format($liquidacion['valunitario'], 2, '.', ',');?>
                 </div>
               </td>
               <td class="td_linea_punteada_derecha">
                 <div class="valores_centrado">
                   <?php echo number_format($liquidacion['k1_val'], 2, '.', ',');?>
                 </div>
               </td>
               <td class="td_linea_punteada_derecha">
                 <div class="valores_centrado">
                   <?php echo number_format($liquidacion['k2_val'], 2, '.', ',');?>
                 </div>
               </td>
               <td class="td_linea_punteada_derecha">
                 <div class="valores_centrado">
                   <?php echo number_format($liquidacion['k3_val'], 2, '.', ',');?>
                 </div>
               </td>
               <td class="td_linea_punteada_derecha">
                 <div class="valores_centrado">
                   <?php echo number_format($liquidacion['k4_val'], 2, '.', ',');?>
                 </div>
               </td>
               <td class="td_linea_punteada_derecha">
                 <div class="valores_centrado">
                   <?php echo number_format($liquidacion['k5_val'], 2, '.', ',');?>
                 </div>
               </td>
               <td class="td_linea_punteada_derecha">
                 <div class="valores_centrado">
                   <?php echo number_format($liquidacion['k6_val'], 2, '.', ',');?>
                 </div>
               </td>
               <td>
                 <div class="valores_centrado">
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
           <div class="titulo">
             VALUACI&Oacute;N DE LA CONSTRUCCI&Oacute;N
           </div>
           <table class="tabla_con_borde_y_divisiones" width="100%" >
             <tr>
               <td class="td_linea_punteada_abajo_derecha">
                 <div class="subtitulo_centrado">
                   SUPERFICIE
                 </div>
               </td>
               <td class="td_linea_punteada_abajo_derecha">
                 <div class="subtitulo_centrado">
                   VALOR <br>BS/M2
                 </div>
               </td>
               <td class="td_linea_punteada_abajo_derecha">
                 <div class="subtitulo_centrado">
                   KK1 <br>DEPRESIA.
                 </div>
               </td>
               <td class="td_linea_punteada_abajo_derecha">
                 <div class="subtitulo_centrado">
                   KK2 <br>CONSERVA
                 </div>
               </td>
               <td class="td_linea_punteada_abajo_derecha">
                 <div class="subtitulo_centrado">
                   KK3 <br>DESTINO / USO
                 </div>
               </td>
               <td class="td_linea_punteada_abajo">
                 <div class="subtitulo_centrado">
                   VALOR DE <br>CONSTRUCCI&Oacute;N
                 </div>
               </td>
             </tr>

           <?php
           $suma_valor_construccion = 0;
           while ($bloque = pg_fetch_array($bloques, NULL, PGSQL_BOTH)) {
           ?>
             <tr>
               <td class="td_linea_punteada_arriba_derecha">
                 <div class="valores_centrado">
                   <?php echo number_format($bloque['superficie'], 2, '.', ',');?>
                 </div>
               </td>
                 <td class="td_linea_punteada_arriba_derecha">
                 <div class="valores_centrado">
                   <?php echo number_format($bloque['valunitario'], 2, '.', ',');?>
                 </div>
               </td>
                 <td class="td_linea_punteada_arriba_derecha">
                 <div class="valores_centrado">
                   <?php echo number_format($bloque['kk1_val'], 2, '.', ',');?>
                 </div>
               </td>
                 <td class="td_linea_punteada_arriba_derecha">
                 <div class="valores_centrado">
                   <?php echo number_format($bloque['kk2_val'], 2, '.', ',');?>
                 </div>
               </td>
                 <td class="td_linea_punteada_arriba_derecha">
                 <div class="valores_centrado">
                   <?php echo number_format($bloque['kk3'], 2, '.', ',');?>
                 </div>
               </td>
               <td class="td_linea_punteada_arriba">
                 <div class="valores_centrado">
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

         <td rowspan="4" width="45%" style="vertical-align: bottom;">
           <div class="encabezado">
             SELLO
           </div>
         </td>
       </tr>

       <tr>
         <td>
           <div class="titulo">
             VALUACI&Oacute;N DEL INMUEBLE
           </div>
           <table class="tabla_con_borde_y_divisiones" width="100%">
             <tr>
               <td class="td_linea_punteada_abajo_derecha">
                 <div class="subtitulo_centrado">
                   VAL. DEL <br>TERRENO
                 </div>
               </td>
               <td class="td_linea_punteada_abajo_derecha">
                 <div class="subtitulo_centrado">
                   VAL. DE LA<br>CONSTRUCCI&Oacute;N
                 </div>
               </td>
               <td class="td_linea_punteada_abajo_derecha">
                 <div class="subtitulo_centrado">
                   VAL. <br>LIBROS
                 </div>
               </td>
               <td class="td_linea_punteada_abajo_derecha">
                 <div class="subtitulo_centrado">
                   EXCENCI&Oacute;N
                 </div>
               </td>
               <td class="td_linea_punteada_abajo_derecha">
                 <div class="subtitulo_centrado">
                   UFV
                 </div>
               </td>
               <td class="td_linea_punteada_abajo">
                 <div class="subtitulo_centrado">
                   BASE <br>IMPONIBLE
                 </div>
               </td>
             </tr>

             <tr>
               <td class="td_linea_punteada_derecha">
                 <div class="valores_centrado">
                   <?php echo number_format($liquidacion['valterr'], 2, '.', ',');?>
                 </div>
               </td>
               <td class="td_linea_punteada_derecha">
                 <div class="valores_centrado">
                   <?php echo number_format($suma_valor_construccion, 2, '.', ',');?>
                 </div>
               </td>
               <td class="td_linea_punteada_derecha">
                 <div class="valores_centrado">
                   0
                 </div>
               </td>
               <td class="td_linea_punteada_derecha">
                 <div class="valores_centrado">
                  <?php echo number_format($liquidacion['exencion'], 2, '.', ',');?>
                 </div>
               </td>
               <td class="td_linea_punteada_derecha">
                 <div class="valores_centrado">
                   <?php echo $liquidacion['ufv'];?>
                 </div>
               </td>
               <td>
                 <div class="valores_centrado">
                   <?php echo number_format($liquidacion['valterr']+$suma_valor_construccion, 2, '.', ',');?>
                 </div>
               </td>
             </tr>
           </table>
         </td>
       </tr>

        <tr>
          <td>
            <div class="titulo">
              C&Aacute;LCULO DE ACCESORIOS
            </div>
            <table class="tabla_con_borde_y_divisiones">
              <tr>
                <td>
                  <div class="subtitulo">
                    MONTO DETERMINADO:
                  </div>
                </td>
                <td>
                  <div class="valores">
                    <?php echo number_format($liquidacion['impuestoneto'], 2, '.', ',');?>
                  </div>
                </td>

                <td>
                  <div class="subtitulo">
                    MULTA INCUMPLIMIENTO:
                  </div>
                </td>
                <td>
                  <div class="valores">
                    <?php echo number_format($liquidacion['multamora'], 2, '.', ',');?>
                  </div>
                </td>
              </tr>

              <tr>
                <td>
                  <div class="subtitulo">
                    DESCUENTO PAGO T&Eacute;RMINO:
                  </div>
                </td>
                <td>
                  <div class="valores">
                    <?php echo number_format($liquidacion['pagotermino'], 2, '.', ',');?>
                  </div>
                </td>

                <td>
                  <div class="subtitulo">
                    REPOSICI&Oacute;N FORMULARIO:
                  </div>
                </td>
                <td>
                  <div class="valores">
                    <?php echo number_format($liquidacion['formulario'], 2, '.', ',');?>
                  </div>
                </td>
              </tr>

              <tr>
                <td>
                  <div class="subtitulo">
                    PAGOS ANTERIORES:
                  </div>
                </td>
                <td>
                  <div class="valores">
                    <?php echo number_format($liquidacion['pagoanterior'], 2, '.', ',');?>
                  </div>
                </td>

                <td>
                  <div class="subtitulo">
                    OTROS CR&Eacute;DITOS:
                  </div>
                </td>
                <td>
                  <div class="valores">
                    <?php echo number_format($liquidacion['totalcredito'], 2, '.', ',');?>
                  </div>
                </td>
              </tr>

              <tr>
                <td>
                  <div class="subtitulo">
                    MANTENIMIENTO DE VALOR:
                  </div>
                </td>
                <td>
                  <div class="valores">
                    <?php echo number_format($liquidacion['mantevalor'], 2, '.', ',');?>
                  </div>
                </td>

                <td>
                  <div class="subtitulo">
                    SALDO A FAVOR
                  </div>
                </td>
                <td>
                  <div class="valores">
                    <?php echo number_format($liquidacion['saldofavor'], 2, '.', ',');?>
                  </div>
                </td>
              </tr>

              <tr>
                <td>
                  <div class="subtitulo">
                    INTER&Eacute;S:
                  </div>
                </td>
                <td>
                  <div class="valores">
                    <?php echo number_format($liquidacion['interes'], 2, '.', ',');?>
                  </div>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <tr>
          <td>
            <?php
              $total = $liquidacion['impuestoneto'] - $liquidacion['pagotermino'] + $liquidacion['formulario'];
              $total_literal = convertir($total);
            ?>
            <div class="titulo">
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
