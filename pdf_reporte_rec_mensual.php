<?php
  include("verificar_login.php");
  include("inc.config.php");

  //Incluimos el pdf.php para usar la función
  include ('pdf.php');

  //Creamos variables de sesion para poder ser leidas desde los diseños
  $_SESSION['pago'] = $_POST['pago'];
  $_SESSION['gestion'] = $_POST['gestion'];
  //echo $_POST['reporte_anual'];
  //Establecemos reporte anual o el mes seleccionado segun corresponda
  if (isset($_POST['reporte_anual'])) {
    $_SESSION['reporte_anual'] = $_POST['reporte_anual'];
  }
  else {
    $_SESSION['mes'] = $_POST['mes'];
  }

  //Utilizamos la función para generar el pdf, le pasamos la ruta del diseño y el nombre del pdf
  generar_pdf("disenos_pdf/reporte_rec_mensual.php", "Recaudacion Mensual", "leter", "landscape");
?>
