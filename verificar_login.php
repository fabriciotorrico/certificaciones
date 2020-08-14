<?php
//***************************VALIDA SESSION**************************
session_start();
if($_SESSION['idusuario_ss'] == ""){
	header("Location: index.php");
}
////////////////////////////////////////
?>
