<?php
  $link=Conectarse();

  //Tomamos las variables necesarias
  $id = $_SESSION['id'];
  //$id = 2;

  //Tomamos los datos del certificado
  $sql  = "SELECT *
           FROM vista_certificadofrontera
           WHERE id = '$id'";
  $result = pg_query($link, $sql);
  $datos = pg_fetch_array($result, NULL, PGSQL_BOTH);

  //Tomamos la ip o el dominio para el codio qr
  $sql  = "SELECT ip_dominio
           FROM ips_dominios
           WHERE activo = '1'";
  $result = pg_query($link, $sql);
  $ip = pg_fetch_array($result, NULL, PGSQL_BOTH);
  $ip_dominio = $ip['ip_dominio'];

  //Creamos la funcion para dar formato con 0s al nro
  function formato_nro($nro){
    $digitos_nro = strlen($nro);
    switch ($digitos_nro) {
      case '1':
        $nro_final = "0000000".$nro;
        break;

      case '2':
        $nro_final = "000000".$nro;
        break;

      case '3':
        $nro_final = "00000".$nro;
        break;

      case '4':
        $nro_final = "0000".$nro;
        break;

      case '5':
        $nro_final = "000".$nro;
        break;

      case '6':
        $nro_final = "00".$nro;
        break;

      case '7':
        $nro_final = "0".$nro;
        break;

      default:
        $nro_final = $nro;
        break;
    }
    return $nro_final;
  }
?>

<html>
<?php include_once ("header_diseños.php"); ?>
 <body>
   <div id="watermark">
    <img src="imagenes/marca-agua.png" height="100%" width="100%" />
   </div>
    <?php //Encabezado ?>
     <table width="100%" border="0">
       <tr>
         <td width="24%">
           <div class="subtitulo_centrado">
             <p>
               <b>INSTITUTO GEOGR&Aacute;FICO MILITAR</b>
               <br>DEPARTAMENTO II - COMERCIALIZACI&Oacute;N
               <br><u>BOLIVIA</u>
             </p>
           </div>
         </td>
         <td width="50%">
           <div class="encabezado" style="color: #ff6666; text-align:right">
             <?php echo "No. ".formato_nro($id);?>
           </div>
         </td>
       </tr>
       <tr>
         <td colspan="2">
           <div class="encabezado">
             <p style="font-family:Algerian; font-size: 18px">CERTIFICADO DE UBICACI&Oacute;N GEOGR&Aacute;FICA</p>
           </div>
         </td>
       </tr>
     </table>

     <?php //Datos Solicitante ?>

     <div class="encabezado" style="text-align:left">
       DATOS PERSONALES DEL SOLICITANTE:
     </div>
      <table class="tabla_con_borde_y_divisiones" style="width: 100%">
        <tr>
          <td colspan="2">
            <div class="encabezado">
              SR/A <?php echo $datos['nombre'] ?>
            </div>
          </td>
          <td>
            <div class="encabezado">
              <?php echo $datos['ci'] ?>
            </div>
          </td>
          <td>
            <div class="encabezado">
              <?php echo $datos['exp'] ?>
            </div>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <div class="valores_centrado">
              NOMBRES Y APELLIDOS
            </div>
          </td>
          <td>
            <div class="valores_centrado">
              C.I.
            </div>
          </td>
          <td>
            <div class="valores_centrado">
              EXP
            </div>
          </td>
        </tr>

        <tr>
          <td>
            <div class="encabezado">
              <?php echo $datos['cargo'] ?>
            </div>
          </td>
          <td colspan="2">
            <div class="encabezado">
              <?php echo $datos['nombre_uuee'] ?>
            </div>
          </td>
          <td>
            <div class="encabezado">
              <?php echo $datos['se_uuee'] ?>
            </div>
          </td>
        </tr>
        <tr>
          <td>
            <div class="valores_centrado">
              CARGO
            </div>
          </td>
          <td colspan="2">
            <div class="valores_centrado">
              NOMBRE UU.EE.
            </div>
          </td>
          <td>
            <div class="valores_centrado">
              SIE
            </div>
          </td>
        </tr>
      </table>

      <br>
      <div class="encabezado" style="text-align:left">
        EL SUSCRITO JEFE DEL DEPARTAMENTO-II COMERCIALIZACI&Oacute;N DEL INSTITUTO GEOGR&Aacute;FICO MILITAR, A SOLICITUD DEL INTERESADO(A) CERTIFICA:
        QUE, LA UBICACI&Oacute;N GEOGR&Aacute;FICA DE LA MENCIONADA UNIDAD EDUCATIVA ES LA SIGUIENTE:
      </div>

      <table style="width: 100%">
        <tr>
          <td width="28%">
            <div class="valores_2">
              DEPARTAMENTO
            </div>
          </td>
          <td width="2%">
            <div class="valores_2">
              :
            </div>
          </td>
          <td width="70%">
            <div class="valores_2">
              <?php echo $datos['departamento']; ?>
            </div>
          </td>
        </tr>

        <tr>
          <td>
            <div class="valores_2">
              PROVINCIA
            </div>
          </td>
          <td>
            <div class="valores_2">
              :
            </div>
          </td>
          <td>
            <div class="valores_2">
              <?php echo $datos['provincia']; ?>
            </div>
          </td>
        </tr>

        <tr>
          <td>
            <div class="valores_2">
              MUNICIPIO
            </div>
          </td>
          <td>
            <div class="valores_2">
              :
            </div>
          </td>
          <td>
            <div class="valores_2">
              <?php echo $datos['municipio']; ?>
            </div>
          </td>
        </tr>

        <tr>
          <td>
            <div class="valores_2">
              LOCALIDAD
            </div>
          </td>
          <td>
            <div class="valores_2">
              :
            </div>
          </td>
          <td>
            <div class="valores_2">
              <?php echo $datos['localidad']; ?>
            </div>
          </td>
        </tr>

        <tr>
          <td>
            <div class="valores_2">
              LATITUD
            </div>
          </td>
          <td>
            <div class="valores_2">
              :
            </div>
          </td>
          <td>
            <div class="valores_2">
              <?php echo $datos['latitud_grados']."&deg; ".$datos['latitud_minutos']."' ".$datos['latitud_segundos']."'' ".$datos['latitud']; ?>
            </div>
          </td>
        </tr>

        <tr>
          <td>
            <div class="valores_2">
              LONGITUD
            </div>
          </td>
          <td>
            <div class="valores_2">
              :
            </div>
          </td>
          <td>
            <div class="valores_2">
              <?php echo $datos['longitud_grados']."&deg; ".$datos['longitud_minutos']."' ".$datos['longitud_segundos']."'' ".$datos['longitud']; ?>
            </div>
          </td>
        </tr>

        <tr>
          <td>
            <div class="valores_2">
              DISTANCIA APROXIMADA
            </div>
          </td>
          <td>
            <div class="valores_2">
              :
            </div>
          </td>
          <td>
            <div class="valores_2">
              <?php echo $datos['distancia']; ?>
            </div>
          </td>
        </tr>
      </table>

      <br>
      <div class="encabezado" style="text-align:left">
        DATOS ADMINISTRATIVOS:
      </div>
      <div class="valores_3">
        Memor&aacute;ndum de Designaci&oacute;n <b>No. <?php echo $datos['memo_designacion'];?></b>, &Iacute;tem <b>No. <?php echo $datos['item'];?></b>,
        Servicio <b>No. <?php echo $datos['servicio'];?></b>, emitido por la Direcci&oacute;n Distrital de Educaci&oacute;n <?php echo $datos['distrital'];?>.
      </div>

      <br>
      <div class="valores_3">
        <b>NOTA.-</b> El presente certificado, corresponde a solicitud, <b><u>PARA TR&Aacute;MITE DE CALIFICACI&Oacute;N DE A&Ntilde;OS DE FRONTERA, </u></b>
        los datos t&eacute;cnicos se obtuvieron del Departamentod de <?php echo $datos['departamento'];?> a escala 1:50.000
      </div>

      <br>
      <div class="valores_3">
        Es cuanto tengo a bien certificar en honor a la verdad y a petici&oacute;n escrita del/la interesado/a para los fines consiguientes.
      </div>

      <br>
      <div class="valores_3" style="text-align: right">
        <?php echo $datos['lugar_fecha']; ?>
      </div>


      <br>
      <table width="100%">
        <tr>
          <td width="50%">
            <div class="valores_3" style="text-align: center">
              <br><br><br><br><?php echo $datos['encargado_emision']; ?>
            </div>
            <div class="encabezado" style="text-align: center">
              ENCARGADO DE EMISI&Oacute;N DE DISTANCIAS
            </div>
          </td>
          <td style="text-align:right">
            <?php
            //CODIGO QR
                //set it to writable location, a place for temp generated PNG files
                $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'codigos_qr'.DIRECTORY_SEPARATOR;
                //html PNG location prefix
                $PNG_WEB_DIR = 'disenos_pdf/codigos_qr/';
                include "phpqrcode.php";

                $id_encode = base64_encode($id);
                $texto_qr = 'http://'.$ip_dominio.'/certificaciones/verificacion_certificado_frontera.php?id='.$id_encode;

                $_REQUEST['data'] = $texto_qr;
                $_REQUEST['size'] = 2;
                $_REQUEST['level'] = "M";

                //ofcourse we need rights to create temp dir
                if (!file_exists($PNG_TEMP_DIR))
                    mkdir($PNG_TEMP_DIR);
                $filename = $PNG_TEMP_DIR.'qr_certificado_frontera.png';
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
          </td>
        </tr>

        <tr>
          <td colspan="2">
            <div class="valores_3" style="text-align: center">
              <br><br><br><br><br><br><?php echo $datos['jefe_depto']; ?>
            </div>
            <div class="encabezado" style="text-align:center">
              JEFE DPTO. II RR.PP. - COMERCIALIZACI&Oacute;N
              <br>INSTITUTO GEOGR&Aacute;FICO MILITAR
            </div>
          </td>
        </tr>
      </table>
      <!--i class="fa fa-scissors"></i>-----------------------------------------------------------------------------------------------------------------------------------------<-->
      ___________________________________________________________________________________________
       <div class="valores_3" style="text-align: center">
         <?php echo $datos['igm_direccion']." ".$datos['igm_casilla']." - ".$datos['igm_fax']; ?>
         <br>
         <?php echo $datos['igm_telefonos']." ".$datos['igm_mail']; ?>
       </div>
  </body>
</html>

<style>
    #watermark {
      position: fixed;
      bottom:   10cm;
      left:     5.5cm;
      width:    8cm;
      height:   8cm;
      z-index:  -1000;
      opacity: 0.2; /* Firefox, Chrome, Safari, Opera, IE >= 9 (preview) */
      filter:alpha(opacity=2); /* for <= IE 8 */
    }
</style>
