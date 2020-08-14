<?php
function Conectarse()
{
  if (!($link = pg_connect("host=localhost port=5432 dbname=certificaciones user=postgres password=fabricio"))){
      echo "Error conectando a la base de datos.";
      exit();
  }
  //session_start();
  return $link;
}
?>
