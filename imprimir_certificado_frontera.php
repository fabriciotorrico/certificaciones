<?php
  include("inc.config.php");
  echo base64_encode(1);
  //Decodificamos el id que se pasa por la url y lo pasamos como variable de sesion
   $_SESSION['id'] = base64_decode($_GET["id"]);

  //Incluimos el pdf.php para usar la función
  include ('pdf.php');

  //Utilizamos la función para generar el pdf, le pasamos la ruta del diseño y el nombre del pdf
  generar_pdf("disenos_pdf/certificado_frontera.php", "Certificado de Frontera", "leter", "portrait");
?>
