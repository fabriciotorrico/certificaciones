<?php
  include("verificar_login.php");
  include("inc.config.php");

  //Incluimos el pdf.php para usar la función
  include ('pdf.php');

  //Creamos variables de sesion para poder ser leidas desde los diseños
  $_SESSION['id_codigo_catastro'] = $_POST['id'];
  $_SESSION['gestion'] = $_POST['gestion'];

  //Utilizamos la función para generar el pdf, le pasamos la ruta del diseño y el nombre del pdf
  generar_pdf("disenos_pdf/sello_pago.php", "sello_pago", "leter", "portrait");
?>
