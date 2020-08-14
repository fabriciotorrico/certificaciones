<?php
// Cargamos la librería dompdf que hemos instalado en la carpeta dompdf
require ('dompdf/autoload.inc.php');
use Dompdf\Dompdf;

function generar_pdf($file_content, $file_name, $tamaño_hoja, $posicion_hoja)
{
  // Instanciamos un objeto de la clase DOMPDF.
  $pdf = new DOMPDF();
  // Definimos el tamaño y orientación del papel que queremos.
  //$pdf->set_paper("leter", "portrait");
  $pdf->set_paper($tamaño_hoja, $posicion_hoja);

  ob_start();
  require_once ($file_content);
  $html = ob_get_clean();

  set_time_limit(3000);
  //Establecemos el archivo que tiene elcontenido del pdf
  //$html = file_get_contents($file_content);
  // Cargamos el contenido HTML.
  //Se cambia decode por encode para solucionar caracteres extraños
  //$pdf->load_html(utf8_decode($html));
  $pdf->load_html(utf8_encode($html));
  //$pdf->load_html(utf8_decode($file_content));
  // Renderizamos el documento PDF.
  $pdf->render();


//$pdf->get_page_count();
//  $w = $pdf->get_width();
  //$h = $pdf->get_height();
  //$font = Font_Metrics::get_font("helvetica", "bold");
  //$pdf->page_text(120, 40, "Header: {PAGE_NUM} of {PAGE_COUNT}", "helvetica", 6, array(0,0,0));


  // Enviamos el fichero PDF al navegador.
  $pdf->stream($file_name,array("Attachment"=>0));


}
?>
