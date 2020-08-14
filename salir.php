<?php  
session_start();

//	SE BORRA LOS ELEMENTOS DE LA VARIABLE SESSION
$_SESSION = array();
//	SE DESTRUYE LA SESION
session_destroy();
// 	SE REDIRECCIONA A LA PAGINA DE LOGUEO
header ("Location: index.php");
?>
