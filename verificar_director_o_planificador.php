<?
//***************************VALIDA QUE EL USUARIO SEA DIRECTOR O PLANIFICADOR**************************
session_start();
if($_SESSION['perfil_ss'] != "DIRECTOR" && $_SESSION['perfil_ss'] != "PLANIFICADOR"){
	header("Location: home.php");
}
////////////////////////////////////////
?>
