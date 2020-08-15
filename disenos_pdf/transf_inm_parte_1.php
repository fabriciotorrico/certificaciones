<?php
  //include("verificar_login.php");
  //include("inc.config.php");
  $link=Conectarse();

  //Tomamos las variables necesarias
  $id_inmueble = $_SESSION['id_inmueble'];
  $ci_vendedor = $_SESSION['ci_vendedor'];
  $ci_comprador = $_SESSION['ci_comprador'];
  $bolivianos = $_SESSION['bolivianos'];
  $dolares = $_SESSION['dolares'];
  $ddrr = $_SESSION['ddrr'];
  $minuta = $_SESSION['minuta'];
  $monto_transferencia = $_SESSION['monto_transferencia'];

  //Tomamos los datos del vendedor
  $sql  = "SELECT *
           FROM buscar
           WHERE ci = '$ci_vendedor'";
  $result = pg_query($link, $sql);
  $vendedor = pg_fetch_array($result, NULL, PGSQL_BOTH);

  //Tomamos los datos del comprador
  $sql  = "SELECT *
           FROM buscar
           WHERE ci = '$ci_comprador'";
  $result = pg_query($link, $sql);
  $comprador = pg_fetch_array($result, NULL, PGSQL_BOTH);

  //Tomamos los datos de la transferencia
  $sql  = "SELECT *
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
  $transferencia = pg_fetch_array($result, NULL, PGSQL_BOTH);

  //Tomamos los datos de los bloques
  $sql  = "SELECT *
           FROM transferencia_construccion
           WHERE id_inmueble = '$id_inmueble'
            AND id_transferencia = '$transferencia[idtransf]'
            AND activo = '1'";
  $bloques = pg_query($link, $sql);

  //Creamos la funcion para dar formato con 0s al nro de transferencia
  function formato_nro_transferencia($transferencia){
    $digitos_transferencia = strlen($transferencia);
    switch ($digitos_transferencia) {
      case '1':
        $nro_transferencia = "0000000".$transferencia;
        break;

      case '2':
        $nro_transferencia = "000000".$transferencia;
        break;

      case '3':
        $nro_transferencia = "00000".$transferencia;
        break;

      case '4':
        $nro_transferencia = "0000".$transferencia;
        break;

      case '5':
        $nro_transferencia = "000".$transferencia;
        break;

      case '6':
        $nro_transferencia = "00".$transferencia;
        break;

      case '7':
        $nro_transferencia = "0".$transferencia;
        break;

      default:
        $nro_transferencia = $transferencia;
        break;
    }
    return $nro_transferencia;
  }
?>
<html>
<?php include_once ("header_diseños.php"); ?>
<?php include 'conversor_numeral_literal.php';?>
 <body>
   <?php
   //Repetimos n veces el mismo formato, para recortar
   for ($i=1; $i <= 2; $i++) {
   ?>
     <table width="90%" border="0">
       <tr>
         <td width="10%" rowspan="2">
           <img class="foto" src="imagenes/logos_municipios/logo_id_municipio_1.jpg" width="130px" height="130px"alt="SIIM WEB">
         </td>
         <td width="490px">
           <div class="encabezado">
             IMPUESTO MUNICIPAL A LA TRANSFERENCIA DE BIENES INMUEBLES</p>
             <p>Form. 501 - I.M.T.B.I.</p>
           </div>
         </td>
         <td width="60px">
           <div class="encabezado" style="color: #ff6666">
             <?php echo formato_nro_transferencia($transferencia['numero_transferencia']); ?>
           </div>
         </td>
       </tr>

       <tr>
         <td colspan="2">
           <div class="titulo">
              DATOS DEL VENDEDOR O CEDENTE &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              Usuario: <?php echo $_SESSION['nombres_ss']." ".$_SESSION['apellidos_ss']; ?>
           </div>
           <table class="tabla_con_borde_y_divisiones" width=100%>
             <tr>
               <td class="td_linea_punteada_abajo_derecha">
                 <div class="subtitulo_centrado_negrita">
                   PMC
                 </div>
               </td>

               <td class="td_linea_punteada_abajo_derecha">
                 <div class="subtitulo_centrado_negrita">
                   C&Eacute;DULA DE IDENTIDAD
                 </div>
               </td>

               <td class="td_linea_punteada_abajo_derecha">
                 <div class="subtitulo_centrado_negrita">
                   TEL&Eacute;FONO
                 </div>
               </td>

               <td class="td_linea_punteada_abajo_derecha">
                 <div class="subtitulo_centrado_negrita">
                   FECHA NAC.
                 </div>
               </td>

               <td class="td_linea_punteada_abajo">
                 <div class="subtitulo_centrado_negrita">
                   NIT
                 </div>
               </td>
             </tr>

             <tr>
               <td class="td_linea_punteada_derecha">
                 <div class="valores_centrado">
                   <?php if (isset($vendedor['pmc'])) {echo $vendedor['pmc'];}?>
                 </div>
               </td>

               <td class="td_linea_punteada_derecha">
                 <div class="valores_centrado">
                   <?php if (isset($vendedor['ci'])) {echo $vendedor['ci'];}?>
                 </div>
               </td>

               <td class="td_linea_punteada_derecha">
                 <div class="valores">
                   <?php if (isset($vendedor['telefono_propietario'])) {echo $vendedor['telefono_propietario'];}?>
                 </div>
               </td>

               <td class="td_linea_punteada_derecha">
                 <div class="valores">
                   <?php if (isset($vendedor['fecha_nacimiento_propietario'])) {echo $vendedor['fecha_nacimiento_propietario'];}?>
                 </div>
               </td>

               <td>
                 <div class="valores">
                   <?php if (isset($vendedor['nit_propietario'])) {echo $vendedor['nit_propietario'];}?>
                 </div>
               </td>
             </tr>

             <tr>
               <td colspan="5">
                 <div class="subtitulo_izquierda_negrita">
                   NOMBRE O RAZ&Oacute;N SOCIAL:
                 </div>
               </td>
             </tr>
             <tr>
               <td colspan="5">
                 <div class="valores">
                   <?php if (isset($vendedor['propietario'])) {echo $vendedor['propietario'];}?>
                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                   <?php if (isset($vendedor['tipo_persona_propietario'])) {echo " ".$vendedor['tipo_persona_propietario'];}?>
                 </div>
               </td>
             </tr>

             <tr>
               <td colspan="5">
                 <div class="subtitulo_izquierda_negrita">
                   NOMBRE DEL REPRESENTANTE LEGAL:
                 </div>
               </td>
             </tr>
             <tr>
               <td colspan="5">
                 <div class="valores">
                   <?php if (isset($vendedor['rep_legal'])) {echo $vendedor['rep_legal'];}?>
                 </div>
               </td>
             </tr>
           </table>
         </td>
       </tr>
     </table>

     <table width="700px" border="0">
       <tr>
         <td rowspan="3" width="1px">
           <div class="encabezado">
             <?php
              if ($i == 1) {echo "N<br>O<br>T<br>A<br>R<br>I<br>A";}//{echo "<p class='texto_vertical'> NOTARIA <p>";}
              else {echo "C<br>O<br>M<br>P<br>R<br>A<br>D<br>O<br>R";}//{echo "<p class='texto_vertical'> COMPRADOR <p>";}
             ?>
           </div>
         </td>
         <td colspan="2">
           <div class="titulo">
             DATOS DEL COMPRADOR O BENEFICIARIO
           </div>
           <table class="tabla_con_borde" width=100%>
             <tr>
               <td class="td_linea_punteada_abajo_derecha">
                 <div class="subtitulo_centrado_negrita">
                   PMC
                 </div>
               </td>

               <td class="td_linea_punteada_abajo_derecha">
                 <div class="subtitulo_centrado_negrita">
                   C&Eacute;DULA DE IDENTIDAD
                 </div>
               </td>

               <td class="td_linea_punteada_abajo_derecha">
                 <div class="subtitulo_centrado_negrita">
                   TEL&Eacute;FONO
                 </div>
               </td>

               <td class="td_linea_punteada_abajo_derecha">
                 <div class="subtitulo_centrado_negrita">
                   FECHA NAC.
                 </div>
               </td>

               <td class="td_linea_punteada_abajo">
                 <div class="subtitulo_centrado_negrita">
                   NIT
                 </div>
               </td>
             </tr>

             <tr>
               <td class="td_linea_punteada_derecha">
                 <div class="valores_centrado">
                   <?php if (isset($comprador['pmc'])) {echo $comprador['pmc'];}?>
                 </div>
               </td>

               <td class="td_linea_punteada_derecha">
                 <div class="valores_centrado">
                   <?php if (isset($comprador['ci'])) {echo $comprador['ci'];}?>
                 </div>
               </td>

               <td class="td_linea_punteada_derecha">
                 <div class="valores">
                   <?php if (isset($comprador['telefono_propietario'])) {echo $comprador['telefono_propietario'];}?>
                 </div>
               </td>

               <td class="td_linea_punteada_derecha">
                 <div class="valores">
                   <?php if (isset($comprador['fecha_nacimiento_propietario'])) {echo $comprador['fecha_nacimiento_propietario'];}?>
                 </div>
               </td>

               <td>
                 <div class="valores">
                   <?php if (isset($comprador['nit_propietario'])) {echo $comprador['nit_propietario'];}?>
                 </div>
               </td>
             </tr>

             <tr>
               <td colspan="5">
                 <div class="subtitulo_izquierda_negrita">
                   NOMBRE O RAZ&Oacute;N SOCIAL:
                 </div>
               </td>
             </tr>
             <tr>
               <td colspan="5">
                 <div class="valores">
                   <?php if (isset($comprador['propietario'])) {echo $comprador['propietario'];}?>
                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                   <?php if (isset($comprador['tipo_persona_propietario'])) {echo " ".$comprador['tipo_persona_propietario'];}?>
                 </div>
               </td>
             </tr>
           </table>
         </td>
       </tr>

       <tr>
         <td colspan="2">
           <div class="titulo">
             IDENTIFICACI&Oacute;N Y TIPIFICACI&Oacute;N DEL INMUEBLE QUE SE TRANSFIERE
           </div>
           <table class="tabla_con_borde" width=100%>
             <tr>
               <td class="td_linea_abajo" colspan="3">
                 <div class="subtitulo_izquierda_negrita">
                   DIRECCI&Oacute;N
                 </div>
               </td>
             </tr>

             <tr>
               <td colspan="2">
                 <div class="subtitulo_izquierda_negrita">
                   ZONA
                 </div>
               </td>
               <td>
                 <div class="subtitulo_izquierda_negrita">
                   BARRIO
                 </div>
               </td>
             </tr>
             <tr>
               <td>
                 <div class="valores">
                   <?php if (isset($inmueble['zona'])) {echo $inmueble['zona'];} ?>
                 </div>
               </td>
               <td colspan="2">
                 <div class="valores">
                   <?php if (isset($inmueble['barrio'])) {echo $inmueble['barrio'];} ?>
                   <?php if (isset($inmueble[''])) {echo $inmueble[''];} ?>
                 </div>
               </td>
             </tr>

             <tr>
               <td colspan="3">
                 <div class="subtitulo_izquierda_negrita">
                   DIRECCI&Oacute;N
                 </div>
               </td>
             </tr>
             <tr>
               <td colspan="3">
                 <div class="valores">
                   <?php if (isset($inmueble['direccion'])) {echo $inmueble['direccion'];} ?>
                 </div>
               </td>
             </tr>

              <tr>
                <td class="td_linea_arriba_derecha" width="12%">
                  <div class="subtitulo_centrado_negrita">
                    DATOS DEL TERRENO
                  </div>
                </td>

                <td class="td_linea_arriba_derecha" width="10%">
                  <div class="subtitulo_centrado_negrita">
                    C&Oacute;DIGO CATASTRAL
                  </div>
                </td>

                <td class="td_linea_arriba" width="70%">
                  <div class="subtitulo_centrado_negrita">
                    DATOS DE LAS CONSTRUCCIONES
                  </div>
                </td>
              </tr>
              <tr>
                <td class="td_linea_arriba_derecha" rowspan="3">
                  <table>
                    <tr>
                      <td class="subtitulo">
                        SUPERFICIE:
                      </td>
                      <td class="valores">
                        <?php if (isset($transferencia['supterreno'])) {echo $transferencia['supterreno'];} ?>
                      </td>
                    </tr>

                    <tr>
                      <td class="subtitulo">
                        VALOR BS/M2:
                      </td>
                      <td class="valores">
                        <?php if (isset($transferencia['valuniterr'])) {echo $transferencia['valuniterr'];} ?>
                      </td>
                    </tr>

                    <tr>
                      <td class="subtitulo">
                        K1 (Tipo de V&iacute;a):
                      </td>
                      <td class="valores">
                        <?php if (isset($transferencia['k1'])) {echo $transferencia['k1'];} ?>
                      </td>
                    </tr>

                    <tr>
                      <td class="subtitulo">
                        K2 (Topograf&iacute;a):
                      </td>
                      <td class="valores">
                        <?php if (isset($transferencia['k2'])) {echo $transferencia['k2'];} ?>
                      </td>
                    </tr>

                    <tr>
                      <td class="subtitulo">
                        K3 (Forma):
                      </td>
                      <td class="valores">
                        <?php if (isset($transferencia['k3'])) {echo $transferencia['k3'];} ?>
                      </td>
                    </tr>

                    <tr>
                      <td class="subtitulo">
                        K4 (Ubicaci&oacute;n):
                      </td>
                      <td class="valores">
                        <?php if (isset($transferencia['k4'])) {echo $transferencia['k4'];} ?>
                      </td>
                    </tr>

                    <tr>
                      <td class="subtitulo">
                        K5 (Servicios):
                      </td>
                      <td class="valores">
                        <?php if (isset($transferencia['k5'])) {echo $transferencia['k5'];} ?>
                      </td>
                    </tr>

                    <tr>
                      <td class="subtitulo">
                        K6 (Fren / Fon):
                      </td>
                      <td class="valores">
                        <?php if (isset($transferencia['k6'])) {echo $transferencia['k6'];} ?>
                      </td>
                    </tr>
                  </table>
                </td>

                <td class="td_linea_arriba_derecha">
                  <div class="valores_centrado">
                    <?php if (isset($transferencia['codigo'])) {echo $transferencia['codigo'];} ?>
                  </div>
                </td>

                <td class="td_linea_arriba" rowspan="3">
                  <table>
                    <tr>
                      <td class="td_linea_punteada_abajo_derecha"><div class="subtitulo_centrado_negrita">Bloque</div></td>
                      <td class="td_linea_punteada_abajo_derecha"><div class="subtitulo_centrado_negrita">Superficie</div></td>
                      <td class="td_linea_punteada_abajo_derecha"><div class="subtitulo_centrado_negrita">Valor <br>Bs./M2</div></td>
                      <td class="td_linea_punteada_abajo_derecha"><div class="subtitulo_centrado_negrita">KK1 <br>Depreciaci&oacute;n</div></td>
                      <td class="td_linea_punteada_abajo_derecha"><div class="subtitulo_centrado_negrita">KK2 <br>Conservaci&oacute;n</div></td>
                      <td class="td_linea_punteada_abajo"><div class="subtitulo_centrado_negrita">KK3 <br>Destino / Uso</div></td>
                    </tr>
                    <?php
                     $nro_bloque = 1;
                     while ($bloque = pg_fetch_array($bloques, NULL, PGSQL_BOTH)) {
                       ?>
                       <tr>
                         <td class="td_linea_punteada_arriba_derecha"><div class="valores_centrado"><?php echo "B".$nro_bloque;;?></div></td>
                         <td class="td_linea_punteada_arriba_derecha"><div class="valores_centrado"><?php echo $bloque['superficie'];?></div></td>
                         <td class="td_linea_punteada_arriba_derecha"><div class="valores_centrado"><?php echo $bloque['valor_unitario'];?></div></td>
                         <td class="td_linea_punteada_arriba_derecha"><div class="valores_centrado"><?php echo $bloque['kk1_val'];?></div></td>
                         <td class="td_linea_punteada_arriba_derecha"><div class="valores_centrado"><?php echo $bloque['kk2_val'];?></div></td>
                         <td class="td_linea_punteada_arriba"><div class="valores_centrado"><?php echo $bloque['kk3_val'];?></div></td>
                       </tr>
                       <?php
                      $nro_bloque = $nro_bloque + 1;
                    }
                    ?>
                  </table>
                </td>
              </tr>

              <tr>
                <td class="td_linea_arriba_derecha">
                  <div class="subtitulo_centrado_negrita">
                    FOLIO REAL - DDRR
                  </div>
                </td>
              </tr>

              <tr>
                <td class="td_linea_arriba_derecha">
                  <div class="valores_centrado">
                    <?php if (isset($transferencia['ddrr'])) {echo $transferencia['ddrr'];} ?>
                  </div>
                </td>
              </tr>
            </table>
         </td>
       </tr>

       <tr>
         <td>
           <div class="titulo">
             DETERMINACI&Oacute;N DE LA BASE IMPONIBLE Y DEL IMPUESTO
           </div>
           <table class="tabla_con_borde">
             <tr>
               <td width="25%">
                 <div class="subtitulo">
                   MINUTA:
                 </div>
               </td>
               <td width="25%">
                 <div class="valores">
                   <?php if (isset($transferencia['fechminuta'])) {echo $transferencia['fechminuta'];} ?>
                 </div>
               </td>

               <td rowspan="6">
                 &nbsp;&nbsp;&nbsp;
               </td>

               <td width="25%">
                 <div class="subtitulo">
                   BASE IMPONIBLE:
                 </div>
               </td>
               <td width="25%">
                 <div class="valores">
                   <?php if (isset($transferencia['baseimponible'])) {echo number_format($transferencia['baseimponible'], 2, '.', ',');} ?>
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
                   <?php if (isset($transferencia['fechliqui'])) {echo $transferencia['fechliqui'];} ?>
                 </div>
               </td>

               <td>
                 <div class="subtitulo">
                   IMPUESTO NETO:
                 </div>
               </td>
               <td>
                 <div class="valores">
                   <?php if (isset($transferencia['impuestoneto'])) {echo number_format($transferencia['impuestoneto'], 2, '.', ',');} ?>
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
                   <?php if (isset($transferencia['fechvenci'])) {echo $transferencia['fechvenci'];} ?>
                 </div>
               </td>

               <td>
                 <div class="subtitulo">
                   MANT. VALOR:
                 </div>
               </td>
               <td>
                 <div class="valores">
                   <?php if (isset($transferencia['mantevalor'])) {echo number_format($transferencia['mantevalor'], 2, '.', ',');} ?>
                 </div>
               </td>
             </tr>

             <tr>
               <td>
                 <div class="subtitulo">
                   UFV:
                 </div>
               </td>
               <td>
                 <div class="valores">
                   <?php if (isset($transferencia['ufv'])) {echo $transferencia['ufv'];} ?>
                 </div>
               </td>

               <td>
                 <div class="subtitulo">
                   SUB. TOTAL:
                 </div>
               </td>
               <td>
                 <div class="valores">
                   <?php if (isset($transferencia['montotranfe'])) {echo number_format($transferencia['montotranfe'], 2, '.', ',');} ?>
                 </div>
               </td>
             </tr>

             <tr>
               <td>
                 <div class="subtitulo">
                   VALOR VENTA:
                 </div>
               </td>
               <td>
                 <div class="valores">
                   <?php if (isset($transferencia['montobs'])) {echo "Bs. ".$transferencia['montobs'];} ?>
                 </div>
               </td>

               <td>
                 <div class="subtitulo">
                   INTERESES:
                 </div>
               </td>
               <td>
                 <div class="valores">
                   <?php if (isset($transferencia['interes'])) {echo $transferencia['interes'];} ?>
                 </div>
               </td>
             </tr>

             <tr>
               <td>
                 <div class="subtitulo">
                   ALICUOTA:
                 </div>
               </td>
               <td>
                 <div class="valores">
                   <?php if (isset($transferencia['alicuota'])) {echo ($transferencia['alicuota']*100)."%";} ?>
                 </div>
               </td>

               <td>
                 <div class="subtitulo">
                   DEB. FORMALES:
                 </div>
               </td>
               <td>
                 <div class="valores">
                   <?php if (isset($transferencia['deberesfor'])) {echo $transferencia['deberesfor'];} ?>
                 </div>
               </td>
             </tr>
           </table>
           <?php
             $total = $transferencia['montotranfe'];
             $total_literal = convertir($total);
           ?>
           <div class="titulo">
             TOTAL: <?php echo number_format($total, 2, '.', ',');?> SON: <?php echo $total_literal;?>
           </div>
         </td>

         <td>
           <div class="encabezado">
             SELLO
           </div>
         </td>
       </tr>
     </table>
     <?php if ($i < 2) {
      ?>
      <p style="page-break-after: always;"></p>
      <!--i class="fa fa-scissors"></i>-----------------------------------------------------------------------------------------------------------------------------------------<-->
      <?php
    }
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
