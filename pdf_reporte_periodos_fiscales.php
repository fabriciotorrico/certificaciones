<?php
  include("verificar_login.php");
  include("inc.config.php");

  //Incluimos el pdf.php para usar la función
  include ('pdf.php');

  //Creamos variables de sesion para poder ser leidas desde los diseños
  $_SESSION['pago'] = $_POST['pago'];
  $_SESSION['gestion'] = $_POST['gestion'];

  //Utilizamos la función para generar el pdf, le pasamos la ruta del diseño y el nombre del pdf
  generar_pdf("disenos_pdf/reporte_periodos_fiscales.php", "Periodos Fiscales", "leter", "portrait");
?>
