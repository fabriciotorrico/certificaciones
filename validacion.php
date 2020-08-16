<?php
//date_default_timezone_set('UTC-4:00');
//	SE INICIA LA SESION Y SE CREAN VARIABLES DE SESION PARA EL USUARIO QUE INGRESA AL SISTEMA
session_start();
include("inc.config.php");
$link=Conectarse();

//Tomamos los datos necesarios
$username = $_POST['username'];
$contrasena	= $_POST['contrasena'];
$contrasena_md5 = md5($contrasena);

  //Verificar que los campos esten llenos
  if($username == "" or $contrasena == ""){
    header("Location:index.php");
  }
  else{
    //Tomamos los campos para verificar si el usuario y contraseña existen
  	$sql  = "SELECT *
  	         FROM usuario
             WHERE username = '$username' AND contrasena = '$contrasena_md5'";
  	$result = pg_query($link, $sql);

    //Si hay un registro (existe el usuario)
    if ($row = pg_fetch_array($result, NULL, PGSQL_BOTH)){
  	  $_SESSION['idusuario_ss'] = $row['id'];
  		$_SESSION['nombres_ss'] = $row['nombres'];
  		$_SESSION['apellidos_ss'] = $row['apellidos'];

      //Redrieccionamos al menu
      header("Location: menu.php");
  	}
    //En caso que no exista, reenviamos a la pagina de logeo
    else {
  		//	SE REDIRECCION AL LOGIN DEL SISTEMA
      echo $_SESSION["usr_pw_incorrecto"] = 1;
      header("Location: index.php");
  	}
  }
?>
