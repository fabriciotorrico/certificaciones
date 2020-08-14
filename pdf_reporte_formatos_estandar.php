<?php
  include("verificar_login.php");
  include("inc.config.php");

  //Incluimos el pdf.php para usar la función
  include ('pdf.php');

  //Creamos variables de sesion para poder ser leidas desde los diseños
  $_SESSION['recaudacion'] = $_POST['recaudacion'];
  $_SESSION['concepto'] = $_POST['concepto'];
  $_SESSION['id_usuario'] = $_POST['id_usuario'];
  $_SESSION['tipo'] = $_POST['tipo'];
  $_SESSION['fecha'] = $_POST['fecha'];

  //Utilizamos la función para generar el pdf, le pasamos la ruta del diseño y el nombre del pdf

  if ($_POST['tipo'] == "resumen") {
    generar_pdf("disenos_pdf/reporte_formatos_estandar_resumen.php", "Formatos Estandar Resumen", "leter", "portrait");
  }

?>
