<?php
  include("verificar_login.php");
  include("inc.config.php");

  //Incluimos el pdf.php para usar la función
  include ('pdf.php');

  //Creamos variables de sesion para poder ser leidas desde los diseños
  $_SESSION['tipo'] = $_POST['tipo'];

  //Establecemos rel valor de SOI
  if (isset($_POST['soi'])) {
    $_SESSION['soi'] = 1;
  }
  else {
    $_SESSION['soi'] = 0;
  }

  //Utilizamos la función para generar el pdf, le pasamos la ruta del diseño y el nombre del pdf
  generar_pdf("disenos_pdf/reporte_sincom.php", "Reporte SINCOM", "leter", "landscape");
?>
